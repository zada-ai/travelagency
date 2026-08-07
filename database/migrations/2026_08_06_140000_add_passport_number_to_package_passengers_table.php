<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('package_passengers', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('passport_document');
            }
        });
    }

    public function down(): void
    {
        Schema::table('package_passengers', function (Blueprint $table) {
            if (Schema::hasColumn('package_passengers', 'passport_number')) {
                $table->dropColumn('passport_number');
            }
        });
    }
};
