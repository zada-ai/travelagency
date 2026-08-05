<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightRequest;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::orderByDesc('created_at')->paginate(15);
        $airlines = Airline::orderBy('name')->get();
        $airports = Airport::orderBy('city')->orderBy('code')->get();

        return view('admin.airline-ticket-management', compact('tickets', 'airlines', 'airports'));
    }

    public function store(StoreFlightRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['airline_id']) && empty($data['airline'])) {
            $data['airline'] = Airline::find($data['airline_id'])?->name;
        }

        Ticket::create(array_merge($data, [
            'available_seats' => $data['total_seats'],
        ]));

        return redirect()->route('admin.airline-ticket-management')->with('success', 'Flight uploaded successfully.');
    }
}
