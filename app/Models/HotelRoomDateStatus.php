<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoomDateStatus extends Model
{
    use HasFactory;

    public const UNAVAILABLE_STATUSES = ['Reserved', 'Occupied', 'Cleaning', 'Maintenance'];
    public const BOOKED_STATUSES = ['Reserved', 'Occupied'];

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

    public function scopeConflictsForDateRange($query, $checkIn, $checkOut)
    {
        return $query
            ->whereDate('inventory_date', '>=', $checkIn)
            ->whereDate('inventory_date', '<', $checkOut)
            ->whereIn('status', self::UNAVAILABLE_STATUSES);
    }
}
