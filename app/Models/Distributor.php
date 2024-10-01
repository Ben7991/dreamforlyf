<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    public $fillable = [
        "upline_id",
        "leg",
        "registration_package_id",
        "country",
        "city",
        "user_id",
        "phone_number",
        "wave",
        "next_maintenance_date"
    ];

    public function upline() {
        return $this->belongsTo(Upline::class);
    }

    public function registrationPackage() {
        return $this->belongsTo(RegistrationPackage::class);
    }

    public function portfolio() {
        return $this->hasOne(Portfolio::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function bankDetails() {
        return $this->hasOne(BankDetail::class);
    }

    public function changePackage(RegistrationPackage $package) {
        $this->registration_package_id = $package->id;
        $this->save();
    }

    public function findDistributorLeg(Upline $upline) {
        $leg = "";
        $nextUpline = $this->upline;

        while($nextUpline !== null) {
            $currentDistirbutor = $nextUpline->user->distributor;

            if ($nextUpline->id === $upline->id) {
                $leg = $currentDistirbutor->leg;
                break;
            }

            $nextUpline = $currentDistirbutor->upline;
        }

        return $leg;
    }
}
