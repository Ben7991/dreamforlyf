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
        Schema::create('stockist_transfer_history', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date_added")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->decimal("amount");
            $table->foreignId("stockist_id")->constrained("stockists");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockist_transfer_history');
    }
};
