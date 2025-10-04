<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockistRequest;
use App\Mail\AccountRegistration;
use App\Models\Stockist;
use App\Models\User;
use App\Models\UserType;
use App\Utility\PasswordGenerator;
use App\View\Components\Layout\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StockistController extends Controller
{
    public function index()
    {
        return view("admin.stockist.index", [
            "totalStockist" => Stockist::count(),
            "suspendedStockists" => User::where("role", UserType::STOCKIST->name)->where("status", "suspended")->count(),
            "stockists" => Stockist::getActiveStockist()
        ]);
    }

    public function create()
    {
        return view("admin.stockist.create", [
            "countries" => DB::table('countries')->get()
        ]);
    }

    public function store(StockistRequest $request, $locale)
    {
        $validated = $request->validated();

        try {
            $generatedPassword = PasswordGenerator::generate();

            $user = User::create([
                'id' => User::nextId(),
                'name' => $validated["name"],
                'email' => $validated["email"],
                'password' => $generatedPassword,
                "role" => UserType::STOCKIST->name
            ]);

            $stockist = Stockist::create([
                "country" => $validated["country"],
                "city" => $validated["city"],
                "code" => $validated["code"],
                "user_id" => $user->id
            ]);

            Mail::to($validated["email"])->send(
                new AccountRegistration($validated["name"], $validated["email"], $generatedPassword)
            );

            return redirect("/$locale/admin/stockists")->with([
                "class" => "success",
                "message" => "Added new stockist successfully"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function show($locale, $id)
    {
        try {
            $user = User::findOrFail($id);
            $transfers = DB::table("stockist_transfer_history")
                ->where("stockist_id", $user->stockist->id)
                ->get();

            return view("admin.stockist.show", [
                "user" => $user,
                "transfers" => $transfers
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Resource not found"
            ]);
        }
    }

    public function update(StockistRequest $request, $locale, $id)
    {
        $validated = $request->validated();

        try {
            $user = User::findOrFail($id);
            $stockist = $user->stockist;

            $user->name = $validated["name"];
            $user->email = $validated["email"];
            $user->save();

            $stockist->country = $validated["country"];
            $stockist->code = $validated["code"];
            $stockist->city = $validated["city"];
            $stockist->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Stockist details updated successfully"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function transfer_wallet(Request $request, $locale, $id)
    {
        $validated = $request->validate([
            "amount" => "required|regex:/^[0-9]+(\.[0-9]{2})?$/"
        ]);

        $amount = (float) $validated["amount"];

        try {
            $user = User::findOrFail($id);
            $stockist = $user->stockist;

            DB::table("stockist_transfer_history")->insert([
                "amount" => $amount,
                "stockist_id" => $stockist->id
            ]);

            $stockist->wallet += $amount;
            $stockist->save();

            return redirect()->back()->with([
                "message" => "Successfully transferred wallet to stockists",
                "class" => "success"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong",
                "class" => "danger"
            ]);
        }
    }

    public function transfer()
    {
        $amountTransfered = DB::table("stockist_transfer_history")->where("status", "COMPLETE")->sum("amount");
        $transfers = DB::table("stockist_transfer_history")
            ->join("stockists", "stockist_transfer_history.stockist_id", "stockists.id")
            ->select(
                "stockist_transfer_history.id as id",
                "stockist_transfer_history.date_added as date_added",
                "stockist_transfer_history.amount as amount",
                "stockists.code as code",
                "stockist_transfer_history.status as status"
            )
            ->get();

        return view("admin.stockist.wallet-transfer", [
            "transfers" => $transfers,
            "totalAmountTransfered" => "$" . number_format($amountTransfered, 2),
            "totalTransfer" => count($transfers)
        ]);
    }

    public function reverse_transfer($locale, $id)
    {
        try {
            $result = DB::table("stockist_transfer_history")->find($id);

            if ($result === null) {
                throw new Exception("Resource not found");
            }

            $stockist = Stockist::find($result->stockist_id);
            $stockist->wallet -= (float) $result->amount;
            $stockist->save();
            DB::table("stockist_transfer_history")->where("id", $id)->update(["status" => "REVERSE"]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Reversed transfer successfully"
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }


    public function stockist_withdrawals()
    {
        $total = DB::table("stockist_withdrawal")->count();
        $pending = DB::table("stockist_withdrawal")->where("status", "PENDING")->count();
        $approved = DB::table("stockist_withdrawal")->where("status", "APPROVED")->count();
        $withdrawals = DB::table("stockist_withdrawal")
            ->join("stockists", "stockist_withdrawal.stockist_id", "=", "stockists.id")
            ->select(DB::raw("stockist_withdrawal.created_at, stockist_withdrawal.amount, stockist_withdrawal.deduction, stockist_withdrawal.status, stockists.code, stockist_withdrawal.id"))
            ->get();

        return view("admin.stockist.withdrawal", [
            "total" => $total,
            "pending" => $pending,
            "approved" => $approved,
            "withdrawals" => $withdrawals
        ]);
    }

    public function stockist_withdrawal_requests()
    {
        $total = DB::table("stockist_withdrawal_request")
            ->where("stockist_request", "REQUESTED")
            ->count();
        $requests = DB::table("stockist_withdrawal_request")->where("stockist_request", "REQUESTED")
            ->join("stockists", "stockist_withdrawal_request.stockist_id", "=", "stockists.id")
            ->select(DB::raw("stockists.code, stockist_withdrawal_request.id"))
            ->get();

        return view("admin.stockist.withdrawal-request", [
            "total" => $total,
            "requests" => $requests
        ]);
    }


    public function approve_request($locale, $id)
    {
        try {
            $request = DB::table("stockist_withdrawal_request")->find($id);

            if ($request === null) {
                throw new Exception("Please check and try approving the request again");
            }

            DB::table("stockist_withdrawal_request")
                ->where("id", $id)
                ->update([
                    "approval_status" => "APPROVED",
                    "stockist_request" => "PENDING"
                ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Request approved"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }


    public function withdrawal_details($locale, $id)
    {
        try {
            $withdrawal = DB::table("stockist_withdrawal")
                ->join("stockists", "stockist_withdrawal.stockist_id", "=", "stockists.id")
                ->select(DB::raw("stockist_withdrawal.created_at, stockist_withdrawal.amount, stockist_withdrawal.deduction, stockist_withdrawal.status, stockists.code, stockist_withdrawal.id, stockist_withdrawal.stockist_id, stockist_withdrawal.mode"))
                ->where("stockist_withdrawal.id", $id)
                ->first();

            if ($withdrawal === null) {
                throw new Exception("Please select the right details");
            }

            $bankDetails = DB::table("stockist_bank_details")
                ->where("stockist_id", $withdrawal->stockist_id)
                ->first();

            return view("admin.stockist.withdrawal-details", [
                "withdrawalDetails" => $withdrawal,
                "bankDetails" => $bankDetails
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }


    public function withdrawal_approve($locale, $id)
    {
        try {
            $withdrawalDetails = DB::table("stockist_withdrawal")->find($id);

            if ($withdrawalDetails === null) {
                throw new Exception("Ensure you are making the right selection");
            }

            DB::table("stockist_withdrawal")
                ->where("id", $id)
                ->update([
                    "status" => "APPROVED"
                ]);

            $stockist = Stockist::find($withdrawalDetails->stockist_id);
            $stockist->bonus -= $withdrawalDetails->amount;
            $stockist->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully approved withdrawal"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function suspend_account(Request $request, $locale, $id)
    {
        try {
            $stockistUserDetail = User::findOrFail($id);
            $stockistUserDetail->status = 'suspend';
            $stockistUserDetail->save();

            return redirect()->back()->with([
                'class' => 'success',
                'message' => 'Stockist account has sucessfully been suspended'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'class' => 'success',
                'message' => $e->getMessage()
            ]);
        }
    }
}
