<?php

namespace App\BusinessLogic;

use App\Models\Distributor;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionPortfolio;
use App\Models\TransactionType;

final class PersonalBonus {
    public static function giveBonus(Distributor $distributor, Product $product, int $purchaseQuantity) {
        $portfolio = $distributor->portfolio;
        $bvPoint = $product->bv_point * $purchaseQuantity;

        $amount = 0.1 * $bvPoint;
        $portfolio->commission_wallet += $amount;

        Transaction::create([
            "amount" => $amount,
            "distributor_id" => $distributor->id,
            "portfolio" => TransactionPortfolio::PERSONAL_WALLET->name,
            "transaction_type" => TransactionType::PERSONAL->name
        ]);

        $portfolio->save();
    }
}
