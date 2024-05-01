<?php
namespace App\Utility;

abstract class PinGenerator {
    public static function generate() {
        $numbers = "1234567890";
        $lengthOfNumbers = strlen($numbers);
        $pin = "";

        for($i = 1; $i <= 4; $i++) {
            $pin .= $numbers[rand(0, $lengthOfNumbers - 1)];
        }

        return $pin;
    }
}
