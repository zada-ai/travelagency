<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackagePassenger extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
    ];

    protected $fillable = [
        'package_booking_id',
        'type',
        'name',
        'dob',
        'cnic_document',
        'passport_document',
        'passport_number',
    ];

    public function booking()
    {
        return $this->belongsTo(PackageBooking::class, 'package_booking_id');
    }
}
