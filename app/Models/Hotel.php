<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HotelFacility;
use App\Models\HotelMealPlan;
use App\Models\HotelRoomInventory;
use App\Models\HotelRoomType;
use App\Models\HotelSeasonalRate;
use App\Models\HotelImage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_name',
        'hotel_code',
        'city',
        'category',
        'distance_from_haram',
        'address',
        'phone',
        'email',
        'website',
        'latitude',
        'longitude',
        'description',
        'status',
        'featured',
    ];

    protected $casts = [
        'distance_from_haram' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function roomTypes()
    {
        return $this->hasMany(HotelRoomType::class);
    }

    public function seasonalRates()
    {
        return $this->hasMany(HotelSeasonalRate::class);
    }

    public function mealPlans()
    {
        return $this->hasMany(HotelMealPlan::class);
    }

    public function facilities()
    {
        return $this->hasMany(HotelFacility::class);
    }

    public function inventories()
    {
        return $this->hasMany(HotelRoomInventory::class);
    }

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class)->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(HotelImage::class)->where('is_cover', true);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $image = $this->coverImage()->first() ?: $this->images()->first();

        return $image ? Storage::disk('public')->url($image->path) : null;
    }
}
