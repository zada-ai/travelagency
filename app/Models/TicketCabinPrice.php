<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCabinPrice extends Model
{
    protected $fillable = [
        'ticket_id',
        'cabin_class',
        'price',
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'price' => 'decimal:2',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}