<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the public homepage with featured customer packages', function () {
    Package::create([
        'title' => 'Luxury Ramadan Umrah',
        'airline' => 'Saudi Arabian Airlines',
        'origin' => 'ISB',
        'destination' => 'JED',
        'departure_date' => '2026-08-15',
        'return_date' => '2026-08-30',
        'duration' => '15 Days',
        'price' => 14500,
        'total_seats' => 50,
        'available_seats' => 12,
        'status' => 'Active',
        'show_to_customers' => true,
        'show_to_agents' => true,
        'makkah_hotel' => 'Pullman Zamzam',
        'madinah_hotel' => 'Anwar Al Madinah',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Luxury Ramadan Umrah');
    $response->assertSee('Explore Umrah Packages');
    $response->assertSee('Browse Packages');
});
