<?php

namespace App\BusinessLogic;

use App\Models\Distributor;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionType;
use App\Models\Upline;

final class UpgradeBonus {
    public static function giveBonus(Distributor $distributor, int $point) {
        $generation = 1;
        $referral_id = Referral::where("distributor_id", $distributor->id)->first()->upline_id;
        $upline = Upline::find($referral_id);

        while ($generation <= 3) {
            $currentDistributor = $upline->user->distributor;

            if ($upline === null || $currentDistributor === null) {
                break;
            }

            $rate = self::calculateRate($generation);
            $bonus = $point * $rate;

            $uplinePortfolio = $currentDistributor->portfolio;
            $uplinePortfolio->commission_wallet += $bonus;
            $uplinePortfolio->save();

            self::storeTransaction($bonus, $upline);

            $distributor = $upline->user->distributor;
            $referral = Referral::where("distributor_id", $distributor->id)->first();

            if ($referral === null) {
                break;
            }

            $upline = Upline::find($referral->upline_id);

            $generation++;
        }
    }

    private static function calculateRate($generation) {
        if ($generation === 1) {
            return 0.25;
        }
        else if ($generation === 2) {
            return 0.03;
        }
        else {
            return 0.02;
        }
    }

    private static function storeTransaction($amount, $upline) {
        $distributor = $upline->user->distributor;

        Transaction::create([
            "distributor_id" => $distributor->id,
            "amount" => $amount,
            "portfolio" => TransactionPortfolio::COMMISSION_WALLET->name,
            "transaction_type" => TransactionType::UPGRADE->name
        ]);
    }
}
