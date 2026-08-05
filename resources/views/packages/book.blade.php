<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Package | Hujaj Umrah ERP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased bg-slate-50">

    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[280px_1fr] relative">
            @include('layouts.partials.sidebar')

            <main class="flex-1 p-4 md:p-8 overflow-y-auto space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xs border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Book Package</h1>
                        <p class="text-slate-500 text-sm mt-1">You are booking: <span class="font-semibold text-indigo-600">{{ $package->title }}</span></p>
                    </div>
                    <a href="{{ route('packages.index') }}" class="px-5 py-2 border border-gray-300 text-gray-600 rounded-full hover:bg-gray-50 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $bookingStoreRoute = request()->routeIs('travel-agents.packages.book')
                        ? route('travel-agents.packages.store', $package->id)
                        : route('packages.store', $package->id);
                @endphp

                <form action="{{ $bookingStoreRoute }}" method="POST" enctype="multipart/form-data" id="bookingForm">
                    @csrf

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                        <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                            <h5 class="text-base font-bold text-gray-800">1. Contact Information</h5>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_name" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" value="{{ old('contact_name', auth()->user()->name ?? '') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="contact_email" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_phone" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required>
                            </div>
                        </div>
                    </div>
                    @php
                        $outboundFlight = $package->outboundFlight;
                        $returnFlight = $package->returnFlight;
                    @endphp

                    {{-- Package Flight Details --}}
                    @if($package->has_flight && $outboundFlight)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                                <h5 class="text-base font-bold text-gray-800">2. Flight Information</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Selected outbound and return flight details included in this package.</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                    <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                        <div class="text-xs uppercase tracking-wider text-slate-500 mb-3 font-semibold">Outbound Flight</div>
                                        <div class="space-y-2">
                                            <div><span class="font-semibold text-gray-900">Airline:</span> {{ $outboundFlight->airline ?? $package->airline ?? 'N/A' }}</div>
                                            <div><span class="font-semibold text-gray-900">Flight Number:</span> {{ $outboundFlight->flight_number ?? 'N/A' }}</div>
                                            <div><span class="font-semibold text-gray-900">Route:</span> {{ $outboundFlight->departureAirport?->code ?? 'N/A' }} → {{ $outboundFlight->arrivalAirport?->code ?? 'N/A' }}</div>
                                            <div><span class="font-semibold text-gray-900">Departure:</span> {{ $outboundFlight->departure_date?->format('d M Y') ?? 'N/A' }} {{ $outboundFlight->departure_time ?? '' }}</div>
                                            <div><span class="font-semibold text-gray-900">Arrival:</span> {{ $outboundFlight->departure_date?->format('d M Y') ?? 'N/A' }} {{ $outboundFlight->arrival_time ?? '' }}</div>
                                            <div><span class="font-semibold text-gray-900">Cabin Class:</span> {{ collect(['Economy', 'Premium Economy', 'Business', 'First'])->filter(fn($cabin) => $outboundFlight->{$outboundFlight->getCabinField($cabin)} > 0)->join(', ') ?: 'Economy' }}</div>
                                            <div><span class="font-semibold text-gray-900">Flight Type:</span> {{ $outboundFlight->ticket_type ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    @if($outboundFlight->ticket_type === 'Round-trip' && $outboundFlight->return_date)
                                        <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                            <div class="text-xs uppercase tracking-wider text-slate-500 mb-3 font-semibold">Return Flight</div>
                                            <div class="space-y-2">
                                                <div><span class="font-semibold text-gray-900">Airline:</span> {{ $outboundFlight->airline ?? $package->airline ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Flight Number:</span> {{ $outboundFlight->flight_number ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Route:</span> {{ $outboundFlight->returnDepartureAirport?->code ?? $outboundFlight->arrivalAirport?->code ?? 'N/A' }} → {{ $outboundFlight->returnArrivalAirport?->code ?? $outboundFlight->departureAirport?->code ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Departure:</span> {{ $outboundFlight->return_date?->format('d M Y') ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Arrival:</span> {{ $outboundFlight->return_date?->format('d M Y') ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    @elseif($returnFlight)
                                        <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                            <div class="text-xs uppercase tracking-wider text-slate-500 mb-3 font-semibold">Return Flight</div>
                                            <div class="space-y-2">
                                                <div><span class="font-semibold text-gray-900">Airline:</span> {{ $returnFlight->airline ?? $package->airline ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Flight Number:</span> {{ $returnFlight->flight_number ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Route:</span> {{ $returnFlight->departureAirport?->code ?? 'N/A' }} → {{ $returnFlight->arrivalAirport?->code ?? 'N/A' }}</div>
                                                <div><span class="font-semibold text-gray-900">Departure:</span> {{ $returnFlight->departure_date?->format('d M Y') ?? 'N/A' }} {{ $returnFlight->departure_time ?? '' }}</div>
                                                <div><span class="font-semibold text-gray-900">Arrival:</span> {{ $returnFlight->departure_date?->format('d M Y') ?? 'N/A' }} {{ $returnFlight->arrival_time ?? '' }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Package Service Summary --}}
                    @if($package->has_flight || $package->has_hotel || $package->has_transport || $package->has_visa || $package->has_meals)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                                <h5 class="text-base font-bold text-gray-800">3. Included Package Services</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Overview of the services included in this package.</p>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                    <div class="font-semibold text-gray-900">Flights</div>
                                    <div>{{ $package->has_flight ? 'Included' : 'Not included' }}</div>
                                </div>
                                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                    <div class="font-semibold text-gray-900">Hotel Accommodation</div>
                                    <div>{{ $package->has_hotel ? 'Included' : 'Not included' }}</div>
                                </div>
                                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                    <div class="font-semibold text-gray-900">Transport</div>
                                    <div>{{ $package->has_transport ? 'Included' : 'Not included' }}</div>
                                    @if($package->has_transport)
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($package->transportRates->where('rate_type', 'passenger')->isNotEmpty())
                                                SAR {{ number_format($package->transportRates->where('rate_type', 'passenger')->min('price'), 2) }} - SAR {{ number_format($package->transportRates->where('rate_type', 'passenger')->max('price'), 2) }} per person depending on passenger count
                                            @elseif($package->transport_price)
                                                SAR {{ number_format($package->transport_price, 2) }} per person
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                    <div class="font-semibold text-gray-900">Visa</div>
                                    <div>{{ $package->has_visa ? 'Included' : 'Not included' }}</div>
                                    @if($package->has_visa)
                                        <div class="text-xs text-gray-500 mt-1">SAR {{ number_format($package->effectiveVisaProcessingPrice(), 2) }} per person</div>
                                    @endif
                                </div>
                                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
                                    <div class="font-semibold text-gray-900">Meals</div>
                                    <div>{{ $package->has_meals ? 'Included' : 'Not included' }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Package Hotel Details --}}
@if($package->hotelStays->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">

        <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
            <h5 class="text-base font-bold text-gray-800">
                        4. Hotel Accommodation
                Hotels included in this package
            </p>
        </div>

        <div class="p-6 space-y-4">

            @foreach($package->hotelStays as $stay)

                <div class="border border-gray-200 rounded-xl p-5 bg-slate-50">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <div>
                            <div class="flex items-center gap-2">
                                <h6 class="font-bold text-gray-900 text-base">
                                    {{ $stay->hotel_name }}
                                </h6>

                                @if($stay->star_rating)
                                    <span class="text-amber-500 text-sm">
                                        {{ str_repeat('★', (int) $stay->star_rating) }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-500 mt-1">
                                <i class="bi bi-geo-alt"></i>
                                {{ $stay->city }}
                            </p>
                        </div>

                        @if($stay->price_per_person)
                            <div class="text-right">
                                <span class="text-xs text-gray-400 block">
                                    Hotel Price / Person
                                </span>

                                <span class="font-bold text-indigo-600">
                                    SAR {{ number_format($stay->price_per_person, 2) }}
                                </span>
                            </div>
                        @endif

                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5 pt-4 border-t border-gray-200">

                        <div>
                            <span class="text-xs text-gray-400 block">
                                Check-in
                            </span>

                            <span class="text-sm font-semibold text-gray-800">
                                {{ $stay->check_in?->format('d M Y') ?? 'N/A' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400 block">
                                Check-out
                            </span>

                            <span class="text-sm font-semibold text-gray-800">
                                {{ $stay->check_out?->format('d M Y') ?? 'N/A' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400 block">
                                Nights
                            </span>

                            <span class="text-sm font-semibold text-gray-800">
                                {{ $stay->nights ?? 'N/A' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-xs text-gray-400 block">
                                Room Type
                            </span>

                            <span class="text-sm font-semibold text-gray-800">
                                {{ $stay->room_type ?? 'N/A' }}
                            </span>
                        </div>

                    </div>

                    @if($stay->distance_from_haram || $stay->walking_time)

                        <div class="flex flex-wrap gap-4 mt-4 text-xs text-gray-500">

                            @if($stay->distance_from_haram)
                                <span>
                                    <i class="bi bi-signpost-2"></i>
                                    {{ $stay->distance_from_haram }} from Haram
                                </span>
                            @endif

                            @if($stay->walking_time)
                                <span>
                                    <i class="bi bi-person-walking"></i>
                                    {{ $stay->walking_time }} walking
                                </span>
                            @endif

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    </div>
@endif

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                        <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                        <h5 class="text-base font-bold text-gray-800">3. Select Seats</h5>
<p class="text-xs text-gray-500 mt-0.5">Total available seats: {{ $package->available_seats }} | Default base price: SAR {{ number_format($package->price, 0) }}</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                                        <input type="number" name="adults" id="adults" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" min="1" value="{{ old('adults', 1) }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                                        <input type="number" name="children" id="children" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" min="0" value="{{ old('children', 0) }}">
                                        <p class="text-xs text-gray-400 mt-1">Use Children for non-infant passengers.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Infants</label>
                                        <input type="number" name="infants" id="infants" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" min="0" value="{{ old('infants', 0) }}">
                                    </div>
                                </div>

                                <div class="mt-6 p-4 bg-indigo-50 rounded-xl">
                                    <h6 class="text-sm font-semibold text-indigo-900 mb-3">Price Breakdown</h6>
                                    <div class="space-y-3 text-sm text-slate-700">
                                        <div class="flex justify-between"><span>Adult Charges</span><span id="adultPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Child Charges</span><span id="childPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Infant Charges</span><span id="infantPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Visa Processing</span><span id="visaPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Transport</span><span id="transportPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Hotel</span><span id="hotelPriceDisplay">SAR 0</span></div>
                                        <div class="flex justify-between"><span>Flight / Ticket</span><span id="flightPriceDisplay">SAR 0</span></div>
                                        <hr class="border-slate-200" />
                                        <div class="flex justify-between text-base font-semibold text-indigo-900"><span>Grand Total</span><span id="grandTotalDisplay">SAR 0</span></div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                        <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50 flex items-center justify-between">
                            <div>
                                <h5 class="text-base font-bold text-gray-800">3. Passenger Details</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Provide full passenger details for each selected seat.</p>
                            </div>
                            <button type="button" class="px-4 py-1.5 border border-indigo-600 text-indigo-600 rounded-full text-xs font-semibold hover:bg-indigo-50 transition" onclick="generatePassengerFields()">
                                Generate Fields
                            </button>
                        </div>
                        <div class="p-6 bg-gray-50/30" id="passengersContainer">
                            <div class="text-center py-8 text-gray-400" id="passengerPlaceholder">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-sm">Please enter the number of seats above and click <strong>Generate Fields</strong> to provide passenger details.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-full shadow-md hover:shadow-lg transition text-sm">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    @php
        $transportRatesArray = [];
        foreach ($package->transportRates as $rate) {
            $transportRatesArray[] = [
                'rate_type' => $rate->rate_type,
                'passenger_from' => (int) $rate->passenger_from,
                'passenger_to' => (int) $rate->passenger_to,
                'price' => (float) $rate->price,
            ];
        }

        $hotelUnitPrice = 0;
        if ($package->has_hotel) {
            foreach ($package->hotelStays as $stay) {
                $hotelUnitPrice += (float) ($stay->price_per_person ?? 0);
            }
        }
    @endphp

    <script>
        const adultPrice = {{ $package->effectiveAdultPrice() }};
        const childPrice = {{ $package->effectiveChildPrice() }};
        const infantPrice = {{ $package->effectiveInfantPrice() }};
        const visaProcessingPrice = {{ $package->effectiveVisaProcessingPrice() }};
        const transportRates = @json($transportRatesArray);
        const hotelUnitPrice = {{ $hotelUnitPrice }};
        const hasVisa = {{ $package->has_visa ? 'true' : 'false' }};
        const hasTransport = {{ $package->has_transport ? 'true' : 'false' }};
        const availableSeats = {{ $package->available_seats }};
        const hasFlight = {{ $package->has_flight && $outboundFlight ? 'true' : 'false' }};
        const flightRates = {
            outbound: {
                adult: {{ $outboundFlight->adult_fare ?? 0 }},
                child: {{ $outboundFlight->child_fare ?? 0 }},
                infant: {{ $outboundFlight->infant_fare ?? 0 }},
            },
            return: {
                enabled: {{ $package->returnFlight && $package->outboundFlight && $package->outboundFlight->ticket_type !== 'Round-trip' && $package->returnFlight->id !== $package->outboundFlight->id ? 'true' : 'false' }},
                adult: {{ $package->returnFlight->adult_fare ?? 0 }},
                child: {{ $package->returnFlight->child_fare ?? 0 }},
                infant: {{ $package->returnFlight->infant_fare ?? 0 }},
            }
        };
        const oldPassengers = @json(old('passengers', []));
        const passengerPlaceholderHtml = `
            <div class="text-center py-8 text-gray-400" id="passengerPlaceholder">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p class="text-sm">Please enter the number of seats above and click <strong>Generate Fields</strong> to provide passenger details.</p>
            </div>
        `;

        function findPassengerTransportRate(paidPassengers) {
            return transportRates.find(rate =>
                rate.rate_type === 'passenger'
                && paidPassengers >= rate.passenger_from
                && paidPassengers <= rate.passenger_to
            ) || null;
        }

        function calculateTransportCharges(adults, children, infants) {
            if (!hasTransport || transportRates.length === 0) {
                return 0;
            }

            const paidPassengers = adults + children;
            let total = 0;

            if (paidPassengers > 0) {
                const rate = findPassengerTransportRate(paidPassengers);

                if (rate) {
                    total += rate.price * paidPassengers;
                }
            }

            if (infants > 0) {
                const infantRate = transportRates.find(rate => rate.rate_type === 'infant');

                if (infantRate) {
                    total += infantRate.price * infants;
                }
            }

            return total;
        }

        function calculateFlightCharges(adults, children, infants) {
            if (!hasFlight) {
                return 0;
            }

            let total = adults * flightRates.outbound.adult
                + children * flightRates.outbound.child
                + infants * flightRates.outbound.infant;

            if (flightRates.return.enabled) {
                total += adults * flightRates.return.adult
                    + children * flightRates.return.child
                    + infants * flightRates.return.infant;
            }

            return total;
        }

        function calculateTotal() {
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            const infants = parseInt(document.getElementById('infants').value) || 0;

            const totalGuests = adults + children + infants;
            const adultCharges = adults * adultPrice;
            const childCharges = children * childPrice;
            const infantCharges = infants * infantPrice;
            const visaCharges = hasVisa ? totalGuests * visaProcessingPrice : 0;
            const transportCharges = calculateTransportCharges(adults, children, infants);
            const hotelCharges = totalGuests * hotelUnitPrice;
            const flightCharges = calculateFlightCharges(adults, children, infants);
            const grandTotal = adultCharges + childCharges + infantCharges + visaCharges + transportCharges + hotelCharges + flightCharges;

            document.getElementById('adultPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(adultCharges);
            document.getElementById('childPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(childCharges);
            document.getElementById('infantPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(infantCharges);
            document.getElementById('visaPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(visaCharges);
            document.getElementById('transportPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(transportCharges);
            document.getElementById('hotelPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(hotelCharges);
            document.getElementById('flightPriceDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(flightCharges);
            document.getElementById('grandTotalDisplay').innerText = 'SAR ' + new Intl.NumberFormat().format(grandTotal);
        }

        function createPassengerFields(passengers) {
            const container = document.getElementById('passengersContainer');
            container.innerHTML = '';

            if (!passengers || passengers.length === 0) {
                container.innerHTML = passengerPlaceholderHtml;
                return;
            }

            passengers.forEach((passenger, index) => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-xl p-5 border border-gray-200 shadow-sm mb-4';
                card.innerHTML = `
                    <h6 class="font-bold text-indigo-600 mb-4 text-sm">Passenger ${index + 1} (${passenger.type || 'Adult'})</h6>
                    <input type="hidden" name="passengers[${index}][type]" value="${passenger.type || 'Adult'}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="passengers[${index}][name]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" value="${passenger.name || ''}" required placeholder="As per passport">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="passengers[${index}][dob]" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3 border" value="${passenger.dob || ''}" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">CNIC Upload (Front & Back) <span class="text-red-500">*</span></label>
                            <input type="file" name="passengers[${index}][cnic_document]" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border rounded-lg py-1 px-2 border-gray-300" accept=".jpg,.jpeg,.png,.pdf" required>
                            <p class="text-[10px] text-gray-400 mt-1">Max 5MB. Combine front and back into one file.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Passport Upload <span class="text-red-500">*</span></label>
                            <input type="file" name="passengers[${index}][passport_document]" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border rounded-lg py-1 px-2 border-gray-300" accept=".jpg,.jpeg,.png,.pdf" required>
                            <p class="text-[10px] text-gray-400 mt-1">Max 5MB.</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function generatePassengerFields() {
            const adults = parseInt(document.getElementById('adults').value) || 0;
            const children = parseInt(document.getElementById('children').value) || 0;
            const infants = parseInt(document.getElementById('infants').value) || 0;
            const total = adults + children + infants;

            if (total === 0) {
                document.getElementById('passengersContainer').innerHTML = '<div class="text-center py-4 text-red-500 text-sm font-medium">Please select at least 1 seat.</div>';
                return;
            }

            if (total > availableSeats) {
                document.getElementById('passengersContainer').innerHTML = '<div class="text-center py-4 text-red-500 text-sm font-medium">You cannot select more seats than available (' + availableSeats + ').</div>';
                return;
            }

            const passengers = [];
            for (let i = 0; i < adults; i++) passengers.push({ type: 'Adult' });
            for (let i = 0; i < children; i++) passengers.push({ type: 'Child' });
            for (let i = 0; i < infants; i++) passengers.push({ type: 'Infant' });

            createPassengerFields(passengers);
        }

        document.getElementById('adults').addEventListener('input', calculateTotal);
        document.getElementById('children').addEventListener('input', calculateTotal);
        document.getElementById('infants').addEventListener('input', calculateTotal);

        window.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
            if (oldPassengers.length > 0) {
                createPassengerFields(oldPassengers);
            }
        });
    </script>
</body>
</html>
