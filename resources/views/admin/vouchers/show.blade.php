@extends('layouts.app')

@section('content')
@php
    $booking = $voucher->flightBooking ?? $voucher->packageBooking;
    $isFlight = $voucher->flight_booking_id !== null;
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
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Flight Voucher</h1>
                    <p class="text-sm text-slate-500 mt-1">Voucher #: {{ $voucher->voucher_number }}</p>
                    <p class="text-sm text-slate-500">Issued: {{ optional($voucher->issued_at)->format('d M Y H:i') }}</p>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 text-white">Back</a>
                    <a href="{{ route('admin.vouchers.download', ['voucher' => $voucher->id]) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white">Download PDF</a>
                    <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white">Print</button>
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
                    <p class="mt-2 text-lg font-semibold text-slate-900">Flight</p>
                </div>
            </div>

            @if($isFlight && $ticket)
                <div class="grid gap-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Flight Details</h2>
                        <div class="mt-4 grid md:grid-cols-4 gap-4">
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <p class="text-xs uppercase text-slate-400">Airline</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $ticket->airline ?? $ticket->airlineMaster?->name ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <p class="text-xs uppercase text-slate-400">Flight Number</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $ticket->flight_number ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <p class="text-xs uppercase text-slate-400">Cabin Class</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $booking?->cabin_class ?? '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <p class="text-xs uppercase text-slate-400">Ticket Type</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $ticket->ticket_type ?? '-' }}</p>
                            </div>
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
