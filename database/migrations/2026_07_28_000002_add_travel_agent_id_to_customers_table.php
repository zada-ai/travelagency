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
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'travel_agent_id')) {
                $table->foreignId('travel_agent_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('travel_agents')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'travel_agent_id')) {
                $table->dropForeign(['travel_agent_id']);
                $table->dropColumn('travel_agent_id');
            }
        });
    }
};
