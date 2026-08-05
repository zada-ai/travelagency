<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('outbound_flight_id')->nullable()->constrained('tickets')->nullOnDelete()->after('return_date');
            $table->foreignId('return_flight_id')->nullable()->constrained('tickets')->nullOnDelete()->after('outbound_flight_id');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['return_flight_id']);
            $table->dropForeign(['outbound_flight_id']);
            $table->dropColumn(['outbound_flight_id', 'return_flight_id']);
        });
    }
};
