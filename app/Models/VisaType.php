<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'base_fee',
        'service_charge',
        'is_active',
    ];

    public function visaApplications()
    {
        return $this->hasMany(VisaApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTotalCostAttribute()
    {
        return $this->base_fee + $this->service_charge;
    }
}
