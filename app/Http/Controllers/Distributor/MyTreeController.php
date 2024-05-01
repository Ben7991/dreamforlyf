<?php

namespace App\Http\Controllers\Distributor;

use App\BusinessLogic\BvCycle;
use App\BusinessLogic\CashBack;
use App\BusinessLogic\PoolBonus as BusinessPoolBonus;
use App\BusinessLogic\ReferralBonus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterDistributor;
use App\Mail\AccountRegistration;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\PackageType;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Referral;
use App\Models\ReferralLeg;
use App\Models\RegistrationPackage;
use App\Models\Stockist;
use App\Models\Upline;
use App\Models\User;
use App\Models\UserType;
use App\Utility\GlobalValues;
use App\Utility\PasswordGenerator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MyTreeController extends Controller
{
    public function index() {
        $totalReferrals = $totalLeftBv = $totalRightBv = $downlineCount = $paidBv = $leftDistributors = $rightDistirbutors = 0;

        $upline = Auth::user()->upline;

        if ($upline !== null) {
            $totalReferrals = $upline->referrals->count();
            $totalLeftBv = $upline->first_leg_point;
            $totalRightBv = $upline->second_leg_point;
            $downlineCount = count($upline->distributors);
            $max = max($totalLeftBv, $totalRightBv);
            $paidBv = $max - $upline->last_awarded_point;
            $leftDistributors = $upline->left_leg_count;
            $rightDistirbutors = $upline->right_leg_count;
        }

        return view("distributor.my-tree.index", [
            "totalReferrals" => $totalReferrals,
            "totalLeftBv" => $totalLeftBv,
            "totalRightBv" => $totalRightBv,
            "distributorCount" => $downlineCount,
            "token" => GlobalValues::getRegistrationToken(),
            "paid_bv" => $paidBv,
            "leftDistributors" => $leftDistributors,
            "rightDistributors" => $rightDistirbutors
        ]);
    }

    public function create() {
        $packages = RegistrationPackage::all();

        return view("distributor.my-tree.create", [
            "packages" => $packages,
            "stockists" => Stockist::all()
        ]);
    }

    public function register(RegisterDistributor $request, $locale) {
        $validated = $request->validated();
        $generatedPassword = PasswordGenerator::generate();
        $currentUser = Auth::user();
        $upline = $currentUser->upline;

        if ($upline === null) {
            $upline = Upline::create([
                "user_id" => $currentUser->id
            ]);
        }

        $referer = $upline;

        try {
            if ($upline->isLegOccupied($validated["leg"]) && count($upline->distributors) < 2) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Leg already occupied"
                ]);
            }

            $leg = $validated["leg"];
            $uplineSelectedLeg = $leg;

            if (count($upline->distributors) === 2) {
                $upline = $upline->nextUpline($validated["leg"]);
                $leg = $upline->nextLeg();
            }

            $portfolio = $currentUser->distributor->portfolio;
            $existingRegistrationPackage = RegistrationPackage::findOrFail($validated["package_id"]);

            if ($portfolio->current_balance < $existingRegistrationPackage->price) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient funds in your current balance portfolio"
                ]);
            }

            $existingPackageType = PackageType::findOrFail($validated["type"]);
            $purchasedProducts = DB::table("product_package_type")->where("type_id", $existingPackageType->id)->get();

            $storedDistributor = $this->storeUser($validated, $generatedPassword, $upline, $existingRegistrationPackage, $referer, $leg, $uplineSelectedLeg);
            $this->storeOrder($purchasedProducts, $storedDistributor, $existingRegistrationPackage, $validated["stockist_id"]);
            $portfolio->subtractPurchaseAmount($existingRegistrationPackage->price);

            CashBack::giveCashBackBonus($storedDistributor, $existingRegistrationPackage);
            ReferralBonus::distributeBonus($referer, $existingRegistrationPackage);
            BvCycle::initialCycle($upline, $existingRegistrationPackage->bv_point, $storedDistributor->leg);

            Mail::to($validated["email"])->send(
                new AccountRegistration($validated["name"], $validated["email"], $generatedPassword)
            );

            return redirect("/$locale/distributor/my-tree")->with([
                "class" => "success",
                "message" => "Added new distributor successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
                //"message" => "Something went wrong, please contact admin for assistance"
            ]);
        }
    }

    private function storeUser($data, $password, $upline, $package, $referer, $leg, $uplineSelectedLeg) {
        $storedUser = User::create([
            "id" => User::nextId(),
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => $password,
            "role" => UserType::DISTRIBUTOR->name
        ]);

        $storedDistributor = Distributor::create([
            "upline_id" => $upline->id,
            "leg" => $leg,
            "registration_package_id" => $package->id,
            "country" => $data["country"],
            "city" => $data["city"],
            "user_id" => $storedUser->id,
            "phone_number" => $data["phone_number"],
            "wave" => $data["wave"],
            "next_maintenance_date" => (new Carbon())->addMonths(2)
        ]);

        Portfolio::create([
            "distributor_id" => $storedDistributor->id
        ]);

        Referral::create([
            "upline_id" => $referer->id,
            "distributor_id" => $storedDistributor->id,
            "leg" => $uplineSelectedLeg === "1st" ? ReferralLeg::LEFT->name : ReferralLeg::RIGHT->name
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
                "quantity" => $product->quantity,
            ]);

            $updatedProduct = Product::find($product->product_id);
            $updatedProduct->quantity -= $product->quantity;
            $updatedProduct->save();
        }
    }

    public function downline_detail($locale, $id) {
        try {
            $distributor = Distributor::findOrFail($id);
            $user = $distributor->user;
            $imagePath = $user->image !== null ? asset(str_replace("public", "storage", $user->image)) : "none";
            $rank = "None";
            $leftBv = $user->upline !== null ? $user->upline->first_leg_point : 0;
            $rightBv = $user->upline !== null ? $user->upline->second_leg_point : 0;

            return response()->json([
                "name" => $distributor->user->name,
                "imagePath" => $imagePath,
                "leftBv" => $leftBv,
                "rightBv" => $rightBv,
                "rank" => $rank,
                "link" => "/$locale/distributor/my-tree/$user->id",
                "membershipPackage" => $distributor->registrationPackage->name,
                "id" => $user->id,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                "message" => "Distributor doesn't exist"
            ], 500);
        }
    }

    public function downline_tree($locale, $id) {
        try {
            $existingUser = User::findOrFail($id);
            $totalLeftLeg = $totalRightLeg = 0;

            if ($existingUser->upline !== null) {
                $totalLeftLeg = $existingUser->upline->first_leg_point;
                $totalRightLeg = $existingUser->upline->second_leg_point;
            }

            return view("distributor.my-tree.downline-tree", [
                "user" => $existingUser,
                "membershipPackage" => $existingUser->distributor->registrationPackage->name,
                "totalLeftLeg" => $totalLeftLeg,
                "totalRightLeg" => $totalRightLeg,
                "token" => GlobalValues::getRegistrationToken(),
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "User doesn't exist"
            ]);
        }
    }

    public function user_detail($locale, $id) {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                "link" => "/$locale/distributor/my-tree/$user->id",
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                "message" => "User doesn't exist"
            ], 500);
        }
    }
}
