<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'hotel_room_id',
        'block_from',
        'block_to',
        'reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'block_from' => 'date',
        'block_to' => 'date',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeConflictsForDateRange($query, $checkIn, $checkOut)
    {
        return $query->where('status', 'Active')
            ->whereDate('block_from', '<', $checkOut)
            ->whereDate('block_to', '>', $checkIn);
    }
}
