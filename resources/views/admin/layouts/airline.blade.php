<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Airline Management') | Umrah ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }
        .sidebar-active {
            background: linear-gradient(135deg, #2563eb 0%, #059669 100%);
            color: #ffffff !important;
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.35);
        }
        .sidebar-active i, .sidebar-active span {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased flex flex-col">
    <div class="min-h-screen lg:flex">
        {{-- Sidebar --}}
        <aside class="w-full lg:w-72 bg-white border-r border-slate-200/90 text-slate-800 shadow-xs flex flex-col justify-between">
            <div class="p-5">
                {{-- Brand Header --}}
                <div class="mb-6 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-emerald-500 p-2 shadow-md shadow-blue-500/20 flex items-center justify-center text-white">
                        <i class="bi bi-airplane-engines text-lg"></i>
                    </div>
                    <div>
                        <a href="{{ route('admin.airline-ticket-management') }}" class="font-extrabold text-base text-slate-900 tracking-tight block">Airline ERP</a>
                        <p class="text-[11px] font-semibold text-slate-400">Flight &amp; Ticketing Hub</p>
                    </div>
                </div>

                <nav class="space-y-6 text-sm">
                    <div>
                        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                            <i class="bi bi-grid-fill text-xs"></i>
                            <span>Navigation</span>
                        </p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.airline-ticket-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ request()->routeIs('admin.airline-ticket-management') ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="bi bi-speedometer2 text-base {{ request()->routeIs('admin.airline-ticket-management') ? 'text-white' : 'text-blue-600' }}"></i>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('admin.airline-bookings.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ request()->routeIs('admin.airline-bookings.*') ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="bi bi-journal-bookmark-fill text-base {{ request()->routeIs('admin.airline-bookings.*') ? 'text-white' : 'text-sky-600' }}"></i>
                                <span>Flight Bookings</span>
                            </a>
                            <a href="{{ route('admin.airlines.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ request()->routeIs('admin.airlines.*') ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="bi bi-building text-base {{ request()->routeIs('admin.airlines.*') ? 'text-white' : 'text-emerald-600' }}"></i>
                                <span>Airlines Catalog</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-1.5">
                            <i class="bi bi-airplane-fill text-xs"></i>
                            <span>Flight Status Filter</span>
                        </p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.airline-flights.index', ['status' => 'Approved']) }}" class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ request('status') === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>Approved Flights</span>
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            </a>
                            <a href="{{ route('admin.airline-flights.index', ['status' => 'Pending']) }}" class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ request('status') === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>Pending Flights</span>
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                            </a>
                            <a href="{{ route('admin.airline-flights.index', ['status' => 'Rejected']) }}" class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ request('status') === 'Rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>Rejected Flights</span>
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            </a>
                            <a href="{{ route('admin.airline-flights.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-semibold transition {{ request()->routeIs('admin.airline-flights.index') && request()->query('status') === null ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>All Flights</span>
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100">
                <a href="{{ route('dashboard') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition">
                    <i class="bi bi-arrow-left"></i>
                    <span>Super Admin Main Hub</span>
                </a>
            </div>
        </aside>

        {{-- Main Container --}}
        <div class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200/80">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">@yield('page-heading', 'Airline Ticket Management')</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium">@yield('page-description', 'Manage airline ticket rules, uploads, and flight bookings from a dedicated portal.')</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:opacity-95 transition">
                        <i class="bi bi-grid-fill"></i>
                        <span>Main Dashboard</span>
                    </a>
                </div>
            </header>

            <main class="space-y-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
