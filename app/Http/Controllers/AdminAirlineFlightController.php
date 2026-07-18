<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminAirlineFlightController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $activeStatus = $status ? ucfirst($status) : 'All';

        $statuses = [
            'All' => Ticket::count(),
            'Approved' => Ticket::where('status', 'Approved')->count(),
            'Pending' => Ticket::where('status', 'Pending')->count(),
            'Rejected' => Ticket::where('status', 'Rejected')->count(),
        ];

        $flights = Ticket::withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'Confirmed')])
            ->withCount(['bookings as pending_bookings_count' => fn ($query) => $query->where('status', 'Pending')])
            ->withCount(['bookings as cancelled_bookings_count' => fn ($query) => $query->where('status', 'Cancelled')])
            ->when($status && in_array($status, ['Approved', 'Pending', 'Rejected'], true), fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.airline-flights.index', compact('flights', 'activeStatus', 'statuses'));
    }

    public function create()
    {
        return view('admin.airline-flights.create');
    }

    public function store(StoreFlightRequest $request)
    {
        $data = $request->validated();
        $data['available_seats'] = $data['total_seats'];

        Ticket::create($data);

        return redirect()->route('admin.airline-flights.index')->with('success', 'Flight created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.airline-flights.edit', compact('ticket'));
    }

    public function update(StoreFlightRequest $request, Ticket $ticket)
    {
        $data = $request->validated();

        $bookedSeats = $ticket->bookings()->where('status', '!=', 'Cancelled')->sum('total_passengers');
        $availableSeats = max(0, $data['total_seats'] - $bookedSeats);
        $data['available_seats'] = $availableSeats;

        $ticket->update($data);

        return redirect()->route('admin.airline-flights.show', $ticket)->with('success', 'Flight updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.airline-ticket-management')->with('success', 'Flight deleted successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Approved,Rejected'],
        ]);

        $ticket->status = $request->input('status');
        $ticket->save();

        return redirect()->route('admin.airline-flights.show', $ticket)->with('success', 'Flight status updated successfully.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('bookings')
            ->loadCount([
                'bookings as approved_bookings_count' => fn ($query) => $query->where('status', 'Confirmed'),
                'bookings as pending_bookings_count' => fn ($query) => $query->where('status', 'Pending'),
            ]);

        return view('admin.airline-flights.show', compact('ticket'));
    }
}
