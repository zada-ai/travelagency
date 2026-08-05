@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8 px-4">

    {{-- PAGE TITLE --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">
            My Bookings
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            View your flight and Umrah package bookings.
        </p>
    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- ===================================================== --}}
    {{-- FLIGHT BOOKINGS --}}
    {{-- ===================================================== --}}

    <div class="mb-10">

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-xl font-bold text-slate-900">
                ✈️ Flight Bookings
            </h2>

            <span class="text-sm text-slate-500">
                {{ $bookings->count() }} booking(s)
            </span>

        </div>


        @if($bookings->isEmpty())

            <div class="p-5 bg-white border border-slate-200 rounded-xl text-slate-500">
                You have no flight bookings yet.
            </div>

        @else

            <div class="space-y-4">

                @foreach($bookings as $b)

                    <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">

                        <div class="flex justify-between items-center gap-4">

                            {{-- LEFT --}}
                            <div>

                                <div class="font-bold text-lg text-slate-900">

                                    {{ $b->ticket->airline ?? 'Airline' }}

                                    -

                                    {{ $b->ticket->flight_number ?? '' }}

                                </div>

                                <div class="text-sm text-slate-500 mt-1">
                                    {{ $b->ticket->route ?? 'Flight Route' }}
                                </div>

                                @if($b->reference)
                                    <div class="text-xs text-slate-400 mt-2">
                                        Reference:
                                        {{ $b->reference }}
                                    </div>
                                @endif

                            </div>


                            {{-- RIGHT --}}
                            <div class="text-right">

                                <div class="font-bold text-lg text-slate-900">
                                    SAR {{ number_format($b->grand_total ?? $b->price ?? 0, 2) }}
                                </div>

                                <div class="text-sm text-slate-500">
                                    {{ $b->created_at->format('d M Y') }}
                                </div>

                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                                    @if($b->status === 'Confirmed')
                                        bg-green-100 text-green-700
                                    @elseif($b->status === 'Cancelled' || $b->status === 'Rejected')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-yellow-100 text-yellow-700
                                    @endif
                                ">
                                    {{ $b->status }}
                                </span>

                                @if($b->status === 'Approved')
                                    <div class="mt-3">
                                        <a href="{{ route('customer.vouchers.show', ['flightBooking' => $b->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">View Voucher</a>
                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>



    {{-- ===================================================== --}}
    {{-- UMRAH PACKAGE BOOKINGS --}}
    {{-- ===================================================== --}}

    <div>

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-xl font-bold text-slate-900">
                🕋 Umrah Package Bookings
            </h2>

            <span class="text-sm text-slate-500">
                {{ $packageBookings->count() }} booking(s)
            </span>

        </div>


        @if($packageBookings->isEmpty())

            <div class="p-5 bg-white border border-slate-200 rounded-xl text-slate-500">
                You have no Umrah package bookings yet.
            </div>

        @else

            <div class="space-y-4">

                @foreach($packageBookings as $booking)

                    <div class="p-5 border border-slate-200 rounded-xl bg-white shadow-sm">

                        <div class="flex justify-between items-center gap-4">

                            {{-- LEFT --}}
                            <div>

                                <div class="font-bold text-lg text-slate-900">

                                    {{ $booking->package->name ?? 'Umrah Package' }}

                                </div>

                                <div class="text-sm text-slate-500 mt-1">

                                    Booking Reference:

                                    <span class="font-medium text-slate-700">
                                        {{ $booking->reference_number }}
                                    </span>

                                </div>


                                <div class="text-sm text-slate-500 mt-2">

                                    {{ $booking->adults }} Adult(s)

                                    @if($booking->children > 0)
                                        · {{ $booking->children }} Child(ren)
                                    @endif

                                    @if($booking->infants > 0)
                                        · {{ $booking->infants }} Infant(s)
                                    @endif

                                </div>


                                <div class="text-xs text-slate-400 mt-2">

                                    {{ $booking->created_at->format('d M Y') }}

                                </div>

                            </div>


                            {{-- RIGHT --}}
                            <div class="text-right">

                                <div class="font-bold text-lg text-slate-900">

                                    SAR {{ number_format($booking->total_price, 2) }}

                                </div>


                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold
                                    @if($booking->status === 'Confirmed')
                                        bg-green-100 text-green-700
                                    @elseif($booking->status === 'Cancelled' || $booking->status === 'Rejected')
                                        bg-red-100 text-red-700
                                    @else
                                        bg-yellow-100 text-yellow-700
                                    @endif
                                ">

                                    {{ $booking->status }}
                                    @if($booking->status === 'Approved')
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <a
                                                href="{{ route('customer.package-bookings.voucher', $booking->id) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition"
                                            >
                                                Old Voucher
                                            </a>
                                            <a
                                                href="{{ route('customer.vouchers.package.show', ['packageBooking' => $booking->id]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-500 transition"
                                            >
                                                New Voucher
                                            </a>
                                        </div>
                                    @endif

                                </span>
                                {{-- VOUCHER --}}


                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</div>

@endsection