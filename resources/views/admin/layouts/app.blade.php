<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen lg:flex">
        <aside class="w-full lg:w-72 bg-slate-950 text-slate-100 shadow-lg">
            <div class="px-6 py-7 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="text-xl font-semibold tracking-tight text-white">Umrah ERP</a>
                <p class="mt-2 text-sm text-slate-400">Admin Hotel Management</p>
            </div>

            <nav class="px-4 py-6 space-y-1 text-sm">
                <a href="{{ route('admin.hotel-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Hotel Management</a>
                <a href="{{ route('admin.hotels.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotels.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Hotels</a>
                <a href="{{ route('admin.hotel-room-types.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-room-types.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Room Types</a>
                <a href="{{ route('admin.hotel-seasonal-rates.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-seasonal-rates.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Seasonal Rates</a>
                <a href="{{ route('admin.hotel-meal-plans.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-meal-plans.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Meal Plans</a>
                <a href="{{ route('admin.hotel-facilities.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-facilities.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Facilities</a>
                <a href="{{ route('admin.hotel-room-inventory.index') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.hotel-room-inventory.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Room Inventory</a>
                <a href="{{ route('admin.bookings.index') }}" class="flex justify-between items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.bookings.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span>Bookings</span>
                    @php
                        $recentBookings = \App\Models\Booking::where('created_at', '>=', now()->subHours(2))->count();
                    @endphp
                    @if($recentBookings > 0)
                        <span class="inline-flex items-center rounded-full bg-rose-600 px-3 py-0.5 text-xs font-semibold text-white">{{ $recentBookings }}</span>
                    @endif
                </a>
                <!-- <a href="{{ route('admin.user-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.user-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">User Management</a> -->
                <!-- <a href="{{ route('admin.customer-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.customer-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Customer Management</a>
                <a href="{{ route('admin.agent-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.agent-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Agent Management</a>
                <a href="{{ route('admin.airline-ticket-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.airline-ticket-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Airline / Ticket</a>
                <a href="{{ route('admin.visa-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.visa-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Visa Management</a>
                <a href="{{ route('admin.reports') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.reports') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Reports</a> -->
            </nav>
        </aside>

        <div class="flex-1 p-6 xl:px-10 xl:py-8">
            <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900">@yield('page-heading', 'Admin Panel')</h1>
                    <p class="mt-2 text-sm text-slate-500">@yield('page-description', 'Manage hotel operations, bookings, rates and inventory from a unified dashboard.')</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">Dashboard</a>
                </div>
            </header>

            <main class="space-y-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
