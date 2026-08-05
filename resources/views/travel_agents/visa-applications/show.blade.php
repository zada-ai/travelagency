<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application Details | Agent Portal</title>
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
            background: rgba(255, 255, 255, 0.88);
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
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
                            <span class="text-xl">🏠</span>
                            Overview
                        </a>
                        <a href="{{ route('travel-agents.hotels.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <span class="text-xl">🏨</span>
                            Hotels
                        </a>
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <span class="text-xl">✈️</span>
                            Tickets
                        </a>
                        <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-blue-600 bg-blue-50 transition duration-200">
                            <span class="text-xl">🛂</span>
                            Visa Applications
                        </a>
                        <a href="{{ route('travel-agents.bookings') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <span class="text-xl">📘</span>
                            My Bookings
                        </a>
                    </nav>
                </div>

                <form action="{{ route('travel-agents.logout') }}" method="POST" class="pt-4 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 shadow-xs transition">Logout</button>
                </form>
            </aside>

            <main class="p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden">
                <div class="max-w-6xl mx-auto space-y-6">
                    <nav class="flex items-center gap-2 text-sm text-slate-500">
                        <a href="{{ route('travel-agents.visa-applications') }}" class="hover:text-blue-600 transition">Visa Applications</a>
                        <span>→</span>
                        <span class="text-blue-600 font-semibold">Application Details</span>
                    </nav>

                    <section class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Visa Application</p>
                                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Application #{{ $visaApplication->id }}</h1>
                                <p class="mt-2 text-sm text-slate-500">Review customer details, document status, and visa progress.</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('travel-agents.visa-applications.edit', $visaApplication->id) }}" class="inline-flex items-center justify-center rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Edit application</a>
                                <a href="{{ route('travel-agents.visa-applications.print', $visaApplication->id) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Print</a>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-6 lg:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Customer details</h2>
                                <dl class="mt-6 grid gap-4">
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Customer Name</dt>
                                        <dd class="mt-1 font-bold text-slate-900">{{ $visaApplication->customer_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Passport Number</dt>
                                        <dd class="mt-1 font-bold text-slate-900">{{ $visaApplication->passport_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Nationality</dt>
                                        <dd class="mt-1 font-bold text-slate-900">{{ $visaApplication->nationality }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Status</dt>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Status</dt>
                                        <dd class="mt-1 inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 uppercase">{{ $visaApplication->status }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Visa summary</h2>
                                <dl class="mt-6 grid gap-4">
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Visa type</dt>
                                        <dd class="mt-1 font-bold text-slate-900">{{ $visaApplication->visaType?->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Visa fee</dt>
                                        <dd class="mt-1 font-bold text-slate-900">SAR {{ number_format($visaApplication->visa_fee, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Service charges</dt>
                                        <dd class="mt-1 font-bold text-slate-900">SAR {{ number_format($visaApplication->service_charges, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Total amount</dt>
                                        <dd class="mt-1 text-xl font-extrabold text-slate-900">SAR {{ number_format($visaApplication->total_amount, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Assigned officer</dt>
                                        <dd class="mt-1 font-bold text-slate-900">{{ $visaApplication->visaOfficer?->name ?? 'Not assigned' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-6 lg:grid-cols-3">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Passport expiry</p>
                                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $visaApplication->passport_expiry?->format('d M Y') }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Created by</p>
                                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $agent->company_name }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Submitted on</p>
                                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $visaApplication->created_at?->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-4 lg:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Notes & remarks</h3>
                                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $visaApplication->remarks ?? 'No remarks provided.' }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Documents</h3>
                                <div class="mt-4 space-y-3">
                                    <a href="{{ route('travel-agents.visa-applications.document.download', ['id' => $visaApplication->id, 'field' => 'passport_copy']) }}" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Passport Copy <span>Download</span></a>
                                    <a href="{{ route('travel-agents.visa-applications.document.download', ['id' => $visaApplication->id, 'field' => 'photograph']) }}" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Photograph <span>Download</span></a>
                                    <a href="{{ route('travel-agents.visa-applications.document.download', ['id' => $visaApplication->id, 'field' => 'cnic_copy']) }}" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">CNIC Copy <span>Download</span></a>
                                    <a href="{{ route('travel-agents.visa-applications.document.download', ['id' => $visaApplication->id, 'field' => 'vaccination_certificate']) }}" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Vaccination Certificate <span>Download</span></a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        if (mobileMenuToggle) mobileMenuToggle.addEventListener('click', openSidebar);
        if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
