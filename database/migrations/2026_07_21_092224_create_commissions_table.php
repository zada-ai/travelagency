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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_agent_id')->constrained('travel_agents')->onDelete('cascade');
            $table->string('booking_type')->default('flight'); // flight, hotel, package
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('customer_name');
            $table->string('package_name')->nullable();
            $table->decimal('booking_amount', 10, 2);
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->date('paid_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
