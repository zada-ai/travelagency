@php
    $currentUser = $currentUser ?? auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = $agent ?? $currentUser;
    $hasWebUser = auth()->check();
    $hasTravelAgentUser = auth()->guard('travel_agent')->check();
    $isCustomer = (bool) ($isCustomer ?? ($hasWebUser && ! $hasTravelAgentUser));
    $isVisaOfficer = (bool) ($isVisaOfficer ?? false);

    if (! $isCustomer && ! $isVisaOfficer && ! $hasTravelAgentUser && ! $hasWebUser) {
        $isCustomer = true;
    }

    $portalLabel = $portalLabel ?? ($isCustomer ? 'Customer Portal' : ($isVisaOfficer ? 'Visa Portal' : 'Agent Portal'));
    $portalSystemLabel = $portalSystemLabel ?? ($isCustomer ? 'Customer Portal System' : ($isVisaOfficer ? 'Visa Office System' : 'Agent Portal System'));

    $routeName = request()->route() ? request()->route()->getName() : null;
    $isActive = fn($name, $prefix = null) => $routeName === $name || ($prefix && str_starts_with((string) $routeName, $prefix));
@endphp

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-[280px] -translate-x-full xl:translate-x-0 xl:static bg-slate-900 text-slate-50 shadow-2xl xl:shadow-none transition-transform duration-300 ease-in-out border-r border-slate-800">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-5 py-5 border-b border-slate-800">
            <div>
                <p class="text-[10px] uppercase tracking-[0.28em] text-sky-300/80">{{ $portalSystemLabel }}</p>
                <h1 class="mt-2 text-xl font-extrabold text-white">{{ $portalLabel }}</h1>
            </div>
            <button id="mobileMenuClose" class="xl:hidden rounded-lg border border-slate-700 p-2 text-slate-300 hover:text-white hover:border-slate-500" type="button" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="px-4 py-5 border-b border-slate-800">
            <div class="flex items-center gap-3 rounded-2xl bg-slate-800/70 px-3 py-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-sky-400 via-cyan-400 to-indigo-500 text-base font-bold text-white shadow-lg shadow-sky-500/20">
                    {{ strtoupper(substr((string) ($currentUser->name ?? $currentUser->company_name ?? $currentUser->email ?? 'U'), 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-white">{{ $currentUser->name ?? $currentUser->company_name ?? 'User' }}</p>
                    <p class="truncate text-xs text-slate-400">{{ $currentUser->email ?? 'No email available' }}</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-4">
            @if($isCustomer)
                <div class="space-y-1">
                    <p class="px-3 pb-2 text-[10px] uppercase tracking-[0.24em] text-slate-400">Customer Portal</p>

                    <a href="{{ route('customer.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('customer.dashboard') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-sky-300 group-hover:bg-slate-700">⌂</span>
                        Dashboard
                    </a>

                    <div x-data="{ open: true }" class="space-y-1">
                        <button type="button" @click="open = !open" class="group flex w-full items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                            <span class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-violet-300">📦</span>
                                Build Package
                            </span>
                            <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" /></svg>
                        </button>

                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('customer.packages.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Create Package</a>
                            <a href="{{ route('tickets.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Select Flight</a>
                            <a href="{{ route('hotels.booking') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Select Hotel</a>
                            <a href="{{ route('custom.package') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Custom Package</a>
                        </div>
                    </div>

                    <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-emerald-300">✓</span>
                        Vouchers
                    </a>
                    <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-amber-300">▣</span>
                        Invoices
                    </a>
                    <a href="{{ route('customer.bookings') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('customer.bookings') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-cyan-300">🧳</span>
                        My Bookings
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('customer.dashboard') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-rose-300">⚙</span>
                        My Profile
                    </a>
                </div>
            @elseif($isVisaOfficer)
                <div class="space-y-1">
                    <p class="px-3 pb-2 text-[10px] uppercase tracking-[0.24em] text-slate-400">Visa Officer</p>
                    <a href="{{ route('visa-office.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.dashboard') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-sky-300 group-hover:bg-slate-700">⌂</span>
                        Dashboard
                    </a>
                    <a href="{{ route('visa-office.assigned') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.assigned') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-violet-300 group-hover:bg-slate-700">▣</span>
                        Assigned Applications
                    </a>
                    <a href="{{ route('visa-office.visa-management') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.visa-management') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-emerald-300 group-hover:bg-slate-700">✓</span>
                        Visa Management
                    </a>
                    <a href="{{ route('visa-office.document.queue') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.document.queue') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-amber-300 group-hover:bg-slate-700">▤</span>
                        Document Verification
                    </a>
                    <a href="{{ route('visa-office.issued') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.issued') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-cyan-300 group-hover:bg-slate-700">✓</span>
                        Issued Visas
                    </a>
                    <a href="{{ route('visa-office.notifications') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.notifications') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-rose-300 group-hover:bg-slate-700">◔</span>
                        Notifications
                    </a>
                    <a href="{{ route('visa-office.profile') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.profile') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-indigo-300 group-hover:bg-slate-700">◎</span>
                        My Profile
                    </a>
                    <a href="{{ route('visa-office.rejected') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('visa-office.rejected') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-red-300 group-hover:bg-slate-700">✕</span>
                        Rejected Applications
                    </a>
                </div>
            @else
                <div class="space-y-1">
                    <p class="px-3 pb-2 text-[10px] uppercase tracking-[0.24em] text-slate-400">Travel Agent</p>
                    <a href="{{ route('travel-agents.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.dashboard') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-sky-300 group-hover:bg-slate-700">⌂</span>
                        Overview
                    </a>
                    <a href="{{ route('hotels.booking') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('hotels.booking') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-violet-300 group-hover:bg-slate-700">🏨</span>
                        Hotels
                    </a>
                    <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.tickets') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-emerald-300 group-hover:bg-slate-700">✈</span>
                        Tickets
                    </a>
                    <a href="{{ route('travel-agents.packages.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.packages.index') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-amber-300 group-hover:bg-slate-700">✦</span>
                        Umrah Packages
                    </a>
                    {{-- <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.visa-applications') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-cyan-300 group-hover:bg-slate-700">✓</span>
                        Visa Applications
                    </a> --}}
                    <a href="{{ route('travel-agents.customer-visa.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.customer-visa.index') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-rose-300 group-hover:bg-slate-700">◎</span>
                        Customers
                    </a>
                    <a href="{{ route('travel-agents.sub-agents.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.sub-agents.index') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-indigo-300 group-hover:bg-slate-700">◌</span>
                        Sub-Agent Management
                    </a>
                    <a href="{{ route('travel-agents.sub-agents.create') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.sub-agents.create') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-sky-300 group-hover:bg-slate-700">＋</span>
                        Create Sub-Agent
                    </a>
                    <a href="{{ route('travel-agents.booking-history.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive('travel-agents.booking-history.index') ? 'bg-sky-500/15 text-sky-200 border border-sky-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-emerald-300 group-hover:bg-slate-700">◔</span>
                        Booking History

                    <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-indigo-300">◎</span>
                        Profile
                    </a>
                    <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-yellow-300">▣</span>
                        Documents
                    </a>
                    <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-800 text-emerald-300">✓</span>
                        Approval Status
                    </a>
                </div>
            @endif
        </nav>

        <div class="border-t border-slate-800 px-4 py-4">
            <button id="logoutTrigger" type="button" class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-500/30 bg-rose-500/10 px-3 py-2.5 text-sm font-semibold text-rose-200 transition hover:bg-rose-500/15">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h7a1 1 0 110 2H5v10h6a1 1 0 110 2H4a1 1 0 01-1-1V4zm10.293 3.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L14.586 11H8a1 1 0 110-2h6.586l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </div>

        <div class="px-3 pb-4">
            <div class="rounded-2xl border border-slate-700 bg-slate-800/80 p-4">
                <span class="inline-flex items-center rounded-md bg-sky-500/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-sky-300">24/7 Support</span>
                <p class="mt-3 text-xs leading-relaxed text-slate-300">Need instant assistance? Reach out directly via WhatsApp.</p>
                <a href="https://wa.me/923123456789" target="_blank" class="mt-3.5 flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-3 py-2.5 text-xs font-bold text-white hover:bg-sky-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-white"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97a16.607 16.607 0 0 0 6.585 6.585l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" /></svg>
                    WhatsApp Support
                </a>
            </div>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/50 xl:hidden"></div>

<div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900">Confirm logout</h3>
        <p class="mt-2 text-sm text-slate-600">Are you sure you want to sign out of your dashboard?</p>
        <div class="mt-6 flex justify-end gap-3">
            <button id="logoutCancel" type="button" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
            @if($isCustomer)
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">Logout</button>
                </form>
            @elseif($isVisaOfficer)
                <form method="POST" action="{{ route('visa-office.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">Logout</button>
                </form>
            @else
                <form method="POST" action="{{ route('travel-agents.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">Logout</button>
                </form>
            @endif
        </div>
    </div>
</div>
