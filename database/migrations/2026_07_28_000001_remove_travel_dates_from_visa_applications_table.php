<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (Schema::hasColumn('visa_applications', 'travel_date')) {
                $table->dropColumn('travel_date');
            }
            if (Schema::hasColumn('visa_applications', 'return_date')) {
                $table->dropColumn('return_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applications', 'travel_date')) {
                $table->date('travel_date')->nullable()->after('nationality');
            }
            if (! Schema::hasColumn('visa_applications', 'return_date')) {
                $table->date('return_date')->nullable()->after('travel_date');
            }
        });
    }
};
