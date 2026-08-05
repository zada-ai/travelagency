<?php

namespace App\Models;

use App\Models\HotelRoomInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'hotel_room_type_id',
        'hotel_room_id',
        'meal_plan_id',
        'reference_number',
        'check_in',
        'check_out',
        'adults',
        'children',
        'infants',
        'total_passengers',
        'room_price',
        'meal_price',
        'visa_price',
        'transport_price',
        'taxes',
        'discount',
        'grand_total',
        'include_visa',
        'include_transport',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'payment_status',
        'contacted',
        'contacted_by',
        'contacted_at',
        'travel_agent_id',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'room_price' => 'decimal:2',
        'meal_price' => 'decimal:2',
        'visa_price' => 'decimal:2',
        'transport_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'include_visa' => 'boolean',
        'include_transport' => 'boolean',
        'contacted' => 'boolean',
        'contacted_at' => 'datetime',
    ];

    public const UNAVAILABLE_STATUSES = ['Reserved', 'Occupied'];
    public const BOOKED_STATUSES = ['Reserved', 'Occupied'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(HotelRoomType::class, 'hotel_room_type_id');
    }

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }

    public function mealPlan()
    {
        return $this->belongsTo(HotelMealPlan::class, 'meal_plan_id');
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function travelAgent()
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function scopeForAgent($query, $agentId)
    {
        return $query->where('travel_agent_id', $agentId);
    }

    public function getTotalNightsAttribute(): int
    {
        return $this->check_in && $this->check_out ? $this->check_in->diffInDays($this->check_out) : 0;
    }

    public function getContactStatusAttribute(): string
    {
        return $this->contacted ? 'Contacted' : 'Not Contacted';
    }

    public function cancel()
    {
        if ($this->status === 'Cancelled') {
            return $this;
        }

        if ($this->hotel_room_id && $this->room) {
            $hasCurrentOrFutureBooking = $this->room->bookings()
                ->whereIn('status', self::BOOKED_STATUSES)
                ->where('id', '!=', $this->id)
                ->whereDate('check_out', '>', now()->startOfDay())
                ->exists();

            $hasActiveBlock = $this->room->blocks()
                ->active()
                ->whereDate('block_to', '>', now()->startOfDay())
                ->exists();

            if (! $hasCurrentOrFutureBooking && ! $hasActiveBlock) {
                $this->room->update(['status' => 'Available']);
            }
        }

        if (! $this->hotel_room_id) {
            $this->restoreInventoryForCancelledBooking();
        }

        $this->update(['status' => 'Cancelled']);

        return $this;
    }

    private function restoreInventoryForCancelledBooking(): void
    {
        if (! $this->hotel_room_type_id || ! $this->check_in || ! $this->check_out) {
            return;
        }

        $roomTypeId = $this->hotel_room_type_id;
        $hotelId = $this->hotel_id;
        $current = $this->check_in->copy();

        while ($current->lt($this->check_out)) {
            $date = $current->format('Y-m-d');

            $inventory = HotelRoomInventory::where('hotel_id', $hotelId)
                ->where('hotel_room_type_id', $roomTypeId)
                ->whereDate('inventory_date', $date)
                ->first();

            if ($inventory) {
                $inventory->update([
                    'available_rooms' => max(0, $inventory->available_rooms + 1),
                    'booked_rooms' => max(0, $inventory->booked_rooms - 1),
                ]);
            }

            $current->addDay();
        }
    }
}
