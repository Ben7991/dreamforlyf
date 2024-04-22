<?php

namespace Database\Seeders;

use App\Models\RegistrationPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegistrationPackage::create([
            "name" => "Starter Club",
            "price" => 90,
            "bv_point" => 30,
            "cutoff" => 200
        ]);

        RegistrationPackage::create([
            "name" => "Junior Club",
            "price" => 180,
            "bv_point" => 60,
            "cutoff" => 500
        ]);

        RegistrationPackage::create([
            "name" => "Senior Club",
            "price" => 360,
            "bv_point" => 120,
            "cutoff" => 800
        ]);

        RegistrationPackage::create([
            "name" => "Executive Club",
            "price" => 720,
            "bv_point" => 240,
            "cutoff" => 1200
        ]);

        RegistrationPackage::create([
            "name" => "Vip Club",
            "price" => 1440,
            "bv_point" => 480,
            "cutoff" => 1500
        ]);
    }
}
