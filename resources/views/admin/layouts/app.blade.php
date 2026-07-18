<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    @php
        $currentRoute = request()->route()?->getName();
        $adminUserName = auth()->user()->name ?? 'Admin User';
    @endphp

    <div class="min-h-screen">
        <header class="fixed inset-x-0 top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl shadow-sm">
            <div class="mx-auto flex h-16 max-w-[1800px] items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200 lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-white">U</span>
                        <span>Umrah ERP</span>
                    </a>
                </div>

                <div class="hidden lg:flex flex-1 items-center justify-center px-4">
                    <div class="flex w-full max-w-2xl items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 104 4 4 4 0 00-4-4zM2 8a6 6 0 1110.8 3.4l4 4a1 1 0 01-1.4 1.4l-4-4A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        <input type="search" placeholder="Search bookings, agents, flights..." class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200" aria-label="Notifications">
                        <span class="absolute -right-1 -top-1 inline-flex h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z" />
                            <path d="M14 15a2 2 0 11-4 0h4z" />
                        </svg>
                    </button>
                    <button type="button" class="hidden sm:inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v2" />
                            <path d="M12 21v2" />
                            <path d="M4.22 4.22l1.42 1.42" />
                            <path d="M18.36 18.36l1.42 1.42" />
                            <path d="M1 12h2" />
                            <path d="M21 12h2" />
                            <path d="M4.22 19.78l1.42-1.42" />
                            <path d="M18.36 5.64l1.42-1.42" />
                            <circle cx="12" cy="12" r="5" />
                        </svg>
                        Settings
                    </button>

                    <div class="relative">
                        <button id="profileMenuButton" type="button" class="inline-flex items-center gap-3 rounded-3xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-500 text-white">{{ strtoupper(substr($adminUserName, 0, 1)) }}</span>
                            <span>{{ $adminUserName }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="profileMenu" class="invisible absolute right-0 z-40 mt-3 w-64 overflow-hidden rounded-3xl border border-slate-200 bg-white text-slate-900 shadow-2xl opacity-0 transition duration-200">
                            <div class="p-4">
                                <p class="text-sm font-semibold">{{ $adminUserName }}</p>
                                <p class="mt-1 text-sm text-slate-500">Administrator</p>
                            </div>
                            <div class="border-t border-slate-200"></div>
                            <div class="space-y-1 p-3">
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">My Profile</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">Account Settings</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100">Notifications</a>
                            </div>
                            <div class="border-t border-slate-200"></div>
                            <form action="/logout" method="POST" onsubmit="return confirm('Are you sure you want to logout?');" class="p-3">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="lg:flex">
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-slate-950 px-4 py-6 text-slate-100 transition duration-300 lg:static lg:translate-x-0 lg:!translate-x-0">
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

                <nav class="mt-10 space-y-1 text-sm">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'dashboard' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">D</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.user-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.user-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">U</span>
                        <span>User Management</span>
                    </a>
                    <a href="{{ route('admin.customer-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.customer-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">C</span>
                        <span>Customer Management</span>
                    </a>
                    <a href="{{ route('admin.agent-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.agent-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">A</span>
                        <span>Agent Management</span>
                    </a>
                    <a href="{{ route('admin.airline-ticket-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.airline-ticket-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">F</span>
                        <span>Airline Management</span>
                    </a>
                    <a href="{{ route('admin.hotel-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.hotel-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">H</span>
                        <span>Hotel Management</span>
                    </a>
                    <a href="{{ route('admin.visa-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.visa-management' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">V</span>
                        <span>Visa Management</span>
                    </a>
                    <a href="{{ route('admin.package-builder') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.package-builder' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">P</span>
                        <span>Package Builder</span>
                    </a>
                    <a href="{{ route('admin.booking-management') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.booking-management' || str_contains($currentRoute, 'admin.bookings') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">B</span>
                        <span>Booking Management</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.reports' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">R</span>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.accounting') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 transition {{ $currentRoute === 'admin.accounting' ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200">$</span>
                        <span>Accounting</span>
                    </a>
                </nav>

                <div class="mt-8 rounded-[2rem] bg-slate-900 p-5 shadow-lg ring-1 ring-slate-800/70">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Quick Actions</p>
                    <div class="mt-4 grid gap-3">
                        <a href="{{ route('admin.airline-flights.create') }}" class="block rounded-3xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Add Flight</a>
                        <a href="{{ route('admin.hotels.create') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Add Hotel</a>
                        <a href="{{ route('admin.visa-management') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Add Visa</a>
                        <a href="{{ route('admin.package-builder') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Add Package</a>
                        <a href="{{ route('admin.booking-management') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Add Booking</a>
                        <a href="{{ route('admin.agent-management') }}" class="block rounded-3xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Add Agent</a>
                    </div>
                </div>
            </aside>

            <main class="min-h-screen flex-1 px-4 py-6 lg:ml-72 xl:px-8">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-slate-900">@yield('page-heading', 'Dashboard')</h1>
                        <p class="mt-2 text-sm text-slate-500">@yield('page-description', 'Manage operations, bookings, and reports from a centralized ERP experience.')</p>
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
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const profileButton = document.getElementById('profileMenuButton');
            const profileMenu = document.getElementById('profileMenu');

            sidebarToggle?.addEventListener('click', function () {
                sidebar?.classList.toggle('-translate-x-full');
            });

            sidebarClose?.addEventListener('click', function () {
                sidebar?.classList.add('-translate-x-full');
            });

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
