<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'country',
        'status',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
