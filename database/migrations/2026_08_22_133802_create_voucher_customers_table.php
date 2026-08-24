<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('travel_agent_id')
                ->constrained('travel_agents')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('passport_no');
            $table->date('date_of_birth');

            $table->timestamps();

            $table->index([
                'travel_agent_id',
                'passport_no',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_customers');
    }
};