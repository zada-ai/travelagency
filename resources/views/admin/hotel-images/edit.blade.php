@extends('admin.layouts.app')

@section('title', 'Edit Hotel Image')
@section('page-heading', 'Edit Hotel Image')
@section('page-description', 'Update image title, alt text, sort order, status, cover flag, and replace the stored file.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @if(session('success'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-slate-900 shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.hotel-images.update', $hotelImage) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel</label>
                        <select name="hotel_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" required>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" @selected(old('hotel_id', $hotelImage->hotel_id) == $hotel->id)>{{ $hotel->hotel_name }}</option>
                            @endforeach
                        </select>
                        @error('hotel_id')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $hotelImage->sort_order) }}" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" required>
                        @error('sort_order')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Image Title</label>
                        <input type="text" name="title" value="{{ old('title', $hotelImage->title) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('title')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Alt Text</label>
                        <input type="text" name="alt_text" value="{{ old('alt_text', $hotelImage->alt_text) }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('alt_text')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-3">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Replace Image</label>
                        <input type="file" name="replace_image" accept="image/png,image/jpeg,image/jpg" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                        @error('replace_image')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        <p class="text-xs text-slate-500">Leave blank to keep the existing file.</p>
                    </div>
                    <div class="space-y-3">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Current Image</p>
                            <img src="{{ Storage::disk('public')->url($hotelImage->path) }}" alt="{{ $hotelImage->alt_text ?? 'Current hotel image' }}" class="mt-4 h-56 w-full rounded-3xl object-cover" />
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $hotelImage->is_active)) class="h-4 w-4 text-blue-600" />
                        <span class="text-sm text-slate-700">Active</span>
                    </label>
                    <label class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <input type="checkbox" name="is_cover" value="1" @checked(old('is_cover', $hotelImage->is_cover)) class="h-4 w-4 text-blue-600" />
                        <span class="text-sm text-slate-700">Cover Image</span>
                    </label>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-500">Save Changes</button>
                    <a href="{{ route('admin.hotel-images.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
