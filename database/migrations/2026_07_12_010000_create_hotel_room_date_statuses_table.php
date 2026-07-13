<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_date_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->date('inventory_date');
            $table->enum('status', ['Available', 'Occupied', 'Reserved', 'Cleaning', 'Maintenance'])->default('Available');
            $table->timestamps();
            $table->unique(['hotel_room_id', 'inventory_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_date_statuses');
    }
};
