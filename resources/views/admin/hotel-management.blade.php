@extends('admin.layouts.app')

@section('title', 'Hotel Management')
@section('page-heading', 'Hotel Management')
@section('page-description', 'A centralized hotel operations dashboard for the Umrah ERP admin panel.')

@section('content')
    <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <div class="grid gap-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-slate-950 p-5 text-white shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Total Hotels</p>
                        <p class="mt-4 text-3xl font-semibold">{{ number_format($metrics['total_hotels']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Active Hotels</p>
                        <p class="mt-4 text-3xl font-semibold text-emerald-600">{{ number_format($metrics['active_hotels']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Inactive Hotels</p>
                        <p class="mt-4 text-3xl font-semibold text-amber-600">{{ number_format($metrics['inactive_hotels']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Occupancy</p>
                        <p class="mt-4 text-3xl font-semibold text-sky-600">{{ number_format($metrics['occupancy'], 0) }}%</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Hotel Images</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Upload to Hero Banner</h2>
                    </div>
                </div>
                <p class="text-sm text-slate-500 mt-3">Select a hotel from the left list and upload images to display in the public hero slider.</p>

                <div class="mt-6 space-y-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Manage all uploaded hotel images in a centralized list.</p>
                        </div>
                        <a href="{{ route('admin.hotel-images.index') }}" class="inline-flex items-center rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Manage Hotel Images</a>
                    </div>
                    @foreach($hotels as $hotel)
                        <div class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 p-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $hotel->hotel_name }}</p>
                            </div>
                            <button type="button" class="upload-images-button inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100" data-hotel-id="{{ $hotel->id }}" data-hotel-name="{{ $hotel->hotel_name }}">
                                Upload Images
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Location Distribution</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Hotels by City</h2>
                    </div>
                    <div class="text-sm text-slate-500">Live inventory overview</div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-950 p-5 text-white shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Makkah</p>
                        <p class="mt-4 text-3xl font-semibold">{{ number_format($metrics['makkah_hotels']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-950 p-5 text-white shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Madinah</p>
                        <p class="mt-4 text-3xl font-semibold">{{ number_format($metrics['madinah_hotels']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6">
            <div id="hotelImageUploadPanel" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hidden">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Upload Hotel Images</p>
                        <h2 id="uploadPanelTitle" class="mt-2 text-2xl font-semibold text-slate-900">Select a hotel to begin</h2>
                    </div>
                </div>

                <form action="{{ route('admin.hotels.upload-images') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel</label>
                        <select name="hotel_id" id="hotelSelect" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" required>
                            <option value="">Select hotel</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}">{{ $hotel->hotel_name }}</option>
                            @endforeach
                        </select>
                        @error('hotel_id')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Upload Images</label>
                        <input type="file" name="images[]" id="hotelImagesInput" multiple accept="image/png,image/jpeg,image/jpg" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" required />
                        <p class="mt-2 text-xs text-slate-500">Choose up to 20 images. JPG, JPEG, PNG only.</p>
                        @error('images')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                        @error('images.*')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">Upload Images</button>
                        <button type="button" id="cancelUploadPanel" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Today</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Check-in / Check-out</h2>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Today's Check-ins</p>
                        <p class="mt-3 text-3xl font-semibold">{{ number_format($metrics['today_checkins']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Today's Check-outs</p>
                        <p class="mt-3 text-3xl font-semibold">{{ number_format($metrics['today_checkouts']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Availability</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Room Inventory</h2>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Available Rooms</p>
                        <p class="mt-3 text-3xl font-semibold">{{ number_format($metrics['available_rooms']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Booked Rooms</p>
                        <p class="mt-3 text-3xl font-semibold">{{ number_format($metrics['booked_rooms']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadButtons = document.querySelectorAll('.upload-images-button');
            const uploadPanel = document.getElementById('hotelImageUploadPanel');
            const uploadPanelTitle = document.getElementById('uploadPanelTitle');
            const hotelSelect = document.getElementById('hotelSelect');
            const cancelButton = document.getElementById('cancelUploadPanel');

            if (!uploadButtons.length || !uploadPanel || !hotelSelect || !cancelButton) {
                return;
            }

            uploadButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const hotelId = button.dataset.hotelId;
                    const hotelName = button.dataset.hotelName;

                    if (!hotelId) {
                        return;
                    }

                    hotelSelect.value = hotelId;
                    uploadPanelTitle.textContent = 'Upload images for ' + hotelName;
                    uploadPanel.classList.remove('hidden');
                    uploadPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            cancelButton.addEventListener('click', function () {
                uploadPanel.classList.add('hidden');
                hotelSelect.value = '';
                uploadPanelTitle.textContent = 'Select a hotel to begin';
                document.getElementById('hotelImagesInput').value = '';
            });
        });
    </script>
@endsection
