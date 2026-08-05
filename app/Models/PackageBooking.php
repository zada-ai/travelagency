<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageBooking extends Model
{
    protected $fillable = [
        'package_id',
        'user_id',
        'reference_number',
        'adults',
        'children',
        'infants',
        'total_price',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'visa_provider_company_name',
'visa_provider_logo',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function passengers()
    {
        return $this->hasMany(PackagePassenger::class);
    }
}