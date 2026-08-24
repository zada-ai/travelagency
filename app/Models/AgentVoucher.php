<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_agent_id',
        'agent_company_id',
        'package',
        'transportation_type',
        'transportation_sector',
        'vehicle_type',
        'transport_persons',
        'arrival_flight_no',
        'arrival_flight_pnr',
        'arrival_departure_time',
        'arrival_arrival_time',
        'arrival_departure_from',
        'arrival_to',
        'arrival_pdf',
        'departure_flight_no',
        'departure_flight_pnr',
        'departure_departure_time',
        'departure_arrival_time',
        'departure_from',
        'departure_to',
        'departure_pdf',
        'hotels',
        'passengers',
        'remarks',
    ];

    protected $casts = [
        'hotels'     => 'array',
        'passengers' => 'array',
    ];

    /**
     * The agent company associated with this voucher.
     */
    public function agentCompany()
    {
        return $this->belongsTo(AgentCompany::class);
    }

    /**
     * The travel agent who created this voucher.
     */
    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }
}
