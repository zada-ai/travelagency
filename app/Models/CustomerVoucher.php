<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_booking_id',
        'package_booking_id',
        'voucher_number',
        'status',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function flightBooking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    public function packageBooking()
    {
        return $this->belongsTo(PackageBooking::class, 'package_booking_id');
    }
}
