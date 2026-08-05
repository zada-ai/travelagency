<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_booking_id')->constrained('flight_bookings')->cascadeOnDelete();
            $table->enum('passenger_type', ['Adult', 'Child', 'Infant']);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_upload')->nullable();
            $table->string('cnic_upload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_booking_passengers');
    }
};
