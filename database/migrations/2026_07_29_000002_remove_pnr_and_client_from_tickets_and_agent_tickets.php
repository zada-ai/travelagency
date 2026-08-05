<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (Schema::hasColumn('tickets', 'pnr')) {
                    $table->dropColumn('pnr');
                }
                if (Schema::hasColumn('tickets', 'client')) {
                    $table->dropColumn('client');
                }
            });
        }

        if (Schema::hasTable('travel_agent_tickets')) {
            Schema::table('travel_agent_tickets', function (Blueprint $table) {
                if (Schema::hasColumn('travel_agent_tickets', 'client')) {
                    $table->dropColumn('client');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('tickets', 'pnr')) {
                    $table->string('pnr')->nullable()->after('refundable');
                }
                if (! Schema::hasColumn('tickets', 'client')) {
                    $table->string('client')->nullable()->after('status');
                }
            });
        }

        if (Schema::hasTable('travel_agent_tickets')) {
            Schema::table('travel_agent_tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('travel_agent_tickets', 'client')) {
                    $table->string('client')->nullable()->after('status');
                }
            });
        }
    }
};
