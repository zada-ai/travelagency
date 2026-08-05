<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'travel_agent_id',
        'booking_type',
        'booking_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'payment_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
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
        return $query->where('status', $status);
    }

    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        $sequence = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -4) + 1 : 1;
        return "{$prefix}{$date}" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = $invoice->generateInvoiceNumber();
            }
        });
    }
}
