<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoomDateStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_room_id',
        'booking_id',
        'inventory_date',
        'status',
    ];

    protected $casts = [
        'inventory_date' => 'date',
    ];

    public function hotelRoom()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
