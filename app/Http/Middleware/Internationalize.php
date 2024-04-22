<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Internationalize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = ["en", "fr"];
        $appliedLocale = $request->locale;

        if (!in_array($appliedLocale, $locales)) {
            return abort(404);
        }

        App::setlocale($appliedLocale);

        return $next($request);
    }
}
