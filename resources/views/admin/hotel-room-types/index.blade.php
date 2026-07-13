@extends('admin.layouts.app')

@section('title', 'Hotel Room Types')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Hotel Room Types</h1>
            <p class="text-sm text-slate-500">Manage hotel room categories, rates, and availability.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.hotel-room-types.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">New Room Type</a>
            <a href="{{ route('admin.hotel-room-types.export') }}?{{ http_build_query($filters) }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Export CSV</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="Room name or code" />
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
                <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                <select name="status" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All statuses</option>
                    <option value="Active" {{ isset($filters['status']) && $filters['status'] === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ isset($filters['status']) && $filters['status'] === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.hotel-room-types.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Hotel</th>
                    <th class="px-4 py-3 text-left font-semibold">Room Name</th>
                    <th class="px-4 py-3 text-left font-semibold">Code</th>
                    <th class="px-4 py-3 text-left font-semibold">Occupancy</th>
                    <th class="px-4 py-3 text-left font-semibold">Available</th>
                    <th class="px-4 py-3 text-left font-semibold">Rate</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @forelse($roomTypes as $roomType)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3">{{ $roomType->hotel?->hotel_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $roomType->room_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $roomType->room_code }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $roomType->max_occupancy }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $roomType->available_rooms }}/{{ $roomType->total_rooms }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ number_format($roomType->daily_rate, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $roomType->status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $roomType->status }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.hotel-room-types.edit', $roomType) }}" class="rounded-md bg-slate-900 px-3 py-1 text-sm font-medium text-white transition hover:bg-slate-700">Edit</a>
                                <form action="{{ route('admin.hotel-room-types.destroy', $roomType) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this room type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-rose-600 px-3 py-1 text-sm font-medium text-white transition hover:bg-rose-500">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No room types found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $roomTypes->withQueryString()->links() }}</div>
</div>
@endsection
