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
        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->char("type", 1);
            $table->foreignId("package_id")->constrained("registration_packages");
            $table->string("image");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_types');
    }
};
