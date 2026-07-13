@extends('admin.layouts.app')

@section('title', 'Add Room Inventory')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900">New Room Inventory</h1>
            <p class="mt-1 text-sm text-slate-500">Add a daily inventory record for a hotel room type.</p>
        </div>

        <form action="{{ route('admin.hotel-room-inventory.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Hotel</span>
                    <select name="hotel_id" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900">
                        <option value="">Select hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(old('hotel_id') == $hotel->id)>{{ $hotel->hotel_name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Room Type</span>
                    <select name="hotel_room_type_id" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900">
                        <option value="">Select room type</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('hotel_room_type_id') == $type->id)>{{ $type->room_name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_room_type_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Inventory Date</span>
                    <input type="date" name="inventory_date" value="{{ old('inventory_date') }}" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900" />
                    @error('inventory_date')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Total Rooms</span>
                    <input type="number" name="total_rooms" min="0" value="{{ old('total_rooms') }}" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900" />
                    @error('total_rooms')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Available Rooms</span>
                    <input type="number" name="available_rooms" min="0" value="{{ old('available_rooms') }}" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900" />
                    @error('available_rooms')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Booked Rooms</span>
                    <input type="number" name="booked_rooms" min="0" value="{{ old('booked_rooms') }}" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900" />
                    @error('booked_rooms')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Status</span>
                    <select name="status" class="mt-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900">
                        <option value="Active" @selected(old('status') == 'Active')>Active</option>
                        <option value="Inactive" @selected(old('status') == 'Inactive')>Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-sky-600 px-6 py-2 text-sm font-semibold text-white hover:bg-sky-700">Save Inventory</button>
                <a href="{{ route('admin.hotel-room-inventory.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
