<?php

namespace App\Models;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\FlightBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'airline',
        'airline_id',
        'route',
        'flight_number',
        'pnr',

        // Outbound timing
        'departure_time',
        'arrival_time',

        // Return timing
        'return_departure_time',
        'return_arrival_time',

        // Dates
        'departure_date',
        'return_date',

        // Airports
        'departure_airport_id',
        'arrival_airport_id',
        'return_departure_airport_id',
        'return_arrival_airport_id',

        // Ticket information
        'ticket_type',
        'refundable',
        'baggage',
        'meal',

        // General prices
        'price',
        'adult_price',
        'child_price',
        'infant_price',

        // Tax / service
        'tax_rate',
        'service_charge_rate',

        // Status / visibility
        'status',
        'visibility',
        'reference',

        // Seats
        'total_seats',
        'available_seats',
        'economy_seats',
        'premium_economy_seats',
        'business_seats',
        'first_seats',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',

        'price' => 'decimal:2',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',

        'tax_rate' => 'decimal:4',
        'service_charge_rate' => 'decimal:4',

        'total_seats' => 'integer',
        'available_seats' => 'integer',

        'economy_seats' => 'integer',
        'premium_economy_seats' => 'integer',
        'business_seats' => 'integer',
        'first_seats' => 'integer',

        'refundable' => 'boolean',
    ];

    protected $appends = [
        'trip_date',
        'booked_seats',
    ];

    /*
    |--------------------------------------------------------------------------
    | PORTAL VISIBILITY
    |--------------------------------------------------------------------------
    */

    public function scopeForPortal(Builder $query, string $portal): Builder
    {
        $portal = strtolower($portal);

        if ($portal === 'agent') {
            return $query->whereIn('visibility', [
                'Both',
                'Agent Only',
            ]);
        }

        if ($portal === 'customer') {
            return $query->whereIn('visibility', [
                'Both',
                'Customer Only',
            ]);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function bookings()
    {
        return $this->hasMany(FlightBooking::class);
    }

    public function airlineMaster()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function returnDepartureAirport()
    {
        return $this->belongsTo(
            Airport::class,
            'return_departure_airport_id'
        );
    }

    public function returnArrivalAirport()
    {
        return $this->belongsTo(
            Airport::class,
            'return_arrival_airport_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CABIN PRICES
    |--------------------------------------------------------------------------
    */

    public function cabinPrices()
    {
        return $this->hasMany(
            TicketCabinPrice::class,
            'ticket_id'
        );
    }

    public function getCabinPrice(string $cabinClass): ?float
    {
        $price = $this->cabinPrices()
            ->where('cabin_class', $cabinClass)
            ->value('price');

        return $price !== null ? (float) $price : null;
    }

    public static function cabinClasses(): array
    {
        return [
            'Economy',
            'Premium Economy',
            'Business',
            'First',
        ];
    }

    public static function makeFromApiPayload(array $payload): self
    {
        $normalized = [
            'airline' => $payload['airline'] ?? $payload['airline_name'] ?? 'Unknown Airline',
            'route' => trim(($payload['origin'] ?? '') . ' - ' . ($payload['destination'] ?? '')),
            'flight_number' => $payload['flight_number'] ?? $payload['flightNo'] ?? $payload['segment_number'] ?? 'UNKNOWN',
            'reference' => $payload['reference'] ?? $payload['id'] ?? ($payload['flight_number'] ?? 'UNKNOWN') . '-' . now()->timestamp,
            'departure_date' => $payload['departure_date'] ?? $payload['departureDate'] ?? now()->toDateString(),
            'return_date' => $payload['return_date'] ?? $payload['returnDate'] ?? null,
            'departure_time' => $payload['departure_time'] ?? $payload['departureTime'] ?? '00:00',
            'arrival_time' => $payload['arrival_time'] ?? $payload['arrivalTime'] ?? '00:00',
            'price' => $payload['price'] ?? $payload['adult_price'] ?? 0,
            'adult_price' => $payload['adult_price'] ?? $payload['price'] ?? 0,
            'child_price' => $payload['child_price'] ?? $payload['price'] ?? 0,
            'infant_price' => $payload['infant_price'] ?? $payload['child_price'] ?? 0,
            'tax_rate' => $payload['tax_rate'] ?? 0.08,
            'service_charge_rate' => $payload['service_charge_rate'] ?? 0.015,
            'status' => $payload['status'] ?? 'Approved',
            'visibility' => $payload['visibility'] ?? 'Both',
            'total_seats' => $payload['total_seats'] ?? $payload['seats'] ?? 0,
            'available_seats' => $payload['available_seats'] ?? $payload['total_seats'] ?? $payload['seats'] ?? 0,
            'economy_seats' => $payload['economy_seats'] ?? $payload['economy'] ?? 0,
            'premium_economy_seats' => $payload['premium_economy_seats'] ?? $payload['premium_economy'] ?? 0,
            'business_seats' => $payload['business_seats'] ?? $payload['business'] ?? 0,
            'first_seats' => $payload['first_seats'] ?? $payload['first'] ?? 0,
        ];

        $ticket = new self($normalized);

        $cabinPrices = $payload['cabin_prices'] ?? [];

        foreach ($cabinPrices as $cabinClass => $price) {
            if ($price === null || $price === '') {
                continue;
            }

            $ticket->setAttribute('cabin_prices', $cabinPrices);
            break;
        }

        return $ticket;
    }

    /*
    |--------------------------------------------------------------------------
    | CABIN SEATS
    |--------------------------------------------------------------------------
    */

    public function getCabinField(string $cabinClass): ?string
    {
        return match ($cabinClass) {
            'Economy' => 'economy_seats',
            'Premium Economy' => 'premium_economy_seats',
            'Business' => 'business_seats',
            'First' => 'first_seats',
            default => null,
        };
    }

    public function getClassAvailableSeats(string $cabinClass): int
    {
        $field = $this->getCabinField($cabinClass);

        return $field
            ? (int) $this->{$field}
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | SEAT RESERVATION
    |--------------------------------------------------------------------------
    */

    public function reserveSeats(
        int $passengers,
        ?string $cabinClass = null
    ): bool {
        if ($passengers > $this->available_seats) {
            return false;
        }

        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);

            if (
                ! $field ||
                $passengers > (int) $this->{$field}
            ) {
                return false;
            }
        }

        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);

            $this->decrement(
                $field,
                $passengers
            );
        }

        $this->decrement(
            'available_seats',
            $passengers
        );

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RELEASE SEATS
    |--------------------------------------------------------------------------
    */

    public function releaseSeats(
        int $passengers,
        ?string $cabinClass = null
    ): void {
        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);

            if ($field) {
                $this->increment(
                    $field,
                    $passengers
                );
            }
        }

        $this->increment(
            'available_seats',
            $passengers
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE SEATS
    |--------------------------------------------------------------------------
    */

    public function normalizeAvailableSeats(): void
    {
        $this->available_seats =
            (int) $this->economy_seats +
            (int) $this->premium_economy_seats +
            (int) $this->business_seats +
            (int) $this->first_seats;
    }

    /*
    |--------------------------------------------------------------------------
    | FARES
    |--------------------------------------------------------------------------
    */

    public function getAdultFareAttribute()
    {
        return $this->adult_price ?? $this->price;
    }

    public function getChildFareAttribute()
    {
        return $this->child_price
            ?? $this->adult_price
            ?? $this->price;
    }

    public function getInfantFareAttribute()
    {
        return $this->infant_price
            ?? $this->child_price
            ?? $this->adult_price
            ?? $this->price;
    }

    /*
    |--------------------------------------------------------------------------
    | TAX & SERVICE CHARGE
    |--------------------------------------------------------------------------
    */

    public function getTaxRateAttribute($value)
    {
        return $value === null
            ? 0.08
            : $value;
    }

    public function getServiceChargeRateAttribute($value)
    {
        return $value === null
            ? 0.015
            : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    */

    public function getReturnRouteAttribute(): ?string
    {
        if (
            ! $this->route ||
            ! $this->return_date
        ) {
            return null;
        }

        if (
            $this->departureAirport &&
            $this->arrivalAirport
        ) {
            return
                $this->arrivalAirport->code .
                ' - ' .
                $this->departureAirport->code;
        }

        $segments = explode(
            ' - ',
            $this->route
        );

        return count($segments) === 2
            ? implode(
                ' - ',
                array_reverse($segments)
            )
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | TRIP DATE
    |--------------------------------------------------------------------------
    */

    public function getTripDateAttribute()
    {
        return $this->departure_date
            ? $this->departure_date->format('Y-m-d')
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKED SEATS
    |--------------------------------------------------------------------------
    */

    public function getBookedSeatsAttribute(): int
    {
        return (int) $this->bookings()
            ->where(
                'status',
                '!=',
                'Cancelled'
            )
            ->sum('total_passengers');
    }

    /*
    |--------------------------------------------------------------------------
    | REMAINING SEATS
    |--------------------------------------------------------------------------
    */

    public function getRemainingSeatsAttribute(): int
    {
        return max(
            0,
            (int) $this->available_seats
        );
    }
}