@extends('admin.layouts.app')

@section('title', 'Hotel Images')
@section('page-heading', 'Hotel Image Management')
@section('page-description', 'View, edit, replace, and delete hotel images uploaded for the public hero slider.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.2fr_0.9fr_0.9fr_0.9fr]">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Search</label>
                    <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Hotel name, title, alt text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Hotel</label>
                    <select name="hotel_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">All hotels</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(($filters['hotel_id'] ?? '') == $hotel->id)>{{ $hotel->hotel_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">All</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                        <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
                    <a href="{{ route('admin.hotel-images.index') }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Hotel Images</h2>
                    <p class="mt-2 text-sm text-slate-500">Manage uploaded hotel images for all hotels.</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-950 text-white">
                        <tr>
                            <th class="px-4 py-4 font-semibold">Preview</th>
                            <th class="px-4 py-4 font-semibold">Hotel</th>
                            <th class="px-4 py-4 font-semibold">Title</th>
                            <th class="px-4 py-4 font-semibold">Alt Text</th>
                            <th class="px-4 py-4 font-semibold">Sort Order</th>
                            <th class="px-4 py-4 font-semibold">Cover</th>
                            <th class="px-4 py-4 font-semibold">Status</th>
                            <th class="px-4 py-4 font-semibold">Created</th>
                            <th class="px-4 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($images as $image)
                            <tr>
                                <td class="px-4 py-4 align-middle">
                                    <a href="{{ Storage::disk('public')->url($image->path) }}" target="_blank" class="inline-block h-16 w-24 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $image->alt_text ?? 'Hotel image' }}" class="h-full w-full object-cover" />
                                    </a>
                                </td>
                                <td class="px-4 py-4 align-middle">{{ $image->hotel->hotel_name ?? '-' }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->title ?? '-' }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->alt_text ?? '-' }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->sort_order }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->is_cover ? 'Yes' : 'No' }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="px-4 py-4 align-middle">{{ $image->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-4 align-middle">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.hotel-images.show', $image) }}" class="rounded-2xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">View</a>
                                        <a href="{{ route('admin.hotel-images.edit', $image) }}" class="rounded-2xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">Edit</a>
                                        <form action="{{ route('admin.hotel-images.destroy', $image) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this image? This will remove the file from storage.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-2xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-500">No hotel images found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $images->links() }}
            </div>
        </div>
    </div>
@endsection
