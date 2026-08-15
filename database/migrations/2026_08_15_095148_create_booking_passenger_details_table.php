<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_passenger_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_passenger_id')
                ->constrained('booking_passengers')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('age')->nullable();

            $table->timestamps();

            $table->unique('booking_passenger_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_passenger_details');
    }
};