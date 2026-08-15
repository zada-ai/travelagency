<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    @php
        $currentRoute = request()->route()?->getName();
        $adminUserName = auth()->user()->name ?? 'Admin User';
    @endphp

    <div class="min-h-screen">
        <div class="min-h-screen">
            <div id="sidebarBackdrop" class="fixed inset-0 z-10 hidden bg-slate-950/60 lg:hidden"></div>
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-slate-950 px-4 py-6 text-slate-100 transition duration-300 lg:translate-x-0">
                <div class="flex items-center justify-between lg:hidden">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Menu</p>
                    </div>
                    <button id="sidebarClose" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-slate-300 transition hover:bg-slate-800" aria-label="Close sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="mt-6 rounded-[2rem] bg-slate-900 p-4 shadow-lg ring-1 ring-slate-800/60">
                    <div class="flex items-center gap-3">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-500 text-xl font-semibold text-white">A</div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Administrator</p>
                            <p class="mt-1 text-base font-semibold text-white">{{ $adminUserName }}</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-400">Unified controls for bookings, flights, hotels, agents, and reports.</p>
                </div>

                <nav class="mt-10 space-y-4 text-sm">
                    <div class="space-y-1 rounded-3xl border border-slate-800 bg-slate-900/80 p-3">
                        <p class="px-3 pb-2 text-xs uppercase tracking-[0.28em] text-slate-500">Core</p>
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'dashboard' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">D</span>
                            <span>Dashboard</span>
                        </a>
                        {{-- <a href="{{ route('admin.user-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.user-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">U</span>
                            <span>User Management</span>
                        </a> --}}
                        <a href="{{ route('admin.customer-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.customer-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">C</span>
                            <span>Customer Management</span>
                        </a>
                        <a href="{{ route('admin.agent-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.agent-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">A</span>
                            <span>Agent Management</span>
                        </a>
                        <a href="{{ route('admin.airline-ticket-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.airline-ticket-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">F</span>
                            <span>Airline Tickets</span>
                        </a>
                        <a href="{{ route('admin.airline-bookings.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.airline-bookings.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">FB</span>
                            <span>Flight Bookings</span>
                        </a>
                        <a href="{{ route('admin.hotel-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.hotel-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">H</span>
                            <span>Hotel Management</span>
                        </a>
                        <a href="{{ route('admin.booking-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.bookings.*') || $currentRoute === 'admin.booking-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">B</span>
                            <span>Booking Management</span>
                        </a>
                    </div>

                    <div class="space-y-1 rounded-3xl border border-slate-800 bg-slate-900/80 p-3">
                        <p class="px-3 pb-2 text-xs uppercase tracking-[0.28em] text-slate-500">Operations</p>
                        <a href="{{ route('admin.packages.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ Str::startsWith($currentRoute, 'admin.packages') && !Str::startsWith($currentRoute, 'admin.package-bookings') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">P</span>
                            <span>Package Builder</span>
                        </a>
                        <a href="{{ route('admin.package-bookings.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ Str::startsWith($currentRoute, 'admin.package-bookings') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">B</span>
                            <span>Package Bookings</span>
                        </a>
                        {{-- <a href="{{ route('admin.dynamic-package-builder') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.dynamic-package-builder' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">DP</span>
                            <span>Dynamic Builder</span>
                        </a> --}}
                        {{-- <a href="{{ route('admin.dynamic-package-calculator') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.dynamic-package-calculator' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">C</span>
                            <span>Package Calculator</span>
                        </a> --}}
                        {{-- <a href="{{ route('admin.payment-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.payment-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">$</span>
                            <span>Payments</span>
                        </a>
                        <a href="{{ route('admin.accounting') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.accounting' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">A</span>
                            <span>Accounting</span>
                        </a> --}}
                    </div>

                    <div class="space-y-1 rounded-3xl border border-slate-800 bg-slate-900/80 p-3">
                        <p class="px-3 pb-2 text-xs uppercase tracking-[0.28em] text-slate-500">Insights</p>
                        {{-- <a href="{{ route('admin.reports') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.reports' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">R</span>
                            <span>Reports</span>
                        </a>
                        <a href="{{ route('admin.notifications') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.notifications' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">N</span>
                            <span>Notifications</span>
                        </a>
                        <a href="{{ route('admin.website-cms') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.website-cms' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">W</span>
                            <span>Website CMS</span>
                        </a>
                        <a href="{{ route('admin.crm') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.crm' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">CRM</span>
                            <span>CRM</span>
                        </a> --}}
                        <a href="{{ route('admin.voucher-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.voucher-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">V</span>
                            <span>Vouchers</span>
                        </a>
                        <a href="{{ route('admin.vouchers.index') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ Str::startsWith($currentRoute, 'admin.vouchers') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">NV</span>
                            <span>New Vouchers</span>
                        </a>
                        {{-- <a href="{{ route('admin.transport-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.transport-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">T</span>
                            <span>Transport</span>
                        </a> --}}
                        <a href="{{ route('admin.visa-management') }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ $currentRoute === 'admin.visa-management' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">V</span>
                            <span>Visa Management</span>
                        </a>
                    </div>
                </nav>

                <div class="mt-8 rounded-[2rem] bg-slate-900 p-5 shadow-lg ring-1 ring-slate-800/70">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Quick Actions</p>
                    {{-- <div class="mt-4 grid gap-3">
                        <a href="{{ route('admin.hotels.create') }}" class="block rounded-3xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Add Hotel</a>
                        <a href="{{ route('admin.hotel-meal-plans.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Hotel Meal Plans</a>
                        <a href="{{ route('admin.hotel-room-inventory.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Room Inventory</a>
                        <a href="{{ route('admin.hotel-seasonal-rates.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Seasonal Rates</a>
                        <a href="{{ route('admin.hotel-facilities.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Facilities</a>
                        <a href="{{ route('admin.hotel-room-types.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Room Types</a>
                        <a href="{{ route('admin.bookings.index') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Room Bookings</a>
                    </div> --}}
                    <div class="mt-6 rounded-[2rem] border border-slate-800 bg-slate-900 p-4 shadow-lg">
                        <p class="text-xs uppercase tracking-[0.28em] text-slate-500">Session</p>
                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full rounded-3xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-400">Logout</button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="min-h-screen w-full px-4 py-6 lg:ml-72 xl:px-8">
                <div class="flex flex-col gap-4 lg:hidden">
                    <button id="sidebarToggle" class="inline-flex items-center gap-2 rounded-2xl bg-slate-800 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                        </svg>
                        Open menu
                    </button>
                </div>
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('Hujaj-Umrah.png') }}" alt="Hujaj Umrah" class="h-16 w-auto object-contain" />
                        <div>
                            <h1 class="text-3xl font-semibold text-white">@yield('page-heading', 'Dashboard')</h1>
                            <p class="mt-2 text-sm text-slate-400">@yield('page-description', 'Manage operations, bookings, and reports from a centralized ERP experience.')</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const profileButton = document.getElementById('profileMenuButton');
            const profileMenu = document.getElementById('profileMenu');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                sidebarBackdrop?.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                sidebarBackdrop?.classList.add('hidden');
            }

            sidebarToggle?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            sidebarBackdrop?.addEventListener('click', closeSidebar);

            profileButton?.addEventListener('click', function () {
                const visible = profileMenu?.classList.contains('!visible');
                if (visible) {
                    profileMenu?.classList.remove('!visible', 'opacity-100');
                    profileMenu?.classList.add('invisible', 'opacity-0');
                } else {
                    profileMenu?.classList.remove('invisible', 'opacity-0');
                    profileMenu?.classList.add('!visible', 'opacity-100');
                }
            });

            document.addEventListener('click', function (event) {
                if (!profileButton?.contains(event.target) && !profileMenu?.contains(event.target)) {
                    profileMenu?.classList.remove('!visible', 'opacity-100');
                    profileMenu?.classList.add('invisible', 'opacity-0');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
