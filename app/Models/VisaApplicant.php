<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaApplicant extends Model
{
    protected $fillable = [
        'visa_application_id',
        'applicant_number',
        'full_name',
        'father_name',
        'gender',
        'date_of_birth',
        'nationality',
        'passport_number',
        'passport_expiry_date',
        'mobile_number',
        'email',
        'address',
        'passport_scan',
        'photo',
        'cnic',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry_date' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(VisaApplication::class, 'visa_application_id');
    }
}