<?php

namespace App\Http\Controllers\Stockist;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Transaction;
use App\Models\TransactionalStatus;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockistController extends Controller
{
    public function index() {
        $result = DB::table("transactions")
                    ->join("stockist_transfer", "transactions.id", "stockist_transfer.transaction_id")
                    ->join("distributors", "distributors.id", "transactions.distributor_id")
                    ->join("users", "users.id", "distributors.user_id")
                    ->select("transactions.amount", "users.name", "users.id", "stockist_transfer.date_added")
                    ->get();

        return view("stockist.index", [
            "pendingOrderCount" => Order::where("status", OrderStatus::PENDING->name)->count(),
            "transferCount" => count($result),
            "transfers" => $result
        ]);
    }

    public function orderHistory() {
        $orders = Order::all();
        $total = count($orders);
        $pending = Order::where("status", "PENDING")->count();
        $approved = Order::where("status", "APPROVED")->count();

        return view("stockist.order-history", [
            "orders" => $orders,
            "pending" => $pending,
            "approved" => $approved,
            "total" => $total
        ]);
    }

    public function orderDetails($locale, $id) {
        try {
            $order = Order::findOrFail($id);
            return view("stockist.order-details", [
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

    public function changeOrderStatus(Request $request, $locale, $id) {
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

    public function transferWallet() {
        $result = DB::table("transactions")
                    ->join("stockist_transfer", "transactions.id", "stockist_transfer.transaction_id")
                    ->join("distributors", "distributors.id", "transactions.distributor_id")
                    ->join("users", "users.id", "distributors.user_id")
                    ->select("transactions.amount", "users.name", "users.id", "stockist_transfer.date_added")
                    ->get();

        return view("stockist.transfer-wallet", [
            "transfers" => $result
        ]);
    }

    public function sendDistributorWallet(Request $request, $locale, $id) {
        $validated = $request->validate([
            "amount" => "required|numeric"
        ]);
        $amount = (float)$validated["amount"];

        try {
            $currentStockist = Auth::user()->stockist;
            if ($currentStockist->wallet < $amount) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Insufficient wallet to perform transfer"
                ]);
            }

            $user = User::findOrFail($id);
            $distributor = $user->distributor;
            $portfolio = $distributor->portfolio;

            $portfolio->current_balance += $amount;
            $portfolio->save();

            $transaction = Transaction::create([
                "distributor_id" => $distributor->id,
                "amount" => $amount,
                "portfolio" => TransactionalStatus::CURRENT_BALANCE->name,
                "transaction_type" => TransactionType::DEPOSIT->name,
            ]);

            DB::table("stockist_transfer")->insert([
                "transaction_id" => $transaction->id
            ]);

            $currentStockist->wallet -= $amount;
            $currentStockist->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Transfer completed successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function profile() {
        return view("stockist.profile");
    }
}
