<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaCompany extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}