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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('airline')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('total_seats')->default(50);
            $table->integer('available_seats')->default(50);
            
            // Inclusions
            $table->boolean('has_visa')->default(true);
            $table->boolean('has_hotel')->default(true);
            $table->boolean('has_transport')->default(true);
            $table->boolean('has_flight')->default(true);
            $table->boolean('has_meals')->default(false);
            
            // Hotels
            $table->string('makkah_hotel')->nullable();
            $table->string('madinah_hotel')->nullable();
            
            $table->string('status')->default('Active'); // Active, Draft, Full
            $table->string('badge')->nullable(); // e.g., 'Premium Ramadan'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
