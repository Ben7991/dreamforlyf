<?php

namespace App\Utility;

use Illuminate\Support\Facades\Hash;

abstract class GlobalValues {
    private function __construct() { }

    public static function getRegistrationToken() {
        return Hash::make("&registration-@-token-@-dist-@-spon-@-downline&");
    }

    public static function getRegistrationTokenString() {
        return "&registration-@-token-@-dist-@-spon-@-downline&";
    }

    public static function getMaintenanceTokenString() {
        return "&maintenance-@-purchase&";
    }
}
