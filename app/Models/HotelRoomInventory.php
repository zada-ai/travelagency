<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoomInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'hotel_room_type_id',
        'inventory_date',
        'total_rooms',
        'available_rooms',
        'booked_rooms',
        'status',
    ];

    protected $casts = [
        'inventory_date' => 'date',
        'total_rooms' => 'integer',
        'available_rooms' => 'integer',
        'booked_rooms' => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(HotelRoomType::class, 'hotel_room_type_id');
    }

    public function scopeForDateRange($query, $checkIn, $checkOut)
    {
        return $query->whereBetween('inventory_date', [$checkIn, $checkOut->copy()->subDay()]);
    }

    public static function summarizeAvailability(int $hotelId, int $roomTypeId, $checkIn, $checkOut): array
    {
        $inventoryRows = self::where('hotel_id', $hotelId)
            ->where('hotel_room_type_id', $roomTypeId)
            ->whereBetween('inventory_date', [$checkIn, $checkOut->copy()->subDay()])
            ->get();

        if ($inventoryRows->isEmpty()) {
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => 'Sold Out',
                'dates' => [],
            ];
        }

        $totalRooms = $inventoryRows->min('total_rooms');
        $availableRooms = $inventoryRows->min('available_rooms');
        $bookedRooms = $inventoryRows->max('booked_rooms');
        $occupancyPercent = $totalRooms > 0 ? (int) round(($bookedRooms / $totalRooms) * 100) : 0;
        $status = $availableRooms > 0 ? 'Available' : 'Sold Out';

        return [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'booked_rooms' => $bookedRooms,
            'occupancy_percent' => $occupancyPercent,
            'status' => $status,
            'dates' => $inventoryRows->map(fn ($row) => [
                'date' => $row->inventory_date->format('Y-m-d'),
                'available_rooms' => $row->available_rooms,
                'booked_rooms' => $row->booked_rooms,
                'total_rooms' => $row->total_rooms,
            ])->values()->toArray(),
        ];
    }
}
