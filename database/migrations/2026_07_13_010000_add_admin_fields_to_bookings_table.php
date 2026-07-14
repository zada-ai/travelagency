<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('Pending')->after('status');
            }

            if (! Schema::hasColumn('bookings', 'contacted')) {
                $table->boolean('contacted')->default(false)->after('payment_status');
            }

            if (! Schema::hasColumn('bookings', 'contacted_by')) {
                $table->string('contacted_by')->nullable()->after('contacted');
            }

            if (! Schema::hasColumn('bookings', 'contacted_at')) {
                $table->dateTime('contacted_at')->nullable()->after('contacted_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'contacted_at')) {
                $table->dropColumn('contacted_at');
            }
            if (Schema::hasColumn('bookings', 'contacted_by')) {
                $table->dropColumn('contacted_by');
            }
            if (Schema::hasColumn('bookings', 'contacted')) {
                $table->dropColumn('contacted');
            }
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
