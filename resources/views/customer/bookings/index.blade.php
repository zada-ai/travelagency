@php
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = auth()->guard('travel_agent')->user() ?? $currentUser;
    $hasWebUser = auth()->check();
    $hasTravelAgentUser = auth()->guard('travel_agent')->check();
    $isCustomer = (bool) ($hasWebUser && ! $hasTravelAgentUser);
    $isVisaOfficer = false;
    $userRole = $hasTravelAgentUser ? 'travel_agent' : 'customer';

    if (! $isCustomer && ! $hasTravelAgentUser && ! $hasWebUser) {
        $isCustomer = true;
        $userRole = 'customer';
    }

    $portalLabel = $isCustomer ? 'Customer Portal' : 'Agent Portal';
    $portalSystemLabel = $isCustomer ? 'Customer Portal System' : 'Agent Portal System';
@endphp

@extends('layouts.dashboard')

@section('content')

<div class="space-y-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

```
    {{-- PAGE HEADER --}}
    <div class="mb-7">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">
                    Customer Portal
                </p>

                <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    My Bookings
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    View your flight and Umrah package bookings, statuses, and available vouchers.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 w-fit rounded-xl bg-white border border-slate-200 px-4 py-2.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-sm font-semibold text-slate-700">
                    Booking History
                </span>
            </div>

        </div>
    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- SUMMARY CARDS --}}
    @php
        $flightCount = $bookings->count();
        $packageCount = $packageBookings->count();
        $hotelCount = isset($hotelBookings) ? $hotelBookings->count() : 0;
        $totalCount = $flightCount + $packageCount + $hotelCount;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">

        {{-- Total --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Total Bookings
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $totalCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        All booking types
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 114 0m-4 0h4"/>
                    </svg>
                </div>

            </div>
        </div>


        {{-- Flights --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Flight Bookings
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $flightCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Airline reservations
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7zM6 12.5h10"/>
                    </svg>
                </div>

            </div>
        </div>


        {{-- Packages --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Umrah Packages
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $packageCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Package reservations
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.4-3.9h6.7L12 2z"/>
                    </svg>
                </div>

            </div>
        </div>
        
        {{-- Hotels --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Hotel Bookings
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $hotelCount }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Hotel reservations
                    </p>
                </div>

                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                    </svg>
                </div>

            </div>
        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- FLIGHT BOOKINGS --}}
    {{-- ===================================================== --}}

    {{-- ===================================================== --}}
    {{-- HOTEL BOOKINGS --}}
    {{-- ===================================================== --}}

    <section class="mb-8 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

        {{-- Section Header --}}
        <div class="px-5 sm:px-6 py-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Hotel Bookings
                        </h2>

                        <p class="text-sm text-slate-500">
                            Your hotel reservations
                        </p>
                    </div>

                </div>

                <span class="inline-flex w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    {{ $hotelCount }} Booking{{ $hotelCount != 1 ? 's' : '' }}
                </span>

            </div>

        </div>


        @if(empty($hotelBookings) || $hotelBookings->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                    </svg>
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    No hotel bookings yet
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Your hotel reservations will appear here.
                </p>

            </div>

        @else

            <div class="divide-y divide-slate-100">

                @foreach($hotelBookings as $h)

                    <div class="p-5 sm:px-6 hover:bg-slate-50 transition">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                            <div class="flex items-start gap-4 min-w-0">

                                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <h3 class="font-bold text-slate-900 text-base sm:text-lg truncate">
                                        {{ $h->hotel->hotel_name ?? 'Hotel' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-600">
                                        Booking #{{ $h->id }} · {{ optional($h->created_at)->format('d M Y') }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400">

                                        <span>
                                            Reference:
                                            <strong class="text-slate-600">
                                                {{ $h->reference_number ?? '-' }}
                                            </strong>
                                        </span>

                                        <span>
                                            {{ $h->check_in?->format('d M Y') }} — {{ $h->check_out?->format('d M Y') }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                <div class="text-left sm:text-right">

                                    <p class="text-lg font-bold text-slate-900">
                                        SAR {{ number_format($h->grand_total ?? 0, 2) }}
                                    </p>

                                    <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($h->status) === 'cancelled' ? 'bg-red-50 text-red-700' : (in_array(strtolower($h->status), ['confirmed','approved']) ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700') }}">
                                        {{ $h->status }}
                                    </span>

                                </div>

                                @php
                                    $canViewVoucher = false;

                                    if(auth()->guard('web')->check()) {
                                        $u = auth()->guard('web')->user();
                                        if(!empty($u->email) && $u->email === $h->contact_email) $canViewVoucher = true;
                                        if(!$canViewVoucher && !empty($u->phone) && $u->phone === $h->contact_phone) $canViewVoucher = true;
                                        if(!$canViewVoucher && isset($h->user_id) && $h->user_id === $u->id) $canViewVoucher = true;
                                    }

                                    if(auth()->guard('travel_agent')->check()) {
                                        $a = auth()->guard('travel_agent')->user();
                                        if(isset($h->travel_agent_id) && $h->travel_agent_id === $a->id) $canViewVoucher = true;
                                    }
                                @endphp

                                @if($canViewVoucher)
                                    <a href="{{ route('customer.bookings.hotel.voucher', ['booking' => $h->id]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-semibold hover:bg-amber-700 transition">
                                        View Voucher
                                    </a>
                                @else
                                    <a href="{{ route('hotels.booking.confirmation', ['booking' => $h->id]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-700 transition">
                                        View Details
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200">
                {{-- No pagination for hotels currently (collection) --}}
            </div>

        @endif

    </section>

    <section class="mb-8 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

        {{-- Section Header --}}
        <div class="px-5 sm:px-6 py-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7zM6 12.5h10"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Flight Bookings
                        </h2>

                        <p class="text-sm text-slate-500">
                            Your airline reservations
                        </p>
                    </div>

                </div>

                <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">
                    {{ $flightCount }} Booking{{ $flightCount != 1 ? 's' : '' }}
                </span>

            </div>

        </div>


        @if($bookings->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-slate-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7z"/>
                    </svg>
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    No flight bookings yet
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Your flight reservations will appear here.
                </p>

            </div>

        @else

            <div class="divide-y divide-slate-100">

                @foreach($bookings as $b)

                    @php
                        $flightStatus = strtolower((string) $b->status);

                        $flightStatusClass = match($flightStatus) {
                            'confirmed', 'approved' => 'bg-emerald-50 text-emerald-700',
                            'cancelled', 'rejected' => 'bg-red-50 text-red-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp

                    <div class="p-5 sm:px-6 hover:bg-slate-50 transition">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                            {{-- LEFT --}}
                            <div class="flex items-start gap-4 min-w-0">

                                <div class="w-11 h-11 shrink-0 rounded-xl bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7zM6 12.5h10"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <h3 class="font-bold text-slate-900 text-base sm:text-lg truncate">
                                        {{ $b->ticket->airline ?? 'Airline' }}
                                        @if(!empty($b->ticket?->flight_number))
                                            <span class="text-slate-400">·</span>
                                            {{ $b->ticket->flight_number }}
                                        @endif
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $b->ticket->route ?? 'Flight Route' }}
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400">

                                        @if($b->reference)
                                            <span>
                                                Reference:
                                                <strong class="text-slate-600">
                                                    {{ $b->reference }}
                                                </strong>
                                            </span>
                                        @endif

                                        <span>
                                            {{ optional($b->created_at)->format('d M Y') }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- RIGHT --}}
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                <div class="text-left sm:text-right">

                                    <p class="text-lg font-bold text-slate-900">
                                        SAR {{ number_format($b->grand_total ?? $b->price ?? 0, 2) }}
                                    </p>

                                    <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-semibold {{ $flightStatusClass }}">
                                        {{ $b->status }}
                                    </span>

                                </div>

                                @if($b->status === 'Approved')

                                    <a href="{{ route('customer.vouchers.show', ['flightBooking' => $b->id]) }}"
                                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">

                                        <svg class="w-4 h-4" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M9 12.75L11.25 15 15 9.75"/>
                                        </svg>

                                        View Voucher
                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </section>


    {{-- ===================================================== --}}
    {{-- UMRAH PACKAGE BOOKINGS --}}
    {{-- ===================================================== --}}

    <section class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

        {{-- Section Header --}}
        <div class="px-5 sm:px-6 py-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.4-3.9h6.7L12 2z"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Umrah Package Bookings
                        </h2>

                        <p class="text-sm text-slate-500">
                            Your package reservations and vouchers
                        </p>
                    </div>

                </div>

                <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    {{ $packageCount }} Booking{{ $packageCount != 1 ? 's' : '' }}
                </span>

            </div>

        </div>


        @if($packageBookings->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-slate-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.5-3.9h6.7L12 2z"/>
                    </svg>
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    No Umrah package bookings yet
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Your Umrah package reservations will appear here.
                </p>

            </div>

        @else

            <div class="divide-y divide-slate-100">

                @foreach($packageBookings as $booking)

                    @php
                        $packageStatus = strtolower((string) $booking->status);

                        $packageStatusClass = match($packageStatus) {
                            'confirmed', 'approved' => 'bg-emerald-50 text-emerald-700',
                            'cancelled', 'rejected' => 'bg-red-50 text-red-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp

                    <div class="p-5 sm:px-6 hover:bg-slate-50 transition">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                            {{-- LEFT --}}
                            <div class="flex items-start gap-4 min-w-0">

                                <div class="w-11 h-11 shrink-0 rounded-xl bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.4-3.9h6.7L12 2z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">

                                    <h3 class="font-bold text-slate-900 text-base sm:text-lg truncate">
                                        {{ $booking->package->name ?? 'Umrah Package' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-600">
                                        Reference:
                                        <span class="font-semibold text-slate-800">
                                            {{ $booking->reference_number ?? '-' }}
                                        </span>
                                    </p>

                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400">

                                        <span>
                                            {{ $booking->adults }} Adult{{ $booking->adults != 1 ? 's' : '' }}
                                        </span>

                                        @if($booking->children > 0)
                                            <span>
                                                {{ $booking->children }} Child{{ $booking->children != 1 ? 'ren' : '' }}
                                            </span>
                                        @endif

                                        @if($booking->infants > 0)
                                            <span>
                                                {{ $booking->infants }} Infant{{ $booking->infants != 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        <span>
                                            {{ optional($booking->created_at)->format('d M Y') }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- RIGHT --}}
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                <div class="text-left sm:text-right">

                                    <p class="text-lg font-bold text-slate-900">
                                        SAR {{ number_format($booking->total_price ?? 0, 2) }}
                                    </p>

                                    <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-semibold {{ $packageStatusClass }}">
                                        {{ $booking->status }}
                                    </span>

                                </div>


                                @if($booking->status === 'Approved')

                                    <div class="flex flex-col sm:flex-row gap-2">

                                        <a href="{{ route('customer.package-bookings.voucher', $booking->id) }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 transition">

                                            <svg class="w-4 h-4" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>

                                            Old Voucher
                                        </a>

                                        <a href="{{ route('customer.vouchers.package.show', ['packageBooking' => $booking->id]) }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">

                                            <svg class="w-4 h-4" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M9 12.75L11.25 15 15 9.75"/>
                                            </svg>

                                            New Voucher
                                        </a>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </section>

</div>
```

</div>

@endsection
