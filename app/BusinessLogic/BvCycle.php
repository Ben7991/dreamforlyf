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

    public static function initialCycle(Upline $upline, int $point, string $leg)
    {
        $ranks = Rank::orderBy("bv_point", "desc")->get();

        $user = $upline->user;
        $distributor = $user->distributor;

        while ($upline !== null && $distributor !== null) {
            if (self::isAccountMaintained($distributor)) {
                if ($leg === "1st") {
                    $upline->first_leg_point = $upline->first_leg_point + $point;
                    $upline->left_leg_count = $upline->left_leg_count + 1;
                } else {
                    $upline->second_leg_point = $upline->second_leg_point + $point;
                    $upline->right_leg_count = $upline->right_leg_count + 1;
                }

                self::rankAward($upline, $ranks);
                BinaryBonus::distributeBonus($upline, $distributor);
            } else {
                if ($leg === '1st') {
                    $upline->left_leg_count = $upline->left_leg_count + 1;
                } else {
                    $upline->right_leg_count = $upline->right_leg_count + 1;
                }
            }


            $upline->save();

            $leg = $distributor->leg;

            $upline = $distributor->upline;
            $user = $upline->user;
            $distributor = $user->distributor;

            if ($distributor === null) {
                break;
            }
        }
    }

    private static function isAccountMaintained(Distributor $distributor)
    {
        $currentDate = new Carbon();
        $expiringDate = Carbon::parse($distributor->next_maintenance_date);
        return $expiringDate->greaterThanOrEqualTo($currentDate);
    }

    private static function rankAward($upline, $ranks)
    {
        $minimumLegPoint = self::minimumBvPoints($upline);
        $attainedRank = null;

        foreach ($ranks as $rank) {
            if ($minimumLegPoint >= $rank->bv_point) {
                $attainedRank = $rank;
                break;
            }
        }

        if ($attainedRank === null) {
            return;
        }

        $uplineRank = DB::table('upline_ranks')
            ->where('upline_id', $upline->id)
            ->where('rank_id', $attainedRank->id)
            ->first();

        if ($uplineRank === null) {
            DB::table("upline_ranks")->insert([
                "upline_id" => $upline->id,
                "rank_id" => $attainedRank->id
            ]);
        }
    }
}
