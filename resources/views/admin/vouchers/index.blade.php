@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Voucher Management</h1>
            <p class="text-sm text-slate-500 mt-1">
                Manage new flight and package vouchers separately from the legacy voucher system.
            </p>
        </div>

        <form method="GET"
              action="{{ route('admin.vouchers.index') }}"
              class="flex flex-wrap items-center gap-2">

            <input
                name="q"
                value="{{ request('q') }}"
                placeholder="Search voucher, customer, booking ref"
                class="border rounded-lg px-3 py-2"
            />

            <select name="type" class="border rounded-lg px-3 py-2">
                <option value="">All types</option>
                <option value="flight" {{ request('type') === 'flight' ? 'selected' : '' }}>
                    Flight
                </option>
                <option value="package" {{ request('type') === 'package' ? 'selected' : '' }}>
                    Package
                </option>
            </select>

            <button class="px-4 py-2 rounded-lg bg-slate-900 text-white">
                Filter
            </button>
        </form>
    </div>


    {{-- ========================================================= --}}
    {{-- FLIGHT BOOKINGS READY FOR VOUCHER --}}
    {{-- ========================================================= --}}

    <div class="bg-white border rounded-xl p-6 mb-8">

        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    Confirmed Flight Bookings - Generate New Voucher
                </h2>

                <p class="text-sm text-slate-500">
                    Confirmed flight bookings without a new voucher are shown here.
                    Admin can generate the voucher directly.
                </p>
            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="text-left bg-slate-950 text-white">
                        <th class="p-3">Booking Ref</th>
                        <th class="p-3">Customer</th>
                        <th class="p-3">Flight / Airline</th>
                        <th class="p-3">Amount</th>
                        <th class="p-3">Booking Date</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>


                <tbody>

                @forelse($approvedFlightBookings as $booking)

                    <tr class="border-t hover:bg-slate-50">

                        {{-- Booking Ref --}}
                        <td class="p-3 font-semibold">
                            {{ $booking->reference ?? 'N/A' }}
                        </td>


                        {{-- Customer --}}
                        <td class="p-3">

                            {{ $booking->contact_name
                                ?? optional($booking->user)->name
                                ?? 'N/A'
                            }}

                            @if($booking->contact_email)
                                <div class="text-xs text-slate-400">
                                    {{ $booking->contact_email }}
                                </div>
                            @endif

                        </td>


                        {{-- Flight --}}
                        <td class="p-3">

                            @if($booking->ticket)

                                <div class="font-semibold">
                                    {{ $booking->ticket->flight_number ?? '-' }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $booking->ticket->airline ?? '-' }}
                                </div>

                            @else

                                <span class="text-slate-400">
                                    Flight information unavailable
                                </span>

                            @endif

                        </td>


                        {{-- Amount --}}
                        <td class="p-3 font-semibold">
                            SAR {{ number_format((float) $booking->grand_total, 2) }}
                        </td>


                        {{-- Booking Date --}}
                        <td class="p-3">

                            {{ $booking->created_at
                                ? $booking->created_at->format('d M Y')
                                : '-'
                            }}

                        </td>


                        {{-- Status --}}
                        <td class="p-3">

                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                {{ $booking->status }}
                            </span>

                        </td>


                        {{-- Action --}}
                        <td class="p-3">

                            @if($booking->voucher)

                                <div class="flex flex-wrap gap-2">

                                    <a
                                        href="{{ route('admin.vouchers.show', ['voucher' => $booking->voucher->id]) }}"
                                        class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-500"
                                    >
                                        View Voucher
                                    </a>


                                    <a
                                        href="{{ route('admin.vouchers.download', ['voucher' => $booking->voucher->id]) }}"
                                        class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                    >
                                        Download PDF
                                    </a>


                                    <button
                                        type="button"
                                        onclick="window.open('{{ route('admin.vouchers.download', ['voucher' => $booking->voucher->id]) }}', '_blank')"
                                        class="rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-slate-950 hover:bg-emerald-400"
                                    >
                                        Print
                                    </button>

                                </div>

                            @else

                                <form
                                    action="{{ route('admin.vouchers.generate.flight', $booking) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500"
                                    >
                                        Generate Voucher
                                    </button>
                                </form>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="p-8 text-center">

                            <div class="text-slate-400 text-3xl mb-2">
                                ✈️
                            </div>

                            <div class="font-semibold text-slate-700">
                                No confirmed flight bookings found.
                            </div>

                            <div class="text-sm text-slate-500 mt-1">
                                Once a flight booking is Confirmed, it will appear here.
                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- GENERATED VOUCHERS --}}
    {{-- ========================================================= --}}

    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-lg font-semibold">
                Generated Vouchers
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                All vouchers generated through the new voucher system.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="text-left bg-slate-50">

                        <th class="p-3">Voucher</th>
                        <th class="p-3">Booking Ref</th>
                        <th class="p-3">Customer</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Created</th>
                        <th class="p-3">Actions</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($vouchers as $v)

                    @php
                        $flightBooking = $v->flightBooking;
                        $packageBooking = $v->packageBooking;

                        $booking = $flightBooking ?? $packageBooking;

                        $customerName =
                            optional($flightBooking)->contact_name
                            ?? optional(optional($flightBooking)->user)->name
                            ?? optional(optional($packageBooking)->user)->name
                            ?? 'N/A';

                        $bookingReference =
                            optional($flightBooking)->reference
                            ?? optional($packageBooking)->reference_number
                            ?? 'N/A';

                        $type = $flightBooking ? 'Flight' : 'Package';
                    @endphp


                    <tr class="border-t hover:bg-slate-50">


                        {{-- Voucher --}}
                        <td class="p-3 font-semibold">
                            {{ $v->voucher_number }}
                        </td>


                        {{-- Booking Ref --}}
                        <td class="p-3">
                            {{ $bookingReference }}
                        </td>


                        {{-- Customer --}}
                        <td class="p-3">
                            {{ $customerName }}
                        </td>


                        {{-- Type --}}
                        <td class="p-3">

                            @if($type === 'Flight')

                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    Flight
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                                    Package
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td class="p-3">
                            {{ $v->status }}
                        </td>


                        {{-- Created --}}
                        <td class="p-3">

                            {{ $v->created_at
                                ? $v->created_at->format('d M Y')
                                : '-'
                            }}

                        </td>


                        {{-- Actions --}}
                        <td class="p-3">

                            <div class="flex flex-wrap gap-3">

                                <a
                                    href="{{ route('admin.vouchers.show', ['voucher' => $v->id]) }}"
                                    class="text-blue-600 font-semibold hover:underline"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route('admin.vouchers.download', ['voucher' => $v->id]) }}"
                                    class="text-slate-700 font-semibold hover:underline"
                                >
                                    Download
                                </a>


                                <button
                                    type="button"
                                    onclick="window.open('{{ route('admin.vouchers.download', ['voucher' => $v->id]) }}', '_blank')"
                                    class="text-green-600 font-semibold hover:underline"
                                >
                                    Print
                                </button>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="7" class="p-8 text-center text-slate-500">
                            No vouchers have been generated yet.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="p-4">
            {{ $vouchers->links() }}
        </div>

    </div>

</div>
@endsection