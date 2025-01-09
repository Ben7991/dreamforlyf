<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockist extends Model
{
    use HasFactory;

    protected $fillable = [
        "country",
        "city",
        "code",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankDetails()
    {
        return $this->hasOne(StockistBankDetails::class);
    }

    public static function getActiveStockist()
    {
        $activeStockistUsers = User::where('role', 'STOCKIST')->where('status', 'active')->get();
        $stockists = [];

        foreach ($activeStockistUsers as $user) {
            $stockists[] = Stockist::where('user_id', $user->id)->first();
        }

        return $stockists;
    }
}
