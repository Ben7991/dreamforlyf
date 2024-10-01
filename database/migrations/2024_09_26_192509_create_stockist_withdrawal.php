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
        Schema::create('stockist_withdrawal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal("amount");
            $table->decimal("deduction");
            $table->foreignId("stockist_id")->constrained("stockists");
            $table->enum("status", ["PENDING", "APPROVED"])->default("PENDING");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockist_withdrawal');
    }
};
