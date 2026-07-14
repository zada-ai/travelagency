<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_passengers', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('booking_passengers', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('booking_passengers', 'passport_expiry')) {
                $table->date('passport_expiry')->nullable()->after('passport_number');
            }
            if (! Schema::hasColumn('booking_passengers', 'nationality')) {
                $table->string('nationality')->nullable()->after('passport_expiry');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            if (Schema::hasColumn('booking_passengers', 'nationality')) {
                $table->dropColumn('nationality');
            }
            if (Schema::hasColumn('booking_passengers', 'passport_expiry')) {
                $table->dropColumn('passport_expiry');
            }
            if (Schema::hasColumn('booking_passengers', 'passport_number')) {
                $table->dropColumn('passport_number');
            }
            if (Schema::hasColumn('booking_passengers', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
        });
    }
};
