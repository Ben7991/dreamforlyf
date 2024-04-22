<?php
namespace App\BusinessLogic;

use App\Models\Distributor;
use App\Models\PoolBonus as ModelsPoolBonus;
use App\Models\Rank;
use App\Models\Upline;
use Illuminate\Support\Facades\DB;

final class PoolBonus
{
    public static function giveBonus(Upline $upline) {
        $hasQualifiedPackage  = self::hasHighestPackage($upline);

        if (!$hasQualifiedPackage) {
            return;
        }

        $rankToCheck = self::getNextRankToCheck($upline);
        $attainedRank = DB::table("upline_ranks")->where("upline_id", $upline->id)->where("rank_id", $rankToCheck->id)->first();

        if ($attainedRank === null) {
            return;
        }

        $referredDistributors = $upline->referrals;

        if (count($referredDistributors) === 0) {
            return;
        }


        $leftDistributor = self::getDistributor("1st", $referredDistributors);
        $rightDistributor = self::getDistributor("2nd", $referredDistributors);

        if ($leftDistributor === null || $rightDistributor === null) {
            return;
        }

        $leftDistributorUplineDetail = $leftDistributor->user->upline;
        $rightDistributorUplineDetail = $rightDistributor->user->upline;

        $leftUplineAttainedRank = DB::table("upline_ranks")->where("upline_id", $leftDistributorUplineDetail->id)->where("rank_id", $rankToCheck->id)->first();
        $rightUplineAttainedRank = DB::table("upline_ranks")->where("upline_id", $rightDistributorUplineDetail->id)->where("rank_id", $rankToCheck->id)->first();

        if ($leftUplineAttainedRank === null || $rightUplineAttainedRank === null) {
            return;
        }

        // if all criteria are satisfied then the details get added for the specific upline
        ModelsPoolBonus::create([
            "upline_id" => $upline->id,
            "rank_id" => $rankToCheck->id
        ]);
    }

    private static function hasHighestPackage(Upline $upline) {
        $distributor = $upline->user->distributor;
        $currentPackage = $distributor->registrationPackage;
        return $currentPackage->id === 5;
    }

    private static function getDistributor($leg, $referredDistributors) {
        $foundDistributor = null;

        foreach($referredDistributors as $referredDistributor) {
            $distributor = Distributor::find($referredDistributor->distributor_id);
            $upline = $distributor->user->upline;

            if ($distributor->leg === $leg && $upline !== null) {
                if (self::hasHighestPackage($upline)) {
                    $foundDistributor = $distributor;
                }
                break;
            }
        }

        return $foundDistributor;
    }

    private static function getNextRankToCheck($upline) {
        $existingPoolRecords = ModelsPoolBonus::where("upline_id", $upline->id)->get();
        $nextRank = null;

        if (count($existingPoolRecords) === 0) {
            $nextRank = Rank::find(1); // first rank
        }
        else {
            $id = count($existingPoolRecords) + 1;
            $nextRank = Rank::find($id);
        }

        return $nextRank;
    }
}
