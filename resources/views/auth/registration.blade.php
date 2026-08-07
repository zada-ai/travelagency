@extends('auth.layouts')

@section('title', 'Customer Registration')

@section('content')
    <div>
        {{-- Brand Header --}}
        <div class="flex items-center gap-3">
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                style="background:linear-gradient(135deg,#2563eb,#10b981); box-shadow:0 8px 20px -6px rgba(37,99,235,0.45);"
            >
                <svg width="20" height="20" viewBox="0 0 40 40" fill="none">
                    <path
                        d="M20 3 L23.5 16.5 L37 20 L23.5 23.5 L20 37 L16.5 23.5 L3 20 L16.5 16.5 Z"
                        fill="white"
                    />
                </svg>
            </div>

            <div class="leading-tight">
                <span class="block text-lg font-extrabold tracking-tight text-slate-900">
                    Hujaj <span class="text-emerald-600">Umrah</span>
                </span>

                <span class="block text-[10px] font-bold uppercase tracking-[0.18em] text-blue-600">
                    Customer Registration
                </span>
            </div>
        </div>

        {{-- Heading --}}
        <div class="mt-8 sm:mt-9">
            <div
                class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.22em] text-blue-600"
            >
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Step-by-step customer registration
            </div>

            <h1 class="mt-3 text-[24px] font-extrabold tracking-tight text-slate-900 sm:text-[28px]">
                Create your customer profile
            </h1>

            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Complete your details in three simple steps to start your Umrah journey.
            </p>
        </div>

        {{-- Progress Indicator --}}
        <div class="mt-6 flex items-center gap-2">
            <div
                class="h-2 flex-1 rounded-full bg-blue-600"
                id="progressBar1"
            ></div>

            <div
                class="h-2 flex-1 rounded-full bg-slate-200"
                id="progressBar2"
            ></div>

            <div
                class="h-2 flex-1 rounded-full bg-slate-200"
                id="progressBar3"
            ></div>
        </div>

        <div class="mt-3 flex items-center justify-between text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
            <span>Account</span>
            <span>Travel Info</span>
            <span>Profile</span>
        </div>

        {{-- Validation Error Alert --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs text-red-700">
                <div class="mb-1 flex items-center gap-2 font-bold">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <span>Please fix the following</span>
                </div>

                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Registration Form --}}
        <form
            action="{{ route('register.submit') }}"
            method="POST"
            class="mt-6 space-y-4 sm:mt-7"
            autocomplete="on"
            id="customerRegistrationForm"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- =========================================================
                STEP 1
            ========================================================== --}}
            <div id="step-1" data-step="1" class="space-y-4">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <label
                            for="name"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Full name
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="e.g. Ahmed Raza"
                        >
                    </div>

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Email address
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="name@example.com"
                        >
                    </div>

                    {{-- Mobile --}}
                    <div>
                        <label
                            for="mobile_number"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Mobile number
                        </label>

                        <input
                            type="tel"
                            name="mobile_number"
                            id="mobile_number"
                            value="{{ old('mobile_number') }}"
                            required
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="+92 300 1234567"
                        >
                    </div>

                    {{-- Password --}}
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="At least 8 characters"
                        >
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Confirm password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            required
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="Re-enter your password"
                        >
                    </div>

                    {{-- DOB --}}
                    <div>
                        <label
                            for="date_of_birth"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Date of birth
                        </label>

                        <input
                            type="date"
                            name="date_of_birth"
                            id="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label
                            for="gender"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Gender
                        </label>

                        <select
                            name="gender"
                            id="gender"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                </div>

                <div class="flex justify-end">
                    <button
                        type="button"
                        onclick="showStep(2)"
                        class="min-h-[48px] rounded-2xl bg-blue-600 px-6 font-bold text-sm text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:bg-blue-700 hover:shadow-xl"
                    >
                        Next
                        <span class="ml-1">→</span>
                    </button>
                </div>
            </div>


            {{-- =========================================================
                STEP 2
            ========================================================== --}}
            <div id="step-2" data-step="2" class="hidden space-y-4">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- WhatsApp --}}
                    <div>
                        <label
                            for="whatsapp_number"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            WhatsApp number
                        </label>

                        <input
                            type="tel"
                            name="whatsapp_number"
                            id="whatsapp_number"
                            value="{{ old('whatsapp_number') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Optional"
                        >
                    </div>

                    {{-- Nationality --}}
                    <div>
                        <label
                            for="nationality"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Nationality
                        </label>

                        <input
                            type="text"
                            name="nationality"
                            id="nationality"
                            value="{{ old('nationality') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Pakistan"
                        >
                    </div>

                    {{-- CNIC --}}
                    <div>
                        <label
                            for="cnic"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            CNIC / National ID number
                        </label>

                        <input
                            type="text"
                            name="cnic"
                            id="cnic"
                            value="{{ old('cnic') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="42201-1234567-8"
                        >
                    </div>

                    {{-- Passport --}}
                    <div>
                        <label
                            for="passport_number"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Passport number
                        </label>

                        <input
                            type="text"
                            name="passport_number"
                            id="passport_number"
                            value="{{ old('passport_number') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="PK1234567"
                        >
                    </div>

                    {{-- Passport Expiry --}}
                    <div>
                        <label
                            for="passport_expiry"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Passport expiry date
                        </label>

                        <input
                            type="date"
                            name="passport_expiry"
                            id="passport_expiry"
                            value="{{ old('passport_expiry') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                        >
                    </div>

                    {{-- Country --}}
                    <div>
                        <label
                            for="country"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Country
                        </label>

                        <select
                            name="country"
                            id="country"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                        >
                            <option value="">Select country</option>
                            <option value="Pakistan" {{ old('country') === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                            <option value="Saudi Arabia" {{ old('country') === 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                            <option value="United Arab Emirates" {{ old('country') === 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                            <option value="United Kingdom" {{ old('country') === 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                        </select>
                    </div>

                    {{-- City --}}
                    <div>
                        <label
                            for="city"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            id="city"
                            value="{{ old('city') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Islamabad"
                        >
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label
                            for="address"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Enter your full address"
                        >{{ old('address') }}</textarea>
                    </div>

                </div>

                <div class="flex items-center justify-between gap-3">
                    <button
                        type="button"
                        onclick="showStep(1)"
                        class="min-h-[48px] rounded-2xl border border-slate-200 bg-white px-5 font-bold text-sm text-slate-600 transition-all duration-200 hover:border-blue-300 hover:text-blue-600"
                    >
                        ← Back
                    </button>

                    <button
                        type="button"
                        onclick="showStep(3)"
                        class="min-h-[48px] rounded-2xl bg-emerald-600 px-6 font-bold text-sm text-white shadow-lg shadow-emerald-500/20 transition-all duration-200 hover:bg-emerald-700"
                    >
                        Next →
                    </button>
                </div>
            </div>


            {{-- =========================================================
                STEP 3
            ========================================================== --}}
            <div id="step-3" data-step="3" class="hidden space-y-4">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    {{-- Emergency Contact Name --}}
                    <div>
                        <label
                            for="emergency_contact_name"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Emergency contact name
                        </label>

                        <input
                            type="text"
                            name="emergency_contact_name"
                            id="emergency_contact_name"
                            value="{{ old('emergency_contact_name') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="e.g. Ali Raza"
                        >
                    </div>

                    {{-- Relationship --}}
                    <div>
                        <label
                            for="relationship"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Relationship
                        </label>

                        <input
                            type="text"
                            name="relationship"
                            id="relationship"
                            value="{{ old('relationship') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="Brother"
                        >
                    </div>

                    {{-- Emergency Number --}}
                    <div>
                        <label
                            for="emergency_contact_number"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Emergency contact number
                        </label>

                        <input
                            type="tel"
                            name="emergency_contact_number"
                            id="emergency_contact_number"
                            value="{{ old('emergency_contact_number') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="+92 333 1234567"
                        >
                    </div>

                    {{-- Profile Photo --}}
                    <div>
                        <label
                            for="profile_photo"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Profile photo
                        </label>

                        <input
                            type="file"
                            name="profile_photo"
                            id="profile_photo"
                            accept="image/*"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                    </div>

                    {{-- Agent Reference --}}
                    <div>
                        <label
                            for="agent_reference"
                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                        >
                            Agent reference
                        </label>

                        <input
                            type="text"
                            name="agent_reference"
                            id="agent_reference"
                            value="{{ old('agent_reference') }}"
                            class="w-full min-h-[52px] rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition-all duration-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="Optional"
                        >
                    </div>

                    {{-- Terms --}}
                    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4 sm:col-span-2">

                        <label class="flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-slate-600">
                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                required
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <span>
                                I agree to the
                                <span class="font-bold text-blue-600">
                                    Terms & Conditions
                                </span>.
                            </span>
                        </label>

                        <label class="mt-3 flex cursor-pointer items-start gap-3 text-sm leading-relaxed text-slate-600">
                            <input
                                type="checkbox"
                                name="privacy_policy"
                                value="1"
                                required
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                            >

                            <span>
                                I agree to the
                                <span class="font-bold text-emerald-600">
                                    Privacy Policy
                                </span>.
                            </span>
                        </label>

                    </div>

                </div>

                <div class="flex items-center justify-between gap-3">
                    <button
                        type="button"
                        onclick="showStep(2)"
                        class="min-h-[48px] rounded-2xl border border-slate-200 bg-white px-5 font-bold text-sm text-slate-600 transition-all duration-200 hover:border-blue-300 hover:text-blue-600"
                    >
                        ← Back
                    </button>

                    <button
                        type="submit"
                        class="min-h-[48px] rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 px-6 font-bold text-sm text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:from-blue-700 hover:to-emerald-700 hover:shadow-xl"
                    >
                        Create Account
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- Footer with Login Link --}}
    <div class="mt-8 border-t border-slate-200 pt-6 text-center">
        <p class="text-xs font-medium text-slate-500">
            Already have an account?

            <a
                href="{{ route('login') }}"
                class="font-extrabold text-blue-600 transition-colors hover:text-emerald-600"
            >
                Sign in instead
            </a>
        </p>
    </div>


    {{-- Step Script --}}
    <script>
        function showStep(step) {
            const targetStep = document.querySelector(`[data-step="${step}"]`);

            if (!targetStep) {
                return;
            }

            document.querySelectorAll('[data-step]').forEach(function (panel) {
                panel.style.display = panel === targetStep ? '' : 'none';
            });

            targetStep.classList.remove('hidden');

            const bars = [
                document.getElementById('progressBar1'),
                document.getElementById('progressBar2'),
                document.getElementById('progressBar3')
            ];

            bars.forEach(function (bar, index) {
                if (!bar) {
                    return;
                }

                if (index + 1 <= step) {
                    bar.style.background =
                        index + 1 === step
                            ? '#2563eb'
                            : '#10b981';
                } else {
                    bar.style.background = '#e2e8f0';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            showStep(1);
        });
    </script>
@endsection