<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rank::create([
            "name" => "Onyx",
            "bv_point" => 9000,
            "award" => "Cash $1,000"
        ]);

        Rank::create([
            "name" => "Opal",
            "bv_point" => 60000,
            "award" => "Trip"
        ]);

        Rank::create([
            "name" => "Ruby",
            "bv_point" => 150000,
            "award" => "First Car ($10,000)"
        ]);

        Rank::create([
            "name" => "Sapphire",
            "bv_point" => 330000,
            "award" => "Big Car ($25,000)"
        ]);

        Rank::create([
            "name" => "Blue Diamond",
            "bv_point" => 600000,
            "award" => "Big Cash ($35,000)"
        ]);
    }
}
