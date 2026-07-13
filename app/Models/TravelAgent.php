<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TravelAgent extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'travel_agents';

    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'password',
        'mobile',
        'company_address',
        'country',
        'city',
        'company_logo',
        'dts_license',
        'cnic_front',
        'cnic_back',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
