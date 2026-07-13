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
use App\Models\HotelRoom;
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
                'roomTypes' => fn ($query) => $query->where('status', 'Active'),
                'seasonalRates',
                'mealPlans',
                'facilities',
                'inventories',
                'coverImage',
                'images',
            ])
            ->when($city->isNotEmpty(), fn ($query) => $query->where('city', $city))
            ->orderBy('hotel_name')
            ->get();

        $selectedCheckIn = $checkIn ?: Carbon::today();
        $selectedCheckOut = $checkOut ?: Carbon::today()->addDay();

        $hotels = $hotels->map(function ($hotel) use ($selectedCheckIn, $selectedCheckOut) {
            $bestRoomType = null;
            $bestAvailability = [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_percent' => 0,
                'status' => 'Sold Out',
            ];

            foreach ($hotel->roomTypes as $roomType) {
                $availability = HotelRoomInventory::summarizeAvailability(
                    $hotel->id,
                    $roomType->id,
                    $selectedCheckIn,
                    $selectedCheckOut
                );

                if (! $bestRoomType || $availability['available_rooms'] > $bestAvailability['available_rooms']) {
                    $bestRoomType = $roomType;
                    $bestAvailability = $availability;
                }
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
            ->withCount(['hotelRooms as available_room_count' => fn ($query) => $query->where('status', 'Available')])
            ->get();

        $availableRoomTypes = $roomTypes->map(function ($roomType) use ($checkIn, $checkOut) {
            $roomCount = HotelRoomDateStatus::whereHas('hotelRoom', fn ($q) => $q->where('hotel_room_type_id', $roomType->id))
                ->whereBetween('inventory_date', [$checkIn, $checkOut->copy()->subDay()])
                ->where('status', 'Available')
                ->groupBy('hotel_room_id')
                ->havingRaw('COUNT(*) = ?', [$checkIn->diffInDays($checkOut)])
                ->get()
                ->pluck('hotel_room_id')
                ->unique()
                ->count();

            return [
                'id' => $roomType->id,
                'room_name' => $roomType->room_name,
                'capacity' => $roomType->max_occupancy,
                'daily_rate' => $roomType->daily_rate,
                'available_rooms' => $roomCount,
                'extra_bed_price' => $roomType->extra_bed_price,
                'status' => $roomType->status,
            ];
        })->filter(fn ($roomType) => $roomType['available_rooms'] > 0)->values();

        return view('hotels.booking', compact('hotel', 'availableRoomTypes'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['roomTypes', 'seasonalRates', 'mealPlans', 'facilities', 'inventories']);

        $recommendations = Hotel::active()
            ->where('city', $hotel->city)
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

        $dateRange = collect();
        $current = $request->check_in->copy();
        while ($current->lt($request->check_out)) {
            $dateRange->push($current->format('Y-m-d'));
            $current->addDay();
        }

        $inventoryRows = HotelRoomInventory::where('hotel_id', $hotel->id)
            ->where('hotel_room_type_id', $roomType->id)
            ->whereBetween('inventory_date', [$request->check_in, $request->check_out->copy()->subDay()])
            ->get();

        if ($inventoryRows->count() !== $dateRange->count() || $inventoryRows->min('available_rooms') < 1) {
            return back()->withErrors(['hotel_room_type_id' => 'No available room inventory found for the selected dates.']);
        }

        $availableRoom = HotelRoom::where('hotel_room_type_id', $roomType->id)
            ->where('status', 'Available')
            ->whereDoesntHave('dateStatuses', fn ($query) => $query->whereIn('inventory_date', $dateRange)->whereNotIn('status', ['Available']))
            ->inRandomOrder()
            ->first();

        if (! $availableRoom) {
            return back()->withErrors(['hotel_room_type_id' => 'No available room was found for the selected dates.']);
        }

        foreach ($inventoryRows as $inventory) {
            $inventory->available_rooms = max($inventory->available_rooms - 1, 0);
            $inventory->booked_rooms = $inventory->booked_rooms + 1;
            $inventory->status = $inventory->available_rooms > 0 ? 'Available' : 'Sold Out';
            $inventory->save();
        }

        $booking = Booking::create([
            'hotel_id' => $hotel->id,
            'hotel_room_type_id' => $roomType->id,
            'hotel_room_id' => $availableRoom->id,
            'meal_plan_id' => $request->meal_plan_id,
            'reference_number' => strtoupper(Str::random(10)),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults,
            'children' => $request->children,
            'infants' => $request->infants,
            'total_passengers' => $request->adults + $request->children + $request->infants,
            'room_price' => $roomType->daily_rate,
            'meal_price' => $request->include_meal ? HotelMealPlan::find($request->meal_plan_id)?->price_per_person ?? 0 : 0,
            'taxes' => 0,
            'discount' => 0,
            'grand_total' => 0,
            'status' => 'Reserved',
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
        ]);

        foreach ($request->input('passengers', []) as $passenger) {
            $booking->passengers()->create($passenger);
        }

        $taxes = ($booking->room_price + $booking->meal_price) * 0.10;
        $booking->update(['taxes' => $taxes, 'grand_total' => $booking->room_price + $booking->meal_price + $taxes]);

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

        return redirect()->route('hotels.booking.confirmation', ['booking' => $booking->id]);
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
