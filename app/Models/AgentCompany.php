<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentCompany extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}