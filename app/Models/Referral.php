<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum ReferralLeg {
    case LEFT;
    case RIGHT;
}

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        "distributor_id",
        "upline_id",
        "leg"
    ];

    public function upline() {
        return $this->belongsTo(Upline::class);
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }
}
