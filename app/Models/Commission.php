<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'travel_agent_id',
        'booking_type',
        'booking_id',
        'customer_name',
        'package_name',
        'booking_amount',
        'commission_percentage',
        'commission_amount',
        'payment_status',
        'paid_date',
        'remarks',
    ];

    protected $casts = [
        'booking_amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_date' => 'date',
    ];

    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function scopeForAgent($query, $agentId)
    {
        return $query->where('travel_agent_id', $agentId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
    }
}
