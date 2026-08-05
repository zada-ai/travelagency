<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('visa_applications', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete()->after('id');
            }

            if (!Schema::hasColumn('visa_applications', 'total_persons')) {
                $table->integer('total_persons')->default(0)->after('customer_id');
            }
            if (!Schema::hasColumn('visa_applications', 'adults')) {
                $table->integer('adults')->default(0)->after('total_persons');
            }
            if (!Schema::hasColumn('visa_applications', 'children')) {
                $table->integer('children')->default(0)->after('adults');
            }
            if (!Schema::hasColumn('visa_applications', 'infants')) {
                $table->integer('infants')->default(0)->after('children');
            }
            if (!Schema::hasColumn('visa_applications', 'visa_type')) {
                $table->string('visa_type')->nullable()->after('infants');
            }
        });
    }

    public function down()
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            if (Schema::hasColumn('visa_applications', 'visa_type')) {
                $table->dropColumn('visa_type');
            }
            if (Schema::hasColumn('visa_applications', 'infants')) {
                $table->dropColumn('infants');
            }
            if (Schema::hasColumn('visa_applications', 'children')) {
                $table->dropColumn('children');
            }
            if (Schema::hasColumn('visa_applications', 'adults')) {
                $table->dropColumn('adults');
            }
            if (Schema::hasColumn('visa_applications', 'total_persons')) {
                $table->dropColumn('total_persons');
            }
            if (Schema::hasColumn('visa_applications', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }
        });
    }
};
