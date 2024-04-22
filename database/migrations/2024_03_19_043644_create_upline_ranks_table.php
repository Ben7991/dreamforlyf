<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upline_ranks', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date_added")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->foreignId("upline_id")->constrained("uplines");
            $table->foreignId("rank_id")->constrained("ranks");
            $table->enum("status", ["PENDING", "AWARDED"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upline_ranks');
    }
};
