<?php

namespace App\BusinessLogic;

use App\Models\Transaction;
use App\Models\TransactionalStatus;
use App\Models\TransactionType;
use App\Models\Upline;
use Carbon\Carbon;

final class BinaryBonus
{
    use FindMinimumBvPoint, CheckForLeadershipBonus;

    public static function distributeBonus(Upline $upline) {
        $minimumBvPoint = self::minimumBvPoints($upline);
        $lastAwardedPoint = $upline->last_awarded_point;

        $pointDifference = $minimumBvPoint - $lastAwardedPoint;

        if ($pointDifference === 0) {
            return;
        }

        $currentDate = Carbon::now();
        $lastAwardedDate = Carbon::parse($upline->last_awarded_date);
        $dayDifference = $lastAwardedDate->diffInDays($currentDate);

        if ($dayDifference >= 1) {
            $upline->last_awarded_date = Carbon::now();
            $upline->last_amount_paid = 0;
        }

        $amount = $pointDifference * 0.125;
        $distributor = $upline->user->distributor;

        if ($distributor === null) {
            return;
        }

        $portfolio = $distributor->portfolio;
        $registrationPackage = $distributor->registrationPackage;

        if ($upline->last_amount_paid < $registrationPackage->cutoff) {
            $portfolio->commission_wallet += $amount;
            $upline->last_amount_paid += $amount;
            $remainingAmount = $upline->last_amount_paid - $registrationPackage->cutoff;
            $upline->last_awarded_point += $pointDifference;   // update last awarded point;

            if (self::isQualified($registrationPackage)) {
                $upline->weekly_point += $pointDifference;
            }

            if ($remainingAmount > 0) { // if excess we subtract amount
                if (self::isQualified($registrationPackage)) {
                    $upline->weekly_point -= $pointDifference;
                }

                $upline->last_amount_paid -= $remainingAmount;
                $portfolio->commission_wallet -= $remainingAmount;

                Transaction::create([
                    "distributor_id" => $distributor->id,
                    "amount" => $remainingAmount,
                    "portfolio" => TransactionalStatus::COMMISSION_WALLET->name,
                    "transaction_type" => TransactionType::BINARY->name
                ]);
            }
            else {
                Transaction::create([
                    "distributor_id" => $distributor->id,
                    "amount" => $amount,
                    "portfolio" => TransactionalStatus::COMMISSION_WALLET->name,
                    "transaction_type" => TransactionType::BINARY->name
                ]);
            }

            $portfolio->save();
        }
        else if ($upline->last_amount_paid === $registrationPackage->cutoff) {
            $upline->last_awarded_point += $pointDifference;
        }
    }
}
