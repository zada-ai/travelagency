<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PackageHotelStay;
use App\Models\PackageTransportRate;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'airline',
        'origin',
        'destination',
        'departure_date',
        'return_date',
        'duration',
        'price',
        'adult_price',
        'child_price',
        'infant_price',
        'visa_processing_price',
        'transport_price',
        'total_seats',
        'available_seats',
        'has_visa',
        'has_hotel',
        'has_transport',
        'has_flight',
        'has_meals',
        'makkah_hotel',
        'madinah_hotel',
        'status',
        'badge',
        'show_to_agents',
        'show_to_customers',
        'outbound_flight_id',
        'return_flight_id',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'visa_processing_price' => 'decimal:2',
        'transport_price' => 'decimal:2',
        'has_visa' => 'boolean',
        'has_hotel' => 'boolean',
        'has_transport' => 'boolean',
        'has_flight' => 'boolean',
        'has_meals' => 'boolean',
        'show_to_agents' => 'boolean',
        'show_to_customers' => 'boolean',
        'outbound_flight_id' => 'integer',
        'return_flight_id' => 'integer',
    ];

    public function effectiveAdultPrice(): float
    {
        return (float) ($this->adult_price ?? $this->price);
    }

    public function effectiveChildPrice(): float
    {
        return (float) ($this->child_price ?? $this->adult_price ?? $this->price);
    }

    public function effectiveInfantPrice(): float
    {
        return (float) ($this->infant_price ?? $this->child_price ?? $this->adult_price ?? $this->price);
    }

    public function effectiveVisaProcessingPrice(): float
    {
        return (float) ($this->visa_processing_price ?? 1400);
    }

    public function effectiveTransportPrice(): float
    {
        return (float) ($this->transport_price ?? 0);
    }

    public function flightFareForSeatType(string $type): float
    {
        if (! $this->has_flight || ! $this->outboundFlight) {
            return 0.0;
        }

        return match (strtolower($type)) {
            'adult' => (float) $this->outboundFlight->adult_fare,
            'child' => (float) $this->outboundFlight->child_fare,
            'infant' => (float) $this->outboundFlight->infant_fare,
            default => 0.0,
        };
    }

    public function flightTotal(int $adultCount, int $childCount, int $infantCount): float
    {
        if (! $this->has_flight || ! $this->outboundFlight) {
            return 0.0;
        }

        $total = $adultCount * $this->outboundFlight->adult_fare
            + $childCount * $this->outboundFlight->child_fare
            + $infantCount * $this->outboundFlight->infant_fare;

        if ($this->returnFlight
            && $this->outboundFlight->ticket_type !== 'Round-trip'
            && $this->returnFlight->id !== $this->outboundFlight->id
        ) {
            $total += $adultCount * $this->returnFlight->adult_fare
                + $childCount * $this->returnFlight->child_fare
                + $infantCount * $this->returnFlight->infant_fare;
        }

        return $total;
    }

    public function transportRateForPaidPassengers(int $paidPassengers): ?PackageTransportRate
    {
        return $this->transportRates->first(function ($rate) use ($paidPassengers) {
            return $rate->rate_type === 'passenger'
                && $paidPassengers >= $rate->passenger_from
                && $paidPassengers <= $rate->passenger_to;
        });
    }

    public function transportTotal(int $paidPassengers, int $infantCount): float
    {
        $transportTotal = 0;

        if ($paidPassengers > 0) {
            $rate = $this->transportRateForPaidPassengers($paidPassengers);

            if ($rate) {
                $transportTotal += (float) $rate->price * $paidPassengers;
            }
        }

        if ($infantCount > 0) {
            $infantRate = $this->transportRates->firstWhere('rate_type', 'infant');

            if ($infantRate) {
                $transportTotal += (float) $infantRate->price * $infantCount;
            }
        }

        return $transportTotal;
    }

    public function getShowToAgentsAttribute($value): bool
    {
        if (array_key_exists('show_to_agent', $this->attributes)) {
            return (bool) $this->attributes['show_to_agent'];
        }

        return (bool) $value;
    }

    public function setShowToAgentsAttribute($value): void
    {
        $this->attributes['show_to_agent'] = $value;
    }

    public function getShowToCustomersAttribute($value): bool
    {
        if (array_key_exists('show_to_customer', $this->attributes)) {
            return (bool) $this->attributes['show_to_customer'];
        }

        return (bool) $value;
    }

    public function setShowToCustomersAttribute($value): void
    {
        $this->attributes['show_to_customer'] = $value;
    }

    public function scopeVisibleToAgents($query)
    {
        return $query->where('show_to_agent', true)
            ->where('status', 'Active');
    }

    public function scopeVisibleToCustomers($query)
    {
        return $query->where('show_to_customer', true)
            ->where('status', 'Active');
    }

    public function hotelPricePerPersonTotal(): float
    {
        return $this->hotelStays->sum(function ($stay) {
            return (float) ($stay->price_per_person ?? 0);
        });
    }

    public function bookings()
    {
        return $this->hasMany(PackageBooking::class);
    }

    public function outboundFlight()
    {
        return $this->belongsTo(Ticket::class, 'outbound_flight_id');
    }

    public function returnFlight()
    {
        return $this->belongsTo(Ticket::class, 'return_flight_id');
    }

    public function hotelStays()
    {
        return $this->hasMany(PackageHotelStay::class, 'package_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
       public function transportRates(): HasMany
{
    return $this->hasMany(PackageTransportRate::class);
}
}
