<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applications', 'travel_from')) {
                $table->date('travel_from')->nullable()->after('visa_type');
            }
            if (! Schema::hasColumn('visa_applications', 'travel_to')) {
                $table->date('travel_to')->nullable()->after('travel_from');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (Schema::hasColumn('visa_applications', 'travel_from')) {
                $table->dropColumn('travel_from');
            }
            if (Schema::hasColumn('visa_applications', 'travel_to')) {
                $table->dropColumn('travel_to');
            }
        });
    }
};
