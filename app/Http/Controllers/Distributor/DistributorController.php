<?php

namespace App\Http\Controllers\Distributor;

use App\BusinessLogic\BvCycle;
use App\BusinessLogic\LegCounter;
use App\BusinessLogic\PersonalBonus;
use App\BusinessLogic\UpgradeBonus;
use App\Http\Controllers\Controller;
use App\Models\Distributor;
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
use App\BusinessLogic\PoolBonus as BusinessPoolBonus;
use App\Models\CodeEthics;
use App\Models\PoolBonus;
use App\Models\PoolBonusStatus;
use App\Utility\GlobalValues;
use Carbon\Carbon;
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

        return view("distributor.index", [
            "referredDistibutors" => $referredDistributors,
            "token" => GlobalValues::getRegistrationToken(),
            "totalOrders" => Order::where("distributor_id", $currentUser->distributor->id)->count(),
            "remainingDays" => $status ? $expiringDate->diffInDays($currentDate) : "-" . $expiringDate->diffInDays($currentDate),
        ]);
    }

    public function code_ethics() {
        return view("distributor.code-ethics");
    }

    public function ranks() {
        return view("distributor.ranks", [
            "total" => Rank::count(),
            "ranks" => Rank::all()
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
        $validated = $request->validate([
            "quantity" => "bail|required|numeric",
            "stockist" => "bail|required"
        ]);

        $distributor = Auth::user()->distributor;
        $portfolio = $distributor->portfolio;
        $upline = $distributor->upline;

        try {
            $product = Product::findOrFail($id);
            $stockist = Stockist::findOrFail($validated["stockist"]);
            $amount = $product->price * $validated["quantity"];

            if ($portfolio->current_balance < $amount) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient balance to complete your order"
                ]);
            }

            $portfolio->current_balance -= $amount;
            $portfolio->save();

            $this->storeOrder($product, $validated["quantity"], $distributor, $stockist);

            $point = $product->bv_point * $validated["quantity"];
            BvCycle::initialCycle($upline, $point, $distributor->leg);
            PersonalBonus::giveBonus($distributor, $product, $validated["quantity"]);

            return redirect("/$locale/distributor/order-history")->with([
                "class" => "success",
                "message" => "Purchased $product->name successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    private function storeOrder(Product $product, int $purchasedQuantity, Distributor $distributor, Stockist $stockist) {
        $amount = $product->price * $purchasedQuantity;

        $storedOrder = Order::create([
            "amount" => $amount,
            "distributor_id" => $distributor->id,
            "order_type" => OrderType::NORMAL->name,
            "stockist_id" => $stockist->id
        ]);

        DB::table("order_items")->insert([
            "order_id" => $storedOrder->id,
            "product_id" => $product->id,
            "quantity" => $purchasedQuantity
        ]);
    }

    public function membership_packages() {
        $currentPackage = Auth::user()->distributor->registrationPackage;
        $upgradePackages = RegistrationPackage::where("id", ">", $currentPackage->id)->get();

        return view("distributor.membership-packages", [
            "packages" => RegistrationPackage::all(),
            "upgradePackages" => $upgradePackages
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
            $currentPackage = Auth::user()->distributor->registrationPackage;
            $portfolio = Auth::user()->distributor->portfolio;
            $priceDifference = $nextPackage->price - $currentPackage->price;

            if ($priceDifference > $portfolio->current_balance) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient current balance to upgrade to higher package"
                ]);
            }

            $upgradeTypes = UpgradePackage::where("next_package_id", $nextPackage->id)
                ->where("current_package_id", $currentPackage->id)->get();

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
            $currentPackage = $distributor->registrationPackage;
            $bvPoint = $nextPackage->bv_point - $currentPackage->bv_point;

            // 1. substract from current portfolio the difference between next package price and current package price
            $amountToSubtract = $nextPackage->price - $distributor->registrationPackage->price;
            $portfolio->subtractPurchaseAmount($amountToSubtract);

            // 2. store upgrade history
            UpgradeHistory::create([
                "distributor_id" => $distributor->id,
                "registration_package_id" => $nextPackage->id,
                "upgrade_type_id" => $upgradeType->id
            ]);

            // 3. change current package to new package
            $distributor->changePackage($nextPackage);

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
                    "currentPackage" => $fetchDistributor->distributor->registrationPackage->name,
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
}
