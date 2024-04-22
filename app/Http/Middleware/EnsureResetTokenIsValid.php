<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureResetTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        $token = $request->token;

        $existingRecord = DB::table("password_reset_tokens")->where("email", $email)->where("token", $token)->first();

        if (!$email || !$token || $existingRecord === null) {
            $locale = App::currentLocale();
            return redirect("/$locale");
        }

        return $next($request);
    }
}
