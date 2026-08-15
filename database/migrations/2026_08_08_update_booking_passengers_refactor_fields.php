<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            // Add full_name column
            if (!Schema::hasColumn('booking_passengers', 'full_name')) {
                $table->string('full_name')->nullable()->after('last_name');
            }

            // Add passport_document_path column
            if (!Schema::hasColumn('booking_passengers', 'passport_document_path')) {
                $table->string('passport_document_path')->nullable()->after('date_of_birth');
            }
        });

        // Migrate data from first_name and last_name to full_name if they exist
        if (Schema::hasColumn('booking_passengers', 'first_name') && Schema::hasColumn('booking_passengers', 'last_name')) {
            DB::statement("UPDATE booking_passengers SET full_name = CONCAT(first_name, ' ', last_name) WHERE full_name IS NULL");
        }

        Schema::table('booking_passengers', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('booking_passengers', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('booking_passengers', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('booking_passengers', 'nationality')) {
                $table->dropColumn('nationality');
            }
            if (Schema::hasColumn('booking_passengers', 'passport_expiry')) {
                $table->dropColumn('passport_expiry');
            }
            if (Schema::hasColumn('booking_passengers', 'passport_number')) {
                $table->dropColumn('passport_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            // Add back old columns
            if (!Schema::hasColumn('booking_passengers', 'first_name')) {
                $table->string('first_name')->nullable()->after('passenger_type');
            }
            if (!Schema::hasColumn('booking_passengers', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('booking_passengers', 'nationality')) {
                $table->string('nationality')->nullable()->after('passport_number');
            }
            if (!Schema::hasColumn('booking_passengers', 'passport_expiry')) {
                $table->date('passport_expiry')->nullable()->after('passport_number');
            }
            if (!Schema::hasColumn('booking_passengers', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('date_of_birth');
            }

            // Drop new columns
            if (Schema::hasColumn('booking_passengers', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('booking_passengers', 'passport_document_path')) {
                $table->dropColumn('passport_document_path');
            }
        });
    }
};
