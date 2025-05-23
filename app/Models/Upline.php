<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Upline extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "user_id",
    ];

    public function ranks()
    {
        return $this->belongsToMany(Upline::class, "upline_ranks", "upline_id", "rank_id")
            ->withPivot(['id', 'status', /*'date_added'*/]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function distributors()
    {
        return $this->hasMany(Distributor::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function nextLeg()
    {
        if (count($this->distributors) === 0) {
            return "1st";
        } else if (count($this->distributors) === 1) {
            if ($this->distributors[0]->leg === "1st") {
                return "2nd";
            } else {
                return "1st";
            }
        }

        throw new \Exception("Upline's downlines is complete");
    }

    public function nextUpline($leg)
    {
        $upline = $this;
        $distributors = $upline->distributors;
        $nextDistributor = null;

        foreach ($distributors as $distributor) {
            if ($distributor->leg === $leg) {
                $nextDistributor = $distributor;
            }
        }

        return $this->findNextFreeUpline($nextDistributor, $leg);
    }

    private function findNextFreeUpline($distributor, $previousLeg)
    {
        $upline = $distributor->user->upline;

        if ($upline === null) {
            return self::create([
                "user_id" => $distributor->user->id
            ]);
        }

        if (count($upline->distributors) < 2) {
            return $upline;
        }

        $queue = [];

        foreach ($upline->distributors as $distributor) {
            $queue[] = $distributor;
        }

        $currentDistributor = null;

        while (true) {
            $currentDistributor = array_shift($queue);
            $currentUpline = $currentDistributor->user->upline;

            if ($currentUpline === null || count($currentUpline->distributors) < 2) {
                break;
            }

            foreach ($currentUpline->distributors as $distributor) {
                $queue[] = $distributor;
            }
        }

        if ($currentDistributor->user->upline) {
            return $currentDistributor->user->upline;
        }

        return self::create([
            "user_id" => $currentDistributor->user->id
        ]);
    }

    public function isLegOccupied($leg)
    {
        $distributors = $this->distributors;
        $isOccupied = false;

        foreach ($distributors as $distributor) {
            if ($distributor->leg === $leg) {
                $isOccupied = true;
            }
        }

        return $isOccupied;
    }

    public static function qualifiedForLeadershipBonus()
    {
        $qualifiedPackageIds = [4, 5, 6];
        $qualifiedUplines = [];

        $uplines = Upline::where("weekly_point", ">", 0)
            ->whereNot(function ($query) {
                $query->where("id", 1)->orWhere("id", 2);
            })->get();

        foreach ($uplines as $upline) {
            if (in_array($upline->user->distributor->getCurrentMembershipPackage()->id, $qualifiedPackageIds)) {
                $qualifiedUplines[] = $upline;
            }
        }

        return $qualifiedUplines;
    }

    public function isRankTypeAttained($rankId)
    {
        $rank = DB::table("upline_ranks")
            ->where("rank_id", $rankId)
            ->where("upline_id", $this->id)
            ->first();

        return $rank;
    }
}
