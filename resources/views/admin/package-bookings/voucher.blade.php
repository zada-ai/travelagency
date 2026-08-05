<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umrah Voucher - {{ $booking->reference_number }}</title>
    
    <!-- Vite / Tailwind CSS Loader (Aap apni setup ke mutabiq use karein) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* PDF print optimization rules */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
            }
            .print-shadow-none {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
            oucher-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #ddd;
        margin-bottom: 25px;
    }

    .company-info h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .company-info p {
        margin: 5px 0 0;
        color: #666;
        font-size: 14px;
    }

    .company-logo {
        width: 120px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .company-logo img {
        max-width: 120px;
        max-height: 70px;
        object-fit: contain;
    }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800 p-4 md:p-8 print:p-0 print:bg-white">

    <!-- Print / Download Button -->
    <div class="max-w-4xl mx-auto text-right mb-4 no-print">
        <button onclick="window.print()" class="bg-gray-900 hover:bg-gray-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-sm transition-all duration-150 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print / Save PDF
        </button>
    </div>

    <!-- Main Voucher Document -->
    <div class="max-w-4xl mx-auto bg-white p-6 md:p-10 border border-gray-200 shadow-sm rounded-xl print-shadow-none print:border-none print:p-0">

        <!-- HEADER -->
      <!-- HEADER -->

<!-- HEADER -->
<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:items-start justify-between border-b-2 border-gray-900 pb-5 mb-6 gap-4">

    {{-- Visa Provider Information --}}
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            {{ $booking->visa_provider_company_name ?? 'Umrah Travel Services' }}
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Umrah Package Booking Department
        </p>
    </div>

    {{-- Visa Provider Logo + Voucher Reference --}}
    <div class="flex items-center justify-end">

       @if(!empty($booking->visa_provider_logo))
    <img
        src="{{ asset($booking->visa_provider_logo) }}?v={{ $booking->updated_at?->timestamp }}"
        alt="{{ $booking->visa_provider_company_name ?? 'Visa Provider' }}"
        class="max-h-20 object-contain"
        style="max-width:180px;"
    >
@endif
        <div class="text-left sm:text-right ml-4">

            <h2 class="text-xl font-black uppercase text-gray-900 tracking-wider">
                Umrah Voucher
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Reference:
                <strong class="text-gray-900 font-bold">
                    {{ $booking->reference_number }}
                </strong>
            </p>

        </div>

    </div>

</div>


        <!-- BOOKING INFORMATION -->
        <div class="mt-6">
            <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                Booking Information
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 border border-gray-200 border-t-0 rounded-b-md divide-y sm:divide-y-0 divide-gray-200">
                <div class="p-3.5 border-b sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Booking Reference</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->reference_number }}</span>
                </div>
                <div class="p-3.5 border-b border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Booking Status</span>
                    <span class="text-sm font-semibold text-gray-900 capitalize">{{ $booking->status }}</span>
                </div>
                <div class="p-3.5 border-b sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Package</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->title ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5 border-b border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Booking Date</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->created_at?->format('d M Y') }}</span>
                </div>
                <div class="p-3.5 border-b sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Contact Name</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->contact_name }}</span>
                </div>
                <div class="p-3.5 border-b border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Contact Phone</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->contact_phone }}</span>
                </div>
                <div class="p-3.5 border-b sm:border-b-0 sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Contact Email</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->contact_email }}</span>
                </div>
                <div class="p-3.5">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Passengers</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->adults + $booking->children + $booking->infants }}</span>
                </div>
            </div>
        </div>

        <!-- PASSENGER DETAILS -->
        <div class="mt-6">
            <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                Passenger Details
            </div>
            <div class="overflow-x-auto border border-gray-200 border-t-0 rounded-b-md">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-600 border-b border-gray-200">
                            <th class="p-3 border-r border-gray-200 w-12 text-center">#</th>
                            <th class="p-3 border-r border-gray-200">Passenger Name</th>
                            <th class="p-3 border-r border-gray-200">Type</th>
                            <th class="p-3">Date of Birth</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($booking->passengers as $index => $passenger)
                            <tr class="hover:bg-gray-50/50">
                                <td class="p-3 border-r border-gray-200 text-center text-gray-500 text-xs">{{ $index + 1 }}</td>
                                <td class="p-3 border-r border-gray-200 font-bold text-gray-900">{{ $passenger->name }}</td>
                                <td class="p-3 border-r border-gray-200 text-gray-700">{{ $passenger->type }}</td>
                                <td class="p-3 text-gray-700">{{ $passenger->dob?->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PACKAGE INFORMATION -->
        <div class="mt-6">
            <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                Package Details
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 border border-gray-200 border-t-0 rounded-b-md divide-y sm:divide-y-0 divide-gray-200">
                <div class="p-3.5 border-b sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Package Name</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->title ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5 border-b border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Airline</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->airline ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5 border-b sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Origin</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->origin ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5 border-b border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Destination</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->destination ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5 border-b sm:border-b-0 sm:border-r border-gray-200">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Departure Date</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->departure_date?->format('d M Y') ?? 'N/A' }}</span>
                </div>
                <div class="p-3.5">
                    <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Return Date</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $booking->package->return_date?->format('d M Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        @php
            $outbound = $booking->package->outboundFlight;
            $return = $booking->package->returnFlight;
        @endphp

        @if($booking->package->has_flight && $outbound)
            <div class="mt-6">
                <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                    Flight Details
                </div>
                <div class="border border-gray-200 border-t-0 rounded-b-md bg-white p-4 space-y-6">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Outbound Flight</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <div class="font-semibold text-gray-900">Airline</div>
                                <div>{{ $outbound->airline ?? $booking->package->airline ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Flight Number</div>
                                <div>{{ $outbound->flight_number ?? 'N/A' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="font-semibold text-gray-900">Route</div>
                                <div>{{ $outbound->departureAirport?->code ?? 'N/A' }} → {{ $outbound->arrivalAirport?->code ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Departure Date</div>
                                <div>{{ $outbound->departure_date?->format('d M Y') ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Departure Time</div>
                                <div>{{ $outbound->departure_time ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Arrival Time</div>
                                <div>{{ $outbound->arrival_time ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Cabin Class</div>
                                <div>
                                    @php
                                        $cabinClasses = collect(['Economy', 'Premium Economy', 'Business', 'First'])
                                            ->filter(fn($cabin) => $outbound->{$outbound->getCabinField($cabin)} > 0)
                                            ->values();
                                    @endphp
                                    {{ $cabinClasses->join(', ') ?: 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Flight Type</div>
                                <div>{{ $outbound->ticket_type ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($return)
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Return Flight</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <div class="font-semibold text-gray-900">Airline</div>
                                    <div>{{ $return->airline ?? $booking->package->airline ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Flight Number</div>
                                    <div>{{ $return->flight_number ?? 'N/A' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="font-semibold text-gray-900">Route</div>
                                    <div>{{ $return->departureAirport?->code ?? 'N/A' }} → {{ $return->arrivalAirport?->code ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Departure Date</div>
                                    <div>{{ $return->departure_date?->format('d M Y') ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Departure Time</div>
                                    <div>{{ $return->departure_time ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Arrival Date</div>
                                    <div>{{ $return->departure_date?->format('d M Y') ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Arrival Time</div>
                                    <div>{{ $return->arrival_time ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Cabin Class</div>
                                    <div>
                                        @php
                                            $returnCabinClasses = collect(['Economy', 'Premium Economy', 'Business', 'First'])
                                                ->filter(fn($cabin) => $return->{$return->getCabinField($cabin)} > 0)
                                                ->values();
                                        @endphp
                                        {{ $returnCabinClasses->join(', ') ?: 'N/A' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Flight Type</div>
                                    <div>{{ $return->ticket_type ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    @elseif($outbound->ticket_type === 'Round-trip' && ($outbound->return_date || $outbound->returnDepartureAirport || $outbound->returnArrivalAirport))
                        @php
                            $hasReturnDepartureTime = array_key_exists('return_departure_time', $outbound->getAttributes());
                            $hasReturnArrivalTime = array_key_exists('return_arrival_time', $outbound->getAttributes());
                        @endphp
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Return Flight</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div>
                                    <div class="font-semibold text-gray-900">Airline</div>
                                    <div>{{ $outbound->airline ?? $booking->package->airline ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Flight Number</div>
                                    <div>{{ $outbound->flight_number ?? 'N/A' }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="font-semibold text-gray-900">Route</div>
                                    <div>{{ $outbound->returnDepartureAirport?->code ?? $outbound->arrivalAirport?->code ?? 'N/A' }} → {{ $outbound->returnArrivalAirport?->code ?? $outbound->departureAirport?->code ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Return Date</div>
                                    <div>{{ $outbound->return_date?->format('d M Y') ?? 'N/A' }}</div>
                                </div>
                                @if($hasReturnDepartureTime && $outbound->return_departure_time)
                                    <div>
                                        <div class="font-semibold text-gray-900">Return Departure Time</div>
                                        <div>{{ $outbound->return_departure_time }}</div>
                                    </div>
                                @endif
                                @if($hasReturnArrivalTime && $outbound->return_arrival_time)
                                    <div>
                                        <div class="font-semibold text-gray-900">Return Arrival Time</div>
                                        <div>{{ $outbound->return_arrival_time }}</div>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-900">Cabin Class</div>
                                    <div>{{ $cabinClasses->join(', ') ?: 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Flight Type</div>
                                    <div>{{ $outbound->ticket_type ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @php
            $package = $booking->package;
            $hotelDisplayItems = collect();

            if ($package) {
                if ($package->hotelStays && $package->hotelStays->isNotEmpty()) {
                    $hotelDisplayItems = $package->hotelStays;
                } else {
                    if (! empty($package->makkah_hotel)) {
                        $hotelDisplayItems->push((object) [
                            'city' => 'Makkah',
                            'hotel_name' => $package->makkah_hotel,
                        ]);
                    }

                    if (! empty($package->madinah_hotel)) {
                        $hotelDisplayItems->push((object) [
                            'city' => 'Madinah',
                            'hotel_name' => $package->madinah_hotel,
                        ]);
                    }
                }
            }
        @endphp

        @if($package && $hotelDisplayItems->isNotEmpty())
            <div class="mt-6">
                <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                    Hotel Accommodation
                </div>
                <div class="border border-gray-200 border-t-0 rounded-b-md bg-white p-4 space-y-4">
                    @foreach($hotelDisplayItems as $stay)
                        @php
                            $stayCity = data_get($stay, 'city');
                            $stayHotelName = data_get($stay, 'hotel_name');
                            $stayCheckIn = data_get($stay, 'check_in');
                            $stayCheckOut = data_get($stay, 'check_out');
                            $stayNights = data_get($stay, 'nights');
                            $stayStarRating = data_get($stay, 'star_rating');
                            $stayDistance = data_get($stay, 'distance_from_haram');
                            $stayWalkingTime = data_get($stay, 'walking_time');
                            $stayRoomType = data_get($stay, 'room_type');
                            $stayRoomSharing = data_get($stay, 'room_sharing_options');
                            $stayTransportNotes = data_get($stay, 'transport_notes');
                        @endphp

                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-gray-400">{{ $stayCity }}</p>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $stayHotelName }}</h3>
                                </div>
                                <div class="text-sm text-gray-600">
                                    @if($stayCheckIn)
                                        <div>Check-in: {{ $stayCheckIn instanceof \Carbon\Carbon ? $stayCheckIn->format('d M Y') : $stayCheckIn }}</div>
                                    @endif
                                    @if($stayCheckOut)
                                        <div>Check-out: {{ $stayCheckOut instanceof \Carbon\Carbon ? $stayCheckOut->format('d M Y') : $stayCheckOut }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                                @if($stayRoomType)
                                    <div>
                                        <div class="font-semibold text-gray-900">Room Type</div>
                                        <div>{{ $stayRoomType }}</div>
                                    </div>
                                @endif
                                @if($stayNights)
                                    <div>
                                        <div class="font-semibold text-gray-900">Nights</div>
                                        <div>{{ $stayNights }}</div>
                                    </div>
                                @endif
                                @if($stayStarRating)
                                    <div>
                                        <div class="font-semibold text-gray-900">Star Rating</div>
                                        <div>{{ $stayStarRating }} Star</div>
                                    </div>
                                @endif
                                @if($stayDistance)
                                    <div>
                                        <div class="font-semibold text-gray-900">Distance from Haram</div>
                                        <div>{{ $stayDistance }}</div>
                                    </div>
                                @endif
                                @if($stayWalkingTime)
                                    <div>
                                        <div class="font-semibold text-gray-900">Walking Time</div>
                                        <div>{{ $stayWalkingTime }}</div>
                                    </div>
                                @endif
                                @if($stayRoomSharing)
                                    <div>
                                        <div class="font-semibold text-gray-900">Room Sharing</div>
                                        <div>
                                            @if(is_array($stayRoomSharing))
                                                {{ implode(', ', $stayRoomSharing) }}
                                            @else
                                                {{ $stayRoomSharing }}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($stayTransportNotes)
                                <div class="mt-4 text-sm text-gray-700">
                                    <div class="font-semibold text-gray-900">Transport Notes</div>
                                    <div>{{ $stayTransportNotes }}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($booking->package->has_transport)
            <div class="mt-6">
                <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                    Transport
                </div>
                <div class="border border-gray-200 border-t-0 rounded-b-md bg-white p-4 text-sm text-gray-700">
                    <div class="font-semibold text-gray-900 mb-2">Included</div>
                    @if($booking->package->transport_price)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <div class="font-semibold text-gray-900">Transport Price</div>
                                <div>SAR {{ number_format($booking->package->transport_price, 2) }}</div>
                            </div>
                            @if($booking->package->hotelStays->first()?->transport_notes)
                                <div class="md:col-span-2">
                                    <div class="font-semibold text-gray-900">Transport Notes</div>
                                    <div>{{ $booking->package->hotelStays->first()->transport_notes }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($booking->package->has_visa)
            <div class="mt-6">
                <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                    Visa
                </div>
                <div class="border border-gray-200 border-t-0 rounded-b-md bg-white p-4 text-sm text-gray-700">
                    <div class="font-semibold text-gray-900">Included</div>
                </div>
            </div>
        @endif

        @if($booking->package->has_meals)
            <div class="mt-6">
                <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                    Meals
                </div>
                <div class="border border-gray-200 border-t-0 rounded-b-md bg-white p-4 text-sm text-gray-700">
                    <div class="font-semibold text-gray-900">Included</div>
                </div>
            </div>
        @endif

        <!-- BOOKING AMOUNT -->
        <div class="mt-6">
            <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                Booking Amount
            </div>
            <div class="flex justify-end border border-gray-200 border-t-0 rounded-b-md bg-gray-50/30 p-4">
                <div class="w-full sm:w-72 bg-white border border-gray-200 rounded-lg overflow-hidden divide-y divide-gray-200 shadow-sm">
                    <div class="flex justify-between items-center px-4 py-2.5 text-xs text-gray-600">
                        <span>Adults</span>
                        <strong class="font-semibold text-gray-900">{{ $booking->adults }}</strong>
                    </div>
                    <div class="flex justify-between items-center px-4 py-2.5 text-xs text-gray-600">
                        <span>Children</span>
                        <strong class="font-semibold text-gray-900">{{ $booking->children }}</strong>
                    </div>
                    <div class="flex justify-between items-center px-4 py-2.5 text-xs text-gray-600">
                        <span>Infants</span>
                        <strong class="font-semibold text-gray-900">{{ $booking->infants }}</strong>
                    </div>
                    <div class="flex justify-between items-center px-4 py-3 bg-gray-100 text-sm font-bold text-gray-900">
                        <span>Total Amount</span>
                        <span class="text-base text-indigo-700">SAR {{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- IMPORTANT NOTES -->
        <div class="mt-6">
            <div class="bg-gray-900 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-t-md">
                Important Notes
            </div>
            <div class="p-4 border border-gray-200 border-t-0 rounded-b-md text-xs text-gray-600 space-y-1.5 leading-relaxed bg-gray-50/20">
                <p>• This voucher confirms the approved Umrah package booking.</p>
                <p>• Please carry your original passport and required travel documents during your journey.</p>
                <p>• Hotel accommodation and package services are subject to the terms and conditions of the selected package.</p>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="mt-10 pt-6 border-t border-gray-200 text-center text-xs text-gray-500 space-y-1">
           

<strong class="block text-gray-700 font-semibold">
    {{ $booking->visa_provider_company_name ?? 'Umrah Travel Services' }}
</strong>



            <p>This is a system-generated voucher.</p>
            <p class="text-[11px] text-gray-400">Reference: {{ $booking->reference_number }}</p>
        </div>

    </div>

</body>
</html>