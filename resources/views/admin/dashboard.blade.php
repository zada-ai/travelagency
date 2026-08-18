@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-heading', 'Super Admin Dashboard')
@section('page-description', 'Manage the Umrah ERP operations from the internal administration portal.')

@section('content')

{{-- ========================================
     MANAGEMENT SECTIONS
     ======================================== --}}

<!-- Agent & Customer Management -->
<div class="space-y-4">
    <div class="flex items-baseline gap-3">
        <h2 class="text-lg font-bold text-white">People Management</h2>
        <span class="text-xs uppercase tracking-widest text-slate-500">Users & Stakeholders</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.customer-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-purple-500 hover:shadow-purple-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-purple-400">Management</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Customers</h3>
                    <p class="mt-1 text-xs text-slate-400">View and manage customer accounts and profiles.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.agent-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-blue-500 hover:shadow-blue-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">Management</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Travel Agents</h3>
                    <p class="mt-1 text-xs text-slate-400">Review, approve, and manage agency accounts.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400 group-hover:bg-blue-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>

<!-- Booking Management -->
<div class="space-y-4">
    <div class="flex items-baseline gap-3">
        <h2 class="text-lg font-bold text-white">Booking Operations</h2>
        <span class="text-xs uppercase tracking-widest text-slate-500">Reservations & Transactions</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.booking-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-emerald-500 hover:shadow-emerald-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400">Bookings</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">All Bookings</h3>
                    <p class="mt-1 text-xs text-slate-400">Manage hotel, flight, and package bookings.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.airline-bookings.index') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-sky-500 hover:shadow-sky-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-sky-400">Bookings</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Flight Bookings</h3>
                    <p class="mt-1 text-xs text-slate-400">Track and manage all flight reservations.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-400 group-hover:bg-sky-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.package-bookings.index') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-amber-500 hover:shadow-amber-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-400">Bookings</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Package Bookings</h3>
                    <p class="mt-1 text-xs text-slate-400">Manage Umrah package reservations.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400 group-hover:bg-amber-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>

<!-- Inventory & Hotel Management -->
<div class="space-y-4">
    <div class="flex items-baseline gap-3">
        <h2 class="text-lg font-bold text-white">Inventory Management</h2>
        <span class="text-xs uppercase tracking-widest text-slate-500">Hotels & Properties</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.hotel-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-rose-500 hover:shadow-rose-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-rose-400">Inventory</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Hotels</h3>
                    <p class="mt-1 text-xs text-slate-400">Configure hotels, rooms, rates, and facilities.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/10 text-rose-400 group-hover:bg-rose-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.packages.index') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-indigo-500 hover:shadow-indigo-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400">Inventory</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Packages</h3>
                    <p class="mt-1 text-xs text-slate-400">Create and manage Umrah package bundles.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400 group-hover:bg-indigo-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>

<!-- Flight & Ticket Management -->
<div class="space-y-4">
    <div class="flex items-baseline gap-3">
        <h2 class="text-lg font-bold text-white">Flight Operations</h2>
        <span class="text-xs uppercase tracking-widest text-slate-500">Airlines & Tickets</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.airline-ticket-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-cyan-500 hover:shadow-cyan-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-cyan-400">Flights</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Airlines & Tickets</h3>
                    <p class="mt-1 text-xs text-slate-400">Manage airlines, routes, and flight tickets.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-4-2m4 2l4-2"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>

<!-- Visa & Special Services -->
<div class="space-y-4">
    <div class="flex items-baseline gap-3">
        <h2 class="text-lg font-bold text-white">Visa & Services</h2>
        <span class="text-xs uppercase tracking-widest text-slate-500">Special Programs</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {{-- <a href="{{ route('admin.visa-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-fuchsia-500 hover:shadow-fuchsia-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-fuchsia-400">Services</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Visa Management</h3>
                    <p class="mt-1 text-xs text-slate-400">Track visa applications and document status.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-500/10 text-fuchsia-400 group-hover:bg-fuchsia-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
        </a> --}}

        <a href="{{ route('admin.voucher-management') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-lime-500 hover:shadow-lime-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-lime-400">Services</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">Vouchers</h3>
                    <p class="mt-1 text-xs text-slate-400">Manage hotel and travel voucher templates.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-lime-500/10 text-lime-400 group-hover:bg-lime-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4l-8 8m0 0l8 8M6 12h12"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('admin.vouchers.index') }}" class="group rounded-2xl border border-slate-800 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-lg transition hover:border-orange-500 hover:shadow-orange-500/10">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-orange-400">Services</p>
                    <h3 class="mt-2 text-lg font-semibold text-white">New Vouchers</h3>
                    <p class="mt-1 text-xs text-slate-400">Generate and track travel vouchers.</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/10 text-orange-400 group-hover:bg-orange-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
        </a>
    </div>
</div>

@endsection
