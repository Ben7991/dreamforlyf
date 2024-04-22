<?php

namespace App\Http\Controllers\Distributor;

use App\BusinessLogic\CheckForLeadershipBonus;
use App\BusinessLogic\GetLeadershipBonusRate;
use App\Http\Controllers\Controller;
use App\Mail\WithdrawalRequest;
use App\Models\BonusWithdrawal;
use App\Models\BonusWithdrawalStatus;
use App\Models\Transaction;
use App\Models\TransactionalStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PortfolioController extends Controller
{
    use CheckForLeadershipBonus, GetLeadershipBonusRate;

    public function index() {
        $currentUser = Auth::user();
        $distributor = $currentUser->distributor;
        $portfolio = $distributor->portfolio;

        $personalWallet = Transaction::where("portfolio", TransactionalStatus::PERSONAL_WALLET->name)
                ->where("distributor_id", $distributor->id)->sum("amount");

        $upline = $currentUser->upline;
        $leadershipWallet = 0;
        $leadershipWeeklyPoint = 0;

        if ($upline !== null && self::isQualified($distributor->registrationPackage)) {
            $rate = self::determineRate($distributor->registrationPackage);
            $leadershipWallet = $upline->weekly_point * $rate;
            $leadershipWeeklyPoint = $upline->weekly_point;
        }

        $totalWithdrawals = BonusWithdrawal::where("distributor_id", $distributor->id)->sum("amount");

        return view("distributor.portfolio.index", [
            "portfolio" => $portfolio,
            "personalWallet" => $personalWallet,
            "leadershipWallet" => $leadershipWallet,
            "totalWithdrawals" => $totalWithdrawals,
            "leadershipWeelyPoint" => $leadershipWeeklyPoint
        ]);
    }

    public function bonus_withdrawal() {
        $distributor = Auth::user()->distributor;
        $withdrawals = BonusWithdrawal::where("distributor_id", $distributor->id)->get();
        $pending = BonusWithdrawal::where("distributor_id", $distributor->id)->where("status", BonusWithdrawalStatus::PENDING->name)->count();
        $approved = BonusWithdrawal::where("distributor_id", $distributor->id)->where("status", BonusWithdrawalStatus::APPROVED->name)->count();

        return view("distributor.portfolio.bonus-withdrawal", [
            "withdrawals" => $withdrawals,
            "totalRequest" => count($withdrawals),
            "pending" => $pending,
            "approved" => $approved
        ]);
    }

    public function withdrawal_request(Request $request, $locale) {
        $amount = $request->amount;
        $distributor = Auth::user()->distributor;
        $portfolio = $distributor->portfolio;
        $preparedAmount = (float)$amount;

        if ($this->checkPendingWithdrawals($distributor)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "You have a pending request, please await for it to be resolved before you can request again"
            ]);
        }

        if ($amount === "" || !preg_match("/^[0-9]+(\.[0-9]{2})*$/", $amount)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Validation failed"
            ]);
        }
        else if ($preparedAmount > $portfolio->commission_wallet) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Insufficient commission balance to make withdrawal"
            ]);
        }

        if (!Hash::check($request->code, $distributor->code)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Invalid code"
            ]);
        }

        try {
            $deduction = $preparedAmount * 0.05;

            $result = BonusWithdrawal::create([
                "amount" => $preparedAmount,
                "deduction" => $deduction,
                "distributor_id" => $distributor->id
            ]);

            $user = Auth::user();

            Mail::to($user->email)->send(
                new WithdrawalRequest($result->id, $user->name, $user->email, $preparedAmount)
            );

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Request was successfully made"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function checkPendingWithdrawals($distributor) {
        $pendingWithdrawals = BonusWithdrawal::where("distributor_id", $distributor->id)
            ->where("status", BonusWithdrawalStatus::PENDING->name)
            ->count();

        return $pendingWithdrawals > 0;
    }

    public function transaction_history() {
        $distributor = Auth::user()->distributor;
        $transactionCount = Transaction::where("distributor_id", $distributor->id)->count();
        $transactions = Transaction::where("distributor_id", $distributor->id)->get();

        return view("distributor.portfolio.transaction-history", [
            "transactionCount" => $transactionCount,
            "transactions" => $transactions
        ]);
    }
}
