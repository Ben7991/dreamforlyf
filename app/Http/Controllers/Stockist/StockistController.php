<?php

namespace App\Http\Controllers\Stockist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\Product;
use App\Models\RegistrationPackage;
use App\Models\StockistBankDetails;
use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionType;
use App\Models\UpgradeHistory;
use App\Models\UpgradePackage;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockistController extends Controller
{
    public function index() {
        $result = DB::table("transactions")
                    ->join("stockist_transfer", "transactions.id", "stockist_transfer.transaction_id")
                    ->join("distributors", "distributors.id", "transactions.distributor_id")
                    ->join("users", "users.id", "distributors.user_id")
                    ->select("transactions.amount", "users.name", "users.id", "stockist_transfer.date_added")
                    ->get();

        $stockistId = Auth::user()->stockist->id;
        $pendingorderCount = Order::where("status", OrderStatus::PENDING->name)
            ->where("stockist_id", $stockistId)
            ->count();

        return view("stockist.index", [
            "pendingOrderCount" => $pendingorderCount,
            "transferCount" => count($result),
            "transfers" => $result
        ]);
    }

    public function orderHistory() {
        $stockistId = Auth::user()->stockist->id;

        $orders = Order::orderBy("id", "desc")
            ->where("stockist_id", $stockistId)
            ->get();
        $total = count($orders);

        $pending = Order::where("status", "PENDING")
            ->where("stockist_id", $stockistId)
            ->count();
        $approved = Order::where("status", "APPROVED")
            ->where("stockist_id", $stockistId)
            ->count();

        return view("stockist.order-history", [
            "orders" => $orders,
            "pending" => $pending,
            "approved" => $approved,
            "total" => $total
        ]);
    }

    public function orderDetails($locale, $id) {
        try {
            $order = Order::findOrFail($id);
            return view("stockist.order-details", [
                "order" => $order
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Order doesn't exist"
            ]);
        }
    }

    public function changeOrderStatus(Request $request, $locale, $id) {
        $validated = $request->validate([
            "status" => "required"
        ]);

        $acceptableValues = ["PENDING", "APPROVED"];

        if (!in_array($validated["status"], $acceptableValues)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Status isn't recognized"
            ]);
        }

        try {
            $order = Order::findOrFail($id);
            $order->status = $validated["status"];
            $order->save();
            $this->calculateBonus($order);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Order approved successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Order doesn't exist"
            ]);
        }
    }

    private function calculateBonus(Order $order) {
        $bonusRate = 0.05;
        $stockist = Auth::user()->stockist;

        if ($order->order_type === OrderType::MAINTENANCE->name || $order->order_type === OrderType::NORMAL->name) {
            $orderItem = DB::table("order_items")->where("order_id", $order->id)->first();
            $product = Product::find($orderItem->product_id);
            $bonus = $product->bv_point * $bonusRate;
            $stockist->bonus += $bonus;
        }
        else if ($order->order_type === OrderType::REGISTRATION->name) {
            $registrationPackage = $order->distributor->getCurrentMembershipPackage();
            $bonus = $registrationPackage->bv_point * $bonusRate;
            $stockist->bonus += $bonus;
        }
        else {
            // get distributor using the distributor_id from the order instance
            // find upgrade history from the upgrade_histories tables (this table contains upgrade_type_id column)
            $upgradeHistory = UpgradeHistory::where("distributor_id", $order->distributor_id)->orderBy("id", "desc")->first();
            $upgradeType = UpgradePackage::find($upgradeHistory->upgrade_type_id);
            // use the upgrade_type_id extracted from the upgrade_histories table to get the upgrade type containing both the previous package and next package
            $previousPackage = RegistrationPackage::find($upgradeType->current_package_id);
            $nextPackage = RegistrationPackage::find($upgradeType->next_package_id);
            // get the bvs of both and subtract the previous package from the old package.
            $calculatedBvDifference = $nextPackage->bv_point - $previousPackage->bv_point;
            // calculate the 5% bonus on the calculated bv difference and add to stokist bonus
            $bonus = $calculatedBvDifference * 0.05;
            $stockist->bonus += $bonus;
        }

        $stockist->save();
    }

    public function transferWallet() {
        $result = DB::table("transactions")
                    ->join("stockist_transfer", "transactions.id", "stockist_transfer.transaction_id")
                    ->join("distributors", "distributors.id", "transactions.distributor_id")
                    ->join("users", "users.id", "distributors.user_id")
                    ->select("transactions.amount", "users.name", "users.id", "stockist_transfer.date_added")
                    ->get();

        return view("stockist.transfer-wallet", [
            "transfers" => $result
        ]);
    }

    public function sendDistributorWallet(Request $request, $locale, $id) {
        $validated = $request->validate([
            "amount" => "required|numeric"
        ]);
        $amount = (float)$validated["amount"];

        try {
            $currentStockist = Auth::user()->stockist;

            if ($currentStockist->wallet < $amount) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient wallet to perform transfer"
                ]);
            }

            $user = User::findOrFail($id);
            $distributor = $user->distributor;
            $portfolio = $distributor->portfolio;

            $portfolio->current_balance += $amount;
            $portfolio->save();

            $transaction = Transaction::create([
                "distributor_id" => $distributor->id,
                "amount" => $amount,
                "portfolio" => TransactionPortfolio::CURRENT_BALANCE->name,
                "transaction_type" => TransactionType::DEPOSIT->name,
            ]);

            DB::table("stockist_transfer")->insert([
                "transaction_id" => $transaction->id
            ]);

            $currentStockist->wallet -= $amount;
            $currentStockist->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Transfer completed successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function profile() {
        return view("stockist.profile");
    }


    public function bonus_withdrawal() {
        $stockist = Auth::user()->stockist;
        $withdrawals = DB::table("stockist_withdrawal")->where("stockist_id", $stockist->id)->get();
        $pending = DB::table("stockist_withdrawal")->where("stockist_id", $stockist->id)->where("status", "PENDING")->count();
        $approved = DB::table("stockist_withdrawal")->where("stockist_id", $stockist->id)->where("status", "APPROVED")->count();
        $request = DB::table("stockist_withdrawal_request")->where("stockist_id", $stockist->id)->first();

        return view("stockist.bonus-withdrawal", [
            "withdrawals" => $withdrawals,
            "totalRequest" => count($withdrawals),
            "pending" => $pending,
            "approved" => $approved,
            "request" => $request
        ]);
    }


    public function request_withdrawal() {
        $stockist = Auth::user()->stockist;

        try {
            $existingRequest = DB::table("stockist_withdrawal_request")->where("stockist_id", $stockist->id)->first();

            if ($existingRequest === null) {
                DB::table("stockist_withdrawal_request")->insert([
                    "stockist_request" => "REQUESTED",
                    "approval_status" => "PENDING",
                    "stockist_id" => $stockist->id
                ]);
            }
            else {
                DB::table("stockist_withdrawal_request")->where("stockist_id", $stockist->id)->update([
                    "stockist_request" => "REQUESTED",
                    "approval_status" => "PENDING",
                ]);
            }

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Ask admin to open the withdrawal portal"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }


    public function make_withdrawal(Request $request, $locale) {
        $validated = $request->validate([
            "amount" => "bail|required|regex:/^[0-9]*(\.[0-9]{2})*$/",
            "mode" => "required"
        ]);

        $amount = (float)$validated["amount"];
        $stockist = Auth::user()->stockist;

        try {
            $existingRequest = DB::table("stockist_withdrawal")
                ->where("stockist_id", $stockist->id)
                ->where("status", "PENDING")
                ->first();

            if ($existingRequest !== null) {
                throw new Exception("You have a pending request");
            }

            if ($amount > $stockist->bonus) {
                throw new Exception("Insufficient bonus to request withdrawal");
            }

            DB::table("stockist_withdrawal")->insert([
                "created_at" => Carbon::now(),
                "amount" => $amount,
                "deduction" => $amount * 0.05,
                "stockist_id" => $stockist->id,
                "mode" => $validated["mode"]
            ]);

            DB::table("stockist_withdrawal_request")
                ->where("stockist_id", $stockist->id)
                ->update([
                    "approval_status" => "PENDING"
                ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully request, awaiting admin approval and payout"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }


    public function personal_information(Request $request) {
        $id = Auth::id();
        $currentUser = User::find($id);
        $validated = $request->validate([
            "name" => "bail|required",
            "email" => "nullable|email",
            "momo" => "bail|required"
        ]);

        try {
            $currentUser->name = $validated["name"];
            $currentUser->email = $validated["email"];
            $currentUser->save();

            $stockist = Auth::user()->stockist;
            $stockist->phone_number = $validated["momo"];
            $stockist->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Updated personal information successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong, please contact developer for assistance"
            ]);
        }
    }


    public function set_bank_details(Request $request) {
        $validated = $request->validate([
            "full_name" => "required|regex:/^[a-zA-Z ]*$/",
            "bank_name" => "required",
            "bank_branch" => "required",
            "beneficiary_name" => "required",
            "account_number" => "required|regex:/^[0-9]*$/",
            "iban" => "required|regex:/^[0-9]*$/",
            "swift_number" => "required||regex:/^[0-9]*$/",
            "phone_number" => "required|regex:/^\+[0-9]*$/",
        ]);

        try {
            $stockist = Auth::user()->stockist;
            $message = "";
            $existingBankDetails = StockistBankDetails::where("stockist_id", $stockist->id)
                ->first();

            if ($existingBankDetails === null) {
                StockistBankDetails::create([
                    "full_name" => $validated["full_name"],
                    "bank_name" => $validated["bank_name"],
                    "bank_branch" => $validated["bank_branch"],
                    "beneficiary_name" => $validated["beneficiary_name"],
                    "account_number" => $validated["account_number"],
                    "iban_number" => $validated["iban"],
                    "swift_number" => $validated["swift_number"],
                    "phone_number" => $validated["phone_number"],
                    "stockist_id" => $stockist->id
                ]);
                $message = "Successfully added bank details successfully";
            }
            else {
                $existingBankDetails->full_name = $validated["full_name"];
                $existingBankDetails->bank_name = $validated["bank_name"];
                $existingBankDetails->bank_branch = $validated["bank_branch"];
                $existingBankDetails->beneficiary_name = $validated["beneficiary_name"];
                $existingBankDetails->account_number = $validated["account_number"];
                $existingBankDetails->iban_number = $validated["iban_number"];
                $existingBankDetails->swift_number = $validated["swift_number"];
                $existingBankDetails->phone_number = $validated["phone_number"];
                $existingBankDetails->save();
                $message = "Successfully updated bank details successfully";
            }

            return redirect()->back()->with([
                "message" => $message,
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong, please check and try again",
                "class" => "danger"
            ]);
        }
    }
}
