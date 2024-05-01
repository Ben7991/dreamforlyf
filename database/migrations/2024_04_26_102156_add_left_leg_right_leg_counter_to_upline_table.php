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
        Schema::table('uplines', function (Blueprint $table) {
            $table->integer("left_leg_count")->default(0);
            $table->integer("right_leg_count")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uplines', function (Blueprint $table) {
            $table->dropColumn("left_leg_count");
            $table->dropColumn("right_leg_count");
        });
    }
};
