<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sub-Agent | Agent Portal</title>
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
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.55rem;
        }
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border-width: 1px;
            border-color: #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #3b82f6;
        }
        .file-input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            background: #ffffff;
        }
        .file-input-btn {
            background-color: #f8fafc;
            color: #334155;
            padding: 0.95rem 1rem;
            font-size: 0.92rem;
            border-right: 1px solid #e2e8f0;
            font-weight: 600;
            cursor: pointer;
        }
        .file-input-name {
            padding: 0.95rem 1rem;
            color: #64748b;
            font-size: 0.92rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    @php
        $agent = auth()->guard('travel_agent')->user();
        $portalLabel = 'Agent Portal';
        $portalSystemLabel = 'Agent Portal System';
    @endphp

    <header class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-4 backdrop-blur-md xl:hidden sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
            </div>
            <span class="text-lg font-bold text-slate-800">{{ $portalLabel }}</span>
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
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white"><path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" /></svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 tracking-tight">Hujaj Umrah</h1>
                                <p class="text-xs text-slate-500">{{ $portalSystemLabel }}</p>
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
                                <p class="font-bold text-slate-800 truncate text-sm mt-0.5">{{ $agent->company_name ?? 'Travel Agency' }}</p>
                            </div>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <a href="{{ route('travel-agents.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Overview
                        </a>
                        <a href="{{ route('travel-agents.hotels.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-16.5 0V3.75c0-.414.336-.75.75-.75h7.5c.414 0 .75.336.75.75V21m-9 0h18" />
                            </svg>
                            Hotels
                        </a>
                        <a href="{{ route('travel-agents.tickets') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h5.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125H7.5a1.125 1.125 0 01-1.125-1.125V7.125C6.375 6.504 6.879 6 7.5 6z" />
                            </svg>
                            Tickets
                        </a>
                        <a href="{{ route('travel-agents.visa-applications') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Visa Applications
                        </a>
                        <a href="{{ route('travel-agents.customer-visa.index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-blue-50/50 hover:text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-400 group-hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Customers
                        </a>
                        <a href="{{ route('travel-agents.sub-agents.create') }}" class="group flex items-center gap-3 rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm-7.5 13.5a7.5 7.5 0 0115 0v1.125a1.125 1.125 0 01-1.125 1.125H6.375A1.125 1.125 0 015.25 19.5V18zm14.25-3.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                            Create Sub-Agent
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
                        <a href="{{ route('travel-agents.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                        <span>→</span>
                        <span class="text-blue-600 font-semibold">Create Sub-Agent</span>
                    </nav>

                    <section class="glass-panel rounded-[2rem] p-6 md:p-8 shadow-xs">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Sub-Agent</p>
                                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Create a New Sub-Agent</h1>
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">Use your agent dashboard to add a sub-agent instantly. This page matches your dashboard styles and keeps the same form fields and submission flow.</p>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mt-6 rounded-3xl border border-red-100 bg-red-50 p-5 text-sm text-red-700">
                                <p class="font-semibold">Please fix the following errors:</p>
                                <ul class="mt-3 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ $action ?? route('travel-agents.sub-agents.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                            @csrf
                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="block">
                                    <span class="form-label">First Name</span>
                                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required placeholder="John" class="form-input" />
                                </label>
                                <label class="block">
                                    <span class="form-label">Last Name</span>
                                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required placeholder="Doe" class="form-input" />
                                </label>
                                <label class="block">
                                    <span class="form-label">Company Name</span>
                                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required placeholder="Company Name" class="form-input" />
                                </label>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="block">
                                    <span class="form-label">Email Address</span>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="example@mail.com" class="form-input" />
                                </label>
                                <label class="block">
                                    <span class="form-label">Mobile Number</span>
                                    <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" required placeholder="03XXXXXXXXX" class="form-input" />
                                </label>
                                <label class="block">
                                    <span class="form-label">Company Address</span>
                                    <input id="company_address" name="company_address" type="text" value="{{ old('company_address') }}" required placeholder="Company Address" class="form-input" />
                                </label>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="block">
                                    <span class="form-label">Password</span>
                                    <div class="relative">
                                        <input id="password" name="password" type="password" required placeholder="••••••••" class="form-input pr-16" />
                                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-800 text-sm font-semibold">Show</button>
                                    </div>
                                </label>
                                <label class="block">
                                    <span class="form-label">Confirm Password</span>
                                    <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••" class="form-input" />
                                </label>
                                <label class="block">
                                    <span class="form-label">Country</span>
                                    <select id="country" name="country" required class="form-select">
                                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>Select Country</option>
                                        <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="Saudi Arabia" {{ old('country') == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                        <option value="United Arab Emirates" {{ old('country') == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                                        <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                        <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </label>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="block">
                                    <span class="form-label">City</span>
                                    <input id="city" name="city" type="text" value="{{ old('city') }}" required placeholder="City" class="form-input" />
                                </label>
                                <div class="lg:col-span-2">
                                    <span class="form-label">Password Strength</span>
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="flex items-center justify-between mb-3 text-sm text-slate-500">
                                            <span>Strength indicator</span>
                                            <span id="strength-text" class="font-semibold text-slate-700">Weak</span>
                                        </div>
                                        <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                                            <div id="strength-bar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-3">
                                <label class="block">
                                    <span class="form-label">Company Logo</span>
                                    <div class="file-input-wrapper">
                                        <label for="company_logo" class="file-input-btn">Choose File</label>
                                        <span id="logo-preview" class="file-input-name">No file chosen</span>
                                        <input id="company_logo" name="company_logo" type="file" accept="image/*" required class="hidden" onchange="previewFile('company_logo', 'logo-preview')" />
                                    </div>
                                </label>
                                <label class="block">
                                    <span class="form-label">DTS License</span>
                                    <div class="file-input-wrapper">
                                        <label for="dts_license" class="file-input-btn">Choose File</label>
                                        <span id="license-preview" class="file-input-name">No file chosen</span>
                                        <input id="dts_license" name="dts_license" type="file" accept="image/*,.pdf" required class="hidden" onchange="previewFile('dts_license', 'license-preview')" />
                                    </div>
                                </label>
                                <label class="block">
                                    <span class="form-label">Owner CNIC</span>
                                    <div class="file-input-wrapper">
                                        <label for="cnic_front" class="file-input-btn">Choose File</label>
                                        <span id="cnic-front-preview" class="file-input-name">No file chosen</span>
                                        <input id="cnic_front" name="cnic_front" type="file" accept="image/*,.pdf" required class="hidden" onchange="previewFile('cnic_front', 'cnic-front-preview')" />
                                    </div>
                                </label>
                            </div>

                            <div class="grid gap-6 lg:grid-cols-2">
                                <label class="block">
                                    <span class="form-label">CNIC Back (Optional)</span>
                                    <div class="file-input-wrapper">
                                        <label for="cnic_back" class="file-input-btn">Choose File</label>
                                        <span id="cnic-back-preview" class="file-input-name">No file chosen</span>
                                        <input id="cnic_back" name="cnic_back" type="file" accept="image/*,.pdf" class="hidden" onchange="previewFile('cnic_back', 'cnic-back-preview')" />
                                    </div>
                                </label>
                                <label class="block">
                                    <span class="form-label">Remarks</span>
                                    <textarea id="remarks" name="remarks" rows="4" class="form-textarea" placeholder="Optional notes for the agency">{{ old('remarks') }}</textarea>
                                </label>
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="terms" name="terms" value="1" required class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                    <label for="terms" class="text-sm text-slate-600">I agree to the terms and conditions.</label>
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 hover:bg-blue-700 transition">{{ $buttonText ?? 'Create Sub-Agent' }}</button>
                            </div>
                        </form>
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

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (!passwordInput) return;

            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        function previewFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(previewId);
            if (!input || !label) return;

            const fileName = input.files.length > 0 ? input.files[0].name : 'No file chosen';
            label.textContent = fileName;
        }

        const passwordField = document.getElementById('password');
        const strengthText = document.getElementById('strength-text');
        const strengthBar = document.getElementById('strength-bar');

        if (passwordField && strengthText && strengthBar) {
            passwordField.addEventListener('input', function () {
                const value = passwordField.value;
                const score = Math.min(4, Math.max(1, Math.round((value.length / 8) * 4)));
                const states = [
                    { text: 'Very Weak', color: 'bg-red-500', width: 'w-1/4' },
                    { text: 'Weak', color: 'bg-orange-500', width: 'w-1/2' },
                    { text: 'Good', color: 'bg-yellow-500', width: 'w-3/4' },
                    { text: 'Strong', color: 'bg-emerald-500', width: 'w-full' },
                ];
                const state = states[Math.max(0, Math.min(states.length - 1, score - 1))];

                strengthText.textContent = state.text;
                strengthBar.className = `h-full ${state.width} ${state.color} transition-all duration-300`;
            });
        }
    </script>
</body>
</html>
