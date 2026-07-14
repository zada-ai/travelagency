<?php

namespace App\Models;

use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelRoomInventory;
use App\Services\RoomAvailabilityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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

    public function seasonalRates()
    {
        return $this->hasMany(HotelSeasonalRate::class, 'hotel_room_type_id');
    }

    public function rateForDates($checkIn = null, $checkOut = null): float
    {
        if (! $checkIn || ! $checkOut) {
            return (float) $this->daily_rate;
        }

        $startDate = $checkIn->copy()->startOfDay();
        $endDate = $checkOut->copy()->subDay()->endOfDay();

        $seasonalRate = $this->seasonalRates()
            ->where('status', 'Active')
            ->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate)
            ->orderBy('daily_rate')
            ->first();

        return $seasonalRate ? (float) $seasonalRate->daily_rate : (float) $this->daily_rate;
    }

    public function hasHotelRooms(): bool
    {
        return $this->hotelRooms()->exists();
    }

    public function availableRoomsForDates(Carbon $checkIn, Carbon $checkOut): int
    {
        if ($checkOut->lte($checkIn)) {
            return 0;
        }

        $inventorySummary = $this->inventorySummaryForDates($checkIn, $checkOut);
        if ($inventorySummary !== null) {
            return $inventorySummary['available_rooms'];
        }

        if ($this->hasHotelRooms()) {
            return (new RoomAvailabilityService())->countAvailableRooms($this, $checkIn, $checkOut);
        }

        return 0;
    }

    public function findAvailableRoomForDates(Carbon $checkIn, Carbon $checkOut): ?HotelRoom
    {
        if (! $this->hasHotelRooms() || $checkOut->lte($checkIn)) {
            return null;
        }

        return (new RoomAvailabilityService())->findAvailableRoom($this, $checkIn, $checkOut);
    }

    private function inventoryRowForDate(string $date): ?HotelRoomInventory
    {
        return HotelRoomInventory::where('hotel_id', $this->hotel_id)
            ->where('hotel_room_type_id', $this->id)
            ->where('status', 'Active')
            ->where(function ($q) use ($date) {
                $q->whereDate('inventory_date', $date)
                  ->orWhere(function ($q2) use ($date) {
                      $q2->whereDate('inventory_date', '<=', $date)
                         ->whereNotNull('inventory_date_to')
                         ->whereDate('inventory_date_to', '>=', $date);
                  });
            })
            ->orderByDesc('inventory_date_to')
            ->first();
    }

    private function inventorySummaryForDates(Carbon $checkIn, Carbon $checkOut): ?array
    {
        $current = $checkIn->copy();
        $inventoryFound = false;
        $totalRooms = null;
        $dateDetails = collect();
        $unavailableDates = [];

        while ($current->lt($checkOut)) {
            $date = $current->format('Y-m-d');
            $row = $this->inventoryRowForDate($date);

            if ($row) {
                $inventoryFound = true;
            }

            $availableForDay = $row ? (int) $row->available_rooms : 0;
            $booked = $row ? (int) $row->booked_rooms : 0;
            $total = $row ? (int) $row->total_rooms : 0;

            $dateDetails->push([
                'date' => $date,
                'available_rooms' => $availableForDay,
                'booked_rooms' => $booked,
                'total_rooms' => $total,
                'available' => $availableForDay > 0,
            ]);

            if ($availableForDay === 0) {
                $unavailableDates[] = $date;
            }

            $totalRooms = is_null($totalRooms) ? $total : min($totalRooms, $total);
            $current->addDay();
        }

        if (! $inventoryFound) {
            return null;
        }

        $availableRooms = $dateDetails->min('available_rooms') ?? 0;
        $bookedRooms = $dateDetails->max('booked_rooms') ?? 0;
        $occupancyPercent = $totalRooms > 0 ? (int) round(($bookedRooms / $totalRooms) * 100) : 0;
        $status = $availableRooms > 0 ? 'Available' : 'Sold Out';

        return [
            'total_rooms' => $totalRooms ?? 0,
            'available_rooms' => $availableRooms,
            'booked_rooms' => $bookedRooms,
            'occupancy_percent' => $occupancyPercent,
            'status' => $status,
            'dates' => $dateDetails->toArray(),
            'unavailable_dates' => $unavailableDates,
        ];
    }

    public function summarizeAvailabilityForDates(Carbon $checkIn, Carbon $checkOut): array
    {
        if ($checkOut->lte($checkIn)) {
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => 'Sold Out',
                'dates' => [],
                'unavailable_dates' => [],
            ];
        }

        $inventorySummary = $this->inventorySummaryForDates($checkIn, $checkOut);
        if ($inventorySummary !== null) {
            return $inventorySummary;
        }

        if ($this->hasHotelRooms()) {
            return (new RoomAvailabilityService())->summarizeAvailability($this, $checkIn, $checkOut);
        }

        return [
            'total_rooms' => 0,
            'available_rooms' => 0,
            'booked_rooms' => 0,
            'occupancy_percent' => 0,
            'status' => 'Sold Out',
            'dates' => [],
            'unavailable_dates' => [],
        ];
    }

    public function dateStatuses()
    {
        return $this->hasManyThrough(HotelRoomDateStatus::class, HotelRoom::class, 'hotel_room_type_id', 'hotel_room_id');
    }
}
