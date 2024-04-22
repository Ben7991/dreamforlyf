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
        Schema::create('maintenance_packages', function (Blueprint $table) {
            $table->id();
            $table->smallInteger("duration_in_months")->unique();
            $table->smallInteger("total_products");
            $table->decimal("total_price");
            $table->smallInteger("bv_point");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_packages');
    }
};
