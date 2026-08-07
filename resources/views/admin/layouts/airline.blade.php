<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Airline Management') | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen lg:flex">
        <aside class="w-full lg:w-80 bg-slate-950 text-slate-100 shadow-lg">
            <div class="px-6 py-7 border-b border-slate-800">
                <a href="{{ route('admin.airline-ticket-management') }}" class="text-xl font-semibold tracking-tight text-white">Airline ERP</a>
                <p class="mt-2 text-sm text-slate-400">Airline Ticket Management</p>
            </div>

            <nav class="px-6 py-6 space-y-6 text-sm">
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Airline Management</p>
                    <a href="{{ route('admin.airline-ticket-management') }}" class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.airline-ticket-management') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Dashboard</a>
                </div>

                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Ticket Management</p>
                    <div class="space-y-2 rounded-3xl bg-slate-900/70 px-4 py-4">
                        <a href="{{ route('admin.airline-ticket-management') }}" class="block rounded-2xl px-4 py-2 text-slate-200 hover:bg-slate-800">All Tickets</a>
                        <a href="{{ route('admin.airline-ticket-management') }}#upload" class="block rounded-2xl px-4 py-2 text-slate-200 hover:bg-slate-800">Upload Ticket</a>
                            <a href="{{ route('admin.airlines.index') }}" class="block rounded-2xl px-4 py-2 text-slate-200 hover:bg-slate-800">Airlines</a>
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Flight Status</p>
                    <div class="space-y-2 rounded-3xl bg-slate-900/70 px-4 py-4">
                        <a href="{{ route('admin.airline-flights.index', ['status' => 'Approved']) }}" class="block rounded-2xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'Approved' ? 'bg-amber-500 text-slate-950' : 'text-slate-200 hover:bg-slate-800' }}">Approved</a>
                        <a href="{{ route('admin.airline-flights.index', ['status' => 'Pending']) }}" class="block rounded-2xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'Pending' ? 'bg-amber-500 text-slate-950' : 'text-slate-200 hover:bg-slate-800' }}">Pending</a>
                        <a href="{{ route('admin.airline-flights.index', ['status' => 'Rejected']) }}" class="block rounded-2xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'Rejected' ? 'bg-amber-500 text-slate-950' : 'text-slate-200 hover:bg-slate-800' }}">Rejected</a>
                        <a href="{{ route('admin.airline-flights.index') }}" class="block rounded-2xl px-4 py-2 text-sm font-medium transition {{ request()->query('status') === null ? 'bg-amber-500 text-slate-950' : 'text-slate-200 hover:bg-slate-800' }}">All Flights</a>
                    </div>
                </div>
            </nav>
        </aside>

        <div class="flex-1 p-6 xl:px-10 xl:py-8">
            <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900">@yield('page-heading', 'Airline Ticket Management')</h1>
                    <p class="mt-2 text-sm text-slate-500">@yield('page-description', 'Manage airline ticket rules, uploads, and sales from a dedicated airline module.')</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">Main Dashboard</a>
                </div>
            </header>

            <main class="space-y-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
