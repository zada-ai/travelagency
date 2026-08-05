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
        Schema::create('package_transport_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                ->constrained('packages')
                ->cascadeOnDelete();

            $table->enum('rate_type', ['passenger', 'infant'])
                ->default('passenger');

            $table->unsignedInteger('passenger_from')->default(1);
            $table->unsignedInteger('passenger_to')->default(1);

            $table->decimal('price', 10, 2);

            $table->timestamps();

            $table->index(['package_id', 'rate_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_transport_rates');
    }
};