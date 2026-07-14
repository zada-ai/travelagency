<?php

namespace Tests\Unit;

use App\Models\Hotel;
use App\Models\HotelRoomInventory;
use App\Models\HotelRoomType;
use App\Repositories\HotelRoomInventoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelRoomInventoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_merges_with_existing_inventory_when_the_same_hotel_room_type_and_date_already_exists(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Test Hotel',
            'hotel_code' => 'TEST-HOTEL',
            'city' => 'Makkah',
            'category' => '5 Star',
            'distance_from_haram' => 0.5,
            'address' => 'Test address',
            'phone' => '1234567890',
            'status' => 'Active',
        ]);

        $roomType = HotelRoomType::create([
            'hotel_id' => $hotel->id,
            'room_name' => 'Deluxe Room',
            'room_code' => 'DELUXE-ROOM',
            'max_occupancy' => 3,
            'total_rooms' => 10,
            'available_rooms' => 10,
            'daily_rate' => 250.00,
            'extra_bed_price' => 50.00,
            'status' => 'Active',
        ]);

        $existingInventory = HotelRoomInventory::create([
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'inventory_date' => '2026-07-15',
            'total_rooms' => 10,
            'available_rooms' => 8,
            'booked_rooms' => 2,
            'status' => 'Active',
        ]);

        $inventoryToUpdate = HotelRoomInventory::create([
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'inventory_date' => '2026-07-16',
            'total_rooms' => 10,
            'available_rooms' => 7,
            'booked_rooms' => 3,
            'status' => 'Active',
        ]);

        $repository = new HotelRoomInventoryRepository();

        $result = $repository->update($inventoryToUpdate, [
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'inventory_date' => '2026-07-15',
            'total_rooms' => 12,
            'available_rooms' => 9,
            'booked_rooms' => 3,
            'status' => 'Active',
        ]);

        $existingInventory->refresh();
        $inventoryToUpdate->refresh();

        $this->assertSame($existingInventory->id, $result->id);
        $this->assertSame(12, $existingInventory->total_rooms);
        $this->assertSame(9, $existingInventory->available_rooms);
        $this->assertSame(3, $existingInventory->booked_rooms);
        $this->assertTrue($inventoryToUpdate->trashed());
    }
}
