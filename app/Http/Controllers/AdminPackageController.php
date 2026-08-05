<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Ticket;
use App\Models\PackageTransportRate;

class AdminPackageController extends Controller
{
    private function normalizeHotelStays(array $rows): \Illuminate\Support\Collection
    {
        return collect($rows)
            ->map(function ($row) {
                $normalized = [];

                foreach ($row as $key => $value) {
                    $normalized[$key] = is_string($value) ? trim($value) : $value;
                }

                if (array_key_exists('room_sharing_options', $normalized)) {
                    $normalized['room_sharing_options'] = $this->normalizeRoomSharingOptions($normalized['room_sharing_options']);
                }

                if (array_key_exists('custom_to_haram', $normalized)) {
                    $normalized['custom_to_haram'] = (bool) ($normalized['custom_to_haram'] ?? false);
                }

                return $normalized;
            })
            ->filter(function ($row) {
                return $this->hasMeaningfulHotelStayData($row);
            })
            ->values();
    }

    private function normalizeRoomSharingOptions(mixed $value): ?array
    {
        if (is_array($value)) {
            $items = [];

            foreach ($value as $item) {
                if (is_string($item)) {
                    $trimmed = trim($item);

                    if ($trimmed !== '') {
                        $items[] = $trimmed;
                    }
                }
            }

            return $items;
        }

        if (is_string($value)) {
            $parts = preg_split('/[\r\n,]+/', $value);

            if ($parts === false) {
                return null;
            }

            $items = [];

            foreach ($parts as $item) {
                if (is_string($item)) {
                    $trimmed = trim($item);

                    if ($trimmed !== '') {
                        $items[] = $trimmed;
                    }
                }
            }

            return $items;
        }

        return null;
    }

    private function hasMeaningfulHotelStayData(array $row): bool
    {
        return ! empty($row['hotel_name'])
            || ! empty($row['city'])
            || ! empty($row['star_rating'])
            || ! empty($row['check_in'])
            || ! empty($row['check_out'])
            || ! empty($row['nights'])
            || ! empty($row['distance_from_haram'])
            || ! empty($row['walking_time'])
            || ! empty($row['transport_notes'])
            || ! empty($row['room_type'])
            || ! empty($row['price_per_person'])
            || ! empty($row['room_sharing_options'])
            || ! empty($row['custom_to_haram']);
    }

    public function index()
    {
        $packages = Package::latest()->paginate(20);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $tickets = Ticket::with(['departureAirport', 'arrivalAirport'])
            ->where('status', 'Approved')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_date')
            ->get();

        return view('admin.packages.create', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'airline' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'departure_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'visa_processing_price' => 'nullable|numeric|min:0',
            'transport_price' => 'nullable|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'has_visa' => 'boolean',
            'has_hotel' => 'boolean',
            'has_transport' => 'boolean',
            'has_flight' => 'boolean',
            'has_meals' => 'boolean',
            'makkah_hotel' => 'nullable|string|max:255',
            'madinah_hotel' => 'nullable|string|max:255',
            'status' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'show_to_agents' => 'boolean',
            'show_to_customers' => 'boolean',
            'outbound_flight_id' => 'required_with:has_flight|nullable|exists:tickets,id',
            'return_flight_id' => 'nullable|different:outbound_flight_id|exists:tickets,id',
            'hotel_stays' => 'nullable|array',
            'hotel_stays.*.hotel_name' => 'nullable|string|max:255',
            'hotel_stays.*.city' => 'nullable|string|max:255',
            'hotel_stays.*.star_rating' => 'nullable|integer|min:0|max:5',
            'hotel_stays.*.check_in' => 'nullable|date',
            'hotel_stays.*.check_out' => 'nullable|date',
            'hotel_stays.*.nights' => 'nullable|integer|min:1',
            'hotel_stays.*.room_type' => 'nullable|string|max:255',
            'hotel_stays.*.price_per_person' => 'nullable|numeric|min:0',
            'hotel_stays.*.distance_from_haram' => 'nullable|string|max:255',
            'hotel_stays.*.walking_time' => 'nullable|string|max:255',
            'hotel_stays.*.room_sharing_options' => 'nullable',
            'hotel_stays.*.transport_notes' => 'nullable|string',
            'hotel_stays.*.custom_to_haram' => 'nullable|boolean',
            'transport_rates' => 'nullable|array',
'transport_rates.1.price' => 'nullable|numeric|min:0',
'transport_rates.2.price' => 'nullable|numeric|min:0',
'transport_rates.3.price' => 'nullable|numeric|min:0',
'transport_rates.4.price' => 'nullable|numeric|min:0',
'transport_rates.5_49.price' => 'nullable|numeric|min:0',
'transport_rates.infant.price' => 'nullable|numeric|min:0',
        ]);

