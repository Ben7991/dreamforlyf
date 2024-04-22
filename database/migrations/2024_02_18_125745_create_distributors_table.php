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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("upline_id")->constrained("uplines");
            $table->char("leg", 3);
            $table->foreignId("registration_package_id")->constrained("registration_packages");
            $table->string("country");
            $table->string("city");
            $table->string("user_id")->unique();
            $table->foreign("user_id")->references("id")->on("users");
            $table->string("phone_number");
            $table->string("wave")->nullable();
            $table->dateTime("next_maintenance_date");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
