<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('airline_id')->nullable()->after('airline');
            $table->unsignedBigInteger('departure_airport_id')->nullable()->after('route');
            $table->unsignedBigInteger('arrival_airport_id')->nullable()->after('departure_airport_id');
            $table->unsignedBigInteger('return_departure_airport_id')->nullable()->after('arrival_airport_id');
            $table->unsignedBigInteger('return_arrival_airport_id')->nullable()->after('return_departure_airport_id');
            $table->string('ticket_type')->nullable()->after('return_date');
            $table->boolean('refundable')->default(false)->after('ticket_type');
            $table->string('pnr')->nullable()->after('refundable');

            $table->foreign('airline_id')->references('id')->on('airlines')->nullOnDelete();
            $table->foreign('departure_airport_id')->references('id')->on('airports')->nullOnDelete();
            $table->foreign('arrival_airport_id')->references('id')->on('airports')->nullOnDelete();
            $table->foreign('return_departure_airport_id')->references('id')->on('airports')->nullOnDelete();
            $table->foreign('return_arrival_airport_id')->references('id')->on('airports')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['airline_id']);
            $table->dropForeign(['departure_airport_id']);
            $table->dropForeign(['arrival_airport_id']);
            $table->dropForeign(['return_departure_airport_id']);
            $table->dropForeign(['return_arrival_airport_id']);

            $table->dropColumn([
                'airline_id',
                'departure_airport_id',
                'arrival_airport_id',
                'return_departure_airport_id',
                'return_arrival_airport_id',
                'ticket_type',
                'refundable',
                'pnr',
            ]);
        });
    }
};
