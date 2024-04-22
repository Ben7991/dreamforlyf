<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends LocalizedModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "name",
        "quantity",
        "price",
        "image",
        "description_en",
        "description_fr",
        "bv_point"
    ];

    protected $localizedAttributes = [
        "description"
    ];
}
