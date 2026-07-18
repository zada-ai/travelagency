<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('economy_seats')->default(0)->after('available_seats');
            $table->unsignedInteger('premium_economy_seats')->default(0)->after('economy_seats');
            $table->unsignedInteger('business_seats')->default(0)->after('premium_economy_seats');
            $table->unsignedInteger('first_seats')->default(0)->after('business_seats');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['economy_seats', 'premium_economy_seats', 'business_seats', 'first_seats']);
        });
    }
};
