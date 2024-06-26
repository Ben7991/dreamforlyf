<?php

namespace App\Http\Middleware;

use App\BusinessLogic\LegCounter;
use App\Models\BonusWithdrawal;
use App\Models\Order;
use App\Models\PoolBonus;
use App\Models\PoolBonusStatus;
use App\Models\UserType;
use App\BusinessLogic\PoolBonus as BusinessPoolBonus;
use App\Models\Announcement;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDistributor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role !== UserType::DISTRIBUTOR->name) {
            return redirect()->back();
        }

        $ordersCount = Order::where("distributor_id", $loggedInUser->distributor->id)->where("status", "PENDING")->count();
        $request->session()->put("orders_count", $ordersCount);

        $withdrawalCount = BonusWithdrawal::where("distributor_id", $loggedInUser->distributor->id)->where("status", "PENDING")->count();
        $request->session()->put("withdrawal_count", $withdrawalCount);

        $isWithdrawalDays = Carbon::TUESDAY === Carbon::now()->dayOfWeek || Carbon::WEDNESDAY === Carbon::now()->dayOfWeek;
        $request->session()->put("isWithdrawalDay", $isWithdrawalDays);

        $upline = $loggedInUser->upline;

        if ($upline !== null) {
            BusinessPoolBonus::giveBonus($loggedInUser->upline);

            $pending = 0;
            foreach($upline->ranks as $rank) {
                if ($rank->pivot->status === "PENDING") {
                    $pending++;
                }
            }
            $request->session()->put("qualified_rank_count", $pending);

            $totalQualified = PoolBonus::where("upline_id", $upline->id)->where("status", PoolBonusStatus::PENDING->name)->count();
            $request->session()->put("qualified_pool_count", $totalQualified);
        } else {
            $request->session()->put("qualified_rank_count", 0);
            $request->session()->put("qualified_pool_count", 0);
        }

        $currentAnnouncement = Announcement::first();

        if ($currentAnnouncement !== null) {
            session()->put("announcement", $currentAnnouncement->description);
        }
        else if (session()->get("announcement")) {
            session()->remove("announcement");
        }

        $distributor = Auth::user()->distributor;
        LegCounter::counterDistributorsInEachLeg($distributor);


        return $next($request);
    }
}
