<?php

namespace App\BusinessLogic;

use App\Models\Distributor;
use App\Models\RegistrationPackage;
use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionType;

final class CashBack
{
    private function __construct() { }

    public static function giveCashBackBonus(Distributor $distributor, RegistrationPackage $package) {
        $portfolio = $distributor->portfolio;
        $bvPoint = $package->bv_point;

        $amount = 0.06 * $bvPoint;
        $portfolio->commission_wallet = $amount;

        Transaction::create([
            "amount" => $amount,
            "distributor_id" => $distributor->id,
            "portfolio" => TransactionPortfolio::COMMISSION_WALLET->name,
            "transaction_type" => TransactionType::CASH_BACK->name
        ]);

        $portfolio->save();
    }
}
