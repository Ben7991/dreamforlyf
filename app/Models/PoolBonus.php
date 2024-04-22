<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum PoolBonusStatus {
    case PENDING;
    case AWARDED;
}

class PoolBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        "upline_id",
        "rank_id"
    ];

    public function upline() {
        return $this->belongsTo(Upline::class);
    }
}
