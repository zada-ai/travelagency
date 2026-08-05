<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Tickets | Umrah ERP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fb;
        }

        .sidebar {
            width: 270px;
            background: #ffffff;
            border-right: 1px solid #e8edf5;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .sidebar-link:hover {
            background: #f1f5ff;
            color: #2563eb;
        }

        .sidebar-link.active {
            background: #eaf1ff;
            color: #2563eb;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .main-bg {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .07), transparent 28%),
                #f5f7fb;
        }

        .panel {
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
        }

        .flight-card {
            transition: .2s ease;
        }

        .flight-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        }

        .mobile-menu {
            display: none;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 230px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .mobile-menu {
                display: flex;
            }
        }
    </style>
</head>

<body class="min-h-screen text-slate-700">

<div class="flex min-h-screen">

    {{-- =========================================================
         CUSTOMER SIDEBAR
    ========================================================== --}}
    <aside class="sidebar fixed inset-y-0 left-0 z-40 flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-100">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                <svg width="23" height="23" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12L3 4L8 12L3 20L21 12Z"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linejoin="round"/>
                    <path d="M8 12H21"
                          stroke="currentColor"
                          stroke-width="2"
                          stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <h1 class="text-base font-extrabold text-slate-900">
                    Umrah ERP
                </h1>
                <p class="text-xs text-slate-400">
                    Customer Portal
                </p>
            </div>
        </div>

        {{-- Customer --}}
        <div class="px-5 pt-5">
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-[.22em] text-slate-400">
                    Customer Account
                </p>

                <div class="mt-3 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'C', 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900">
                            {{ auth()->user()->name ?? 'Customer' }}
                        </p>

                        <p class="truncate text-xs text-slate-400">
                            {{ auth()->user()->email ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-4 py-5">

            <p class="px-3 pb-3 text-[10px] font-bold uppercase tracking-[.25em] text-slate-400">
                Main Menu
            </p>

            <div class="space-y-1">

                <a href="{{ route('customer.dashboard') ?? '#' }}"
                   class="sidebar-link">
                    <span class="sidebar-icon">⌂</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('packages.index') ?? '#' }}"
                   class="sidebar-link">
                    <span class="sidebar-icon">✦</span>
                    <span>Build Package</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">◇</span>
                    <span>My Quotes</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">▣</span>
                    <span>My Bookings</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">▤</span>
                    <span>Documents</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">◉</span>
                    <span>Visa Status</span>
                </a>

                {{-- ACTIVE --}}
                <a href="{{ route('tickets.index') }}"
                   class="sidebar-link active">
                    <span class="sidebar-icon">✈</span>
                    <span>My Tickets</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">▱</span>
                    <span>Vouchers</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">▧</span>
                    <span>Invoices</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">↔</span>
                    <span>Payment History</span>
                </a>

                <a href="#"
                   class="sidebar-link">
                    <span class="sidebar-icon">⚙</span>
                    <span>My Profile</span>
                </a>

            </div>
        </nav>

        {{-- Logout --}}
        <div class="border-t border-slate-100 p-4">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="sidebar-link w-full text-left hover:bg-red-50 hover:text-red-600">
                    <span class="sidebar-icon">↪</span>
                    <span>Logout</span>
                </button>
            </form>

        </div>
    </aside>


    {{-- =========================================================
         MAIN AREA
    ========================================================== --}}
    <main class="main-bg min-h-screen flex-1 md:ml-[270px]">

        {{-- Mobile Header --}}
        <div class="mobile-menu items-center justify-between border-b border-slate-200 bg-white px-4 py-4">

            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">
                    ✈
                </div>

                <div>
                    <p class="font-bold text-slate-900">Umrah ERP</p>
                    <p class="text-xs text-slate-400">Customer Portal</p>
                </div>
            </div>

            <button class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
                ☰
            </button>
        </div>


        <div class="mx-auto max-w-[1450px] px-5 py-6 sm:px-7 lg:px-9">

            {{-- =================================================
                 TOP HEADER
            ================================================== --}}
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[.25em] text-blue-600">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        Flight Management
                    </div>

                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Search & Book Flights
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Search available flights, compare cabin fares and choose the best option
                        for your Umrah journey.
                    </p>
                </div>

                <div class="flex gap-3">

                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            Active Flights
                        </p>

                        <p class="mt-1 text-2xl font-extrabold text-slate-900">
                            {{ $tickets->count() }}
                        </p>
                    </div>

                    <div class="hidden rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm sm:block">
                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            Availability
                        </p>

                        <p class="mt-1 text-sm font-bold text-emerald-600">
                            Live
                        </p>
                    </div>

                </div>

            </div>


            {{-- =================================================
                 SEARCH BOX
            ================================================== --}}
            <section class="panel mb-6 p-5 sm:p-6">

                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[.25em] text-blue-600">
                            Search Flights
                        </p>

                        <h2 class="mt-1 text-xl font-extrabold text-slate-900">
                            Find your journey
                        </h2>
                    </div>

                    <p class="text-xs text-slate-400">
                        Search by route, date or airline
                    </p>

                </div>

                <form method="GET"
                      action="{{ route('tickets.index') }}"
                      class="grid gap-4 lg:grid-cols-12">

                    {{-- FROM --}}
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            From / Route
                        </label>

                        <input type="text"
                               name="from"
                               value="{{ request('from') }}"
                               placeholder="Islamabad"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    {{-- TO --}}
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            To / Route
                        </label>

                        <input type="text"
                               name="to"
                               value="{{ request('to') }}"
                               placeholder="Jeddah"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    {{-- DEPARTURE --}}
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            Departure
                        </label>

                        <input type="date"
                               name="departure"
                               value="{{ request('departure') }}"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    {{-- RETURN --}}
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            Return
                        </label>

                        <input type="date"
                               name="return"
                               value="{{ request('return') }}"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    {{-- AIRLINE --}}
                    <div class="lg:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                            Airline
                        </label>

                        <input type="text"
                               name="airline"
                               value="{{ request('airline') }}"
                               placeholder="PIA"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex items-end lg:col-span-2">

                        <button type="submit"
                                class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">
                            Search Flights
                        </button>

                    </div>

                </form>

            </section>


            {{-- =================================================
                 RESULTS HEADER
            ================================================== --}}
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">
                        Available Flights
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Showing {{ $tickets->count() }} customer-visible flights.
                    </p>
                </div>

                <div class="flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Live availability
                </div>

            </div>


            {{-- =================================================
                 FLIGHTS
            ================================================== --}}
            @if($tickets->isEmpty())

                <section class="panel p-12 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-2xl">
                        ✈
                    </div>

                    <h3 class="mt-5 text-xl font-extrabold text-slate-900">
                        No flights found
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        No flights match your current search. Try another route,
                        date or airline.
                    </p>

                </section>

            @else

                <div class="space-y-4">

                    @foreach($tickets as $ticket)

                        @php
                            $economyPrice = $ticket->getCabinPrice('Economy');
                            $premiumPrice = $ticket->getCabinPrice('Premium Economy');
                            $businessPrice = $ticket->getCabinPrice('Business');
                            $firstPrice = $ticket->getCabinPrice('First');

                            $bestPrice = $economyPrice ?? $ticket->adult_price ?? $ticket->price;

                            $routeLabel = $ticket->route
                                ?: trim(($ticket->departureAirport?->city ?? '') . ' - ' . ($ticket->arrivalAirport?->city ?? ''));

                            $departureCode = $ticket->departureAirport?->code ?? '---';
                            $arrivalCode = $ticket->arrivalAirport?->code ?? '---';
                        @endphp


                        <article class="flight-card overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-sm">

                            {{-- TOP --}}
                            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

                                <div class="flex items-center gap-4">

                                    {{-- Airline Logo --}}
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white shadow-lg shadow-blue-100">
                                        {{ strtoupper(substr($ticket->airline ?? 'FL', 0, 2)) }}
                                    </div>

                                    <div>

                                        <div class="flex flex-wrap items-center gap-2">

                                            <span class="text-sm font-extrabold text-slate-900">
                                                {{ $ticket->airline }}
                                            </span>

                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                {{ $ticket->flight_number }}
                                            </span>

                                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600">
                                                {{ $ticket->status }}
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $ticket->ticket_type }}
                                            @if($ticket->reference)
                                                · Ref {{ $ticket->reference }}
                                            @endif
                                        </p>

                                    </div>

                                </div>


                                {{-- DATE --}}
                                <div class="rounded-2xl bg-slate-50 px-5 py-3 text-center">

                                    <p class="text-[9px] font-bold uppercase tracking-[.2em] text-slate-400">
                                        Departure
                                    </p>

                                    <p class="mt-1 text-sm font-extrabold text-slate-900">
                                        {{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}
                                    </p>

                                </div>

                            </div>


                            {{-- MAIN --}}
                            <div class="grid gap-6 px-5 py-6 sm:px-6 lg:grid-cols-[1fr_auto]">

                                <div>

                                    {{-- ROUTE --}}
                                    <div class="flex items-center gap-4">

                                        <div class="min-w-[70px]">
                                            <p class="text-2xl font-extrabold text-slate-900">
                                                {{ $departureCode }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $ticket->departure_time ?? '--:--' }}
                                            </p>
                                        </div>


                                        <div class="flex flex-1 items-center gap-3">

                                            <div class="h-px flex-1 bg-slate-200"></div>

                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                                ✈
                                            </div>

                                            <div class="h-px flex-1 bg-slate-200"></div>

                                        </div>


                                        <div class="min-w-[70px] text-right">

                                            <p class="text-2xl font-extrabold text-slate-900">
                                                {{ $arrivalCode }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $ticket->arrival_time ?? '--:--' }}
                                            </p>

                                        </div>

                                    </div>


                                    <div class="mt-2 text-center text-xs text-slate-400">
                                        {{ $routeLabel }}
                                    </div>


                                    {{-- INFO --}}
                                    <div class="mt-6 grid gap-3 sm:grid-cols-3">

                                        <div class="rounded-2xl bg-slate-50 p-4">

                                            <p class="text-[9px] font-bold uppercase tracking-[.2em] text-slate-400">
                                                Seats Available
                                            </p>

                                            <p class="mt-2 text-sm font-extrabold text-slate-900">
                                                {{ $ticket->available_seats }}
                                                <span class="font-medium text-slate-400">
                                                    / {{ $ticket->total_seats }}
                                                </span>
                                            </p>

                                        </div>


                                        <div class="rounded-2xl bg-slate-50 p-4">

                                            <p class="text-[9px] font-bold uppercase tracking-[.2em] text-slate-400">
                                                Baggage
                                            </p>

                                            <p class="mt-2 text-sm font-extrabold text-slate-900">
                                                {{ $ticket->baggage ?: 'Not specified' }}
                                            </p>

                                        </div>


                                        <div class="rounded-2xl bg-slate-50 p-4">

                                            <p class="text-[9px] font-bold uppercase tracking-[.2em] text-slate-400">
                                                Meal
                                            </p>

                                            <p class="mt-2 text-sm font-extrabold text-slate-900">
                                                {{ $ticket->meal ?: 'Not specified' }}
                                            </p>

                                        </div>

                                    </div>


                                    {{-- RETURN --}}
                                    @if($ticket->return_date)

                                        <div class="mt-4 flex flex-wrap items-center gap-3 rounded-2xl border border-blue-100 bg-blue-50/60 px-4 py-3">

                                            <span class="text-[10px] font-bold uppercase tracking-[.2em] text-blue-500">
                                                Return
                                            </span>

                                            <span class="font-bold text-slate-900">
                                                {{ $ticket->return_date?->format('d M Y') }}
                                            </span>

                                            @if($ticket->return_departure_time)

                                                <span class="text-slate-300">•</span>

                                                <span class="text-sm text-slate-600">
                                                    {{ $ticket->return_departure_time }}
                                                    →
                                                    {{ $ticket->return_arrival_time }}
                                                </span>

                                            @endif

                                        </div>

                                    @endif

                                </div>


                                {{-- PRICE / ACTION --}}
                                <div class="w-full lg:w-[290px]">

                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">

                                        <p class="text-[10px] font-bold uppercase tracking-[.2em] text-slate-400">
                                            Starting Price
                                        </p>

                                        <div class="mt-2 flex items-end gap-2">

                                            <span class="text-3xl font-extrabold text-slate-900">
                                                SAR {{ number_format($bestPrice, 2) }}
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-slate-400">
                                            Per passenger · Economy
                                        </p>


                                        {{-- CABIN PRICES --}}
                                        <div class="mt-5 space-y-2">

                                            @if($economyPrice !== null)
                                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs border border-slate-100">
                                                    <span class="text-slate-500">Economy</span>
                                                    <span class="font-bold text-slate-900">
                                                        SAR {{ number_format($economyPrice, 0) }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if($premiumPrice !== null)
                                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs border border-slate-100">
                                                    <span class="text-slate-500">Premium Economy</span>
                                                    <span class="font-bold text-slate-900">
                                                        SAR {{ number_format($premiumPrice, 0) }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if($businessPrice !== null)
                                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs border border-slate-100">
                                                    <span class="text-slate-500">Business</span>
                                                    <span class="font-bold text-slate-900">
                                                        SAR {{ number_format($businessPrice, 0) }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if($firstPrice !== null)
                                                <div class="flex items-center justify-between rounded-xl bg-white px-3 py-2 text-xs border border-slate-100">
                                                    <span class="text-slate-500">First</span>
                                                    <span class="font-bold text-slate-900">
                                                        SAR {{ number_format($firstPrice, 0) }}
                                                    </span>
                                                </div>
                                            @endif

                                        </div>


                                        {{-- ACTIONS --}}
                                        <div class="mt-5 grid gap-2">

                                            <a href="{{ route('ticket.details', $ticket) }}"
                                               class="flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                                                View Details
                                            </a>

                                            <a href="{{ route('ticket.details', $ticket) }}"
                                               class="flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                                                Book Ticket
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @endif

        </div>

    </main>

</div>

</body>
</html>