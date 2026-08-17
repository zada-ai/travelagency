<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\PackageBooking;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelAgentBookingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        // Get hotel bookings
        $hotelBookingsQuery = Booking::query()
            ->where('travel_agent_id', $agent->id)
            ->with(['hotel', 'roomType', 'mealPlan']);

        // Get flight bookings
        $flightBookingsQuery = FlightBooking::query()
            ->where('travel_agent_id', $agent->id)
            ->with(['ticket', 'agent']);

        // Get package bookings
        $packageBookingsQuery = PackageBooking::query()
            ->with(['package', 'user', 'passengers']);

        // Some installations may not have travel_agent_id on package_bookings.
        // Guard the filter with a schema check to avoid SQL errors.
        if (Schema::hasColumn('package_bookings', 'travel_agent_id')) {
            $packageBookingsQuery->where('travel_agent_id', $agent->id);
        } else {
            // Fallback: no travel agent column available — return empty result set
            $packageBookingsQuery->whereRaw('0 = 1');
        }

        // Apply filters
        if ($request->filled('booking_id')) {
            $hotelBookingsQuery->where('id', 'like', '%' . $request->booking_id . '%');
            $flightBookingsQuery->where('id', 'like', '%' . $request->booking_id . '%');
        }

        if ($request->filled('customer')) {
            $hotelBookingsQuery->where('contact_name', 'like', '%' . $request->customer . '%');
            $flightBookingsQuery->where('contact_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->filled('status')) {
            $hotelBookingsQuery->where('status', $request->status);
            $flightBookingsQuery->where('status', $request->status);
            $packageBookingsQuery->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $hotelBookingsQuery->whereBetween('created_at', [$request->from_date, $request->to_date]);
            $flightBookingsQuery->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $hotelBookings = $hotelBookingsQuery->orderByDesc('created_at')->paginate(10);
        $flightBookings = $flightBookingsQuery->orderByDesc('created_at')->paginate(10);
        $packageBookings = $packageBookingsQuery->orderByDesc('created_at')->paginate(10);

        return view('travel_agents.booking-history.index', compact(
            'agent',
            'hotelBookings',
            'flightBookings',
            'packageBookings'
        ));
    }

    public function showHotelBooking($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $booking = Booking::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['hotel', 'roomType', 'mealPlan', 'passengers'])
            ->firstOrFail();

        return view('travel_agents.booking-history.show-hotel', compact('agent', 'booking'));
    }

    public function showFlightBooking($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $booking = FlightBooking::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->with(['ticket', 'agent'])
            ->firstOrFail();

        return view('travel_agents.booking-history.show-flight', compact('agent', 'booking'));
    }

    public function cancelHotelBooking($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $booking = Booking::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if (in_array($booking->status, ['Cancelled', 'Completed'])) {
            return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
        }

        $booking->cancel();

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function cancelFlightBooking($id)
    {
        $agent = Auth::guard('travel_agent')->user();
        
        $booking = FlightBooking::where('id', $id)
            ->where('travel_agent_id', $agent->id)
            ->firstOrFail();

        if (in_array($booking->status, ['Cancelled', 'Rejected'])) {
            return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
        }

        $booking->cancel();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
