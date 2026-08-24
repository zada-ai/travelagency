<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherCustomer extends Model
{
    protected $fillable = [
        'travel_agent_id',
        'name',
        'passport_no',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function travelAgent(): BelongsTo
    {
        return $this->belongsTo(TravelAgent::class);
    }
}