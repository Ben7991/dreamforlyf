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
        Schema::create('upgrade_package_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId("upgrade_package_id")->constrained("upgrade_packages");
            $table->foreignId("product_id")->constrained("products");
            $table->smallInteger("quantity");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_package_product');
    }
};
