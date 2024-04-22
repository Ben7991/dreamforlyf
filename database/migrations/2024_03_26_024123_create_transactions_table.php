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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal("amount", 15, 2);
            $table->enum("portfolio", ["CURRENT_BALANCE", "PERSONAL_WALLET", "LEADERSHIP_WALLET", "COMMISSION_WALLET"]);
            $table->enum("transaction_type", ["CASH_BACK", "REFERRAL", "BINARY", "LEADERSHIP", "UPGRADE", "PERSONAL", "DEPOSIT"]);
            $table->foreignId("distributor_id")->constrained("distributors");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
