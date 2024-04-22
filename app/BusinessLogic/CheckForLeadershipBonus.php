<?php

namespace App\BusinessLogic;

use App\Models\RegistrationPackage;

trait CheckForLeadershipBonus
{
    private static function isQualified(RegistrationPackage $distributorPackage) {
        $qualifiedPackages = RegistrationPackage::where("id", ">=", 3)->get();
        $isQualified = false;

        foreach($qualifiedPackages as $package) {
            if ($package->id === $distributorPackage->id) {
                $isQualified = true;
            }
        }

        return $isQualified;
    }
}
