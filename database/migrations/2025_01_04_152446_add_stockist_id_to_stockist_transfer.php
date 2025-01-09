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
        Schema::table('stockist_transfer', function (Blueprint $table) {
            $table->foreignId('stockist_id')->constrained('stockists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stockist_transfer', function (Blueprint $table) {
            $table->dropColumn('stockist_id');
        });
    }
};
