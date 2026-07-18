<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::orderByDesc('created_at')->paginate(15);

        return view('admin.airline-ticket-management', compact('tickets'));
    }

    public function store(StoreFlightRequest $request)
    {
        Ticket::create(array_merge($request->validated(), [
            'available_seats' => $request->validated()['total_seats'],
        ]));

        return redirect()->route('admin.airline-ticket-management')->with('success', 'Flight uploaded successfully.');
    }
}
