<?php

namespace App\Utility;

abstract class PasswordGenerator {
    public static function generate(int $length = 10) {
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890@$%&";
        $totalCharacters = strlen($characters);
        $generatedPassword = "";

        for ($i = 0; $i < $length; $i++) {
            $generatedPassword .= $characters[rand(0, $totalCharacters - 1)];
        }

        return $generatedPassword;
    }
}