        $validated['available_seats'] = $validated['total_seats'];
        $validated['has_visa'] = $request->has('has_visa');
        $validated['has_hotel'] = $request->has('has_hotel');
        $validated['has_transport'] = $request->has('has_transport');
        $validated['has_flight'] = $request->has('has_flight');
        $validated['has_meals'] = $request->has('has_meals');
        $validated['show_to_agents'] = $request->has('show_to_agents');
        $validated['show_to_customers'] = $request->has('show_to_customers');

        if (! $request->has('has_flight')) {
            $validated['outbound_flight_id'] = null;
            $validated['return_flight_id'] = null;
        }

        $hotelStays = $this->normalizeHotelStays($request->input('hotel_stays', []));

        foreach ($hotelStays as $index => $stay) {
            if (empty($stay['hotel_name']) && empty($stay['city'])) {
                continue;
            }

            if (! empty($stay['check_in']) && ! empty($stay['check_out']) &&
                \Carbon\Carbon::parse($stay['check_out'])->lt(\Carbon\Carbon::parse($stay['check_in']))) {
                return back()->withInput()->withErrors([
                    'hotel_stays' => 'Hotel stay check-out must be the same day or after check-in.',
                ]);
            }

            if (empty($stay['nights']) && ! empty($stay['check_in']) && ! empty($stay['check_out'])) {
                $hotelStays[$index]['nights'] = max(
                    1,
                    \Carbon\Carbon::parse($stay['check_in'])->diffInDays(\Carbon\Carbon::parse($stay['check_out']))
                );
            }
        }

        unset($validated['hotel_stays']);

     $package = Package::create($validated);

$transportRates = [
    [
        'rate_type' => 'passenger',
        'passenger_from' => 1,
        'passenger_to' => 1,
        'price' => $request->input('transport_rates.1.price', 850),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 2,
        'passenger_to' => 2,
        'price' => $request->input('transport_rates.2.price', 800),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 3,
        'passenger_to' => 3,
        'price' => $request->input('transport_rates.3.price', 745),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 4,
        'passenger_to' => 4,
        'price' => $request->input('transport_rates.4.price', 725),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 5,
        'passenger_to' => 49,
        'price' => $request->input('transport_rates.5_49.price', 650),
    ],
    [
        'rate_type' => 'infant',
        'passenger_from' => 0,
        'passenger_to' => 2,
        'price' => $request->input('transport_rates.infant.price', 540),
    ],
];

$package->transportRates()->createMany($transportRates);

if ($hotelStays->isNotEmpty()) {
            $package->hotelStays()->createMany($hotelStays->map(function ($stay, $index) {
                return array_merge($stay, ['sort_order' => $index]);
            })->toArray());
        }

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $tickets = Ticket::with(['departureAirport', 'arrivalAirport'])
            ->where('status', 'Approved')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_date')
            ->get();

