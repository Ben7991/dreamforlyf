<?php

namespace App\Http\Middleware;

use App\Models\MaintenancePackage;
use App\Utility\GlobalValues;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class EnsureMaintenancePackageIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->id;
        $secret = $request->secret;
        $tokenString = GlobalValues::getMaintenanceTokenString();
        $package = MaintenancePackage::find($id);

        if ($package === null || $secret === null || !Hash::check($tokenString, $secret)) {
            return redirect()->back();
        }

        return $next($request);
    }
}
