<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_passengers', 'cnic_document_path')) {
                $table->string('cnic_document_path')->nullable()->after('passport_document_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            if (Schema::hasColumn('booking_passengers', 'cnic_document_path')) {
                $table->dropColumn('cnic_document_path');
            }
        });
    }
};
