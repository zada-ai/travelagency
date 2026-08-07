@extends('layouts.app')

@section('content')
@php
    $booking = $voucher->flightBooking ?? $voucher->packageBooking;
    $isFlight = $voucher->flight_booking_id !== null;
    $bookingType = $isFlight ? 'Flight' : 'Package';
    $ticket = $isFlight ? $booking?->ticket : null;
    $agent = $isFlight ? $booking?->agent : null;
    $visaIncluded = (bool) ($booking?->include_visa ?? false);
    $transportIncluded = (bool) ($booking?->include_transport ?? false);
    $adults = (int) ($booking?->adults ?? 0);
    $children = (int) ($booking?->children ?? 0);
    $infants = (int) ($booking?->infants ?? 0);
    $totalPassengers = (int) ($booking?->total_passengers ?? ($adults + $children + $infants));
    $departureAirport = $ticket?->departureAirport;
    $arrivalAirport = $ticket?->arrivalAirport;
    $returnDepartureAirport = $ticket?->returnDepartureAirport;
    $returnArrivalAirport = $ticket?->returnArrivalAirport;
@endphp

<div class="max-w-6xl mx-auto py-10 px-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                {{-- Left: Agent block (shows only when agent exists) --}}
                <div class="order-1 md:order-1">
                    @if($agent)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-center gap-3">
                            @php
                                $agentLogo = $agent?->company_logo ?? $agent?->logo ?? null;
                                $agentLogoUrl = $agentLogo ? (str_starts_with($agentLogo, 'http') ? $agentLogo : asset($agentLogo)) : null;
                            @endphp

                            @if($agentLogoUrl)
                                <img src="{{ $agentLogoUrl }}" alt="Agent Logo" class="object-contain" style="max-width:160px; max-height:80px; width:auto; height:auto;" />
                            @else
                                <div class="h-12 w-12 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-slate-400">AGENT</div>
                            @endif

                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $agent?->company_name ?? $agent?->name ?? 'Travel Agent' }}</div>
                                @if($agent?->email)
                                    <div class="text-xs text-slate-500">{{ $agent->email }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Middle: Title and meta --}}
                <div class="flex-1 order-3 md:order-2 text-left md:text-center">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $bookingType }} Voucher</h1>
                    <p class="text-sm text-slate-500">Issued: {{ optional($voucher->issued_at)->format('d M Y H:i') }}</p>
                </div>

                {{-- Right: Admin logo and actions --}}
                <div class="order-2 md:order-3 text-right">
                    @if(! empty($voucher->admin_company_logo) || ! empty($voucher->admin_company_name))
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left inline-block">
                            @if(! empty($voucher->admin_company_logo))
                                <img src="{{ asset($voucher->admin_company_logo) }}" alt="Admin Logo" class="mb-3 object-contain" style="max-width:160px; max-height:80px; width:auto; height:auto;" />
                            @endif
                            <div class="text-sm font-semibold text-slate-900">{{ $voucher->admin_company_name ?? 'Admin Company' }}</div>
                        </div>
                    @endif

                    {{-- Transport type is set during voucher creation (Generate Voucher modal) and is not editable here. --}}

                    <div class="inline-flex items-center gap-2 ml-3">
                        <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 text-white">Back</a>
                        <a href="{{ route('admin.vouchers.download', ['voucher' => $voucher->id]) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white">Download PDF</a>
                        <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white">Print</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 space-y-8">
            <div class="grid md:grid-cols-4 gap-4">
                <div class="bg-slate-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Booking Reference</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->reference ?? $booking?->reference_number ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Customer</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->contact_name ?? optional($booking?->user)->name ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Booking Status</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->status ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Booking Type</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $bookingType }}</p>
                </div>
                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Applicant / Booking Contact</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->contact_name ?? $booking?->user?->name ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Booked By</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($booking?->user)->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Email Address</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->contact_email ?? $booking?->user?->email ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-wider text-slate-400">WhatsApp Number</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $booking?->contact_phone ?? $booking?->user?->phone ?? 'N/A' }}</p>
                    </div>
            </div>

            @php
                $voucherPassengers = collect();
                if (method_exists($voucher, 'passengers')) {
                    $voucherPassengers = $voucher->passengers;
                }
                if (! $voucherPassengers->count() && $booking && method_exists($booking, 'passengers')) {
                    $voucherPassengers = $booking->passengers;
                }
            @endphp

            @if($voucherPassengers->count())
                <div class="rounded-2xl border border-slate-200 p-5 bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-900">Passenger Information</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                                    <th class="p-3 font-semibold">Passenger</th>
                                    <th class="p-3 font-semibold">Type</th>
                                    <th class="p-3 font-semibold">Passport</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voucherPassengers as $passenger)
                                    <tr class="border-t border-slate-200">
                                        <td class="p-3 text-slate-900">
                                            {{ trim(($passenger->first_name ?? '') . ' ' . ($passenger->last_name ?? '')) ?: ($passenger->name ?? ($passenger->full_name ?? 'N/A')) }}
                                        </td>
                                        <td class="p-3 text-slate-900">
                                            {{ $passenger->passenger_type ?? $passenger->type ?? 'ADT' }}
                                        </td>
                                        <td class="p-3 text-slate-900">
                                            {{ $passenger->passport_number ?? $passenger->passport ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($isFlight && $ticket)
                <div class="grid gap-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Flight Details</h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <h3 class="text-lg font-bold text-slate-900">Additional Services</h3>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="w-full text-sm border-collapse">
                                        <thead>
                                            <tr class="bg-slate-100 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                                                <th class="p-3 font-semibold">Service</th>
                                                <th class="p-3 font-semibold">Type</th>
                                                <th class="p-3 font-semibold">Status</th>
                                                <th class="p-3 font-semibold">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-t border-slate-200">
                                                <td class="p-3 text-slate-900">Visa</td>
                                                <td class="p-3 text-slate-900">-</td>
                                                <td class="p-3 text-slate-900">{{ $visaIncluded ? 'Included' : 'Not Included' }}</td>
                                                <td class="p-3 text-slate-900">@if($visaIncluded) SAR {{ number_format($booking?->visa_price ?? 0, 2) }} @else - @endif</td>
                                            </tr>
                                            <tr class="border-t border-slate-200">
                                                <td class="p-3 text-slate-900">Transport</td>
                                                <td class="p-3 text-slate-900">{{ $voucher->transport_type ?? ($booking?->transport_type ?? '-') }}</td>
                                                <td class="p-3 text-slate-900">{{ $transportIncluded ? 'Included' : 'Not Included' }}</td>
                                                <td class="p-3 text-slate-900">@if($transportIncluded) SAR {{ number_format($booking?->transport_price ?? 0, 2) }} @else - @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Departure</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $departureAirport?->code ?? '-' }}</p>
                            <p class="text-sm text-slate-500">{{ $departureAirport?->name ?? '' }}</p>
                            <p class="mt-3 text-sm text-slate-700">{{ $ticket->departure_date?->format('d M Y') ?? '-' }} {{ $ticket->departure_time ?? '' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Arrival</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $arrivalAirport?->code ?? '-' }}</p>
                            <p class="text-sm text-slate-500">{{ $arrivalAirport?->name ?? '' }}</p>
                            <p class="mt-3 text-sm text-slate-700">{{ $ticket->arrival_time ?? '-' }}</p>
                        </div>
                    </div>

                    @if($ticket->return_date)
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Return Flight</p>
                            <div class="mt-4 grid md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs uppercase text-slate-400">From</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $returnDepartureAirport?->code ?? $arrivalAirport?->code ?? '-' }}</p>
                                    <p class="text-sm text-slate-500">{{ $returnDepartureAirport?->name ?? '' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-slate-400">Return Date</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $ticket->return_date?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-sm text-slate-500">{{ $ticket->return_departure_time ?? '-' }} → {{ $ticket->return_arrival_time ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-slate-400">To</p>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $returnArrivalAirport?->code ?? $departureAirport?->code ?? '-' }}</p>
                                    <p class="text-sm text-slate-500">{{ $returnArrivalAirport?->name ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid md:grid-cols-4 gap-4">
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Baggage</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $ticket->baggage ?? 'Not specified' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Meal</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $ticket->meal ?? 'Not specified' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Refundable</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ $ticket->refundable ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <p class="text-xs uppercase text-slate-400">Seat(s)</p>
                            <p class="mt-2 font-semibold text-slate-900">{{ is_array($booking?->seat_numbers) ? implode(', ', $booking->seat_numbers) : ($booking?->seat_numbers ?? '-') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 p-5">
                    <h3 class="text-lg font-bold text-slate-900">Additional Services</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span>Visa</span>
                            <span class="font-semibold">{{ $visaIncluded ? 'Included' : 'Not Included' }}</span>
                        </div>
                        @if($visaIncluded)
                            <div class="text-sm text-slate-500">Visa Price: SAR {{ number_format($booking?->visa_price ?? 0, 2) }}</div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span>Transport</span>
                            <span class="font-semibold">{{ $transportIncluded ? 'Included' : 'Not Included' }}</span>
                        </div>
                        @if($transportIncluded)
                            <div class="text-sm text-slate-500">Transport Price: SAR {{ number_format($booking?->transport_price ?? 0, 2) }}</div>
                        @endif

                        @if(! empty($voucher->transport_type))
                            <div class="text-sm text-slate-700">Transport Type: <span class="font-semibold">{{ $voucher->transport_type }}</span></div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 p-5">
                    <h3 class="text-lg font-bold text-slate-900">Fare Summary</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div class="flex justify-between">
                            <span>Base Fare</span>
                            <span>SAR {{ number_format($booking?->price ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxes</span>
                            <span>SAR {{ number_format($booking?->taxes ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Service Charge</span>
                            <span>SAR {{ number_format($booking?->service_charge ?? 0, 2) }}</span>
                        </div>
                        @if($visaIncluded)
                            <div class="flex justify-between">
                                <span>Visa</span>
                                <span>SAR {{ number_format($booking?->visa_price ?? 0, 2) }}</span>
                            </div>
                        @endif
                        @if($transportIncluded)
                            <div class="flex justify-between">
                                <span>Transport</span>
                                <span>SAR {{ number_format($booking?->transport_price ?? 0, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t pt-3 flex justify-between font-bold text-slate-900">
                            <span>Grand Total</span>
                            <span>SAR {{ number_format($booking?->grand_total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
