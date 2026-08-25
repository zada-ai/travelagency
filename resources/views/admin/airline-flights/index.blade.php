@extends('admin.layouts.app')

@section('title', 'Flight Management')
@section('page-heading', 'Flight Management')
@section('page-description', 'Manage airline schedules, seat availability, and booking statistics.')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Flight Inventory</h2>
            <p class="mt-1 text-xs font-medium text-slate-500">All scheduled airline flights with seat availability, fares and passenger stats.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.airline-ticket-management') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition">
                <i class="bi bi-arrow-left"></i> Ticket Hub
            </a>
            <a href="{{ route('admin.airline-flights.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:from-blue-700 hover:to-emerald-700 transition">
                <i class="bi bi-plus-lg"></i> Create Flight
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[240px_1fr]">
        {{-- Status Filter Aside --}}
        <aside class="space-y-3 rounded-3xl border border-slate-200/90 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Filter By Status</h3>
                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-100">{{ $activeStatus }}</span>
            </div>
            <div class="space-y-1.5 pt-1">
                @foreach($statuses as $key => $count)
                    <a href="{{ $key === 'All' ? route('admin.airline-flights.index') : route('admin.airline-flights.index', ['status' => $key]) }}" class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-bold transition {{ $activeStatus === $key ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span>{{ $key }}</span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $activeStatus === $key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $count }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Flights Table Card --}}
        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
            <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
                <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-4">Flight</th>
                            <th class="px-5 py-4">Timetable</th>
                            <th class="px-5 py-4 text-center">Seat Capacity</th>
                            <th class="px-5 py-4 text-center">Bookings</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($flights as $flight)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">{{ $flight->flight_number }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium">{{ $flight->airline }} · {{ $flight->route }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">{{ $flight->departure_time }} ➔ {{ $flight->arrival_time }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $flight->departure_date?->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="font-bold text-slate-900">{{ $flight->total_seats }} Total</div>
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                        {{ $flight->available_seats }} Available
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="font-bold text-slate-900">{{ $flight->booked_seats }} pax</div>
                                    <div class="text-[10px] text-slate-400">{{ $flight->confirmed_bookings_count }} confirmed</div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold {{ $flight->status === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($flight->status === 'Processing' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($flight->status === 'Cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600')) }}">
                                        {{ $flight->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.airline-flights.show', $flight) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-blue-50 hover:text-blue-600 transition" title="View Flight">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.airline-flights.edit', $flight) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-emerald-50 hover:text-emerald-600 transition" title="Edit Flight">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.airline-flights.show', $flight) }}#bookings" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-indigo-50 hover:text-indigo-600 transition" title="Bookings">
                                            <i class="bi bi-ticket-detailed"></i>
                                        </a>
                                        <form action="{{ route('admin.airline-flights.destroy', $flight) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this flight?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-rose-500 shadow-xs hover:bg-rose-50 hover:text-rose-700 transition" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-medium">
                                    <i class="bi bi-airplane text-3xl text-slate-300 mb-2"></i>
                                    <p class="text-xs font-semibold text-slate-600">No scheduled flights found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $flights->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

