@php
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = auth()->guard('travel_agent')->user() ?? $currentUser;
    $hasWebUser = auth()->check();
    $hasTravelAgentUser = auth()->guard('travel_agent')->check();
    $isCustomer = (bool) ($hasWebUser && ! $hasTravelAgentUser);
    $isVisaOfficer = false;
    $userRole = $hasTravelAgentUser ? 'travel_agent' : 'customer';

    if (! $isCustomer && ! $hasTravelAgentUser && ! $hasWebUser) {
        $isCustomer = true;
        $userRole = 'customer';
    }

    $portalLabel = 'Agent Portal';
    $portalSystemLabel = 'Agent Portal System';
@endphp

@extends('layouts.dashboard')

@section('content')
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

    <script>
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
@endsection
