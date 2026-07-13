<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HotelRoomInventory;

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
        'taxes',
        'discount',
        'grand_total',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'room_price' => 'decimal:2',
        'meal_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

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

    public function cancel()
    {
        if ($this->status === 'Cancelled') {
            return $this;
        }

        $dateRange = collect();
        $current = $this->check_in->copy();
        while ($current->lt($this->check_out)) {
            $dateRange->push($current->format('Y-m-d'));
            $current->addDay();
        }

        $inventoryRows = HotelRoomInventory::where('hotel_id', $this->hotel_id)
            ->where('hotel_room_type_id', $this->hotel_room_type_id)
            ->whereIn('inventory_date', $dateRange)
            ->get();

        foreach ($inventoryRows as $inventory) {
            $inventory->available_rooms = min($inventory->total_rooms, $inventory->available_rooms + 1);
            $inventory->booked_rooms = max(0, $inventory->booked_rooms - 1);
            $inventory->status = $inventory->available_rooms > 0 ? 'Available' : 'Sold Out';
            $inventory->save();
        }

        $this->update(['status' => 'Cancelled']);

        if ($this->room) {
            $this->room->update(['status' => 'Available']);
        }

        return $this;
    }
}
