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
        Schema::create('registration_packages', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->decimal("price");
            $table->integer("bv_point");
            $table->decimal("cutoff");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_packages');
    }
};
