<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderType;
use App\Models\RegistrationPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AnalyticsController extends Controller
{
    private function getRegistrationsReport(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->leftJoin("distributors", "orders.distributor_id", "=", "distributors.id")
                ->leftJoin("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->where("orders.order_type", "=", OrderType::REGISTRATION->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("distributors.registration_package_id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->leftJoin("distributors", "orders.distributor_id", "=", "distributors.id")
                ->leftJoin("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->where("orders.order_type", "=", OrderType::REGISTRATION->name)
                ->whereBetween("date_added", [$startDate, $endDate])
                ->groupBy("distributors.registration_package_id")
                ->get();
        }

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

        return $mainResult;
    }

    public function index(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->leftJoin("distributors", "orders.distributor_id", "=", "distributors.id")
                ->leftJoin("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->where("orders.order_type", "=", OrderType::REGISTRATION->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("distributors.registration_package_id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->leftJoin("distributors", "orders.distributor_id", "=", "distributors.id")
                ->leftJoin("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name, registration_packages.price, registration_packages.bv_point, registration_packages.id"))
                ->where("orders.order_type", "=", OrderType::REGISTRATION->name)
                ->whereBetween("date_added", [$startDate, $endDate])
                ->groupBy("distributors.registration_package_id")
                ->get();
        }

        $secondResult = DB::table("package_types")
            ->join("product_package_type", "package_types.id", "=", "product_package_type.type_id")
            ->join("registration_packages", "package_types.package_id", "=", "registration_packages.id")
            ->select(DB::raw("registration_packages.id, sum(product_package_type.quantity) as qty"))
            ->groupBy("package_types.id")
            ->get();

        $mainResult = [];
        $totalGeneralProductQuantity = 0;

        foreach($result as $row) {
            $eachRow = new stdClass();
            $eachRow->name = $row->name;
            $eachRow->price = $row->price;
            $eachRow->bv_point = $row->bv_point;
            $eachRow->total_number = $row->total_number;

            foreach ($secondResult as $secondRow) {
                if ($row->id === $secondRow->id) {
                    $eachRow->quantity = $secondRow->qty;
                    $totalGeneralProductQuantity += $eachRow->quantity * $row->total_number;
                    break;
                }
            }

            $mainResult[] = $eachRow;
        }

        return view("admin.analytics.index", [
            "result" => $mainResult,
            "totalGeneralProductQuantity" => $totalGeneralProductQuantity
        ]);
    }


    private function getPersonalPurchaseReport(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        $result = [];

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::NORMAL->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("products.id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::NORMAL->name)
                ->whereBetween("date_added", [$startDate, $endDate])
                ->groupBy("products.id")
                ->get();
        }

        return $result;
    }


    public function personal_purchase(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        $totalGeneralProductQuantity = 0;
        $result = [];

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::NORMAL->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("products.id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::NORMAL->name)
                ->whereBetween("date_added", [$startDate, $endDate])
                ->groupBy("products.id")
                ->get();
        }

        foreach($result as $row) {
            $totalGeneralProductQuantity += $row->quantity;
        }

        return view("admin.analytics.personal-purchase", [
            "result" => $result,
            "totalGeneralProductQuantity" => $totalGeneralProductQuantity
        ]);
    }


    private function getUpgradeBonusReport(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");
        $upgradeHistoryResult = [];

        if (!$startDate && !$endDate) {
            $upgradeHistoryResult = DB::table('upgrade_histories')
                ->selectRaw('count(upgrade_type_id) as total, upgrade_type_id')
                ->where("created_at", "LIKE", "$currentDate%")
                ->groupBy('upgrade_type_id')
                ->get();
        } else {
            $upgradeHistoryResult = DB::table('upgrade_histories')
                ->selectRaw('count(upgrade_type_id) as total, upgrade_type_id')
                ->whereBetween("created_at", [$startDate, $endDate])
                ->groupBy('upgrade_type_id')
                ->get();
        }

        $upgradeProducts = DB::table('upgrade_package_product')->get();

        $mainResult = [];

        foreach($upgradeHistoryResult as $result) {
            foreach($upgradeProducts as $product) {
                if ($result->upgrade_type_id === $product->upgrade_package_id) {
                    $row = [
                        'total' => $result->total,
                        'upgrade_type_id' => $result->upgrade_type_id,
                        'quantity' => $product->quantity
                    ];

                    $mainResult[] = $row;
                }
            }
        }

        return $mainResult;
    }


    public function upgrade_bonus(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");
        $upgradeHistoryResult = [];

        if (!$startDate && !$endDate) {
            $upgradeHistoryResult = DB::table('upgrade_histories')
                ->selectRaw('count(upgrade_type_id) as total, upgrade_type_id')
                ->where("created_at", "LIKE", "$currentDate%")
                ->groupBy('upgrade_type_id')
                ->get();
        } else {
            $upgradeHistoryResult = DB::table('upgrade_histories')
                ->selectRaw('count(upgrade_type_id) as total, upgrade_type_id')
                ->whereBetween("created_at", [$startDate, $endDate])
                ->groupBy('upgrade_type_id')
                ->get();
        }

        $upgradeProducts = DB::table('upgrade_package_product')->get();

        $mainResult = [];

        foreach($upgradeHistoryResult as $result) {
            foreach($upgradeProducts as $product) {
                if ($result->upgrade_type_id === $product->upgrade_package_id) {
                    $row = [
                        'total' => $result->total,
                        'upgrade_type_id' => $result->upgrade_type_id,
                        'quantity' => $product->quantity
                    ];

                    $mainResult[] = $row;
                }
            }
        }

        $ids = [];

        foreach($mainResult as $result) {
            $ids[] = $result['upgrade_type_id'];
        }

        $packages = DB::table("upgrade_packages")
            ->whereIn("id", $ids)
            ->get();

        $packageData = [];

        for($i = 0; $i < count($packages); $i++) {
            $current = RegistrationPackage::find($packages[$i]->current_package_id);
            $next = RegistrationPackage::find($packages[$i]->next_package_id);

            $packageData[] = [
                'package' => $current->name . " to " . $next->name,
                'price' => $next->price - $current->price,
                'bv' => $next->bv_point - $current->bv_point,
                'quantity' => $mainResult[$i]['quantity'],
                'total' => $mainResult[$i]['total'],
            ];
        }

        return view("admin.analytics.upgrade-bonus", [
            'packages' => $packageData
        ]);
    }


    private function getMaintenanceReport(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        $result = [];

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, products.bv_point, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::MAINTENANCE->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("products.id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, products.bv_point, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::MAINTENANCE->name)
                ->groupBy("products.id")
                ->whereBetween("date_added", [$startDate, $endDate])
                ->get();
        }

        return $result;
    }


    public function maintenance(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $currentDate = date("Y-m-d");

        $totalGeneralProductQuantity = 0;
        $result = [];

        if (!$startDate && !$endDate) {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, products.bv_point, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::MAINTENANCE->name)
                ->where("date_added", "LIKE", "$currentDate%")
                ->groupBy("products.id")
                ->get();
        }
        else {
            $result = DB::table("orders")
                ->join("order_items", "orders.id", "=", "order_items.order_id")
                ->join("products", "products.id", "=", "order_items.product_id")
                ->select(DB::raw("products.name, products.price, products.bv_point, sum(order_items.quantity) as quantity"))
                ->where("order_type", "=", OrderType::MAINTENANCE->name)
                ->groupBy("products.id")
                ->whereBetween("date_added", [$startDate, $endDate])
                ->get();
        }


        foreach($result as $row) {
            $totalGeneralProductQuantity += $row->quantity;
        }

        return view("admin.analytics.maintenance", [
            "result" => $result,
            "totalGeneralProductQuantity" => $totalGeneralProductQuantity
        ]);
    }


    public function general_assessment(Request $request) {
        $registrationReport = $this->getRegistrationsReport($request);
        $upgradeReport = $this->getUpgradeBonusReport($request);
        $personalReport = $this->getPersonalPurchaseReport($request);
        $maintenanceReport = $this->getMaintenanceReport($request);

        $totalRegistration = $totalUpgrade = $totalPersonal = $totalMaintenance = 0;

        foreach($registrationReport as $report) {
            $quantity = (int)$report->quantity;
            $totalRegistration += $report->total_number * $quantity;
        }
        $registration = ($totalRegistration * 2 * 4) + ($totalRegistration * 1 * 4);

        for($i = 0; $i < count($upgradeReport); $i++) {
            $totalUpgrade += $upgradeReport[$i]['total'] * $upgradeReport[$i]['quantity'];
        }
        $upgrade = ($totalUpgrade * 2 * 4) + ($totalUpgrade * 1 * 4);

        foreach($personalReport as $report) {
            $totalPersonal += (int)$report->quantity;
        }
        $personal = ($totalPersonal * 2 * 4) + ($totalPersonal * 1 * 4);

        foreach($maintenanceReport as $report) {
            $totalMaintenance += (int)$report->quantity;
        }
        $maintenance = ($totalMaintenance * 2 * 4) + ($totalMaintenance * 1 * 4);


        return view("admin.analytics.general-assessment", [
            'registration' => [
                'first' => $totalRegistration * 2 * 4,
                'second' => $totalRegistration * 1 * 4
            ],
            'upgrade' => [
                'first' => $totalUpgrade * 2 * 4,
                'second' => $totalUpgrade * 1 * 4,
            ],
            'personal' => [
                'first' => $totalPersonal * 2 * 4,
                'second' => $totalPersonal * 1 * 4,
            ],
            'maintenance' => [
                'first' => $totalMaintenance * 2 * 4,
                'second' => $totalMaintenance * 1 * 4,
            ],
            'totalQuantity' => $maintenance + $registration + $upgrade + $personal,
        ]);
    }
}
