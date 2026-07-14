@extends('admin.layouts.app')

@section('title', 'Booking Details')
@section('page-heading', 'Booking Details')
@section('page-description', 'Review the full reservation details and manage booking status.')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">{{ $booking->reference_number }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Booked on {{ $booking->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $booking->status === 'Cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $booking->status }}</span>
                    @if($booking->status !== 'Cancelled')
                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Cancel this booking?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-600">Cancel Booking</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Guest</h3>
                    <p class="mt-4 text-sm text-slate-700">{{ $booking->contact_name }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->contact_email }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->contact_phone }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Stay</h3>
                    <p class="mt-4 text-sm text-slate-700">{{ $booking->hotel->hotel_name ?? '-' }}</p>
                    <p class="text-sm text-slate-500">Room: {{ $booking->roomType->room_name ?? '-' }}</p>
                    <p class="text-sm text-slate-500">Meal plan: {{ $booking->mealPlan?->meal_name ?? 'None' }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Dates & Guests</h3>
                    <p class="mt-4 text-sm text-slate-700">Check-in: {{ $booking->check_in->format('d M Y') }}</p>
                    <p class="text-sm text-slate-500">Check-out: {{ $booking->check_out->format('d M Y') }}</p>
                    <p class="text-sm text-slate-500">Adults: {{ $booking->adults }}</p>
                    <p class="text-sm text-slate-500">Children: {{ $booking->children }}</p>
                    <p class="text-sm text-slate-500">Infants: {{ $booking->infants }}</p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Booking Summary</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Room price</p>
                        <p class="mt-3 text-xl font-semibold text-slate-900">AED {{ number_format($booking->room_price, 2) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Meal price</p>
                        <p class="mt-3 text-xl font-semibold text-slate-900">AED {{ number_format($booking->meal_price, 2) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Taxes</p>
                        <p class="mt-3 text-xl font-semibold text-slate-900">AED {{ number_format($booking->taxes, 2) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Grand total</p>
                        <p class="mt-3 text-xl font-semibold text-slate-900">AED {{ number_format($booking->grand_total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Passenger Details</h2>
                <span class="text-sm text-slate-500">Total: {{ $booking->passengers->count() }}</span>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-4 py-4 font-semibold">Name</th>
                            <th class="px-4 py-4 font-semibold">Type</th>
                            <th class="px-4 py-4 font-semibold">Age</th>
                            <th class="px-4 py-4 font-semibold">Passport</th>
                            <th class="px-4 py-4 font-semibold">Nationality</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($booking->passengers as $passenger)
                            <tr>
                                <td class="px-4 py-4">{{ trim($passenger->first_name . ' ' . $passenger->last_name) ?: 'Passenger' }}</td>
                                <td class="px-4 py-4">{{ $passenger->passenger_type }}</td>
                                <td class="px-4 py-4">{{ $passenger->age ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $passenger->passport_number ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $passenger->nationality ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">No passenger records available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
