@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-heading', 'Super Admin Dashboard')
@section('page-description', 'Manage the Umrah ERP operations, bookings, agencies, and inventory from the administration portal.')

@section('content')

@php
    // Safe dynamic counters
    $customerCount = class_exists(\App\Models\Customer::class) ? \App\Models\Customer::count() : 0;
    $agentCount = class_exists(\App\Models\TravelAgent::class) ? \App\Models\TravelAgent::count() : 0;
    $hotelCount = class_exists(\App\Models\Hotel::class) ? \App\Models\Hotel::count() : 0;
    
    $flightBookingCount = class_exists(\App\Models\FlightBooking::class) ? \App\Models\FlightBooking::count() : 0;
    $packageBookingCount = class_exists(\App\Models\PackageBooking::class) ? \App\Models\PackageBooking::count() : 0;
    $totalBookings = $flightBookingCount + $packageBookingCount;

    $packageCount = class_exists(\App\Models\VoucherPackage::class) 
        ? \App\Models\VoucherPackage::count() 
        : (class_exists(\App\Models\Package::class) ? \App\Models\Package::count() : 0);

    $ticketCount = class_exists(\App\Models\Ticket::class) ? \App\Models\Ticket::count() : 0;
    $voucherCount = class_exists(\App\Models\AgentVoucher::class) ? \App\Models\AgentVoucher::count() : 0;
    $visaCount = class_exists(\App\Models\VisaApplication::class) ? \App\Models\VisaApplication::count() : 0;
@endphp

