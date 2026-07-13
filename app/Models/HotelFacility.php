<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelFacility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'facility_name',
        'facility_code',
        'description',
        'status',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
