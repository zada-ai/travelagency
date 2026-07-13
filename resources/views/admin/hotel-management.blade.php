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
@endsection
