<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('travel_agents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile');
            $table->string('company_address');
            $table->string('country');
            $table->string('city');
            $table->string('company_logo');
            $table->string('dts_license');
            $table->string('cnic_front');
            $table->string('cnic_back');
            $table->string('status')->default('Pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_agents');
    }
};
