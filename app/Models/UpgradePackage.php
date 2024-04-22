<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradePackage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "current_package_id",
        "next_package_id",
        "image",
        "type"
    ];

    public function current_package() {
        return $this->belongsTo(RegistrationPackage::class, "current_package_id");
    }

    public function next_package() {
        return $this->belongsTo(RegistrationPackage::class, "next_package_id");
    }

    public function products() {
        return $this->belongsToMany(Product::class, "upgrade_package_product", "upgrade_package_id", "product_id")
            ->withPivot(["quantity", "id"]);
    }
}
