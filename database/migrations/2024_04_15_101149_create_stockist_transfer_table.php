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
        Schema::create('stockist_transfer', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date_added")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->foreignId("transaction_id")->constrained("transactions");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockist_transfer');
    }
};
