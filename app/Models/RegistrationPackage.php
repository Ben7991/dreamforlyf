<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationPackage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "name",
        "price",
        "bv_point",
        "cutoff"
    ];
}
