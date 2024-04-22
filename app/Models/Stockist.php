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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
