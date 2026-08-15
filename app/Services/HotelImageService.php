<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelImageService
{
    public function uploadImages(Hotel $hotel, array $files): array
    {
        $uploaded = [];

        $nextOrder = (int) $hotel->allImages()->max('sort_order') + 1;
        $hasCover = $hotel->allImages()->where('is_cover', true)->exists();

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $slug = Str::slug($hotel->hotel_name ?: (string) $hotel->id);
            $path = $file->store('hotels/' . $slug . '/images', 'public');
            $uploaded[] = $hotel->allImages()->create([
                'path' => $path,
                'sort_order' => $nextOrder,
                'is_cover' => ! $hasCover,
                'is_active' => true,
            ]);

            if (! $hasCover) {
                $hasCover = true;
            }

            $nextOrder++;
        }

        return $uploaded;
    }

    public function syncImages(Hotel $hotel, array $data): Hotel
    {
        $removeImageIds = $data['remove_images'] ?? [];
        $existingOrder = $data['existing_image_order'] ?? [];
        $coverImageId = $data['cover_image_id'] ?? null;
        $activeImageIds = $data['active_image_ids'] ?? [];
        $replaceImages = $data['replace_images'] ?? [];

        if (! empty($removeImageIds)) {
            $hotel->allImages()->whereIn('id', $removeImageIds)->get()->each(function (HotelImage $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });
        }

        foreach ($replaceImages as $imageId => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $image = $hotel->allImages()->find($imageId);
            if (! $image) {
                continue;
            }

            Storage::disk('public')->delete($image->path);
            $slug = Str::slug($hotel->hotel_name ?: (string) $hotel->id);
            $image->update([
                'path' => $file->store('hotels/' . $slug . '/images', 'public'),
            ]);
        }

        foreach ($existingOrder as $imageId => $sortOrder) {
            $image = $hotel->allImages()->find($imageId);
            if ($image) {
                $image->update(['sort_order' => $sortOrder]);
            }
        }

        if (is_array($activeImageIds)) {
            $hotel->allImages()->get()->each(function (HotelImage $image) use ($activeImageIds) {
                $image->update(['is_active' => in_array($image->id, $activeImageIds, true)]);
            });
        }

        if ($coverImageId) {
            $hotel->allImages()->update(['is_cover' => false]);
            $coverImage = $hotel->allImages()->find($coverImageId);
            if ($coverImage) {
                $coverImage->update(['is_cover' => true, 'is_active' => true]);
            }
        }

        if (! $hotel->allImages()->where('is_cover', true)->where('is_active', true)->exists()) {
            $firstActive = $hotel->allImages()->where('is_active', true)->orderByDesc('is_cover')->orderBy('sort_order')->first();
            if ($firstActive) {
                $firstActive->update(['is_cover' => true]);
            }
        }

        return $hotel->refresh();
    }

    public function updateImage(HotelImage $image, array $data): HotelImage
    {
        $oldHotelId = $image->hotel_id;
        $newHotelId = $data['hotel_id'] ?? $oldHotelId;
        $updateData = [
            'hotel_id' => $newHotelId,
            'title' => $data['title'] ?? $image->title,
            'alt_text' => $data['alt_text'] ?? $image->alt_text,
            'sort_order' => $data['sort_order'] ?? $image->sort_order,
            'is_active' => $data['is_active'] ?? false,
            'is_cover' => ! empty($data['is_cover']),
        ];

        if (! empty($data['replace_image']) && $data['replace_image'] instanceof UploadedFile) {
            Storage::disk('public')->delete($image->path);
            $targetHotel = Hotel::find($newHotelId);
            $slug = $targetHotel ? Str::slug($targetHotel->hotel_name ?: (string) $newHotelId) : (string) $newHotelId;
            $updateData['path'] = $data['replace_image']->store('hotels/' . $slug . '/images', 'public');
        }

        if ($updateData['is_cover']) {
            $this->clearCoverForHotel($newHotelId);
            $updateData['is_active'] = true;
        }

        $image->update($updateData);

        if ($oldHotelId !== $newHotelId) {
            $this->ensureCoverForHotel($oldHotelId);
        }

        if (! $image->is_cover) {
            $this->ensureCoverForHotel($newHotelId);
        }

        return $image->refresh();
    }

    public function deleteImage(HotelImage $image): void
    {
        $hotelId = $image->hotel_id;

        Storage::disk('public')->delete($image->path);
        $wasCover = $image->is_cover;
        $image->delete();

        if ($wasCover) {
            $this->ensureCoverForHotel($hotelId);
        }
    }

    protected function clearCoverForHotel(int $hotelId): void
    {
        HotelImage::where('hotel_id', $hotelId)->update(['is_cover' => false]);
    }

    protected function ensureCoverForHotel(int $hotelId): void
    {
        $hotelImages = HotelImage::where('hotel_id', $hotelId)->where('is_active', true);
        if (! $hotelImages->where('is_cover', true)->exists()) {
            $firstActive = $hotelImages->orderByDesc('is_cover')->orderBy('sort_order')->first();
            if ($firstActive) {
                $firstActive->update(['is_cover' => true]);
            }
        }
    }
}
