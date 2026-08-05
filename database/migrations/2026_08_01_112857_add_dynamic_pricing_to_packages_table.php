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
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('adult_price', 10, 2)->nullable()->after('price');
            $table->decimal('child_price', 10, 2)->nullable()->after('adult_price');
            $table->decimal('infant_price', 10, 2)->nullable()->after('child_price');
            $table->decimal('visa_processing_price', 10, 2)->nullable()->after('infant_price');
            $table->decimal('transport_price', 10, 2)->nullable()->after('visa_processing_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'adult_price',
                'child_price',
                'infant_price',
                'visa_processing_price',
                'transport_price',
            ]);
        });
    }
};
