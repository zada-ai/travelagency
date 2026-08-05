<h1 style="background:red;color:white;padding:10px;text-align:center;font-weight:bold;z-index:9999;position:relative;">
THIS IS THE ACTIVE DASHBOARD VIEW
</h1>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCustomer ?? false ? 'Customer Dashboard' : (($userRole ?? '') === 'visa_office' ? 'Visa Office Dashboard' : 'Agent Dashboard') }} | Umrah ERP</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
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

   @php
    $authUser = auth()->user();
    $agentGuard = auth()->guard('travel_agent')->user();
    $currentUser = $authUser ?? $agentGuard;

    // Force Customer Portal if URL contains '/customer'
    $isCustomerRoute = request()->is('customer*');

    $isCustomer = $isCustomerRoute || ($currentUser && (
        (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Customer'))
        || in_array(strtolower((string) ($currentUser->role ?? $currentUser->designation ?? '')), ['customer'], true)
    ));

    $isVisaOfficer = !$isCustomer && ($currentUser && (
        (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Visa Officer'))
        || in_array(strtolower((string) ($currentUser->role ?? $currentUser->designation ?? '')), ['visa_officer', 'visa office', 'visa officer'], true)
    ));

    $portalLabel = $isCustomer ? 'Customer Portal' : ($isVisaOfficer ? 'Visa Portal' : 'Agent Portal');
    $portalSystemLabel = $isCustomer ? 'Customer Portal System' : ($isVisaOfficer ? 'Visa Office System' : 'Agent Portal System');

    $userName = $currentUser->company_name ?? $currentUser->name ?? 'Guest User';
    $userInitial = strtoupper(substr($userName, 0, 1));
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp
    <!-- Mobile Header/Navigation Trigger -->
    <header class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-4 backdrop-blur-md xl:hidden sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
            </div>
            <span class="text-lg font-bold text-slate-800">
                {{ $portalLabel }}
            </span>
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
            
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] -translate-x-full border-r border-slate-200 bg-white p-6 transition-transform duration-350 cubic-bezier(0.4, 0, 0.2, 1) xl:static xl:translate-x-0 flex flex-col justify-between shadow-xs">
                <div class="space-y-6">
                    <!-- Brand Section -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 tracking-tight">Hujaj Umrah</h1>
                                <p class="text-xs text-slate-500">
                                    {{ $portalSystemLabel }}
                                </p>
                            </div>
                        </div>
                        <button id="mobileMenuClose" class="xl:hidden p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Profile Quick Info -->
                    @if($isVisaOfficer)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold uppercase">
                                    {{ strtoupper(substr($agent->first_name ?? ($agent->name ?? 'O'), 0, 1)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">Visa Officer</p>
                                    <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ $agent->name ?? $agent->first_name ?? 'Officer' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $agent->designation ?? 'Visa Officer' }} @if(!empty($agent->employee_id)) · ID: {{ $agent->employee_id }} @endif</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center text-white font-semibold uppercase">
                                    @if(!empty($agent->company_logo))
                                        <img src="{{ asset('storage/'.$agent->company_logo) }}" alt="{{ $agent->company_name ?? 'Company Logo' }}" class="h-full w-full object-cover" />
                                    @else
                                        {{ strtoupper(substr($agent->company_name ?? 'A', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">Agency Company</p>
                                    <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ $agent->company_name ?? 'Travel Agency' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Dynamic Sidebar Navigation Links -->
                    <nav class="space-y-1">
                        @if($isCustomer)
                            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-[11px] font-bold uppercase tracking-[0.25em] text-emerald-700">
                                Customer Portal
                            </div>

                            <a href="#overview" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Dashboard
                            </a>
<!-- Build Package Toggle -->
<div x-data="{ open: false }" class="w-full">

    <!-- Main Button -->
    <button @click="open = !open"
        class="w-full group flex items-center justify-between gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">

        <div class="flex items-center gap-3">
            <!-- 📦 SVG ICON (same style) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.8" stroke="currentColor"
                class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 7.5l9-4.5 9 4.5M3 7.5v9l9 4.5m-9-13.5l9 4.5m9-4.5v9l-9 4.5" />
            </svg>

            <span>Build Package</span>
        </div>

        <!-- 🔽 Arrow -->
        <svg :class="open ? 'rotate-180' : ''"
            class="h-4 w-4 transition-transform duration-300"
            fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 9l6 6 6-6"></path>
        </svg>
    </button>

    <!-- 🔽 Submenu -->
    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">

        <a href="{{ route('customer.packages.create') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-blue-50">
            Create Package
        </a>

        <a href="{{ route('tickets.index') }}" class="block px-3 py-2 text-sm rounded-lg hover:bg-blue-50">
            Select Flight
        </a>

        <a href="/hotels/booking" class="block px-3 py-2 text-sm rounded-lg hover:bg-blue-50">
            Select Hotel
        </a>

        <!-- ✅ Tera Custom Button -->
        <a href="{{ route('custom.package') }}"
           class="block px-3 py-2 text-sm rounded-lg hover:bg-blue-50">
            Custom Package
        </a>

    </div>
</div>
                            {{-- <a href="#quotes" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                My Quotes
                            </a> --}}
{{-- 
                            <a href="#bookings" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                My Bookings
                            </a> --}}

                            {{-- <div class="px-4 py-2">
                                <div class="text-[11px] font-bold uppercase tracking-[0.25em] text-slate-400">Documents</div>
                                <div class="mt-2 space-y-1 text-sm text-slate-600">
                                    <div class="rounded-lg bg-slate-50 px-3 py-2">Upload Passport</div>
                                    <div class="rounded-lg bg-slate-50 px-3 py-2">Upload Documents</div>
                                </div>
                            </div> --}}

                            {{-- <a href="#visa-status" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                Visa Status
                            </a> --}}

                            {{-- <a href="{{ route('customer.visa.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0 0v-3m0 12v-3m4.5-4.5h3m-12 0h3" />
                                </svg>
                                Visa Applications
                            </a> --}}
                            {{-- <a href="{{ route('customer.visa.create') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Apply Visa
                            </a>

                            <a href="#tickets" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h5.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125H7.5a1.125 1.125 0 01-1.125-1.125V7.125C6.375 6.504 6.879 6 7.5 6z" />
                                </svg>
                                My Tickets
                            </a> --}}

                            <a href="#vouchers" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Vouchers
                            </a>

                            <a href="#invoices" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Invoices
                            </a>

                            {{-- <a href="{{ route('travel-agents.sub-agents.create') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm-7.5 13.5a7.5 7.5 0 0115 0v1.125a1.125 1.125 0 01-1.125 1.125H6.375A1.125 1.125 0 015.25 19.5V18zm14.25-3.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                Create Sub-Agent
                            </a> --}}

                            {{-- <a href="#payments" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M6.75 3.75h10.5a2.25 2.25 0 012.25 2.25v12.75a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18.75V6a2.25 2.25 0 012.25-2.25z" />
                                </svg>
                                Payment History
                            </a> --}}

<a href="#profile" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                               My Bookings
                            </a>
                            <a href="#profile" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                My Profile
                            </a>
                        @elseif($isVisaOfficer)
                            <!-- Visa Officer Navigation -->
                            <a href="{{ route('visa-office.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('visa-office.assigned') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Assigned Applications
                            </a>
                            <a href="{{ route('visa-office.visa-management') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Visa Management
                            </a>
                            <a href="{{ route('visa-office.document.queue') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Document Verification
                            </a>
                            <a href="{{ route('visa-office.issued') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Issued Visas
                            </a>
                            <a href="{{ route('visa-office.notifications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118.6 14.6V11a6 6 0 10-12 0v3c0 .386-.149.757-.415 1.04L4 17h5m6 0v1a3 3 0 11-6 0v-1"/></svg>
                                    @php
                                        $unreadCount = auth()->user()->unreadNotificationsCount();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                                Notifications
                            </a>
                            <a href="{{ route('visa-office.profile') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6z"/></svg>
                                My Profile
                            </a>
                            <a href="{{ route('visa-office.rejected') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rejected Applications
                            </a>
                        @else
                            <!-- Travel Agent Navigation -->
                            <a href="#overview" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Overview
                            </a>
                            <a href="{{ route('hotels.booking') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-16.5 0V3.75c0-.414.336-.75.75-.75h7.5c.414 0 .75.336.75.75V21m-9 0h18" />
                                </svg>
                                Hotels
                            </a>
                            <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h5.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125H7.5a1.125 1.125 0 01-1.125-1.125V7.125C6.375 6.504 6.879 6 7.5 6z" />
                                </svg>
                                Tickets
                            </a>
                             <a href="{{ route('travel-agents.packages.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                UMMRAH PACKAGES
                            </a>
                            <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Visa Applications
                            </a>
                            <a href="{{ route('travel-agents.customer-visa.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Customers
                            </a>
                            <a href="{{ route('travel-agents.sub-agents.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5a3 3 0 013-3h12a3 3 0 013 3v9a3 3 0 01-3 3H6a3 3 0 01-3-3v-9zm3 0v9h12v-9H6zm4.5 3h3" />
                                </svg>
                                Sub-Agent Management
                            </a>
                            <a href="{{ route('travel-agents.sub-agents.create') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm-7.5 13.5a7.5 7.5 0 0115 0v1.125a1.125 1.125 0 01-1.125 1.125H6.375A1.125 1.125 0 015.25 19.5V18zm14.25-3.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                Create Sub-Agent
                            </a>
                            <a href="{{ route('travel-agents.booking-history.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Booking History
                            </a>
                            <a href="{{ route('travel-agents.commission.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Commission
                            </a>
                            <a href="{{ route('travel-agents.reports.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Reports
                            </a>
                            <a href="#profile" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profile
                            </a>
                            <a href="#documents" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Documents
                            </a>
                            <a href="#status" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Approval Status
                            </a>
                        @endif

                        @if($isCustomer)
                            <div class="pt-4 border-t border-slate-100">
                                <button id="logoutTrigger" type="button" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 shadow-xs transition">
                                    Logout
                                </button>
                            </div>
                        @elseif($isVisaOfficer)
                            <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-slate-100">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 shadow-xs transition">Logout</button>
                            </form>
                        @else
                            <form action="{{ route('travel-agents.logout') }}" method="POST" class="pt-4 border-t border-slate-100">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 shadow-xs transition">Logout</button>
                            </form>
                        @endif
                    </nav>
                </div>

                <!-- Support Box -->
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner mt-8">
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 mb-2">24/7 Support</span>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Need instant assistance? Reach out directly via WhatsApp.</p>
                    <a href="https://wa.me/923123456789" target="_blank" class="mt-3.5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white py-2.5 shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97a16.607 16.607 0 0 0 6.585 6.585l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" /></svg>
                        WhatsApp Support
                    </a>
                </div>
            </aside>

            <!-- Main Scrollable Section -->
            <main class="p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden">
                <div class="max-w-6xl mx-auto space-y-6">

                    @if(!empty($innerView))
                        @include($innerView)
                    @endif

                    @if($isCustomer)
                        <section id="overview" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs relative overflow-hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Customer Overview</span>
                                    </div>
                                    <h1 class="mt-3 text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Welcome back, {{ auth()->user()->name ?? 'Customer' }}</h1>
                                    <p class="mt-2.5 text-sm text-slate-500 font-medium">Track your bookings, payments, visa progress, and package requests in one place.</p>
                                </div>
                                <div class="rounded-2xl bg-emerald-50 border border-emerald-100 px-5 py-3.5 text-xs text-emerald-800 font-bold self-start md:self-auto shadow-xs">Customer Dashboard</div>
                            </div>

                            {{-- <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Active Bookings</span>
                                    <span class="mt-2 block text-2xl font-extrabold text-slate-900">3</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Remaining Balance</span>
                                    <span class="mt-2 block text-2xl font-extrabold text-amber-600">PKR 48,500</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Visa Status</span>
                                    <span class="mt-2 block text-2xl font-extrabold text-blue-600">In Process</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Next Departure</span>
                                    <span class="mt-2 block text-2xl font-extrabold text-slate-900">12 Aug 2026</span>
                                </div>
                            </div> --}}
                        </section>

                        <section id="build-package" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold">Quick Actions</h2>
                                    <p class="text-sm text-slate-500">Start a custom package or upload your travel documents.</p>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#build-package" class="btn btn-primary">Build Custom Package</a>
                                <a href="#documents" class="btn btn-outline-primary">Upload Passport</a>
                            </div>
                        </section>

                        {{-- <section id="bookings" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h2 class="text-xl font-bold">Recent Bookings</h2>
                                    <p class="text-sm text-slate-500">Your latest package activity and booking status.</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Package</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Premium Umrah Package</td>
                                            <td>12 Jul 2026</td>
                                            <td><span class="badge bg-warning text-dark">Pending Payment</span></td>
                                            <td>PKR 280,000</td>
                                        </tr>
                                        <tr>
                                            <td>Family Visa + Hotel</td>
                                            <td>05 Jul 2026</td>
                                            <td><span class="badge bg-success">Confirmed</span></td>
                                            <td>PKR 195,000</td>
                                        </tr>
                                        <tr>
                                            <td>Economy Ticket Bundle</td>
                                            <td>01 Jul 2026</td>
                                            <td><span class="badge bg-secondary">Draft</span></td>
                                            <td>PKR 95,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section> --}}
                    @elseif($isVisaOfficer)
                        <!-- Visa Officer: Overview Banner -->
                        <section id="overview" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs relative overflow-hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Visa Processing Desk</span>
                                    </div>
                                    <h1 class="mt-3 text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Welcome back, {{ $agent->name ?? $agent->first_name ?? 'Officer' }}</h1>
                                    <p class="mt-2.5 text-sm text-slate-500 font-medium">{{ $agent->designation ?? 'Visa Officer' }} @if(!empty($agent->employee_id)) · ID: <span class="font-bold text-slate-800">{{ $agent->employee_id }}</span>@endif</p>
                                </div>
                                <div class="rounded-2xl bg-emerald-50 border border-emerald-100 px-5 py-3.5 text-xs text-emerald-800 font-bold self-start md:self-auto shadow-xs">Active Session</div>
                            </div>

                            <!-- Officer KPI Cards -->
                            <div class="mt-6 grid gap-4 sm:grid-cols-4 lg:grid-cols-8">
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Assigned</div>
                                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalAssigned ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Pending Reviews</div>
                                    <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $pending ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Under Review</div>
                                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $underReview ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Documents Required</div>
                                    <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $documentsRequired ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Approved</div>
                                    <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $approved ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Rejected</div>
                                    <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $rejected ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center col-span-2 lg:col-span-1">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Issued Today</div>
                                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $issuedToday ?? 0 }}</div>
                                </div>
                                <div class="rounded-2xl bg-white border p-4 text-center col-span-2 lg:col-span-1">
                                    <div class="text-xs text-slate-400 uppercase font-bold">Today's Tasks</div>
                                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $todaysTasks ?? 0 }}</div>
                                </div>
                            </div>
                        </section>

                        <!-- Assigned Applications Table -->
                        <section id="assigned-applications" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold">Assigned Applications</h2>
                                    <p class="text-sm text-slate-500">Showing applications assigned to you.</p>
                                </div>
                                <div>
                                    <a href="{{ route('visa-office.assigned') }}" class="text-xs font-semibold text-blue-600">View All</a>
                                </div>
                            </div>

                            @if($recentApplications->isEmpty())
                                <div class="text-sm text-slate-500">No assigned applications found.</div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs uppercase text-slate-500">
                                            <tr>
                                                <th class="px-3 py-2">Application ID</th>
                                                <th class="px-3 py-2">Customer</th>
                                                <th class="px-3 py-2">Passport</th>
                                                <th class="px-3 py-2">Visa Type</th>
                                                <th class="px-3 py-2">Agent</th>
                                                <th class="px-3 py-2">Travel Date</th>
                                                <th class="px-3 py-2">Status</th>
                                                <th class="px-3 py-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentApplications as $app)
                                            <tr class="border-t">
                                                <td class="px-3 py-2">#{{ $app->id }}</td>
                                                <td class="px-3 py-2">{{ $app->customer_name }}</td>
                                                <td class="px-3 py-2">{{ $app->passport_number }}</td>
                                                <td class="px-3 py-2">{{ $app->visaType->name ?? 'N/A' }}</td>
                                                <td class="px-3 py-2">{{ $app->travelAgent->company_name ?? 'Direct' }}</td>
                                                <td class="px-3 py-2">{{ optional($app->travel_date)->format('M d, Y') }}</td>
                                                <td class="px-3 py-2">{{ $app->status }}</td>
                                                <td class="px-3 py-2">
                                                    <a href="{{ route('visa-office.applications.show', $app->id) }}" class="text-xs text-blue-600">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </section>

                        <!-- Document Verification Queue -->
                        <section id="document-queue" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold">Document Verification Queue</h2>
                                    <p class="text-sm text-slate-500">Applications missing documents or requiring verification.</p>
                                </div>
                                <div>
                                    <a href="{{ route('visa-office.document.queue') }}" class="text-xs font-semibold text-blue-600">View Queue</a>
                                </div>
                            </div>

                            @if($pendingReviews->isEmpty())
                                <div class="text-sm text-slate-500">No pending document verifications.</div>
                            @else
                                <div class="grid gap-4">
                                    @foreach($pendingReviews as $app)
                                        <div class="rounded-lg bg-white border p-4 flex items-center justify-between">
                                            <div>
                                                <div class="font-bold">#{{ $app->id }} — {{ $app->customer_name }}</div>
                                                <div class="text-xs text-slate-500">Passport: {{ $app->passport_number }} · Status: {{ $app->status }}</div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('visa-office.applications.show', $app->id) }}" class="text-xs text-emerald-600 font-bold">Verify Document</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <!-- Visa Officer Profile Section -->
                        <section id="profile" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="border-b border-slate-100 pb-5">
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Officer Credentials</span>
                                <h2 class="mt-2.5 text-2xl font-extrabold text-slate-900 tracking-tight">Visa Officer Profile</h2>
                            </div>
                            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Officer Name</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block">{{ $agent->name ?? $agent->company_name ?? 'N/A' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Employee ID</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block font-mono">{{ $agent->employee_id ?? 'N/A' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Department</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block">{{ $agent->department ?? 'Visa Processing' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Designation</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block">{{ $agent->designation ?? 'Visa Officer' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Email Address</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block truncate">{{ $agent->email ?? 'N/A' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Phone Number</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block font-mono">{{ $agent->phone ?? $agent->mobile ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- Recent Activity & Notifications -->
                        <section id="activity" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs grid gap-6 md:grid-cols-2">
                            <div>
                                <h3 class="font-bold text-lg">Recent Activity</h3>
                                @if($recentlyIssuedVisas->isEmpty())
                                    <div class="text-sm text-slate-500 mt-3">No recent activity.</div>
                                @else
                                    <ul class="mt-3 space-y-2">
                                        @foreach($recentlyIssuedVisas as $a)
                                            <li class="text-sm">Issued: #{{ $a->id }} — {{ $a->customer_name }} ({{ $a->visaType->name ?? 'N/A' }})</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Notifications</h3>
                                @if($recentNotifications->isEmpty())
                                    <div class="text-sm text-slate-500 mt-3">No notifications.</div>
                                @else
                                    <ul class="mt-3 space-y-2 text-sm">
                                        @foreach($recentNotifications as $n)
                                            <li>{{ is_string($n) ? $n : ($n->data['message'] ?? json_encode($n)) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </section>

                    @else
                        {{-- Travel Agent UI --}}
                        <!-- Section: Welcome Banner Header -->
                        <section id="overview" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs relative overflow-hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Active Account</span>
                                    </div>
                                    <h1 class="mt-3 text-3xl font-extrabold text-slate-900 tracking-tight leading-none">Welcome back, {{ $agent->first_name ?? ($agent->company_name ?? 'Agent') }}</h1>
                                    <p class="mt-2.5 text-sm text-slate-500 font-medium">Your agency status is currently marked as <span class="font-bold text-slate-800 uppercase">{{ $agent->status ?? 'Active' }}</span>.</p>
                                </div>
                                <div class="rounded-2xl bg-emerald-50 border border-emerald-100 px-5 py-3.5 text-xs text-emerald-800 font-bold self-start md:self-auto shadow-xs">Member since {{ isset($agent->created_at) && $agent->created_at ? (is_string($agent->created_at) ? $agent->created_at : $agent->created_at->format('M d, Y')) : 'N/A' }}</div>
                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Email Address</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block truncate">{{ $agent->email ?? 'N/A' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Mobile Number</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block font-mono">{{ $agent->mobile ?? $agent->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Agency Location</span>
                                    <span class="text-sm font-bold text-slate-800 mt-2 block">{{ $agent->city ?? 'N/A' }}, {{ $agent->country ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- Existing agent sections (packages, profile, documents, status) -->
                        <section id="packages" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Booking Center</span>
                                <h2 class="mt-2.5 text-2xl font-extrabold text-slate-900 tracking-tight">Agent Packages</h2>
                                <p class="text-slate-500 text-sm mt-1 font-medium">Choose from curated Umrah packages or reserve group airline tickets.</p>
                            </div>

                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <!-- Umrah Package Card -->
                                <a href="{{ route('travel-agents.hotels.index', ['package' => 'umrah']) }}" class="group block rounded-[24px] bg-white border border-slate-200 p-6 transition-all duration-250 hover:-translate-y-1 hover:border-blue-500/20 hover:shadow-lg shadow-sm">
                                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
                                        <div>
                                            <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest">Umrah Package</span>
                                            <h3 class="mt-1 text-xl font-bold text-slate-900">Explore Umrah Packages</h3>
                                        </div>
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-xs group-hover:bg-blue-600 group-hover:text-white transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed font-medium">View curated Umrah hotel packages, shuttle listings, and accommodation details for your clients.</p>
                                    <div class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-blue-600 group-hover:text-indigo-600 transition">Click To Book Now <span aria-hidden="true" class="group-hover:translate-x-1.5 transition-transform duration-200">→</span></div>
                                </a>

                                <!-- Group Ticket Card -->
                                <a href="{{ route('travel-agents.group-booking') }}" class="group block rounded-[24px] bg-white border border-slate-200 p-6 transition-all duration-250 hover:-translate-y-1 hover:border-blue-500/20 hover:shadow-lg shadow-sm">
                                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
                                        <div>
                                            <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-widest">Group Ticket</span>
                                            <h3 class="mt-1 text-xl font-bold text-slate-900">Book Group Packages</h3>
                                        </div>
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 shadow-xs group-hover:bg-indigo-600 group-hover:text-white transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3.13a4 4 0 010 7.75"/><path d="M12 7h.01"/><path d="M12 12c0-2.21 1.79-4 4-4h4"/><path d="M2 20v-2a4 4 0 014-4h8a4 4 0 014 4v2"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Create and process group flight booking requests and manage block packages instantly.</p>
                                    <div class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-indigo-600 group-hover:text-blue-600 transition">Click To Book Now <span aria-hidden="true" class="group-hover:translate-x-1.5 transition-transform duration-200">→</span></div>
                                </a>
                            </div>
                        </section>

                        <section id="sub-agents" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Sub-Agent Network</span>
                                    <h2 class="mt-2.5 text-2xl font-extrabold text-slate-900 tracking-tight">Your Sub-Agents</h2>
                                    <p class="text-sm text-slate-500 mt-1">View the agents you created and manage their profiles from one place.</p>
                                </div>
                                <a href="{{ route('travel-agents.sub-agents.create') }}" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                                    Create Sub-Agent
                                </a>
                            </div>

                            @if($subAgents->isEmpty())
                                <div class="text-sm text-slate-500">You have not created any sub-agents yet.</div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left border-collapse">
                                        <thead class="text-xs uppercase text-slate-500">
                                            <tr>
                                                <th class="px-3 py-3 border-b border-slate-200">Name</th>
                                                <th class="px-3 py-3 border-b border-slate-200">Email</th>
                                                <th class="px-3 py-3 border-b border-slate-200">Status</th>
                                                <th class="px-3 py-3 border-b border-slate-200">Created</th>
                                                <th class="px-3 py-3 border-b border-slate-200">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subAgents as $subAgent)
                                                <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                                    <td class="px-3 py-3">{{ $subAgent->first_name ?? $subAgent->company_name ?? 'N/A' }} {{ $subAgent->last_name ?? '' }}</td>
                                                    <td class="px-3 py-3">{{ $subAgent->email ?? 'N/A' }}</td>
                                                    <td class="px-3 py-3 uppercase font-semibold text-slate-700">{{ $subAgent->status ?? 'Pending' }}</td>
                                                    <td class="px-3 py-3">{{ optional($subAgent->created_at)->format('M d, Y') }}</td>
                                                    <td class="px-3 py-3 space-x-2">
                                                        <a href="{{ route('travel-agents.sub-agents.show', $subAgent) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">View</a>
                                                        <a href="{{ route('travel-agents.sub-agents.edit', $subAgent) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">Edit</a>
                                                        <form action="{{ route('travel-agents.sub-agents.destroy', $subAgent) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub-agent?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </section>

                        <section id="profile" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Identity verification</span>
                                    <h2 class="mt-2.5 text-2xl font-extrabold text-slate-900 tracking-tight">Uploaded Documents</h2>
                                </div>
                                <div class="rounded-full bg-slate-100 px-4 py-1.5 text-xs text-slate-500 font-bold border border-slate-200">Approved Credentials</div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block">Company Logo</span>
                                    @if(!empty($agent->company_logo))
                                        <a href="{{ asset('storage/'.$agent->company_logo) }}" target="_blank" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition">View file logo</a>
                                    @else
                                        <span class="mt-3 block text-xs text-slate-400 font-medium">Not provided</span>
                                    @endif
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block">DTS License File</span>
                                    @if(!empty($agent->dts_license))
                                        <a href="{{ asset('storage/'.$agent->dts_license) }}" target="_blank" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition">View license credentials</a>
                                    @else
                                        <span class="mt-3 block text-xs text-slate-400 font-medium">Not provided</span>
                                    @endif
                                </div>
                                <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block">CNIC Front & Back Card</span>
                                    <div class="mt-3 flex gap-4 text-xs font-bold">
                                        @if(!empty($agent->cnic_front))
                                            <a href="{{ asset('storage/'.$agent->cnic_front) }}" target="_blank" class="text-blue-600 hover:text-blue-700">Front Side</a>
                                        @else
                                            <span class="text-slate-400 font-normal">No Front</span>
                                        @endif
                                        <span class="text-slate-300">|</span>
                                        @if(!empty($agent->cnic_back))
                                            <a href="{{ asset('storage/'.$agent->cnic_back) }}" target="_blank" class="text-blue-600 hover:text-blue-700">Back Side</a>
                                        @else
                                            <span class="text-slate-400 font-normal">No Back</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="status" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                            <div class="border-b border-slate-100 pb-5">
                                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Account status</span>
                                <h2 class="mt-2.5 text-2xl font-extrabold text-slate-900 tracking-tight">Approval Status</h2>
                            </div>
                            <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 p-6">
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Current Verification status</span>
                                <span class="mt-2 block text-2xl font-black uppercase {{ ($agent->status ?? 'Active') === 'Approved' || ($agent->status ?? 'Active') === 'Active' ? 'text-emerald-600' : (($agent->status ?? '') === 'Rejected' ? 'text-rose-600' : 'text-amber-600') }}">{{ $agent->status ?? 'Active' }}</span>
                                @if (!empty($agent->remarks))
                                    <div class="mt-4 pt-4 border-t border-slate-200/60">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block mb-1">Administrative Remarks</span>
                                        <p class="text-sm font-semibold text-slate-600 leading-relaxed">{{ $agent->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                </div>
            </main>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal fade hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4 py-6" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-centered w-full max-w-lg">
            <div class="modal-content border-0 shadow rounded-3xl bg-white overflow-hidden">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="logoutModalLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-slate-600">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer border-0">
                    <button id="logoutCancel" type="button" class="btn btn-outline-secondary">Cancel</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripting for Mobile Sidebar Drawer -->
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

        const logoutTrigger = document.getElementById('logoutTrigger');
        const logoutModal = document.getElementById('logoutModal');
        const logoutCancel = document.getElementById('logoutCancel');

        const openLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.remove('hidden');
            logoutModal.style.display = 'flex';
            document.body.classList.add('overflow-hidden');
        };

        const closeLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.add('hidden');
            logoutModal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        };

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', openLogoutModal);
        }

        if (logoutCancel) {
            logoutCancel.addEventListener('click', closeLogoutModal);
        }

        if (logoutModal) {
            logoutModal.addEventListener('click', (event) => {
                if (event.target === logoutModal) {
                    closeLogoutModal();
                }
            });
        }
    </script>
</body>
</html>