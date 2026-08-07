<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_vouchers', function (Blueprint $table) {
            $table->string('admin_company_name')->nullable()->after('status');
            $table->string('admin_company_logo')->nullable()->after('admin_company_name');
        });
    }

    public function down(): void
    {
        Schema::table('customer_vouchers', function (Blueprint $table) {
            $table->dropColumn(['admin_company_name', 'admin_company_logo']);
        });
    }
};
