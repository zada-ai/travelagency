<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVoucherPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_voucher_id',
        'passenger_id',
        'passenger_type',
        'first_name',
        'last_name',
        'name',
        'passport_number',
    ];

    public function voucher()
    {
        return $this->belongsTo(CustomerVoucher::class, 'customer_voucher_id');
    }
}
