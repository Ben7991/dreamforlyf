<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index() {
        return view("admin.analytics.index");
    }

    public function registration() {
        return view("admin.analytics.registration");
    }

    public function bonus() {
        return view("admin.analytics.bonus");
    }

    public function withdrawal() {
        return view("admin.analytics.withdrawal");
    }

    public function maintenance() {
        return view("admin.analytics.maintenance");
    }

    public function fetch_data(Request $request) {
        $query = $request->q;

        if ($query === null) {
            return response()->json([
                "message" => "Something went wrong"
            ], 400);
        }
    }
}
