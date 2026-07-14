<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_images', function (Blueprint $table) {
            if (! Schema::hasColumn('hotel_images', 'title')) {
                $table->string('title')->nullable()->after('path');
            }
            if (! Schema::hasColumn('hotel_images', 'alt_text')) {
                $table->string('alt_text')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotel_images', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_images', 'alt_text')) {
                $table->dropColumn('alt_text');
            }
            if (Schema::hasColumn('hotel_images', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
