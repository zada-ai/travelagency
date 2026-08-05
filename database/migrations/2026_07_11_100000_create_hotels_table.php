<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('hotel_code')->unique();
            $table->string('city');
            $table->enum('category', ['3 Star', '4 Star', '5 Star'])->default('4 Star');
            $table->decimal('distance_from_haram', 8, 2)->default(0);
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->boolean('featured')->default(false);
            $table->text('about')->nullable();  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
