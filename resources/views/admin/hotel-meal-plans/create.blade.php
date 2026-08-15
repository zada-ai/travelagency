@extends('admin.layouts.app')

@section('title', 'New Meal Plan')


@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Create Meal Plan</h1>
            <p class="text-sm text-slate-500">Add meal plan pricing for your hotels.</p>
        </div>
        <a href="{{ route('admin.hotel-meal-plans.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Back to list</a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.hotel-meal-plans.store') }}" class="grid gap-6">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 text-black">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Hotel</label>
                    <select name="hotel_id" class="mt-1 block w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        <option value="">Select hotel</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                        @endforeach
                    </select>
                    @error('hotel_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Meal plan code</label>
                    <input type="text" name="meal_plan_code" value="{{ old('meal_plan_code') }}" class="mt-1 block w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="MP-001" />
                    @error('meal_plan_code')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="text-black">
                <label class="block text-sm font-medium text-slate-700">Meal plan name</label>
                <input type="text" name="meal_plan_name" value="{{ old('meal_plan_name') }}" class="mt-1 block w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="Breakfast + Dinner" />
                @error('meal_plan_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="text-black">
                <label class="block text-sm font-medium text-slate-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="Details about what is included">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2 text-black ">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Price per person</label>
                    <input type="number" step="0.01" name="price_per_person" value="{{ old('price_per_person') }}" class="mt-1 block w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="0.00" />
                    @error('price_per_person')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="text-black">
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                        <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.hotel-meal-plans.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Save meal plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
