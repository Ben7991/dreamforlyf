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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("quantity");
            $table->decimal("price");
            $table->string("image")->nullable();
            $table->json("description_en")->nullable();
            $table->json("description_fr")->nullable();
            $table->string("status")->default("in-stock");
            $table->smallInteger("bv_point");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
