<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportationOption extends Model
{
    protected $fillable = [
        'type',
        'sector',
        'vehicle_type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}