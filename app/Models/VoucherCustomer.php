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

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? (int) $this->date_of_birth->age : null;
    }

    public function getPassengerTypeAttribute(): string
    {
        $age = $this->age;
        if ($age === null) return 'N/A';
        if ($age > 10) return 'Adult';
        if ($age >= 5) return 'Child (5-10)';
        if ($age >= 2) return 'Child (2-5)';
        return 'Infant (0-2)';
    }
}