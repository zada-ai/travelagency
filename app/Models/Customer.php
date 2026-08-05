<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'travel_agent_id',
        'customer_code',
        'first_name',
        'last_name',
        'phone',
        'whatsapp_number',
        'cnic',
        'passport_no',
        'passport_expiry',
        'nationality',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'country',
        'emergency_contact_name',
        'relationship',
        'emergency_contact_number',
        'status',
    ];

    protected $casts = [
        'passport_expiry' => 'date',
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function visaApplications()
    {
        return $this->hasMany(VisaApplication::class);
    }
}