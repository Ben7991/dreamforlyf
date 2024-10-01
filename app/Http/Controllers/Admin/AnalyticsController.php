<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AnalyticsController extends Controller
{
    public function index() {
        $result = DB::table("distributors")
            ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
            ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
            ->groupBy("distributors.registration_package_id")
            ->get();

        $secondResult = DB::table("package_types")
            ->join("product_package_type", "package_types.id", "=", "product_package_type.type_id")
            ->join("registration_packages", "package_types.package_id", "=", "registration_packages.id")
            ->select(DB::raw("registration_packages.id, sum(product_package_type.quantity) as qty"))
            ->groupBy("package_types.id")
            ->get();

        $mainResult = [];

        foreach($result as $row) {
            $eachRow = new stdClass();
            $eachRow->name = $row->name;
            $eachRow->price = $row->price;
            $eachRow->bv_point = $row->bv_point;
            $eachRow->total_number = $row->total_number;

            foreach ($secondResult as $secondRow) {
                if ($row->id === $secondRow->id) {
                    $eachRow->quantity = $secondRow->qty;
                    break;
                }
            }
            $mainResult[] = $eachRow;
        }


        return view("admin.analytics.index", [
            "result" => $mainResult
        ]);
    }

    public function personal_purchase() {
        $result = DB::table("orders")
            ->join("order_items", "orders.id", "=", "order_items.order_id")
            ->join("products", "products.id", "=", "order_items.product_id")
            ->select(DB::raw("products.id, products.name, products.price, count(products.id) as quantity"))
            ->where("order_type", "=", OrderType::NORMAL->name)
            ->groupBy("products.id")
            ->get();

        return view("admin.analytics.personal-purchase", [
            "result" => $result
        ]);
    }

    public function upgrade_bonus() {
        return view("admin.analytics.upgrade-bonus");
    }

    public function maintenance() {
        $result = DB::table("orders")
            ->join("order_items", "orders.id", "=", "order_items.order_id")
            ->join("products", "products.id", "=", "order_items.product_id")
            ->select(DB::raw("products.id, products.name, products.price, products.bv_point, count(products.id) as quantity"))
            ->where("order_type", "=", OrderType::MAINTENANCE->name)
            ->groupBy("products.id")
            ->get();

        return view("admin.analytics.maintenance", [
            "result" => $result
        ]);
    }

    public function general_assessment() {
        return view("admin.analytics.general-assessment");
    }

    public function fetch_data(Request $request) {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser === null || $authenticatedUser->role !== "ADMIN") {
            return response()->json([
                "message" => "Access denied"
            ], 400);
        }

        $query = $request->q;
        $start = $request->start;
        $end = $request->end;

        if ($query === null) {
            return response()->json([
                "message" => "Something went wrong"
            ], 400);
        }

        if ($query === 'registration') {
            return response()->json([
                "data" => $this->processRegistrationReport($start, $end)
            ]);
        }
    }

    private function processRegistrationReport($startDate, $endDate) {
        $result = [];
        $secondResult = DB::table("package_types")
            ->join("product_package_type", "package_types.id", "=", "product_package_type.type_id")
            ->join("registration_packages", "package_types.package_id", "=", "registration_packages.id")
            ->select(DB::raw("registration_packages.id, sum(product_package_type.quantity) as qty"))
            ->groupBy("package_types.id")
            ->get();

        if (strcmp($startDate, $endDate) === 0) {
            $date = explode(" ", $startDate)[0];

            $result = DB::table("distributors")
                ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->where("created_at", "LIKE", "$date%")
                ->groupBy("distributors.registration_package_id")
                ->get();


        }
        else {
            $result = DB::table("distributors")
                ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->whereBetween("created_at", [$startDate, $endDate])
                ->groupBy("distributors.registration_package_id")
                ->get();
        }

        $mainResult = [];

        foreach($result as $row) {
            $eachRow = new stdClass();
            $eachRow->name = $row->name;
            $eachRow->price = $row->price;
            $eachRow->bv_point = $row->bv_point;
            $eachRow->total_number = $row->total_number;

            foreach ($secondResult as $secondRow) {
                if ($row->id === $secondRow->id) {
                    $eachRow->quantity = $secondRow->qty;
                    break;
                }
            }
            $mainResult[] = $eachRow;
        }

        return $mainResult;
    }
}
