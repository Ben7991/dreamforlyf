<?php

namespace Database\Seeders;

use App\Models\MaintenancePackage;
use Illuminate\Database\Seeder;

class MaintenancePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaintenancePackage::create([
            "duration_in_months" => 1,
            "total_products" => 1,
            "total_price" => 30,
            "bv_point" => 10
        ]);

        MaintenancePackage::create([
            "duration_in_months" => 3,
            "total_products" => 2,
            "total_price" => 60,
            "bv_point" => 20
        ]);

        MaintenancePackage::create([
            "duration_in_months" => 6,
            "total_products" => 4,
            "total_price" => 120,
            "bv_point" => 40
        ]);

        MaintenancePackage::create([
            "duration_in_months" => 12,
            "total_products" => 8,
            "total_price" => 240,
            "bv_point" => 90
        ]);
    }
}
