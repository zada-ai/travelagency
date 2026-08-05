<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class TravelAgentHotelController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('travel_agent')->user() ?? auth()->user();
        $hotels = Hotel::with(['roomTypes', 'seasonalRates', 'mealPlans', 'facilities', 'inventories', 'images', 'coverImage'])
            ->active()
            ->visibleToPortal('agent')
            ->orderBy('hotel_name')
            ->get();

        return view('travel_agents.hotels.index', compact('agent', 'hotels'));
    }

    public function groupBooking()
    {
        $agent = Auth::guard('travel_agent')->user() ?? auth()->user();

        $groupPackages = [
            [
                'title' => 'Makkah & Madinah Deluxe Group',
                'subtitle' => 'Premium hotels with guided transfers',
                'duration' => '12 Nights',
                'group_size' => '35 persons',
                'price' => 'SAR 7,950',
                'availability' => 8,
                'highlights' => ['Near Haram', 'Shared transport', 'Breakfast included'],
            ],
            [
                'title' => 'Economy Group Package',
                'subtitle' => 'Cost-efficient group rooms with essential services',
                'duration' => '10 Nights',
                'group_size' => '50 persons',
                'price' => 'SAR 5,480',
                'availability' => 14,
                'highlights' => ['Budget hotels', 'Daily breakfast', 'Easy booking'],
            ],
            [
                'title' => 'Luxury Pilgrimage Group',
                'subtitle' => 'High-end group accommodation with executive support',
                'duration' => '14 Nights',
                'group_size' => '20 persons',
                'price' => 'SAR 12,950',
                'availability' => 5,
                'highlights' => ['Five-star hotels', 'Private transfers', 'Premium meal plan'],
            ],
        ];

        $recentTickets = [
            [
                'reference' => 'AGT-2318',
                'client_name' => 'Aziz Tours',
                'trip_date' => '2025-03-05',
                'group_size' => '34 pax',
                'status' => 'Pending',
                'total' => 'SAR 42,500',
            ],
            [
                'reference' => 'AGT-2325',
                'client_name' => 'Noor Travel',
                'trip_date' => '2025-04-10',
                'group_size' => '25 pax',
                'status' => 'Approved',
                'total' => 'SAR 32,750',
            ],
            [
                'reference' => 'AGT-2331',
                'client_name' => 'Hajj Connect',
                'trip_date' => '2025-05-18',
                'group_size' => '40 pax',
                'status' => 'Processing',
                'total' => 'SAR 54,800',
            ],
        ];

        return view('travel_agents.group-booking.group-booking', compact('agent', 'groupPackages', 'recentTickets'));
    }
}
