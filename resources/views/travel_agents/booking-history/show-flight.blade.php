@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('travel-agents.booking-history.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Bookings
        </a>

        {{-- Header --}}
        <div class="mt-5 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            <div class="p-5 sm:p-7 bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.5 19.5l19-7-19-7 3.5 7-3.5 7zM6 12.5h10"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-blue-100">
                                Flight Booking
                            </p>

                            <h1 class="text-2xl sm:text-3xl font-bold">
                                Booking #{{ $booking->id }}
                            </h1>
                        </div>

                    </div>

                    <span class="inline-flex w-fit px-4 py-2 rounded-full bg-white/15 border border-white/20 text-sm font-semibold">
                        {{ $booking->status }}
                    </span>

                </div>
            </div>

            {{-- Booking Details --}}
            <div class="p-5 sm:p-7">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Airline --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Airline
                        </p>
                        <p class="mt-1 text-base font-bold text-slate-900">
                            {{ $booking->ticket->airline ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Reference --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Booking Reference
                        </p>
                        <p class="mt-1 text-base font-bold text-slate-900 break-all">
                            {{ $booking->reference ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Route --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Route
                        </p>

                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 2v20M5 12l7-10 7 10M5 12l7 10 7-10"/>
                                    </svg>
                                </div>

                                <span class="text-base font-bold text-slate-900">
                                    {{ $booking->ticket->route ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Departure --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Departure Date
                        </p>

                        <p class="mt-1 text-base font-bold text-slate-900">
                            @if($booking->ticket?->departure_date)
                                {{ $booking->ticket->departure_date->format('d M Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Booking Status
                        </p>

                        <div class="mt-2">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
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

                {{-- Footer --}}
                <div class="mt-7 pt-5 border-t border-slate-200 flex flex-col sm:flex-row gap-3">

                    <a href="{{ route('travel-agents.booking-history.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Bookings
                    </a>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection