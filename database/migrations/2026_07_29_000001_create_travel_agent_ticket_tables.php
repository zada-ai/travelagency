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
        if (! Schema::hasTable('travel_agent_tickets')) {
            Schema::create('travel_agent_tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('travel_agent_id')->nullable()->constrained('travel_agents')->nullOnDelete();
                $table->string('airline');
                $table->string('route');
                $table->string('flight_number');
                $table->string('reference')->unique();
                $table->date('departure_date');
                $table->date('return_date')->nullable();
                $table->string('departure_time');
                $table->string('arrival_time');
                $table->string('baggage')->nullable();
                $table->string('meal')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->string('status')->default('Pending');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('travel_agent_flights')) {
            Schema::create('travel_agent_flights', function (Blueprint $table) {
                $table->id();
                $table->foreignId('travel_agent_ticket_id')->nullable()->constrained('travel_agent_tickets')->cascadeOnDelete();
                $table->foreignId('travel_agent_id')->nullable()->constrained('travel_agents')->nullOnDelete();
                $table->unsignedInteger('adults')->default(1);
                $table->unsignedInteger('children')->default(0);
                $table->unsignedInteger('infants')->default(0);
                $table->unsignedInteger('total_passengers')->default(1);
                $table->string('contact_name');
                $table->string('contact_email');
                $table->string('contact_phone');
                $table->string('reference')->nullable();
                $table->text('special_requests')->nullable();
                $table->string('status')->default('Pending');
                $table->string('payment_status')->default('Unpaid');
                $table->decimal('price', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        Schema::dropIfExists('travel_agent_ticket_bookings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_agent_flights');
        Schema::dropIfExists('travel_agent_tickets');
    }
};
