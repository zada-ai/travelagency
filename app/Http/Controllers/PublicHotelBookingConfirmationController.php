<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class PublicHotelBookingConfirmationController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['hotel', 'roomType', 'room', 'mealPlan', 'passengers']);

        return view('hotels.booking-confirmation', compact('booking'));
    }
}
