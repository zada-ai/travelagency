<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->foreignId('travel_agent_id')->nullable()->after('ticket_id')->constrained('travel_agents')->cascadeOnDelete();
            $table->json('seat_numbers')->nullable()->after('passenger_details');
            $table->string('cabin_class')->default('Economy')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropForeign(['travel_agent_id']);
            $table->dropColumn(['travel_agent_id', 'seat_numbers', 'cabin_class']);
        });
    }
};
