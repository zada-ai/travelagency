<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('hotels', 'stay_policy_free_cancellation')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->text('stay_policy_free_cancellation')->nullable()->after('about');
                $table->text('stay_policy_haram_shuttle')->nullable()->after('stay_policy_free_cancellation');
                $table->text('stay_policy_flexible_checkin')->nullable()->after('stay_policy_haram_shuttle');
                $table->text('stay_policy_inclusive_breakfast')->nullable()->after('stay_policy_flexible_checkin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('hotels', 'stay_policy_free_cancellation')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn([
                    'stay_policy_free_cancellation',
                    'stay_policy_haram_shuttle',
                    'stay_policy_flexible_checkin',
                    'stay_policy_inclusive_breakfast',
                ]);
            });
        }
    }
};
