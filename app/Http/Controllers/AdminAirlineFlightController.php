<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightRequest;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Ticket;
use App\Models\TicketCabinPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $flights = Ticket::withCount([
            'bookings as confirmed_bookings_count' => fn ($query) =>
                $query->where('status', 'Confirmed'),

            'bookings as pending_bookings_count' => fn ($query) =>
                $query->where('status', 'Pending'),

            'bookings as cancelled_bookings_count' => fn ($query) =>
                $query->where('status', 'Cancelled'),
        ])
            ->when(
                $status &&
                in_array($status, ['Approved', 'Pending', 'Rejected'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.airline-flights.index',
            compact('flights', 'activeStatus', 'statuses')
        );
    }

    public function create()
    {
        $airlines = Airline::orderBy('name')->get();

        $airports = Airport::orderBy('city')
            ->orderBy('code')
            ->get();

        return view(
            'admin.airline-flights.create',
            compact('airlines', 'airports')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE FLIGHT
    |--------------------------------------------------------------------------
    */

    public function store(StoreFlightRequest $request)
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | AIRLINE
        |--------------------------------------------------------------------------
        */

        if (
            ! empty($data['airline_id']) &&
            empty($data['airline'])
        ) {
            $data['airline'] =
                Airline::find($data['airline_id'])?->name;
        }

        /*
        |--------------------------------------------------------------------------
        | CABIN PRICES
        |--------------------------------------------------------------------------
        */

        $cabinPrices = $data['cabin_prices'] ?? [];

        unset($data['cabin_prices']);

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE SEATS
        |--------------------------------------------------------------------------
        */

        $data['available_seats'] = $data['total_seats'];

        /*
        |--------------------------------------------------------------------------
        | CREATE TICKET + CABIN PRICES
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $data,
            $cabinPrices,
            &$ticket
        ) {
            $ticket = Ticket::create($data);

            foreach ($cabinPrices as $cabinClass => $price) {
                if ($price !== null && $price !== '') {
                    TicketCabinPrice::create([
                        'ticket_id' => $ticket->id,
                        'cabin_class' => $cabinClass,
                        'price' => $price,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.airline-flights.index')
            ->with(
                'success',
                'Flight created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Ticket $ticket)
    {
        $airlines = Airline::orderBy('name')->get();

        $airports = Airport::orderBy('city')
            ->orderBy('code')
            ->get();

        $ticket->load('cabinPrices');

        return view(
            'admin.airline-flights.edit',
            compact(
                'ticket',
                'airlines',
                'airports'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        StoreFlightRequest $request,
        Ticket $ticket
    ) {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | AIRLINE
        |--------------------------------------------------------------------------
        */

        if (
            ! empty($data['airline_id']) &&
            empty($data['airline'])
        ) {
            $data['airline'] =
                Airline::find($data['airline_id'])?->name;
        }

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE SEATS
        |--------------------------------------------------------------------------
        */

        $bookedSeats = $ticket
            ->bookings()
            ->where('status', '!=', 'Cancelled')
            ->sum('total_passengers');

        $data['available_seats'] = max(
            0,
            $data['total_seats'] - $bookedSeats
        );

        /*
        |--------------------------------------------------------------------------
        | CABIN PRICES
        |--------------------------------------------------------------------------
        */

        $cabinPrices = $data['cabin_prices'] ?? [];

        unset($data['cabin_prices']);

        /*
        |--------------------------------------------------------------------------
        | UPDATE TICKET + CABIN PRICES
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $ticket,
            $data,
            $cabinPrices
        ) {
            $ticket->update($data);

            /*
            | Remove old cabin prices first.
            | Then create the current submitted prices.
            */

            TicketCabinPrice::where(
                'ticket_id',
                $ticket->id
            )->delete();

            foreach ($cabinPrices as $cabinClass => $price) {
                if ($price !== null && $price !== '') {
                    TicketCabinPrice::create([
                        'ticket_id' => $ticket->id,
                        'cabin_class' => $cabinClass,
                        'price' => $price,
                    ]);
                }
            }
        });

        return redirect()
            ->route(
                'admin.airline-flights.show',
                $ticket
            )
            ->with(
                'success',
                'Flight updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()
            ->route('admin.airline-ticket-management')
            ->with(
                'success',
                'Flight deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Ticket $ticket
    ) {
        $request->validate([
            'status' => [
                'required',
                'in:Pending,Approved,Rejected',
            ],
        ]);

        $ticket->status = $request->input('status');
        $ticket->save();

        return redirect()
            ->route(
                'admin.airline-flights.show',
                $ticket
            )
            ->with(
                'success',
                'Flight status updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'bookings',
            'cabinPrices',
        ])->loadCount([
            'bookings as approved_bookings_count' =>
                fn ($query) =>
                    $query->where('status', 'Confirmed'),

            'bookings as pending_bookings_count' =>
                fn ($query) =>
                    $query->where('status', 'Pending'),
        ]);

        return view(
            'admin.airline-flights.show',
            compact('ticket')
        );
    }
}