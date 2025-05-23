<?php

namespace App\Http\Middleware;

use App\Models\BonusWithdrawal;
use App\Models\BonusWithdrawalStatus;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PoolBonus;
use App\Models\PoolBonusStatus;
use App\Models\Product;
use App\Models\Upline;
use App\Models\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role !== UserType::ADMIN->name) {
            return redirect()->back();
        }

        $productCount = Product::where("quantity", "<=", 3)->count();
        $request->session()->put("product_count", $productCount);

        $orderCount = Order::where("status", OrderStatus::PENDING->name)->count();
        $request->session()->put("order_count", $orderCount);

        $bonusWithdrawal = BonusWithdrawal::where("status", BonusWithdrawalStatus::PENDING->name)->count();
        $request->session()->put("withdrawal_count", $bonusWithdrawal);

        $stockistWithdrawal = DB::table("stockist_withdrawal")
            ->where("status", "PENDING")
            ->count();
        $request->session()->put("stockist_withdrawal_count", $stockistWithdrawal);

        $qualifiedUplines = Upline::qualifiedForLeadershipBonus();
        $request->session()->put("leadership_count", count($qualifiedUplines));

        $qualifiedRanks = DB::table("upline_ranks")->get();
        $pending = 0;
        foreach ($qualifiedRanks as $rank) {
            if ($rank->status === "PENDING") {
                $pending++;
            }
        }
        $request->session()->put("qualified_rank_count", $pending);

        $totalQualifiedPool = PoolBonus::where("status", PoolBonusStatus::PENDING->name)->count();
        $request->session()->put("qualified_pool_count", $totalQualifiedPool);

        return $next($request);
    }
}
