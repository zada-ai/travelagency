<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Seed default room types
        $defaultTypes = [
            ['name' => 'Sharing', 'code' => 'SHR', 'sort_order' => 1, 'status' => true],
            ['name' => '1 Bed Private', 'code' => 'SGL', 'sort_order' => 2, 'status' => true],
            ['name' => '2 Bed Private', 'code' => 'DBL', 'sort_order' => 3, 'status' => true],
            ['name' => 'Triple Bed Private', 'code' => 'TRP', 'sort_order' => 4, 'status' => true],
            ['name' => 'Quad Bed Private', 'code' => 'QUD', 'sort_order' => 5, 'status' => true],
            ['name' => '4 Bed Private', 'code' => '4BD', 'sort_order' => 6, 'status' => true],
            ['name' => '5 Bed Private', 'code' => '5BD', 'sort_order' => 7, 'status' => true],
        ];

        $now = now();
        foreach ($defaultTypes as &$type) {
            $type['created_at'] = $now;
            $type['updated_at'] = $now;
        }

        DB::table('voucher_room_types')->insert($defaultTypes);
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_room_types');
    }
};
