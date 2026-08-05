<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->forPortal('customer')
            ->when($request->filled('from'), fn ($query) => $query->where('route', 'like', '%' . $request->input('from') . '%'))
            ->when($request->filled('to'), fn ($query) => $query->where('route', 'like', '%' . $request->input('to') . '%'))
            ->when($request->filled('departure'), fn ($query) => $query->whereDate('departure_date', $request->input('departure')))
            ->when($request->filled('return'), fn ($query) => $query->whereDate('return_date', $request->input('return')))
            ->when($request->filled('airline'), fn ($query) => $query->where('airline', 'like', '%' . $request->input('airline') . '%'))
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->orderByDesc('created_at')
            ->get();

        return view('user.tickets', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $portal = Auth::guard('travel_agent')->check() ? 'agent' : 'customer';

        if (! Ticket::query()->forPortal($portal)->whereKey($ticket->id)->exists()) {
            abort(404);
        }

        $ticket->load([
            'departureAirport',
            'arrivalAirport',
            'returnDepartureAirport',
            'returnArrivalAirport',
            'cabinPrices',
        ]);

        $visaType = VisaType::active()->latest('id')->first();

        return view('user.ticket', compact('ticket', 'visaType'));
    }
}
