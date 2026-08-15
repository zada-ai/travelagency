<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassengerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_passenger_id',
        'age',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function bookingPassenger(): BelongsTo
    {
        return $this->belongsTo(BookingPassenger::class);
    }
}