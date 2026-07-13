@extends('admin.layouts.app')

@section('title', 'Seasonal Rates')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Seasonal Rates</h1>
            <p class="text-sm text-slate-500">Manage seasonal pricing by hotel and room type.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.hotel-seasonal-rates.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">New Seasonal Rate</a>
            <a href="{{ route('admin.hotel-seasonal-rates.export') }}?{{ http_build_query($filters) }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Export CSV</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="Season or hotel" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Hotel</label>
                <select name="hotel_id" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All hotels</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ isset($filters['hotel_id']) && $filters['hotel_id'] == $hotel->id ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Room type</label>
                <select name="hotel_room_type_id" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All room types</option>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}" {{ isset($filters['hotel_room_type_id']) && $filters['hotel_room_type_id'] == $roomType->id ? 'selected' : '' }}>{{ $roomType->room_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.hotel-seasonal-rates.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Hotel</th>
                    <th class="px-4 py-3 text-left font-semibold">Room Type</th>
                    <th class="px-4 py-3 text-left font-semibold">Season</th>
                    <th class="px-4 py-3 text-left font-semibold">Dates</th>
                    <th class="px-4 py-3 text-left font-semibold">Rate</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @forelse($seasonalRates as $rate)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3">{{ $rate->hotel?->hotel_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $rate->roomType?->room_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $rate->season_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $rate->start_date->format('Y-m-d') }} — {{ $rate->end_date->format('Y-m-d') }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ number_format($rate->daily_rate, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $rate->status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $rate->status }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.hotel-seasonal-rates.edit', $rate) }}" class="rounded-md bg-slate-900 px-3 py-1 text-sm font-medium text-white transition hover:bg-slate-700">Edit</a>
                                <form action="{{ route('admin.hotel-seasonal-rates.destroy', $rate) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this seasonal rate?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-rose-600 px-3 py-1 text-sm font-medium text-white transition hover:bg-rose-500">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No seasonal rates found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $seasonalRates->withQueryString()->links() }}</div>
</div>
@endsection
