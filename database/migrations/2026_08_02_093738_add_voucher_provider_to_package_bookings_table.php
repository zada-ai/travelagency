<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_bookings', function (Blueprint $table) {
            $table->string('voucher_company_name')->nullable()->after('status');
            $table->string('voucher_company_logo')->nullable()->after('voucher_company_name');
        });
    }

    public function down(): void
    {
        Schema::table('package_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'voucher_company_name',
                'voucher_company_logo',
            ]);
        });
    }
};