<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('package_addons', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'visa_price', 'transport_price'
            $table->string('title');        // e.g., 'Visa Processing Fee'
            $table->decimal('price', 10, 2)->default(0.00); // Admin set karega (in PKR/SAR)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_addons');
    }
};