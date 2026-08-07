@extends('layouts.app')

@section('content')
@php
    $booking = $voucher->flightBooking ?? $voucher->packageBooking;
    $isFlight = $voucher->flight_booking_id !== null;

    $agent = $isFlight ? $booking?->agent : null;
    $ticket = $isFlight ? $booking?->ticket : null;

    /*
    |--------------------------------------------------------------------------
    | Agent / Company Information
    |--------------------------------------------------------------------------
    */
    $agentName = $agent?->company_name
        ?? $agent?->name
        ?? 'Travel Agent';

    /*
    |--------------------------------------------------------------------------
    | Agent Logo
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | We only use the logo belonging to THIS booking's agent.
    | No global/admin uploaded logo is used.
    |--------------------------------------------------------------------------
    */
    $agentLogo = null;

    if ($agent) {
        $agentLogo =
            $agent->company_logo
            ?? $agent->logo
            ?? $agent->logo_path
            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Passenger / Price values
    |--------------------------------------------------------------------------
    */
    $adults = (int) ($booking?->adults ?? 0);
    $children = (int) ($booking?->children ?? 0);
    $infants = (int) ($booking?->infants ?? 0);

    $totalPassengers = (int) ($booking?->total_passengers ?? (
        $adults + $children + $infants
    ));

    $visaIncluded = (bool) ($booking?->include_visa ?? false);
    $transportIncluded = (bool) ($booking?->include_transport ?? false);

    /*
    |--------------------------------------------------------------------------
    | Airport details
    |--------------------------------------------------------------------------
    */
    $departureAirport = $ticket?->departureAirport;
    $arrivalAirport = $ticket?->arrivalAirport;
    $returnDepartureAirport = $ticket?->returnDepartureAirport;
    $returnArrivalAirport = $ticket?->returnArrivalAirport;

    // Determine appropriate download URL for this booking type (flight or package)
    $downloadUrl = '';
    if ($booking instanceof \App\Models\FlightBooking) {
        $downloadUrl = route('customer.vouchers.download', ['flightBooking' => $booking->id]);
    } elseif ($booking instanceof \App\Models\PackageBooking) {
        $downloadUrl = route('customer.vouchers.package.download', ['packageBooking' => $booking->id]);
    }
@endphp

<div class="max-w-6xl mx-auto py-8 px-4">

    {{-- ACTION BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 print:hidden">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Flight Voucher
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Voucher: {{ $voucher->voucher_number }}
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ Auth::guard('travel_agent')->check() ? route('travel-agents.bookings') : route('customer.bookings') }}"
               class="px-4 py-2 rounded-lg bg-slate-200 text-slate-800 text-sm font-semibold">
                Back
            </a>

            @if(Auth::guard('travel_agent')->check() || Auth::guard('web')->check())
                <a href="{{ $downloadUrl }}"
                   class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold" target="_blank" rel="noopener">
                    Download PDF
                </a>

                <a href="{{ $downloadUrl }}"
                   class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold" target="_blank" rel="noopener">
                    Print
                </a>
            @endif

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- VOUCHER --}}
    {{-- ========================================================= --}}

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">

        {{-- HEADER --}}
        <div class="px-8 py-6 border-b border-slate-200">

            <div class="flex items-center justify-between gap-6">

                {{-- LEFT: AGENT --}}
                <div class="flex items-center gap-4">

                    @if($agentLogo)

                        @php
                            $logoUrl = str_starts_with($agentLogo, 'http')
                                ? $agentLogo
                                : asset('storage/' . ltrim($agentLogo, '/'));
                        @endphp

                        <img src="{{ $logoUrl }}"
                             alt="{{ $agentName }}"
                             class="w-20 h-20 object-contain rounded-lg border border-slate-200 bg-white p-2">

                    @else

                        <div class="w-20 h-20 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center">
                            <span class="text-xs text-slate-400 text-center">
                                Agent Logo
                            </span>
                        </div>

                    @endif

                    <div>

                        <p class="text-xs uppercase tracking-wider text-slate-400">
                            Issued By
                        </p>

                        <h2 class="text-xl font-bold text-slate-900">
                            {{ $agentName }}
                        </h2>

                        @if($agent?->email)
                            <p class="text-sm text-slate-500">
                                {{ $agent->email }}
                            </p>
                        @endif

                        @if($agent?->mobile)
                            <p class="text-sm text-slate-500">
                                {{ $agent->mobile }}
                            </p>
                        @endif

                    </div>

                </div>


                {{-- RIGHT: VOUCHER --}}
                <div class="text-right">

                    <p class="text-xs uppercase tracking-wider text-slate-400">
                        Flight Voucher
                    </p>

                    <h1 class="text-2xl font-bold text-slate-900">
                        {{ $voucher->voucher_number }}
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Issued:
                        {{ optional($voucher->issued_at)->format('d M Y H:i') }}
                    </p>

                    <span class="inline-block mt-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                        {{ $voucher->status }}
                    </span>

                </div>

            </div>

        </div>


        {{-- BOOKING SUMMARY --}}
        <div class="px-8 py-6">

            <div class="grid md:grid-cols-4 gap-4">

                <div class="border rounded-xl p-4 bg-slate-50">
                    <p class="text-xs uppercase text-slate-400">
                        Booking Reference
                    </p>

                    <p class="text-lg font-bold text-slate-900 mt-1">
                        {{ $booking?->reference ?? '-' }}
                    </p>
                </div>

                <div class="border rounded-xl p-4 bg-slate-50">
                    <p class="text-xs uppercase text-slate-400">
                        Customer
                    </p>

                    <p class="text-lg font-bold text-slate-900 mt-1">
                        {{ $booking?->contact_name ?? $booking?->user?->name ?? '-' }}
                    </p>
                </div>

                <div class="border rounded-xl p-4 bg-slate-50">
                    <p class="text-xs uppercase text-slate-400">
                        Booking Status
                    </p>

                    <p class="text-lg font-bold text-emerald-600 mt-1">
                        {{ $booking?->status ?? '-' }}
                    </p>
                </div>

                <div class="border rounded-xl p-4 bg-slate-50">
                    <p class="text-xs uppercase text-slate-400">
                        Booking Type
                    </p>

                    <p class="text-lg font-bold text-slate-900 mt-1">
                        Flight
                    </p>
                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PASSENGER DETAILS --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                <h3 class="text-lg font-bold text-slate-900 border-b pb-3">
                    Passenger Details
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">

                    <div>
                        <p class="text-xs text-slate-400 uppercase">
                            Total Passengers
                        </p>
                        <p class="font-semibold text-slate-900">
                            {{ $totalPassengers }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase">
                            Adults
                        </p>
                        <p class="font-semibold text-slate-900">
                            {{ $adults }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase">
                            Children
                        </p>
                        <p class="font-semibold text-slate-900">
                            {{ $children }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase">
                            Infants
                        </p>
                        <p class="font-semibold text-slate-900">
                            {{ $infants }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FLIGHT DETAILS --}}
            {{-- ================================================= --}}

            @if($isFlight && $ticket)

                <div class="mt-8">

                    <h3 class="text-lg font-bold text-slate-900 border-b pb-3">
                        Flight Details
                    </h3>


                    {{-- AIRLINE --}}
                    <div class="mt-5 grid md:grid-cols-4 gap-5">

                        <div>
                            <p class="text-xs uppercase text-slate-400">
                                Airline
                            </p>

                            <p class="font-bold text-slate-900 mt-1">
                                {{ $ticket->airline ?? $ticket->airlineMaster?->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase text-slate-400">
                                Flight Number
                            </p>

                            <p class="font-bold text-slate-900 mt-1">
                                {{ $ticket->flight_number ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase text-slate-400">
                                Cabin Class
                            </p>

                            <p class="font-bold text-slate-900 mt-1">
                                {{ $booking->cabin_class ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase text-slate-400">
                                Ticket Type
                            </p>

                            <p class="font-bold text-slate-900 mt-1">
                                {{ $ticket->ticket_type ?? '-' }}
                            </p>
                        </div>

                    </div>


                    {{-- OUTBOUND --}}
                    <div class="mt-6 border rounded-xl overflow-hidden">

                        <div class="bg-slate-900 text-white px-5 py-3 font-semibold">
                            Departure Flight
                        </div>

                        <div class="p-5 grid md:grid-cols-3 gap-6">

                            <div>

                                <p class="text-xs uppercase text-slate-400">
                                    From
                                </p>

                                <p class="text-lg font-bold text-slate-900">
                                    {{ $departureAirport?->code ?? '-' }}
                                </p>

                                <p class="text-sm text-slate-500">
                                    {{ $departureAirport?->name ?? '' }}
                                </p>

                            </div>


                            <div class="text-center">

                                <p class="text-xs uppercase text-slate-400">
                                    Flight
                                </p>

                                <p class="font-bold text-slate-900">
                                    {{ $ticket->flight_number ?? '-' }}
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $ticket->departure_date?->format('d M Y') ?? '-' }}
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $ticket->departure_time ?? '-' }}
                                    →
                                    {{ $ticket->arrival_time ?? '-' }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="text-xs uppercase text-slate-400">
                                    To
                                </p>

                                <p class="text-lg font-bold text-slate-900">
                                    {{ $arrivalAirport?->code ?? '-' }}
                                </p>

                                <p class="text-sm text-slate-500">
                                    {{ $arrivalAirport?->name ?? '' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- RETURN --}}
                    @if($ticket->return_date)

                        <div class="mt-5 border rounded-xl overflow-hidden">

                            <div class="bg-slate-700 text-white px-5 py-3 font-semibold">
                                Return Flight
                            </div>

                            <div class="p-5 grid md:grid-cols-3 gap-6">

                                <div>

                                    <p class="text-xs uppercase text-slate-400">
                                        From
                                    </p>

                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $returnDepartureAirport?->code ?? $arrivalAirport?->code ?? '-' }}
                                    </p>

                                </div>


                                <div class="text-center">

                                    <p class="text-xs uppercase text-slate-400">
                                        Return Date
                                    </p>

                                    <p class="font-bold text-slate-900">
                                        {{ $ticket->return_date?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="text-sm font-semibold text-slate-700 mt-1">
                                        {{ $ticket->return_departure_time ?? '-' }}
                                        →
                                        {{ $ticket->return_arrival_time ?? '-' }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs uppercase text-slate-400">
                                        To
                                    </p>

                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $returnArrivalAirport?->code ?? $departureAirport?->code ?? '-' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- EXTRA TICKET INFO --}}
                    <div class="mt-5 grid md:grid-cols-4 gap-4">

                        <div class="border rounded-lg p-4">
                            <p class="text-xs text-slate-400 uppercase">
                                Baggage
                            </p>
                            <p class="font-semibold mt-1">
                                {{ $ticket->baggage ?? 'Not specified' }}
                            </p>
                        </div>

                        <div class="border rounded-lg p-4">
                            <p class="text-xs text-slate-400 uppercase">
                                Meal
                            </p>
                            <p class="font-semibold mt-1">
                                {{ $ticket->meal ?? 'Not specified' }}
                            </p>
                        </div>

                        <div class="border rounded-lg p-4">
                            <p class="text-xs text-slate-400 uppercase">
                                Refundable
                            </p>
                            <p class="font-semibold mt-1">
                                {{ $ticket->refundable ? 'Yes' : 'No' }}
                            </p>
                        </div>

                        <div class="border rounded-lg p-4">
                            <p class="text-xs text-slate-400 uppercase">
                                Seat(s)
                            </p>
                            <p class="font-semibold mt-1">
                                {{ is_array($booking->seat_numbers)
                                    ? implode(', ', $booking->seat_numbers)
                                    : ($booking->seat_numbers ?? '-') }}
                            </p>
                        </div>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- ADDONS --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                <h3 class="text-lg font-bold text-slate-900 border-b pb-3">
                    Additional Services
                </h3>

                <div class="grid md:grid-cols-2 gap-4 mt-4">

                    <div class="border rounded-xl p-5">

                        <div class="flex items-center justify-between">

                            <span class="font-semibold text-slate-800">
                                Visa
                            </span>

                            @if($visaIncluded)
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                    Included
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                    Not Included
                                </span>
                            @endif

                        </div>

                        @if($visaIncluded)
                            <p class="text-sm text-slate-500 mt-2">
                                Visa Price:
                                SAR {{ number_format($booking->visa_price ?? 0, 2) }}
                            </p>
                        @endif

                    </div>


                    <div class="border rounded-xl p-5">

                        <div class="flex items-center justify-between">

                            <span class="font-semibold text-slate-800">
                                Transport
                            </span>

                            @if($transportIncluded)
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                    Included
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                    Not Included
                                </span>
                            @endif

                        </div>

                        @if($transportIncluded)
                            <p class="text-sm text-slate-500 mt-2">
                                Transport Price:
                                SAR {{ number_format($booking->transport_price ?? 0, 2) }}
                            </p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CONTACT --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                <h3 class="text-lg font-bold text-slate-900 border-b pb-3">
                    Contact Details
                </h3>

                <div class="grid md:grid-cols-3 gap-4 mt-4">

                    <div>
                        <p class="text-xs uppercase text-slate-400">
                            Name
                        </p>
                        <p class="font-semibold">
                            {{ $booking->contact_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-400">
                            Email
                        </p>
                        <p class="font-semibold">
                            {{ $booking->contact_email ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-400">
                            Phone
                        </p>
                        <p class="font-semibold">
                            {{ $booking->contact_phone ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PRICE SUMMARY --}}
            {{-- ================================================= --}}

            <div class="mt-8 border-t pt-6">

                <div class="max-w-md ml-auto space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Base Fare
                        </span>

                        <span class="font-semibold">
                            SAR {{ number_format($booking->price ?? 0, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Taxes
                        </span>

                        <span class="font-semibold">
                            SAR {{ number_format($booking->taxes ?? 0, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">
                            Service Charge
                        </span>

                        <span class="font-semibold">
                            SAR {{ number_format($booking->service_charge ?? 0, 2) }}
                        </span>
                    </div>

                    @if($visaIncluded)

                        <div class="flex justify-between">
                            <span class="text-slate-500">
                                Visa
                            </span>

                            <span class="font-semibold">
                                SAR {{ number_format($booking->visa_price ?? 0, 2) }}
                            </span>
                        </div>

                    @endif

                    @if($transportIncluded)

                        <div class="flex justify-between">
                            <span class="text-slate-500">
                                Transport
                            </span>

                            <span class="font-semibold">
                                SAR {{ number_format($booking->transport_price ?? 0, 2) }}
                            </span>
                        </div>

                    @endif

                    <div class="border-t pt-3 flex justify-between text-lg">

                        <span class="font-bold text-slate-900">
                            Grand Total
                        </span>

                        <span class="font-bold text-slate-900">
                            SAR {{ number_format($booking->grand_total ?? 0, 2) }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="mt-8 pt-5 border-t text-center">

                <p class="text-xs text-slate-400">
                    This voucher is issued against booking
                    <strong>{{ $booking?->reference ?? '-' }}</strong>.
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Please verify all travel details before departure.
                </p>

            </div>

        </div>

    </div>

</div>

<style>
@media print {

    body {
        background: white !important;
    }

    .print\:hidden {
        display: none !important;
    }

    .shadow-sm {
        box-shadow: none !important;
    }

    .rounded-2xl {
        border-radius: 0 !important;
    }

    .border {
        border-color: #d1d5db !important;
    }

}
</style>

@endsection