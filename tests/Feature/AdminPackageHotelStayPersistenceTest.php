<?php

use App\Http\Controllers\AdminPackageController;
use App\Models\Package;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('persists normalized hotel stays through the package relation', function () {
    Schema::dropIfExists('package_hotel_stays');
    Schema::dropIfExists('packages');

    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->decimal('price', 10, 2)->nullable();
        $table->integer('total_seats')->nullable();
        $table->integer('available_seats')->nullable();
        $table->string('status')->nullable();
        $table->timestamps();
    });

    Schema::create('package_hotel_stays', function (Blueprint $table) {
        $table->id();
        $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
        $table->string('city')->nullable();
        $table->string('hotel_name')->nullable();
        $table->date('check_in')->nullable();
        $table->date('check_out')->nullable();
        $table->integer('nights')->nullable();
        $table->string('distance_from_haram')->nullable();
        $table->string('walking_time')->nullable();
        $table->boolean('custom_to_haram')->default(false);
        $table->text('transport_notes')->nullable();
        $table->string('room_type')->nullable();
        $table->json('room_sharing_options')->nullable();
        $table->decimal('price_per_person', 10, 2)->nullable();
        $table->unsignedInteger('sort_order')->default(0);
        $table->timestamps();
    });

    $package = Package::create([
        'title' => 'Persistence Test Package',
        'price' => 1500,
        'total_seats' => 20,
        'available_seats' => 20,
        'status' => 'Active',
    ]);

    $controller = new AdminPackageController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('normalizeHotelStays');
    $method->setAccessible(true);

    $rows = $method->invoke($controller, [[
        'hotel_name' => 'Test Hotel',
        'city' => 'Makkah',
        'check_in' => '2026-01-01',
        'check_out' => '2026-01-03',
        'nights' => 2,
        'distance_from_haram' => '500m',
        'walking_time' => '10 mins',
        'room_type' => 'Double',
        'room_sharing_options' => 'Double, Triple',
        'transport_notes' => 'Bus transfer',
        'custom_to_haram' => '1',
        'price_per_person' => 120,
    ]]);

    $package->hotelStays()->createMany($rows->map(function ($stay, $index) {
        return array_merge($stay, ['sort_order' => $index]);
    })->toArray());

    $savedStay = $package->hotelStays()->first();

    expect($package->hotelStays()->count())->toBe(1)
        ->and($savedStay->hotel_name)->toBe('Test Hotel')
        ->and($savedStay->room_sharing_options)->toBe(['Double', 'Triple'])
        ->and($savedStay->custom_to_haram)->toBeTrue();
});
