<?php

namespace Database\Seeders;

use App\Models\PackageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // => Starter Club

        PackageType::create([
            "type" => "A",
            "package_id" => 1
        ]);

        PackageType::create([
            "type" => "B",
            "package_id" => 1
        ]);

        PackageType::create([
            "type" => "C",
            "package_id" => 1
        ]);

        PackageType::create([
            "type" => "D",
            "package_id" => 1
        ]);

        PackageType::create([
            "type" => "E",
            "package_id" => 1
        ]);

        PackageType::create([
            "type" => "F",
            "package_id" => 1
        ]);

        // End Starter Club

        // ------------------------------

        // Junior Club

        PackageType::create([
            "type" => "A",
            "package_id" => 2
        ]);

        PackageType::create([
            "type" => "B",
            "package_id" => 2
        ]);

        PackageType::create([
            "type" => "C",
            "package_id" => 2
        ]);

        // End Junior Club

        // -----------------------------

        // Senior Club

        PackageType::create([
            "type" => "A",
            "package_id" => 3
        ]);

        PackageType::create([
            "type" => "B",
            "package_id" => 3
        ]);

        PackageType::create([
            "type" => "C",
            "package_id" => 3
        ]);

        // End Senior Club

        // -------------------------------

        // Executive Club

        PackageType::create([
            "type" => "A",
            "package_id" => 4
        ]);

        PackageType::create([
            "type" => "B",
            "package_id" => 4
        ]);

        PackageType::create([
            "type" => "C",
            "package_id" => 4
        ]);

        // End Executive Club

        // --------------------------------

        // VIP Club

        PackageType::create([
            "type" => "A",
            "package_id" => 5
        ]);

        PackageType::create([
            "type" => "B",
            "package_id" => 5
        ]);

        PackageType::create([
            "type" => "C",
            "package_id" => 5
        ]);

        // End VIP Club




    }
}
