<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'city',
        'country',
        'timezone',
        'status',
    ];

    public function departingTickets()
    {
        return $this->hasMany(Ticket::class, 'departure_airport_id');
    }

    public function arrivingTickets()
    {
        return $this->hasMany(Ticket::class, 'arrival_airport_id');
    }

    public function returnDepartingTickets()
    {
        return $this->hasMany(Ticket::class, 'return_departure_airport_id');
    }

    public function returnArrivingTickets()
    {
        return $this->hasMany(Ticket::class, 'return_arrival_airport_id');
    }
}
