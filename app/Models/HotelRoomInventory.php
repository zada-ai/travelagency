<?php

namespace App\Models;

use App\Models\HotelRoomType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class HotelRoomInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'hotel_room_type_id',
        'inventory_date',
        'inventory_date_to',
        'total_rooms',
        'available_rooms',
        'booked_rooms',
        'status',
    ];

    protected $casts = [
        'inventory_date' => 'date',
        'inventory_date_to' => 'date',
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
        return $query
            ->whereDate('inventory_date', '>=', $checkIn)
            ->whereDate('inventory_date', '<', $checkOut);
    }

    public static function summarizeAvailability(int $hotelId, int $roomTypeId, $checkIn, $checkOut): array
    {
        $nights = (int) $checkIn->diffInDays($checkOut);

        if ($nights < 1) {
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => 'Sold Out',
                'dates' => [],
            ];
        }

        $inventoryRows = self::where('hotel_id', $hotelId)
            ->where('hotel_room_type_id', $roomTypeId)
            ->where('status', 'Active')
            ->get();

        $current = $checkIn->copy();
        $dateDetails = collect();
        $availableDays = 0;
        $minAvailableRooms = null;
        $bookedRooms = 0;
        $totalRooms = null;

        while ($current->lt($checkOut)) {
            $dateKey = $current->format('Y-m-d');
            $row = self::findInventoryRowForDate($inventoryRows, $current);
            $available = $row ? $row->available_rooms > 0 : false;

            $dateDetails->push([
                'date' => $dateKey,
                'available_rooms' => $row ? $row->available_rooms : 0,
                'booked_rooms' => $row ? $row->booked_rooms : 0,
                'total_rooms' => $row ? $row->total_rooms : 0,
                'available' => $available,
            ]);

            if ($available) {
                $availableDays++;
            }

            if ($row) {
                $minAvailableRooms = is_null($minAvailableRooms)
                    ? $row->available_rooms
                    : min($minAvailableRooms, $row->available_rooms);
                $bookedRooms = max($bookedRooms, $row->booked_rooms);
                $totalRooms = is_null($totalRooms)
                    ? $row->total_rooms
                    : min($totalRooms, $row->total_rooms);
            }

            $current->addDay();
        }

        $availableRooms = $minAvailableRooms ?? 0;
        $occupiedDates = $dateDetails->filter(fn ($row) => ! $row['available'])->pluck('date')->values()->toArray();
        $status = ($availableDays === $nights && $availableRooms > 0) ? 'Available' : 'Sold Out';
        $occupancyPercent = $totalRooms > 0 ? (int) round(($bookedRooms / $totalRooms) * 100) : 0;

        return [
            'total_rooms' => $totalRooms ?? 0,
            'available_rooms' => $availableRooms,
            'booked_rooms' => $bookedRooms,
            'occupancy_percent' => $occupancyPercent,
            'status' => $status,
            'dates' => $dateDetails->toArray(),
            'unavailable_dates' => $occupiedDates,
        ];
    }

    private static function findInventoryRowForDate($inventoryRows, Carbon $date)
    {
        return $inventoryRows->filter(function ($row) use ($date) {
            if ($row->inventory_date->gt($date)) {
                return false;
            }

            if ($row->inventory_date_to) {
                return $row->inventory_date_to->gte($date);
            }

            return $row->inventory_date->eq($date);
        })->sort(function ($a, $b) {
            if ($a->inventory_date->eq($b->inventory_date)) {
                return $a->inventory_date_to->timestamp <=> $b->inventory_date_to->timestamp;
            }

            return $a->inventory_date->gt($b->inventory_date) ? -1 : 1;
        })->first();
    }
}
