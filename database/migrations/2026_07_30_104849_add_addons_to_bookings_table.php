<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('include_visa')->default(false)->after('grand_total');
            $table->decimal('visa_price', 10, 2)->default(0.00)->after('include_visa');
            
            $table->boolean('include_transport')->default(false)->after('visa_price');
            $table->decimal('transport_price', 10, 2)->default(0.00)->after('include_transport');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['include_visa', 'visa_price', 'include_transport', 'transport_price']);
        });
    }
};