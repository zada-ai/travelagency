<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Models\HotelRoomInventory;
use App\Models\HotelRoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class HotelRoomTypeAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_date_range_is_used_for_multi_night_availability(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Range Hotel',
            'hotel_code' => 'RANGE-HOTEL',
            'city' => 'Makkah',
            'category' => '4 Star',
            'distance_from_haram' => 0.8,
            'address' => '123 Range St',
            'phone' => '0123456789',
            'status' => 'Active',
        ]);

        $roomType = HotelRoomType::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Range Suite',
            'room_code' => 'RANGE-SUITE',
            'max_occupancy' => 2,
            'total_rooms' => 5,
            'available_rooms' => 5,
            'daily_rate' => 150.00,
            'extra_bed_price' => 20.00,
            'status' => 'Active',
        ]);

        HotelRoomInventory::create([
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'inventory_date' => '2026-07-15',
            'inventory_date_to' => '2026-07-17',
            'total_rooms' => 5,
            'available_rooms' => 3,
            'booked_rooms' => 2,
            'status' => 'Active',
        ]);

        $checkIn = Carbon::parse('2026-07-15')->startOfDay();
        $checkOut = Carbon::parse('2026-07-18')->startOfDay();

        $available = $roomType->availableRoomsForDates($checkIn, $checkOut);
        $availability = $roomType->summarizeAvailabilityForDates($checkIn, $checkOut);

        $this->assertSame(3, $available);
        $this->assertSame('Available', $availability['status']);
        $this->assertSame(3, $availability['available_rooms']);
        $this->assertCount(3, $availability['dates']);
        $this->assertSame(['2026-07-15', '2026-07-16', '2026-07-17'], array_column($availability['dates'], 'date'));
    }
}
