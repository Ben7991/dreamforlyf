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
        Schema::create('uplines', function (Blueprint $table) {
            $table->id();
            $table->string("user_id", 10)->unique();
            $table->integer("first_leg_point")->default(0);
            $table->integer("second_leg_point")->default(0);
            $table->foreign("user_id")->references("id")->on("users");
            $table->integer("last_awarded_point")->default(0);
            $table->decimal("last_amount_paid")->default(0);
            $table->integer("weekly_point")->default(0);
            $table->dateTime("last_awarded_date")->default(DB::raw("CURRENT_TIMESTAMP"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uplines');
    }
};
