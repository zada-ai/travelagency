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
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'gender')) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }

            if (! Schema::hasColumn('customers', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('customers', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }

            if (! Schema::hasColumn('customers', 'relationship')) {
                $table->string('relationship')->nullable()->after('emergency_contact_name');
            }

            if (! Schema::hasColumn('customers', 'emergency_contact_number')) {
                $table->string('emergency_contact_number')->nullable()->after('relationship');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'emergency_contact_number')) {
                $table->dropColumn('emergency_contact_number');
            }
            if (Schema::hasColumn('customers', 'relationship')) {
                $table->dropColumn('relationship');
            }
            if (Schema::hasColumn('customers', 'emergency_contact_name')) {
                $table->dropColumn('emergency_contact_name');
            }
            if (Schema::hasColumn('customers', 'whatsapp_number')) {
                $table->dropColumn('whatsapp_number');
            }
            if (Schema::hasColumn('customers', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
