<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::all();
        $total = count($orders);
        $pending = Order::where("status", "PENDING")->count();
        $approved = Order::where("status", "APPROVED")->count();

        return view("admin.order.index", [
            "orders" => $orders,
            "pending" => $pending,
            "approved" => $approved,
            "total" => $total
        ]);
    }

    public function show($locale, $id) {
        try {
            $order = Order::findOrFail($id);
            return view("admin.order.show", [
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

    public function update(Request $request, $locale, $id) {
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
}
