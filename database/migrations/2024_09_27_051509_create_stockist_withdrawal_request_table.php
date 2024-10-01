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
        Schema::create('stockist_withdrawal_request', function (Blueprint $table) {
            $table->id();
            $table->enum("stockist_request", ["PENDING", "REQUESTED"]);
            $table->enum("approval_status", ["PENDING", "APPROVED"]);
            $table->foreignId("stockist_id")->constrained("stockists");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockist_withdrawal_request');
    }
};
