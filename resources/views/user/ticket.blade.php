<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Booking Details | {{ Auth::guard('travel_agent')->check() ? 'Travel Agent Portal' : 'Customer Portal' }}</title>
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

        .glass-panel-hover:hover {
            border-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 10px 30px -15px rgba(59, 130, 246, 0.15);
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

        /* Smooth inputs focus glow */
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
    @php
        // Elegant Departure/Arrival parsing helper
        $deptCode = $ticket->departureAirport?->code;
        $arrCode = $ticket->arrivalAirport?->code;
        $deptCity = $ticket->departureAirport?->city;
        $arrCity = $ticket->arrivalAirport?->city;

        if (!$deptCode || !$arrCode) {
            $segments = array_map('trim', explode(' - ', $ticket->route ?? ''));
            if (count($segments) === 2) {
                [$routeDept, $routeArr] = $segments;
                $deptCity = $deptCity ?: $ticket->departureAirport?->city ?: $ticket->departureAirport?->name ?: $routeDept;
                $arrCity = $arrCity ?: $ticket->arrivalAirport?->city ?: $ticket->arrivalAirport?->name ?: $routeArr;
                $deptCode = $deptCode ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $routeDept), 0, 3));
                $arrCode = $arrCode ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $routeArr), 0, 3));
            } else {
                $deptCity = $deptCity ?: $ticket->departureAirport?->city ?: $ticket->departureAirport?->name ?: ($ticket->route ? trim($ticket->route) : 'Departure');
                $arrCity = $arrCity ?: $ticket->arrivalAirport?->city ?: $ticket->arrivalAirport?->name ?: ($ticket->route ? trim($ticket->route) : 'Arrival');
                $deptCode = $deptCode ?: ($ticket->departureAirport?->code ?: ($deptCity ? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $deptCity), 0, 3)) : 'UNK'));
                $arrCode = $arrCode ?: ($ticket->arrivalAirport?->code ?: ($arrCity ? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $arrCity), 0, 3)) : 'UNK'));
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
            <span class="text-lg font-bold text-slate-800">{{ Auth::guard('travel_agent')->check() ? 'Travel Agent Portal' : 'Customer Portal' }}</span>
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
                                <p class="text-xs text-slate-500">{{ Auth::guard('travel_agent')->check() ? 'Agent Portal System' : 'Customer Portal' }}</p>
                            </div>
                        </div>
                        <button id="mobileMenuClose" class="xl:hidden p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Profile Quick Info (Agent only) -->
                    @if(Auth::guard('travel_agent')->check())
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold uppercase">
                                    {{ substr(auth('travel_agent')->user()->first_name ?? 'A', 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">Logged in Agent</p>
                                    <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ auth('travel_agent')->user()->first_name ?? 'Guest' }} {{ auth('travel_agent')->user()->last_name ?? 'Agent' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner text-center">
                            <p class="font-bold text-slate-800">Customer Portal</p>
                            <p class="text-xs text-slate-500">Signed in: {{ auth()->user()->name ?? auth()->user()->email ?? 'Guest' }}</p>
                        </div>
                    @endif

                    @if(Auth::guard('travel_agent')->check())
                    <!-- Sidebar Navigation Links -->
                    <nav class="space-y-1">
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 0 1 9-9" />
                            </svg>
                            Book Tickets
                        </a>
                        <a href="{{ route('travel-agents.bookings') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                            Bookings List
                        </a>
                        <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0H8.25m11.25 0v1.125c0 .621-.504 1.125-1.125 1.125H5.625c-.621 0-1.125-.504-1.125-1.125V19.5h15Z" />
                            </svg>
                            Reports
                        </a>
                        <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            My Ledger
                        </a>
                        <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Settings
                        </a>
                    </nav>
                    @else
                        <nav class="space-y-1">
                            <a href="{{ route('tickets.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">Browse Flights</a>
                            <a href="{{ route('customer.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">My Dashboard</a>
                            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">Vouchers</a>
                            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">Invoices</a>
                            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">My Profile</a>
                        </nav>
                    @endif
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
                            <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Flight Booking Mode</span>
                        </div>
                        <h2 class="mt-2.5 text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-none">
                            {{ $ticket->airline }} · {{ trim($deptCity . ($arrCity ? ' to ' . $arrCity : '')) }}
                        </h2>
                        <p class="mt-2 text-xs md:text-sm text-slate-500 font-medium">Review schedule details and enter passenger documents to process booking.</p>
                        @if($ticket->route)
                            <p class="mt-2 text-xs md:text-sm text-slate-500 font-medium">Route: {{ $ticket->route }}</p>
                        @endif
                    </div>
                    <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 px-5 py-3 transition hover:bg-slate-50 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                        Back to Flights
                    </a>
                </section>

                @if($ticket->status === 'Approved' && $ticket->available_seats > 0)
                    <!-- Active Booking Form & Sticky Breakdown Layout -->
                    <form action="{{ Auth::guard('travel_agent')->check() ? route('travel-agents.tickets.book', $ticket) : route('tickets.book', $ticket) }}" method="POST" enctype="multipart/form-data" id="bookingForm" class="grid gap-6 lg:grid-cols-[1fr_360px] xl:grid-cols-[1fr_390px] items-start">
                        @csrf
                        <input type="hidden" name="status" value="Pending" />
                        <input type="hidden" name="payment_status" value="Unpaid" />
                        <input type="hidden" name="adults" id="adultsInput" value="2" />
                        <input type="hidden" name="children" id="childrenInput" value="0" />
                        <input type="hidden" name="infants" id="infantsInput" value="0" />
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
                        <input type="hidden" name="reference" value="{{ $ticket->reference }}" />

                        <div class="space-y-6">
                            
                            <!-- Premium Digital Boarding Pass Ticket -->
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
                                <div class="bg-slate-50/50 p-6 grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-4 border-t border-slate-100">
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
                                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Reference Code</span>
                                        <span class="text-sm font-bold text-blue-600 mt-1.5 block tracking-wider font-mono">{{ $ticket->reference }}</span>
                                    </div>
                                    <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Flight Status</span>
                                        <span class="text-sm font-bold text-slate-800 mt-1.5 block flex items-center gap-1.5">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            {{ $ticket->status }}
                                        </span>
                                    </div>
                                    <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Visibility</span>
                                        <span class="text-sm font-semibold text-slate-800 mt-1.5 block">{{ $ticket->visibility ?? 'Both' }}</span>
                                    </div>
                                    <div class="rounded-2xl bg-white p-4 border border-slate-100">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-widest block font-bold">Ticket Type</span>
                                        <span class="text-sm font-semibold text-slate-800 mt-1.5 block">{{ $ticket->ticket_type }}</span>
                                    </div>
                                </div>
                            </article>

                            <!-- Step 1: Seat Class Selection Module -->
                            <article class="glass-panel rounded-[28px] p-6 shadow-xs relative overflow-hidden">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-100 pb-5 mb-5">
                                    <div>
                                        <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Step 1</span>
                                        <h3 class="text-xl font-bold text-slate-900">Select Cabin Class</h3>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 px-4 py-2 border border-slate-200 text-right flex sm:flex-col justify-between items-center sm:items-end gap-4 sm:gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Selected Class</span>
                                        <span id="selectedCabinClassDisplay" class="text-sm font-black text-blue-600 leading-none">Economy</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Cabin Class <span class="text-rose-500">*</span></label>
                                    <select id="cabinClassSelect" name="cabin_class" class="w-full rounded-2xl premium-input px-4 py-3.5 text-sm font-semibold">
                                        <option value="Economy" {{ old('cabin_class', 'Economy') === 'Economy' ? 'selected' : '' }}>Economy ({{ $ticket->getClassAvailableSeats('Economy') }} seats available)</option>
                                        <option value="Premium Economy" {{ old('cabin_class') === 'Premium Economy' ? 'selected' : '' }}>Premium Economy ({{ $ticket->getClassAvailableSeats('Premium Economy') }} seats available)</option>
                                        <option value="Business" {{ old('cabin_class') === 'Business' ? 'selected' : '' }}>Business ({{ $ticket->getClassAvailableSeats('Business') }} seats available)</option>
                                        <option value="First" {{ old('cabin_class') === 'First' ? 'selected' : '' }}>First Class ({{ $ticket->getClassAvailableSeats('First') }} seats available)</option>
                                    </select>
                                </div>

                                <!-- Dynamic validation message block -->
                                <div id="seatClassWarning" class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-slate-800 shadow-sm hidden flex items-start gap-3 backdrop-blur-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                                    <div>
                                        <p class="font-bold text-rose-700 text-sm">Not Available</p>
                                        <p class="text-xs text-slate-600 mt-1 font-semibold" id="seatClassWarningMessage"></p>
                                    </div>
                                </div>
                            </article>

                            <!-- Passenger Selection Module -->
                            <article class="glass-panel rounded-[28px] p-6 shadow-xs relative overflow-hidden">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-100 pb-5">
                                    <div>
                                        <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Step 2: Passenger Count</span>
                                        <h3 class="text-xl font-bold text-slate-900">Choose traveler quantities</h3>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 px-4 py-2 border border-slate-200 text-right flex sm:flex-col justify-between items-center sm:items-end gap-4 sm:gap-1">
                                        <span class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Total Passengers</span>
                                        <span id="passengerTotal" class="text-2xl font-black text-slate-900 leading-none">2</span>
                                    </div>
                                </div>

                                <!-- Counter Grid -->
                                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                    <!-- Adults -->
                                    <div class="rounded-2xl bg-slate-50/50 p-4 border border-slate-100 flex flex-col justify-between">
                                        <div>
                                            <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Adults</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5 font-medium">Age 12+ years</span>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between rounded-xl bg-white p-2 border border-slate-200/60">
                                            <button id="decreaseAdult" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition focus:outline-none font-bold text-lg">-</button>
                                            <span id="adultCount" class="text-xl font-bold text-slate-900 min-w-[20px] text-center">2</span>
                                            <button id="increaseAdult" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-500 shadow-sm transition focus:outline-none font-bold text-lg">+</button>
                                        </div>
                                    </div>
                                    <!-- Children -->
                                    <div class="rounded-2xl bg-slate-50/50 p-4 border border-slate-100 flex flex-col justify-between">
                                        <div>
                                            <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Children</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5 font-medium">Age 2-12 years</span>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between rounded-xl bg-white p-2 border border-slate-200/60">
                                            <button id="decreaseChild" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition focus:outline-none font-bold text-lg">-</button>
                                            <span id="childCount" class="text-xl font-bold text-slate-900 min-w-[20px] text-center">0</span>
                                            <button id="increaseChild" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-500 shadow-sm transition focus:outline-none font-bold text-lg">+</button>
                                        </div>
                                    </div>
                                    <!-- Infants -->
                                    <div class="rounded-2xl bg-slate-50/50 p-4 border border-slate-100 flex flex-col justify-between">
                                        <div>
                                            <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Infants</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5 font-medium">Under 2 years</span>
                                        </div>
                                        <div class="mt-4 flex items-center justify-between rounded-xl bg-white p-2 border border-slate-200/60">
                                            <button id="decreaseInfant" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition focus:outline-none font-bold text-lg">-</button>
                                            <span id="infantCount" class="text-xl font-bold text-slate-900 min-w-[20px] text-center">0</span>
                                            <button id="increaseInfant" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-500 shadow-sm transition focus:outline-none font-bold text-lg">+</button>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- Dynamically Generated Passenger Details Forms -->
                            <article class="glass-panel rounded-[28px] p-6 shadow-xs">
                                <div class="border-b border-slate-100 pb-5">
                                    <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Traveler Details</span>
                                    <h3 class="text-xl font-bold text-slate-900">Identity & Passport documents</h3>
                                </div>
                                <div id="passengerForms" class="mt-6 space-y-6">
                                    <!-- Embedded dynamically in JavaScript -->
                                </div>
                            </article>

                            <!-- Booking Primary Contact Card -->
                            <article class="glass-panel rounded-[28px] p-6 shadow-xs">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                                    <div>
                                        <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Booking Contact</span>
                                        <h3 class="text-xl font-bold text-slate-900">Contact details</h3>
                                    </div>
                                    <div class="h-8 rounded-full bg-slate-50 px-4 flex items-center justify-center text-xs font-semibold text-slate-500 border border-slate-200">
                                        Primary Point of Contact
                                    </div>
                                </div>

                                <div class="grid gap-5 md:grid-cols-3 mt-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Contact Name <span class="text-rose-500">*</span></label>
                                        <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Full name as on passport" class="w-full rounded-2xl premium-input px-4 py-3.5 text-sm" required />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address <span class="text-rose-500">*</span></label>
                                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="agent@agency.com" class="w-full rounded-2xl premium-input px-4 py-3.5 text-sm" required />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">WhatsApp Number <span class="text-rose-500">*</span></label>
                                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+92 312 3456789" class="w-full rounded-2xl premium-input px-4 py-3.5 text-sm" required />
                                    </div>
                                </div>
                            </article>

                        </div>

                        <!-- Sticky Fare Summary & Receipt card -->
                        <aside class="space-y-6 lg:sticky lg:top-6">
                            <article class="glass-panel rounded-[28px] p-6 shadow-xs relative overflow-hidden">
                                <!-- Subtle shining overlay border -->
                                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>
                                
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-blue-600 font-bold block mb-1">Receipt Invoice</span>
                                    <h3 class="text-xl font-bold text-slate-900">Fare Summary</h3>
                                </div>

                                <!-- Receipt List items -->
                                <div class="mt-6 space-y-3">
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">Adult Base Fare</span>
                                        <span class="text-sm font-bold text-slate-800">PKR <span id="adultFare">0.00</span></span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">Child Base Fare</span>
                                        <span class="text-sm font-bold text-slate-800">PKR <span id="childFare">0.00</span></span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">Infant Base Fare</span>
                                        <span class="text-sm font-bold text-slate-800">PKR <span id="infantFare">0.00</span></span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">Regulatory Taxes</span>
                                        <span class="text-sm font-bold text-slate-800">PKR <span id="taxes">0.00</span></span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">ERP Service Fee</span>
                                        <span class="text-sm font-bold text-slate-800">PKR <span id="service">0.00</span></span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50/50 p-4 border border-slate-100">
                                        <span class="text-xs text-slate-500 font-medium">Selected Cabin Class</span>
                                        <span id="fareSummaryCabinClass" class="text-sm font-bold text-slate-800">{{ old('cabin_class', 'Economy') }}</span>
                                    </div>
                                    <!-- Dashed breakdown divider -->
                                    <div class="py-3">
                                        <div class="border-t border-dashed border-slate-200"></div>
                                    </div>

                                    <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Grand Total</span>
                                            <span class="text-xl font-extrabold text-blue-900">PKR <span id="grandTotal">0.00</span></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Visa Option Checkbox -->
<!-- Addons Section (Inside the same <form>) -->
<div class="glass-panel rounded-3xl p-6 space-y-4 my-6">
    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Additional Services (Addons)</h3>
    <p class="text-xs text-slate-500 font-medium">Select optional services for your booking.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
        <!-- Visa Addon -->
        <label class="flex items-start space-x-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
            <input type="checkbox" name="include_visa" value="1" class="mt-1 rounded text-blue-600 focus:ring-blue-500" {{ old('include_visa') ? 'checked' : '' }}>
            <div>
                <span class="block text-sm font-bold text-slate-800">Include Visa Processing</span>
                <span class="text-xs text-slate-500">
                    {{ $visaType ? $visaType->name . ' - SAR ' . number_format($visaType->total_cost, 2) . ' per booking' : 'Fixed SAR 1400 per booking' }}
                </span>
            </div>
        </label>

        <!-- Transport Addon -->
        <label class="flex items-start space-x-3 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
            <input type="checkbox" name="include_transport" value="1" class="mt-1 rounded text-blue-600 focus:ring-blue-500" {{ old('include_transport') ? 'checked' : '' }}>
            <div>
                <span class="block text-sm font-bold text-slate-800">Include Transport</span>
                <span class="text-xs text-slate-500">Airport transfer & transport service</span>
            </div>
        </label>
    </div>
</div>

                                <!-- Action Buttons -->
                                <div class="mt-6 grid gap-3">
                                    <button type="submit" id="continueBooking" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-750 hover:to-indigo-750 text-white font-extrabold text-sm py-4 shadow-lg shadow-blue-500/10 transition duration-200 transform active:scale-[0.98]">
                                        Confirm and Book Tickets
                                    </button>
                                    <button type="button" class="w-full rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 py-3.5 text-xs font-semibold text-slate-600 transition duration-200">
                                        Download Flight Itinerary
                                    </button>
                                </div>
                            </article>
                        </aside>
                    </form>
                @else
                    <!-- Out of Seats warning card -->
                    <section class="glass-panel rounded-[28px] p-8 md:p-12 shadow-md max-w-2xl mx-auto text-center relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 bg-rose-500"></div>
                        <div class="h-16 w-16 mx-auto rounded-full bg-rose-50/50 border border-rose-200 flex items-center justify-center text-rose-500 mb-6 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Booking Currently Unavailable</h3>
                        <p class="mt-3 text-slate-500 text-sm max-w-md mx-auto leading-relaxed font-medium">
                            {{ $ticket->available_seats <= 0 ? 'This flight has reached its maximum passenger capacity. No more seats can be reserved at this time.' : 'This flight option is temporarily offline or has been marked as suspended by administrators.' }}
                        </p>
                        <div class="mt-6 inline-flex rounded-xl bg-slate-100 border border-slate-200 px-4 py-2 text-xs font-bold text-slate-500 tracking-wider">
                            TICKET STATUS: {{ $ticket->status }}
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>

    <!-- Scripting for Sidebar responsiveness, counter logic, and dynamic inputs state saving -->
    <script>
        // Responsive sidebar menu selectors
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

        // Core dynamic price calculations & Counter values
        const prices = {
            adult: @json($ticket->adult_fare),
            child: @json($ticket->child_fare),
            infant: @json($ticket->infant_fare),
            taxRate: @json($ticket->tax_rate ?? 0.08),
            serviceRate: @json($ticket->service_charge_rate ?? 0.015),
        };

        const cabinPrices = {
            'Economy': @json($ticket->getCabinPrice('Economy') ?? $ticket->adult_fare),
            'Premium Economy': @json($ticket->getCabinPrice('Premium Economy') ?? $ticket->adult_fare),
            'Business': @json($ticket->getCabinPrice('Business') ?? $ticket->adult_fare),
            'First': @json($ticket->getCabinPrice('First') ?? $ticket->adult_fare),
        };

        const state = {
            adult: 2,
            child: 0,
            infant: 0,
        };

        // Cache available class seats from PHP
        const classSeats = {
            'Economy': @json($ticket->getClassAvailableSeats('Economy')),
            'Premium Economy': @json($ticket->getClassAvailableSeats('Premium Economy')),
            'Business': @json($ticket->getClassAvailableSeats('Business')),
            'First': @json($ticket->getClassAvailableSeats('First')),
        };

        // Cache elements
        const adultCount = document.getElementById('adultCount');
        const childCount = document.getElementById('childCount');
        const infantCount = document.getElementById('infantCount');
        const passengerTotal = document.getElementById('passengerTotal');
        const adultFare = document.getElementById('adultFare');
        const childFare = document.getElementById('childFare');
        const infantFare = document.getElementById('infantFare');
        const taxesEl = document.getElementById('taxes');
        const serviceEl = document.getElementById('service');
        const grandTotalEl = document.getElementById('grandTotal');
        const fareSummaryCabinPrice = document.getElementById('fareSummaryCabinPrice');
        const adultsInput = document.getElementById('adultsInput');
        const childrenInput = document.getElementById('childrenInput');
        const infantsInput = document.getElementById('infantsInput');
        const passengerForms = document.getElementById('passengerForms');
        const cabinClassSelect = document.getElementById('cabinClassSelect');
        const selectedCabinClassDisplay = document.getElementById('selectedCabinClassDisplay');
        const fareSummaryCabinClass = document.getElementById('fareSummaryCabinClass');
        const seatClassWarning = document.getElementById('seatClassWarning');
        const seatClassWarningMessage = document.getElementById('seatClassWarningMessage');
        const continueBookingBtn = document.getElementById('continueBooking');

        // Dynamic passenger limit parameters
        let maxPassengers = 0;

        // In-memory passenger details store to preserve input values across count changes
        let oldPassengers = @json(old('passengers', []));
        if (!Array.isArray(oldPassengers)) {
            oldPassengers = [];
        }

        function formatNumber(value) {
            return Number(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Dynamic validation banner toggle & prevent submission
        function validateSeats() {
            const currentTotal = state.adult + state.child + state.infant;
            const currentClass = cabinClassSelect ? cabinClassSelect.value : 'Economy';
            const availableSeats = classSeats[currentClass] ?? 0;
            maxPassengers = availableSeats; // Update dynamic limit

            if (selectedCabinClassDisplay) {
                selectedCabinClassDisplay.textContent = currentClass;
            }

            if (fareSummaryCabinClass) {
                fareSummaryCabinClass.textContent = currentClass;
            }

            if (currentTotal > availableSeats) {
                // Show warning message
                if (seatClassWarning && seatClassWarningMessage) {
                    if (availableSeats <= 0) {
                        seatClassWarningMessage.textContent = 'Not Available';
                    } else if (availableSeats === 1) {
                        seatClassWarningMessage.textContent = `Only 1 ${currentClass} seat is available.`;
                    } else {
                        seatClassWarningMessage.textContent = `Only ${availableSeats} ${currentClass} seats are available.`;
                    }
                    seatClassWarning.classList.remove('hidden');
                }
                // Disable submit button
                if (continueBookingBtn) {
                    continueBookingBtn.disabled = true;
                    continueBookingBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    continueBookingBtn.classList.remove('hover:from-blue-600', 'hover:to-indigo-600');
                }
            } else {
                // Hide warning message
                if (seatClassWarning) {
                    seatClassWarning.classList.add('hidden');
                }
                // Enable submit button
                if (continueBookingBtn) {
                    continueBookingBtn.disabled = false;
                    continueBookingBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    continueBookingBtn.classList.add('hover:from-blue-600', 'hover:to-indigo-600');
                }
            }
        }

        // Capture values of form elements currently rendered in the DOM before we destroy/rebuild them
        function saveCurrentInputValues() {
            if (!passengerForms) return;
            const cards = passengerForms.querySelectorAll('.passenger-card');
            cards.forEach((card) => {
                const index = parseInt(card.getAttribute('data-index'));
                if (isNaN(index)) return;

                const fullNameVal = card.querySelector(`[name="passengers[${index}][full_name]"]`)?.value || '';
                const dobVal = card.querySelector(`[name="passengers[${index}][date_of_birth]"]`)?.value || '';
                const passportNumberVal = card.querySelector(`[name="passengers[${index}][passport_number]"]`)?.value || '';
                const passportExpiryVal = card.querySelector(`[name="passengers[${index}][passport_expiry]"]`)?.value || '';

                oldPassengers[index] = {
                    full_name: fullNameVal,
                    date_of_birth: dobVal,
                    passport_number: passportNumberVal,
                    passport_expiry: passportExpiryVal
                };
            });
        }

        function buildPassengerCard(index, type, passenger = {}) {
            return `
                <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 text-slate-800 passenger-card" data-index="${index}">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="h-7 w-7 rounded-lg bg-blue-600 flex items-center justify-center text-xs font-bold text-white shadow-xs">
                                ${ index + 1 }
                            </div>
                            <h4 class="text-sm font-bold text-slate-850">Passenger ${ index + 1 } Document</h4>
                        </div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-[10px] font-bold text-blue-600 uppercase tracking-wider">${type}</span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="passengers[${index}][full_name]" value="${passenger.full_name || ''}" class="w-full rounded-xl premium-input px-3.5 py-3 text-xs" placeholder="As per passport" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Date of Birth <span class="text-rose-500">*</span></label>
                            <input type="date" name="passengers[${index}][date_of_birth]" value="${passenger.date_of_birth || ''}" class="w-full rounded-xl premium-input px-3.5 py-3 text-xs" required />
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 mt-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Passport Upload <span class="text-rose-500">*</span></label>
                            <input type="file" name="passengers[${index}][passport_upload]" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-xl premium-input px-3.5 py-3 text-xs" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">CNIC Upload <span class="text-rose-500">*</span></label>
                            <input type="file" name="passengers[${index}][cnic_upload]" accept=".jpg,.jpeg,.png,.pdf" class="w-full rounded-xl premium-input px-3.5 py-3 text-xs" required />
                        </div>
                    </div>

                    <input type="hidden" name="passengers[${index}][passenger_type]" value="${type}" />
                </div>
            `;
        }

        function renderPassengerForms() {
            if (!passengerForms) return;

            const orderedPassengers = [];
            // Build temporary structures referencing current state quantity counts
            for (let i = 0; i < state.adult; i += 1) {
                orderedPassengers.push({ type: 'Adult', values: oldPassengers[i] ?? {} });
            }
            for (let i = 0; i < state.child; i += 1) {
                const globalIndex = state.adult + i;
                orderedPassengers.push({ type: 'Child', values: oldPassengers[globalIndex] ?? {} });
            }
            for (let i = 0; i < state.infant; i += 1) {
                const globalIndex = state.adult + state.child + i;
                orderedPassengers.push({ type: 'Infant', values: oldPassengers[globalIndex] ?? {} });
            }

            passengerForms.innerHTML = orderedPassengers
                .map((passenger, index) => buildPassengerCard(index, passenger.type, passenger.values))
                .join('');
        }

        function recalculate() {
            const currentClass = cabinClassSelect ? cabinClassSelect.value : 'Economy';
            const classFare = cabinPrices[currentClass] ?? prices.adult;
            const adultAmount = state.adult * (classFare || 0);
            const childAmount = state.child * (prices.child || 0);
            const infantAmount = state.infant * (prices.infant || 0);
            const subTotal = adultAmount + childAmount + infantAmount;
            
            // Rounded tax and service estimates
            const taxes = Math.round(subTotal * prices.taxRate);
            const service = Math.round(subTotal * prices.serviceRate);
            const grandTotal = subTotal + taxes + service;

            // Trigger dynamic seat logic checks
            validateSeats();

            // Render updates to HTML text contents
            passengerTotal.textContent = state.adult + state.child + state.infant;
            adultCount.textContent = state.adult;
            childCount.textContent = state.child;
            infantCount.textContent = state.infant;

            adultFare.textContent = formatNumber(adultAmount);
            childFare.textContent = formatNumber(childAmount);
            infantFare.textContent = formatNumber(infantAmount);
            if (fareSummaryCabinPrice) {
                fareSummaryCabinPrice.textContent = formatNumber(classFare);
            }
            taxesEl.textContent = formatNumber(taxes);
            serviceEl.textContent = formatNumber(service);
            grandTotalEl.textContent = formatNumber(grandTotal);

            // Keep hidden native inputs synced with state counter values
            if (adultsInput) adultsInput.value = state.adult;
            if (childrenInput) childrenInput.value = state.child;
            if (infantsInput) infantsInput.value = state.infant;

            renderPassengerForms();
        }

        function createCounter(buttonId, key, min) {
            const button = document.getElementById(buttonId);
            if (!button) return;
            button.addEventListener('click', () => {
                // Save whatever values the user might have filled before we trigger a DOM update
                saveCurrentInputValues();

                const currentTotal = state.adult + state.child + state.infant;
                const isAddition = button.textContent.trim() === '+';

                // Disallow addition if capacity of class is already filled
                if (isAddition && currentTotal >= maxPassengers) {
                    return; // Dynamic Seat limit reached
                }

                state[key] = Math.max(min, state[key] + (isAddition ? 1 : -1));
                if (state[key] < min) state[key] = min;
                recalculate();
            });
        }

        // Register dropdown change listener
        if (cabinClassSelect) {
            cabinClassSelect.addEventListener('change', () => {
                validateSeats();
                recalculate();
            });
        }

        // Register event hooks to custom trigger counters
        createCounter('increaseAdult', 'adult', 1);
        createCounter('decreaseAdult', 'adult', 1);
        createCounter('increaseChild', 'child', 0);
        createCounter('decreaseChild', 'child', 0);
        createCounter('increaseInfant', 'infant', 0);
        createCounter('decreaseInfant', 'infant', 0);

        // Pre-recalculate calculations once DOM compiles
        recalculate();
    </script>
</body>
</html>
