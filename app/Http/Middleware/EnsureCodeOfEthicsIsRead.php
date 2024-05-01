<?php

namespace App\Http\Middleware;

use App\Models\CodeEthics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCodeOfEthicsIsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loggedInDistributor = Auth::user()->distributor;
        $currentLocale = App::currentLocale();

        if ($loggedInDistributor->code_ethics === CodeEthics::PENDING->name) {
            return redirect("/$currentLocale/distributor/code-ethics");
        }

        return $next($request);
    }
}
