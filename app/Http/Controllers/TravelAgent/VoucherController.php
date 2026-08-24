<?php

namespace App\Http\Controllers\TravelAgent;

use App\Http\Controllers\Controller;
use App\Models\AgentCompany;
use App\Models\AgentVoucher;
use App\Models\VoucherPackage;
use App\Models\VisaCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VoucherCustomer;
use App\Models\TransportationOption;
use App\Models\Ticket;
use App\Models\Hotel;

class VoucherController extends Controller
{
   public function create()
{ $transportationOptions = TransportationOption::where('status', true)
    ->orderBy('type')
    ->orderBy('sector')
    ->orderBy('vehicle_type')
    ->get();
    // Get the currently authenticated travel agent
    $agent = Auth::guard('travel_agent')->user();

    // Show only the company that matches this agent's registered company_name
    $agentCompanies = AgentCompany::where('status', true)
        ->where('name', trim($agent->company_name))
        ->orderBy('name')
        ->get();

    // Get all active voucher packages
    $packages = VoucherPackage::where('status', true)
        ->orderBy('name')
        ->get();

    // Get all active visa companies
    $visaCompanies = VisaCompany::where('status', true)
        ->orderBy('name')
        ->get();

    // Get only this travel agent's voucher customers
    $customers = VoucherCustomer::where('travel_agent_id', $agent->id)
        ->orderBy('name')
        ->get();

    $tickets = Ticket::with([
        'departureAirport',
        'arrivalAirport',
        'returnDepartureAirport',
        'returnArrivalAirport',
    ])
        ->forPortal('agent')
        ->whereNotIn('status', ['Cancelled', 'Rejected'])
        ->orderByDesc('created_at')
        ->get();

    $hotels = Hotel::with(['inventories' => function ($query) {
        $query->where('status', 'Active')
            ->where('available_rooms', '>', 0)
            ->orderBy('inventory_date');
    }])
        ->active()
        ->visibleToPortal('agent')
        ->orderBy('hotel_name')
        ->get();

    return view(
        'travel_agents.vouchers.create',
        compact(
            'agentCompanies',
            'packages',
            'visaCompanies',
            'customers',
            'transportationOptions',
            'tickets',
            'hotels'
        )
    );
}
    public function storeCustomer(Request $request)
    {
        $agentId = Auth::guard('travel_agent')->id();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'passport_no' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
        ]);

        $exists = VoucherCustomer::where('travel_agent_id', $agentId)
            ->where('passport_no', trim($validated['passport_no']))
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A customer with this passport number already exists.',
            ], 422);
        }

        $customer = VoucherCustomer::create([
            'travel_agent_id' => $agentId,
            'name' => trim($validated['name']),
            'passport_no' => trim($validated['passport_no']),
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer added successfully.',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'passport_no' => $customer->passport_no,
                'date_of_birth' => $customer->date_of_birth->format('Y-m-d'),
            ],
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => [
                'required',
                'exists:tickets,id',
            ],
            'agent_company_id' => [
                'required',
                'exists:agent_companies,id',
            ],

            // Package
            'package' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Transportation
            'transportation_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transportation_sector' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transport_persons' => [
                'nullable',
                'integer',
                'min:1',
            ],

            // Arrival to KSA
            'arrival_flight_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'arrival_flight_pnr' => [
                'nullable',
                'string',
                'max:50',
            ],

            'arrival_departure_time' => [
                'nullable',
                'date',
            ],

            'arrival_arrival_time' => [
                'nullable',
                'date',
            ],

            'arrival_departure_from' => [
                'nullable',
                'string',
                'max:255',
            ],

            'arrival_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'arrival_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            // Departure from KSA
            'departure_flight_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'departure_flight_pnr' => [
                'nullable',
                'string',
                'max:50',
            ],

            'departure_departure_time' => [
                'nullable',
                'date',
            ],

            'departure_arrival_time' => [
                'nullable',
                'date',
            ],

            'departure_from' => [
                'nullable',
                'string',
                'max:255',
            ],

            'departure_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'departure_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            // Hotels & passengers
            'hotels_json' => [
                'nullable',
                'string',
            ],

            'passengers_json' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $ticket = Ticket::with([
            'departureAirport',
            'arrivalAirport',
            'returnDepartureAirport',
            'returnArrivalAirport',
        ])
            ->forPortal('agent')
            ->whereNotIn('status', ['Cancelled', 'Rejected'])
            ->findOrFail($validated['ticket_id']);

        unset($validated['ticket_id']);

        $validated['arrival_flight_no'] = $ticket->flight_number;
        $validated['arrival_departure_time'] = $ticket->departure_date && $ticket->departure_time
            ? $ticket->departure_date->format('Y-m-d') . ' ' . $ticket->departure_time
            : null;
        $validated['arrival_arrival_time'] = $ticket->departure_date && $ticket->arrival_time
            ? $ticket->departure_date->format('Y-m-d') . ' ' . $ticket->arrival_time
            : null;
        $validated['arrival_departure_from'] = $ticket->departureAirport?->name ?? $ticket->departureAirport?->code;
        $validated['arrival_to'] = $ticket->arrivalAirport?->name ?? $ticket->arrivalAirport?->code;
        $validated['departure_flight_no'] = $ticket->flight_number;
        $validated['departure_departure_time'] = $ticket->return_date && $ticket->return_departure_time
            ? $ticket->return_date->format('Y-m-d') . ' ' . $ticket->return_departure_time
            : null;
        $validated['departure_arrival_time'] = $ticket->return_date && $ticket->return_arrival_time
            ? $ticket->return_date->format('Y-m-d') . ' ' . $ticket->return_arrival_time
            : null;
        $validated['departure_from'] = $ticket->returnDepartureAirport?->name ?? $ticket->returnDepartureAirport?->code;
        $validated['departure_to'] = $ticket->returnArrivalAirport?->name ?? $ticket->returnArrivalAirport?->code;

        // Handle arrival PDF upload
        if ($request->hasFile('arrival_pdf')) {
            $validated['arrival_pdf'] = $request
                ->file('arrival_pdf')
                ->store('agent_vouchers/arrival', 'public');
        }

        // Handle departure PDF upload
        if ($request->hasFile('departure_pdf')) {
            $validated['departure_pdf'] = $request
                ->file('departure_pdf')
                ->store('agent_vouchers/departure', 'public');
        }

        // Decode passengers JSON
        $validated['passengers'] = json_decode(
            $request->input('passengers_json', '[]'),
            true
        ) ?: [];

        // Extract hotels
        $validated['hotels'] = $this->extractHotels($request);

        // Associate voucher with authenticated travel agent
        $validated['travel_agent_id'] = Auth::guard('travel_agent')->id();

        // Save voucher
        AgentVoucher::create($validated);

        return redirect()
            ->route('travel-agents.vouchers.create')
            ->with('success', 'Voucher saved successfully.');
    }

    /**
     * Extract the hotels array from nested hotels[0][...] fields.
     */
    private function extractHotels(Request $request): array
    {
        $hotelsRaw = $request->input('hotels', []);

        if (empty($hotelsRaw)) {
            return [];
        }

        $hotels = [];

        foreach ($hotelsRaw as $row) {
            if (!empty($row['hotel'])) {
                $hotels[] = [
                    'hotel' => $row['hotel'] ?? null,
                    'check_in' => $row['check_in'] ?? null,
                    'check_out' => $row['check_out'] ?? null,
                    'nights' => $row['nights'] ?? null,
                    'type' => $row['type'] ?? null,
                    'rooms' => $row['rooms'] ?? null,
                    'pax' => $row['pax'] ?? null,
                    'total' => $row['total'] ?? null,
                ];
            }
        }

        return $hotels;
    }
}