<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_voucher_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_voucher_id')
                ->constrained('customer_vouchers')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('passenger_id')->nullable();
            $table->string('passenger_type')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name')->nullable();
            $table->string('passport_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_voucher_passengers');
    }
};
