<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_booking_id')->nullable();
            $table->unsignedBigInteger('package_booking_id')->nullable();
            $table->string('voucher_number')->unique();
            $table->string('status')->default('Issued');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('flight_booking_id')->references('id')->on('flight_bookings')->onDelete('cascade');
            $table->foreign('package_booking_id')->references('id')->on('package_bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_vouchers');
    }
};
