<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelFacilityRequest;
use App\Http\Requests\UpdateHotelFacilityRequest;
use App\Models\Hotel;
use App\Models\HotelFacility;
use App\Services\HotelFacilityService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelFacilityController extends Controller
{
    public function __construct(private HotelFacilityService $service)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'hotel_id', 'status', 'sort', 'direction']);
        $facilities = $this->service->list($filters, 15);
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-facilities.index', compact('facilities', 'hotels', 'filters'));
    }

    public function create()
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-facilities.create', compact('hotels'));
    }

    public function store(StoreHotelFacilityRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.hotel-facilities.index')->with('success', 'Facility created successfully.');
    }

    public function edit(HotelFacility $hotel_facility)
    {
        $hotels = Hotel::select(['id', 'hotel_name'])->orderBy('hotel_name')->get();

        return view('admin.hotel-facilities.edit', compact('hotel_facility', 'hotels'));
    }

    public function update(UpdateHotelFacilityRequest $request, HotelFacility $hotel_facility)
    {
        $this->service->update($hotel_facility, $request->validated());

        return redirect()->route('admin.hotel-facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(HotelFacility $hotel_facility)
    {
        $this->service->delete($hotel_facility);

        return redirect()->route('admin.hotel-facilities.index')->with('success', 'Facility deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'hotel_id', 'status']);
        $facilities = $this->service->export($filters);
        $filename = 'hotel_facilities_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($facilities) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel', 'Facility', 'Code', 'Description', 'Status']);
            foreach ($facilities as $facility) {
                fputcsv($handle, [
                    $facility->hotel?->hotel_name,
                    $facility->facility_name,
                    $facility->facility_code,
                    $facility->description,
                    $facility->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
