<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('visa_applicants', function (Blueprint $table) {
            if (Schema::hasColumn('visa_applicants', 'passport_issue_date')) {
                $table->dropColumn('passport_issue_date');
            }
            if (Schema::hasColumn('visa_applicants', 'cnic_number')) {
                $table->dropColumn('cnic_number');
            }
            if (Schema::hasColumn('visa_applicants', 'cnic_front')) {
                $table->dropColumn('cnic_front');
            }
            if (Schema::hasColumn('visa_applicants', 'cnic_back')) {
                $table->dropColumn('cnic_back');
            }
            if (Schema::hasColumn('visa_applicants', 'vaccination_certificate')) {
                $table->dropColumn('vaccination_certificate');
            }
            if (Schema::hasColumn('visa_applicants', 'supporting_document')) {
                $table->dropColumn('supporting_document');
            }
        });

        if (Schema::hasColumn('visa_applications', 'visa_type_id')) {
            DB::statement('ALTER TABLE visa_applications DROP FOREIGN KEY visa_applications_visa_type_id_foreign');
        }

        Schema::table('visa_applications', function (Blueprint $table) {
            $columnsToDrop = [
                'customer_name',
                'passport_number',
                'passport_expiry',
                'nationality',
                'travel_date',
                'return_date',
                'visa_type_id',
                'visa_fee',
                'service_charges',
                'total_amount',
                'passport_copy',
                'cnic_copy',
                'photograph',
                'vaccination_certificate',
                'visa_copy',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('visa_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('visa_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applications', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete()->after('id');
            }
            if (! Schema::hasColumn('visa_applications', 'travel_agent_id')) {
                $table->foreignId('travel_agent_id')->nullable()->constrained('travel_agents')->nullOnDelete()->after('customer_id');
            }
            if (! Schema::hasColumn('visa_applications', 'assigned_sales_officer_id')) {
                $table->foreignId('assigned_sales_officer_id')->nullable()->constrained('users')->nullOnDelete()->after('travel_agent_id');
            }
            if (! Schema::hasColumn('visa_applications', 'status')) {
                $table->string('status')->default('pending')->after('assigned_sales_officer_id');
            } else {
                $table->string('status')->default('pending')->change();
            }
            if (! Schema::hasColumn('visa_applications', 'remarks')) {
                $table->text('remarks')->nullable()->after('status');
            }
            if (! Schema::hasColumn('visa_applications', 'total_persons')) {
                $table->integer('total_persons')->default(0)->after('remarks');
            }
            if (! Schema::hasColumn('visa_applications', 'adults')) {
                $table->integer('adults')->default(0)->after('total_persons');
            }
            if (! Schema::hasColumn('visa_applications', 'children')) {
                $table->integer('children')->default(0)->after('adults');
            }
            if (! Schema::hasColumn('visa_applications', 'infants')) {
                $table->integer('infants')->default(0)->after('children');
            }
            if (! Schema::hasColumn('visa_applications', 'visa_type')) {
                $table->string('visa_type')->nullable()->after('infants');
            }
        });

        Schema::table('visa_applicants', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applicants', 'passport_expiry_date')) {
                $table->date('passport_expiry_date')->nullable()->after('passport_number');
            }
            if (! Schema::hasColumn('visa_applicants', 'passport_scan')) {
                $table->string('passport_scan')->nullable()->after('address');
            }
            if (! Schema::hasColumn('visa_applicants', 'photo')) {
                $table->string('photo')->nullable()->after('passport_scan');
            }
            if (! Schema::hasColumn('visa_applicants', 'cnic')) {
                $table->string('cnic')->nullable()->after('photo');
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        Schema::table('visa_applicants', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applicants', 'passport_issue_date')) {
                $table->date('passport_issue_date')->nullable();
            }
            if (! Schema::hasColumn('visa_applicants', 'cnic_number')) {
                $table->string('cnic_number')->nullable();
            }
            if (! Schema::hasColumn('visa_applicants', 'cnic_front')) {
                $table->string('cnic_front')->nullable();
            }
            if (! Schema::hasColumn('visa_applicants', 'cnic_back')) {
                $table->string('cnic_back')->nullable();
            }
            if (! Schema::hasColumn('visa_applicants', 'vaccination_certificate')) {
                $table->string('vaccination_certificate')->nullable();
            }
            if (! Schema::hasColumn('visa_applicants', 'supporting_document')) {
                $table->string('supporting_document')->nullable();
            }
        });

        Schema::table('visa_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('visa_applications', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'passport_number')) {
                $table->string('passport_number')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'passport_expiry')) {
                $table->date('passport_expiry')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'nationality')) {
                $table->string('nationality')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'travel_date')) {
                $table->date('travel_date')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'return_date')) {
                $table->date('return_date')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'visa_type_id')) {
                $table->foreignId('visa_type_id')->nullable()->constrained('visa_types');
            }
            if (! Schema::hasColumn('visa_applications', 'visa_fee')) {
                $table->decimal('visa_fee', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'service_charges')) {
                $table->decimal('service_charges', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'passport_copy')) {
                $table->string('passport_copy')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'cnic_copy')) {
                $table->string('cnic_copy')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'photograph')) {
                $table->string('photograph')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'vaccination_certificate')) {
                $table->string('vaccination_certificate')->nullable();
            }
            if (! Schema::hasColumn('visa_applications', 'visa_copy')) {
                $table->string('visa_copy')->nullable();
            }
        });
    }
};
