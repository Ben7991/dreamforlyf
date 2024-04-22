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
        Schema::create('upgrade_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("distributor_id")->constrained("distributors");
            $table->foreignId("registration_package_id")->constrained("registration_packages");
            $table->foreignId("upgrade_type_id")->constrained("upgrade_packages");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_histories');
    }
};
