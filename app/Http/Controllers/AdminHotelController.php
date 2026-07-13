<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Services\HotelImageService;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHotelController extends Controller
{
    public function __construct(private HotelService $hotelService, private HotelImageService $hotelImageService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'city', 'status', 'category', 'sort', 'direction']);
        $hotels = $this->hotelService->list($filters, 15);
        $cities = $this->hotelService->cities();

        return view('admin.hotels.index', compact('hotels', 'cities', 'filters'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(StoreHotelRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'] ?? [];

        if (count($images) > 20) {
            return back()->withInput()->withErrors(['images' => 'You may upload up to 20 images per hotel.']);
        }

        unset($data['images'], $data['existing_image_order'], $data['remove_images'], $data['cover_image_id']);

        $hotel = $this->hotelService->create($data);

        if (! empty($images)) {
            $uploaded = $this->hotelImageService->uploadImages($hotel, $images);
            if ($uploaded && ! $hotel->coverImage()->exists()) {
                $uploaded[0]->update(['is_cover' => true]);
            }
        }

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully.');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $data = $request->validated();
        $images = $data['images'] ?? [];
        $syncData = [
            'existing_image_order' => $data['existing_image_order'] ?? [],
            'remove_images' => $data['remove_images'] ?? [],
            'cover_image_id' => $data['cover_image_id'] ?? null,
        ];

        $totalImages = $hotel->images()->count() - count($syncData['remove_images']) + count($images);
        if ($totalImages > 20) {
            return back()->withInput()->withErrors(['images' => 'A hotel can have a maximum of 20 images. Remove existing images before uploading more.']);
        }

        unset($data['images'], $data['existing_image_order'], $data['remove_images'], $data['cover_image_id']);

        $hotel = $this->hotelService->update($hotel, $data);

        if (! empty($images)) {
            $this->hotelImageService->uploadImages($hotel, $images);
        }

        $this->hotelImageService->syncImages($hotel, $syncData);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        $this->hotelService->delete($hotel);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['search', 'city', 'status', 'category']);
        $hotels = $this->hotelService->export($filters);

        $filename = 'hotels_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($hotels) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Hotel Name', 'Hotel Code', 'City', 'Category', 'Status', 'Featured', 'Distance From Haram']);

            foreach ($hotels as $hotel) {
                fputcsv($handle, [
                    $hotel->hotel_name,
                    $hotel->hotel_code,
                    $hotel->city,
                    $hotel->category,
                    $hotel->status,
                    $hotel->featured ? 'Yes' : 'No',
                    $hotel->distance_from_haram,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
