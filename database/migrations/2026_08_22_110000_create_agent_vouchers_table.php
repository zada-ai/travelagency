<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_vouchers', function (Blueprint $table) {
            $table->id();

            // Foreign key to the travel agent who created this voucher
            $table->unsignedBigInteger('travel_agent_id')->nullable();

            // Foreign key to agent_companies (the selected company)
            $table->unsignedBigInteger('agent_company_id')->nullable();

            // Basic info
            $table->string('package')->nullable();

            // Transportation
            $table->string('transportation_type')->nullable();
            $table->string('transportation_sector')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->unsignedInteger('transport_persons')->nullable();

            // Arrival to KSA
            $table->string('arrival_flight_no')->nullable();
            $table->string('arrival_flight_pnr')->nullable();
            $table->dateTime('arrival_departure_time')->nullable();
            $table->dateTime('arrival_arrival_time')->nullable();
            $table->string('arrival_departure_from')->nullable();
            $table->string('arrival_to')->nullable();
            $table->string('arrival_pdf')->nullable();

            // Departure from KSA
            $table->string('departure_flight_no')->nullable();
            $table->string('departure_flight_pnr')->nullable();
            $table->dateTime('departure_departure_time')->nullable();
            $table->dateTime('departure_arrival_time')->nullable();
            $table->string('departure_from')->nullable();
            $table->string('departure_to')->nullable();
            $table->string('departure_pdf')->nullable();

            // Hotels stored as JSON array
            $table->json('hotels')->nullable();

            // Passengers stored as JSON array
            $table->json('passengers')->nullable();

            // Remarks
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('agent_company_id')
                ->references('id')
                ->on('agent_companies')
                ->onDelete('set null');

            $table->foreign('travel_agent_id')
                ->references('id')
                ->on('travel_agents')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_vouchers');
    }
};
