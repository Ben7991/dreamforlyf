<?php

namespace App\BusinessLogic;

use App\Models\Upline;

trait FindMinimumBvPoint {
    private static function minimumBvPoints(Upline $upline) {
        $firstLegPoint = $upline->first_leg_point;
        $secondLegPoint = $upline->second_leg_point;

        return min($firstLegPoint, $secondLegPoint);
    }
}
