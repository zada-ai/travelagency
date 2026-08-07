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
        'admin_company_name',
        'admin_company_logo',
        'transport_type',
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

    public function passengers()
    {
        return $this->hasMany(CustomerVoucherPassenger::class, 'customer_voucher_id');
    }
}
