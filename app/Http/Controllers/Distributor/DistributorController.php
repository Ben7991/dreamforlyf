<?php

namespace App\Http\Controllers\Distributor;

use App\BusinessLogic\BvCycle;
use App\BusinessLogic\PersonalBonus;
use App\BusinessLogic\UpgradeBonus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\PackageType;
use App\Models\Product;
use App\Models\Rank;
use App\Models\Referral;
use App\Models\RegistrationPackage;
use App\Models\Stockist;
use App\Models\UpgradeHistory;
use App\Models\UpgradePackage;
use App\Enums\EntityStatus;
use App\Models\BankDetail;
use App\Models\CodeEthics;
use App\Models\PoolBonus;
use App\Models\PoolBonusStatus;
use App\Models\User;
use App\Models\UserType;
use App\Utility\GlobalValues;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DistributorController extends Controller
{
    public function index() {
        $currentUser = Auth::user();
        $distributor = $currentUser->distributor;
        $referredDistributors = $this->getReferredDistributors();

        $currentDate = new Carbon();
        $expiringDate = Carbon::parse($distributor->next_maintenance_date);
        $status = $expiringDate->greaterThanOrEqualTo($currentDate);

        $currentPackage = $distributor->getCurrentMembershipPackage();

        return view("distributor.index", [
            "referredDistibutors" => $referredDistributors,
            "token" => GlobalValues::getRegistrationToken(),
            "totalOrders" => Order::where("distributor_id", $currentUser->distributor->id)->count(),
            "remainingDays" => $status ? $expiringDate->diffInDays($currentDate) : "-" . $expiringDate->diffInDays($currentDate),
            "currentPackage" => $currentPackage
        ]);
    }

    public function code_ethics() {
        return view("distributor.code-ethics");
    }

    public function ranks() {
        $upline = Auth::user()->upline;
        $currentRank = "None";

        if ($upline !== null) {
            $rank = DB::table('upline_ranks')
                ->join('ranks', 'upline_ranks.rank_id', '=', 'ranks.id')
                ->where('upline_id', $upline->id)
                ->first();

            if ($rank !== null) {
                $currentRank = $rank->name;
            }
        }

        return view("distributor.ranks", [
            "total" => Rank::count(),
            "ranks" => Rank::orderBy("bv_point", "asc")->get(),
            "currentRank" => $currentRank
        ]);
    }

    public function products() {
        return view("distributor.product.index", [
            "products" => Product::orderBy("price", "asc")->where("quantity", ">", 5)->paginate(8),
        ]);
    }

    public function product_details($locale, $id) {
        try {
            $existingProduct = Product::findOrFail($id);

            return view("distributor.product.details",[
                "product" => $existingProduct,
                "stockists" => Stockist::all()
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Product doesn't exist"
            ]);
        }
    }

    public function product_purchase(Request $request, $locale, $id) {
        $validated = [];

        if ($request->input("purchase") === "direct") {
            $validated = $request->validate([
                "quantity" => "bail|required|numeric",
                "stockist" => "required",
                "purchase" => "required"
            ]);
        }
        else {
            $validated = $request->validate([
                "stockist" => "required",
                "purchase" => "required"
            ]);
        }

        $distributor = Auth::user()->distributor;
        $portfolio = $distributor->portfolio;
        $upline = $distributor->upline;

        try {
            $product = Product::findOrFail($id);
            $stockist = Stockist::findOrFail($validated["stockist"]);
            $amount = $point = 0;

            if (strcmp($validated["purchase"], "direct") === 0) {
                $point = $product->bv_point * $validated["quantity"];
                $amount = $product->price * (int)$validated["quantity"];
            }
            else if (strcmp($validated["purchase"], "maintenance") === 0) {
                $point = $product->bv_point;
                $amount = $product->price;
            }

            if ($portfolio->current_balance < $amount) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient balance to complete your order"
                ]);
            }

            if (strcmp($validated["purchase"], "direct") === 0) {
                $portfolio->subtractPurchaseAmount($amount);
                $this->storeOrder($product, $validated["quantity"], $distributor, $stockist, OrderType::NORMAL->name);
                PersonalBonus::giveBonus($distributor, $product, $validated["quantity"]);
            }
            else if (strcmp($validated["purchase"], "maintenance") === 0) {
                $this->setNextMaintenanceDate($distributor);
                $portfolio->subtractPurchaseAmount($amount);
                $quantity = 1;
                $this->storeOrder($product, $quantity, $distributor, $stockist, OrderType::MAINTENANCE->name);
            }
            else {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Please select from the provided purchase"
                ]);
            }

            BvCycle::initialCycle($upline, $point, $distributor->leg);

            return redirect("/$locale/distributor/order-history")->with([
                "class" => "success",
                "message" => "Purchased was made successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    private function setNextMaintenanceDate($distributor) {
        $currentDate = new Carbon();
        $expiringDate = Carbon::parse($distributor->next_maintenance_date);
        $nextDate = null;
        $durationInMonths = 3;

        if ($expiringDate->greaterThanOrEqualTo($currentDate)) {
            $nextDate = $expiringDate->addMonths($durationInMonths);
        } else {
            $nextDate = (new Carbon())->addMonth($durationInMonths);
        }

        $distributor->next_maintenance_date = $nextDate;
        $distributor->save();
    }

    private function storeOrder($product, $purchasedQuantity, $distributor, $stockist, $orderType) {
        $amount = $product->price * $purchasedQuantity;

        $storedOrder = Order::create([
            "amount" => $amount,
            "distributor_id" => $distributor->id,
            "order_type" => $orderType,
            "stockist_id" => $stockist->id
        ]);

        DB::table("order_items")->insert([
            "order_id" => $storedOrder->id,
            "product_id" => $product->id,
            "quantity" => $purchasedQuantity
        ]);
    }

    public function membership_packages() {
        $currentPackage = Auth::user()->distributor->getCurrentMembershipPackage();
        $upgradePackages = RegistrationPackage::where("id", ">", $currentPackage->id)->get();

        return view("distributor.membership-packages", [
            "packages" => RegistrationPackage::all(),
            "upgradePackages" => $upgradePackages,
            "currentPackage" => $currentPackage
        ]);
    }

    public function package_types() {
        return view("distributor.package-types", [
            "packages" => PackageType::paginate(8),
        ]);
    }

    public function upgrade_package(Request $request, $locale) {
        $validated = $request->validate([
            "package" => "bail|required|regex:/^[0-9]+$/"
        ]);

        try {
            $nextPackage = RegistrationPackage::findOrFail($validated["package"]);
            $token = GlobalValues::getRegistrationToken();
            return redirect("/$locale/distributor/membership-packages/upgrade/products?next=$nextPackage->id&token=$token");
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Package doesn't exist"
            ]);
        }
    }

    public function upgrade_product_selection(Request $request) {
        $nextPackageId = $request->next;
        $token = $request->token;

        try {
            $nextPackage = RegistrationPackage::find($nextPackageId);
            $currentPackage = Auth::user()->distributor->getCurrentMembershipPackage();
            $portfolio = Auth::user()->distributor->portfolio;
            $priceDifference = $nextPackage->price - $currentPackage->price;

            if ($priceDifference > $portfolio->current_balance) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient current balance to upgrade to higher package"
                ]);
            }

            $upgradeTypes = UpgradePackage::where("next_package_id", $nextPackage->id)
                ->where("current_package_id", $currentPackage->id)
                ->where("status", EntityStatus::ACTIVE->name)
                ->get();

            return view("distributor.upgrade-product-selection", [
                "nextPackage" => $nextPackage,
                "currentPackage" => $currentPackage,
                "upgradeTypes" => $upgradeTypes,
                "token" => $token,
                "stockists" => Stockist::all()
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function complete_upgrade(Request $request, $locale, $id) {
        $stockist = Stockist::find($request->stockist_id);
        $nextPackageId = $request->next;
        $upgradeTypeId = $id;

        try {
            $distributor = Auth::user()->distributor;
            $portfolio = $distributor->portfolio;

            $upgradeType = UpgradePackage::findOrFail($upgradeTypeId);
            $nextPackage = RegistrationPackage::find($nextPackageId);
            $currentPackage = $distributor->getCurrentMembershipPackage();
            $bvPoint = $nextPackage->bv_point - $currentPackage->bv_point;

            // 1. substract from current portfolio the difference between next package price and current package price
            $amountToSubtract = $nextPackage->price - $currentPackage->price;
            $portfolio->subtractPurchaseAmount($amountToSubtract);

            // 2. store upgrade history
            UpgradeHistory::create([
                "distributor_id" => $distributor->id,
                "registration_package_id" => $nextPackage->id,
                "upgrade_type_id" => $upgradeType->id
            ]);

            // 3. change current package to new package
            // $distributor->changePackage($nextPackage);

            // 4. store order history and products attached to upgrade type
            $products = DB::table("upgrade_package_product")->where("upgrade_package_id", $upgradeType->id)->get();
            $storedOrder = Order::create([
                "amount" => $amountToSubtract,
                "distributor_id" => $distributor->id,
                "order_type" => OrderType::UPGRADE->name,
                "stockist_id" => $stockist->id
            ]);

            foreach($products as $product) {
                DB::table("order_items")->insert([
                    "order_id" => $storedOrder->id,
                    "product_id" => $product->product_id,
                    "quantity" => $product->quantity
                ]);
            }

            // 5. give upgrade bonus
            UpgradeBonus::giveBonus($distributor, $bvPoint);

            $upline = $distributor->upline;
            BvCycle::initialCycle($upline, $bvPoint, $distributor->leg);

            return redirect("/$locale/distributor")->with([
                "class" => "success",
                "message" => "Current package upgraded successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
                // "message" => "Something went wrong"
            ]);
        }
    }

    public function qualified_ranks() {
        $upline = Auth::user()->upline;
        $qualifiedRanks = [];
        $total = 0;
        $awarded = 0;
        $pending = 0;

        if ($upline !== null) {
            $total = count($upline->ranks);

            foreach($upline->ranks as $rank) {
                if ($rank->pivot->status === "PENDING") {
                    $pending++;
                }
                else {
                    $awarded++;
                }
                $qualifiedRanks[] = [
                    "id" => $rank->pivot->id,
                    "rank" => Rank::find($rank->pivot->rank_id),
                    "status" => $rank->pivot->status,
                ];
            }
        }

        return view("distributor.qualified-ranks", [
            "qualifiedRanks" => $qualifiedRanks,
            "total" => $total,
            "awarded" => $awarded,
            "pending" => $pending
        ]);
    }

    public function qualified_pool() {
        $upline = Auth::user()->upline;
        $qualifiedPools = [];
        $total = $pending = $approved = 0;

        if ($upline) {
            $fetchedBonuses = PoolBonus::where("upline_id", $upline->id)->get();
            $total = count($fetchedBonuses);

            foreach($fetchedBonuses as $bonus) {
                $qualifiedPools[] = [
                    "status" => $bonus->status,
                    "date_time" => $bonus->created_at,
                ];

                switch($bonus->status) {
                    case PoolBonusStatus::PENDING->name:
                        $pending++;
                        break;
                    case PoolBonusStatus::AWARDED->name:
                        $approved++;
                        break;
                }
            }
        }

        return view("distributor.qualified-pool", [
            "poolRecords" => $qualifiedPools,
            "total" => $total,
            "pending" => $pending,
            "approved" => $approved
        ]);
    }

    public function upgrade_history() {
        $distributor = Auth::user()->distributor;
        $upgrades = UpgradeHistory::where("distributor_id", $distributor->id)->get();

        return view("distributor.upgrade-history", [
            "upgrades" => $upgrades,
            "total" => count($upgrades)
        ]);
    }

    public function complan() {
        return view("distributor.complan", [
            "packages" => RegistrationPackage::all()
        ]);
    }

    public function profile() {
        return view("distributor.profile");
    }

    public function set_pin(Request $request) {
        $validated = $request->validate([
            "code" => "bail|required|numeric|min:4"
        ]);

        try {
            $distributor = Auth::user()->distributor;
            $distributor->code = Hash::make($validated["code"]);
            $distributor->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Pin created successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function change_pin(Request $request) {
        $validated = $request->validate([
            "current_pin" => "bail|required|numeric|min:4",
            "new_pin" => "bail|required|numeric|min:4",
            "confirm_pin" => "bail|required|same:new_pin",
        ]);

        try {
            $distributor = Auth::user()->distributor;

            if (!Hash::check($validated["current_pin"], $distributor->code)) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Invalid pin"
                ]);
            }

            $distributor->code = Hash::make($validated["confirm_pin"]);
            $distributor->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Pin changed successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function referred_distributors() {
        $referredDistributors = $this->getReferredDistributors();

        return view("distributor.referred-distributors", [
            "referredDistributors" => $referredDistributors,
            "total" => count($referredDistributors)
        ]);
    }

    private function getReferredDistributors(): array {
        $upline = Auth::user()->upline;
        $referredDistributors = [];

        if ($upline !== null) {
            $fetchedDistributors = Referral::where("upline_id", $upline->id)->get();

            foreach($fetchedDistributors as $fetchDistributor) {
                $rank = "None";
                $upline = $fetchDistributor->distributor->user->upline;

                if ($upline !== null) {
                    $highestRank = DB::table("upline_ranks")->orderBy("id", "desc")->where("upline_id", $upline->id)->first();
                    if ($highestRank !== null) {
                        $fetchedRank = Rank::find($highestRank->rank_id);
                        $rank = $fetchedRank->name;
                    }
                }

                $referredDistributors[] = [
                    "id" => $fetchDistributor->distributor->user->id,
                    "name" => $fetchDistributor->distributor->user->name,
                    "currentPackage" => $fetchDistributor->distributor->getCurrentMembershipPackage()->name,
                    "leg" => $fetchDistributor->leg,
                    "rank" => $rank
                ];
            }
        }

        return $referredDistributors;
    }


    public function read_code_ethics(Request $request, $locale) {
        try {
            $distributor = Auth::user()->distributor;
            $distributor->code_ethics = CodeEthics::READ->name;
            $distributor->save();

            return redirect("/$locale/distributor");
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function ethics() {
        return view("distributor.ethics");
    }

    public function check_credential(Request $request, $value) {
        $existingUser = null;
        try {
            $existingUser = User::where("role", UserType::DISTRIBUTOR->name)
                                ->where("id", $value)
                                ->orWhere("email", $value)
                                ->firstOrFail();

            $formattedLoggedInUserId = (int)substr(Auth::id(), 3);
            $formattedExistingUserId = (int)substr($existingUser->id, 3);

            if ($formattedExistingUserId < $formattedLoggedInUserId) {
                return response()->json([
                    "message" => "Only downlines allowed"
                ], 500);
            }

            $upline = $existingUser->upline;

            if ($upline === null || ($upline !== null && count($upline->distributors) === 0)) {
                return response()->json([
                    "message" => "$existingUser->name has space on both legs"
                ]);
            }

            if (count($upline->distributors) === 2) {
                return response()->json([
                    "message" => "$existingUser->name has no space available on both legs"
                ], 400);
            }

            $availableSpace = "";

            if ($upline->distributors[0]->leg === "1st") {
                $availableSpace = "right";
            } else {
                $availableSpace = "left";
            }

            return response()->json([
                "message" => "$existingUser->name has space available on $availableSpace"
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                "message" => "User doesn't exist",
                "data" => $e->getMessage()
            ], 400);
        }
    }


    public function store_bank_details(Request $request) {
        $validated = $request->validate([
            "full_name" => "required|regex:/^[a-zA-Z ]*$/",
            "bank_name" => "required",
            "bank_branch" => "required",
            "beneficiary_name" => "required",
            "account_number" => "required|min:8|max:17|regex:/^[0-9a-zA-Z]*$/",
            "iban" => "required|regex:/^[a-zA-Z0-9]{23}$/|",
            "swift_number" => "bail|required|regex:/^[a-zA-Z0-9]{8}$/",
            "phone_number" => "bail|required|regex:/^\+[0-9]*$/",
            "rib" => "bail|required|min:2|max:3|regex:/^[0-9]*$/"
        ]);

        try {
            $distributor = Auth::user()->distributor;
            $message = "";
            $existingBankDetails = BankDetail::where("distributor_id", $distributor->id)
                ->first();

            if ($existingBankDetails === null) {
                BankDetail::create([
                    "full_name" => $validated["full_name"],
                    "bank_name" => $validated["bank_name"],
                    "bank_branch" => $validated["bank_branch"],
                    "beneficiary_name" => $validated["beneficiary_name"],
                    "account_number" => $validated["account_number"],
                    "iban_number" => $validated["iban"],
                    "swift_number" => $validated["swift_number"],
                    "phone_number" => $validated["phone_number"],
                    "distributor_id" => $distributor->id,
                    "rib" => $validated["rib"]
                ]);
                $message = "Successfully added bank details successfully";
            }
            else {
                $existingBankDetails->full_name = $validated["full_name"];
                $existingBankDetails->bank_name = $validated["bank_name"];
                $existingBankDetails->bank_branch = $validated["bank_branch"];
                $existingBankDetails->beneficiary_name = $validated["beneficiary_name"];
                $existingBankDetails->account_number = $validated["account_number"];
                $existingBankDetails->iban_number = $validated["iban"];
                $existingBankDetails->swift_number = $validated["swift_number"];
                $existingBankDetails->phone_number = $validated["phone_number"];
                $existingBankDetails->rib_number = $validated["rib"];
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
