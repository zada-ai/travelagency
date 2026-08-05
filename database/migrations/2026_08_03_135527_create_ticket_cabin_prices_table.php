<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_cabin_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->string('cabin_class');
            $table->decimal('price', 12, 2);

            $table->timestamps();

            $table->unique(['ticket_id', 'cabin_class']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_cabin_prices');
    }
};