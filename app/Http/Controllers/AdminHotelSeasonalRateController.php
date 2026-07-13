<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelSeasonalRateRequest;
use App\Http\Requests\UpdateHotelSeasonalRateRequest;
use App\Models\Hotel;
use App\Models\HotelRoomType;
use App\Models\HotelSeasonalRate;
use App\Services\HotelSeasonalRateService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelSeasonalRateController extends Controller
{
    public function __construct(private HotelSeasonalRateService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'hotel_room_type_id', 'status', 'sort', 'direction']);
        $seasonalRates = $this->service->list($filters, 15);
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-seasonal-rates.index', compact('seasonalRates', 'hotels', 'roomTypes', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-seasonal-rates.create', compact('hotels', 'roomTypes'));
    }

    public function store(StoreHotelSeasonalRateRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.hotel-seasonal-rates.index')->with('success', 'Seasonal rate created successfully.');
    }

    public function edit(HotelSeasonalRate $hotel_seasonal_rate)
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();
        $roomTypes = HotelRoomType::select(['id', 'room_name'])->orderBy('room_name')->get();

        return view('admin.hotel-seasonal-rates.edit', compact('hotel_seasonal_rate', 'hotels', 'roomTypes'));
    }

    public function update(UpdateHotelSeasonalRateRequest $request, HotelSeasonalRate $hotel_seasonal_rate)
    {
        $this->service->update($hotel_seasonal_rate, $request->validated());

        return redirect()->route('admin.hotel-seasonal-rates.index')->with('success', 'Seasonal rate updated successfully.');
    }

    public function destroy(HotelSeasonalRate $hotel_seasonal_rate)
    {
        $this->service->delete($hotel_seasonal_rate);

        return redirect()->route('admin.hotel-seasonal-rates.index')->with('success', 'Seasonal rate deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'hotel_room_type_id', 'status']);
        $seasonalRates = $this->service->export($filters);
        $filename = 'seasonal_rates_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($seasonalRates) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel', 'Room Type', 'Season', 'Start Date', 'End Date', 'Daily Rate', 'Status']);
            foreach ($seasonalRates as $rate) {
                fputcsv($handle, [
                    $rate->hotel?->hotel_name,
                    $rate->roomType?->room_name,
                    $rate->season_name,
                    $rate->start_date->format('Y-m-d'),
                    $rate->end_date->format('Y-m-d'),
                    $rate->daily_rate,
                    $rate->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
