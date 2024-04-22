<?php

namespace App\Http\Controllers\Distributor;

use App\BusinessLogic\BvCycle;
use App\Http\Controllers\Controller;
use App\Models\MaintenancePackage;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\Product;
use App\Models\Stockist;
use App\Utility\GlobalValues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MaintenancePackageController extends Controller
{
    public function index() {
        $distributor = Auth::user()->distributor;
        $currentDate = new Carbon();
        $expiringDate = Carbon::parse($distributor->next_maintenance_date);
        $status = $expiringDate->greaterThanOrEqualTo($currentDate);

        return view("distributor.maintenance-package.index", [
            "packages" => MaintenancePackage::all(),
            "remainingDays" => $status ? $expiringDate->diffInDays($currentDate) : "-" . $expiringDate->diffInDays($currentDate),
            "status" => $status ? "Done" : "Expired"
        ]);
    }

    public function store(Request $request) {
        $packageId = $request->packageId;
        $products = $request->products;

        $existingPackage = MaintenancePackage::find($packageId);
        $isProductValidated = $this->validatedProducts($products);
        $isItemQuantityValidated = $this->validateProductQuantity($products, $existingPackage);

        if ($existingPackage === null || !$isProductValidated || !$isItemQuantityValidated) {
            return response()->json([
                "message" => "Validation failed"
            ], 422);
        }

        try {
            $distributor = Auth::user()->distributor;
            $portfolio = $distributor->portfolio;

            $currentDate = new Carbon();
            $expiringDate = Carbon::parse($distributor->next_maintenance_date);
            $nextDate = null;

            if ($expiringDate->greaterThanOrEqualTo($currentDate)) {
                $nextDate = $expiringDate->addMonths($existingPackage->duration_in_months);
            } else {
                $nextDate = (new Carbon())->addMonth($existingPackage->duration_in_months);
            }

            $distributor->next_maintenance_date = $nextDate;
            $distributor->save();

            $portfolio->subtractPurchaseAmount($existingPackage->total_price);
            $this->storeOrderDetails($existingPackage, $distributor, $products);

            BvCycle::initialCycle($distributor->upline, $existingPackage->bv_point, $distributor->leg);

            return response()->json([
                "message" => "Maintenance completed successfully, you will be redirected soon"
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                "message" => "Something went wrong, please contact admin for assistance"
            ], 500);
        }
    }

    public function validatedProducts($products) {
        $isValidated = true;

        foreach($products as $product) {
            $existingProduct = Product::find($product["id"]);
            if (!$existingProduct) {
                $isValidated = false;
            }
        }

        return $isValidated;
    }

    private function validateProductQuantity($products, $maintenancePackage) {
        $maximumQuantity = $maintenancePackage->total_products;
        $totalQuantity = 0;
        $isAccurate = true;

        foreach($products as $product) {
            $totalQuantity += $product["quantity"];
        }

        if ($totalQuantity > $maximumQuantity) {
            $isAccurate = false;
        }

        return $isAccurate;
    }

    private function storeOrderDetails($existingPackage, $distributor, $products) {
        $stockist = Stockist::find(1);

        $storedOrder = Order::create([
            "amount" => $existingPackage->total_price,
            "distributor_id" => $distributor->id,
            "order_type" => OrderType::MAINTENANCE->name,
            "stockist_id" => $stockist->id
        ]);

        foreach($products as $product) {
            DB::table("order_items")->insert([
                "order_id" => $storedOrder->id,
                "product_id" => $product["id"],
                "quantity" => $product["quantity"],
            ]);

            $updatedProduct = Product::find($product["id"]);
            $updatedProduct->quantity -= $product["quantity"];
            $updatedProduct->save();
        }
    }

    public function select_package(Request $request, $locale) {
        $validated = $request->validate([
            "package_id" => "bail|required|numeric"
        ]);

        try {
            $package = MaintenancePackage::findOrFail($validated["package_id"]);
            $token = Hash::make(GlobalValues::getMaintenanceTokenString());
            return redirect("/$locale/distributor/maint-packages/$package->id?secret=$token");
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Package doesn't exist"
            ]);
        }
    }

    public function account($locale, $id) {
        $products = Product::where("bv_point", 10)->where("quantity", ">", 5)->get();
        $package = MaintenancePackage::find($id);

        return view("distributor.maintenance-package.account", [
            "products" => $products,
            "package" => $package
        ]);
    }

    public function product_details($id) {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "image" => str_replace("public", "storage", asset($product->image)),
                ]
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                "message" => "Product doesn't exist"
            ], 500);
        }
    }
}
