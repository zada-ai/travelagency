<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->string('room_name');
            $table->string('room_code')->unique();
            $table->unsignedTinyInteger('max_occupancy')->default(1);
            $table->unsignedSmallInteger('total_rooms')->default(0);
            $table->unsignedSmallInteger('available_rooms')->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('extra_bed_price', 10, 2)->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_types');
    }
};
