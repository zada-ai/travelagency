<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRoomInventoryRequest;
use App\Http\Requests\UpdateHotelRoomInventoryRequest;
use App\Models\Hotel;
use App\Models\HotelRoomType;
use App\Models\HotelRoomInventory;
use App\Services\HotelRoomInventoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelRoomInventoryController extends Controller
{
    public function __construct(private HotelRoomInventoryService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'hotel_room_type_id', 'date', 'status', 'sort', 'direction']);
        $inventories = $this->service->list($filters, 15);
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-room-inventory.index', compact('inventories', 'hotels', 'roomTypes', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-room-inventory.create', compact('hotels', 'roomTypes'));
    }

    public function store(StoreHotelRoomInventoryRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.hotel-room-inventory.index')->with('success', 'Room inventory added successfully.');
    }

    public function edit(HotelRoomInventory $hotel_room_inventory)
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-room-inventory.edit', compact('hotel_room_inventory', 'hotels', 'roomTypes'));
    }

    public function update(UpdateHotelRoomInventoryRequest $request, HotelRoomInventory $hotel_room_inventory)
    {
        $this->service->update($hotel_room_inventory, $request->validated());

        return redirect()->route('admin.hotel-room-inventory.index')->with('success', 'Room inventory updated successfully.');
    }

    public function destroy(HotelRoomInventory $hotel_room_inventory)
    {
        $this->service->delete($hotel_room_inventory);

        return redirect()->route('admin.hotel-room-inventory.index')->with('success', 'Room inventory removed successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'hotel_room_type_id', 'date', 'status']);
        $inventories = $this->service->export($filters);
        $filename = 'room_inventory_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($inventories) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel', 'Room Type', 'Date', 'Total Rooms', 'Available Rooms', 'Booked Rooms', 'Status']);
            foreach ($inventories as $inventory) {
                fputcsv($handle, [
                    $inventory->hotel?->hotel_name,
                    $inventory->roomType?->room_name,
                    $inventory->inventory_date->format('Y-m-d'),
                    $inventory->total_rooms,
                    $inventory->available_rooms,
                    $inventory->booked_rooms,
                    $inventory->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
