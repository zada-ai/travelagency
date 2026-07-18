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
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->json('passenger_details')->nullable()->after('total_passengers');
            $table->decimal('taxes', 10, 2)->default(0)->after('grand_total');
            $table->decimal('service_charge', 10, 2)->default(0)->after('taxes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn(['passenger_details', 'taxes', 'service_charge']);
        });
    }
};
