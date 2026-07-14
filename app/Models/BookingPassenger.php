<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'passenger_type',
        'first_name',
        'last_name',
        'age',
        'date_of_birth',
        'passport_number',
        'passport_expiry',
        'nationality',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
