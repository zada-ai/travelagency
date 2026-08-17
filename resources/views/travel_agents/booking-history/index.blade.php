@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')


<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <p class="text-sm font-medium text-blue-600 mb-1">Travel Agent Portal</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                    My Bookings
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    View and manage your hotel and flight bookings.
                </p>
            </div>

            <a href="{{ route('travel-agents.dashboard') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                </svg>
                Dashboard
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

            {{-- Hotel --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Hotel Bookings
                        </p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">
                            {{ $hotelBookings->total() }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m-2 4h2m4-4h2m-2 4h2"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Flight --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Flight Bookings
                        </p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">
                            {{ $flightBookings->total() }}
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Package Bookings
                        </p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">
                            {{ $packageBookings->total() ?? 0 }}
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

        </div>

        {{-- Hotel Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">

            <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Hotel Bookings
                        </h2>
                        <p class="text-sm text-slate-500">
                            Your hotel reservation history
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                        {{ $hotelBookings->total() }} Total
                    </span>
                </div>
            </div>

            @if($hotelBookings->isEmpty())

                <div class="py-12 px-6 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-900">
                        No hotel bookings found
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Your hotel bookings will appear here.
                    </p>
                </div>

            @else

                <div class="divide-y divide-slate-100">
                    @foreach($hotelBookings as $booking)

                        <div class="p-5 sm:px-6 hover:bg-slate-50 transition">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div class="flex items-start gap-4 min-w-0">

                                    <div class="w-11 h-11 shrink-0 rounded-xl bg-blue-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="font-bold text-slate-900 truncate">
                                            {{ $booking->hotel->hotel_name ?? 'Hotel Booking' }}
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            Booking #{{ $booking->id }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Reference:
                                            <span class="font-medium text-slate-600">
                                                {{ $booking->reference_number ?? '-' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                    <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold
                                        {{ strtolower($booking->status) === 'cancelled'
                                            ? 'bg-red-50 text-red-700'
                                            : (strtolower($booking->status) === 'confirmed'
                                                ? 'bg-green-50 text-green-700'
                                                : 'bg-amber-50 text-amber-700') }}">
                                        {{ $booking->status }}
                                    </span>

                                    <a href="{{ route('travel-agents.booking-history.show-hotel', $booking->id) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-700 transition">
                                        View Details
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>

                                    @php
                                        $canViewVoucher = false;
                                        $user = auth()->guard('web')->user();
                                        $agentUser = auth()->guard('travel_agent')->user();

                                        if ($user) {
                                            if (!empty($user->email) && $user->email === $booking->contact_email) $canViewVoucher = true;
                                            if (!$canViewVoucher && !empty($user->phone) && $user->phone === $booking->contact_phone) $canViewVoucher = true;
                                            if (!$canViewVoucher && isset($booking->user_id) && $booking->user_id === $user->id) $canViewVoucher = true;
                                        }

                                        if ($agentUser) {
                                            if (isset($booking->travel_agent_id) && $booking->travel_agent_id === $agentUser->id) $canViewVoucher = true;
                                        }
                                    @endphp

                                    @if($canViewVoucher)
                                        <a href="{{ route('customer.bookings.hotel.voucher', ['booking' => $booking->id]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-semibold hover:bg-amber-700 transition">
                                            View Voucher
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-slate-200">
                    {{ $hotelBookings->links() }}
                </div>

            @endif
        </div>

        {{-- Flight Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Flight Bookings
                        </h2>
                        <p class="text-sm text-slate-500">
                            Your flight reservation history
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                        {{ $flightBookings->total() }} Total
                    </span>
                </div>
            </div>

            @if($flightBookings->isEmpty())

                <div class="py-12 px-6 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7z"/>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-900">
                        No flight bookings found
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Your flight bookings will appear here.
                    </p>
                </div>

            @else

                <div class="divide-y divide-slate-100">
                    @foreach($flightBookings as $b)

                        <div class="p-5 sm:px-6 hover:bg-slate-50 transition">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div class="flex items-start gap-4 min-w-0">

                                    <div class="w-11 h-11 shrink-0 rounded-xl bg-indigo-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7zM6 12.5h10"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="font-bold text-slate-900 truncate">
                                            {{ $b->ticket->airline ?? 'Flight Booking' }}
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            Booking #{{ $b->id }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Reference:
                                            <span class="font-medium text-slate-600">
                                                {{ $b->reference ?? '-' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                    <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold
                                        {{ strtolower($b->status) === 'cancelled'
                                            ? 'bg-red-50 text-red-700'
                                            : (in_array(strtolower($b->status), ['confirmed', 'approved'])
                                                ? 'bg-green-50 text-green-700'
                                                : 'bg-amber-50 text-amber-700') }}">
                                        {{ $b->status }}
                                    </span>

                                    <a href="{{ route('travel-agents.booking-history.show-flight', $b->id) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-700 transition">
                                        View Details
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-slate-200">
                    {{ $flightBookings->links() }}
                </div>

            @endif
        </div>

        {{-- Package Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mt-8">

            <div class="px-5 sm:px-6 py-5 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Package Bookings
                        </h2>

                        <p class="text-sm text-slate-500">
                            Your Umrah package reservations
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                        {{ $packageBookings->total() ?? 0 }} Total
                    </span>
                </div>
            </div>

            @if($packageBookings->isEmpty())

                <div class="py-12 px-6 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.4-3.9h6.7L12 2z"/>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-900">
                        No package bookings found
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Your package bookings will appear here.
                    </p>
                </div>

            @else

                <div class="divide-y divide-slate-100">
                    @foreach($packageBookings as $pkg)

                        <div class="p-5 sm:px-6 hover:bg-slate-50 transition">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div class="flex items-start gap-4 min-w-0">

                                    <div class="w-11 h-11 shrink-0 rounded-xl bg-emerald-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 2l2.1 6.4h6.7l-5.4 3.9 2.1 6.4-5.5-4-5.5 4 2.1-6.4-5.4-3.9h6.7L12 2z"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0">

                                        <h3 class="font-bold text-slate-900 truncate">
                                            {{ $pkg->package->title ?? 'Package Booking' }}
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            Booking #{{ $pkg->id }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Reference:
                                            <span class="font-medium text-slate-600">
                                                {{ $pkg->reference_number ?? '-' }}
                                            </span>
                                        </p>

                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">

                                    <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold
                                        {{ strtolower($pkg->status) === 'cancelled'
                                            ? 'bg-red-50 text-red-700'
                                            : (strtolower($pkg->status) === 'approved'
                                                ? 'bg-green-50 text-green-700'
                                                : 'bg-amber-50 text-amber-700') }}">
                                        {{ $pkg->status }}
                                    </span>

                                    <a href="{{ route('travel-agents.package-bookings.voucher', $pkg->id) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-black rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                                        View Voucher
                                    </a>

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-slate-200">
                    {{ $packageBookings->links() }}
                </div>

            @endif
        </div>

    </div>
</div>
@endsection