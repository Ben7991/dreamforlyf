<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum TransactionPortfolio {
    case CURRENT_BALANCE;
    case PERSONAL_WALLET;
    case LEADERSHIP_WALLET;
    case COMMISSION_WALLET;
}

enum TransactionType {
    case CASH_BACK;
    case REFERRAL;
    case BINARY;
    case LEADERSHIP;
    case UPGRADE;
    case PERSONAL;
    case DEPOSIT;
}

enum TransactionStatus {
    case COMPLETE;
    case REVERSED;
}

class Transaction extends Model
{
    use HasFactory;

    public $fillable = [
        "distributor_id",
        "amount",
        "portfolio",
        "transaction_type",
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }
}
