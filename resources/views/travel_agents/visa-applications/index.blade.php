<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Applications | Umrah ERP</title>
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

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300 xl:hidden"></div>

    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[280px_1fr] relative">
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] -translate-x-full border-r border-slate-200 bg-white p-6 transition-transform duration-350 cubic-bezier(0.4, 0, 0.2, 1) xl:static xl:translate-x-0 flex flex-col justify-between shadow-xs">
                <div class="space-y-8">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

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

                    <nav class="space-y-1">
                        <a href="{{ route('travel-agents.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            Overview
                        </a>
                        <a href="{{ route('travel-agents.hotels.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-16.5 0V3.75c0-.414.336-.75.75-.75h7.5c.414 0 .75.336.75.75V21m-9 0h18" /></svg>
                            Hotels
                        </a>
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 0 1 9-9" /></svg>
                            Tickets
                        </a>
                        <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            Visa Applications
                        </a>
                        <a href="#profile" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            Profile
                        </a>
                        <a href="#documents" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            Documents
                        </a>
                        <a href="#status" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Approval Status
                        </a>
                        <form action="{{ route('travel-agents.logout') }}" method="POST" class="pt-4 border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 shadow-xs transition">Logout</button>
                        </form>
                    </nav>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-inner mt-8">
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 mb-2">24/7 Support</span>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Need instant booking assistance? Reach out directly via WhatsApp.</p>
                    <a href="https://wa.me/923123456789" target="_blank" class="mt-3.5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white py-2.5 shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97a16.607 16.607 0 0 0 6.585 6.585l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" /></svg>
                        WhatsApp Support
                    </a>
                </div>
            </aside>

            <main class="p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden">
                <div class="max-w-6xl mx-auto space-y-6">
                    <nav class="flex items-center gap-2 text-sm">
                        <a href="{{ route('travel-agents.dashboard') }}" class="text-slate-500 hover:text-blue-600 transition font-medium">Dashboard</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-slate-300"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        <span class="text-blue-600 font-semibold">Visa Applications</span>
                    </nav>

                    <section class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Visa Applications</p>
                                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Visa Applications</h1>
                                <p class="mt-2 text-sm text-slate-500">Manage and track visa processing for your clients.</p>
                            </div>
                            <a href="{{ route('travel-agents.visa-applications.create') }}" class="inline-flex items-center justify-center rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition">Create Application</a>
                        </div>
                    </section>

                    <section class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                        <form action="{{ route('travel-agents.visa-applications') }}" method="GET" class="grid gap-4 xl:grid-cols-[1.6fr_0.95fr]">
                            <div class="grid gap-4 md:grid-cols-3">
                                <label class="block">
                                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Search</span>
                                    <input name="search" value="{{ request('search') }}" type="text" placeholder="Customer name or passport" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                                </label>
                                <label class="block">
                                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Visa Type</span>
                                    <select name="visa_type_id" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none">
                                        <option value="">All types</option>
                                        @foreach($visaTypes as $visaType)
                                            <option value="{{ $visaType->id }}" {{ request('visa_type_id') == $visaType->id ? 'selected' : '' }}>{{ $visaType->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="block">
                                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</span>
                                    <select name="status" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none">
                                        <option value="">All statuses</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3 items-end">
                                <label class="block">
                                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">From</span>
                                    <input name="from_date" type="date" value="{{ request('from_date') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                                </label>
                                <label class="block">
                                    <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">To</span>
                                    <input name="to_date" type="date" value="{{ request('to_date') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                                </label>
                                <div class="flex gap-2">
                                    <button type="submit" class="inline-flex min-h-[58px] items-center justify-center rounded-3xl bg-blue-600 px-6 py-4 text-sm font-semibold text-white hover:bg-blue-700 transition">Filter</button>
                                    <a href="{{ route('travel-agents.visa-applications') }}" class="inline-flex min-h-[58px] items-center justify-center rounded-3xl border border-slate-200 bg-white px-6 py-4 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Reset</a>
                                </div>
                            </div>
                        </form>
                    </section>

                    <section class="grid gap-6">
                        @forelse ($visaApplications as $visaApplication)
                            @php
                                $statusClasses = [
                                    'Approved' => 'bg-emerald-50 text-emerald-700',
                                    'Issued' => 'bg-emerald-50 text-emerald-700',
                                    'Embassy Checking' => 'bg-purple-50 text-purple-700',
                                    'Under Review' => 'bg-amber-50 text-amber-700',
                                    'Pending' => 'bg-orange-50 text-orange-700',
                                    'Rejected' => 'bg-rose-50 text-rose-700',
                                    'Draft' => 'bg-blue-50 text-blue-700',
                                    'Submitted' => 'bg-blue-50 text-blue-700',
                                ];
                                $statusBadgeClass = $statusClasses[$visaApplication->status] ?? 'bg-slate-50 text-slate-600';
                            @endphp
                            <article class="glass-panel rounded-3xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] {{ $statusBadgeClass }}">{{ $visaApplication->status }}</span>
                                        <h2 class="mt-4 text-2xl font-bold text-slate-900">{{ $visaApplication->customer_name }}</h2>
                                        <p class="mt-2 text-sm text-slate-500">Passport {{ $visaApplication->passport_number }} · {{ $visaApplication->visaType?->name ?? 'Visa product unavailable' }}</p>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-3">
                                        <div class="rounded-3xl bg-slate-50 p-4 text-slate-700">
                                            <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Amount</p>
                                            <p class="mt-2 text-base font-semibold text-slate-900">SAR {{ number_format($visaApplication->total_amount, 2) }}</p>
                                        </div>
                                        <div class="rounded-3xl bg-slate-50 p-4 text-slate-700">
                                            <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Created</p>
                                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $visaApplication->created_at?->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    <a href="{{ route('travel-agents.visa-applications.show', $visaApplication->id) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">View</a>
                                    <a href="{{ route('travel-agents.visa-applications.edit', $visaApplication->id) }}" class="inline-flex items-center justify-center rounded-3xl border border-blue-200 bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">Edit</a>
                                    <form action="{{ route('travel-agents.visa-applications.destroy', $visaApplication->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 hover:bg-red-100 transition">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="glass-panel rounded-3xl border border-slate-200 p-10 text-center">
                                <p class="text-xl font-semibold text-slate-900">No visa applications found</p>
                                <p class="mt-2 text-sm text-slate-500">Start by creating a visa application for your first customer.</p>
                                <a href="{{ route('travel-agents.visa-applications.create') }}" class="mt-5 inline-flex items-center justify-center rounded-3xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Create application</a>
                            </div>
                        @endforelse
                    </section>

                    <div class="mt-4">
                        {{ $visaApplications->withQueryString()->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>

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
