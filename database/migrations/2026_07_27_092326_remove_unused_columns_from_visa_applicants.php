<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visa_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'passport_issue_date',
                'cnic_number'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('visa_applicants', function (Blueprint $table) {
            $table->date('passport_issue_date')->nullable();
            $table->string('cnic_number')->nullable();
        });
    }
};