{{-- ========================================
     HERO EXECUTIVE BANNER (Blue & Green Gradient)
     ======================================== --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 via-blue-600 to-emerald-600 p-6 sm:p-8 lg:p-10 text-white shadow-xl shadow-blue-600/15">
    {{-- Decorative Background Elements --}}
    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <div class="absolute right-1/3 -bottom-16 h-48 w-48 rounded-full bg-emerald-400/20 blur-xl pointer-events-none"></div>
    <div class="absolute left-1/4 -top-8 h-32 w-32 rounded-full bg-blue-300/10 blur-lg pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/15 backdrop-blur-md px-3.5 py-1 text-xs font-bold text-emerald-100 border border-white/20 mb-3 shadow-xs">
                <span class="h-2 w-2 rounded-full bg-emerald-300 animate-ping"></span>
                <span>Umrah ERP Enterprise Platform</span>
            </div>
            
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                Super Admin Control Hub
            </h1>
            
            <p class="mt-2 text-sm sm:text-base text-blue-50/90 leading-relaxed font-normal">
                Centralized management for travel agencies, airline routes, Makkah &amp; Madinah hotels, Umrah package builder, and instant voucher generation.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('travel-agents.vouchers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-blue-700 shadow-md hover:bg-blue-50 transition active:scale-[0.98]">
                    <i class="bi bi-plus-circle-fill text-blue-600"></i>
                    <span>Generate Voucher</span>
                </a>
                <a href="{{ route('admin.agent-management') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/30 backdrop-blur-md border border-emerald-300/40 px-4 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-emerald-500/40 transition active:scale-[0.98]">
                    <i class="bi bi-person-check-fill text-emerald-200"></i>
                    <span>Manage Agencies</span>
                </a>
                <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-800/40 backdrop-blur-md border border-white/20 px-4 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-blue-800/60 transition active:scale-[0.98]">
                    <i class="bi bi-box-seam text-blue-200"></i>
                    <span>Package Builder</span>
                </a>
            </div>
        </div>

        {{-- Live Server Info Box --}}
        <div class="flex lg:flex-col justify-between sm:justify-start gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 sm:p-5 lg:min-w-[220px]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-200">System Status</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                    <span class="font-bold text-sm sm:text-base text-white">Online &amp; Active</span>
                </div>
            </div>
            <div class="lg:border-t lg:border-white/15 lg:pt-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-200">Current Time</p>
                <p class="font-semibold text-xs sm:text-sm text-white mt-0.5">{{ now()->format('h:i A (T)') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ========================================
     TOP METRIC PERFORMANCE CARDS (Blue & Green)
     ======================================== --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
    
    {{-- Customers --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-blue-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Customers</span>
            <span class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-people-fill"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($customerCount) }}</p>
        <p class="text-[10px] font-semibold text-blue-600 mt-0.5 flex items-center gap-1">
            <span>Pilgrim Profiles</span>
        </p>
    </div>

    {{-- Travel Agents --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-emerald-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Agencies</span>
            <span class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-briefcase-fill"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($agentCount) }}</p>
        <p class="text-[10px] font-semibold text-emerald-600 mt-0.5 flex items-center gap-1">
            <span>Travel Partners</span>
        </p>
    </div>

    {{-- Total Bookings --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-teal-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Bookings</span>
            <span class="h-8 w-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-journal-bookmark-fill"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($totalBookings) }}</p>
        <p class="text-[10px] font-semibold text-teal-600 mt-0.5 flex items-center gap-1">
            <span>Active Reservations</span>
        </p>
    </div>

    {{-- Hotels --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-blue-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hotels</span>
            <span class="h-8 w-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-building"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($hotelCount) }}</p>
        <p class="text-[10px] font-semibold text-sky-600 mt-0.5 flex items-center gap-1">
            <span>Makkah &amp; Madinah</span>
        </p>
    </div>

    {{-- Packages --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-emerald-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Packages</span>
            <span class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-box-seam-fill"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($packageCount) }}</p>
        <p class="text-[10px] font-semibold text-emerald-600 mt-0.5 flex items-center gap-1">
            <span>Umrah Bundles</span>
        </p>
    </div>

    {{-- Vouchers --}}
    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:shadow-md hover:border-blue-300 transition duration-200 group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Vouchers</span>
            <span class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
                <i class="bi bi-receipt-cutoff"></i>
            </span>
        </div>
        <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-800">{{ number_format($voucherCount) }}</p>
        <p class="text-[10px] font-semibold text-blue-600 mt-0.5 flex items-center gap-1">
            <span>Issued Vouchers</span>
        </p>
    </div>

</div>

{{-- ========================================
     MANAGEMENT HUB SECTIONS
     ======================================== --}}

{{-- SECTION 1: PEOPLE & STAKEHOLDERS (Blue Accent) --}}
<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold shadow-xs">
                <i class="bi bi-people-fill"></i>
            </span>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">People &amp; Stakeholder Management</h2>
        </div>
        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">Users &amp; Partners</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        
        {{-- Customers Card --}}
        <a href="{{ route('admin.customer-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Accounts</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Customer Profiles</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">View, edit, and manage registered pilgrims, passport details, and history.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-people-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                <span>Access customer list</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Travel Agents Card --}}
        <a href="{{ route('admin.agent-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Agencies</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Travel Agents</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Review, approve, and manage registered travel agency partnerships &amp; accounts.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-briefcase-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>Manage agency partners</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>
</div>

{{-- SECTION 2: BOOKING OPERATIONS (Emerald Accent) --}}
<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-600 text-white text-xs font-bold shadow-xs">
                <i class="bi bi-journal-bookmark-fill"></i>
            </span>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Booking &amp; Reservation Operations</h2>
        </div>
        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">Orders &amp; Logs</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        
        {{-- All Bookings Card --}}
        <a href="{{ route('admin.booking-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Master</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">All Bookings</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Centralized management of hotel rooms, flights, and custom package bookings.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-journal-check text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>View all reservations</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Flight Bookings Card --}}
        <a href="{{ route('admin.airline-bookings.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Airlines</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Flight Bookings</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Track airline ticket reservations, PNR status, passenger lists, and departures.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-sky-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-airplane-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                <span>Manage flight bookings</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Package Bookings Card --}}
        <a href="{{ route('admin.package-bookings.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Packages</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Package Bookings</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Manage confirmed Umrah package orders, vouchers, and customer allotments.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-600 to-green-600 text-white shadow-md shadow-emerald-600/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-bookmark-star-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>Manage package orders</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>
</div>

{{-- SECTION 3: HOSPITALITY & INVENTORY MANAGEMENT --}}
<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-600 text-white text-xs font-bold shadow-xs">
                <i class="bi bi-buildings-fill"></i>
            </span>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Hospitality &amp; Hotel Inventory</h2>
        </div>
        <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-100">Hotels &amp; Rates</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        
        {{-- Hotel Management Card --}}
        <a href="{{ route('admin.hotel-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-teal-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-100">Properties</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors">Hotels Management</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Configure Makkah &amp; Madinah hotels, room categories, pricing, and distances.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-md shadow-teal-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-building text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-600">
                <span>Configure hotel catalog</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Room Inventory Card --}}
        <a href="{{ route('admin.hotel-room-inventory.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Allotment</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Room Inventory</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Manage date-based room inventories, seasonal allotments, and availability blocks.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar3-range text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>Manage room inventory</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Meal Plans Card --}}
        <a href="{{ route('admin.hotel-meal-plans.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Hospitality</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Meal Plans &amp; Facilities</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Configure breakfast, half-board/full-board meal options, and hotel amenities.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-cup-hot-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                <span>Configure meal options</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>
</div>

{{-- SECTION 4: PACKAGES & FLIGHT OPERATIONS --}}
<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold shadow-xs">
                <i class="bi bi-box-seam-fill"></i>
            </span>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Packages &amp; Flight Operations</h2>
        </div>
        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">Bundles &amp; Tickets</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        
        {{-- Package Builder Card --}}
        <a href="{{ route('admin.packages.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Builder</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Package Builder</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Design, price, and publish comprehensive Umrah packages with hotels &amp; transport.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-box-seam-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>Manage Umrah packages</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Airline Tickets Card --}}
        <a href="{{ route('admin.airline-ticket-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Flights</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Airlines &amp; Tickets</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Manage flight carriers, departure/return schedules, airports, and ticket inventory.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-sky-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-ticket-perforated-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                <span>Manage flight tickets</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>
</div>

{{-- SECTION 5: VOUCHERS & VISA SERVICES --}}
<div class="space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-600 text-white text-xs font-bold shadow-xs">
                <i class="bi bi-receipt-cutoff"></i>
            </span>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Vouchers, Visas &amp; Documentation</h2>
        </div>
        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">PDF &amp; Processing</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        
        {{-- Generated Vouchers Card --}}
        <a href="{{ route('admin.vouchers.index') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Documents</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Generated Vouchers</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Search, view, and download official PDF travel vouchers for hotel &amp; flight stays.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-receipt-cutoff text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                <span>View all vouchers</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Voucher Settings Card --}}
        <a href="{{ route('admin.voucher-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">Branding</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Voucher Templates</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Configure provider branding, agency company logos, and voucher layout settings.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-sky-600 text-white shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-sliders2-vertical text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                <span>Configure templates</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

        {{-- Visa Operations Card --}}
        <a href="{{ route('admin.visa-management') }}" class="group relative rounded-2xl border border-slate-200/90 bg-white p-5 shadow-xs hover:shadow-xl hover:border-teal-400 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-100">Processing</span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-teal-600 transition-colors">Visa Operations</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Monitor visa applications, document verification status, and officer assignments.</p>
                </div>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-md shadow-teal-500/20 group-hover:scale-110 transition-transform">
                    <i class="bi bi-passport-fill text-lg"></i>
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-600">
                <span>View visa applications</span>
                <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </a>

    </div>
</div>

@endsection
