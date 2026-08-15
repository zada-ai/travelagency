<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->unsignedTinyInteger('age')->nullable();
            $table->timestamps();
        });

        Schema::table('booking_passengers', function (Blueprint $table) {
            $table->foreignId('passenger_id')
                ->nullable()
                ->after('id')
                ->constrained('passengers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            $table->dropForeign(['passenger_id']);
            $table->dropColumn('passenger_id');
        });

        Schema::dropIfExists('passengers');
    }
};