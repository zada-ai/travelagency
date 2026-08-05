<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminHotelManagementController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();

        $totalRooms = HotelRoom::count();
        $bookedRooms = HotelRoom::whereHas('bookings', function ($query) use ($today) {
            $query->whereIn('status', Booking::UNAVAILABLE_STATUSES)
                ->whereDate('check_in', '<', $today->copy()->addDay())
                ->whereDate('check_out', '>', $today);
        })->count();

        $roomsQuery = HotelRoom::where('status', 'Available')
            ->whereDoesntHave('bookings', function ($query) use ($today) {
                $query->whereIn('status', Booking::UNAVAILABLE_STATUSES)
                    ->whereDate('check_in', '<', $today->copy()->addDay())
                    ->whereDate('check_out', '>', $today);
            });

        if (Schema::hasTable('room_blocks')) {
            $roomsQuery = $roomsQuery->whereDoesntHave('blocks', function ($query) use ($today) {
                $query->active()
                    ->whereDate('block_from', '<', $today->copy()->addDay())
                    ->whereDate('block_to', '>', $today);
            });
        }

        $availableRooms = $roomsQuery->count();

        $metrics = [
            'total_hotels' => Hotel::count(),
            'active_hotels' => Hotel::active()->count(),
            'inactive_hotels' => Hotel::inactive()->count(),
            'makkah_hotels' => Hotel::where('city', 'Makkah')->count(),
            'madinah_hotels' => Hotel::where('city', 'Madinah')->count(),
            'available_rooms' => $availableRooms,
            'booked_rooms' => $bookedRooms,
            'occupancy' => $totalRooms > 0 ? (int) round(($bookedRooms / $totalRooms) * 100) : 0,
            'today_checkins' => Booking::whereIn('status', Booking::UNAVAILABLE_STATUSES)
                ->whereDate('check_in', $today)
                ->count(),
            'today_checkouts' => Booking::whereIn('status', Booking::UNAVAILABLE_STATUSES)
                ->whereDate('check_out', $today)
                ->count(),
        ];

        $hotels = Hotel::active()->orderBy('hotel_name')->get([
            'id',
            'hotel_name',
            'city',
            'about',
            'stay_policy_free_cancellation',
            'stay_policy_haram_shuttle',
            'stay_policy_flexible_checkin',
            'stay_policy_inclusive_breakfast',
        ]);

        return view('admin.hotel-management', compact('metrics', 'hotels'));
    }
}
