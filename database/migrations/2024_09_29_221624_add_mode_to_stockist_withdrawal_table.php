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
        Schema::table('stockist_withdrawal', function (Blueprint $table) {
            $table->enum("mode", ["MOMO", "BANK"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stockist_withdrawal', function (Blueprint $table) {
            $table->dropColumn("mode");
        });
    }
};
