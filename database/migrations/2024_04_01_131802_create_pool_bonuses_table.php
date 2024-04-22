<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pool_bonuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("upline_id")->constrained("uplines");
            $table->enum("status", ["PENDING", "AWARDED"])->default("PENDING");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pool_bonuses');
    }
};
