<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hujaj Umrah')</title>
    <link rel="icon" type="image/png" href="{{ asset('Hujaj-Umrah.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('Hujaj-Umrah.png') }}">
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

<header id="siteHeader" class="z-100">

    {{-- ===================== TOP UTILITY BAR ===================== --}}
    <div
        id="topUtilityBar"
        class="hidden max-h-20 overflow-hidden bg-slate-950 text-slate-200 opacity-100 transition-all duration-200 md:block"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2 text-xs sm:px-6 lg:px-8">

            <div class="flex items-center gap-5">

                <a
                    href="tel:+923165444095"
                    class="flex items-center gap-2 transition hover:text-white"
                >
                    <i class="bi bi-telephone-fill text-blue-400"></i>
                    +923165444095
                </a>

                <a
                    href="mailto:info@umrahbooking.pk"
                    class="flex items-center gap-2 transition hover:text-white"
                >
                    <i class="bi bi-envelope-fill text-blue-400"></i>
                    info@umrahbooking.pk
                </a>

            </div>

            <div class="flex items-center gap-4 text-sm">

                <a href="https://facebook.com" class="transition hover:text-blue-400">
                    <i class="bi bi-facebook"></i>
                </a>

                <a href="https://twitter.com" class="transition hover:text-blue-400">
                    <i class="bi bi-twitter"></i>
                </a>

                <a href="https://instagram.com" class="transition hover:text-blue-400">
                    <i class="bi bi-instagram"></i>
                </a>

            </div>

        </div>
    </div>


    {{-- ===================== MAIN NAVBAR ===================== --}}
    <div
        id="mainNavbar"
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur transition-all duration-200"
    >

        <div
            id="navbarInner"
            class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 transition-all duration-200 sm:px-6 lg:px-8"
        >

            {{-- ===================== LOGO ===================== --}}
            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3"
            >

                <div class="flex items-center gap-3">

                    <img
                        id="navbarLogo"
                        src="{{ asset('Hujaj-Umrah.png') }}"
                        alt="Hujaj Umrah Logo"
                        class="h-10 w-10 rounded-lg object-cover shadow-sm transition-all duration-200"
                    >

                    <div class="leading-tight">

                        <p
                            id="navbarBrand"
                            class="text-lg font-extrabold tracking-tight text-slate-900 transition-all duration-200"
                        >
                            Hujaj
                            <span class="text-emerald-600">Umrah</span>
                        </p>

                        <p
                            id="navbarSubtitle"
                            class="text-[10px] font-medium uppercase tracking-[0.18em] text-slate-400 transition-all duration-200"
                        >
                            Travel & Umrah Services
                        </p>

                    </div>

                </div>

            </a>


            {{-- ===================== MOBILE TOGGLE ===================== --}}
            <button
                id="navbarToggle"
                type="button"
                class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-3 text-slate-700 shadow-sm transition hover:border-blue-300 md:hidden"
            >
                <i class="bi bi-list text-xl"></i>
            </button>


            {{-- ===================== NAV LINKS ===================== --}}
            <nav
                id="navbarMenu"
                class="absolute inset-x-4 top-full z-40 hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-2xl md:static md:z-auto md:flex md:flex-1 md:items-center md:justify-center md:rounded-none md:border-none md:bg-transparent md:p-0 md:shadow-none"
            >

                <div class="flex flex-col gap-4 md:flex-row md:items-center md:gap-7">

                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Home
                    </a>

                    <a
                        href="{{ route('home') }}#about"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        About
                    </a>

                    <a
                        href="{{ route('packages.index') }}"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Umrah Packages
                    </a>

                    <a
                        href="{{ route('home') }}#flights"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Flights
                    </a>

                    <a
                        href="{{ route('home') }}#hotels"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Hotels
                    </a>

                    {{-- <a
                        href="{{ route('home') }}#services"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Visa
                    </a> --}}

                    <a
                        href="{{ route('travel-agents.login') }}"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        B2B Agent
                    </a>

                    <a
                        href="{{ route('home') }}#contact-cta"
                        class="text-sm font-semibold text-slate-700 transition hover:text-blue-600"
                    >
                        Contact
                    </a>


                    {{-- Mobile Auth --}}
                    <div class="flex items-center gap-3 border-t border-slate-100 pt-4 md:hidden">

                        @guest

                            <a
                                href="{{ route('login') }}"
                                class="flex-1 rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:text-blue-600"
                            >
                                Login
                            </a>

                            <a
                                href="{{ route('register') }}"
                                class="flex-1 rounded-full bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700"
                            >
                                Register
                            </a>

                        @else

                            <a
                                href="{{ route('dashboard') }}"
                                class="flex-1 rounded-full bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700"
                            >
                                My Account
                            </a>

                        @endguest

                    </div>

                </div>

            </nav>


            {{-- ===================== DESKTOP AUTH ===================== --}}
            <div class="hidden items-center gap-3 md:flex">

                @guest

                    <a
                        href="{{ route('login') }}"
                        class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:text-blue-600"
                    >
                        Login
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700"
                    >
                        Register
                    </a>

                @else

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700"
                    >
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

    const utilityBar = document.getElementById('topUtilityBar');
    const navbarInner = document.getElementById('navbarInner');
    const navbarLogo = document.getElementById('navbarLogo');
    const navbarBrand = document.getElementById('navbarBrand');
    const navbarSubtitle = document.getElementById('navbarSubtitle');

    /*
    |--------------------------------------------------------------------------
    | Mobile Menu
    |--------------------------------------------------------------------------
    */

    navToggle?.addEventListener('click', function () {
        navMenu.classList.toggle('hidden');
    });


    /*
    |--------------------------------------------------------------------------
    | Sticky Navbar On Scroll
    |--------------------------------------------------------------------------
    */

    let lastScroll = 0;

    function handleNavbarScroll() {

        const currentScroll = window.scrollY;

        if (currentScroll > 50) {

            // Hide top utility bar
            utilityBar.classList.remove(
                'max-h-20',
                'opacity-100'
            );

            utilityBar.classList.add(
                'max-h-0',
                'opacity-0'
            );


            // Make navbar slightly smaller (gentle shrink)
            navbarInner.classList.remove('py-4');

            navbarInner.classList.add('py-3');


            // Slightly smaller logo
            navbarLogo.classList.remove('h-10','w-10');
            navbarLogo.classList.add('h-9','w-9');


            // Smaller brand text
            navbarBrand.classList.remove('text-lg');

            navbarBrand.classList.add('text-base');


            // Smaller subtitle
            navbarSubtitle.classList.add('hidden');

        } else {

            // Show top utility bar
            utilityBar.classList.remove(
                'max-h-0',
                'opacity-0'
            );

            utilityBar.classList.add(
                'max-h-20',
                'opacity-100'
            );


            // Restore navbar size
            navbarInner.classList.remove('py-3');

            navbarInner.classList.add('py-4');


            // Restore logo
            navbarLogo.classList.remove(
                'h-9',
                'w-9'
            );

            navbarLogo.classList.add(
                'h-10',
                'w-10'
            );


            // Restore brand
            navbarBrand.classList.remove('text-base');

            navbarBrand.classList.add('text-lg');


            // Restore subtitle
            navbarSubtitle.classList.remove('hidden');
        }

        lastScroll = currentScroll;
    }


    window.addEventListener(
        'scroll',
        handleNavbarScroll,
        { passive: true }
    );

});
</script>

</body>
</html>
