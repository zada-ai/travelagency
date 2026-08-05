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

    public const VISIBILITY_BOTH = 'Both';
    public const VISIBILITY_AGENT_ONLY = 'Agent Only';
    public const VISIBILITY_CUSTOMER_ONLY = 'Customer Only';

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
        'about',
        'stay_policy_free_cancellation',
        'stay_policy_haram_shuttle',
        'stay_policy_flexible_checkin',
        'stay_policy_inclusive_breakfast',
        'status',
        'featured',
        'visibility',
    ];

    protected $casts = [
        'distance_from_haram' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured' => 'boolean',
    ];

    public function stayPolicyHighlights(): array
    {
        return [
            [
                'title' => 'Free cancellation',
                'text' => $this->stay_policy_free_cancellation ?: 'Cancel up to 24 hours before arrival without any fees.',
            ],
            [
                'title' => 'Haram shuttle',
                'text' => $this->stay_policy_haram_shuttle ?: 'Complimentary shuttle service to the holy mosque every 30 minutes.',
            ],
            [
                'title' => 'Flexible check-in',
                'text' => $this->stay_policy_flexible_checkin ?: 'Early arrival subject to availability and priority guest support.',
            ],
            [
                'title' => 'Inclusive breakfast',
                'text' => $this->stay_policy_inclusive_breakfast ?: 'Daily buffet breakfast included for all confirmed room bookings.',
            ],
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function scopeVisibleToPortal($query, string $portal = 'customer')
    {
        if ($portal === 'agent') {
            return $query->whereIn('visibility', [self::VISIBILITY_BOTH, self::VISIBILITY_AGENT_ONLY]);
        }

        return $query->whereIn('visibility', [self::VISIBILITY_BOTH, self::VISIBILITY_CUSTOMER_ONLY]);
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
        return $this->hasMany(HotelImage::class)
            ->where('is_active', true)
            ->orderByDesc('is_cover')
            ->orderBy('sort_order');
    }

    public function allImages()
    {
        return $this->hasMany(HotelImage::class)
            ->orderByDesc('is_cover')
            ->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(HotelImage::class)
            ->where('is_cover', true)
            ->where('is_active', true);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $image = $this->coverImage()->first() ?: $this->images()->first();

        return $image ? Storage::disk('public')->url($image->path) : null;
    }
}
