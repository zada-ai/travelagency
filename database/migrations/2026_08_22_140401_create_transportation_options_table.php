<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transportation_options', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('sector');
            $table->string('vehicle_type');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['status', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportation_options');
    }
};