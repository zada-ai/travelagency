<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\HotelRoom;
use App\Models\HotelRoomType;
use App\Models\RoomBlock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class RoomAvailabilityService
{
    public function availableRoomQuery(HotelRoomType $roomType, Carbon $checkIn, Carbon $checkOut)
    {
        return $roomType->hotelRooms()
            ->whereNotIn('status', ['Maintenance', 'Cleaning'])
            ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', Booking::UNAVAILABLE_STATUSES)
                    ->whereDate('check_in', '<', $checkOut)
                    ->whereDate('check_out', '>', $checkIn);
            })
            ->whereDoesntHave('blocks', function ($query) use ($checkIn, $checkOut) {
                $query->active()
                    ->whereDate('block_from', '<', $checkOut)
                    ->whereDate('block_to', '>', $checkIn);
            });
    }

    public function countAvailableRooms(HotelRoomType $roomType, Carbon $checkIn, Carbon $checkOut): int
    {
        if ($checkOut->lte($checkIn)) {
            return 0;
        }

        return $this->availableRoomQuery($roomType, $checkIn, $checkOut)->count();
    }

    public function findAvailableRoom(HotelRoomType $roomType, Carbon $checkIn, Carbon $checkOut): ?HotelRoom
    {
        return $this->availableRoomQuery($roomType, $checkIn, $checkOut)
            ->inRandomOrder()
            ->first();
    }

    public function summarizeAvailability(HotelRoomType $roomType, Carbon $checkIn, Carbon $checkOut): array
    {
        if ($checkOut->lte($checkIn)) {
            return $this->emptySummary();
        }

        $totalRooms = $roomType->hotelRooms()->count();
        $availableRooms = $this->countAvailableRooms($roomType, $checkIn, $checkOut);
        $unavailableRooms = max(0, $totalRooms - $availableRooms);
        $occupancyPercent = $totalRooms > 0 ? (int) round(($unavailableRooms / $totalRooms) * 100) : 0;
        $dateDetails = collect();
        $unavailableDates = [];
        $current = $checkIn->copy();

        while ($current->lt($checkOut)) {
            $conflictedRooms = HotelRoom::where('hotel_room_type_id', $roomType->id)
                ->where(function ($query) use ($current) {
                    $query->whereHas('bookings', function ($query) use ($current) {
                        $query->whereIn('status', Booking::UNAVAILABLE_STATUSES)
                            ->whereDate('check_in', '<=', $current)
                            ->whereDate('check_out', '>', $current);
                    })
                    ->orWhereHas('blocks', function ($query) use ($current) {
                        $query->active()
                            ->whereDate('block_from', '<=', $current)
                            ->whereDate('block_to', '>', $current);
                    });
                })
                ->distinct()
                ->count('id');

            $availableForDay = max(0, $totalRooms - $conflictedRooms);

            $dateDetails->push([
                'date' => $current->format('Y-m-d'),
                'available_rooms' => $availableForDay,
                'booked_rooms' => $conflictedRooms,
                'total_rooms' => $totalRooms,
                'available' => $availableForDay > 0,
            ]);

            if ($availableForDay === 0) {
                $unavailableDates[] = $current->format('Y-m-d');
            }

            $current->addDay();
        }

        return [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'booked_rooms' => $unavailableRooms,
            'occupancy_percent' => $occupancyPercent,
            'status' => $availableRooms > 0 ? 'Available' : 'Sold Out',
            'dates' => $dateDetails->toArray(),
            'unavailable_dates' => $unavailableDates,
        ];
    }

    private function emptySummary(): array
    {
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
}
