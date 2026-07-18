<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightBookingRequest;
use App\Models\FlightBooking;
use App\Models\Ticket;
use App\Models\TravelAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFlightBookingController extends Controller
{
    public function store(StoreFlightBookingRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();
        $cabinClass = $validated['cabin_class'] ?? 'Economy';

        if ($ticket->status !== 'Approved' || $ticket->available_seats <= 0) {
            return redirect()->back()->withErrors(['ticket' => 'This flight is not available for booking.']);
        }

        $classAvailable = $ticket->getClassAvailableSeats($cabinClass);
        $validated['cabin_class'] = $cabinClass;

        $passengers = collect($validated['passengers'] ?? [])->map(function (array $passenger) {
            $birthDate = new \DateTime($passenger['date_of_birth']);
            $today = new \DateTime();
            $interval = $today->diff($birthDate);
            $age = (int) $interval->y;

            if ($age < 2) {
                $passenger['passenger_type'] = 'Infant';
            } elseif ($age < 12) {
                $passenger['passenger_type'] = 'Child';
            } else {
                $passenger['passenger_type'] = 'Adult';
            }

            $passenger['age'] = $age;
            return $passenger;
        })->values();

        $adultCount = $passengers->where('passenger_type', 'Adult')->count();
        $childCount = $passengers->where('passenger_type', 'Child')->count();
        $infantCount = $passengers->where('passenger_type', 'Infant')->count();
        $totalPassengers = $passengers->count();

        if ($totalPassengers === 0) {
            return redirect()->back()->withErrors(['adults' => 'At least one passenger is required.']);
        }

        if ($totalPassengers > $ticket->available_seats) {
            return redirect()->back()->withErrors(['adults' => 'Booking exceeds available seats. Only '.$ticket->available_seats.' seats are left.']);
        }

        if ($totalPassengers > $classAvailable) {
            return redirect()->back()->withErrors(['cabin_class' => 'Booking exceeds available seats for '.$cabinClass.'. Only '.$classAvailable.' seats are left in this cabin.']);
        }

        $adultPrice = $ticket->adult_price ?? $ticket->price;
        $childPrice = $ticket->child_price ?? $adultPrice;
        $infantPrice = $ticket->infant_price ?? $childPrice;

        $subtotal = ($adultCount * $adultPrice)
            + ($childCount * $childPrice)
            + ($infantCount * $infantPrice);

        $taxes = round($subtotal * ($ticket->tax_rate ?? 0.08), 2);
        $serviceCharge = round($subtotal * ($ticket->service_charge_rate ?? 0.015), 2);
        $grandTotal = round($subtotal + $taxes + $serviceCharge, 2);

        session([
            'flight_booking_review' => [
                'ticket_id' => $ticket->id,
                'ticket_reference' => $ticket->reference,
                'ticket_airline' => $ticket->airline,
                'ticket_route' => $ticket->route,
                'ticket_flight_number' => $ticket->flight_number,
                'adults' => $adultCount,
                'children' => $childCount,
                'infants' => $infantCount,
                'total_passengers' => $totalPassengers,
                'passengers' => $passengers->toArray(),
                'contact_name' => $validated['contact_name'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'reference' => $validated['reference'] ?? $ticket->reference,
                'special_requests' => $validated['special_requests'] ?? null,
                'payment_status' => $validated['payment_status'] ?? 'Unpaid',
                'status' => $validated['status'] ?? 'Pending',
                'cabin_class' => $validated['cabin_class'] ?? 'Economy',
                'seat_numbers' => $this->generateSeatNumbers($ticket, $totalPassengers, $validated['cabin_class']),
                'subtotal' => $subtotal,
                'taxes' => $taxes,
                'service_charge' => $serviceCharge,
                'grand_total' => $grandTotal,
            ],
        ]);

        return redirect()->route('travel-agents.bookings.review');
    }

    public function review()
    {
        $booking = session('flight_booking_review');

        if (! $booking) {
            return redirect()->route('travel-agents.tickets')->withErrors(['booking' => 'No booking data found.']);
        }

        $ticket = Ticket::find($booking['ticket_id']);

        if (! $ticket || $ticket->status !== 'Approved') {
            return redirect()->route('travel-agents.tickets')->withErrors(['ticket' => 'This flight is no longer available.']);
        }

        return view('travel_agents.bookings.review', [
            'ticket' => $ticket,
            'booking' => $booking,
        ]);
    }

    public function confirm(Request $request)
    {
        $booking = session('flight_booking_review');

        if (! $booking) {
            return redirect()->route('travel-agents.tickets')->withErrors(['booking' => 'No booking data found.']);
        }

        $ticket = Ticket::find($booking['ticket_id']);

        if (! $ticket || $ticket->status !== 'Approved') {
            return redirect()->route('travel-agents.tickets')->withErrors(['ticket' => 'This flight is no longer available.']);
        }

        if ($booking['total_passengers'] > $ticket->available_seats) {
            return redirect()->route('travel-agents.tickets')->withErrors(['adults' => 'Booking exceeds available seats.']);
        }

        if ($booking['total_passengers'] > $ticket->getClassAvailableSeats($booking['cabin_class'])) {
            return redirect()->route('travel-agents.tickets')->withErrors(['cabin_class' => 'Booking exceeds available seats for '.$booking['cabin_class'].'.']);
        }

        if (! $ticket->reserveSeats($booking['total_passengers'], $booking['cabin_class'])) {
            return redirect()->route('travel-agents.tickets')->withErrors(['adults' => 'Unable to reserve seats. Please try again.']);
        }

        FlightBooking::create([
            'ticket_id' => $ticket->id,
            'travel_agent_id' => Auth::guard('travel_agent')->id(),
            'adults' => $booking['adults'],
            'children' => $booking['children'],
            'infants' => $booking['infants'],
            'total_passengers' => $booking['total_passengers'],
            'passenger_details' => $booking['passengers'],
            'contact_name' => $booking['contact_name'],
            'contact_email' => $booking['contact_email'],
            'contact_phone' => $booking['contact_phone'],
            'reference' => $booking['reference'],
            'special_requests' => $booking['special_requests'],
            'status' => $booking['status'],
            'payment_status' => $booking['payment_status'],
            'cabin_class' => $booking['cabin_class'],
            'seat_numbers' => $booking['seat_numbers'],
            'price' => $booking['subtotal'],
            'taxes' => $booking['taxes'],
            'service_charge' => $booking['service_charge'],
            'grand_total' => $booking['grand_total'],
        ]);

        session()->forget('flight_booking_review');

        return redirect()->route('travel-agents.bookings')->with('success', 'Booking created successfully.');
    }

    public function cancelReview(Request $request)
    {
        session()->forget('flight_booking_review');

        return redirect()->route('travel-agents.tickets')->with('info', 'Booking review cancelled.');
    }

    public function agentBookings()
    {
        $agent = Auth::guard('travel_agent')->user();
        $bookings = FlightBooking::with('ticket')->where('travel_agent_id', $agent->id)
            ->orderByDesc('created_at')
            ->get();

        return view('travel_agents.bookings.index', compact('agent', 'bookings'));
    }

    public function index(Request $request)
    {
        $bookings = FlightBooking::with(['ticket', 'agent'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.airline-bookings.index', compact('bookings'));
    }

    public function show(FlightBooking $flightBooking)
    {
        $flightBooking->load(['ticket', 'agent']);

        return view('admin.airline-bookings.show', compact('flightBooking'));
    }

    public function updateStatus(Request $request, FlightBooking $flightBooking)
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Reserved,Confirmed,Cancelled,Rejected'],
        ]);

        $newStatus = $request->input('status');
        $oldStatus = $flightBooking->status;
        $oldFinal = in_array($oldStatus, ['Cancelled', 'Rejected'], true);
        $newFinal = in_array($newStatus, ['Cancelled', 'Rejected'], true);

        if ($oldFinal && ! $newFinal) {
            if (! $flightBooking->ticket->reserveSeats($flightBooking->total_passengers, $flightBooking->cabin_class)) {
                return redirect()->back()->withErrors(['status' => 'Not enough seats available to reactivate this booking.']);
            }
        }

        if (! $oldFinal && $newFinal) {
            $flightBooking->ticket->releaseSeats($flightBooking->total_passengers, $flightBooking->cabin_class);
        }

        $flightBooking->status = $newStatus;
        $flightBooking->save();

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(FlightBooking $flightBooking)
    {
        if (! in_array($flightBooking->status, ['Cancelled', 'Rejected'], true)) {
            $flightBooking->ticket->releaseSeats($flightBooking->total_passengers, $flightBooking->cabin_class);
        }

        $flightBooking->delete();

        return redirect()->route('admin.airline-bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function cancel(FlightBooking $flightBooking)
    {
        $flightBooking->cancel();

        return redirect()->back()->with('success', 'Booking cancelled and seats restored.');
    }

    private function generateSeatNumbers(Ticket $ticket, int $passengers, string $cabinClass): array
    {
        $prefixes = [
            'Economy' => 'E',
            'Premium Economy' => 'PE',
            'Business' => 'B',
            'First' => 'F',
        ];

        $classOrder = ['Economy', 'Premium Economy', 'Business', 'First'];
        $start = 1;

        foreach ($classOrder as $class) {
            if ($class === $cabinClass) {
                break;
            }
            $start += $ticket->getClassAvailableSeats($class) + ($ticket->{$ticket->getCabinField($class)} ?? 0) - $ticket->getClassAvailableSeats($class);
        }

        $start = max(1, $start);
        $prefix = $prefixes[$cabinClass] ?? 'S';

        return collect(range(0, $passengers - 1))
            ->map(fn ($offset) => $prefix . ($start + $offset))
            ->toArray();
    }
}
