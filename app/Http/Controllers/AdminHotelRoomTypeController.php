<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRoomTypeRequest;
use App\Http\Requests\UpdateHotelRoomTypeRequest;
use App\Models\Hotel;
use App\Models\HotelRoomType;
use App\Services\HotelRoomTypeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelRoomTypeController extends Controller
{
    public function __construct(private HotelRoomTypeService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'status', 'sort', 'direction']);
        $roomTypes = $this->service->list($filters, 15);
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-room-types.index', compact('roomTypes', 'hotels', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-room-types.create', compact('hotels'));
    }

    public function store(StoreHotelRoomTypeRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.hotel-room-types.index')->with('success', 'Room type created successfully.');
    }

    public function edit(HotelRoomType $hotel_room_type)
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-room-types.edit', compact('hotel_room_type', 'hotels'));
    }

    public function update(UpdateHotelRoomTypeRequest $request, HotelRoomType $hotel_room_type)
    {
        $this->service->update($hotel_room_type, $request->validated());

        return redirect()->route('admin.hotel-room-types.index')->with('success', 'Room type updated successfully.');
    }

    public function destroy(HotelRoomType $hotel_room_type)
    {
        $this->service->delete($hotel_room_type);

        return redirect()->route('admin.hotel-room-types.index')->with('success', 'Room type deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'status']);
        $roomTypes = $this->service->export($filters);
        $filename = 'room_types_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($roomTypes) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel', 'Room Name', 'Room Code', 'Max Occupancy', 'Total Rooms', 'Available Rooms', 'Daily Rate', 'Extra Bed Price', 'Status']);
            foreach ($roomTypes as $roomType) {
                fputcsv($handle, [
                    $roomType->hotel?->hotel_name,
                    $roomType->room_name,
                    $roomType->room_code,
                    $roomType->max_occupancy,
                    $roomType->total_rooms,
                    $roomType->available_rooms,
                    $roomType->daily_rate,
                    $roomType->extra_bed_price,
                    $roomType->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
