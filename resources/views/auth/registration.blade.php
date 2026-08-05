@extends('auth.layouts')

@section('title', 'Customer Registration')

@section('content')
    <div>
        <!-- Brand Header -->
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:var(--ink); box-shadow:0 8px 20px -6px rgba(11,42,46,0.45);">
                <svg width="20" height="20" viewBox="0 0 40 40" fill="none">
                    <path d="M20 3 L23.5 16.5 L37 20 L23.5 23.5 L20 37 L16.5 23.5 L3 20 L16.5 16.5 Z" fill="var(--gold-light)"/>
                </svg>
            </div>
            <div class="leading-tight">
                <span class="block text-lg font-bold tracking-tight font-display" style="color:var(--ink);">Umrah ERP</span>
                <span class="block text-[10px] font-semibold uppercase tracking-[0.18em]" style="color:var(--gold);">Customer Onboarding</span>
            </div>
        </div>

        <!-- Heading -->
        <div class="mt-8 sm:mt-9">
            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em]" style="background:rgba(200,155,60,0.12); color:var(--gold);">
                <span class="h-2 w-2 rounded-full" style="background:var(--gold);"></span>
                Step-by-step customer registration
            </div>
            <h1 class="mt-3 text-[24px] sm:text-[28px] font-semibold tracking-tight font-display" style="color:var(--ink);">Create your customer profile</h1>
            <p class="mt-2 text-sm leading-relaxed" style="color:#5B6B63;">
                Complete your details in three simple steps to start your Umrah journey.
            </p>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-6 flex items-center gap-2">
            <div class="h-2 flex-1 rounded-full" id="progressBar1" style="background:var(--ink);"></div>
            <div class="h-2 flex-1 rounded-full" id="progressBar2" style="background:#E4DDCB;"></div>
            <div class="h-2 flex-1 rounded-full" id="progressBar3" style="background:#E4DDCB;"></div>
        </div>
        <div class="mt-3 flex items-center justify-between text-[11px] font-semibold uppercase tracking-[0.2em]" style="color:#6B7A73;">
            <span>Account</span>
            <span>Travel Info</span>
            <span>Profile</span>
        </div>

        <!-- Validation Error Alert -->
        @if ($errors->any())
            <div class="mt-6 rounded-2xl p-4 text-xs border" style="background:#FBEAEA; border-color:#F0C6C6; color:#8A2E2E;">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Please fix the following</span>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form action="{{ route('register.submit') }}" method="POST" class="mt-6 sm:mt-7 space-y-4" autocomplete="on" id="customerRegistrationForm">
            @csrf

            <!-- Step 1 -->
            <div id="step-1" data-step="1" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Full name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. Ahmed Raza">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Email address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="name@agency.com">
                    </div>

                    <div>
                        <label for="mobile_number" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Mobile number</label>
                        <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" required
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. +92 300 1234567">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="At least 8 characters">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Confirm password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="Re-enter your password">
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Date of birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';">
                    </div>

                    <div>
                        <label for="gender" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Gender</label>
                        <select name="gender" id="gender"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';">
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="showStep(2)"
                        class="min-h-[48px] rounded-2xl px-5 font-semibold text-sm transition-all duration-200" style="background:var(--ink); color:var(--gold-light); box-shadow:0 12px 24px -10px rgba(11,42,46,0.45);">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2 -->
            <div id="step-2" data-step="2" class="space-y-4 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">WhatsApp number</label>
                        <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="optional">
                    </div>

                    <div>
                        <label for="nationality" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Nationality</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="Pakistan">
                    </div>

                    <div>
                        <label for="cnic" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">CNIC / National ID number</label>
                        <input type="text" name="cnic" id="cnic" value="{{ old('cnic') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. 42201-1234567-8">
                    </div>

                    <div>
                        <label for="passport_number" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Passport number</label>
                        <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. PK1234567">
                    </div>

                    <div>
                        <label for="passport_expiry" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Passport expiry date</label>
                        <input type="date" name="passport_expiry" id="passport_expiry" value="{{ old('passport_expiry') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';">
                    </div>

                    <div>
                        <label for="country" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Country</label>
                        <select name="country" id="country"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';">
                            <option value="">Select country</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                        </select>
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="Karachi">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full rounded-2xl px-4 py-3 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="Enter your full address"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <button type="button" onclick="showStep(1)"
                        class="min-h-[48px] rounded-2xl px-5 font-semibold text-sm transition-all duration-200" style="background:#F1EADA; color:#3B463E;">
                        Back
                    </button>
                    <button type="button" onclick="showStep(3)"
                        class="min-h-[48px] rounded-2xl px-5 font-semibold text-sm transition-all duration-200" style="background:var(--ink); color:var(--gold-light); box-shadow:0 12px 24px -10px rgba(11,42,46,0.45);">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3 -->
            <div id="step-3" data-step="3" class="space-y-4 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="emergency_contact_name" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Emergency contact name</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. Ali Raza">
                    </div>

                    <div>
                        <label for="relationship" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Relationship</label>
                        <input type="text" name="relationship" id="relationship" value="{{ old('relationship') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="Brother">
                    </div>

                    <div>
                        <label for="emergency_contact_number" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Emergency contact number</label>
                        <input type="tel" name="emergency_contact_number" id="emergency_contact_number" value="{{ old('emergency_contact_number') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="e.g. +92 333 1234567">
                    </div>

                    <div>
                        <label for="profile_photo" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Profile photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                            class="w-full min-h-[52px] rounded-2xl px-4 py-3 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);">
                    </div>

                    <div>
                        <label for="agent_reference" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">Agent reference</label>
                        <input type="text" name="agent_reference" id="agent_reference" value="{{ old('agent_reference') }}"
                            class="w-full min-h-[52px] rounded-2xl px-4 text-sm outline-none transition-all duration-200 border"
                            style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                            onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                            onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                            placeholder="optional">
                    </div>

                    <div class="sm:col-span-2 rounded-2xl border p-4" style="border-color:#E4DDCB; background:rgba(251,247,238,0.6);">
                        <label class="flex items-start gap-3 text-sm leading-relaxed cursor-pointer" style="color:#3B463E;">
                            <input type="checkbox" name="terms" value="1" required class="mt-1 h-4 w-4 rounded border-[#D8CFB4]" style="accent-color:var(--gold);">
                            <span>I agree to the <span class="font-semibold" style="color:var(--gold);">Terms & Conditions</span>.</span>
                        </label>
                        <label class="mt-3 flex items-start gap-3 text-sm leading-relaxed cursor-pointer" style="color:#3B463E;">
                            <input type="checkbox" name="privacy_policy" value="1" required class="mt-1 h-4 w-4 rounded border-[#D8CFB4]" style="accent-color:var(--gold);">
                            <span>I agree to the <span class="font-semibold" style="color:var(--gold);">Privacy Policy</span>.</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <button type="button" onclick="showStep(2)"
                        class="min-h-[48px] rounded-2xl px-5 font-semibold text-sm transition-all duration-200" style="background:#F1EADA; color:#3B463E;">
                        Back
                    </button>
                    <button type="submit"
                        class="min-h-[48px] rounded-2xl px-5 font-semibold text-sm transition-all duration-200" style="background:var(--ink); color:var(--gold-light); box-shadow:0 12px 24px -10px rgba(11,42,46,0.45);">
                        Create account
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer with Login Link -->
    <div class="mt-8 pt-6 border-t text-center" style="border-color:#E9E2CE;">
        <p class="text-xs font-medium" style="color:#5B6B63;">
            Already have an account?
            <a href="{{ route('login') }}" class="font-extrabold transition-colors hover:text-[#D4A856]" style="color:var(--gold);">Sign in instead</a>
        </p>
    </div>

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

            const bars = [document.getElementById('progressBar1'), document.getElementById('progressBar2'), document.getElementById('progressBar3')];
            bars.forEach(function (bar, index) {
                if (bar) {
                    bar.style.background = (index + 1 <= step) ? 'var(--ink)' : '#E4DDCB';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            showStep(1);
        });
    </script>
@endsection
