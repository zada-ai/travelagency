<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->foreignId('hotel_room_type_id')->constrained('hotel_room_types')->cascadeOnDelete();
            $table->foreignId('hotel_room_id')->nullable()->constrained('hotel_rooms')->nullOnDelete();
            $table->foreignId('meal_plan_id')->nullable()->constrained('hotel_meal_plans')->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedTinyInteger('infants')->default(0);
            $table->unsignedSmallInteger('total_passengers')->default(1);
            $table->decimal('room_price', 10, 2)->default(0);
            $table->decimal('meal_price', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
