<?php

namespace App\BusinessLogic;

use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionType;
use App\Models\Upline;

final class LeadershipBonus
{
    use CheckForLeadershipBonus, GetLeadershipBonusRate;

    public static function giveBonus(Upline $upline) {
        $distributor = $upline->user->distributor;

        if ($distributor === null) {
            return;
        }

        $currentPackage = $distributor->getCurrentMembershipPackage();
        $isEligibleForBonus = self::isQualified($currentPackage);

        if (!$isEligibleForBonus) {
            return;
        }

        $rate = self::determineRate($currentPackage);
        $bonus = $upline->weekly_point * $rate; // => new

        $portfolio = $distributor->portfolio;
        $portfolio->commission_wallet += $bonus;
        $portfolio->save();

        Transaction::create([
            "distributor_id" => $distributor->id,
            "amount" => $bonus,
            "portfolio" => TransactionPortfolio::COMMISSION_WALLET->name,
            "transaction_type" => TransactionType::LEADERSHIP->name
        ]);

        $upline->weekly_point = 0;
        $upline->save();
    }
}
