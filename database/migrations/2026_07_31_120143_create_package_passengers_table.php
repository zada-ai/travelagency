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
        Schema::create('package_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_booking_id')->constrained('package_bookings')->onDelete('cascade');
            $table->string('type'); // Adult, Child, Infant
            $table->string('name');
            $table->date('dob');
            $table->string('cnic_document')->nullable();
            $table->string('passport_document')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_passengers');
    }
};
