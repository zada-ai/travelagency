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
        Schema::table('package_bookings', function (Blueprint $table) {
            $table->string('visa_provider_company_name')->nullable()->after('status');
            $table->string('visa_provider_logo')->nullable()->after('visa_provider_company_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'visa_provider_company_name',
                'visa_provider_logo',
            ]);
        });
    }
};  