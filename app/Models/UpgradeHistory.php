<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "distributor_id",
        "registration_package_id",
        "upgrade_type_id"
    ];

    public function registrationPackage() {
        return $this->belongsTo(RegistrationPackage::class);
    }

    public function upgradeType() {
        return $this->belongsTo(UpgradePackage::class, "upgrade_type_id");
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }
}
