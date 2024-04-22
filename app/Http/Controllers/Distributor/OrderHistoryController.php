<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    public function index() {
        $currentUser = Auth::user();
        $distributor = $currentUser->distributor;
        $orders = $distributor->orders;

        $totalOrders = $orders->count();
        $totalPendingOrders = Order::where("status", "pending")->where("distributor_id", $distributor->id)->count();
        $totalApprovedOrders = Order::where("status", "approve")->where("distributor_id", $distributor->id)->count();

        return view("distributor.order-history.index", [
            "orders" => $orders,
            "totalOrders" => $totalOrders,
            "totalPendingOrders" => $totalPendingOrders,
            "totalApprovedOrders" => $totalApprovedOrders,
        ]);
    }

    public function show($locale, $id) {
        try {
            $existingOrder = Order::findOrFail($id);
            $existingOrderItems = DB::table("order_items")->where("order_id", $existingOrder->id)->get();
            $orderItems = [];

            foreach($existingOrderItems as $item) {
                $orderItems[] = [
                    "product" => Product::find($item->product_id),
                    "quantity" => $item->quantity
                ];
            }

            return view("distributor.order-history.show", [
                "order" => $existingOrder,
                "orderItems" => $orderItems
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back();
        }
    }
}
