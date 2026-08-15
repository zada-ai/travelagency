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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicHotelBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hotel Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $city = $request->string('city')->trim();

        $checkIn = $this->parseBookingDate(
            $request->input('check_in')
        );

        $checkOut = $this->parseBookingDate(
            $request->input('check_out')
        );

        if ($checkIn && $checkOut && $checkIn->gte($checkOut)) {
            $checkOut = null;
        }

        $hotels = $this->buildFilteredHotelQuery($request)->get();

        $hotels = $this->prepareHotelCollection(
            $hotels,
            $checkIn,
            $checkOut
        );

        return view(
            'hotels.booking',
            compact(
                'hotels',
                'city',
                'checkIn',
                'checkOut'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Filter
    |--------------------------------------------------------------------------
    */

    public function filter(Request $request)
    {
        $hotels = $this->buildFilteredHotelQuery($request)->get();

        $checkIn = $this->parseBookingDate(
            $request->input('check_in')
        );

        $checkOut = $this->parseBookingDate(
            $request->input('check_out')
        );

        $hotels = $this->prepareHotelCollection(
            $hotels,
            $checkIn,
            $checkOut
        );

        return view(
            'hotels.partials.hotel-results',
            compact('hotels')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hotel Query
    |--------------------------------------------------------------------------
    */

    private function buildFilteredHotelQuery(Request $request)
    {
        $city = $request->string('city')->trim();

        $selectedCategories = $request->input(
            'category',
            []
        );

        return Hotel::active()
            ->visibleToPortal(
                $this->currentHotelVisibilityRole()
            )
            ->with([
                'roomTypes.hotelRooms' => function ($query) {
                    $query->where('status', 'Available');
                },
                'seasonalRates',
                'mealPlans',
                'facilities',
                'coverImage',
                'images',
            ])
            ->when(
                $city->isNotEmpty(),
                function ($query) use ($city) {
                    $query->whereRaw(
                        'LOWER(city) = ?',
                        [
                            mb_strtolower(
                                $city->toString()
                            )
                        ]
                    );
                }
            )
            ->when(
                !empty($selectedCategories),
                function ($query) use ($selectedCategories) {
                    $query->whereIn(
                        'category',
                        $selectedCategories
                    );
                }
            )
            ->when(
                $request->string('distance')->trim(),
                function ($query) use ($request) {

                    $distance = $request
                        ->string('distance')
                        ->trim()
                        ->toString();

                    if ($distance === '0-250') {
                        $query->whereBetween(
                            'distance_from_haram',
                            [0, 250]
                        );
                    } elseif ($distance === '250-500') {
                        $query->whereBetween(
                            'distance_from_haram',
                            [250, 500]
                        );
                    } elseif ($distance === '500-1000') {
                        $query->whereBetween(
                            'distance_from_haram',
                            [500, 1000]
                        );
                    } elseif ($distance === '1000+') {
                        $query->where(
                            'distance_from_haram',
                            '>',
                            1000
                        );
                    }
                }
            )
            ->orderBy('hotel_name');
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare Hotels
    |--------------------------------------------------------------------------
    */

    private function prepareHotelCollection(
        $hotels,
        $checkIn,
        $checkOut
    ) {
        return $hotels->map(
            function ($hotel) use ($checkIn, $checkOut) {

                $bestRoomType = null;

                $bestAvailability = [
                    'total_rooms' => 0,
                    'available_rooms' => 0,
                    'booked_rooms' => 0,
                    'occupancy_percent' => 0,
                    'status' => (
                        $checkIn && $checkOut
                    )
                        ? 'Sold Out'
                        : 'Select dates',
                ];

                if ($checkIn && $checkOut) {

                    foreach (
                        $hotel->roomTypes as $roomType
                    ) {

                        $availability =
                            $roomType
                                ->summarizeAvailabilityForDates(
                                    $checkIn,
                                    $checkOut
                                );

                        if (
                            !$bestRoomType ||
                            $availability['available_rooms']
                            >
                            $bestAvailability['available_rooms']
                        ) {
                            $bestRoomType = $roomType;

                            $bestAvailability =
                                $availability;
                        }
                    }
                }

                if (!$bestRoomType) {

                    $bestRoomType =
                        $hotel->roomTypes
                            ->sortBy('daily_rate')
                            ->first();
                }

                $hotel->bestRoomType =
                    $bestRoomType;

                $hotel->availability =
                    $bestAvailability;

                return $hotel;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date Parser
    |--------------------------------------------------------------------------
    */

    private function parseBookingDate(
        ?string $value
    ): ?Carbon {

        if (!$value) {
            return null;
        }

        try {

            return Carbon::parse($value)
                ->startOfDay();

        } catch (\Throwable $e) {

            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Visibility Role
    |--------------------------------------------------------------------------
    */

    private function currentHotelVisibilityRole(): string
    {
        if (
            auth()
                ->guard('travel_agent')
                ->check()
        ) {
            return 'agent';
        }

        return 'customer';
    }

    /*
    |--------------------------------------------------------------------------
    | Availability
    |--------------------------------------------------------------------------
    */

    public function availability(
        HotelAvailabilityRequest $request
    ) {
        $hotel = Hotel::with([
            'roomTypes.hotelRooms'
        ])->findOrFail(
            $request->hotel_id
        );

        $checkIn = Carbon::parse(
            $request->check_in
        )->startOfDay();

        $checkOut = Carbon::parse(
            $request->check_out
        )->startOfDay();

        $roomTypes = $hotel->roomTypes
            ->where('status', 'Active');

        $availableRoomTypes = $roomTypes
            ->map(
                function ($roomType) use (
                    $checkIn,
                    $checkOut
                ) {

                    $availableRooms =
                        $roomType
                            ->availableRoomsForDates(
                                $checkIn,
                                $checkOut
                            );

                    return [
                        'id' => $roomType->id,

                        'room_name' =>
                            $roomType->room_name,

                        'capacity' =>
                            $roomType->max_occupancy,

                        'daily_rate' =>
                            (float)
                            $roomType->daily_rate,

                        'available_rooms' =>
                            $availableRooms,

                        'extra_bed_price' =>
                            (float)
                            $roomType->extra_bed_price,

                        'status' =>
                            $availableRooms > 0
                                ? 'Available'
                                : 'Sold Out',
                    ];
                }
            )
            ->values();

        if (
            $request->expectsJson() ||
            $request->ajax()
        ) {

            return response()->json([
                'roomTypes' =>
                    $availableRoomTypes
            ]);
        }

        return view(
            'hotels.booking',
            compact(
                'hotel',
                'availableRoomTypes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hotel Details
    |--------------------------------------------------------------------------
    */

    public function show(Hotel $hotel)
    {
        $hotel->load([
            'roomTypes',
            'seasonalRates',
            'mealPlans',
            'facilities',
            'inventories'
        ]);

        $recommendations = Hotel::active()
            ->whereRaw(
                'LOWER(city) = ?',
                [
                    mb_strtolower(
                        $hotel->city
                    )
                ]
            )
            ->where(
                'id',
                '!=',
                $hotel->id
            )
            ->orderByDesc('featured')
            ->orderBy('hotel_name')
            ->take(3)
            ->get();

        $policyHighlights =
            $hotel->stayPolicyHighlights();

        $reviews = [
            [
                'name' => 'Amina S.',
                'rating' => 5,
                'comment' =>
                    'Perfect location and calm atmosphere. The staff were very helpful during our stay.',
            ],
            [
                'name' => 'Omar H.',
                'rating' => 4,
                'comment' =>
                    'Rooms were spacious and clean. Easy access to Haram and ideal for pilgrim groups.',
            ],
            [
                'name' => 'Fatima R.',
                'rating' => 5,
                'comment' =>
                    'Excellent hospitality, felt like home during our pilgrimage with fast check-in.',
            ],
        ];

        return view(
            'hotels.details',
            compact(
                'hotel',
                'recommendations',
                'policyHighlights',
                'reviews'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Final Booking
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreBookingRequest $request
    ) {
        if (function_exists('set_time_limit')) {
            @set_time_limit(60);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Review Session
        |--------------------------------------------------------------------------
        */

        $sessionReview = session(
            'booking_review_data',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | Hotel
        |--------------------------------------------------------------------------
        */

        $hotel = Hotel::findOrFail(
            $request->hotel_id
        );

        $roomType = HotelRoomType::where(
            'id',
            $request->hotel_room_type_id
        )
            ->where(
                'hotel_id',
                $hotel->id
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        if (
            !empty($sessionReview['checkIn']) &&
            !empty($sessionReview['checkOut'])
        ) {

            $checkIn = Carbon::parse(
                $sessionReview['checkIn']
            )->startOfDay();

            $checkOut = Carbon::parse(
                $sessionReview['checkOut']
            )->startOfDay();

        } else {

            $checkIn = Carbon::parse(
                $request->check_in
            )->startOfDay();

            $checkOut = Carbon::parse(
                $request->check_out
            )->startOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Dates
        |--------------------------------------------------------------------------
        */

        if ($checkIn->gte($checkOut)) {

            return back()
                ->withErrors([
                    'check_out' =>
                        'Check-out date must be after check-in date.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $dateRange = collect();

        $current = $checkIn->copy();

        while ($current->lt($checkOut)) {

            $dateRange->push(
                $current->format('Y-m-d')
            );

            $current->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Availability
        |--------------------------------------------------------------------------
        */

        $availableRooms =
            $roomType->availableRoomsForDates(
                $checkIn,
                $checkOut
            );

        if ($availableRooms < 1) {

            return back()
                ->withErrors([
                    'hotel_room_type_id' =>
                        'No available room was found for the selected dates.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Physical Room
        |--------------------------------------------------------------------------
        */

        $availableRoom =
            $roomType->hasHotelRooms()
                ? $roomType->findAvailableRoomForDates(
                    $checkIn,
                    $checkOut
                )
                : null;

        if (
            $roomType->hasHotelRooms() &&
            !$availableRoom
        ) {

            return back()
                ->withErrors([
                    'hotel_room_type_id' =>
                        'No available room was found for the selected dates.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Meal Plan
        |--------------------------------------------------------------------------
        */

        $mealPlanId =
            $request->meal_plan_id
            ??
            ($sessionReview['meal_plan_id'] ?? null);

        $includeMeal =
            $request->boolean('include_meal')
            ||
            !empty($mealPlanId);

        $mealPlan =
            $includeMeal && $mealPlanId
                ? HotelMealPlan::find(
                    $mealPlanId
                )
                : null;

        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        $adults =
            (int) $request->adults;

        $children =
            (int) $request->children;

        $infants =
            (int) $request->infants;

        $totalGuests =
            $adults +
            $children +
            $infants;

        /*
        |--------------------------------------------------------------------------
        | Nights
        |--------------------------------------------------------------------------
        */

        $nights =
            max(
                $checkIn->diffInDays(
                    $checkOut
                ),
                1
            );

        /*
        |--------------------------------------------------------------------------
        | Meal Price
        |--------------------------------------------------------------------------
        */

        $mealPricePerPerson =
            $mealPlan?->price_per_person ?? 0;

        $mealPriceTotal =
            $includeMeal
                ? $mealPricePerPerson
                    *
                    $totalGuests
                    *
                    $nights
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Transport
        |--------------------------------------------------------------------------
        */

        $transportPrice = 0;

        if (
            $request->boolean(
                'include_transport'
            )
        ) {

            $transportPrice =
                ($adults * 520)
                +
                ($children * 600)
                +
                ($infants * 520);
        }

        /*
        |--------------------------------------------------------------------------
        | Visa
        |--------------------------------------------------------------------------
        */

        $activeVisaType =
            VisaType::active()
                ->latest('id')
                ->first();

        $visaPrice = 0;

        if (
            $request->boolean(
                'include_visa'
            )
        ) {

            $visaPrice =
                $activeVisaType?->total_cost
                ?? 1400;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Booking
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(function () use (
            $request,
            $hotel,
            $roomType,
            $checkIn,
            $checkOut,
            $dateRange,
            $availableRoom,
            $mealPriceTotal,
            $visaPrice,
            $transportPrice,
            $adults,
            $children,
            $infants,
            $totalGuests,
            $nights,
            $sessionReview
        ) {

            /*
            |--------------------------------------------------------------------------
            | Re-check availability inside transaction
            |--------------------------------------------------------------------------
            */

            $availableRooms =
                $roomType->availableRoomsForDates(
                    $checkIn,
                    $checkOut
                );

            if ($availableRooms < 1) {
                throw new \RuntimeException(
                    'The selected room is no longer available for these dates.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = Booking::create([

                'hotel_id' =>
                    $hotel->id,

                'hotel_room_type_id' =>
                    $roomType->id,

                'hotel_room_id' =>
                    $availableRoom?->id,

                'reference_number' =>
                    strtoupper(
                        Str::random(10)
                    ),

                'check_in' =>
                    $checkIn,

                'check_out' =>
                    $checkOut,

                'adults' =>
                    $adults,

                'children' =>
                    $children,

                'infants' =>
                    $infants,

                'total_passengers' =>
                    $totalGuests,

                'room_price' =>
                    $roomType->daily_rate,

                'meal_plan_id' =>
                    $mealPlan?->id,

                'meal_price' =>
                    $mealPriceTotal,

                'visa_price' =>
                    $visaPrice,

                'transport_price' =>
                    $transportPrice,

                'include_visa' =>
                    $request->boolean(
                        'include_visa'
                    ),

                'include_transport' =>
                    $request->boolean(
                        'include_transport'
                    ),

                'taxes' => 0,

                'discount' => 0,

                'grand_total' => 0,

                'status' => 'Pending',

                'contact_name' =>
                    $request->contact_name,

                'contact_email' =>
                    $request->contact_email,

                'contact_phone' =>
                    $request->contact_phone,

                'payment_status' =>
                    'Pending',

                'contacted' =>
                    false,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Stored Passenger Files
            |--------------------------------------------------------------------------
            */

            $storedPaths =
                $sessionReview[
                    'passengerStoredPaths'
                ] ?? [];

            /*
            |--------------------------------------------------------------------------
            | Create Passengers
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->input(
                    'passengers',
                    []
                ) as $index => $passenger
            ) {

                $passportPath =
                    $storedPaths[$index][
                        'passport_document_path'
                    ] ?? null;

                $cnicPath =
                    $storedPaths[$index][
                        'cnic_document_path'
                    ] ?? null;

                /*
                |--------------------------------------------------------------------------
                | Passenger
                |--------------------------------------------------------------------------
                */

                $bookingPassenger =
                    $booking->passengers()->create([
                        'booking_id' =>
                            $booking->id,

                        'passenger_type' =>
                            $passenger[
                                'passenger_type'
                            ] ?? null,

                        'full_name' =>
                            $passenger[
                                'full_name'
                            ] ?? null,

                        'date_of_birth' =>
                            $passenger[
                                'date_of_birth'
                            ] ?? null,

                        'passport_document_path' =>
                            $passportPath,

                        'cnic_document_path' =>
                            $cnicPath,
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Passenger Age
                |--------------------------------------------------------------------------
                */

                $age = null;

                if (
                    !empty(
                        $passenger['date_of_birth']
                    )
                ) {

                    try {

                        $age = Carbon::parse(
                            $passenger['date_of_birth']
                        )->age;

                    } catch (\Throwable $e) {

                        Log::warning(
                            'Unable to calculate passenger age',
                            [
                                'booking_id' =>
                                    $booking->id,

                                'passenger_index' =>
                                    $index,

                                'date_of_birth' =>
                                    $passenger[
                                        'date_of_birth'
                                    ],

                                'error' =>
                                    $e->getMessage(),
                            ]
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Passenger Details
                |--------------------------------------------------------------------------
                */

                $bookingPassenger
                    ->details()
                    ->create([
                        'age' => $age,
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Total
            |--------------------------------------------------------------------------
            */

            $roomTotal =
                $booking->room_price * $nights;

            $taxableAmount =
                $roomTotal
                +
                $booking->meal_price
                +
                $booking->visa_price
                +
                $booking->transport_price;

            $taxes =
                $taxableAmount * 0.10;

            $grandTotal =
                $taxableAmount + $taxes;

            $booking->update([
                'taxes' =>
                    $taxes,

                'grand_total' =>
                    $grandTotal,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Reserve Physical Room
            |--------------------------------------------------------------------------
            */

            if ($availableRoom) {

                foreach (
                    $dateRange as $date
                ) {

                    HotelRoomDateStatus::updateOrCreate(
                        [
                            'hotel_room_id' =>
                                $availableRoom->id,

                            'inventory_date' =>
                                $date,
                        ],
                        [
                            'booking_id' =>
                                $booking->id,

                            'status' =>
                                'Reserved',
                        ]
                    );
                }

                $availableRoom->update([
                    'status' =>
                        'Reserved'
                ]);

            } elseif (
                !$roomType->hasHotelRooms()
            ) {

                $this->reserveInventoryDates(
                    $roomType,
                    $checkIn,
                    $checkOut
                );
            }

            return $booking;
        });

        /*
        |--------------------------------------------------------------------------
        | Clear Review Session
        |--------------------------------------------------------------------------
        */

        session()->forget(
            'booking_review_data'
        );

        /*
        |--------------------------------------------------------------------------
        | JSON Response
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {

            $booking->load([
                'hotel',
                'roomType',
                'mealPlan',
            ]);

            return response()->json([

                'success' => true,

                'booking' => [

                    'id' =>
                        $booking->id,

                    'reference_number' =>
                        $booking->reference_number,

                    'hotel_name' =>
                        $booking
                            ->hotel
                            ->hotel_name,

                    'check_in' =>
                        $booking
                            ->check_in
                            ->format('d M Y'),

                    'check_out' =>
                        $booking
                            ->check_out
                            ->format('d M Y'),

                    'total_passengers' =>
                        $booking
                            ->total_passengers,

                    'grand_total' =>
                        number_format(
                            $booking
                                ->grand_total,
                            2
                        ),

                    'booking_details_url' =>
                        route(
                            'hotels.booking.confirmation',
                            [
                                'booking' =>
                                    $booking->id
                            ]
                        ),
                ],
            ]);
        }

        return redirect()->route(
            'hotels.booking.confirmation',
            [
                'booking' =>
                    $booking->id
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review Show
    |--------------------------------------------------------------------------
    */

    public function reviewShow(
        Request $request
    ) {

        $sessionData =
            session(
                'booking_review_data'
            );

        if (!$sessionData) {

            return redirect()
                ->route(
                    'hotels.booking'
                )
                ->with(
                    'error',
                    'Session expired. Please start booking again.'
                );
        }

        $hotel =
            Hotel::findOrFail(
                $sessionData['hotel_id']
            );

        $roomType =
            HotelRoomType::findOrFail(
                $sessionData[
                    'hotel_room_type_id'
                ]
            );

        $mealPlan =
            !empty(
                $sessionData['meal_plan_id']
            )
                ? HotelMealPlan::find(
                    $sessionData[
                        'meal_plan_id'
                    ]
                )
                : null;

        $checkIn =
            Carbon::parse(
                $sessionData['checkIn']
            )->startOfDay();

        $checkOut =
            Carbon::parse(
                $sessionData['checkOut']
            )->startOfDay();

        $nights =
            $sessionData['nights'];

        $totalGuests =
            $sessionData['totalGuests'];

        $roomCharge =
            $sessionData['roomCharge'];

        $mealCharge =
            $sessionData['mealCharge'];

        $transportPrice =
            $sessionData['transportPrice'];

        $visaPrice =
            $sessionData['visaPrice'];

        $taxes =
            $sessionData['taxes'];

        $grandTotal =
            $sessionData['grandTotal'];

        $totalInPKR =
            $sessionData['totalInPKR'];

        $passengerFiles =
            $sessionData['passengerFiles']
            ?? [];

        $passengerStoredPaths =
            $sessionData[
                'passengerStoredPaths'
            ]
            ?? [];

        $passengers =
            $sessionData['passengers']
            ?? [];

        $contactName =
            $sessionData[
                'contact_name'
            ]
            ?? null;

        $contactEmail =
            $sessionData[
                'contact_email'
            ]
            ?? null;

        $contactPhone =
            $sessionData[
                'contact_phone'
            ]
            ?? null;

        $mockRequest =
            Request::create(
                '/',
                'GET',
                $sessionData
            );

        return view(
            'hotels.booking-review',
            compact(
                'hotel',
                'roomType',
                'mealPlan',
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
                'totalInPKR',
                'passengerFiles',
                'passengerStoredPaths',
                'passengers',
                'contactName',
                'contactEmail',
                'contactPhone'
            )
        )->with(
            'request',
            $mockRequest
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Review Booking
    |--------------------------------------------------------------------------
    */

    public function review(
        StoreBookingRequest $request
    ) {

        if (function_exists('set_time_limit')) {
            @set_time_limit(60);
        }

        $hotel =
            Hotel::findOrFail(
                $request->hotel_id
            );

        $roomType =
            HotelRoomType::where(
                'id',
                $request->hotel_room_type_id
            )
            ->where(
                'hotel_id',
                $hotel->id
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Meal Plan
        |--------------------------------------------------------------------------
        */

        $mealPlan =
            $request->boolean(
                'include_meal'
            )
                ? HotelMealPlan::find(
                    $request->meal_plan_id
                )
                : null;

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $checkIn =
            Carbon::parse(
                $request->check_in
            )->startOfDay();

        $checkOut =
            Carbon::parse(
                $request->check_out
            )->startOfDay();

        if ($checkIn->gte($checkOut)) {

            return back()
                ->withErrors([
                    'check_out' =>
                        'Check-out date must be after check-in date.'
                ])
                ->withInput();
        }

        $nights =
            max(
                $checkIn->diffInDays(
                    $checkOut
                ),
                1
            );

        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        $adults =
            (int) $request->adults;

        $children =
            (int) $request->children;

        $infants =
            (int) $request->infants;

        $totalGuests =
            $adults +
            $children +
            $infants;

        /*
        |--------------------------------------------------------------------------
        | Room Charge
        |--------------------------------------------------------------------------
        */

        $roomCharge =
            $roomType->daily_rate
            *
            $nights;

        /*
        |--------------------------------------------------------------------------
        | Meal Charge
        |--------------------------------------------------------------------------
        */

        $mealCharge =
            $request->boolean(
                'include_meal'
            )
                ? (
                    ($mealPlan?->price_per_person ?? 0)
                    *
                    $totalGuests
                    *
                    $nights
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Transport
        |--------------------------------------------------------------------------
        */

        $transportPrice = 0;

        if (
            $request->boolean(
                'include_transport'
            )
        ) {

            $transportPrice =
                ($adults * 520)
                +
                ($children * 600)
                +
                ($infants * 520);
        }

        /*
        |--------------------------------------------------------------------------
        | Visa
        |--------------------------------------------------------------------------
        */

        $activeVisaType =
            VisaType::active()
                ->latest('id')
                ->first();

        $visaPrice =
            $request->boolean(
                'include_visa'
            )
                ? (
                    $activeVisaType?->total_cost
                    ?? 1400
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Tax
        |--------------------------------------------------------------------------
        */

        $taxableAmount =
            $roomCharge
            +
            $mealCharge
            +
            $visaPrice
            +
            $transportPrice;

        $taxes =
            $taxableAmount * 0.10;

        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $taxableAmount + $taxes;

        /*
        |--------------------------------------------------------------------------
        | PKR Conversion
        |--------------------------------------------------------------------------
        */

        $totalInPKR =
            $grandTotal * 83;

        /*
        |--------------------------------------------------------------------------
        | Passenger Files
        |--------------------------------------------------------------------------
        */

        $passengerFiles = [];

        $passengerStoredPaths = [];

        $passengers =
            $request->input(
                'passengers',
                []
            );

        foreach (
            $passengers as $index => $passenger
        ) {

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    "passengers.{$index}.passport_document"
                )
            ) {

                try {

                    $passportFile =
                        $request->file(
                            "passengers.{$index}.passport_document"
                        );

                    $extension =
                        strtolower(
                            $passportFile
                                ->getClientOriginalExtension()
                        );

                    $filename =
                        Str::uuid()
                        .
                        (
                            $extension
                                ? ".{$extension}"
                                : ''
                        );

                    $stored =
                        $passportFile->storeAs(
                            'passport-documents',
                            $filename,
                            'public'
                        );

                    if (!$stored) {
                        throw new \RuntimeException(
                            'Passport file could not be stored.'
                        );
                    }

                    $passengerFiles[$index][
                        'passport_document'
                    ] = [

                        'name' =>
                            $passportFile
                                ->getClientOriginalName(),

                        'size' =>
                            $passportFile
                                ->getSize(),

                        'path' =>
                            $stored,
                    ];

                    $passengerStoredPaths[$index][
                        'passport_document_path'
                    ] = $stored;

                } catch (\Throwable $e) {

                    Log::error(
                        'Failed to store passport document',
                        [
                            'index' =>
                                $index,

                            'error' =>
                                $e->getMessage(),
                        ]
                    );

                    return back()
                        ->withErrors([
                            'passengers' =>
                                'Unable to upload passport document. Please try again.',
                        ])
                        ->withInput();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CNIC
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile(
                    "passengers.{$index}.cnic_document"
                )
            ) {

                try {

                    $cnicFile =
                        $request->file(
                            "passengers.{$index}.cnic_document"
                        );

                    $extension =
                        strtolower(
                            $cnicFile
                                ->getClientOriginalExtension()
                        );

                    $filename =
                        Str::uuid()
                        .
                        (
                            $extension
                                ? ".{$extension}"
                                : ''
                        );

                    $stored =
                        $cnicFile->storeAs(
                            'cnic-documents',
                            $filename,
                            'public'
                        );

                    if (!$stored) {
                        throw new \RuntimeException(
                            'CNIC file could not be stored.'
                        );
                    }

                    $passengerFiles[$index][
                        'cnic_document'
                    ] = [

                        'name' =>
                            $cnicFile
                                ->getClientOriginalName(),

                        'size' =>
                            $cnicFile
                                ->getSize(),

                        'path' =>
                            $stored,
                    ];

                    $passengerStoredPaths[$index][
                        'cnic_document_path'
                    ] = $stored;

                } catch (\Throwable $e) {

                    Log::error(
                        'Failed to store CNIC document',
                        [
                            'index' =>
                                $index,

                            'error' =>
                                $e->getMessage(),
                        ]
                    );

                    return back()
                        ->withErrors([
                            'passengers' =>
                                'Unable to upload CNIC document. Please try again.',
                        ])
                        ->withInput();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save Review Session
        |--------------------------------------------------------------------------
        */

        Log::info(
            'PASSENGER STORED PATHS BEFORE SESSION',
            [
                'passengerStoredPaths' =>
                    $passengerStoredPaths,

                'passengerFiles' =>
                    $passengerFiles,

                'passengers' =>
                    $passengers,
            ]
        );

        session([
            'booking_review_data' => [

                'hotel_id' =>
                    $hotel->id,

                'hotel_room_type_id' =>
                    $roomType->id,

                'meal_plan_id' =>
                    $mealPlan?->id,

                'checkIn' =>
                    $checkIn->toDateString(),

                'checkOut' =>
                    $checkOut->toDateString(),

                'nights' =>
                    $nights,

                'totalGuests' =>
                    $totalGuests,

                'roomCharge' =>
                    $roomCharge,

                'mealCharge' =>
                    $mealCharge,

                'transportPrice' =>
                    $transportPrice,

                'visaPrice' =>
                    $visaPrice,

                'taxes' =>
                    $taxes,

                'grandTotal' =>
                    $grandTotal,

                'totalInPKR' =>
                    $totalInPKR,

                'passengerFiles' =>
                    $passengerFiles,

                'passengerStoredPaths' =>
                    $passengerStoredPaths,

                'contact_name' =>
                    $request->contact_name,

                'contact_email' =>
                    $request->contact_email,

                'contact_phone' =>
                    $request->contact_phone,

                'passengers' =>
                    $passengers,

                'adults' =>
                    $adults,

                'children' =>
                    $children,

                'infants' =>
                    $infants,

                'check_in' =>
                    $request->check_in,

                'check_out' =>
                    $request->check_out,

                'include_meal' =>
                    $request->boolean(
                        'include_meal'
                    ),

                'include_visa' =>
                    $request->boolean(
                        'include_visa'
                    ),

                'include_transport' =>
                    $request->boolean(
                        'include_transport'
                    ),
            ]
        ]);

        Log::info(
            'BOOKING REVIEW SESSION SAVED',
            [
                'review_data' =>
                    session(
                        'booking_review_data'
                    ),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | View Data
        |--------------------------------------------------------------------------
        */

        $contactName =
            $request->contact_name;

        $contactEmail =
            $request->contact_email;

        $contactPhone =
            $request->contact_phone;

        return view(
            'hotels.booking-review',
            compact(
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
                'totalInPKR',
                'passengerFiles',
                'passengerStoredPaths',
                'passengers',
                'contactName',
                'contactEmail',
                'contactPhone'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Review
    |--------------------------------------------------------------------------
    */

    public function reviewEdit(
        StoreBookingRequest $request
    ) {

        return redirect()
            ->route(
                'hotels.booking.create',
                [
                    'hotel' =>
                        $request->hotel_id
                ]
            )
            ->withInput(
                $request->all()
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Form
    |--------------------------------------------------------------------------
    */

    public function create(
        Hotel $hotel,
        Request $request
    ) {

        $hotel->load([
            'roomTypes',
            'mealPlans',
            'facilities',
            'images'
        ]);

        $checkIn =
            $request->old('check_in')
                ? Carbon::parse(
                    $request->old(
                        'check_in'
                    )
                )->startOfDay()
                : null;

        $checkOut =
            $request->old('check_out')
                ? Carbon::parse(
                    $request->old(
                        'check_out'
                    )
                )->startOfDay()
                : null;

        if (
            $checkIn &&
            $checkOut &&
            $checkOut->lt($checkIn)
        ) {

            $checkOut = null;
        }

        $roomTypeDateRanges =
            $this->buildRoomTypeDateRanges(
                $hotel
            );

        $roomTypeAvailabilities = [];

        if ($checkIn && $checkOut) {

            foreach (
                $hotel->roomTypes
                    ->where(
                        'status',
                        'Active'
                    )
                as $roomType
            ) {

                $roomTypeAvailabilities[
                    $roomType->id
                ] =
                    $roomType
                        ->summarizeAvailabilityForDates(
                            $checkIn,
                            $checkOut
                        );
            }
        }

        $visaType =
            VisaType::active()
                ->latest('id')
                ->first();

        return view(
            'hotels.booking-form',
            compact(
                'hotel',
                'checkIn',
                'checkOut',
                'roomTypeAvailabilities',
                'roomTypeDateRanges',
                'visaType'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Date Ranges
    |--------------------------------------------------------------------------
    */

    private function buildRoomTypeDateRanges(
        Hotel $hotel
    ): array {

        $roomTypeIds =
            $hotel->roomTypes
                ->where(
                    'status',
                    'Active'
                )
                ->pluck('id')
                ->all();

        if (empty($roomTypeIds)) {
            return [];
        }

        $inventoryGroups =
            HotelRoomInventory::where(
                'hotel_id',
                $hotel->id
            )
            ->whereIn(
                'hotel_room_type_id',
                $roomTypeIds
            )
            ->where(
                'status',
                'Active'
            )
            ->get()
            ->groupBy(
                'hotel_room_type_id'
            );

        return $inventoryGroups
            ->mapWithKeys(
                function (
                    $items,
                    $roomTypeId
                ) {

                    $minDate =
                        $items->min(
                            'inventory_date'
                        );

                    $maxDate =
                        $items
                            ->map(
                                function ($item) {

                                    return $item
                                        ->inventory_date_to
                                        ? $item
                                            ->inventory_date_to
                                            ->format(
                                                'Y-m-d'
                                            )
                                        : $item
                                            ->inventory_date
                                            ->format(
                                                'Y-m-d'
                                            );
                                }
                            )
                            ->max();

                    if (
                        !$minDate ||
                        !$maxDate
                    ) {
                        return [];
                    }

                    return [

                        $roomTypeId => [

                            'min_date' =>
                                $minDate->format(
                                    'Y-m-d'
                                ),

                            'max_date' =>
                                $maxDate,
                        ],
                    ];
                }
            )
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Reserve Inventory
    |--------------------------------------------------------------------------
    */

    private function reserveInventoryDates(
        HotelRoomType $roomType,
        Carbon $checkIn,
        Carbon $checkOut
    ): void {

        $current =
            $checkIn->copy();

        while (
            $current->lt($checkOut)
        ) {

            $date =
                $current->format(
                    'Y-m-d'
                );

            /*
            |--------------------------------------------------------------------------
            | Exact Inventory
            |--------------------------------------------------------------------------
            */

            $inventory =
                HotelRoomInventory::where(
                    'hotel_id',
                    $roomType->hotel_id
                )
                ->where(
                    'hotel_room_type_id',
                    $roomType->id
                )
                ->whereDate(
                    'inventory_date',
                    $date
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Date Range Inventory
            |--------------------------------------------------------------------------
            */

            if (!$inventory) {

                $inventory =
                    HotelRoomInventory::where(
                        'hotel_id',
                        $roomType->hotel_id
                    )
                    ->where(
                        'hotel_room_type_id',
                        $roomType->id
                    )
                    ->whereDate(
                        'inventory_date',
                        '<=',
                        $date
                    )
                    ->whereNotNull(
                        'inventory_date_to'
                    )
                    ->whereDate(
                        'inventory_date_to',
                        '>=',
                        $date
                    )
                    ->orderByDesc(
                        'inventory_date_to'
                    )
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Update Inventory
            |--------------------------------------------------------------------------
            */

            if ($inventory) {

                $inventoryEntry =
                    HotelRoomInventory::withTrashed()
                        ->firstOrNew([
                            'hotel_id' =>
                                $roomType->hotel_id,

                            'hotel_room_type_id' =>
                                $roomType->id,

                            'inventory_date' =>
                                $date,
                        ]);

                $inventoryEntry
                    ->inventory_date_to =
                    $date;

                $inventoryEntry
                    ->total_rooms =
                    $inventory->total_rooms;

                $inventoryEntry
                    ->available_rooms =
                    max(
                        0,
                        $inventory
                            ->available_rooms - 1
                    );

                $inventoryEntry
                    ->booked_rooms =
                    $inventory
                        ->booked_rooms + 1;

                $inventoryEntry
                    ->status =
                    $inventory->status;

                $inventoryEntry
                    ->deleted_at = null;

                $inventoryEntry->save();
            }

            $current->addDay();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Booking $booking
    ) {

        if (
            $booking->status ===
            'Cancelled'
        ) {

            return redirect()
                ->route(
                    'hotels.booking.confirmation',
                    [
                        'booking' =>
                            $booking->id
                    ]
                )
                ->with(
                    'info',
                    'This booking has already been cancelled.'
                );
        }

        $booking->cancel();

        return redirect()
            ->route(
                'hotels.booking.confirmation',
                [
                    'booking' =>
                        $booking->id
                ]
            )
            ->with(
                'success',
                'Booking cancelled and inventory restored successfully.'
            );
    }
}