<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Dashboard') | Umrah ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(14, 165, 233, 0.04) 0px, transparent 50%);
            background-attachment: fixed;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        /* Custom Modern Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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
    @php
        $currentRoute = request()->route()?->getName();
        $adminUser = auth()->user();
        $adminUserName = $adminUser->name ?? 'Super Administrator';
        $adminUserEmail = $adminUser->email ?? 'admin@umraherp.com';
    @endphp

    <div class="flex min-h-screen overflow-hidden">
        {{-- Mobile Sidebar Backdrop --}}
        <div id="sidebarBackdrop" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-xs transition-opacity lg:hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full transform flex flex-col justify-between border-r border-slate-200/90 bg-white px-4 py-5 transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:translate-x-0 lg:w-72 lg:transform-none shadow-xs lg:shadow-none">
            <div class="flex flex-col flex-1 overflow-y-auto">
                
                {{-- Logo & Brand Header --}}
                <div class="mb-5 flex items-center justify-between px-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-emerald-500 p-2 shadow-md shadow-blue-500/20 flex items-center justify-center text-white">
                            <img src="{{ asset('Hujaj-Umrah.png') }}" alt="Umrah ERP Logo" class="h-7 w-auto object-contain brightness-0 invert" />
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-lg text-slate-900 tracking-tight">Umrah ERP</span>
                                <span class="rounded-md bg-emerald-50 border border-emerald-200/80 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">Admin</span>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-400">Enterprise Control Hub</p>
                        </div>
                    </a>

                    {{-- Close Button for Mobile --}}
                    <button id="sidebarClose" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-400 hover:bg-slate-100 hover:text-slate-700 lg:hidden">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- User Profile Card in Sidebar --}}
                <div class="mb-6 rounded-2xl border border-blue-100/80 bg-gradient-to-br from-blue-50/80 via-white to-emerald-50/50 p-3.5 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="inline-flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-emerald-600 text-sm font-extrabold text-white shadow-md shadow-blue-600/20">
                                {{ strtoupper(substr($adminUserName, 0, 1)) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-bold text-slate-800">{{ $adminUserName }}</p>
                            <p class="truncate text-[11px] font-medium text-slate-400">{{ $adminUserEmail }}</p>
                            <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-emerald-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Super Admin
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Navigation Links --}}
                <nav class="space-y-6 text-sm flex-1 pr-1">
                    
                    {{-- Core Section --}}
                    <div>
                        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                            <i class="bi bi-grid-fill text-xs"></i>
                            <span>Core Operations</span>
                        </p>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ in_array($currentRoute, ['dashboard', 'admin.dashboard']) ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700' }}">
                                <i class="bi bi-speedometer2 text-base {{ in_array($currentRoute, ['dashboard', 'admin.dashboard']) ? 'text-white' : 'text-blue-600 group-hover:text-blue-700' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.customer-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.customer-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700' }}">
                                <i class="bi bi-people-fill text-base {{ $currentRoute === 'admin.customer-management' ? 'text-white' : 'text-blue-600 group-hover:text-blue-700' }}"></i>
                                <span>Customer Profiles</span>
                            </a>
                            <a href="{{ route('admin.agent-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.agent-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700' }}">
                                <i class="bi bi-briefcase-fill text-base {{ $currentRoute === 'admin.agent-management' ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                                <span>Travel Agents</span>
                            </a>
                            <a href="{{ route('admin.booking-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ request()->routeIs('admin.bookings.*') || $currentRoute === 'admin.booking-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700' }}">
                                <i class="bi bi-journal-check text-base {{ request()->routeIs('admin.bookings.*') || $currentRoute === 'admin.booking-management' ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                                <span>All Bookings</span>
                            </a>
                            <a href="{{ route('admin.airline-bookings.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ request()->routeIs('admin.airline-bookings.*') ? 'sidebar-active' : 'text-slate-600 hover:bg-blue-50/70 hover:text-blue-700' }}">
                                <i class="bi bi-airplane-fill text-base {{ request()->routeIs('admin.airline-bookings.*') ? 'text-white' : 'text-sky-600 group-hover:text-sky-700' }}"></i>
                                <span>Flight Bookings</span>
                            </a>
                        </div>
                    </div>

                    {{-- Inventory & Hotels Section --}}
                    <div>
                        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-1.5">
                            <i class="bi bi-buildings-fill text-xs"></i>
                            <span>Hospitality & Inventory</span>
                        </p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.hotel-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.hotel-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-emerald-50/70 hover:text-emerald-700' }}">
                                <i class="bi bi-building text-base {{ $currentRoute === 'admin.hotel-management' ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                                <span>Hotels Management</span>
                            </a>
                            <a href="{{ route('admin.hotel-room-inventory.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ Str::startsWith($currentRoute, 'admin.hotel-room-inventory') ? 'sidebar-active' : 'text-slate-600 hover:bg-emerald-50/70 hover:text-emerald-700' }}">
                                <i class="bi bi-calendar3-range text-base {{ Str::startsWith($currentRoute, 'admin.hotel-room-inventory') ? 'text-white' : 'text-teal-600 group-hover:text-teal-700' }}"></i>
                                <span>Room Inventory</span>
                            </a>
                            <a href="{{ route('admin.packages.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ Str::startsWith($currentRoute, 'admin.packages') && !Str::startsWith($currentRoute, 'admin.package-bookings') ? 'sidebar-active' : 'text-slate-600 hover:bg-emerald-50/70 hover:text-emerald-700' }}">
                                <i class="bi bi-box-seam-fill text-base {{ Str::startsWith($currentRoute, 'admin.packages') && !Str::startsWith($currentRoute, 'admin.package-bookings') ? 'text-white' : 'text-blue-600 group-hover:text-blue-700' }}"></i>
                                <span>Package Builder</span>
                            </a>
                            <a href="{{ route('admin.package-bookings.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ Str::startsWith($currentRoute, 'admin.package-bookings') ? 'sidebar-active' : 'text-slate-600 hover:bg-emerald-50/70 hover:text-emerald-700' }}">
                                <i class="bi bi-bookmark-star-fill text-base {{ Str::startsWith($currentRoute, 'admin.package-bookings') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                                <span>Package Bookings</span>
                            </a>
                            <a href="{{ route('admin.airline-ticket-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.airline-ticket-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-emerald-50/70 hover:text-emerald-700' }}">
                                <i class="bi bi-ticket-perforated-fill text-base {{ $currentRoute === 'admin.airline-ticket-management' ? 'text-white' : 'text-sky-600 group-hover:text-sky-700' }}"></i>
                                <span>Airline Tickets</span>
                            </a>
                        </div>
                    </div>

                    {{-- Services & Vouchers Section --}}
                    <div>
                        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-teal-600 flex items-center gap-1.5">
                            <i class="bi bi-file-earmark-medical-fill text-xs"></i>
                            <span>Vouchers & Visas</span>
                        </p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.vouchers.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ Str::startsWith($currentRoute, 'admin.vouchers') ? 'sidebar-active' : 'text-slate-600 hover:bg-teal-50/70 hover:text-teal-700' }}">
                                <i class="bi bi-receipt-cutoff text-base {{ Str::startsWith($currentRoute, 'admin.vouchers') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                                <span>Generated Vouchers</span>
                            </a>
                            <a href="{{ route('admin.voucher-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.voucher-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-teal-50/70 hover:text-teal-700' }}">
                                <i class="bi bi-sliders2-vertical text-base {{ $currentRoute === 'admin.voucher-management' ? 'text-white' : 'text-blue-600 group-hover:text-blue-700' }}"></i>
                                <span>Voucher Settings</span>
                            </a>
                            <a href="{{ route('admin.visa-management') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-semibold transition duration-200 {{ $currentRoute === 'admin.visa-management' ? 'sidebar-active' : 'text-slate-600 hover:bg-teal-50/70 hover:text-teal-700' }}">
                                <i class="bi bi-passport-fill text-base {{ $currentRoute === 'admin.visa-management' ? 'text-white' : 'text-indigo-600 group-hover:text-indigo-700' }}"></i>
                                <span>Visa Operations</span>
                            </a>
                        </div>
                    </div>

                </nav>
            </div>

            {{-- Sidebar Footer with Logout --}}
            <div class="mt-4 pt-3 border-t border-slate-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition duration-200">
                        <i class="bi bi-box-arrow-right text-sm"></i>
                        <span>Sign Out Session</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden min-h-screen">
            
            {{-- Top Header --}}
            <header class="sticky top-0 z-30 glass-header border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3.5">
                <div class="flex items-center justify-between gap-4">
                    
                    {{-- Mobile Toggle & Page Title --}}
                    <div class="flex items-center gap-3">
                        <button id="sidebarToggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition lg:hidden shadow-xs">
                            <i class="bi bi-list text-xl"></i>
                        </button>

                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">@yield('page-heading', 'Super Admin Dashboard')</h1>
                                <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live System
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium hidden sm:block">@yield('page-description', 'Manage Umrah ERP operations from the centralized internal administration hub.')</p>
                        </div>
                    </div>

                    {{-- Top Header Right Actions --}}
                    <div class="flex items-center gap-3">
                        
                        {{-- Quick Agent Portal Link --}}
                        <a href="{{ route('travel-agents.vouchers.create') }}" class="hidden md:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 text-white text-xs font-bold shadow-md shadow-blue-500/20 hover:opacity-95 transition">
                            <i class="bi bi-plus-circle"></i>
                            <span>Create Voucher</span>
                        </a>

                        {{-- Date Display --}}
                        <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 shadow-xs">
                            <i class="bi bi-calendar-event text-blue-600"></i>
                            <span>{{ now()->format('D, d M Y') }}</span>
                        </div>

                        {{-- User Avatar Pill --}}
                        <div class="flex items-center gap-2.5 pl-2">
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                {{ strtoupper(substr($adminUserName, 0, 1)) }}
                            </div>
                        </div>

                    </div>

                </div>
            </header>

            {{-- Main Body Container --}}
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8 max-w-7xl w-full mx-auto space-y-8">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200/80 bg-white/80 backdrop-blur-xs py-4 px-4 sm:px-6 lg:px-8 text-center text-xs font-medium text-slate-400">
                <p>&copy; {{ date('Y') }} Umrah ERP System • Internal Super Admin Portal</p>
            </footer>

        </div>
    </div>

    {{-- Mobile Sidebar Toggle Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                sidebarBackdrop?.classList.remove('hidden');
                document.body.classList.add('sidebar-open');
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                sidebarBackdrop?.classList.add('hidden');
                document.body.classList.remove('sidebar-open');
            }

            sidebarToggle?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            sidebarBackdrop?.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
