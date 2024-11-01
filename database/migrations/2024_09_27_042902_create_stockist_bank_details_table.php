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
        Schema::create('stockist_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string("full_name");
            $table->string("bank_name");
            $table->string("bank_branch");
            $table->string("beneficiary_name");
            $table->string("account_number");
            $table->string("iban_number");
            $table->string("swift_number");
            $table->string("phone_number");
            $table->foreignId("stockist_id")->constrained("stockists");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockist_bank_details');
    }
};