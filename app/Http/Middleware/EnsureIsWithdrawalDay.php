<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsWithdrawalDay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isWithdrawalDays = Carbon::TUESDAY === Carbon::now()->dayOfWeek || Carbon::WEDNESDAY === Carbon::now()->dayOfWeek;

        if (!$isWithdrawalDays) {
            return redirect()->back();
        }

        return $next($request);
    }
}
