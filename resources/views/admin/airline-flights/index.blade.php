@extends('admin.layouts.airline')

@section('title', 'Flight Management')
@section('page-heading', 'Flight Management')
@section('page-description', 'Manage airline schedules, seat availability, and booking statistics.')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Flights</h2>
                <p class="mt-2 text-sm text-slate-500">All scheduled airline flights with seat availability and booking stats.</p>
            </div>
            <a href="{{ route('admin.airline-flights.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-400">Create Flight</a>
        </div>

        <div class="grid gap-4 lg:grid-cols-[260px_1fr]">
            <aside class="space-y-4 rounded-3xl border border-slate-200 bg-slate-950 p-5 text-slate-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Status Filter</h3>
                    <span class="rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-200">{{ $activeStatus }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($statuses as $key => $count)
                        <a href="{{ $key === 'All' ? route('admin.airline-flights.index') : route('admin.airline-flights.index', ['status' => $key]) }}" class="flex items-center justify-between rounded-3xl px-4 py-3 text-sm font-medium transition {{ $activeStatus === $key ? 'bg-amber-500 text-slate-950' : 'bg-slate-900/90 text-slate-200 hover:bg-slate-900' }}">
                            <span>{{ $key }}</span>
                            <span class="rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6 rounded-3xl bg-slate-900/80 p-4 text-sm text-slate-400">
                    <p class="font-semibold text-slate-200">Approved flights</p>
                    <p class="mt-2 text-slate-400">Click the Approved filter to show only flights that are currently approved.</p>
                </div>
            </aside>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Flight</th>
                        <th class="px-4 py-3 text-left font-semibold">Departure</th>
                        <th class="px-4 py-3 text-left font-semibold">Seats</th>
                        <th class="px-4 py-3 text-left font-semibold">Bookings</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($flights as $flight)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-4">
                                <div class="font-semibold text-slate-900">{{ $flight->flight_number }}</div>
                                <div class="text-xs text-slate-500">{{ $flight->airline }} · {{ $flight->route }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <div>{{ $flight->departure_time }} → {{ $flight->arrival_time }}</div>
                                <div class="text-xs text-slate-500">{{ $flight->departure_date?->format('d M Y') }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <div>Total {{ $flight->total_seats }}</div>
                                <div class="text-slate-500">Available {{ $flight->available_seats }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <div>{{ $flight->booked_seats }} passengers</div>
                                <div class="text-slate-500">Confirmed {{ $flight->confirmed_bookings_count }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $flight->status }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right space-x-2">
                                <a href="{{ route('admin.airline-flights.show', $flight) }}" class="inline-flex rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">View</a>
                                <a href="{{ route('admin.airline-flights.edit', $flight) }}" class="inline-flex rounded-2xl bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">Edit</a>
                                <form action="{{ route('admin.airline-flights.destroy', $flight) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex rounded-2xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-400">Delete</button>
                                </form>
                                <a href="{{ route('admin.airline-flights.show', $flight) }}#bookings" class="inline-flex rounded-2xl bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-500">View Bookings</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $flights->links() }}
        </div>
    </div>
@endsection