        return view('admin.packages.edit', compact('package', 'tickets'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'airline' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'departure_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'visa_processing_price' => 'nullable|numeric|min:0',
            'transport_price' => 'nullable|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'has_visa' => 'boolean',
            'has_hotel' => 'boolean',
            'has_transport' => 'boolean',
            'has_flight' => 'boolean',
            'has_meals' => 'boolean',
            'makkah_hotel' => 'nullable|string|max:255',
            'madinah_hotel' => 'nullable|string|max:255',
            'status' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'show_to_agents' => 'boolean',
            'show_to_customers' => 'boolean',
            'outbound_flight_id' => 'required_with:has_flight|nullable|exists:tickets,id',
            'return_flight_id' => 'nullable|different:outbound_flight_id|exists:tickets,id',
            'hotel_stays' => 'nullable|array',
            'hotel_stays.*.hotel_name' => 'nullable|string|max:255',
            'hotel_stays.*.city' => 'nullable|string|max:255',
            'hotel_stays.*.star_rating' => 'nullable|integer|min:0|max:5',
            'hotel_stays.*.check_in' => 'nullable|date',
            'hotel_stays.*.check_out' => 'nullable|date',
            'hotel_stays.*.nights' => 'nullable|integer|min:1',
            'hotel_stays.*.room_type' => 'nullable|string|max:255',
            'hotel_stays.*.price_per_person' => 'nullable|numeric|min:0',
            'hotel_stays.*.distance_from_haram' => 'nullable|string|max:255',
            'hotel_stays.*.walking_time' => 'nullable|string|max:255',
            'hotel_stays.*.room_sharing_options' => 'nullable',
            'hotel_stays.*.transport_notes' => 'nullable|string',
            'hotel_stays.*.custom_to_haram' => 'nullable|boolean',
            'transport_rates' => 'nullable|array',
'transport_rates.1.price' => 'nullable|numeric|min:0',
'transport_rates.2.price' => 'nullable|numeric|min:0',
'transport_rates.3.price' => 'nullable|numeric|min:0',
'transport_rates.4.price' => 'nullable|numeric|min:0',
'transport_rates.5_49.price' => 'nullable|numeric|min:0',
'transport_rates.infant.price' => 'nullable|numeric|min:0',
            
        ]);

        $validated['has_visa'] = $request->has('has_visa');
        $validated['has_hotel'] = $request->has('has_hotel');
        $validated['has_transport'] = $request->has('has_transport');
        $validated['has_flight'] = $request->has('has_flight');
        $validated['has_meals'] = $request->has('has_meals');
        $validated['show_to_agents'] = $request->has('show_to_agents');
        $validated['show_to_customers'] = $request->has('show_to_customers');

        if (! $request->has('has_flight')) {
            $validated['outbound_flight_id'] = null;
            $validated['return_flight_id'] = null;
        }

        $diff = $validated['total_seats'] - $package->total_seats;
        $validated['available_seats'] = max(
            0,
            $package->available_seats + $diff
        );

        $hotelStays = $this->normalizeHotelStays($request->input('hotel_stays', []));

        foreach ($hotelStays as $index => $stay) {
            if (empty($stay['hotel_name']) && empty($stay['city'])) {
                continue;
            }

            if (! empty($stay['check_in']) && ! empty($stay['check_out']) &&
                \Carbon\Carbon::parse($stay['check_out'])->lt(\Carbon\Carbon::parse($stay['check_in']))) {
                return back()->withInput()->withErrors([
                    'hotel_stays' => 'Hotel stay check-out must be the same day or after check-in.',
                ]);
            }

            if (empty($stay['nights']) && ! empty($stay['check_in']) && ! empty($stay['check_out'])) {
                $hotelStays[$index]['nights'] = max(
                    1,
                    \Carbon\Carbon::parse($stay['check_in'])->diffInDays(\Carbon\Carbon::parse($stay['check_out']))
                );
            }
        }

        unset($validated['hotel_stays']);

       $package->update($validated);

$package->transportRates()->delete();

$transportRates = [
    [
        'rate_type' => 'passenger',
        'passenger_from' => 1,
        'passenger_to' => 1,
        'price' => $request->input('transport_rates.1.price', 850),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 2,
        'passenger_to' => 2,
        'price' => $request->input('transport_rates.2.price', 800),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 3,
        'passenger_to' => 3,
        'price' => $request->input('transport_rates.3.price', 745),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 4,
        'passenger_to' => 4,
        'price' => $request->input('transport_rates.4.price', 725),
    ],
    [
        'rate_type' => 'passenger',
        'passenger_from' => 5,
        'passenger_to' => 49,
        'price' => $request->input('transport_rates.5_49.price', 650),
    ],
    [
        'rate_type' => 'infant',
        'passenger_from' => 0,
        'passenger_to' => 2,
        'price' => $request->input('transport_rates.infant.price', 540),
    ],
];

$package->transportRates()->createMany($transportRates);
$package->hotelStays()->delete();

        if ($hotelStays->isNotEmpty()) {
            $package->hotelStays()->createMany($hotelStays->map(function ($stay, $index) {
                return array_merge($stay, ['sort_order' => $index]);
            })->toArray());
        }

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}