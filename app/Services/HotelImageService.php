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

        $nextOrder = (int) $hotel->images()->max('sort_order') + 1;

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('hotels/' . $hotel->id . '/images', 'public');
            $uploaded[] = $hotel->images()->create([
                'path' => $path,
                'sort_order' => $nextOrder,
            ]);
            $nextOrder++;
        }

        return $uploaded;
    }

    public function syncImages(Hotel $hotel, array $data): Hotel
    {
        $removeImageIds = $data['remove_images'] ?? [];
        $existingOrder = $data['existing_image_order'] ?? [];
        $coverImageId = $data['cover_image_id'] ?? null;

        if (! empty($removeImageIds)) {
            $hotel->images()->whereIn('id', $removeImageIds)->get()->each(function (HotelImage $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });
        }

        foreach ($existingOrder as $imageId => $sortOrder) {
            $image = $hotel->images()->find($imageId);
            if ($image) {
                $image->update(['sort_order' => $sortOrder]);
            }
        }

        if ($coverImageId) {
            $hotel->images()->update(['is_cover' => false]);
            $coverImage = $hotel->images()->find($coverImageId);
            if ($coverImage) {
                $coverImage->update(['is_cover' => true]);
            }
        }

        return $hotel->refresh();
    }
}
