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
        Schema::table('tickets', function (Blueprint $table) {
            $table->decimal('adult_price', 10, 2)->nullable()->after('price');
            $table->decimal('child_price', 10, 2)->nullable()->after('adult_price');
            $table->decimal('infant_price', 10, 2)->nullable()->after('child_price');
            $table->decimal('tax_rate', 5, 4)->default(0.08)->after('infant_price');
            $table->decimal('service_charge_rate', 5, 4)->default(0.015)->after('tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'adult_price',
                'child_price',
                'infant_price',
                'tax_rate',
                'service_charge_rate',
            ]);
        });
    }
};
