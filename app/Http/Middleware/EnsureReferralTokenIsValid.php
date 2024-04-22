<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Utility\GlobalValues;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureReferralTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->id;
        $token = $request->token;
        $acceptableSides = ["left", "right"];
        $side = $request->side;
        $user = User::find($id);
        $value = GlobalValues::getRegistrationTokenString();

        if ($user === null || !Hash::check($value, $token) || !in_array($side, $acceptableSides)) {
            return redirect("/");
        }

        return $next($request);
    }
}
