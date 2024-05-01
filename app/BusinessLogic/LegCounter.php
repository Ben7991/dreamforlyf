<?php
namespace App\BusinessLogic;

use App\Models\Distributor;

abstract class LegCounter
{
    public static $visitedDistributors = [];

    public static function counterDistributorsInEachLeg(Distributor $currentDistributor) {
        $visitedDistributors = self::determineDistributorsInEachLeg($currentDistributor);

        if (count($visitedDistributors) === 1) {
            return;
        }

        $totalLeftCount = $totalRightCount = 0;
        $isLeftFound = $isRightFound = false;
        $currentUpline = $currentDistributor->user->upline;

        for($i = 1; $i < count($visitedDistributors); $i++) {
            if ($visitedDistributors[$i]->upline->id === $currentUpline->id) {
                if ($visitedDistributors[$i]->leg === "1st") {
                    $isLeftFound = true;
                }

                if ($visitedDistributors[$i]->leg === "2nd") {
                    $isRightFound = true;
                }
            }

            if ($isLeftFound && $isRightFound) {
                $totalRightCount++;
            }
            else if ($isLeftFound) {
                $totalLeftCount++;
            }
            else if ($isRightFound) {
                $totalRightCount++;
            }
        }

        $currentUpline->left_leg_count = $totalLeftCount;
        $currentUpline->right_leg_count = $totalRightCount;
        $currentUpline->save();
    }

    private static function determineDistributorsInEachLeg(Distributor $rootDistributor) {
        self::traverse($rootDistributor);
        return self::$visitedDistributors;
    }

    private static function traverse(Distributor $node) {
        array_push(self::$visitedDistributors, $node);
        $leftDistributor = $rightDistributor = null;

        $currentUpline = $node->user->upline;

        if ($currentUpline === null) {
            return;
        }

        foreach($currentUpline->distributors as $distributor) {
            if ($distributor->leg === "1st") {
                $leftDistributor = $distributor;
                break;
            }
        }

        if ($leftDistributor !== null) {
            self::traverse($leftDistributor);
        }

        foreach($currentUpline->distributors as $distributor) {
            if ($distributor->leg === "2nd") {
                $rightDistributor = $distributor;
                break;
            }
        }

        if ($rightDistributor !== null) {
            self::traverse($rightDistributor);
        }
    }
}
