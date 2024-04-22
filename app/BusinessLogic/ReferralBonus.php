<?php

namespace App\BusinessLogic;

use App\Models\Referral;
use App\Models\RegistrationPackage;
use App\Models\Transaction;
use App\Models\TransactionalStatus;
use App\Models\TransactionType;
use App\Models\Upline;

final class ReferralBonus {
    private function __construct() { }

    public static function distributeBonus(Upline $referral, RegistrationPackage $package) {
        $uplineNumber = 1;

        while ($uplineNumber <= 3 && $referral !== null) {
            $distributor = $referral->user->distributor;

            if ($distributor === null) {
                break;
            }

            $portfolio = $distributor->portfolio;
            $bonusRate = 0;

            if ($uplineNumber === 1) {
                $bonusRate = 0.25;
            }
            else if ($uplineNumber === 2) {
                $bonusRate = 0.03;
            }
            else if ($uplineNumber === 3) {
                $bonusRate = 0.02;
            }

            $amount = $package->bv_point * $bonusRate;
            $portfolio->commission_wallet += $amount;
            $portfolio->save();

            Transaction::create([
                "amount" => $amount,
                "distributor_id" => $distributor->id,
                "portfolio" => TransactionalStatus::COMMISSION_WALLET->name,
                "transaction_type" => TransactionType::REFERRAL->name
            ]);

            // $referral = $distributor->upline;
            $referralInfo = Referral::where("distributor_id", $distributor->id)->first();

            if ($referralInfo === null) {
                break;
            }

            $referral_id = $referralInfo->upline_id;
            $referral = Upline::find($referral_id);
            $uplineNumber++;
        }
    }
}
