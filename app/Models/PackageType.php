<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function registration_package() {
        return $this->belongsTo(RegistrationPackage::class, "package_id");
    }

    public function products() {
        return $this->belongsToMany(Product::class, "product_package_type", "type_id", "product_id")
            ->withPivot("quantity", "id");
    }
}
