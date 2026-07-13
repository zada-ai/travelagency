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
            ->orderBy('hotel_name')
            ->get();

        return view('travel_agents.hotels.index', compact('agent', 'hotels'));
    }
}
