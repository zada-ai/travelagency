<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hujaj Umrah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

    <header class="sticky top-0 z-50 shadow-sm">

        {{-- ===================== TOP UTILITY BAR ===================== --}}
        <div class="hidden bg-slate-950 text-slate-200 md:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2 text-xs sm:px-6 lg:px-8">
                <div class="flex items-center gap-5">
                    <a href="tel:+923001234567" class="flex items-center gap-2 transition hover:text-white">
                        <i class="bi bi-telephone-fill text-blue-400"></i>
                        +92 300 1234567
                    </a>
                    <a href="mailto:hello@hujajumrah.com" class="flex items-center gap-2 transition hover:text-white">
                        <i class="bi bi-envelope-fill text-blue-400"></i>
                        hello@hujajumrah.com
                    </a>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="#" class="transition hover:text-blue-400"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="transition hover:text-blue-400"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="transition hover:text-blue-400"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        {{-- ===================== MAIN NAVBAR ===================== --}}
        <div class="border-b border-slate-200/80 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-slate-900 text-white shadow-lg shadow-blue-500/20">
                        <i class="bi bi-compass text-xl"></i>
                    </div>
                    <div>
                        <p class="text-base font-bold tracking-tight text-slate-900">Hujaj Umrah</p>
                        <p class="text-xs text-slate-500">Premium pilgrimage journeys</p>
                    </div>
                </a>

                {{-- Mobile toggle --}}
                <button id="navbarToggle" type="button" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-3 text-slate-700 shadow-sm transition hover:border-blue-300 md:hidden">
                    <i class="bi bi-list text-xl"></i>
                </button>

                {{-- Nav links --}}
                <nav id="navbarMenu" class="absolute inset-x-4 top-full z-40 hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-2xl md:static md:z-auto md:flex md:flex-1 md:items-center md:justify-center md:rounded-none md:border-none md:bg-transparent md:p-0 md:shadow-none">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:gap-7">
                        <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Home</a>
                        <a href="{{ route('home') }}#about" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">About</a>
                        <a href="{{ route('packages.index') }}" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Umrah Packages</a>
                        <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Flights</a>
                        <a href="{{ route('hotels.booking') }}" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Hotels</a>
                        <a href="{{ route('home') }}#services" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Visa</a>
                        <a href="{{ route('travel-agents.login') }}" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">B2B Agent</a>
                        <a href="{{ route('home') }}#contact-cta" class="text-sm font-semibold text-slate-700 transition hover:text-blue-600">Contact</a>

                        {{-- Auth links: visible in mobile menu, hidden on desktop (shown separately on the right) --}}
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4 md:hidden">
                            @guest
                                <a href="{{ route('login') }}" class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="flex-1 rounded-full bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700">
                                    Register
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="flex-1 rounded-full bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700">
                                    My Account
                                </a>
                            @endguest
                        </div>
                    </div>
                </nav>

                {{-- Auth links: desktop only --}}
                <div class="hidden items-center gap-3 md:flex">
                    @guest
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700">
                            Register
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700">
                            My Account
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-slate-950 text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-slate-900 text-white shadow-lg shadow-blue-500/20">
                        <i class="bi bi-compass text-xl"></i>
                    </div>
                    <span class="text-base font-bold text-white">Hujaj Umrah</span>
                </a>
                <p class="max-w-sm text-sm leading-6 text-slate-400">Premium Umrah journeys built with trusted hotels, flights, and visa support for every pilgrim.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-400">Quick Links</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-300">
                    <li><a href="{{ route('packages.index') }}" class="transition hover:text-white">Umrah Packages</a></li>
                    <li><a href="{{ route('home') }}#services" class="transition hover:text-white">Visa Services</a></li>
                    <li><a href="{{ route('hotels.booking') }}" class="transition hover:text-white">Hotels</a></li>
                    <li><a href="{{ route('tickets.index') }}" class="transition hover:text-white">Flights</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-400">Services</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-300">
                    <li><a href="{{ route('packages.index') }}" class="transition hover:text-white">Umrah Booking</a></li>
                    <li><a href="{{ route('hotels.booking') }}" class="transition hover:text-white">Hotel Booking</a></li>
                    <li><a href="{{ route('tickets.index') }}" class="transition hover:text-white">Flight Tickets</a></li>
                    <li><a href="{{ route('register') }}" class="transition hover:text-white">Customer Register</a></li>
                </ul>
            </div>
            <div class="space-y-4 text-sm text-slate-300">
                <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-400">Contact</h3>
                <p>Office 12-34, Umrah Plaza, Islamabad, Pakistan</p>
                <p><a href="mailto:hello@hujajumrah.com" class="transition hover:text-white">hello@hujajumrah.com</a></p>
                <p><a href="tel:+923001234567" class="transition hover:text-white">+92 300 1234567</a></p>
                <div class="flex items-center gap-3 pt-2 text-white">
                    <i class="bi bi-facebook"></i>
                    <i class="bi bi-twitter"></i>
                    <i class="bi bi-instagram"></i>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 bg-slate-900 py-6">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>© {{ date('Y') }} Hujaj Umrah. All rights reserved.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="transition hover:text-white">Privacy</a>
                    <a href="#" class="transition hover:text-white">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- WhatsApp floating button --}}
    <a href="https://wa.me/923001234567" target="_blank" rel="noopener"
       class="fixed bottom-6 left-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl shadow-blue-900/30 transition hover:bg-blue-700">
        <i class="bi bi-whatsapp text-2xl"></i>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navToggle = document.getElementById('navbarToggle');
            const navMenu = document.getElementById('navbarMenu');
            navToggle?.addEventListener('click', function () {
                navMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
