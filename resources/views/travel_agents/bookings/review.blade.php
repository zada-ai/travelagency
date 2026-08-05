<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Review | {{ ($booking['initiator'] ?? null) === 'agent' ? 'Agent Portal' : 'Customer Portal' }}</title>
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

        .ticket-punch-left, .ticket-punch-right {
            position: absolute;
            width: 20px;
            height: 20px;
            background: #f4f7fc;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .ticket-punch-left {
            left: -10px;
            box-shadow: inset -2px 0 4px rgba(148, 163, 184, 0.2), 1px 0 0 rgba(59, 130, 246, 0.08);
        }

        .ticket-punch-right {
            right: -10px;
            box-shadow: inset 2px 0 4px rgba(148, 163, 184, 0.2), -1px 0 0 rgba(59, 130, 246, 0.08);
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
    @php
        // Departure and Arrival codes parsing helper
        $deptCode = $ticket->departureAirport?->code;
        $arrCode = $ticket->arrivalAirport?->code;
        $deptCity = $ticket->departureAirport?->city;
        $arrCity = $ticket->arrivalAirport?->city;

        if (!$deptCode || !$arrCode) {
            $segments = array_map('trim', explode(' - ', $ticket->route ?? ''));
            if (count($segments) === 2) {
                [$routeDept, $routeArr] = $segments;
                $deptCity = $deptCity ?: $routeDept;
                $arrCity = $arrCity ?: $routeArr;
                $deptCode = $deptCode ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $routeDept), 0, 3));
                $arrCode = $arrCode ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $routeArr), 0, 3));
            } else {
                $deptCode = $deptCode ?: ($deptCity ? strtoupper(substr($deptCity, 0, 3)) : 'UNK');
                $arrCode = $arrCode ?: ($arrCity ? strtoupper(substr($arrCity, 0, 3)) : 'UNK');
            }
        }

        $returnRoute = null;
        $retDeptCode = $ticket->returnDepartureAirport?->code;
        $retArrCode = $ticket->returnArrivalAirport?->code;
        $retDeptCity = $ticket->returnDepartureAirport?->city;
        $retArrCity = $ticket->returnArrivalAirport?->city;

        if ($ticket->return_date) {
            if (!$retDeptCode || !$retArrCode) {
                $retDeptCity = $retDeptCity ?: $arrCity;
                $retArrCity = $retArrCity ?: $deptCity;
                $retDeptCode = $retDeptCode ?: $arrCode;
                $retArrCode = $retArrCode ?: $deptCode;
            }
            $returnRoute = $retDeptCity . ' - ' . $retArrCity;
        }
    @endphp

    <!-- Mobile Header/Navigation Trigger -->
    <header class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-4 backdrop-blur-md xl:hidden sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
            </div>
            <span class="text-lg font-bold text-slate-800">{{ ($booking['initiator'] ?? null) === 'agent' ? 'Agent Portal' : 'Customer Portal' }}</span>
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
                                <p class="text-xs text-slate-500">{{ ($booking['initiator'] ?? null) === 'agent' ? 'Agent Portal System' : 'Customer Portal' }}</p>
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
                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold uppercase">
                                {{ substr($booking['contact_name'] ?? 'A', 0, 1) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">{{ ($booking['initiator'] ?? null) === 'agent' ? 'Reviewing Agent' : 'Reviewing Customer' }}</p>
                                <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ $booking['contact_name'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Navigation Links -->
                    <nav class="space-y-1">
                        <a href="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.dashboard') : route('customer.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Overview
                        </a>
                        <a href="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.tickets') : route('tickets.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 0 1 9-9" />
                            </svg>
                            Search Flights
                        </a>
                        <a href="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.bookings') : route('customer.bookings') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                            My Bookings
                        </a>
                        <a href="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.visa-applications') : route('customer.visa.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Visa Applications
                        </a>
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
                
                <!-- Toast Alerts System -->
                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-slate-800 shadow-md flex items-start gap-3 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <div>
                            <p class="font-bold text-emerald-800 text-sm">Success</p>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('info'))
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-slate-800 shadow-md flex items-start gap-3 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.085 1.086L11.25 14.25v2.25m3-6a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="font-bold text-blue-800 text-sm">Information</p>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">{{ session('info') }}</p>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-slate-800 shadow-md flex items-start gap-3 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                        <div>
                            <p class="font-bold text-rose-800 text-sm">Please correct the following errors:</p>
                            <ul class="mt-1.5 list-disc space-y-0.5 pl-4 text-xs text-slate-600 font-medium">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Section: Header Bar -->
                <section class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Flight Booking Review</span>
                        </div>
                        <h1 class="mt-2.5 text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-none">
                            Booking Review
                        </h1>
                        @if($ticket->route)
                            <p class="mt-2 text-xs md:text-sm text-slate-500 font-medium">Route: {{ $ticket->route }}</p>
                        @endif
                        <p class="mt-2 text-xs md:text-sm text-slate-500 font-medium">Confirm all details and traveler details before finalizing the reservation.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 px-5 py-3 text-right flex items-center gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Booking Status</p>
                            <p class="mt-1.5 text-sm font-black text-blue-600 uppercase">{{ $booking['status'] }}</p>
                        </div>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-[1fr_360px] xl:grid-cols-[1fr_390px] items-start">
                    <div class="space-y-6">
                        
                        <!-- Flight Details Boarding Pass (styled consistently with ticket details) -->
                        <article class="bg-white border border-slate-200 rounded-[28px] overflow-hidden relative shadow-md">
                            <!-- Top branding bar -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50/50 px-6 py-4 flex flex-wrap justify-between items-center border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/10">
                                        {{ strtoupper(substr($ticket->airline, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 leading-tight uppercase">{{ $ticket->airline }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Flight {{ $ticket->flight_number }}</p>
                                        @if($ticket->route)
                                            <p class="text-xs text-slate-400 mt-1">{{ $ticket->route }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $ticket->ticket_type }}
                                    </span>
                                    @if($ticket->refundable)
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">Refundable</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Non-Refundable</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Boarding Details Area -->
                            <div class="p-6 relative">
                                <!-- Boarding Pass Layout Grid -->
                                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 text-center">
                                    
                                    <!-- Departure -->
                                    <div class="text-left">
                                        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight uppercase">{{ $deptCode }}</p>
                                        <p class="text-xs font-bold text-blue-600 tracking-wide mt-1 uppercase">{{ $deptCity }}</p>
                                        <p class="text-xs text-slate-400 mt-1 truncate">{{ $ticket->departureAirport?->name ?? 'Departure Airport' }}</p>
                                        
                                        <!-- Date and Time -->
                                        <div class="mt-4">
                                            <p class="text-xs text-slate-400 font-bold">DEPARTURE</p>
                                            <p class="text-sm font-semibold text-slate-800 mt-1">{{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}</p>
                                            <p class="text-sm font-medium text-slate-600 mt-0.5">{{ $ticket->departure_time }}</p>
                                        </div>
                                    </div>

                                    <!-- Flight Indicator Path -->
                                    <div class="px-2 flex flex-col items-center justify-center min-w-[80px] sm:min-w-[120px] relative">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Direct</p>
                                        <div class="w-full flex items-center justify-center relative">
                                            <div class="w-full h-px border-t border-dashed border-slate-300"></div>
                                            <div class="absolute bg-white border border-slate-200 h-7 w-7 rounded-full flex items-center justify-center shadow-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-blue-500 rotate-90"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-2">FLIGHT PATH</p>
                                    </div>

                                    <!-- Arrival -->
                                    <div class="text-right">
                                        <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight uppercase">{{ $arrCode }}</p>
                                        <p class="text-xs font-bold text-blue-600 tracking-wide mt-1 uppercase">{{ $arrCity }}</p>
                                        <p class="text-xs text-slate-400 mt-1 truncate">{{ $ticket->arrivalAirport?->name ?? 'Arrival Airport' }}</p>

                                        <!-- Date and Time -->
                                        <div class="mt-4">
                                            <p class="text-xs text-slate-400 font-bold">ARRIVAL</p>
                                            <p class="text-sm font-semibold text-slate-800 mt-1">{{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}</p>
                                            <p class="text-sm font-medium text-slate-600 mt-0.5">{{ $ticket->arrival_time }}</p>
                                        </div>
                                    </div>

                                </div>

                                <!-- If round trip return journey details -->
                                @if($ticket->return_date && $returnRoute)
                                    <div class="mt-6 pt-5 border-t border-slate-100">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div class="flex items-center gap-3">
                                                <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-600 uppercase">Return flight</span>
                                                <div class="space-y-1">
                                                    <p class="text-xs font-bold text-slate-600 uppercase">{{ $retDeptCity }} → {{ $retArrCity }}</p>
                                                    <p class="text-[10px] text-slate-400">{{ $ticket->returnDepartureAirport?->name ?? 'Return Departure Airport' }} to {{ $ticket->returnArrivalAirport?->name ?? 'Return Arrival Airport' }}</p>
                                                    @if($ticket->return_departure_time || $ticket->return_arrival_time)
                                                        <p class="text-[10px] text-slate-400">Return times: {{ $ticket->return_departure_time ?? 'TBD' }} → {{ $ticket->return_arrival_time ?? 'TBD' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-slate-400 font-bold">RETURN DATE</p>
                                                <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $ticket->return_date?->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="grid gap-2 sm:grid-cols-2 mt-4">
                                            <div>
                                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Return Departure</p>
                                                <p class="text-sm font-semibold text-slate-800">{{ $ticket->return_departure_time ?? 'TBD' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Return Arrival</p>
                                                <p class="text-sm font-semibold text-slate-800">{{ $ticket->return_arrival_time ?? 'TBD' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Boarding ticket punch section -->
                            <div class="h-5 relative">
                                <div class="ticket-punch-left"></div>
                                <div class="ticket-punch-right"></div>
                                <div class="absolute inset-0 flex items-center justify-center px-4">
                                    <div class="w-full border-t-2 border-dashed border-slate-200"></div>
                                </div>
                            </div>

                            <!-- Boarding pass bottom parameters grid -->
                            <div class="bg-slate-50/50 p-6 grid gap-4 grid-cols-2 md:grid-cols-4 border-t border-slate-100">
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Baggage Limit</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1.5 block flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                        {{ $ticket->baggage ?? 'Standard Weight' }} Kg
                                    </span>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">In-flight Meal</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1.5 block flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697-.056-4.024-.166C6.845 7.91 6 6.899 6 5.756c0-1.25.992-2.226 2.25-2.25 1.257-.024 2.522-.036 3.75-.036s2.493.012 3.75.036c1.258.024 2.25.999 2.25 2.25 0 1.144-.845 2.154-1.976 2.328A39.227 39.227 0 0112 8.25zm0 0v1.5m0-1.5c1.355 0 2.697.056 4.024.166C17.155 8.09 18 9.101 18 10.244c0 1.25-.992 2.226-2.25 2.25-1.257.024-2.522.036-3.75.036s-2.493-.012-3.75-.036c-1.258-.024-2.25-.999-2.25-2.25 0-1.144.845-2.154 1.976-2.328A39.227 39.227 0 0112 8.25zm0 1.5v5.625c0 .621-.504 1.125-1.125 1.125H4.5m15 0H13.125c-.621 0-1.125-.504-1.125-1.125V9.75" /></svg>
                                        {{ $ticket->meal === 'yes' || $ticket->meal === '1' ? 'Included' : 'Not Included' }}
                                    </span>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Cabin Class</span>
                                    <span class="text-sm font-bold text-blue-600 mt-1.5 block tracking-wider uppercase">{{ $booking['cabin_class'] }}</span>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Reference Code</span>
                                    <span class="text-sm font-bold text-slate-800 mt-1.5 block tracking-wider font-mono">{{ $ticket->reference }}</span>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Ticket Status</span>
                                    <span class="text-sm font-semibold text-slate-800 mt-1.5 block">{{ $ticket->status }}</span>
                                </div>
                                <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Visibility</span>
                                    <span class="text-sm font-semibold text-slate-800 mt-1.5 block">{{ $ticket->visibility ?? 'Both' }}</span>
                                </div>
                            </div>
                        </article>

                        <!-- Passenger Details Cards -->
                        <article class="glass-panel rounded-[28px] p-6 shadow-xs">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Traveler Details</span>
                                    <h3 class="text-xl font-bold text-slate-900">Passenger Document Review</h3>
                                </div>
                                <div class="h-8 rounded-full bg-blue-50 px-4 flex items-center justify-center text-xs font-extrabold text-blue-600 border border-blue-100">
                                    Total: {{ $booking['total_passengers'] }}
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                @foreach($booking['passengers'] as $index => $passenger)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 text-slate-850">
                                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4 mb-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="h-7 w-7 rounded-lg bg-blue-600 flex items-center justify-center text-xs font-bold text-white shadow-xs">
                                                    {{ $index + 1 }}
                                                </div>
                                                <h4 class="text-sm font-bold text-slate-900">
                                                    {{ $passenger['full_name'] ?? trim(($passenger['first_name'] ?? '') . ' ' . ($passenger['last_name'] ?? '')) ?: 'Passenger' }}
                                                </h4>
                                            </div>
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold text-blue-600 uppercase tracking-wider">
                                                {{ $passenger['passenger_type'] ?? 'Unknown' }} • {{ $passenger['gender'] ?? 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="grid gap-4 sm:grid-cols-3 text-sm">
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Passport Upload</p>
                                                @if(! empty($passenger['passport_upload']))
                                                    <a href="{{ asset('storage/' . $passenger['passport_upload']) }}" target="_blank" class="block mt-2 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                                                        <img src="{{ asset('storage/' . $passenger['passport_upload']) }}" alt="Passport upload" class="h-28 w-full object-contain" />
                                                    </a>
                                                @else
                                                    <p class="mt-1 font-bold text-slate-800">No file uploaded</p>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">CNIC Upload</p>
                                                @if(! empty($passenger['cnic_upload']))
                                                    <a href="{{ asset('storage/' . $passenger['cnic_upload']) }}" target="_blank" class="block mt-2 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                                                        <img src="{{ asset('storage/' . $passenger['cnic_upload']) }}" alt="CNIC upload" class="h-28 w-full object-contain" />
                                                    </a>
                                                @else
                                                    <p class="mt-1 font-bold text-slate-800">No file uploaded</p>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date of Birth / Nationality</p>
                                                <p class="mt-1 font-bold text-slate-800">{{ $passenger['date_of_birth'] ?? 'N/A' }} / {{ $passenger['nationality'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    </div>

                    <!-- Sticky Fare Summary & Receipt card -->
                    <aside class="space-y-6 lg:sticky lg:top-6">
                        <article class="glass-panel rounded-[28px] p-6 shadow-xs relative overflow-hidden">
                            <!-- Subtle shining overlay border -->
                            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>
                            
                            <div>
                                <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Reservation</span>
                                <h3 class="text-xl font-bold text-slate-900">Booking Summary</h3>
                            </div>

                            <div class="mt-6 space-y-3 text-sm text-slate-600">
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Adult Passenger Count</span>
                                    <span class="font-bold text-slate-800">{{ $booking['adults'] }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Child Passenger Count</span>
                                    <span class="font-bold text-slate-800">{{ $booking['children'] }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Infant Passenger Count</span>
                                    <span class="font-bold text-slate-800">{{ $booking['infants'] }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Total Passenger Count</span>
                                    <span class="font-bold text-slate-800">{{ $booking['total_passengers'] }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Seat Assignment</span>
                                    <span class="font-bold text-blue-600 font-mono tracking-wider">{{ implode(', ', $booking['seat_numbers']) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Cabin Class</span>
                                    <span class="font-bold text-slate-800">{{ $booking['cabin_class'] ?? 'Economy' }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span>Booking Date</span>
                                    <span class="font-bold text-slate-800">{{ now()->format('d M Y') }}</span>
                                </div>
                            </div>
                        </article>

                        <article class="glass-panel rounded-[28px] p-6 shadow-xs relative overflow-hidden">
                            <div>
                                <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Receipt Invoice</span>
                                <h3 class="text-xl font-bold text-slate-900">Payment Breakdown</h3>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Selected Cabin Class Price</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['selected_cabin_price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Estimated Per Passenger Price</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['subtotal'] / max($booking['total_passengers'], 1), 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Base Subtotal</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['subtotal'], 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Taxes</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['taxes'], 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Visa Add-on</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['visa_price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">Transport Add-on</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['transport_price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                    <span class="text-xs text-slate-500 font-medium">ERP Service Charge</span>
                                    <span class="text-sm font-bold text-slate-800">SAR {{ number_format($booking['service_charge'], 2) }}</span>
                                </div>
                                
                                <div class="py-2">
                                    <div class="border-t border-dashed border-slate-200"></div>
                                </div>

                                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Grand Total</span>
                                        <span class="text-xl font-extrabold text-blue-900">SAR {{ number_format($booking['grand_total'], 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions Group -->
                            <div class="mt-6 space-y-3">
                                <form action="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.bookings.confirm') : route('bookings.confirm') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-750 hover:to-indigo-750 text-white font-extrabold text-sm py-4 shadow-lg shadow-blue-500/10 transition duration-200 transform active:scale-[0.98]">
                                        Confirm Booking
                                    </button>
                                </form>
                                
                                <form action="{{ ($booking['initiator'] ?? null) === 'agent' ? route('travel-agents.bookings.cancel-review') : route('bookings.cancel-review') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full rounded-2xl border border-rose-200 bg-rose-50 text-rose-700 font-bold hover:bg-rose-100/50 py-3.5 text-xs transition duration-205">
                                        Cancel Booking
                                    </button>
                                </form>

                                <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="block w-full text-center rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 py-3.5 text-xs font-semibold text-slate-600 transition duration-200">
                                    Back to Edit
                                </a>
                            </div>
                        </article>
                    </aside>
                </section>
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
