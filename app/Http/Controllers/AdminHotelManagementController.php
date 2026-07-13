<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class AdminHotelManagementController extends Controller
{
    public function index(Request $request)
    {
        $metrics = [
            'total_hotels' => Hotel::count(),
            'active_hotels' => Hotel::active()->count(),
            'inactive_hotels' => Hotel::inactive()->count(),
            'makkah_hotels' => Hotel::where('city', 'Makkah')->count(),
            'madinah_hotels' => Hotel::where('city', 'Madinah')->count(),
            'available_rooms' => 0,
            'booked_rooms' => 0,
            'occupancy' => 0,
            'today_checkins' => 0,
            'today_checkouts' => 0,
        ];

        return view('admin.hotel-management', compact('metrics'));
    }
}
