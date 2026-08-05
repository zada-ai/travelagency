@extends('auth.layouts')

@section('title', 'Sign In')

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
                <span class="block text-[10px] font-semibold uppercase tracking-[0.18em]" style="color:var(--gold);">Agency Operations Portal</span>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="mt-9 sm:mt-12">
            <h1 class="text-[26px] sm:text-3xl font-semibold tracking-tight font-display" style="color:var(--ink);">Welcome back</h1>
            <p class="text-sm mt-2 leading-relaxed" style="color:#5B6B63;">
                Sign in to manage visas, hotel allotments, and pilgrim itineraries.
            </p>
        </div>

        <!-- Validation Error Alert -->
        @if ($errors->any())
            <div class="mt-6 rounded-2xl p-4 text-xs border" style="background:#FBEAEA; border-color:#F0C6C6; color:#8A2E2E;">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>We couldn't sign you in</span>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="mt-7 sm:mt-9 space-y-5" autocomplete="on">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:#3B463E;">
                    Email address
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color:#9AA79C;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 6h16v12H4z" stroke-linejoin="round"/>
                            <path d="M4 7l8 6 8-6" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full min-h-[52px] rounded-2xl pl-11 pr-4 text-sm outline-none transition-all duration-200 border"
                        style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                        onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                        placeholder="name@agency.com">
                    @error('email')
                        <span class="text-xs mt-1" style="color:#8A2E2E;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Password Input with Toggle -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-xs font-bold uppercase tracking-wider" style="color:#3B463E;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold transition-colors hover:text-[#D4A856]" style="color:var(--gold);">Forgot password?</a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" style="color:#9AA79C;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="5" y="10" width="14" height="10" rx="2"/>
                            <path d="M8 10V7a4 4 0 018 0v3"/>
                        </svg>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full min-h-[52px] rounded-2xl pl-11 pr-11 text-sm outline-none transition-all duration-200 border"
                        style="background:#fff; border-color:#E4DDCB; color:var(--charcoal);"
                        onfocus="this.style.borderColor='var(--gold)'; this.style.boxShadow='0 0 0 4px rgba(200,155,60,0.14)';"
                        onblur="this.style.borderColor='#E4DDCB'; this.style.boxShadow='none';"
                        placeholder="••••••••">
                    <button type="button" 
                        onclick="const f=document.getElementById('password'); f.type = f.type==='password' ? 'text' : 'password';"
                        class="absolute right-4 top-1/2 -translate-y-1/2 transition-colors hover:text-[#6B7A73]" 
                        style="color:#9AA79C;" 
                        aria-label="Toggle password visibility">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    @error('password')
                        <span class="text-xs mt-1" style="color:#8A2E2E;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Remember Device Checkbox -->
            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-[#D8CFB4]" style="accent-color:var(--gold);">
                    <span class="text-xs font-semibold" style="color:#5B6B63;">Remember this device</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full mt-2 min-h-[54px] font-bold text-sm tracking-wide rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.99] hover:shadow-lg"
                style="background:var(--ink); color:var(--gold-light); box-shadow:0 14px 30px -10px rgba(11,42,46,0.5);"
                onmouseover="this.style.background='var(--ink-2)'" 
                onmouseout="this.style.background='var(--ink)'">
                <span>Sign in to dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Footer with Registration Link -->
    <div class="mt-10 pt-6 border-t text-center" style="border-color:#E9E2CE;">
        <p class="text-xs font-medium" style="color:#5B6B63;">
            Don't have an agency account?
            <a href="{{ route('register') }}" class="font-extrabold transition-colors hover:text-[#D4A856]" style="color:var(--gold);">Register your agency</a>
        </p>
    </div>
@endsection