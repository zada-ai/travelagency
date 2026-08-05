<?php

uses(Tests\TestCase::class);

use App\Models\Ticket;

it('maps an external flight offer payload into the existing ticket structure', function () {
    $payload = [
        'airline' => 'Saudia',
        'origin' => 'JED',
        'destination' => 'MKK',
        'flight_number' => 'SV123',
        'departure_date' => '2026-08-10',
        'departure_time' => '10:30',
        'arrival_time' => '14:20',
        'price' => 620,
        'adult_price' => 620,
        'child_price' => 310,
        'infant_price' => 0,
        'tax_rate' => 0.08,
        'service_charge_rate' => 0.015,
        'total_seats' => 120,
        'available_seats' => 120,
        'economy_seats' => 80,
        'business_seats' => 40,
        'cabin_prices' => [
            'Economy' => 600,
            'Business' => 900,
        ],
    ];

    $ticket = Ticket::makeFromApiPayload($payload);

    expect($ticket->airline)->toBe('Saudia')
        ->and($ticket->route)->toBe('JED - MKK')
        ->and($ticket->reference)->toContain('SV123')
        ->and($ticket->adult_price)->toBe('620.00')
        ->and($ticket->child_price)->toBe('310.00')
        ->and($ticket->infant_price)->toBe('0.00')
        ->and($ticket->available_seats)->toBe(120)
        ->and($ticket->economy_seats)->toBe(80)
        ->and($ticket->business_seats)->toBe(40)
        ->and($ticket->getCabinPrice('Economy'))->toBe(600.0)
        ->and($ticket->getCabinPrice('Business'))->toBe(900.0);
});
