<?php

namespace App\Models;

use App\Models\CustomerVoucher;
use App\Models\TravelAgent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'travel_agent_id',
        'user_id',
        'booking_type',
        'adults',
        'children',
        'infants',
        'total_passengers',
        'seat_numbers',
        'cabin_class',
        'contact_name',
        'contact_email',
        'contact_phone',
        'reference',
        'special_requests',
        'status',
        'payment_status',
        'price',
        'taxes',
        'service_charge',
        'visa_price',
        'transport_price',
        'include_visa',
        'include_transport',
        'grand_total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'taxes' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'visa_price' => 'decimal:2',
        'transport_price' => 'decimal:2',
        'include_visa' => 'boolean',
        'include_transport' => 'boolean',
        'seat_numbers' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function agent()
    {
        return $this->belongsTo(TravelAgent::class, 'travel_agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function passengers()
    {
        return $this->hasMany(FlightBookingPassenger::class);
    }

    public function voucher()
    {
        return $this->hasOne(CustomerVoucher::class, 'flight_booking_id');
    }

    public function cancel(): self
    {
        if (in_array($this->status, ['Cancelled', 'Rejected'], true)) {
            return $this;
        }

        $this->ticket->releaseSeats($this->total_passengers, $this->cabin_class);
        $this->status = 'Cancelled';
        $this->save();

        return $this;
    }
}
