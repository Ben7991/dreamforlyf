<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            "name" => "D LYF DIAB CARE",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 10
        ]);

        Product::create([
            "name" => "D LYF POWER",
            "quantity" => 200,
            "price" => 27,
            "bv_point" => 9
        ]);

        Product::create([
            "name" => "D LYF JOINT",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 10
        ]);

        Product::create([
            "name" => "D LYF DETOX",
            "quantity" => 200,
            "price" => 27,
            "bv_point" => 9
        ]);

        Product::create([
            "name" => "D LYF SINUS",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 10
        ]);

        Product::create([
            "name" => "D LYF PRO M",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 10
        ]);

        Product::create([
            "name" => "D LYF PCOS",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 11
        ]);

        Product::create([
            "name" => "D LYF ENERGY",
            "quantity" => 200,
            "price" => 30,
            "bv_point" => 11
        ]);
    }
}
