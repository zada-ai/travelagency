<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelRoomInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicHotelController extends Controller
{
    public function show(Hotel $hotel, Request $request)
    {
        $checkIn = $request->input('check_in') ? Carbon::parse($request->input('check_in'))->startOfDay() : null;
        $checkOut = $request->input('check_out') ? Carbon::parse($request->input('check_out'))->startOfDay() : null;

        if ($checkIn && $checkOut && $checkOut->lte($checkIn)) {
            $checkOut = null;
        }

        $hotel->load(['roomTypes', 'seasonalRates', 'mealPlans', 'facilities', 'inventories', 'images', 'coverImage']);

        $roomTypeAvailabilities = [];

        if ($checkIn && $checkOut) {
            foreach ($hotel->roomTypes->where('status', 'Active') as $roomType) {
                $roomTypeAvailabilities[$roomType->id] = HotelRoomInventory::summarizeAvailability(
                    $hotel->id,
                    $roomType->id,
                    $checkIn,
                    $checkOut
                );
            }
        }

        $recommendations = Hotel::active()
            ->where('city', $hotel->city)
            ->where('id', '!=', $hotel->id)
            ->orderByDesc('featured')
            ->orderBy('hotel_name')
            ->take(3)
            ->get();

        $policyHighlights = [
            [
                'title' => 'Free cancellation',
                'text' => 'Cancel up to 24 hours before arrival without any fees.',
            ],
            [
                'title' => 'Haram shuttle',
                'text' => 'Complimentary shuttle service to the holy mosque every 30 minutes.',
            ],
            [
                'title' => 'Flexible check-in',
                'text' => 'Early arrival subject to availability and priority guest support.',
            ],
            [
                'title' => 'Inclusive breakfast',
                'text' => 'Daily buffet breakfast included for all confirmed room bookings.',
            ],
        ];

        $reviews = [
            [
                'name' => 'Amina S.',
                'rating' => 5,
                'comment' => 'Perfect location and calm atmosphere. The staff were very helpful during our stay.',
            ],
            [
                'name' => 'Omar H.',
                'rating' => 4,
                'comment' => 'Rooms were spacious and clean. Easy access to Haram and ideal for pilgrim groups.',
            ],
            [
                'name' => 'Fatima R.',
                'rating' => 5,
                'comment' => 'Excellent hospitality, felt like home during our pilgrimage with fast check-in.',
            ],
        ];

        return view('hotels.details', compact('hotel', 'recommendations', 'policyHighlights', 'reviews', 'checkIn', 'checkOut', 'roomTypeAvailabilities'));
    }
}
