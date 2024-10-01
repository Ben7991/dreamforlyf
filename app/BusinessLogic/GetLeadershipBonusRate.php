<?php
namespace App\BusinessLogic;

use App\Models\RegistrationPackage;

trait GetLeadershipBonusRate {
    private static function determineRate(RegistrationPackage $distributorPackage) {
        if($distributorPackage->id === 4) {
            return 0.02;
        }
        else if ($distributorPackage->id === 5) {
            return 0.03;
        }

        return 0.05;
    }
}
