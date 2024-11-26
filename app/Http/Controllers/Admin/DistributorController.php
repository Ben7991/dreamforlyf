<?php

namespace App\Http\Controllers\Admin;

use App\BusinessLogic\BvCycle;
use App\BusinessLogic\CashBack;
use App\BusinessLogic\LeadershipBonus;
use App\BusinessLogic\ReferralBonus;
use App\Models\RegistrationPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DistributorRequest;
use App\Http\Requests\Admin\EditDistributorRequest;
use App\Mail\AccountRegistration;
use App\Mail\ResetWithdrawalPin;
use App\Models\BonusWithdrawal;
use App\Models\BonusWithdrawalStatus;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\PackageType;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Referral;
use App\Models\ReferralLeg;
use App\Models\Stockist;
use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Models\Upline;
use App\Models\User;
use App\Models\UserType;
use App\Utility\PasswordGenerator;
use App\Utility\PinGenerator;
use App\View\Components\Layout\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DistributorController extends Controller
{
    public function index() {
        $distributors = User::where("role", UserType::DISTRIBUTOR->name)->where("id", "<>", "DFL1000002")->get();
        $totalDistributors = count($distributors);
        $suspendedDistributors = User::where("role", UserType::DISTRIBUTOR->name)->where("status", "suspended")->count();

        return view("admin.distributor.index", [
            "distributors" => $distributors,
            "totalDistributors" => $totalDistributors,
            "suspendDistributors" => $suspendedDistributors
        ]);
    }

    public function create() {
        return view("admin.distributor.create", [
            "packages" => RegistrationPackage::all(),
            "stockists" => Stockist::all()
        ]);
    }

    public function show($locale, $id) {
        try {
            $user = User::findOrFail($id);
            $distributor = $user->distributor;

            $personalWallet = Transaction::where("portfolio", TransactionPortfolio::PERSONAL_WALLET->name)
                ->where("distributor_id", $distributor->id)->sum("amount");

            $leadershipWallet = Transaction::where("portfolio", TransactionPortfolio::LEADERSHIP_WALLET->name)
                ->where("distributor_id", $distributor->id)->sum("amount");

            $totalWithdrawals = BonusWithdrawal::where("distributor_id", $distributor->id)->sum("amount");

            return view("admin.distributor.show", [
                "user" => $user,
                "personalWallet" => $personalWallet,
                "leadershipWallet" => $leadershipWallet,
                "totalWithdrawals" => $totalWithdrawals
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back();
        }
    }

    public function store(DistributorRequest $request, $locale) {
        $validated = $request->validated();
        $generatedPassword = PasswordGenerator::generate();

        try {
            $existingReferral = User::findOrFail($validated["referral_id"]);
            $referralUpline = $existingReferral->upline ? $existingReferral->upline : Upline::create(["user_id", $existingReferral->id]);
            $referralPortfolio = $existingReferral->distributor->portfolio;
            $existingRegistrationPackage = RegistrationPackage::findOrFail($validated["package_id"]);

            if ($referralPortfolio->current_balance < $existingRegistrationPackage->price) {
                throw new Exception("Insufficient personal wallet of referral portfolio");
            }

            $existingUpline = User::findOrFail($validated["upline_id"]);
            $upline = $existingUpline->upline ? $existingUpline->upline : Upline::create(["user_id", $existingUpline->id]);

            if (count($upline->distributors) === 2) {
                throw new Exception("Upline has reached maximum downlines");
            }

            $existingPackageType = PackageType::findOrFail($validated["type"]);
            $purchasedProducts = DB::table("product_package_type")->where("type_id", $existingPackageType->id)->get();

            $storedDistributor = $this->storeUser($validated, $generatedPassword, $upline, $existingRegistrationPackage, $referralUpline);
            $this->storeOrder($purchasedProducts, $storedDistributor, $existingRegistrationPackage, $validated["stockist_id"]);
            $referralPortfolio->subtractPurchaseAmount($existingRegistrationPackage->price);

            CashBack::giveCashBackBonus($storedDistributor, $existingRegistrationPackage);
            ReferralBonus::distributeBonus($referralUpline, $existingRegistrationPackage);
            BvCycle::initialCycle($upline, $existingRegistrationPackage->bv_point, $storedDistributor->leg);

            Mail::to($validated["email"])->send(
                new AccountRegistration($validated["name"], $validated["email"], $generatedPassword)
            );
            return redirect()->back()->with([
                "class" => "success",
                "message" => "Added new distributor successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }

    private function storeUser($data, $password, $upline, $package, $referralUpline) {
        $storedUser = User::create([
            "id" => User::nextId(),
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => $password,
            "role" => UserType::DISTRIBUTOR->name
        ]);

        $leg = $upline->nextLeg();

        $storedDistributor = Distributor::create([
            "upline_id" => $upline->id,
            "leg" => $leg,
            "registration_package_id" => $package->id,
            "country" => $data["country"],
            "city" => $data["city"],
            "user_id" => $storedUser->id,
            "phone_number" => $data["phone_number"],
            "wave" => $data["wave"],
            "next_maintenance_date" => (new Carbon())->addMonths(3)
        ]);

        Portfolio::create([
            "distributor_id" => $storedDistributor->id
        ]);

        Referral::create([
            "upline_id" => $referralUpline->id,
            "distributor_id" => $storedDistributor->id,
            "leg" => $leg === "1st" ? ReferralLeg::LEFT->name : ReferralLeg::RIGHT->name
        ]);

        return $storedDistributor;
    }

    private function storeOrder($products, $distributor, $registrationPackage, $stockistId) {
        $storedOrder = Order::create([
            "amount" => $registrationPackage->price,
            "distributor_id" => $distributor->id,
            "order_type" => OrderType::REGISTRATION->name,
            "stockist_id" => $stockistId
        ]);

        foreach($products as $product) {
            DB::table("order_items")->insert([
                "order_id" => $storedOrder->id,
                "product_id" => $product->product_id,
                "quantity" => $product->quantity
            ]);

            $updatedProduct = Product::find($product->product_id);
            $updatedProduct->quantity -= $product->quantity;
            $updatedProduct->save();
        }
    }

    public function update(EditDistributorRequest $request, $locale, $id) {
        $validated = $request->validated();

        try {
            $user = User::find($id);
            $user->name = $validated["name"];
            $user->email = $validated["email"];
            $user->status = $validated["action"];
            $user->save();

            $distributor = $user->distributor;
            $distributor->phone_number = $validated["phone_number"];
            $distributor->country = $validated["country"];
            $distributor->city = $validated["city"];
            $distributor->wave = $validated["wave_number"];
            $distributor->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully updated distributor details"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function wallet(Request $request, $locale, $id) {
        $validated = $request->validate([
            "wallet" => "bail|required|numeric"
        ]);

        try {
            $user = User::findOrFail($id);
            $portfolio = $user->distributor->portfolio;
            $portfolio->current_balance += (int)$validated["wallet"];
            $portfolio->save();

            Transaction::create([
                "amount" => $validated["wallet"],
                "distributor_id" => $user->distributor->id,
                "portfolio" => TransactionPortfolio::CURRENT_BALANCE->name,
                "transaction_type" => TransactionType::DEPOSIT->name,
            ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully transferred wallet to distributor"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function bonus_withdrawals() {
        $totalWithdrawalAmount = $totalDeduction = $totalAmountToPay = 0;

        $currentDay = Carbon::now()->dayOfWeek;
        $tuesdayDate = "";
        $thursdayDate = "";
        $rangeResults = null;

        if ($currentDay >= Carbon::TUESDAY) {
            $dayDifference = $currentDay - 2;

            if ($dayDifference === 0) {
                $tuesdayDate = Carbon::now()->format("Y-m-d");
                $thursdayDate = Carbon::now()->copy()->addDays(2)->format("Y-m-d");
            }
            else {
                $startDate = Carbon::now()->subDays($dayDifference);
                $tuesdayDate = $startDate->format("Y-m-d");
                $thursdayDate = $startDate->copy()->addDays(2)->format("Y-m-d");
            }

            $rangeResults = BonusWithdrawal::whereBetween("created_at", [$tuesdayDate, $thursdayDate])->get();
            $calculatedValues = $this->getWithdrawalSummary($rangeResults);
            $totalAmountToPay = $calculatedValues["totalAmountToPay"];
            $totalWithdrawalAmount = $calculatedValues["totalWithdrawalAmount"];
            $totalDeduction = $calculatedValues["totalDeduction"];
        }

        return view("admin.bonus-withdrawals", [
            "withdrawals" => BonusWithdrawal::all(),
            "total" => BonusWithdrawal::count(),
            "pending" => BonusWithdrawal::where("status", BonusWithdrawalStatus::PENDING->name)->count(),
            "approved" => BonusWithdrawal::where("status", BonusWithdrawalStatus::APPROVED->name)->count(),
            "totalWithdrawalAmount" => "$" . number_format($totalWithdrawalAmount, 2),
            "totalDeduction" => "$" . number_format($totalDeduction, 2),
            "totalAmountToPay" => "$" . number_format($totalAmountToPay, 2)
        ]);
    }

    private function getWithdrawalSummary($rangeResults) {
        $totalWithdrawalAmount = $totalDeduction = $totalAmountToPay = 0;

        foreach($rangeResults as $result) {
            $totalWithdrawalAmount += $result->amount;
            $totalDeduction += $result->deduction;
            $totalAmountToPay += ($result->amount - $result->deduction);
        }

        return [
            "totalWithdrawalAmount" => $totalWithdrawalAmount,
            "totalDeduction" => $totalDeduction,
            "totalAmountToPay" => $totalAmountToPay
        ];
    }


    public function approve_withdrawal($locale, $id) {
        try {
            $bonusWithdrawal = BonusWithdrawal::findOrFail($id);

            $portfolio = $bonusWithdrawal->distributor->portfolio;
            $portfolio->commission_wallet -= $bonusWithdrawal->amount;
            $portfolio->save();

            $bonusWithdrawal->status = BonusWithdrawalStatus::APPROVED->name;
            $bonusWithdrawal->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully approved withdrawal request"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function filter_withdrawals(Request $request, $locale) {
        $status = $request->status;
        $acceptableValues = ["PENDING", "APPROVED"];

        if (!in_array($status, $acceptableValues)) {
            return redirect()->back();
        }

        try {
            $result = BonusWithdrawal::where("status", $status)->get();

            return view("admin.bonus-withdrawals", [
                "withdrawals" => $result,
                "total" => count($result),
                "pending" => BonusWithdrawal::where("status", BonusWithdrawalStatus::PENDING->name)->count(),
                "approved" => BonusWithdrawal::where("status", BonusWithdrawalStatus::APPROVED->name)->count(),
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back();
        }
    }

    public function leadership_bonus() {
        $qualifiedUplines = Upline::qualifiedForLeadershipBonus();


        return view("admin.leadership-bonus", [
            "qualifiedUplines" => $qualifiedUplines,
        ]);
    }

    public function pay_leadership_bonus($locale, $id) {
        try {
            $upline = Upline::findOrFail($id);
            LeadershipBonus::giveBonus($upline);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully paid leadership bonus"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function pay_all_leadership_bonus($locale) {
        try {
            $qualifiedUplines = Upline::qualifiedForLeadershipBonus();

            foreach($qualifiedUplines as $upline) {
                LeadershipBonus::giveBonus($upline);
            }

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully paid leadership bonus"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function bv_reset() {
        try {
            $distributors = Distributor::all();

            foreach($distributors as $distributor) {
                $portfolio = $distributor->portfolio;
                $portfolio->commission_wallet = 0;
                $portfolio->current_balance = 0;
                $portfolio->save();

                $upline = $distributor->user->upline;

                if ($upline !== null) {
                    $upline->first_leg_point = 0;
                    $upline->second_leg_point = 0;
                    $upline->weekly_point = 0;
                    $upline->last_amount_paid = 0;
                    $upline->last_awarded_point = 0;
                    $upline->save();
                }

                Transaction::where("distributor_id", $distributor->id)->delete();
            }

            DB::table("order_items")->delete();
            DB::table("orders")->delete();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully reset dollar and bv"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function reset_withdrawal_pin($locale, $id) {
        try {
            $user = User::findOrFail($id);
            $distributor = $user->distributor;

            $generatedPin = PinGenerator::generate();
            $distributor->code = Hash::make($generatedPin);
            $distributor->save();

            Mail::to($user->email)->send(
                new ResetWithdrawalPin($user->name, $user->email, $generatedPin)
            );

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully reset pin"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
                //"message" => "Something went wrong, please contact developer"
            ]);
        }
    }

    public function wallet_transfer() {
        $transfers = Transaction::where("transaction_type", TransactionType::DEPOSIT->name)
                        ->orderBy("id", "desc")->get();
        $totalAmount = 0;

        foreach($transfers as $transfer) {
            if ($transfer->status === TransactionStatus::COMPLETE->name) {
                $totalAmount += $transfer->amount;
            }
        }

        return view("admin.distributor.wallet-transfer", [
            "transfers" => $transfers,
            "totalAmountTransfered" => '$ '. number_format($totalAmount, 2),
            "totalTransfer" => count($transfers)
        ]);
    }

    public function reverse_transfer($locale, $id) {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->status = TransactionStatus::REVERSED->name;
            $transaction->save();

            $portfolio = $transaction->distributor->portfolio;
            $portfolio->current_balance -= $transaction->amount;
            $portfolio->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully reversed wallet transfer"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }


    public function withdrawal_details($locale, $id) {
        try {
            $bonusWithdrawal = BonusWithdrawal::findOrFail($id);
            return view("admin.bonus-withdrawal-details", [
                "withdrawalDetails" => $bonusWithdrawal
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Withdrawal doesn't exist"
            ]);
        }
    }
}
