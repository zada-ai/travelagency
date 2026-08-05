<?php

uses(Tests\TestCase::class);

use App\Models\Ticket;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

beforeEach(function () {
    Schema::dropIfExists('tickets');

    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('airline');
        $table->string('route');
        $table->string('flight_number');
        $table->string('reference')->unique();
        $table->date('departure_date');
        $table->date('return_date');
        $table->time('departure_time')->nullable();
        $table->time('arrival_time')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->decimal('adult_price', 10, 2)->default(0);
        $table->decimal('child_price', 10, 2)->default(0);
        $table->decimal('infant_price', 10, 2)->default(0);
        $table->string('status')->default('Approved');
        $table->string('visibility')->default('Both');
        $table->integer('total_seats')->default(0);
        $table->integer('available_seats')->default(0);
        $table->integer('economy_seats')->default(0);
        $table->integer('premium_economy_seats')->default(0);
        $table->integer('business_seats')->default(0);
        $table->integer('first_seats')->default(0);
        $table->timestamps();
    });
});

it('filters flights by portal visibility', function () {
    $agentOnly = createTicket(['visibility' => 'Agent Only']);
    $customerOnly = createTicket(['visibility' => 'Customer Only']);
    $both = createTicket(['visibility' => 'Both']);

    $agentVisibleIds = Ticket::query()->forPortal('agent')->pluck('id')->all();
    $customerVisibleIds = Ticket::query()->forPortal('customer')->pluck('id')->all();

    expect($agentVisibleIds)->toEqual([$agentOnly->id, $both->id]);
    expect($customerVisibleIds)->toEqual([$customerOnly->id, $both->id]);
});

it('does not filter ticket status in portal visibility scope', function () {
    $pendingTicket = createTicket(['visibility' => 'Both', 'status' => 'Pending']);

    $customerVisibleIds = Ticket::query()->forPortal('customer')->pluck('id')->all();

    expect($customerVisibleIds)->toContain($pendingTicket->id);
});

function createTicket(array $overrides = []): Ticket
{
    return Ticket::create(array_merge([
        'airline' => 'Test Airline',
        'route' => 'JED - MKK',
        'flight_number' => 'TEST' . random_int(1000, 9999),
        'reference' => 'REF-' . Str::uuid(),
        'departure_date' => now()->toDateString(),
        'return_date' => now()->addDay()->toDateString(),
        'departure_time' => '10:00',
        'arrival_time' => '14:00',
        'price' => '100.00',
        'adult_price' => '100.00',
        'child_price' => '50.00',
        'infant_price' => '0.00',
        'status' => 'Approved',
        'total_seats' => 100,
        'available_seats' => 100,
        'economy_seats' => 100,
        'premium_economy_seats' => 0,
        'business_seats' => 0,
        'first_seats' => 0,
        'visibility' => 'Both',
    ], $overrides));
}
