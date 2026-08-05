<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Booking | Travel Agent Portal</title>
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
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 0 1 9-9" />
                            </svg>
                            Search Flights
                        </a>
                        <a href="{{ route('travel-agents.bookings') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                            My Bookings
                        </a>
                        <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
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
                <div class="max-w-6xl mx-auto space-y-6">
                    
                    <!-- Top Summary Panel -->
                    <section class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Group Packages Portal</span>
                            </div>
                            <h1 class="mt-3 text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Book Group Packages</h1>
                            <p class="mt-2.5 text-sm text-slate-500 font-medium max-w-2xl">Use the cards below to choose the right group package for your clients and send reservation requests.</p>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <a href="{{ route('travel-agents.dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 px-5 py-3 transition hover:bg-slate-50 shadow-sm">
                                Back to Dashboard
                            </a>
                            <a href="{{ route('travel-agents.hotels.index', ['package' => 'group']) }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-sm px-5 py-3 shadow-md hover:from-blue-700 hover:to-indigo-700 transition">
                                Group Hotel Search
                            </a>
                        </div>
                    </section>

                    <!-- Main grid layout -->
                    <div class="grid gap-6 lg:grid-cols-[1fr_340px] xl:grid-cols-[1fr_370px] items-start">
                        
                        <!-- Available Packages Column -->
                        <section id="package-list" class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Inventory</span>
                                    <h2 class="text-xl font-bold text-slate-900 mt-1">Available Group Packages</h2>
                                </div>
                                <span class="rounded-full bg-blue-50 border border-blue-100 px-3.5 py-1 text-xs font-bold text-blue-600">{{ count($groupPackages) }} packages</span>
                            </div>

                            <div class="space-y-5">
                                @foreach ($groupPackages as $package)
                                    <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition duration-200 hover:border-blue-500/20">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 mb-4">
                                            <div>
                                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase mb-2">
                                                    {{ $package['duration'] }} · {{ $package['group_size'] }}
                                                </span>
                                                <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $package['title'] }}</h3>
                                                <p class="text-xs text-slate-500 mt-1 font-medium">{{ $package['subtitle'] }}</p>
                                            </div>
                                            <div class="text-left sm:text-right">
                                                <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Starting at</span>
                                                <span class="text-xl font-black text-blue-600 mt-1 block">{{ $package['price'] }}</span>
                                                <span class="text-[10px] text-emerald-600 font-semibold block mt-0.5">{{ $package['availability'] }} groups available</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($package['highlights'] as $highlight)
                                                <span class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-1.5 text-xs text-slate-600 font-medium">{{ $highlight }}</span>
                                            @endforeach
                                        </div>

                                        <div class="mt-5 flex items-center justify-between gap-4 pt-4 border-t border-slate-100">
                                            <span class="text-xs text-slate-400 font-medium">Cap: {{ $package['group_size'] }}</span>
                                            <a href="#" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs px-4 py-2.5 shadow-sm transition">
                                                Book This Package
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <!-- Sidebar column details -->
                        <aside class="space-y-6">
                            <!-- Left section context: Create Booking info -->
                            <article class="glass-panel rounded-[28px] p-5 shadow-xs">
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Quick Start</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-1">Reserve Group</h3>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed font-medium">Send a quick inquiry proposal to booking operations to lock special transport/hotel allotments for your group.</p>
                                <a href="#package-list" class="mt-4 flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-xs font-extrabold text-white py-3 shadow-md shadow-blue-500/10 transition">
                                    Start Reservation
                                </a>
                            </article>

                            <!-- Left section context: Recent Tickets -->
                            <article class="glass-panel rounded-[28px] p-5 shadow-xs">
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block mb-3">Recent Requests</span>
                                <div class="space-y-3">
                                    @foreach ($recentTickets as $ticket)
                                        <div class="rounded-2xl bg-white border border-slate-200/80 p-4">
                                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2.5 mb-2.5">
                                                <div>
                                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">{{ $ticket['reference'] }}</span>
                                                    <span class="text-xs font-bold text-slate-800 block mt-0.5 truncate max-w-[120px]">{{ $ticket['client_name'] }}</span>
                                                </div>
                                                <span class="rounded-full px-2.5 py-0.5 text-[9px] font-bold {{ $ticket['status'] === 'Approved' ? 'bg-emerald-50 text-emerald-600' : ($ticket['status'] === 'Pending' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-650') }}">
                                                    {{ $ticket['status'] }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-slate-500 flex items-center justify-between">
                                                <span>{{ $ticket['trip_date'] }} · {{ $ticket['group_size'] }}</span>
                                                <span class="font-bold text-slate-800">{{ $ticket['total'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </article>

                            <!-- Left section context: Booking Summary -->
                            <article class="glass-panel rounded-[28px] p-5 shadow-xs space-y-4">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Booking Summary</span>
                                    <p class="text-xs text-slate-500 mt-1 font-medium">Selected package status details</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Selected package</span>
                                    <span class="text-xs font-bold text-slate-800 mt-1 block truncate">{{ $groupPackages[0]['title'] }}</span>
                                    <span class="text-[10px] text-slate-500 mt-1 block leading-relaxed">{{ $groupPackages[0]['subtitle'] }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Payment timeline</span>
                                    <span class="text-[10px] text-slate-500 mt-1.5 block leading-relaxed font-medium">Booking deposit due within 48 hours. Final payment collected after confirmation.</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Next action</span>
                                    <span class="text-[10px] text-slate-500 mt-1.5 block leading-relaxed font-medium">Send request to operations to reserve rooms and transport.</span>
                                </div>
                            </aside>

                        </div>

                    </div>
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
