@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('travel-agents.booking-history.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Bookings
        </a>

        {{-- Main Card --}}
        <div class="mt-5 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="p-5 sm:p-7 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m-2 4h2m4-4h2m-2 4h2"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-blue-100">
                                Hotel Booking
                            </p>

                            <h1 class="text-2xl sm:text-3xl font-bold">
                                Booking #{{ $booking->id }}
                            </h1>
                        </div>

                    </div>

                    <div class="flex items-center gap-3">

                        <span class="inline-flex w-fit px-4 py-2 rounded-full
                            bg-white/15 border border-white/20
                            text-sm font-semibold">
                            {{ $booking->status }}
                        </span>

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

                        @if($canViewVoucher && in_array($booking->status, \App\Models\Booking::BOOKED_STATUSES, true))
                            <a href="{{ route('customer.bookings.hotel.voucher', ['booking' => $booking->id]) }}" class="inline-flex items-center justify-center gap-2 px-3 py-1 bg-amber-600 text-white rounded text-sm font-semibold hover:bg-amber-700 transition">
                                View Voucher
                            </a>
                        @endif

                    </div>

                </div>
            </div>

            {{-- Booking Information --}}
            <div class="p-5 sm:p-7">

                <h2 class="text-lg font-bold text-slate-900 mb-4">
                    Booking Information
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Hotel --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Hotel
                        </p>

                        <div class="flex items-center gap-3 mt-2">

                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                                </svg>
                            </div>

                            <p class="text-base font-bold text-slate-900">
                                {{ $booking->hotel->hotel_name ?? 'N/A' }}
                            </p>

                        </div>
                    </div>

                    {{-- Reference --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Booking Reference
                        </p>

                        <p class="mt-1 text-base font-bold text-slate-900 break-all">
                            {{ $booking->reference_number ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Passengers --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Total Passengers
                        </p>

                        <p class="mt-1 text-base font-bold text-slate-900">
                            {{ $booking->total_passengers ?? 0 }}
                        </p>
                    </div>

                    {{-- Check In --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Check-in
                        </p>

                        <div class="flex items-center gap-3 mt-2">

                            <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>

                            <p class="font-bold text-slate-900">
                                @if($booking->check_in)
                                    {{ $booking->check_in->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </p>

                        </div>

                    </div>

                    {{-- Check Out --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Check-out
                        </p>

                        <div class="flex items-center gap-3 mt-2">

                            <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>

                            <p class="font-bold text-slate-900">
                                @if($booking->check_out)
                                    {{ $booking->check_out->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </p>

                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Booking Status
                        </p>

                        <div class="mt-2">

                            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold
                                {{ strtolower($booking->status) === 'cancelled'
                                    ? 'bg-red-50 text-red-700'
                                    : (in_array(strtolower($booking->status), ['confirmed', 'approved'])
                                        ? 'bg-green-50 text-green-700'
                                        : 'bg-amber-50 text-amber-700') }}">
                                {{ $booking->status }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Passengers --}}
                <div class="mt-8">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">

                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Passengers
                            </h2>

                            <p class="text-sm text-slate-500">
                                Guest information for this booking
                            </p>
                        </div>

                        <span class="inline-flex w-fit px-3 py-1 rounded-full
                            bg-slate-100 text-slate-700 text-xs font-bold">
                            {{ $booking->passengers->count() }} Passengers
                        </span>

                    </div>


                    @if($booking->passengers->isEmpty())

                        <div class="rounded-xl border border-dashed border-slate-300
                                    bg-slate-50 py-10 px-5 text-center">

                            <div class="w-12 h-12 mx-auto rounded-full bg-white
                                        border border-slate-200 flex items-center justify-center mb-3">

                                <svg class="w-6 h-6 text-slate-400"
                                     fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-8a4 4 0 100-8 4 4 0 000 8zm6-3a4 4 0 100-8 4 4 0 000 8zm0 0v3"/>
                                </svg>

                            </div>

                            <h3 class="font-semibold text-slate-900">
                                No passengers recorded
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Passenger information is not available for this booking.
                            </p>

                        </div>

                    @else

                        <div class="overflow-hidden rounded-xl border border-slate-200">

                            <div class="hidden sm:grid grid-cols-2 bg-slate-50
                                        border-b border-slate-200 px-5 py-3">

                                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Passenger Name
                                </div>

                                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Passport Number
                                </div>

                            </div>

                            <div class="divide-y divide-slate-100">

                                @foreach($booking->passengers as $index => $p)

                                    <div class="px-5 py-4 hover:bg-slate-50 transition">

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">

                                            {{-- Name --}}
                                            <div class="flex items-center gap-3">

                                                <div class="w-9 h-9 shrink-0 rounded-full
                                                            bg-blue-50 text-blue-600
                                                            flex items-center justify-center
                                                            text-sm font-bold">
                                                    {{ $index + 1 }}
                                                </div>

                                                <div>
                                                    <p class="text-xs text-slate-400 sm:hidden">
                                                        Passenger Name
                                                    </p>

                                                    <p class="font-semibold text-slate-900">
                                                        {{ $p->full_name ?? 'N/A' }}
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- Passport --}}
                                            <div class="pl-12 sm:pl-0">

                                                <p class="text-xs text-slate-400 sm:hidden">
                                                    Passport Number
                                                </p>

                                                <p class="text-sm font-medium text-slate-700 break-all">
                                                    {{ $p->passport_number ?? 'Passport N/A' }}
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>


                {{-- Footer --}}
                <div class="mt-8 pt-5 border-t border-slate-200">

                    <a href="{{ route('travel-agents.booking-history.index') }}"
                       class="inline-flex items-center justify-center gap-2
                              px-5 py-2.5 bg-slate-900 text-white
                              rounded-xl text-sm font-semibold
                              hover:bg-slate-700 transition">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 19l-7-7 7-7"/>
                        </svg>

                        Back to My Bookings

                    </a>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection