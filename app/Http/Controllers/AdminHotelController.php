<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Models\HotelImage;
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
        // Limit city filter options to the two primary cities
        $cities = collect(['Makkah', 'Madina']);

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

        unset($data['images'], $data['existing_image_order'], $data['remove_images'], $data['cover_image_id'], $data['replace_images'], $data['is_active']);

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
            'active_image_ids' => array_key_exists('is_active', $data) ? $data['is_active'] : null,
            'replace_images' => $data['replace_images'] ?? [],
        ];

        unset($data['images'], $data['existing_image_order'], $data['remove_images'], $data['cover_image_id'], $data['replace_images'], $data['is_active']);

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

    public function destroyImage(HotelImage $hotelImage)
    {
        Storage::disk('public')->delete($hotelImage->path);
        $hotelImage->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Image deleted successfully.']);
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    public function images(Hotel $hotel)
    {
        return response()->json([
            'images' => $hotel->allImages()->get()->map(function (HotelImage $image) {
                return [
                    'id' => $image->id,
                    'url' => Storage::disk('public')->url($image->path),
                    'is_cover' => $image->is_cover,
                    'sort_order' => $image->sort_order,
                    'is_active' => $image->is_active,
                ];
            }),
        ]);
    }

    public function uploadImages(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $hotel = Hotel::findOrFail($data['hotel_id']);
        $images = $data['images'];

        $currentCount = $hotel->images()->count();
        if ($currentCount + count($images) > 20) {
            return back()->withInput()->withErrors(['images' => 'You may upload a maximum of 20 images per hotel. Remove existing images before adding more.']);
        }

        $this->hotelImageService->uploadImages($hotel, $images);

        return redirect()->route('admin.hotel-management')->with('success', 'Images uploaded successfully. They will now appear in the hotel detail hero banner.');
    }

    public function hotelImageIndex(Request $request)
    {
        $filters = $request->only(['hotel_id', 'status', 'search']);
        $hotels = Hotel::orderBy('hotel_name')->get();

        $images = HotelImage::with('hotel')
            ->when($filters['hotel_id'] ?? null, fn ($query, $hotelId) => $query->where('hotel_id', $hotelId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('is_active', $status === 'active'))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%")
                    ->orWhereHas('hotel', fn ($hotelQuery) => $hotelQuery->where('hotel_name', 'like', "%{$search}%"));
            }))
            ->orderByDesc('is_cover')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.hotel-images.index', compact('images', 'hotels', 'filters'));
    }

    public function hotelImageShow(HotelImage $hotelImage)
    {
        $hotelImage->load('hotel');

        return view('admin.hotel-images.show', compact('hotelImage'));
    }

    public function hotelImageEdit(HotelImage $hotelImage)
    {
        $hotels = Hotel::orderBy('hotel_name')->get();
        $hotelImage->load('hotel');

        return view('admin.hotel-images.edit', compact('hotelImage', 'hotels'));
    }

    public function hotelImageUpdate(Request $request, HotelImage $hotelImage)
    {
        $data = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_cover' => ['nullable', 'boolean'],
            'replace_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $this->hotelImageService->updateImage($hotelImage, $data);

        return redirect()->route('admin.hotel-images.edit', $hotelImage)->with('success', 'Hotel image updated successfully.');
    }

    public function hotelImageDestroy(HotelImage $hotelImage)
    {
        $this->hotelImageService->deleteImage($hotelImage);

        return redirect()->route('admin.hotel-images.index')->with('success', 'Hotel image deleted successfully.');
    }
}
