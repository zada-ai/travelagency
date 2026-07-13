<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->foreignId('hotel_room_type_id')->constrained('hotel_room_types')->cascadeOnDelete();
            $table->date('inventory_date');
            $table->unsignedInteger('total_rooms')->default(0);
            $table->unsignedInteger('available_rooms')->default(0);
            $table->unsignedInteger('booked_rooms')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['hotel_id', 'hotel_room_type_id', 'inventory_date'], 'hotel_room_inventory_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_inventories');
    }
};
