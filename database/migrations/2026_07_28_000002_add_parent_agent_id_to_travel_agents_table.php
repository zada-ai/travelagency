<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_agents', function (Blueprint $table) {
            if (! Schema::hasColumn('travel_agents', 'parent_agent_id')) {
                $table->unsignedBigInteger('parent_agent_id')->nullable()->after('created_by');
                $table->foreign('parent_agent_id')->references('id')->on('travel_agents')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_agents', function (Blueprint $table) {
            if (Schema::hasColumn('travel_agents', 'parent_agent_id')) {
                $table->dropForeign(['parent_agent_id']);
                $table->dropColumn('parent_agent_id');
            }
        });
    }
};
