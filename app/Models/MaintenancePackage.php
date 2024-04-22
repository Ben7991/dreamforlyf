<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePackage extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "duration_in_months",
        "total_products",
        "total_price",
        "bv_point"
    ];
}
