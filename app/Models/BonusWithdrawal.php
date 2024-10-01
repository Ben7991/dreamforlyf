<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum BonusWithdrawalStatus {
    case PENDING;
    case APPROVED;
}

class BonusWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        "amount",
        "deduction",
        "distributor_id",
        "mode"
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }
}
