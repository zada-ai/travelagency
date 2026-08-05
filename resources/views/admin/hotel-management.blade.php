@extends('admin.layouts.app')

@section('title', 'Hotel Management')
@section('page-heading', 'Hotel Management')
@section('page-description', 'A centralized hotel operations dashboard for the Umrah ERP admin panel.')

@section('content')
    <section class="space-y-6">
        <header class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Hotel Operations</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">Hotel Management</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">A centralized hotel operations dashboard for the Umrah ERP admin panel. Manage hotel profiles, review stay policies, handle image uploads, and monitor occupancy.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-100">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        Live Sync Active
                    </span>
                    <a href="{{ route('admin.hotels.create') }}" class="inline-flex items-center justify-center rounded-3xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Add New Hotel</a>
                </div>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl bg-slate-950 p-6 text-white shadow-sm">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Total Hotels</p>
                    <p class="mt-4 text-4xl font-semibold">{{ number_format($metrics['total_hotels']) }}</p>
                    <p class="mt-2 text-sm text-slate-300">Properties</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Active</p>
                    <p class="mt-4 text-4xl font-semibold text-emerald-600">{{ number_format($metrics['active_hotels']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Online</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Inactive</p>
                    <p class="mt-4 text-4xl font-semibold text-amber-600">{{ number_format($metrics['inactive_hotels']) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Paused</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Occupancy</p>
                    <p class="mt-4 text-4xl font-semibold text-sky-600">{{ number_format($metrics['occupancy'], 0) }}%</p>
                    <p class="mt-2 text-sm text-slate-500">Live Rate</p>
                </div>
            </div>
        </header>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Live Hotel List</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Active hotel cards</h2>
                    </div>
                    <a href="{{ route('admin.hotel-images.index') }}" class="inline-flex items-center justify-center rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Manage Hotel Images</a>
                </div>

                <div class="mt-6 grid gap-5 lg:grid-cols-2">
                    @foreach($hotels as $hotel)
                        <div class="group overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm transition duration-150 hover:-translate-y-0.5 hover:shadow-md">
                            <div class="bg-gradient-to-r from-slate-950 to-slate-800 px-6 py-5 text-white">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-xl font-semibold">{{ $hotel->hotel_name }}</h3>
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-slate-200">{{ $hotel->city }}</span>
                                </div>
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">{{ $hotel->about ? \Illuminate\Support\Str::limit($hotel->about, 120) : 'No description added yet.' }}</p>
                            </div>
                            <div class="space-y-4 px-6 py-5">
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @if($hotel->stay_policy_free_cancellation)
                                        <span class="inline-flex items-center rounded-2xl bg-emerald-100 px-3 py-2 text-sm font-medium text-emerald-700">Free cancellation</span>
                                    @endif
                                    @if($hotel->stay_policy_haram_shuttle)
                                        <span class="inline-flex items-center rounded-2xl bg-sky-100 px-3 py-2 text-sm font-medium text-sky-700">Haram shuttle</span>
                                    @endif
                                    @if($hotel->stay_policy_flexible_checkin)
                                        <span class="inline-flex items-center rounded-2xl bg-amber-100 px-3 py-2 text-sm font-medium text-amber-700">Flexible check-in</span>
                                    @endif
                                    @if($hotel->stay_policy_inclusive_breakfast)
                                        <span class="inline-flex items-center rounded-2xl bg-violet-100 px-3 py-2 text-sm font-medium text-violet-700">Breakfast included</span>
                                    @endif
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <p class="text-sm text-slate-500">Updated: {{ $hotel->updated_at?->format('d M Y') ?? '-' }}</p>
                                    <p class="text-sm text-slate-500">Status: {{ $hotel->is_active ? 'Active' : 'Inactive' }}</p>
                                </div>

                                <div class="flex flex-wrap gap-2 pt-1">
                                    <button type="button" class="edit-about-button inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100" data-hotel-id="{{ $hotel->id }}" data-hotel-name="{{ $hotel->hotel_name }}" data-hotel-about="{{ e($hotel->about) }}">Edit About</button>
                                    <a href="{{ route('admin.hotels.edit', $hotel) }}#stayPoliciesSection" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Stay Policies</a>
                                    <a href="{{ route('admin.hotels.edit', $hotel) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Edit Hotel</a>
                                    <button type="button" class="upload-images-button inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100" data-hotel-id="{{ $hotel->id }}" data-hotel-name="{{ $hotel->hotel_name }}">Upload Images</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Quick actions</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Admin shortcuts</h2>
                    </div>
                </div>
                <div class="mt-6 space-y-4">
                    <a href="{{ route('admin.hotels.create') }}" class="block rounded-3xl bg-slate-950 px-5 py-4 text-sm font-semibold text-white hover:bg-slate-800">Create New Hotel</a>
                    <a href="{{ route('admin.hotel-images.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-700 hover:bg-slate-100">Manage Hotel Images</a>
                    <a href="{{ route('admin.hotels.index') }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-700 hover:bg-slate-100">Full Hotel List</a>
                </div>

                <div id="hotelImageUploadPanel" class="mt-6 hidden rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Upload Hotel Images</p>
                            <h3 id="uploadPanelTitle" class="mt-2 text-lg font-semibold text-slate-900">Select a hotel to begin</h3>
                        </div>
                    </div>

                    <form action="{{ route('admin.hotels.upload-images') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel</label>
                            <select name="hotel_id" id="hotelSelect" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" required>
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
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Today</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Check-in summary</h2>
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Check-ins</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($metrics['today_checkins']) }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Check-outs</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($metrics['today_checkouts']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Inventory</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Room availability</h2>
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Available</p>
                        <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ number_format($metrics['available_rooms']) }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Booked</p>
                        <p class="mt-3 text-3xl font-semibold text-amber-600">{{ number_format($metrics['booked_rooms']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Locations</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Hotels by city</h2>
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-950 p-5 text-white">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Makkah</p>
                        <p class="mt-4 text-3xl font-semibold">{{ number_format($metrics['makkah_hotels']) }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950 p-5 text-white">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Madinah</p>
                        <p class="mt-4 text-3xl font-semibold">{{ number_format($metrics['madinah_hotels']) }}</p>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <div id="editAboutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Edit Hotel About</p>
                    <h2 id="editAboutTitle" class="mt-2 text-2xl font-semibold text-slate-900">Edit hotel details</h2>
                </div>
                <button type="button" id="closeEditAboutModal" class="text-slate-500 hover:text-slate-900">Close</button>
            </div>

            <form id="editAboutForm" method="POST" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">About this hotel</label>
                    <textarea id="aboutTextarea" name="about" rows="6" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <button type="button" id="cancelEditAbout" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">Save About</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadButtons = document.querySelectorAll('.upload-images-button');
            const uploadPanel = document.getElementById('hotelImageUploadPanel');
            const uploadPanelTitle = document.getElementById('uploadPanelTitle');
            const hotelSelect = document.getElementById('hotelSelect');
            const cancelButton = document.getElementById('cancelUploadPanel');
            const editButtons = document.querySelectorAll('.edit-about-button');
            const editAboutModal = document.getElementById('editAboutModal');
            const closeEditAboutModal = document.getElementById('closeEditAboutModal');
            const cancelEditAbout = document.getElementById('cancelEditAbout');
            const editAboutTitle = document.getElementById('editAboutTitle');
            const aboutTextarea = document.getElementById('aboutTextarea');
            const editAboutForm = document.getElementById('editAboutForm');

            if (uploadPanel && hotelSelect && cancelButton && uploadButtons.length) {
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
                    const imageInput = document.getElementById('hotelImagesInput');
                    if (imageInput) {
                        imageInput.value = '';
                    }
                });
            }

            if (editButtons.length && editAboutModal && closeEditAboutModal && cancelEditAbout && aboutTextarea && editAboutForm) {
                editButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const hotelId = button.dataset.hotelId;
                        const hotelName = button.dataset.hotelName;
                        const hotelAbout = button.dataset.hotelAbout || '';

                        editAboutTitle.textContent = 'Edit About for ' + hotelName;
                        aboutTextarea.value = hotelAbout;
                        editAboutForm.action = '/admin/hotels/' + hotelId + '/about';
                        editAboutModal.classList.remove('hidden');
                    });
                });

                function hideEditModal() {
                    editAboutModal.classList.add('hidden');
                    aboutTextarea.value = '';
                }

                closeEditAboutModal.addEventListener('click', hideEditModal);
                cancelEditAbout.addEventListener('click', hideEditModal);
            }
        });
    </script>
@endsection
