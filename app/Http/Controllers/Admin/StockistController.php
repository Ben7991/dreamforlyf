<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockistRequest;
use App\Mail\AccountRegistration;
use App\Models\Stockist;
use App\Models\User;
use App\Models\UserType;
use App\Utility\PasswordGenerator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StockistController extends Controller
{
    public function index() {
        return view("admin.stockist.index", [
            "totalStockist" => Stockist::count(),
            "suspendedStockists" => User::where("role", UserType::STOCKIST->name)->where("status", "suspended")->count(),
            "stockists" => Stockist::all()
        ]);
    }

    public function create() {
        return view("admin.stockist.create");
    }

    public function store(StockistRequest $request, $locale) {
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
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function show($locale, $id) {
        try {
            $user = User::findOrFail($id);
            $transfers = DB::table("stockist_transfer_history")
                            ->where("stockist_id", $user->stockist->id)
                            ->get();

            return view("admin.stockist.show", [
                "user" => $user,
                "transfers" => $transfers
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Resource not found"
            ]);
        }
    }

    public function update(StockistRequest $request, $locale, $id) {
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
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function transfer_wallet(Request $request, $locale, $id) {
        $validated = $request->validate([
            "amount" => "required|regex:/^[0-9]+(\.[0-9]{2})?$/"
        ]);

        $amount = (float)$validated["amount"];

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
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong",
                "class" => "danger"
            ]);
        }
    }

    public function transfer() {
        $amountTransfered = DB::table("stockist_transfer_history")->where("status", "COMPLETE")->sum("amount");
        $transfers = DB::table("stockist_transfer_history")
                ->join("stockists", "stockist_transfer_history.stockist_id", "stockists.id")
                ->select("stockist_transfer_history.id as id", "stockist_transfer_history.date_added as date_added",
                "stockist_transfer_history.amount as amount", "stockists.code as code", "stockist_transfer_history.status as status")
                ->get();

        return view("admin.stockist.wallet-transfer", [
            "transfers" => $transfers,
            "totalAmountTransfered" => "$" . number_format($amountTransfered, 2),
            "totalTransfer" => count($transfers)
        ]);
    }

    public function reverse_transfer($locale, $id) {
        try {
            $result = DB::table("stockist_transfer_history")->find($id);

            if ($result === null) {
                throw new Exception("Resource not found");
            }

            $stockist = Stockist::find($result->stockist_id);
            $stockist->wallet -= (float)$result->amount;
            $stockist->save();
            DB::table("stockist_transfer_history")->where("id", $id)->update(["status" => "REVERSE"]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Reversed transfer successfully"
            ]);
        }
        catch(\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }
}
