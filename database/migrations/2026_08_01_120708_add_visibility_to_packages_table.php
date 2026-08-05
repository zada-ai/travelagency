<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->boolean('show_to_agent')
                ->default(true)
                ->after('badge');

            $table->boolean('show_to_customer')
                ->default(true)
                ->after('show_to_agent');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'show_to_agent',
                'show_to_customer',
            ]);
        });
    }
};