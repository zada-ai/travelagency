<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaApplication extends Model
{
    protected $fillable = [
        'customer_id',
        'travel_agent_id',
        'visa_officer_id',
        'assigned_sales_officer_id',
        'status',
        'remarks',
        'total_persons',
        'adults',
        'children',
        'infants',
        'visa_type',
    ];

    protected $casts = [
    ];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function visaOfficer()
    {
        return $this->belongsTo(User::class, 'visa_officer_id');
    }

    public function assignedSalesOfficer()
    {
        return $this->belongsTo(User::class, 'visa_officer_id');
    }

    public function applicants()
    {
        return $this->hasMany(VisaApplicant::class);
    }

    // Backwards-compatible accessors to preserve existing view references
    public function getCustomerNameAttribute()
    {
        if ($this->relationLoaded('customer')) {
            $c = $this->customer;
        } else {
            $c = $this->customer()->first();
        }

        if (! $c) return null;
        return trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? '')) ?: ($c->customer_code ?? null);
    }

    public function getFirstApplicantAttribute()
    {
        if ($this->relationLoaded('applicants')) {
            return $this->applicants->sortBy('applicant_number')->first();
        }

        return $this->applicants()->orderBy('applicant_number')->first();
    }

    public function getPassportNumberAttribute()
    {
        return $this->firstApplicant?->passport_number;
    }

    public function getNationalityAttribute()
    {
        return $this->firstApplicant?->nationality;
    }

    public function getPassportExpiryAttribute()
    {
        return $this->firstApplicant?->passport_expiry_date;
    }

    public function getPassportCopyAttribute()
    {
        return $this->firstApplicant?->passport_scan;
    }

    public function getCnicCopyAttribute()
    {
        return $this->firstApplicant?->cnic;
    }

    public function getPhotographAttribute()
    {
        return $this->firstApplicant?->photo;
    }
}
