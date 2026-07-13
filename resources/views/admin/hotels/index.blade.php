@extends('admin.layouts.app')

@section('title', 'Hotels')
@section('page-heading', 'Hotels')
@section('page-description', 'Manage hotel inventory, status, and operational details.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.4fr_0.8fr_0.8fr_0.6fr_0.6fr_0.5fr]">
                <div class="col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Search</label>
                    <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Hotel name, code, city or address" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">City</label>
                    <select name="city" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" @selected(($filters['city'] ?? '') === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Category</label>
                    <select name="category" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">All Categories</option>
                        <option value="3 Star" @selected(($filters['category'] ?? '') === '3 Star')>3 Star</option>
                        <option value="4 Star" @selected(($filters['category'] ?? '') === '4 Star')>4 Star</option>
                        <option value="5 Star" @selected(($filters['category'] ?? '') === '5 Star')>5 Star</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">All Status</option>
                        <option value="Active" @selected(($filters['status'] ?? '') === 'Active')>Active</option>
                        <option value="Inactive" @selected(($filters['status'] ?? '') === 'Inactive')>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Filter</button>
                    <a href="{{ route('admin.hotels.index') }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Hotels</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $hotels->total() }} records found.</p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('admin.hotels.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-500">Add Hotel</a>
                    <a href="{{ route('admin.hotels.export') }}?{{ request()->getQueryString() }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Export CSV</a>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-4 py-4 font-semibold">Hotel Name</th>
                            <th class="px-4 py-4 font-semibold">Code</th>
                            <th class="px-4 py-4 font-semibold">City</th>
                            <th class="px-4 py-4 font-semibold">Category</th>
                            <th class="px-4 py-4 font-semibold">Status</th>
                            <th class="px-4 py-4 font-semibold">Featured</th>
                            <th class="px-4 py-4 font-semibold">Distance</th>
                            <th class="px-4 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($hotels as $hotel)
                            <tr>
                                <td class="px-4 py-4">{{ $hotel->hotel_name }}</td>
                                <td class="px-4 py-4">{{ $hotel->hotel_code }}</td>
                                <td class="px-4 py-4">{{ $hotel->city }}</td>
                                <td class="px-4 py-4">{{ $hotel->category }}</td>
                                <td class="px-4 py-4">{{ $hotel->status }}</td>
                                <td class="px-4 py-4">{{ $hotel->featured ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-4">{{ number_format($hotel->distance_from_haram, 2) }} km</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="rounded-2xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">Edit</a>
                                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-2xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-600" onclick="return confirm('Delete this hotel?');">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">No hotels found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
@endsection
