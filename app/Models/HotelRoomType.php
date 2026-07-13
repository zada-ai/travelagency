<?php

namespace App\Models;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoomType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'room_name',
        'room_code',
        'max_occupancy',
        'total_rooms',
        'available_rooms',
        'daily_rate',
        'extra_bed_price',
        'status',
    ];

    protected $casts = [
        'max_occupancy' => 'integer',
        'total_rooms' => 'integer',
        'available_rooms' => 'integer',
        'daily_rate' => 'decimal:2',
        'extra_bed_price' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function dateStatuses()
    {
        return $this->hasManyThrough(HotelRoomDateStatus::class, HotelRoom::class, 'hotel_room_type_id', 'hotel_room_id');
    }
}
