<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <title>Flight Voucher {{ $voucher->voucher_number }}</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            margin: 0;
            background: #fff;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .agent-logo-cell {
            width: 100px;
        }

        .agent-logo {
            width: 80px;
            height: 60px;
            object-fit: contain;
        }

        .agent-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .agent-small {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }

        .voucher-meta {
            text-align: right;
        }

        .voucher-title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .voucher-number {
            font-size: 12px;
            color: #475569;
            margin-top: 4px;
        }

        .status {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 4px 9px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            background: #0f172a;
            color: #fff;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .section-body {
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 5px 6px;
            vertical-align: top;
        }

        .label {
            color: #64748b;
            font-size: 9px;
            text-transform: uppercase;
        }

        .value {
            color: #0f172a;
            font-size: 11px;
            font-weight: bold;
            margin-top: 2px;
        }

        .flight-table {
            width: 100%;
            border-collapse: collapse;
        }

        .flight-table td {
            border: none;
            padding: 8px;
            vertical-align: middle;
        }

        .airport-code {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .airport-name {
            font-size: 9px;
            color: #64748b;
        }

        .flight-middle {
            text-align: center;
            width: 30%;
        }

        .flight-number {
            font-weight: bold;
            font-size: 12px;
        }

        .flight-time {
            font-size: 10px;
            color: #475569;
            margin-top: 4px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th {
            background: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 7px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }

        .detail-table td {
            padding: 7px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-table td {
            padding: 6px;
            border: none;
        }

        .price-label {
            text-align: left;
            color: #64748b;
        }

        .price-value {
            text-align: right;
            font-weight: bold;
        }

        .grand-total td {
            border-top: 2px solid #0f172a;
            padding-top: 9px;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }

        .included {
            color: #166534;
            font-weight: bold;
        }

        .not-included {
            color: #64748b;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 9px;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | BOOKING
    |--------------------------------------------------------------------------
    */

    $isFlight = $voucher->flight_booking_id !== null;

    $booking = $voucher->flightBooking ?? $voucher->packageBooking;

    $ticket = $isFlight
        ? $booking?->ticket
        : null;


    /*
    |--------------------------------------------------------------------------
    | AGENT
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Agent comes ONLY from this booking.
    | No global admin logo is used.
    |
    */

    $agent = $isFlight
        ? $booking?->agent
        : null;

    $agentName = $agent?->company_name
        ?? $agent?->name
        ?? 'Travel Agent';


    /*
    |--------------------------------------------------------------------------
    | AGENT LOGO
    |--------------------------------------------------------------------------
    */

    $agentLogo = $agent?->company_logo
        ?? $agent?->logo
        ?? $agent?->logo_path
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | PASSENGERS
    |--------------------------------------------------------------------------
    */

    $passengers = collect();

    if ($booking && method_exists($booking, 'passengers')) {
        $passengers = $booking->passengers()->get();
    }


    /*
    |--------------------------------------------------------------------------
    | COUNTS
    |--------------------------------------------------------------------------
    */

    $adults = (int) ($booking?->adults ?? 0);
    $children = (int) ($booking?->children ?? 0);
    $infants = (int) ($booking?->infants ?? 0);

    $totalPassengers = (int) (
        $booking?->total_passengers
        ?? ($adults + $children + $infants)
    );


    /*
    |--------------------------------------------------------------------------
    | ADDONS
    |--------------------------------------------------------------------------
    */

    $visaIncluded = (bool) ($booking?->include_visa ?? false);

    $transportIncluded = (bool) ($booking?->include_transport ?? false);


    /*
    |--------------------------------------------------------------------------
    | AIRPORTS
    |--------------------------------------------------------------------------
    */

    $departureAirport = $ticket?->departureAirport;

    $arrivalAirport = $ticket?->arrivalAirport;

    $returnDepartureAirport = $ticket?->returnDepartureAirport;

    $returnArrivalAirport = $ticket?->returnArrivalAirport;


    /*
    |--------------------------------------------------------------------------
    | LOGO URL
    |--------------------------------------------------------------------------
    */

    $logoUrl = null;

    if ($agentLogo) {

        if (str_starts_with($agentLogo, 'http')) {

            $logoUrl = $agentLogo;

        } else {

            $logoUrl = public_path(
                'storage/' . ltrim($agentLogo, '/')
            );

        }
    }

@endphp


<div class="container">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <table class="header-table">

        <tr>

            <td class="agent-logo-cell">

                @if($logoUrl && file_exists($logoUrl))

                    <img
                        src="{{ $logoUrl }}"
                        class="agent-logo"
                        alt="{{ $agentName }}"
                    >

                @else

                    <div style="
                        width:80px;
                        height:60px;
                        border:1px solid #e2e8f0;
                        text-align:center;
                        padding-top:15px;
                        box-sizing:border-box;
                        color:#94a3b8;
                        font-size:9px;
                    ">
                        AGENT
                    </div>

                @endif

            </td>


            <td>

                <div class="agent-name">
                    {{ $agentName }}
                </div>

                @if($agent?->email)

                    <div class="agent-small">
                        {{ $agent->email }}
                    </div>

                @endif

                @if($agent?->mobile)

                    <div class="agent-small">
                        {{ $agent->mobile }}
                    </div>

                @endif

            </td>


            <td class="voucher-meta">

                <div class="voucher-title">
                    FLIGHT VOUCHER
                </div>

                <div class="voucher-number">
                    {{ $voucher->voucher_number }}
                </div>

                <div class="voucher-number">
                    Issued:
                    {{ optional($voucher->issued_at)->format('d M Y H:i') }}
                </div>

                <div class="status">
                    {{ $voucher->status }}
                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- BOOKING / CUSTOMER --}}
    {{-- ========================================================= --}}

    <div class="section avoid-break">

        <div class="section-title">
            Booking & Customer Information
        </div>

        <div class="section-body">

            <table class="info-table">

                <tr>

                    <td width="25%">
                        <div class="label">
                            Booking Reference
                        </div>

                        <div class="value">
                            {{ $booking?->reference ?? '-' }}
                        </div>
                    </td>


                    <td width="25%">
                        <div class="label">
                            Customer
                        </div>

                        <div class="value">
                            {{ $booking?->contact_name ?? $booking?->user?->name ?? '-' }}
                        </div>
                    </td>


                    <td width="25%">
                        <div class="label">
                            Booking Status
                        </div>

                        <div class="value">
                            {{ $booking?->status ?? '-' }}
                        </div>
                    </td>


                    <td width="25%">
                        <div class="label">
                            Booking Type
                        </div>

                        <div class="value">
                            Flight
                        </div>
                    </td>

                </tr>

            </table>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FLIGHT --}}
    {{-- ========================================================= --}}

    @if($isFlight && $ticket)

        <div class="section avoid-break">

            <div class="section-title">
                Flight Details
            </div>

            <div class="section-body">

                <table class="info-table">

                    <tr>

                        <td width="25%">
                            <div class="label">
                                Airline
                            </div>

                            <div class="value">
                                {{ $ticket->airline ?? $ticket->airlineMaster?->name ?? '-' }}
                            </div>
                        </td>


                        <td width="25%">
                            <div class="label">
                                Flight Number
                            </div>

                            <div class="value">
                                {{ $ticket->flight_number ?? '-' }}
                            </div>
                        </td>


                        <td width="25%">
                            <div class="label">
                                Cabin Class
                            </div>

                            <div class="value">
                                {{ $booking?->cabin_class ?? '-' }}
                            </div>
                        </td>


                        <td width="25%">
                            <div class="label">
                                Ticket Type
                            </div>

                            <div class="value">
                                {{ $ticket->ticket_type ?? '-' }}
                            </div>
                        </td>

                    </tr>

                </table>


                {{-- OUTBOUND --}}

                <table class="flight-table" style="margin-top:12px;">

                    <tr>

                        <td width="30%">

                            <div class="label">
                                Departure
                            </div>

                            <div class="airport-code">
                                {{ $departureAirport?->code ?? '-' }}
                            </div>

                            <div class="airport-name">
                                {{ $departureAirport?->name ?? '' }}
                            </div>

                            <div class="flight-time">
                                {{ $ticket->departure_date?->format('d M Y') ?? '-' }}
                                <br>
                                {{ $ticket->departure_time ?? '-' }}
                            </div>

                        </td>


                        <td class="flight-middle">

                            <div class="flight-number">
                                {{ $ticket->flight_number ?? '-' }}
                            </div>

                            <div style="margin-top:5px;">
                                ─────────
                            </div>

                            <div class="flight-time">
                                Direct / Flight
                            </div>

                        </td>


                        <td width="30%" style="text-align:right;">

                            <div class="label">
                                Arrival
                            </div>

                            <div class="airport-code">
                                {{ $arrivalAirport?->code ?? '-' }}
                            </div>

                            <div class="airport-name">
                                {{ $arrivalAirport?->name ?? '' }}
                            </div>

                            <div class="flight-time">
                                {{ $ticket->arrival_time ?? '-' }}
                            </div>

                        </td>

                    </tr>

                </table>


                {{-- RETURN --}}

                @if($ticket->return_date)

                    <div style="
                        border-top:1px solid #e2e8f0;
                        margin-top:10px;
                        padding-top:10px;
                    ">

                        <div style="
                            font-weight:bold;
                            font-size:10px;
                            margin-bottom:6px;
                        ">
                            RETURN FLIGHT
                        </div>

                        <table class="flight-table">

                            <tr>

                                <td width="30%">

                                    <div class="label">
                                        Departure
                                    </div>

                                    <div class="airport-code">
                                        {{ $returnDepartureAirport?->code
                                            ?? $arrivalAirport?->code
                                            ?? '-' }}
                                    </div>

                                    <div class="airport-name">
                                        {{ $returnDepartureAirport?->name ?? '' }}
                                    </div>

                                </td>


                                <td class="flight-middle">

                                    <div class="flight-number">
                                        {{ $ticket->flight_number ?? '-' }}
                                    </div>

                                    <div class="flight-time">
                                        {{ $ticket->return_date?->format('d M Y') ?? '-' }}
                                    </div>

                                    <div class="flight-time">
                                        {{ $ticket->return_departure_time ?? '-' }}
                                        →
                                        {{ $ticket->return_arrival_time ?? '-' }}
                                    </div>

                                </td>


                                <td width="30%" style="text-align:right;">

                                    <div class="label">
                                        Arrival
                                    </div>

                                    <div class="airport-code">
                                        {{ $returnArrivalAirport?->code
                                            ?? $departureAirport?->code
                                            ?? '-' }}
                                    </div>

                                    <div class="airport-name">
                                        {{ $returnArrivalAirport?->name ?? '' }}
                                    </div>

                                </td>

                            </tr>

                        </table>

                    </div>

                @endif

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- TICKET FEATURES --}}
        {{-- ===================================================== --}}

        <div class="section avoid-break">

            <div class="section-title">
                Ticket Information
            </div>

            <div class="section-body">

                <table class="detail-table">

                    <tr>

                        <th>
                            Baggage
                        </th>

                        <th>
                            Meal
                        </th>

                        <th>
                            Refundable
                        </th>

                        <th>
                            Seat
                        </th>

                    </tr>

                    <tr>

                        <td>
                            {{ $ticket->baggage ?? 'Not specified' }}
                        </td>

                        <td>
                            {{ $ticket->meal ?? 'Not specified' }}
                        </td>

                        <td>
                            {{ $ticket->refundable ? 'Yes' : 'No' }}
                        </td>

                        <td>
                            @if(is_array($booking?->seat_numbers))
                                {{ implode(', ', $booking->seat_numbers) }}
                            @else
                                {{ $booking?->seat_numbers ?? '-' }}
                            @endif
                        </td>

                    </tr>

                </table>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- PASSENGERS --}}
    {{-- ========================================================= --}}

    <div class="section avoid-break">

        <div class="section-title">
            Passenger Information
        </div>

        <div class="section-body">

            <table class="detail-table">

                <tr>

                    <th>
                        Passenger
                    </th>

                    <th>
                        Type
                    </th>

                    <th>
                        Passport
                    </th>

                </tr>


                @if($passengers->count())

                    @foreach($passengers as $passenger)

                        <tr>

                            <td>
                                {{ $passenger->name
                                    ?? $passenger->full_name
                                    ?? trim(
                                        ($passenger->first_name ?? '')
                                        . ' '
                                        . ($passenger->last_name ?? '')
                                    )
                                    ?: 'N/A' }}
                            </td>

                            <td>
                                {{ $passenger->passenger_type
                                    ?? $passenger->type
                                    ?? 'ADT' }}
                            </td>

                            <td>
                                {{ $passenger->passport_number
                                    ?? $passenger->passport
                                    ?? 'N/A' }}
                            </td>

                        </tr>

                    @endforeach

                @else

                    <tr>

                        <td colspan="3" style="text-align:center;color:#64748b;">
                            Passenger details are not available.
                        </td>

                    </tr>

                @endif

            </table>


            <table class="info-table" style="margin-top:10px;">

                <tr>

                    <td width="25%">
                        <div class="label">
                            Total
                        </div>
                        <div class="value">
                            {{ $totalPassengers }}
                        </div>
                    </td>

                    <td width="25%">
                        <div class="label">
                            Adults
                        </div>
                        <div class="value">
                            {{ $adults }}
                        </div>
                    </td>

                    <td width="25%">
                        <div class="label">
                            Children
                        </div>
                        <div class="value">
                            {{ $children }}
                        </div>
                    </td>

                    <td width="25%">
                        <div class="label">
                            Infants
                        </div>
                        <div class="value">
                            {{ $infants }}
                        </div>
                    </td>

                </tr>

            </table>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- SERVICES --}}
    {{-- ========================================================= --}}

    <div class="section avoid-break">

        <div class="section-title">
            Additional Services
        </div>

        <div class="section-body">

            <table class="detail-table">

                <tr>

                    <th>
                        Service
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Amount
                    </th>

                </tr>


                <tr>

                    <td>
                        Visa
                    </td>

                    <td>

                        @if($visaIncluded)
                            <span class="included">
                                Included
                            </span>
                        @else
                            <span class="not-included">
                                Not Included
                            </span>
                        @endif

                    </td>

                    <td>
                        SAR
                        {{ number_format($booking?->visa_price ?? 0, 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Transport
                    </td>

                    <td>

                        @if($transportIncluded)
                            <span class="included">
                                Included
                            </span>
                        @else
                            <span class="not-included">
                                Not Included
                            </span>
                        @endif

                    </td>

                    <td>
                        SAR
                        {{ number_format($booking?->transport_price ?? 0, 2) }}
                    </td>

                </tr>

            </table>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- PRICE --}}
    {{-- ========================================================= --}}

    <div class="section avoid-break">

        <div class="section-title">
            Fare Summary
        </div>

        <div class="section-body">

            <table class="price-table">

                <tr>

                    <td class="price-label">
                        Base Fare
                    </td>

                    <td class="price-value">
                        SAR
                        {{ number_format($booking?->price ?? 0, 2) }}
                    </td>

                </tr>


                <tr>

                    <td class="price-label">
                        Taxes
                    </td>

                    <td class="price-value">
                        SAR
                        {{ number_format($booking?->taxes ?? 0, 2) }}
                    </td>

                </tr>


                <tr>

                    <td class="price-label">
                        Service Charge
                    </td>

                    <td class="price-value">
                        SAR
                        {{ number_format($booking?->service_charge ?? 0, 2) }}
                    </td>

                </tr>


                @if($visaIncluded)

                    <tr>

                        <td class="price-label">
                            Visa
                        </td>

                        <td class="price-value">
                            SAR
                            {{ number_format($booking?->visa_price ?? 0, 2) }}
                        </td>

                    </tr>

                @endif


                @if($transportIncluded)

                    <tr>

                        <td class="price-label">
                            Transport
                        </td>

                        <td class="price-value">
                            SAR
                            {{ number_format($booking?->transport_price ?? 0, 2) }}
                        </td>

                    </tr>

                @endif


                <tr class="grand-total">

                    <td>
                        GRAND TOTAL
                    </td>

                    <td style="text-align:right;">
                        SAR
                        {{ number_format($booking?->grand_total ?? 0, 2) }}
                    </td>

                </tr>

            </table>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <div class="footer">

        <strong>
            {{ $agentName }}
        </strong>

        <br>

        This voucher is issued against booking
        <strong>{{ $booking?->reference ?? '-' }}</strong>.

        <br>

        Please verify all flight, passenger and travel details before departure.

    </div>

</div>

</body>
</html>