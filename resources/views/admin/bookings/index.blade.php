@extends('admin.layouts.app')

@section('title', 'Bookings')
@section('page-heading', 'Booking Management')
@section('page-description', 'Review hotel booking records, search reservations, and manage booking statuses.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.3fr_0.8fr_0.7fr_0.7fr_0.7fr]">
                <div class="col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Search</label>
                    <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Reference, guest name, hotel or email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel</label>
                    <select name="hotel_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">Any Hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(($filters['hotel_id'] ?? '') == $hotel->id)>{{ $hotel->hotel_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">Any Status</option>
                        <option value="Pending" @selected(($filters['status'] ?? '') === 'Pending')>Pending</option>
                        <option value="Reserved" @selected(($filters['status'] ?? '') === 'Reserved')>Reserved</option>
                        <option value="Cancelled" @selected(($filters['status'] ?? '') === 'Cancelled')>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Check-in from</label>
                    <input type="date" name="check_in_from" value="{{ $filters['check_in_from'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Check-in to</label>
                    <input type="date" name="check_in_to" value="{{ $filters['check_in_to'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Filter</button>
                    <a href="{{ route('admin.bookings.index') }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Bookings</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $bookings->total() }} bookings found.</p>
                </div>
                <a href="{{ route('admin.bookings.export') }}?{{ http_build_query(request()->query()) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Export CSV</a>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-4 py-4 font-semibold">Reference</th>
                            <th class="px-4 py-4 font-semibold">Hotel</th>
                            <th class="px-4 py-4 font-semibold">Guest</th>
                            <th class="px-4 py-4 font-semibold">Room Type</th>
                            <th class="px-4 py-4 font-semibold">Check In</th>
                            <th class="px-4 py-4 font-semibold">Check Out</th>
                            <th class="px-4 py-4 font-semibold">Guests</th>
                            <th class="px-4 py-4 font-semibold">Status</th>
                            <th class="px-4 py-4 font-semibold">Total</th>
                            <th class="px-4 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="px-4 py-4 font-medium text-slate-900">{{ $booking->reference_number }}</td>
                                <td class="px-4 py-4">{{ $booking->hotel->hotel_name ?? 'Unknown' }}</td>
                                <td class="px-4 py-4">{{ $booking->contact_name }}<br><span class="text-xs text-slate-500">{{ $booking->contact_email }}</span></td>
                                <td class="px-4 py-4">{{ $booking->roomType->room_name ?? 'Unknown' }}</td>
                                <td class="px-4 py-4">{{ $booking->check_in->format('Y-m-d') }}</td>
                                <td class="px-4 py-4">{{ $booking->check_out->format('Y-m-d') }}</td>
                                <td class="px-4 py-4">{{ $booking->total_passengers }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $booking->status === 'Cancelled' ? 'bg-rose-100 text-rose-700' : ($booking->status === 'Reserved' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">AED {{ number_format($booking->grand_total, 2) }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-2xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">View</a>
                                        @if($booking->status !== 'Cancelled')
                                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Cancel this booking?');">
                                                @csrf
                                                <button type="submit" class="rounded-2xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600">Cancel</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-500">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection
