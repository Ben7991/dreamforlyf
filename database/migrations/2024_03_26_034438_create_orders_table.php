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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->from(1000);
            $table->dateTime("date_added")->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->decimal("amount");
            $table->foreignId("distributor_id")->constrained("distributors");
            $table->enum("status", ["PENDING", "APPROVED"])->default("PENDING");
            $table->enum("order_type", ["NORMAL", "REGISTRATION", "MAINTENANCE", "UPGRADE"]);
            $table->foreignId("stockist_id")->constrained("stockists");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
