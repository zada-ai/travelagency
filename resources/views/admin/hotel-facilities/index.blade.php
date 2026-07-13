@extends('admin.layouts.app')

@section('title', 'Hotel Facilities')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Hotel Facilities</h1>
            <p class="text-sm text-slate-500">Manage hotel facility types and availability status.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.hotel-facilities.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">New Facility</a>
            <a href="{{ route('admin.hotel-facilities.export') }}?{{ http_build_query($filters) }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Export CSV</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="w-full rounded-md border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" placeholder="Facility or hotel" />
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
                <a href="{{ route('admin.hotel-facilities.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Hotel</th>
                    <th class="px-4 py-3 text-left font-semibold">Facility</th>
                    <th class="px-4 py-3 text-left font-semibold">Code</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @forelse($facilities as $facility)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3">{{ $facility->hotel?->hotel_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $facility->facility_name }}</td>
                        <td class="whitespace-nowrap px-4 py-3">{{ $facility->facility_code }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $facility->status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $facility->status }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.hotel-facilities.edit', $facility) }}" class="rounded-md bg-slate-900 px-3 py-1 text-sm font-medium text-white transition hover:bg-slate-700">Edit</a>
                                <form action="{{ route('admin.hotel-facilities.destroy', $facility) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this facility?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md bg-rose-600 px-3 py-1 text-sm font-medium text-white transition hover:bg-rose-500">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">No facilities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $facilities->withQueryString()->links() }}</div>
</div>
@endsection
