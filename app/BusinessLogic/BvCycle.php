<?php
namespace App\BusinessLogic;

use App\Models\Distributor;
use App\Models\Rank;
use App\Models\Upline;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class BvCycle
{
    use FindMinimumBvPoint;

    public static function initialCycle(Upline $upline, int $point, string $leg) {
        $distributor = $upline->user->distributor;

        while ($upline !== null && $distributor !== null) {
            if (self::isAccountMaintained($distributor)) {
                self::incrementBvPoint($upline, $leg, $point);

                if ($leg === "1st") {
                    $upline->left_leg_count++;
                } else if ($leg === "2nd") {
                    $upline->right_leg_count++;
                }

                self::rankAward($upline);
                BinaryBonus::distributeBonus($upline);
            }

            $upline->save();

            $distributor = $upline->user->distributor;

            if ($distributor === null) {
                break;
            }

            $upline = $distributor->upline;
            $leg = $distributor->leg;
        }
    }

    private static function isAccountMaintained(Distributor $distributor) {
        $currentDate = new Carbon();
        $expiringDate = Carbon::parse($distributor->next_maintenance_date);
        return $expiringDate->greaterThanOrEqualTo($currentDate);
    }

    /**
     * increment upline leg point based on distributors leg
     */
    private static function incrementBvPoint(Upline $upline, string $leg, int $point) {
        if ($leg === "1st") {
            $upline->first_leg_point += $point;
        }
        else {
            $upline->second_leg_point += $point;
        }
    }

    private static function rankAward($upline) {
        $minimumLegPoint = self::minimumBvPoints($upline);

        $ranks = Rank::orderBy("bv_point", "desc")->get();
        $attainedRank = null;

        foreach($ranks as $rank) {
            if ($minimumLegPoint >= $rank->bv_point) {
                $attainedRank = $rank;
                break;
            }
        }

        if ($attainedRank === null) {
            return;
        }

        $uplineRanks = DB::table('upline_ranks')->where('upline_id', $upline->id)->get();

        foreach($uplineRanks as $alreadyAttainedRank) {
            if ($alreadyAttainedRank->rank_id === $attainedRank->id) {
                return;
            }
        }

        DB::table("upline_ranks")->insert([
            "upline_id" => $upline->id,
            "rank_id" => $attainedRank->id
        ]);
    }
}
