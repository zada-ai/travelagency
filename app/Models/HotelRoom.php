<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\HotelRoomDateStatus;
use App\Models\RoomBlock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'hotel_room_type_id',
        'room_number',
        'status',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(HotelRoomType::class, 'hotel_room_type_id');
    }

    public function dateStatuses()
    {
        return $this->hasMany(HotelRoomDateStatus::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'hotel_room_id');
    }

    public function blocks()
    {
        return $this->hasMany(RoomBlock::class, 'hotel_room_id');
    }
}
