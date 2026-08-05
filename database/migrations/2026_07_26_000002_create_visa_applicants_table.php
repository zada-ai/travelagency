<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_application_id')->constrained('visa_applications')->cascadeOnDelete();
            $table->integer('applicant_number')->default(1);
            $table->string('full_name');
            $table->string('father_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('cnic_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            // File paths
            $table->string('passport_scan')->nullable();
            $table->string('photo')->nullable();
            $table->string('cnic_front')->nullable();
            $table->string('cnic_back')->nullable();
            $table->string('vaccination_certificate')->nullable();
            $table->string('supporting_document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_applicants');
    }
};
