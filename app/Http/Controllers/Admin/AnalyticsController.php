<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index() {
        return view("admin.analytics.index");
    }

    public function registration() {
        $result = DB::table("distributors")
            ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
            ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name"))
            ->groupBy("distributors.registration_package_id")
            ->get();

        return view("admin.analytics.registration", [
            "result" => $result
        ]);
    }

    public function bonus() {
        return view("admin.analytics.bonus");
    }

    public function withdrawal() {
        return view("admin.analytics.withdrawal");
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
        if (strcmp($startDate, $endDate) === 0) {
            $date = explode(" ", $startDate)[0];

            return DB::table("distributors")
                ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name"))
                ->where("created_at", "LIKE", "$date%")
                ->groupBy("distributors.registration_package_id")
                ->get();
        }

        return DB::table("distributors")
                ->join("registration_packages", "distributors.registration_package_id", "=", "registration_packages.id")
                ->select(DB::raw("count(distributors.id) as total_number, registration_packages.name"))
                ->whereBetween("created_at", [$startDate, $endDate])
                ->groupBy("distributors.registration_package_id")
                ->get();
    }
}
