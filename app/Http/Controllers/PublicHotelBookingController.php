<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelAvailabilityRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelMealPlan;
use App\Models\HotelRoomInventory;
use App\Models\HotelRoomType;
use App\Models\HotelRoomDateStatus;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PublicHotelBookingController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->string('city')->trim();
        $checkIn = $this->parseBookingDate($request->input('check_in'));
        $checkOut = $this->parseBookingDate($request->input('check_out'));

        if ($checkIn && $checkOut && $checkIn->gte($checkOut)) {
            $checkOut = null;
        }

        $hotels = $this->buildFilteredHotelQuery($request)->get();

        $hotels = $hotels->map(function ($hotel) use ($checkIn, $checkOut) {
            $bestRoomType = null;
            $bestAvailability = [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => $checkIn && $checkOut ? 'Sold Out' : 'Select dates',
            ];

            if ($checkIn && $checkOut) {
                foreach ($hotel->roomTypes as $roomType) {
                    $availability = $roomType->summarizeAvailabilityForDates($checkIn, $checkOut);

                    if (! $bestRoomType || $availability['available_rooms'] > $bestAvailability['available_rooms']) {
                        $bestRoomType = $roomType;
                        $bestAvailability = $availability;
                    }
                }
            }

            if (! $bestRoomType) {
                $bestRoomType = $hotel->roomTypes->sortBy('daily_rate')->first();
            }

            $hotel->bestRoomType = $bestRoomType;
            $hotel->availability = $bestAvailability;

            return $hotel;
        });

        return view('hotels.booking', compact('hotels', 'city', 'checkIn', 'checkOut'));
    }

    public function filter(Request $request)
    {
        $hotels = $this->buildFilteredHotelQuery($request)->get();

        $checkIn = $this->parseBookingDate($request->input('check_in'));
        $checkOut = $this->parseBookingDate($request->input('check_out'));

        $hotels = $this->prepareHotelCollection($hotels, $checkIn, $checkOut);

        return view('hotels.partials.hotel-results', compact('hotels'));
    }

    private function buildFilteredHotelQuery(Request $request)
    {
        $city = $request->string('city')->trim();
        $selectedCategories = $request->input('category', []);

        return Hotel::active()
            ->visibleToPortal($this->currentHotelVisibilityRole())
            ->with([
                'roomTypes.hotelRooms' => fn ($query) => $query->where('status', 'Available'),
                'seasonalRates',
                'mealPlans',
                'facilities',
                'coverImage',
                'images',
            ])
            ->when($city->isNotEmpty(), function ($query) use ($city) {
                $query->whereRaw('LOWER(city) = ?', [mb_strtolower($city->toString())]);
            })
            ->when(! empty($selectedCategories), function ($query) use ($selectedCategories) {
                $query->whereIn('category', $selectedCategories);
            })
            ->when($distance = $request->string('distance')->trim(), function ($query) use ($distance) {
                if ($distance === '0-250') {
                    $query->whereBetween('distance_from_haram', [0, 250]);
                } elseif ($distance === '250-500') {
                    $query->whereBetween('distance_from_haram', [250, 500]);
                } elseif ($distance === '500-1000') {
                    $query->whereBetween('distance_from_haram', [500, 1000]);
                } elseif ($distance === '1000+') {
                    $query->where('distance_from_haram', '>', 1000);
                }
            })
            ->orderBy('hotel_name');
    }

    private function prepareHotelCollection($hotels, $checkIn, $checkOut)
    {
        return $hotels->map(function ($hotel) use ($checkIn, $checkOut) {
            $bestRoomType = null;
            $bestAvailability = [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => $checkIn && $checkOut ? 'Sold Out' : 'Select dates',
            ];

            if ($checkIn && $checkOut) {
                foreach ($hotel->roomTypes as $roomType) {
                    $availability = $roomType->summarizeAvailabilityForDates($checkIn, $checkOut);

                    if (! $bestRoomType || $availability['available_rooms'] > $bestAvailability['available_rooms']) {
                        $bestRoomType = $roomType;
                        $bestAvailability = $availability;
                    }
                }
            }

            if (! $bestRoomType) {
                $bestRoomType = $hotel->roomTypes->sortBy('daily_rate')->first();
            }

            $hotel->bestRoomType = $bestRoomType;
            $hotel->availability = $bestAvailability;

            return $hotel;
        });
    }

    private function parseBookingDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function currentHotelVisibilityRole(): string
    {
        if (auth()->guard('travel_agent')->check()) {
            return 'agent';
        }

        return 'customer';
    }

    public function availability(HotelAvailabilityRequest $request)
    {
        $hotel = Hotel::with(['roomTypes.hotelRooms'])->findOrFail($request->hotel_id);
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();

        $roomTypes = $hotel->roomTypes->where('status', 'Active');

        $availableRoomTypes = $roomTypes->map(function ($roomType) use ($checkIn, $checkOut) {
            $availableRooms = $roomType->availableRoomsForDates($checkIn, $checkOut);

            return [
                'id' => $roomType->id,
                'room_name' => $roomType->room_name,
                'capacity' => $roomType->max_occupancy,
                'daily_rate' => (float) $roomType->daily_rate,
                'available_rooms' => $availableRooms,
                'extra_bed_price' => (float) $roomType->extra_bed_price,
                'status' => $availableRooms > 0 ? 'Available' : 'Sold Out',
            ];
        })->values();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['roomTypes' => $availableRoomTypes]);
        }

        return view('hotels.booking', compact('hotel', 'availableRoomTypes'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['roomTypes', 'seasonalRates', 'mealPlans', 'facilities', 'inventories']);

        $recommendations = Hotel::active()
            ->whereRaw('LOWER(city) = ?', [mb_strtolower($hotel->city)])
            ->where('id', '!=', $hotel->id)
            ->orderByDesc('featured')
            ->orderBy('hotel_name')
            ->take(3)
            ->get();

        $policyHighlights = $hotel->stayPolicyHighlights();

        $reviews = [
            [
                'name' => 'Amina S.',
                'rating' => 5,
                'comment' => 'Perfect location and calm atmosphere. The staff were very helpful during our stay.',
            ],
            [
                'name' => 'Omar H.',
                'rating' => 4,
                'comment' => 'Rooms were spacious and clean. Easy access to Haram and ideal for pilgrim groups.',
            ],
            [
                'name' => 'Fatima R.',
                'rating' => 5,
                'comment' => 'Excellent hospitality, felt like home during our pilgrimage with fast check-in.',
            ],
        ];

        return view('hotels.details', compact('hotel', 'recommendations', 'policyHighlights', 'reviews'));
    }

    public function store(StoreBookingRequest $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);
        $roomType = HotelRoomType::where('id', $request->hotel_room_type_id)->where('hotel_id', $hotel->id)->firstOrFail();

        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();

        $dateRange = collect();
        $current = $checkIn->copy();
        while ($current->lt($checkOut)) {
            $dateRange->push($current->format('Y-m-d'));
            $current->addDay();
        }

        $availableRooms = $roomType->availableRoomsForDates($checkIn, $checkOut);
        if ($availableRooms < 1) {
            return back()->withErrors(['hotel_room_type_id' => 'No available room was found for the selected dates.']);
        }

        $availableRoom = $roomType->hasHotelRooms() ? $roomType->findAvailableRoomForDates($checkIn, $checkOut) : null;

        if ($roomType->hasHotelRooms() && ! $availableRoom) {
            return back()->withErrors(['hotel_room_type_id' => 'No available room was found for the selected dates.']);
        }

        $mealPlan = $request->include_meal ? HotelMealPlan::find($request->meal_plan_id) : null;
        $totalGuests = $request->adults + $request->children + $request->infants;
        $mealPricePerPerson = $mealPlan?->price_per_person ?? 0;
        $mealPriceTotal = $request->include_meal ? $mealPricePerPerson * $totalGuests : 0;

        $transportPrice = 0;
        $visaPrice = 0;
        $activeVisaType = VisaType::active()->latest('id')->first();

        if ($request->include_transport) {
            $transportPrice = ($request->adults * 520) + ($request->children * 600) + ($request->infants * 520);
        }

        if ($request->include_visa) {
            $visaPrice = $activeVisaType?->total_cost ?? 1400;
        }

        $booking = Booking::create([
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'hotel_room_id' => $availableRoom?->id,
            'meal_plan_id' => $request->meal_plan_id,
            'reference_number' => strtoupper(Str::random(10)),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => $request->adults,
            'children' => $request->children,
            'infants' => $request->infants,
            'total_passengers' => $totalGuests,
            'room_price' => $roomType->daily_rate,
            'meal_price' => $mealPriceTotal,
            'visa_price' => $visaPrice,
            'transport_price' => $transportPrice,
            'include_visa' => $request->include_visa,
            'include_transport' => $request->include_transport,
            'taxes' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'status' => 'Pending',
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'payment_status' => 'Pending',
            'contacted' => false,
        ]);

        foreach ($request->input('passengers', []) as $passenger) {
            $booking->passengers()->create($passenger);
        }

        $taxes = ($booking->room_price + $booking->meal_price + $booking->visa_price + $booking->transport_price) * 0.10;
        $booking->update([
            'taxes' => $taxes,
            'grand_total' => $booking->room_price + $booking->meal_price + $booking->visa_price + $booking->transport_price + $taxes,
        ]);

        if ($availableRoom) {
            foreach ($dateRange as $date) {
                HotelRoomDateStatus::updateOrCreate([
                    'hotel_room_id' => $availableRoom->id,
                    'inventory_date' => $date,
                ], [
                    'booking_id' => $booking->id,
                    'status' => 'Reserved',
                ]);
            }

            $availableRoom->update(['status' => 'Reserved']);
        } elseif (! $roomType->hasHotelRooms()) {
            $this->reserveInventoryDates($roomType, $checkIn, $checkOut);
        }

        if ($request->expectsJson()) {
            $booking->load(['hotel', 'roomType', 'mealPlan']);

            return response()->json([
                'success' => true,
                'booking' => [
                    'id' => $booking->id,
                    'reference_number' => $booking->reference_number,
                    'hotel_name' => $booking->hotel->hotel_name,
                    'check_in' => $booking->check_in->format('d M Y'),
                    'check_out' => $booking->check_out->format('d M Y'),
                    'total_passengers' => $booking->total_passengers,
                    'grand_total' => number_format($booking->grand_total, 2),
                    'booking_details_url' => route('hotels.booking.confirmation', ['booking' => $booking->id]),
                ],
            ]);
        }

        return redirect()->route('hotels.booking.confirmation', ['booking' => $booking->id]);
    }

    public function review(StoreBookingRequest $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);
        $roomType = HotelRoomType::findOrFail($request->hotel_room_type_id);
        $mealPlan = $request->include_meal ? HotelMealPlan::find($request->meal_plan_id) : null;
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();
        $nights = max($checkIn->diffInDays($checkOut), 1);
        $totalGuests = $request->adults + $request->children + $request->infants;
        $roomCharge = $roomType->daily_rate * $nights;
        $mealCharge = $request->include_meal ? ($mealPlan?->price_per_person ?? 0) * $totalGuests : 0;

        $transportPrice = 0;
        if ($request->include_transport) {
            $transportPrice = ($request->adults * 520) + ($request->children * 600) + ($request->infants * 520);
        }

        $activeVisaType = VisaType::active()->latest('id')->first();
        $visaPrice = $request->include_visa ? ($activeVisaType?->total_cost ?? 1400) : 0;
        $taxes = ($roomCharge + $mealCharge + $visaPrice + $transportPrice) * 0.10;
        $grandTotal = $roomCharge + $mealCharge + $visaPrice + $transportPrice + $taxes;
        $totalInPKR = $grandTotal * 83;

        return view('hotels.booking-review', compact(
            'hotel',
            'roomType',
            'mealPlan',
            'request',
            'checkIn',
            'checkOut',
            'nights',
            'totalGuests',
            'roomCharge',
            'mealCharge',
            'transportPrice',
            'visaPrice',
            'taxes',
            'grandTotal',
            'totalInPKR'
        ));
    }

    public function reviewEdit(StoreBookingRequest $request)
    {
        return redirect()->route('hotels.booking.create', ['hotel' => $request->hotel_id])->withInput($request->all());
    }

    public function create(Hotel $hotel, Request $request)
    {
        $hotel->load(['roomTypes', 'mealPlans', 'facilities', 'images']);

        $checkIn = $request->old('check_in') ? Carbon::parse($request->old('check_in'))->startOfDay() : null;
        $checkOut = $request->old('check_out') ? Carbon::parse($request->old('check_out'))->startOfDay() : null;

        if ($checkIn && $checkOut && $checkOut->lt($checkIn)) {
            $checkOut = null;
        }

        $roomTypeDateRanges = $this->buildRoomTypeDateRanges($hotel);
        $roomTypeAvailabilities = [];

        if ($checkIn && $checkOut) {
            foreach ($hotel->roomTypes->where('status', 'Active') as $roomType) {
                $roomTypeAvailabilities[$roomType->id] = $roomType->summarizeAvailabilityForDates($checkIn, $checkOut);
            }
        }

        $visaType = VisaType::active()->latest('id')->first();

        return view('hotels.booking-form', compact('hotel', 'checkIn', 'checkOut', 'roomTypeAvailabilities', 'roomTypeDateRanges', 'visaType'));
    }

    private function buildRoomTypeDateRanges(Hotel $hotel): array
    {
        $roomTypeIds = $hotel->roomTypes
            ->where('status', 'Active')
            ->pluck('id')
            ->all();

        if (empty($roomTypeIds)) {
            return [];
        }

        $inventoryGroups = HotelRoomInventory::where('hotel_id', $hotel->id)
            ->whereIn('hotel_room_type_id', $roomTypeIds)
            ->where('status', 'Active')
            ->get()
            ->groupBy('hotel_room_type_id');

        return $inventoryGroups->mapWithKeys(function ($items, $roomTypeId) {
            $minDate = $items->min('inventory_date');
            $maxDate = $items
                ->map(fn ($item) => $item->inventory_date_to ? $item->inventory_date_to->format('Y-m-d') : $item->inventory_date->format('Y-m-d'))
                ->max();

            if (! $minDate || ! $maxDate) {
                return [];
            }

            return [
                $roomTypeId => [
                    'min_date' => $minDate->format('Y-m-d'),
                    'max_date' => $maxDate,
                ],
            ];
        })->toArray();
    }

    private function reserveInventoryDates(HotelRoomType $roomType, Carbon $checkIn, Carbon $checkOut): void
    {
        $current = $checkIn->copy();

        while ($current->lt($checkOut)) {
            $date = $current->format('Y-m-d');

            $inventory = HotelRoomInventory::where('hotel_id', $roomType->hotel_id)
                ->where('hotel_room_type_id', $roomType->id)
                ->whereDate('inventory_date', $date)
                ->first();

            if (! $inventory) {
                $inventory = HotelRoomInventory::where('hotel_id', $roomType->hotel_id)
                    ->where('hotel_room_type_id', $roomType->id)
                    ->whereDate('inventory_date', '<=', $date)
                    ->whereNotNull('inventory_date_to')
                    ->whereDate('inventory_date_to', '>=', $date)
                    ->orderByDesc('inventory_date_to')
                    ->first();
            }

            if ($inventory) {
                $inventoryEntry = HotelRoomInventory::withTrashed()->firstOrNew([
                    'hotel_id' => $roomType->hotel_id,
                    'hotel_room_type_id' => $roomType->id,
                    'inventory_date' => $date,
                ]);

                $inventoryEntry->inventory_date_to = $date;
                $inventoryEntry->total_rooms = $inventory->total_rooms;
                $inventoryEntry->available_rooms = max(0, $inventory->available_rooms - 1);
                $inventoryEntry->booked_rooms = $inventory->booked_rooms + 1;
                $inventoryEntry->status = $inventory->status;
                $inventoryEntry->deleted_at = null;
                $inventoryEntry->save();
            }

            $current->addDay();
        }
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'Cancelled') {
            return redirect()->route('hotels.booking.confirmation', ['booking' => $booking->id])->with('info', 'This booking has already been cancelled.');
        }

        $booking->cancel();

        return redirect()->route('hotels.booking.confirmation', ['booking' => $booking->id])->with('success', 'Booking cancelled and inventory restored successfully.');
    }
}
