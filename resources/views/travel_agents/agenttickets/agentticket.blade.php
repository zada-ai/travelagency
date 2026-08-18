<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search & Book Flight Tickets | Agent Portal</title>
    <!-- Google Fonts: Plus Jakarta Sans for high-end typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f4f7fc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 8px 30px rgba(148, 163, 184, 0.08);
        }

        .premium-input {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(203, 213, 225, 0.8);
            color: #0f172a;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background: #ffffff;
            outline: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.1);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">

    <!-- Mobile Header/Navigation Trigger -->
    <header class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-4 backdrop-blur-md xl:hidden sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
            </div>
            <span class="text-lg font-bold text-slate-800">Agent Portal</span>
        </div>
        <button id="mobileMenuToggle" class="rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-slate-500 hover:text-slate-800 transition hover:bg-slate-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </header>

    <!-- Mobile Drawer Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300 xl:hidden"></div>

    <div class="min-h-screen">
        <!-- Main Layout Split -->
        <div class="grid min-h-screen xl:grid-cols-[280px_1fr] relative">
            
            <!-- Sidebar (Off-canvas on mobile, fixed-width sidebar on desktop) -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] -translate-x-full border-r border-slate-200 bg-white p-6 transition-transform duration-350 cubic-bezier(0.4, 0, 0.2, 1) xl:static xl:translate-x-0 flex flex-col justify-between shadow-xs">
                <div class="space-y-8">
                    <!-- Brand Section -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 tracking-tight">Hujaj Umrah</h1>
                                <p class="text-xs text-slate-500">Agent Portal System</p>
                            </div>
                        </div>
                        <button id="mobileMenuClose" class="xl:hidden p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Profile Quick Info -->
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center text-white font-semibold uppercase">
                                @if(!empty($agent->company_logo))
                                    <img src="{{ asset('storage/'.$agent->company_logo) }}" alt="{{ $agent->company_name ?? 'Company Logo' }}" class="h-full w-full object-cover" />
                                @else
                                    {{ substr($agent->company_name ?? 'A', 0, 1) }}
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">Agency Company</p>
                                <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ $agent->company_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Navigation Links -->
                    <nav class="space-y-1">
                        <a href="{{ route('travel-agents.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Overview
                        </a>
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 0 1 9-9" />
                            </svg>
                            Search Flights
                        </a>
                        <a href="{{ route('travel-agents.bookings') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                            My Bookings
                        </a>
                        
    {{-- <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
        Visa Applications
    </a> --}}

                    </nav>
                </div>

                <!-- Support Box -->
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner mt-8">
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 mb-2">24/7 Support</span>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Need instant booking assistance? Reach out directly via WhatsApp.</p>
                    <a href="https://wa.me/923123456789" target="_blank" class="mt-3.5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white py-2.5 shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97a16.607 16.607 0 0 0 6.585 6.585l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" /></svg>
                        WhatsApp Support
                    </a>
                </div>
            </aside>

            <!-- Main Scrollable Section -->
            <main class="p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    <!-- Header Summary panel -->
                    <header class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Ticket Management</span>
                            </div>
                            <h1 class="mt-3 text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Search & Book Flight Tickets</h1>
                            <p class="mt-3 text-sm text-slate-500 font-medium max-w-2xl">Use the filters below to find flight inventory quickly and book for your agency clients.</p>
                        </div>
                        <div class="grid gap-3 grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 px-5 py-4 text-slate-600">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Active Tickets</p>
                                <p class="mt-2 text-2xl font-black text-slate-900 leading-none">{{ count($tickets) }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 px-5 py-4 text-slate-600">
                                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Next Departure</p>
                                <p class="mt-2 text-md font-bold text-blue-600 leading-none truncate">{{ $tickets[0]['trip_date'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </header>

                    <!-- Filter panel -->
                    <section class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs">
                        <form action="{{ url()->current() }}" method="GET" class="grid gap-4 xl:grid-cols-[1.4fr_0.6fr] items-end">
                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">From</label>
                                    <input type="text" name="from" value="{{ request('from') }}" placeholder="e.g. Islamabad" class="w-full rounded-2xl premium-input px-4 py-3 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">To</label>
                                    <input type="text" name="to" value="{{ request('to') }}" placeholder="e.g. Jeddah" class="w-full rounded-2xl premium-input px-4 py-3 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Departure Date</label>
                                    <input type="date" name="departure" value="{{ request('departure') }}" class="w-full rounded-2xl premium-input px-4 py-3 text-sm" />
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Return Date</label>
                                    <input type="date" name="return" value="{{ request('return') }}" class="w-full rounded-2xl premium-input px-4 py-3 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Airline</label>
                                    <select name="airline" class="w-full rounded-2xl premium-input px-4 py-3 text-sm font-semibold">
                                        <option value="">All Airlines</option>
                                        <option value="PIA" {{ request('airline') === 'PIA' ? 'selected' : '' }}>PIA</option>
                                        <option value="Saudi Airlines" {{ request('airline') === 'Saudi Airlines' ? 'selected' : '' }}>Saudi Airlines</option>
                                        <option value="Emirates" {{ request('airline') === 'Emirates' ? 'selected' : '' }}>Emirates</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-750 hover:to-indigo-750 text-white font-extrabold text-sm py-3.5 shadow-md shadow-blue-500/10 transition">
                                    Search Flights
                                </button>
                            </div>
                        </form>
                    </section>

                    <!-- Tickets Inventory Listing Grid -->
                    <section class="space-y-5">
                        @forelse ($tickets as $ticket)
                            @php
                                $route = $ticket->route ?? 'ISB - JED';
                                $airline = $ticket->airline ?? 'PIA';
                                $flightNumber = $ticket->flight_number ?? 'PK-201';
                                $departureTime = $ticket->departure_time ?? '23:10';
                                $arrivalTime = $ticket->arrival_time ?? '04:25';
                                $departureDate = $ticket->departure_date?->format('Y-m-d') ?? $ticket->trip_date ?? '2025-08-05';
                                $returnDate = $ticket->return_date?->format('Y-m-d');
                                $returnRoute = null;
                                if ($returnDate && str_contains($route, ' - ')) {
                                    $segments = explode(' - ', $route);
                                    $returnRoute = implode(' - ', array_reverse($segments));
                                }
                                $baggage = $ticket->baggage ?? '30';
                                $meal = $ticket->meal ?? '1';
                                $price = $ticket->price ? 'SAR '.number_format($ticket->price, 2) : 'SAR 24,400';
                                $status = $ticket->status ?? 'Approved';

                                // Simple codes parsing helper for short badges
                                $cleanRoute = trim($route, " \t\n\r\0\x0B.");
                                $routeSegments = explode(' - ', $cleanRoute);
                                $deptShort = 'ISB';
                                $arrShort = 'JED';
                                if (count($routeSegments) >= 2) {
                                    $dCity = trim(preg_replace('/^[a-z]{2}\s+/i', '', $routeSegments[0]));
                                    $aCity = trim($routeSegments[1]);
                                    $deptShort = strtoupper(substr($dCity, 0, 3));
                                    $arrShort = strtoupper(substr($aCity, 0, 3));
                                }
                            @endphp
                            <article class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs border border-slate-200/60 hover:border-blue-500/20 transition-all duration-200">
                                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between border-b border-slate-100 pb-5">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-5">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-xl font-bold text-white shadow-md shadow-blue-500/10 flex-shrink-0">
                                            {{ strtoupper(substr($airline, 0, 2)) }}
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-xs uppercase tracking-widest text-slate-400 font-bold">{{ $airline }}</span>
                                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600">{{ $flightNumber }}</span>
                                                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600">{{ $status }}</span>
                                            </div>
                                            <h2 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-none uppercase">
                                                {{ $deptShort }} → {{ $arrShort }} <span class="text-sm font-medium text-slate-400 ml-1.5 lowercase">({{ $route }})</span>
                                            </h2>
                                            <p class="text-xs text-slate-400 font-medium">Ticket reference: <span class="font-bold text-slate-700 font-mono tracking-wider">{{ $ticket->reference }}</span></p>
                                        </div>
                                    </div>

                                    <!-- Flight specifications grid -->
                                    <div class="grid gap-3 grid-cols-2 md:grid-cols-4 xl:items-center">
                                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 min-w-[100px]">
                                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Departure</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800">{{ $departureTime }}</p>
                                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $departureDate }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 min-w-[100px]">
                                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Arrival</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800">{{ $arrivalTime }}</p>
                                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $returnDate ? $returnDate : 'No return leg' }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 min-w-[100px]">
                                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Baggage / Meals</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800">{{ $baggage }} Kg</p>
                                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $meal === 'yes' || $meal === '1' ? 'Food Included' : 'No Meals' }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-blue-50/50 border border-blue-100/50 p-3 min-w-[100px]">
                                            <p class="text-[9px] uppercase tracking-wider text-blue-500 font-bold">Starting price</p>
                                            <p class="mt-1 text-sm font-black text-blue-900">{{ $price }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Base fare</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card footer parameters & Book action -->
                                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex gap-6 text-xs text-slate-500 font-medium">
                                        <div>
                                            <span class="text-slate-400 font-bold uppercase tracking-wider block">Remaining Flight Seats</span>
                                            <span class="text-slate-800 font-black mt-1 block flex items-center gap-1.5">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                {{ $ticket->available_seats }} / {{ $ticket->total_seats }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 font-bold uppercase tracking-wider block">Cabin Seats Available</span>
                                            <span class="text-slate-800 font-bold mt-1 block">
                                                Eco: {{ $ticket->getClassAvailableSeats('Economy') }} · Biz: {{ $ticket->getClassAvailableSeats('Business') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 self-end sm:self-auto">
                                        @if($ticket->status === 'Approved' && $ticket->available_seats > 0)
                                            <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white hover:shadow-md transition px-6 py-3 font-extrabold text-sm shadow-sm">
                                                Book Tickets
                                            </a>
                                        @else
                                            <span class="inline-flex items-center justify-center rounded-2xl bg-slate-200 px-6 py-3 text-xs font-bold text-slate-500">
                                                {{ $ticket->available_seats <= 0 ? 'Sold Out' : 'Unavailable' }}
                                            </span>
                                        @endif
                                        <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition px-5 py-3 font-bold text-sm shadow-xs">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="glass-panel rounded-3xl p-12 text-center text-slate-400 border border-dashed border-slate-200">
                                <p class="text-base font-bold text-slate-600">No flights found</p>
                                <p class="mt-1.5 text-xs text-slate-400 max-w-sm mx-auto">There are no approved flight tickets available at the moment. Try updating the filters or checking back later.</p>
                            </div>
                        @endforelse
                    </section>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripting for Mobile Sidebar Drawer responsive toggle -->
    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (mobileMenuToggle && sidebar && sidebarOverlay) {
            mobileMenuToggle.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        }

        const closeSidebar = () => {
            if (sidebar && sidebarOverlay) {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
