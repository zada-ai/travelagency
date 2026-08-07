<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightBookingRequest;
use App\Models\FlightBooking;
use App\Models\FlightBookingPassenger;
use App\Models\Ticket;
use App\Models\TravelAgent;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PackageBooking;

class AdminFlightBookingController extends Controller
{
    public function store(StoreFlightBookingRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();
        $cabinClass = $validated['cabin_class'] ?? 'Economy';
        $includeVisa = (bool) ($validated['include_visa'] ?? false);
        $includeTransport = (bool) ($validated['include_transport'] ?? false);

        if ($ticket->status !== 'Approved' || $ticket->available_seats <= 0) {
            return redirect()->back()->withErrors(['ticket' => 'This flight is not available for booking.']);
        }

        $classAvailable = $ticket->getClassAvailableSeats($cabinClass);
        $validated['cabin_class'] = $cabinClass;
        $selectedCabinPrice = $ticket->getCabinPrice($cabinClass) ?? ($ticket->adult_price ?? $ticket->price);

        $passengers = collect($validated['passengers'] ?? [])->map(function (array $passenger) {
            if (! empty($passenger['passport_upload']) && is_a($passenger['passport_upload'], \Illuminate\Http\UploadedFile::class)) {
                $passenger['passport_upload'] = $passenger['passport_upload']->store('flight_passenger_documents/passports', 'public');
            }

            if (! empty($passenger['cnic_upload']) && is_a($passenger['cnic_upload'], \Illuminate\Http\UploadedFile::class)) {
                $passenger['cnic_upload'] = $passenger['cnic_upload']->store('flight_passenger_documents/cnic', 'public');
            }

            $fullName = trim(isset($passenger['full_name']) ? $passenger['full_name'] : '');
            if ($fullName !== '') {
                $nameParts = preg_split('/\s+/', $fullName);
                if (count($nameParts) > 1) {
                    $passenger['last_name'] = array_pop($nameParts);
                    $passenger['first_name'] = implode(' ', $nameParts);
                } else {
                    $passenger['first_name'] = $fullName;
                    $passenger['last_name'] = null;
                }
            } else {
                $passenger['first_name'] = null;
                $passenger['last_name'] = null;
            }

            $birthDateValue = isset($passenger['date_of_birth']) ? $passenger['date_of_birth'] : '';
            $birthDate = new \DateTime($birthDateValue ?: 'now');
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
            if ($classAvailable <= 0) {
                return redirect()->back()->withErrors(['cabin_class' => 'Not Available']);
            }
            $msg = $classAvailable === 1
                ? 'Only 1 ' . $cabinClass . ' seat is available.'
                : 'Only ' . $classAvailable . ' ' . $cabinClass . ' seats are available.';
            return redirect()->back()->withErrors(['cabin_class' => $msg]);
        }

        $adultPrice = $selectedCabinPrice;
        $childPrice = $ticket->child_price ?? $adultPrice;
        $infantPrice = $ticket->infant_price ?? $childPrice;

        $subtotal = ($adultCount * $adultPrice)
            + ($childCount * $childPrice)
            + ($infantCount * $infantPrice);

        $selectedCabinPrice = $selectedCabinPrice;

        $transportPrice = 0;
        if ($includeTransport) {
            $transportPrice = ($adultCount * 520) + ($childCount * 600) + ($infantCount * 520);
        }

        $activeVisaType = VisaType::active()->latest('id')->first();
        $visaPrice = $includeVisa ? ($activeVisaType?->total_cost ?? 1400) : 0;

        $taxBase = $subtotal + $transportPrice + $visaPrice;
        $taxes = round($taxBase * ($ticket->tax_rate ?? 0.08), 2);
        $serviceCharge = round($taxBase * ($ticket->service_charge_rate ?? 0.015), 2);
        $grandTotal = round($taxBase + $taxes + $serviceCharge, 2);

        $initiator = Auth::guard('travel_agent')->check() ? 'agent' : (Auth::check() ? 'customer' : 'guest');

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
                'selected_cabin_price' => $selectedCabinPrice,
                'subtotal' => $subtotal,
                'visa_price' => $visaPrice,
                'transport_price' => $transportPrice,
                'include_visa' => $includeVisa,
                'include_transport' => $includeTransport,
                'taxes' => $taxes,
                'service_charge' => $serviceCharge,
                'grand_total' => $grandTotal,
                'initiator' => $initiator,
                'initiator_id' => $initiator === 'agent' ? Auth::guard('travel_agent')->id() : (Auth::id() ?? null),
            ],
        ]);

        if ($initiator === 'agent') {
            return redirect()->route('travel-agents.bookings.review');
        }

        return redirect()->route('bookings.review');
    }

    public function review()
    {
        $booking = session('flight_booking_review');

        if (! $booking) {
            $redirect = Auth::guard('travel_agent')->check() ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['booking' => 'No booking data found.']);
        }

        $ticket = Ticket::with([
            'departureAirport',
            'arrivalAirport',
            'returnDepartureAirport',
            'returnArrivalAirport',
            'cabinPrices',
        ])->find($booking['ticket_id']);

        if (! $ticket || $ticket->status !== 'Approved') {
            $redirect = $booking['initiator'] === 'agent' ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['ticket' => 'This flight is no longer available.']);
        }

        // Render role-appropriate review view
        return view('travel_agents.bookings.review', [
            'ticket' => $ticket,
            'booking' => $booking,
        ]);
    }

    public function confirm(Request $request)
    {
        $booking = session('flight_booking_review');

        if (! $booking) {
            $redirect = Auth::guard('travel_agent')->check() ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['booking' => 'No booking data found.']);
        }

        $ticket = Ticket::with([
            'departureAirport',
            'arrivalAirport',
            'returnDepartureAirport',
            'returnArrivalAirport',
            'cabinPrices',
        ])->find($booking['ticket_id']);

        if (! $ticket || $ticket->status !== 'Approved') {
            $redirect = $booking['initiator'] === 'agent' ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['ticket' => 'This flight is no longer available.']);
        }

        if ($booking['total_passengers'] > $ticket->available_seats) {
            $redirect = $booking['initiator'] === 'agent' ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['adults' => 'Booking exceeds available seats.']);
        }

        if ($booking['total_passengers'] > $ticket->getClassAvailableSeats($booking['cabin_class'])) {
            $redirect = $booking['initiator'] === 'agent' ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['cabin_class' => 'Booking exceeds available seats for '.$booking['cabin_class'].'.']);
        }

        if (! $ticket->reserveSeats($booking['total_passengers'], $booking['cabin_class'])) {
            $redirect = $booking['initiator'] === 'agent' ? route('travel-agents.tickets') : route('tickets.index');
            return redirect($redirect)->withErrors(['adults' => 'Unable to reserve seats. Please try again.']);
        }

        $payload = [
            'ticket_id' => $ticket->id,
            'adults' => $booking['adults'],
            'children' => $booking['children'],
            'infants' => $booking['infants'],
            'total_passengers' => $booking['total_passengers'],
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
            'visa_price' => $booking['visa_price'],
            'transport_price' => $booking['transport_price'],
            'include_visa' => (bool) ($booking['include_visa'] ?? false),
            'include_transport' => (bool) ($booking['include_transport'] ?? false),
            'grand_total' => $booking['grand_total'],
        ];

        if (($booking['initiator'] ?? null) === 'agent') {
            $payload['travel_agent_id'] = $booking['initiator_id'] ?? Auth::guard('travel_agent')->id();
            $payload['booking_type'] = 'agent';
        } else {
            $payload['user_id'] = $booking['initiator_id'] ?? Auth::id();
            $payload['booking_type'] = 'customer';
        }

        $flightBooking = FlightBooking::create($payload);
        if (! empty($booking['passengers']) && is_array($booking['passengers'])) {
            $flightBooking->passengers()->createMany(array_map(function ($passenger) {
                return [
                    'passenger_type' => $passenger['passenger_type'] ?? null,
                    'first_name' => $passenger['first_name'] ?? null,
                    'last_name' => $passenger['last_name'] ?? null,
                    'gender' => $passenger['gender'] ?? null,
                    'age' => $passenger['age'] ?? null,
                    'date_of_birth' => $passenger['date_of_birth'] ?? null,
                    'passport_number' => $passenger['passport_number'] ?? null,
                    'passport_expiry' => $passenger['passport_expiry'] ?? null,
                    'nationality' => $passenger['nationality'] ?? null,
                    'passport_upload' => $passenger['passport_upload'] ?? null,
                    'cnic_upload' => $passenger['cnic_upload'] ?? null,
                ];
            }, $booking['passengers']));
        }

        session()->forget('flight_booking_review');

        if (($booking['initiator'] ?? null) === 'agent') {
            return redirect()->route('travel-agents.bookings.confirmation', ['flightBooking' => $flightBooking->id])->with('success', 'Booking created successfully.');
        }

        return redirect()->route('bookings.confirmation', ['flightBooking' => $flightBooking->id])->with('success', 'Booking created successfully.');
    }

    public function customerBookings()
{
    $user = Auth::user();

    // Flight bookings
    $bookings = FlightBooking::with('ticket')
        ->where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->get();

    // Umrah Package bookings
    $packageBookings = PackageBooking::with(['package', 'passengers'])
        ->where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->get();

    return view('customer.bookings.index', compact(
        'user',
        'bookings',
        'packageBookings'
    ));
}

    public function confirmation(FlightBooking $flightBooking)
    {
        if (Auth::guard('travel_agent')->check()) {
            abort_unless($flightBooking->travel_agent_id === Auth::guard('travel_agent')->id(), 403);
        } elseif (Auth::check()) {
            abort_unless($flightBooking->user_id === Auth::id(), 403);
        } else {
            abort(403);
        }

        return view('flight_bookings.confirmation', [
            'booking' => $flightBooking,
        ]);
    }

    public function cancelReview(Request $request)
    {
        session()->forget('flight_booking_review');

        if (Auth::guard('travel_agent')->check()) {
            return redirect()->route('travel-agents.tickets')->with('info', 'Booking review cancelled.');
        }

        return redirect()->route('tickets.index')->with('info', 'Booking review cancelled.');
    }

    public function agentBookings()
    {
        $agent = Auth::guard('travel_agent')->user();
        $bookings = FlightBooking::with(['ticket', 'voucher'])
            ->where('travel_agent_id', $agent->id)
            ->orderByDesc('created_at')
            ->get();

        return view('travel_agents.bookings.index', compact('agent', 'bookings'));
    }

    public function index(Request $request)
    {
        $bookings = FlightBooking::with(['ticket', 'agent', 'passengers'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.airline-bookings.index', compact('bookings'));
    }

    public function show(FlightBooking $flightBooking)
    {
        $flightBooking->load(['ticket', 'agent', 'passengers']);

        return view('admin.airline-bookings.show', compact('flightBooking'));
    }

    public function updateStatus(Request $request, FlightBooking $flightBooking)
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Reserved,Confirmed,Approved,Cancelled,Rejected'],
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

    public function approve(FlightBooking $flightBooking)
    {
        if (in_array($flightBooking->status, ['Cancelled', 'Rejected'], true)) {
            return redirect()->back()->withErrors(['status' => 'This booking cannot be approved.']);
        }

        if ($flightBooking->status !== 'Approved') {
            $flightBooking->status = 'Approved';
            $flightBooking->save();
        }

        return redirect()->back()->with('success', 'Booking approved successfully.');
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
