<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\UserType;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStockist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loggedInUser = Auth::user();

        if ($loggedInUser->role !== UserType::STOCKIST->name) {
            return redirect()->back();
        }

        $orderCount = Order::where("status", OrderStatus::PENDING->name)->count();
        $request->session()->put("order_count", $orderCount);

        $isWithdrawalDays = Carbon::TUESDAY === Carbon::now()->dayOfWeek || Carbon::WEDNESDAY === Carbon::now()->dayOfWeek;
        $request->session()->put("isWithdrawalDay", $isWithdrawalDays);

        return $next($request);
    }
}
