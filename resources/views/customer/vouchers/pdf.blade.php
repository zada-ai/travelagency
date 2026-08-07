<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <title>Flight Voucher {{ $voucher->voucher_number }}</title>

    <style>
        @page {
            margin: 25px;
        }

        html {
            background: #f7f9fc;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            margin: 0;
            background: #f7f9fc;
        }

        .container {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            background: #ffffff;
            padding: 18px 18px 24px;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
        }

        .header {
            width: 100%;
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .agent-logo-cell {
            width: 145px;
            padding-right: 12px;
        }

        .company-logo {
            display: inline-block;
            max-width: 145px;
            max-height: 80px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .header-meta-cell {
            text-align: left;
            width: calc(100% - 370px);
            padding: 0 14px;
        }

        .header-right-cell {
            width: 165px;
            padding-left: 12px;
            text-align: right;
        }

        .voucher-meta {
            text-align: right;
        }

        .voucher-title {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.12em;
            margin-bottom: 8px;
        }

        .voucher-number,
        .status {
            display: block;
            font-size: 10px;
            color: #475569;
            margin-top: 6px;
        }

        .voucher-number {
            font-weight: 600;
        }

        .status {
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .agent-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .agent-small {
            font-size: 9px;
            color: #475569;
            line-height: 1.5;
        }

        .section {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #0f172a;
            letter-spacing: 0.04em;
        }

        .section-body {
            padding-top: 4px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 8px 10px;
            vertical-align: top;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-label {
            text-align: left;
            color: #64748b;
            font-size: 10px;
            padding: 8px 0;
        }

        .price-value {
            text-align: right;
            font-weight: 700;
            font-size: 10px;
            padding: 8px 0;
        }

        .grand-total td {
            border-top: 2px solid #0f172a;
            padding-top: 12px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 12px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            font-weight: 700;
        }

        .detail-table td {
            padding: 11px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .label {
            color: #64748b;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .value {
            color: #0f172a;
            font-size: 11px;
            font-weight: 700;
            margin-top: 3px;
        }

        .flight-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .flight-table td {
            border: none;
            padding: 10px 0;
            vertical-align: top;
        }

        .airport-code {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .airport-name {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
        }

        .flight-middle {
            text-align: center;
            width: 30%;
            padding: 0 10px;
        }

        .flight-number {
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .flight-time {
            font-size: 10px;
            color: #475569;
            line-height: 1.4;
            margin-top: 4px;
        }

        .included {
            color: #166534;
            font-weight: 700;
        }

        .not-included {
            color: #64748b;
        }

        .footer {
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 9px;
            line-height: 1.5;
        }

        .footer strong {
            color: #0f172a;
            font-weight: 700;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

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

    if ($voucher && method_exists($voucher, 'passengers')) {
        $passengers = $voucher->passengers()->get();
    }

    if (! $passengers->count() && $booking && method_exists($booking, 'passengers')) {
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
    $canRenderImages = extension_loaded('gd') || extension_loaded('gd2');

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

    <div class="header">
        <table class="header-table">

            <tr>

            @php
                $hasAgent = $agent && $agent->id;
                $headerType = $hasAgent ? 'agent' : 'customer';
            @endphp

            @switch($headerType)

                @case('agent')
                            <td class="agent-logo-cell">
                        @if($logoUrl && file_exists($logoUrl) && $canRenderImages)
                            <img src="{{ $logoUrl }}" class="company-logo" alt="{{ $agentName }}">
                        @else
                            <div style="width:120px; height:80px; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:10px; color:#94a3b8;">
                                AGENT
                            </div>
                        @endif
                    </td>

                    <td class="header-meta-cell">
                        <div style="font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Agent Details</div>
                        <div class="agent-name">{{ $agentName }}</div>
                        @if($agent?->email)
                            <div class="agent-small">{{ $agent->email }}</div>
                        @endif
                        @if($agent?->mobile)
                            <div class="agent-small">{{ $agent->mobile }}</div>
                        @endif
                    </td>

                    <td class="header-right-cell">
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:10px;">
                            @php
                                $adminLogoUrl = null;

                                if (! empty($voucher->admin_company_logo)) {
                                    $adminLogoUrl = str_starts_with($voucher->admin_company_logo, 'http')
                                        ? $voucher->admin_company_logo
                                        : public_path(ltrim($voucher->admin_company_logo, '/'));
                                }
                            @endphp

                            @if($adminLogoUrl && file_exists($adminLogoUrl) && $canRenderImages)
                                <img src="{{ $adminLogoUrl }}" class="company-logo" alt="Admin Logo">
                            @else
                                <div style="width:120px; height:80px; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:10px; color:#94a3b8;">
                                    ADMIN LOGO
                                </div>
                            @endif
                        </div>

                        <div style="text-align:right; margin-top:10px;">
                            <div style="font-size:16px; font-weight:bold; color:#0f172a;">
                                {{ $voucher->admin_company_name ?? ($setting->company_name ?? 'Admin Company') }}
                            </div>
                            @if(! empty($setting->company_name) && empty($voucher->admin_company_name))
                                <div style="font-size:10px; color:#64748b; margin-top:4px;">
                                    {{ $setting->company_name }}
                                </div>
                            @endif
                        </div>
                    </td>

                    @break

                @case('customer')
                    <td colspan="3" style="text-align:center;">
                        <div style="display:inline-block; text-align:center;">
                            @php
                                $adminLogoUrl = null;

                                if (! empty($voucher->admin_company_logo)) {
                                    $adminLogoUrl = str_starts_with($voucher->admin_company_logo, 'http')
                                        ? $voucher->admin_company_logo
                                        : public_path(ltrim($voucher->admin_company_logo, '/'));
                                }
                            @endphp

                            @if($adminLogoUrl && file_exists($adminLogoUrl) && $canRenderImages)
                                <img src="{{ $adminLogoUrl }}" class="company-logo" alt="Admin Logo">
                            @else
                                <div style="width:120px; height:80px; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:10px; color:#94a3b8;">
                                    ADMIN LOGO
                                </div>
                            @endif

                            <div style="font-size:16px; font-weight:bold; color:#0f172a; margin-top:8px;">
                                {{ $voucher->admin_company_name ?? ($setting->company_name ?? 'Admin Company') }}
                            </div>
                        </div>
                    </td>

                    @break

            @endswitch


            <td class="voucher-meta" style="text-align:right; padding-left:18px;">

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

                <div class="status" style="margin-top:6px;">
                    {{ $voucher->status }}
                </div>

            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- BOOKING / CUSTOMER --}}
    {{-- ========================================================= --}}

    <div class="section avoid-break">

        <div class="section-title">Booking & Customer Information</div>

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

            <div style="margin-top:12px;">
                <h4 style="font-size:14px;margin-bottom:8px;color:#0f172a;font-weight:700;">Applicant / Booking Contact</h4>
                <table class="info-table">
                    <tr>
                        <td width="33%">
                            <div class="label">Contact Name</div>
                            <div class="value">{{ $booking?->contact_name ?? $booking?->user?->name ?? 'N/A' }}</div>
                        </td>
                        <td width="33%">
                            <div class="label">Booked By</div>
                            <div class="value">{{ optional($booking?->user)->name ?? 'N/A' }}</div>
                        </td>
                        <td width="33%">
                            <div class="label">Email Address</div>
                            <div class="value">{{ $booking?->contact_email ?? $booking?->user?->email ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="33%">
                            <div class="label">WhatsApp Number</div>
                            <div class="value">{{ $booking?->contact_phone ?? $booking?->user?->phone ?? 'N/A' }}</div>
                        </td>
                        <td width="33%">
                            <div class="label">Booking Status</div>
                            <div class="value">{{ $booking?->status ?? '-' }}</div>
                        </td>
                        <td width="33%">
                            <div class="label">Booking Type</div>
                            <div class="value">{{ $isFlight ? 'Flight' : 'Package' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

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
                        Type
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Amount
                    </th>
                </tr>

                <tr>
                    <td>Visa</td>
                    <td>Travel Document</td>
                    <td>
                        @if($visaIncluded)
                            <span class="included">Included</span>
                        @else
                            <span class="not-included">Not Included</span>
                        @endif
                    </td>
                    <td>
                        @if($visaIncluded)
                            SAR {{ number_format($booking?->visa_price ?? 0, 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Transport</td>
                    <td>{{ $voucher->transport_type ?? ($transportIncluded ? 'Included' : '-') }}</td>
                    <td>
                        @if($transportIncluded)
                            <span class="included">Included</span>
                        @else
                            <span class="not-included">Not Included</span>
                        @endif
                    </td>
                    <td>
                        @if($transportIncluded)
                            SAR {{ number_format($booking?->transport_price ?? 0, 2) }}
                        @else
                            -
                        @endif
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

        <div style="margin-top:6px;">
            This voucher is issued against booking
            <strong>{{ $booking?->reference ?? '-' }}</strong>.
        </div>

        <div style="margin-top:6px;">
            Please verify all flight, passenger and travel details before departure.
        </div>

    </div>

</div>

</body>
</html>