<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('passport_number');
            $table->date('passport_expiry');
            $table->string('nationality');
            $table->date('travel_date');
            $table->date('return_date')->nullable();
            
            $table->foreignId('visa_type_id')->constrained('visa_types')->onDelete('cascade');
            $table->foreignId('travel_agent_id')->nullable()->constrained('travel_agents')->onDelete('set null');
            $table->foreignId('visa_officer_id')->nullable()->constrained('users')->onDelete('set null');

            $table->decimal('visa_fee', 10, 2);
            $table->decimal('service_charges', 10, 2);
            $table->decimal('total_amount', 10, 2);
            
            $table->string('status')->default('Draft'); // Draft, Pending, Submitted, Under Review, Documents Required, Approved, Rejected, Issued, Cancelled
            $table->text('remarks')->nullable();

            // Document uploads
            $table->string('passport_copy')->nullable();
            $table->string('cnic_copy')->nullable();
            $table->string('photograph')->nullable();
            $table->string('vaccination_certificate')->nullable();
            $table->string('visa_copy')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visa_applications');
    }
};
