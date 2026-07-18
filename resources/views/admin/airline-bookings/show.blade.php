@extends('admin.layouts.airline')

@section('title', 'Airline Booking Details')
@section('page-heading', 'Airline Booking Details')
@section('page-description', 'Review the booking record and update status as needed.')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Booking #{{ $flightBooking->id }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Flight: {{ $flightBooking->ticket->flight_number ?? '-' }} · {{ $flightBooking->ticket->route ?? '-' }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $flightBooking->status }}</span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Agent: {{ $flightBooking->agent->company_name ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Total Passengers</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ $flightBooking->total_passengers }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Seats</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ implode(', ', $flightBooking->seat_numbers ?? []) }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Cabin Class</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ $flightBooking->cabin_class }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Grand Total</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">SAR {{ number_format($flightBooking->grand_total, 2) }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Contact</h3>
                    <p class="mt-4 text-slate-900">{{ $flightBooking->contact_name }}</p>
                    <p class="text-sm text-slate-500">{{ $flightBooking->contact_email }}</p>
                    <p class="text-sm text-slate-500">{{ $flightBooking->contact_phone }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Selected Seats</h3>
                    <p class="mt-4 text-slate-900">{{ implode(', ', $flightBooking->seat_numbers ?? []) }}</p>
                    <h3 class="mt-6 text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Special Requests</h3>
                    <p class="mt-4 text-slate-900">{{ $flightBooking->special_requests ?? 'None' }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Passenger Details</h3>
                <div class="mt-4 grid gap-4">
                    @foreach($flightBooking->passenger_details ?? [] as $passenger)
                        <div class="rounded-3xl bg-white p-4 shadow-sm">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}</p>
                                    <p class="text-sm text-slate-500">{{ $passenger['passenger_type'] ?? '' }}</p>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <p>{{ $passenger['gender'] ?? '' }}</p>
                                    <p>{{ $passenger['date_of_birth'] ?? '' }}</p>
                                </div>
                            </div>
                            <div class="mt-3 grid gap-4 sm:grid-cols-3 text-sm text-slate-600">
                                <div>
                                    <p class="font-semibold text-slate-900">Passport</p>
                                    <p>{{ $passenger['passport_number'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Nationality</p>
                                    <p>{{ $passenger['nationality'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Age</p>
                                    <p>{{ $passenger['age'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @if($flightBooking->status !== 'Confirmed')
                    <form action="{{ route('admin.airline-bookings.status.update', $flightBooking) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Confirmed">
                        <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Confirm</button>
                    </form>
                @endif
                @if(! in_array($flightBooking->status, ['Cancelled', 'Rejected'], true))
                    <form action="{{ route('admin.airline-bookings.status.update', $flightBooking) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="rounded-2xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-600">Reject</button>
                    </form>
                @endif
                <form action="{{ route('admin.airline-bookings.destroy', $flightBooking) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-slate-700 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-600">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
