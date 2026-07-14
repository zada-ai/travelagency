@extends('admin.layouts.app')

@section('title', 'Hotel Image Details')
@section('page-heading', 'Hotel Image Details')
@section('page-description', 'View full details and preview of the hotel image.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-4">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <img src="{{ Storage::disk('public')->url($hotelImage->path) }}" alt="{{ $hotelImage->alt_text ?? 'Hotel image' }}" class="h-full w-full rounded-3xl object-cover" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Hotel</h3>
                            <p class="mt-4 text-slate-900">{{ $hotelImage->hotel?->hotel_name ?? 'Unknown' }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-5">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Status</h3>
                            <p class="mt-4 text-slate-900">{{ $hotelImage->is_active ? 'Active' : 'Inactive' }}</p>
                            <p class="mt-2 text-slate-500">Cover image: {{ $hotelImage->is_cover ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Image details</h2>
                    <div class="mt-6 space-y-4 text-sm text-slate-600">
                        <div>
                            <span class="block text-xs uppercase tracking-[0.24em] text-slate-400">Title</span>
                            <p class="mt-2 text-slate-900">{{ $hotelImage->title ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-[0.24em] text-slate-400">Alt Text</span>
                            <p class="mt-2 text-slate-900">{{ $hotelImage->alt_text ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-[0.24em] text-slate-400">Sort Order</span>
                            <p class="mt-2 text-slate-900">{{ $hotelImage->sort_order }}</p>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-[0.24em] text-slate-400">Uploaded</span>
                            <p class="mt-2 text-slate-900">{{ $hotelImage->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.hotel-images.edit', $hotelImage) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Edit Image</a>
                        <a href="{{ route('admin.hotel-images.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back to list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
