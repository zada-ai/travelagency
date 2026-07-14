<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelAvailabilityRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelMealPlan;
use App\Models\HotelRoomType;
use App\Models\HotelRoomDateStatus;
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

        $hotels = Hotel::active()
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
            ->orderBy('hotel_name')
            ->get();

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

    public function availability(HotelAvailabilityRequest $request)
    {
        $hotel = Hotel::with(['roomTypes', 'mealPlans', 'facilities'])->findOrFail($request->hotel_id);
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();

        $roomTypes = HotelRoomType::where('hotel_id', $hotel->id)
            ->where('status', 'Active')
            ->with('hotelRooms')
            ->get();

        $availableRoomTypes = $roomTypes->map(function ($roomType) use ($checkIn, $checkOut) {
            return [
                'id' => $roomType->id,
                'room_name' => $roomType->room_name,
                'capacity' => $roomType->max_occupancy,
                'daily_rate' => $roomType->daily_rate,
                'available_rooms' => $roomType->availableRoomsForDates($checkIn, $checkOut),
                'extra_bed_price' => $roomType->extra_bed_price,
                'status' => $roomType->availableRoomsForDates($checkIn, $checkOut) > 0 ? 'Available' : 'Sold Out',
            ];
        })->filter(fn ($roomType) => $roomType['available_rooms'] > 0)->values();

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

        $policyHighlights = [
            [
                'title' => 'Free cancellation',
                'text' => 'Cancel up to 24 hours before arrival without any fees.',
            ],
            [
                'title' => 'Haram shuttle',
                'text' => 'Complimentary shuttle service to the holy mosque every 30 minutes.',
            ],
            [
                'title' => 'Flexible check-in',
                'text' => 'Early arrival subject to availability and priority guest support.',
            ],
            [
                'title' => 'Inclusive breakfast',
                'text' => 'Daily buffet breakfast included for all confirmed room bookings.',
            ],
        ];

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
            'taxes' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'status' => 'Reserved',
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'payment_status' => 'Pending',
            'contacted' => false,
        ]);

        foreach ($request->input('passengers', []) as $passenger) {
            $booking->passengers()->create($passenger);
        }

        $taxes = ($booking->room_price + $booking->meal_price) * 0.10;
        $booking->update(['taxes' => $taxes, 'grand_total' => $booking->room_price + $booking->meal_price + $taxes]);

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
                HotelRoomInventory::updateOrCreate([
                    'hotel_id' => $roomType->hotel_id,
                    'hotel_room_type_id' => $roomType->id,
                    'inventory_date' => $date,
                ], [
                    'hotel_id' => $roomType->hotel_id,
                    'hotel_room_type_id' => $roomType->id,
                    'inventory_date' => $date,
                    'inventory_date_to' => $date,
                    'total_rooms' => $inventory->total_rooms,
                    'available_rooms' => max(0, $inventory->available_rooms - 1),
                    'booked_rooms' => $inventory->booked_rooms + 1,
                    'status' => $inventory->status,
                ]);
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
