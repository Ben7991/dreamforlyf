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
        $email = "";
        $loggedInUpline = Auth::user()->upline;

        if ($loggedInUpline === null || count($loggedInUpline->distributors) < 2) {
            $email = Auth::user()->email;
        }

        return view("distributor.my-tree.create", [
            "packages" => $packages,
            "stockists" => Stockist::all(),
            "email" => $email
        ]);
    }

    public function register(RegisterDistributor $request, $locale) {
        $validated = $request->validated();
        $generatedPassword = PasswordGenerator::generate();
        $upline = $referer = $portfolio = null;
        $leg = $validated["leg"];
        $uplineSelectedLeg = "";

        try {
            $user = User::where("role", UserType::DISTRIBUTOR->name)
                                ->where("id", $validated["upline_id_email"])
                                ->orWhere("email", $validated["upline_id_email"])
                                ->first();

            if ($user === null) {
                return redirect()->back()->with([
                    "message" => "Choosen upline doesn't exist",
                    "class" => "danger"
                ]);
            }

            $formattedLoggedInUserId = (int)substr(Auth::id(), 3);
            $formattedExistingUserId = (int)substr($user->id, 3);

            if ($formattedExistingUserId < $formattedLoggedInUserId) {
                return redirect()->back()->with([
                    "message" => "Only downlines allowed",
                    "class" => "danger"
                ]);
            }

            if ($user->id === Auth::id()) { // new member becoming an upline for the registering distributor
                $upline = $user->upline;

                if ($upline === null) {
                    $upline = Upline::create([
                        "user_id" => $user->id
                    ]);
                }

                if ($upline->isLegOccupied($leg)) {
                    return redirect()->back()->with([
                        "class" => "danger",
                        "message" => "Leg already occupied"
                    ]);
                }

                if (count($upline->distributors) === 2) {
                    return redirect()->back()->with([
                        "class" => "danger",
                        "message" => "Both legs are occupied"
                    ]);
                }

                $referer = $upline;
                $portfolio = $user->distributor->portfolio;
                $uplineSelectedLeg = $leg;
            } else { // continue with referer choosing who becomes a new upline for the new distributor
                $referer = Auth::user()->upline;
                $upline = $user->upline;
                $portfolio = Auth::user()->distributor->portfolio;

                if ($upline === null) {
                    $upline = Upline::create([
                        "user_id" => $user->id
                    ]);
                }

                if (count($upline->distributors) === 2) {
                    return redirect()->back()->with([
                        "class" => "danger",
                        "message" => "Both legs are occupied"
                    ]);
                }

                if ($upline->isLegOccupied($leg)) {
                    return redirect()->back()->with([
                        "class" => "danger",
                        "message" => "Leg already occupied"
                    ]);
                }

                $uplineSelectedLeg = $user->distributor->findDistributorLeg($referer);
            }

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
                "message" => "Something went wrong, ensure upline id/email detail is accurate"
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
