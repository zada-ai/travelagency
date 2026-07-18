<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'airline',
        'route',
        'flight_number',
        'departure_time',
        'arrival_time',
        'departure_date',
        'return_date',
        'baggage',
        'meal',
        'price',
        'adult_price',
        'child_price',
        'infant_price',
        'tax_rate',
        'service_charge_rate',
        'status',
        'reference',
        'client',
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
    ];

    protected $appends = ['trip_date', 'booked_seats'];

    public function bookings()
    {
        return $this->hasMany(FlightBooking::class);
    }

    public function getTripDateAttribute()
    {
        return $this->departure_date ? $this->departure_date->format('Y-m-d') : null;
    }

    public function getBookedSeatsAttribute(): int
    {
        return $this->bookings()->where('status', '!=', 'Cancelled')->sum('total_passengers');
    }

    public function getRemainingSeatsAttribute(): int
    {
        return max(0, $this->available_seats);
    }

    public static function cabinClasses(): array
    {
        return ['Economy', 'Premium Economy', 'Business', 'First'];
    }

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

        return $field ? (int) $this->{$field} : 0;
    }

    public function reserveSeats(int $passengers, ?string $cabinClass = null): bool
    {
        if ($passengers > $this->available_seats) {
            return false;
        }

        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);
            if (! $field || $passengers > $this->{$field}) {
                return false;
            }
        }

        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);
            $this->decrement($field, $passengers);
        }

        $this->decrement('available_seats', $passengers);

        return true;
    }

    public function releaseSeats(int $passengers, ?string $cabinClass = null): void
    {
        if ($cabinClass) {
            $field = $this->getCabinField($cabinClass);
            if ($field) {
                $this->increment($field, $passengers);
            }
        }

        $this->increment('available_seats', $passengers);
    }

    public function normalizeAvailableSeats(): void
    {
        $this->available_seats = $this->economy_seats + $this->premium_economy_seats + $this->business_seats + $this->first_seats;
    }

    public function getAdultFareAttribute()
    {
        return $this->adult_price ?? $this->price;
    }

    public function getChildFareAttribute()
    {
        return $this->child_price ?? $this->adult_price ?? $this->price;
    }

    public function getInfantFareAttribute()
    {
        return $this->infant_price ?? $this->child_price ?? $this->adult_price ?? $this->price;
    }

    public function getTaxRateAttribute($value)
    {
        return $value === null ? 0.08 : $value;
    }

    public function getServiceChargeRateAttribute($value)
    {
        return $value === null ? 0.015 : $value;
    }
}
