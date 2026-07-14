<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicHotelController extends Controller
{
    public function show(Hotel $hotel, Request $request)
    {
        $checkIn = $request->input('check_in') ? Carbon::parse($request->input('check_in'))->startOfDay() : null;
        $checkOut = $request->input('check_out') ? Carbon::parse($request->input('check_out'))->startOfDay() : null;

        if ($checkIn && $checkOut && $checkOut->lt($checkIn)) {
            $checkOut = null;
        }

        $hotel->load(['roomTypes.hotelRooms', 'seasonalRates', 'mealPlans', 'facilities', 'images', 'coverImage']);

        $availableRoomsNow = $hotel->rooms()->where('status', 'Available')->count();

        $initialRoomTypes = $hotel->roomTypes->where('status', 'Active')->map(function ($roomType) {
            return [
                'id' => $roomType->id,
                'room_name' => $roomType->room_name,
                'rate' => (float) $roomType->daily_rate,
                'capacity' => $roomType->max_occupancy,
                'extra_bed_price' => (float) $roomType->extra_bed_price,
                'available_rooms' => $roomType->hotelRooms->where('status', 'Available')->count(),
                'status' => 'Select your dates to check availability',
                'unavailable_dates' => [],
            ];
        })->values()->toArray();

        $roomTypeAvailabilities = [];
        $totalAvailable = 0;

        if ($checkIn && $checkOut) {
            foreach ($hotel->roomTypes->where('status', 'Active') as $roomType) {
                $availability = $roomType->summarizeAvailabilityForDates($checkIn, $checkOut);

                $roomTypeAvailabilities[$roomType->id] = array_merge($availability, [
                    'id' => $roomType->id,
                    'room_name' => $roomType->room_name,
                    'rate' => $roomType->rateForDates($checkIn, $checkOut),
                    'capacity' => $roomType->max_occupancy,
                    'extra_bed_price' => (float) $roomType->extra_bed_price,
                ]);
                $totalAvailable += $availability['available_rooms'];
            }
        }

        if ($request->boolean('ajax')) {
            return response()->json([
                'roomTypeAvailabilities' => array_values($roomTypeAvailabilities),
                'totalAvailable' => $totalAvailable,
            ]);
        }

        $recommendations = Hotel::active()
            ->whereRaw('LOWER(city) = ?', [mb_strtolower($hotel->city)])
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

        return view('hotels.details', compact('hotel', 'recommendations', 'policyHighlights', 'reviews', 'checkIn', 'checkOut', 'roomTypeAvailabilities', 'initialRoomTypes', 'availableRoomsNow'));
    }
}
