@extends('admin.layouts.app')

@section('title', 'Room Inventory')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Room Inventory</h1>
                <p class="mt-1 text-sm text-slate-500">Manage hotel room inventory and availability.</p>
            </div>

            <div class="space-x-2 flex flex-wrap items-center">
                <a href="{{ route('admin.hotel-room-inventory.create') }}" class="inline-flex items-center justify-center rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">New Inventory</a>
                <a href="{{ route('admin.hotel-room-inventory.export', request()->query()) }}" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Export CSV</a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.hotel-room-inventory.index') }}" class="grid gap-4 md:grid-cols-5 mb-6">
            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search hotel or room type" class="rounded-md border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" />
            <select name="hotel_id" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900">
                <option value="">All Hotels</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}" @selected(($filters['hotel_id'] ?? '') == $hotel->id)>{{ $hotel->hotel_name }}</option>
                @endforeach
            </select>
            <select name="hotel_room_type_id" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900">
                <option value="">All Room Types</option>
                @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}" @selected(($filters['hotel_room_type_id'] ?? '') == $type->id)>{{ $type->room_name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900" />
            <select name="status" class="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900">
                <option value="">Any Status</option>
                <option value="Active" @selected(($filters['status'] ?? '') == 'Active')>Active</option>
                <option value="Inactive" @selected(($filters['status'] ?? '') == 'Inactive')>Inactive</option>
            </select>
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
        </form>

        <div class="overflow-x-auto bg-white rounded-lg border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Hotel</th>
                        <th class="px-4 py-3 text-left font-medium">Room Type</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                        <th class="px-4 py-3 text-right font-medium">Available</th>
                        <th class="px-4 py-3 text-right font-medium">Booked</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($inventories as $inventory)
                        <tr>
                            <td class="px-4 py-3 text-slate-700">{{ $inventory->inventory_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $inventory->hotel?->hotel_name }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $inventory->roomType?->room_name }}</td>
                            <td class="px-4 py-3 text-right text-slate-700">{{ $inventory->total_rooms }}</td>
                            <td class="px-4 py-3 text-right text-emerald-700">{{ $inventory->available_rooms }}</td>
                            <td class="px-4 py-3 text-right text-rose-700">{{ $inventory->booked_rooms }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $inventory->status }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.hotel-room-inventory.edit', $inventory) }}" class="inline-flex items-center rounded-md bg-sky-600 px-3 py-1 text-xs font-medium text-white hover:bg-sky-700">Edit</a>
                                    <form action="{{ route('admin.hotel-room-inventory.destroy', $inventory) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1 text-xs font-medium text-white hover:bg-rose-700" onclick="return confirm('Delete this inventory record?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No inventory records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $inventories->links() }}
        </div>
    </div>
@endsection
