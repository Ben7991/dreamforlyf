<?php

namespace App\Http\Middleware;

use App\Models\RegistrationPackage;
use App\Utility\GlobalValues;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureUpgradePackageIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->token;
        $nextPackageId = $request->next;

        $existingPackage = RegistrationPackage::find($nextPackageId);

        if ($existingPackage === null || !Hash::check(GlobalValues::getRegistrationTokenString(), $token)) {
            return redirect()->back();
        }

        return $next($request);
    }
}
