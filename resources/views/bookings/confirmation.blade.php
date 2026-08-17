@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 py-8 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Success Card --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Top Success Banner --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 sm:px-8 py-8 text-white">

                <div class="flex flex-col items-center text-center">

                    {{-- Success Icon --}}
                    <div class="w-20 h-20 rounded-full bg-white/15 border border-white/20 flex items-center justify-center mb-5">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2.5"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm font-bold uppercase tracking-[0.2em] text-emerald-100">
                        Booking Successful
                    </p>

                    <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Booking Confirmed
                    </h1>

                    <p class="mt-3 max-w-xl text-sm sm:text-base text-emerald-50 leading-relaxed">
                        Your flight booking has been created successfully.
                        Your booking details are available below.
                    </p>

                </div>
            </div>


            {{-- Booking Details --}}
            <div class="p-6 sm:p-8">

                {{-- Booking Reference --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                Booking ID
                            </p>

                            <p class="mt-1 text-2xl font-extrabold text-slate-900">
                                #{{ $flightBooking->id }}
                            </p>
                        </div>

                        <div class="inline-flex items-center gap-2 w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">

                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                            Confirmed

                        </div>

                    </div>

                </div>


                {{-- Confirmation Message --}}
                <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-5">

                    <div class="flex items-start gap-3">

                        <div class="w-9 h-9 shrink-0 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-900">
                                Booking Successfully Created
                            </h2>

                            <p class="mt-1 text-sm text-slate-600 leading-relaxed">
                                Your flight booking has been recorded in the system.
                                You can view your booking details and voucher from My Bookings.
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Quick Info --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Booking Number --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Booking Number
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-900 break-all">
                            #{{ $flightBooking->id }}
                        </p>
                    </div>

                    {{-- Created Date --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Created On
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ optional($flightBooking->created_at)->format('d M Y, h:i A') ?? 'N/A' }}
                        </p>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="mt-8 pt-6 border-t border-slate-200">

                    <div class="flex flex-col sm:flex-row gap-3">

                        <a href="{{ route('customer.bookings') }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-700 transition">

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
                            </svg>

                            Back to My Bookings

                        </a>

                        <a href="{{ route('customer.bookings') }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">

                            View Booking

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- Footer Note --}}
        <p class="mt-5 text-center text-xs text-slate-400">
            Keep your booking number <strong>#{{ $flightBooking->id }}</strong> for future reference.
        </p>

    </div>
</div>

@endsection