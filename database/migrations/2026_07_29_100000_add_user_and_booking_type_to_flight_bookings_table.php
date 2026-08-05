<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('travel_agent_id')->constrained('users')->nullOnDelete();
            $table->enum('booking_type', ['agent', 'customer'])->default('agent')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('flight_bookings', 'booking_type')) {
                $table->dropColumn('booking_type');
            }
            if (Schema::hasColumn('flight_bookings', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
