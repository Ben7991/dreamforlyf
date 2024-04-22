<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "distributor_id"
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }

    public function subtractPurchaseAmount($amount) {
        $this->current_balance = $this->current_balance - $amount;
        $this->save();
    }
}
