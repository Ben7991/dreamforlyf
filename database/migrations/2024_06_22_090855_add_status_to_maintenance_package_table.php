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
        Schema::table('maintenance_packages', function (Blueprint $table) {
            $table->enum("status", ["ACTIVE", "HIDDEN"])->default("ACTIVE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_packages', function (Blueprint $table) {
            $table->dropColumn("status");
        });
    }
};
