<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('return_departure_time')->nullable()->after('arrival_time');
            $table->string('return_arrival_time')->nullable()->after('return_departure_time');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'return_departure_time',
                'return_arrival_time',
            ]);
        });
    }
};