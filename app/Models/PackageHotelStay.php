<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageHotelStay extends Model
{
    protected $fillable = [
        'package_id',
        'city',
        'hotel_name',
        'star_rating',
        'check_in',
        'check_out',
        'nights',
        'distance_from_haram',
        'walking_time',
        'custom_to_haram',
        'transport_notes',
        'room_type',
        'room_sharing_options',
        'price_per_person',
        'sort_order',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'custom_to_haram' => 'boolean',
        'room_sharing_options' => 'array',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}