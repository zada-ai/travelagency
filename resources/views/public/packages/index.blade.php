@extends('layouts.public')

@section('title', 'Umrah Packages | Hujaj Umrah')

@section('content')
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-blue-600">Packages</p>
                    <h1 class="mt-2 text-4xl font-bold text-slate-900">Available Umrah packages</h1>
                    <p class="mt-3 max-w-2xl text-slate-600">Browse secure, customer-ready packages with hotels, flights, visa, and transport options.</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Back to homepage
                    <i class="bi bi-arrow-left ml-2"></i>
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                @foreach($displayPackages as $pkg)
                    <div class="rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">{{ $pkg->badge ?? 'Featured' }}</span>
                                <h2 class="mt-4 text-2xl font-bold text-slate-900">{{ $pkg->title ?? $pkg->name }}</h2>
                            </div>
                            <div class="text-right">
                                <span class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $pkg->duration ?? 'Flexible' }}</span>
                                <p class="mt-2 text-3xl font-black text-blue-600">SAR {{ number_format($pkg->price ?? 0, 0) }}</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Departure</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ optional($pkg->departure_date)->format('M d, Y') ?? 'TBA' }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Return</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ optional($pkg->return_date)->format('M d, Y') ?? 'TBA' }}</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Makkah hotel</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $pkg->makkah_hotel ?? 'Not specified' }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Madinah hotel</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $pkg->madinah_hotel ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                            <div class="text-sm text-slate-500">
                                <p>{{ $pkg->available_seats }} seats remaining</p>
                                <p class="mt-1">Status: {{ $pkg->status }}</p>
                            </div>
                            <a href="{{ route('packages.book', $pkg->id) }}" class="inline-flex items-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                Book package
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $displayPackages->links() }}
            </div>
        </div>
    </section>
@endsection
