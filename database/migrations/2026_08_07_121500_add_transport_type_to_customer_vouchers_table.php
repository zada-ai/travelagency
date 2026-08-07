<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_vouchers', function (Blueprint $table) {
            $table->string('transport_type')->nullable()->after('admin_company_logo');
        });
    }

    public function down(): void
    {
        Schema::table('customer_vouchers', function (Blueprint $table) {
            $table->dropColumn('transport_type');
        });
    }
};
