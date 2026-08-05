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
        Schema::create('package_hotel_stays', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                ->constrained('packages')
                ->cascadeOnDelete();

            $table->string('city'); // Makkah / Madinah

            $table->string('hotel_name');

            $table->unsignedTinyInteger('star_rating')->nullable();

            $table->date('check_in');

            $table->date('check_out');

            $table->unsignedInteger('nights')->default(1);

            $table->string('distance_from_haram')->nullable();

            $table->string('walking_time')->nullable();

            $table->boolean('custom_to_haram')->default(false);

            $table->text('transport_notes')->nullable();

            $table->string('room_type')->nullable();

            $table->json('room_sharing_options')->nullable();

            $table->decimal('price_per_person', 10, 2)->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_hotel_stays');
    }
};