<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'passenger_type',
        'full_name',
        'date_of_birth',
        'passport_number',
        'nationality',
        'passport_document_path',
        'cnic_document_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(BookingPassengerDetail::class);
    }

   public function getPassportDocumentUrl()
{
    if (!$this->passport_document_path) {
        return null;
    }

    return route('admin.bookings.passport.download', [
        'booking' => $this->booking_id,
        'passenger' => $this->id,
    ]);
}

public function getCnicDocumentUrl()
{
    if (!$this->cnic_document_path) {
        return null;
    }

    return route('admin.bookings.cnic.download', [
        'booking' => $this->booking_id,
        'passenger' => $this->id,
    ]);
}
}