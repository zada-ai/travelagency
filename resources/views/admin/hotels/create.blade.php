@extends('admin.layouts.app')

@section('title', 'Create Hotel')
@section('page-heading', 'Create Hotel')
@section('page-description', 'Add a new hotel to the Umrah ERP hotel inventory.')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.hotels.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-2">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel Name</label>
                    <input name="hotel_name" value="{{ old('hotel_name') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                    @error('hotel_name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel Code</label>
                    <input name="hotel_code" value="{{ old('hotel_code') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                    @error('hotel_code')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                    <input name="city" value="{{ old('city') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                    @error('city')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                        <select name="category" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                            <option value="3 Star" @selected(old('category') === '3 Star')>3 Star</option>
                            <option value="4 Star" @selected(old('category') === '4 Star')>4 Star</option>
                            <option value="5 Star" @selected(old('category') === '5 Star')>5 Star</option>
                        </select>
                        @error('category')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                            <option value="Active" @selected(old('status') === 'Active')>Active</option>
                            <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                        </select>
                        @error('status')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Distance from Haram (km)</label>
                        <input name="distance_from_haram" value="{{ old('distance_from_haram') }}" type="number" step="0.01" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('distance_from_haram')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input type="hidden" name="featured" value="0" />
                        <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <input type="checkbox" name="featured" value="1" class="h-4 w-4 text-emerald-600" @checked(old('featured') == '1') />
                            <span class="text-sm text-slate-700">Featured hotel</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Address</label>
                    <textarea name="address" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Phone</label>
                        <input name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('phone')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input name="email" value="{{ old('email') }}" type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('email')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Website</label>
                        <input name="website" value="{{ old('website') }}" type="url" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('website')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Latitude</label>
                            <input name="latitude" value="{{ old('latitude') }}" type="number" step="0.0000001" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                            @error('latitude')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Longitude</label>
                            <input name="longitude" value="{{ old('longitude') }}" type="number" step="0.0000001" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                            @error('longitude')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Hotel Gallery</h3>
                    <p class="text-sm text-slate-500 mb-4">Upload up to 20 photos. Select a cover image after saving.</p>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Images</label>
                        <input type="file" name="images[]" multiple accept="image/png,image/jpeg,image/jpg" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('images')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        @error('images.*')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Accepted formats: JPG, JPEG, PNG. Max file size: 5MB each.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.hotels.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">Save Hotel</button>
                </div>
            </div>
        </form>
    </div>
@endsection
