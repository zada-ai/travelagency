<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_booking_id',
        'passenger_type',
        'first_name',
        'last_name',
        'gender',
        'age',
        'date_of_birth',
        'passport_number',
        'passport_expiry',
        'nationality',
        'passport_upload',
        'cnic_upload',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
    ];

    public function flightBooking()
    {
        return $this->belongsTo(FlightBooking::class);
    }
}
