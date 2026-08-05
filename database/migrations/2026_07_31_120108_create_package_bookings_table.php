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
        Schema::create('package_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Optional user/customer reference
            $table->string('reference_number')->unique();
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('Pending'); // Pending, Approved, Cancelled
            
            // Optionally, capture contact info
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_bookings');
    }
};
