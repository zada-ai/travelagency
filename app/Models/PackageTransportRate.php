<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTransportRate extends Model
{
    protected $fillable = [
        'package_id',
        'rate_type',
        'passenger_from',
        'passenger_to',
        'price',
    ];

    protected $casts = [
        'passenger_from' => 'integer',
        'passenger_to' => 'integer',
        'price' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}