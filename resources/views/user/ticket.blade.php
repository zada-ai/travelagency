<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hajj & Umrah Flight Bookings | Premium Pilgrim Travel</title>
    <meta name="description" content="Compare and book Hajj & Umrah flights from Pakistan to Saudi Arabia. Best fares on Saudi Airlines, PIA, AirSial, and more.">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        },
                        gold: {
                            50: '#fdfbeb',
                            100: '#fcf6cd',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & AlpineJS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glassmorphism-dark {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-emerald-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- 1. STICKY HEADER -->
    <header class="sticky top-0 z-50 w-full transition-all duration-300 border-b border-slate-200/80 glassmorphism">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center shadow-md shadow-emerald-700/20">
                        <!-- Custom Kaaba/Islamic Geometric Icon -->
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-emerald-950 block leading-tight">LABBAIK</span>
                        <span class="text-xs font-semibold tracking-widest text-gold-600 block uppercase -mt-0.5">Air Travel</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-1">
                    <a href="#" class="px-4 py-2 rounded-lg text-sm font-semibold text-emerald-700 bg-emerald-50/50 transition-colors">Home</a>
                    <a href="#popular-routes" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors">Flight Tickets</a>
                    <a href="#airlines" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors">Airlines</a>
                    <a href="#deals" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors">Offers</a>
                    <a href="#why-us" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors">About Us</a>
                    <a href="#faq" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-slate-50 transition-colors">Contact</a>
                </nav>

                <!-- Right Actions -->
                <div class="hidden lg:flex items-center gap-4">
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-emerald-700 transition-colors">My Booking</a>
                    <div class="h-4 w-px bg-slate-200"></div>
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-emerald-700 transition-colors">Login</a>
                    <a href="#" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-emerald-700 to-emerald-600 text-white shadow-lg shadow-emerald-700/20 hover:shadow-emerald-700/30 hover:opacity-95 transform active:scale-95 transition-all">Book Ticket</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-700 hover:bg-slate-100 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                        <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="lg:hidden border-t border-slate-200 bg-white" id="mobile-menu" x-show="mobileMenuOpen" x-transition x-cloak>
            <div class="px-4 pt-3 pb-6 space-y-2">
                <a href="#" class="block px-4 py-2.5 rounded-xl text-base font-semibold bg-emerald-50 text-emerald-700">Home</a>
                <a href="#popular-routes" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Flight Tickets</a>
                <a href="#airlines" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Airlines</a>
                <a href="#deals" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Offers</a>
                <a href="#why-us" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">About Us</a>
                <a href="#faq" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Contact</a>
                <div class="h-px bg-slate-100 my-4"></div>
                <a href="#" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">My Booking</a>
                <a href="#" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Login / Register</a>
                <a href="#" class="block w-full text-center px-4 py-3 mt-4 rounded-xl text-base font-bold bg-emerald-600 text-white shadow-md">Book Ticket</a>
            </div>
        </div>
    </header>

    <!-- 2. HERO SECTION -->
    <section class="relative min-h-[90vh] flex items-center justify-center py-16 lg:py-24 overflow-hidden bg-slate-900">
        <!-- Full Width Background Image with Dark & Emerald Tint Overlays -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1920&q=80" alt="Pilgrims boarding plane" class="w-full h-full object-cover object-center opacity-45 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-emerald-950/65 to-slate-950/90"></div>
        </div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-14">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gold-500/15 border border-gold-500/30 text-gold-100 uppercase tracking-widest mb-4">
                    ⭐ Trusted Hajj & Umrah Travel Partner
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    Book Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-gold-400">Hajj & Umrah</span> Flight Tickets with Confidence
                </h1>
                <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
                    Find the best airfare from Pakistan to Saudi Arabia. Compare prices, choose airlines, and book online instantly.
                </p>
            </div>

            <!-- Booking Search Card Container -->
            <div class="max-w-6xl mx-auto" x-data="{ tripType: 'round-trip' }">
                <div class="rounded-3xl border border-white/10 p-5 sm:p-8 shadow-2xl glassmorphism-dark">
                    <form action="#" method="GET" class="space-y-6">
                        
                        <!-- Row 1: Trip Type Selection -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/10">
                            <div class="flex items-center bg-white/5 p-1 rounded-xl border border-white/10">
                                <button type="button" @click="tripType = 'one-way'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200" :class="tripType === 'one-way' ? 'bg-emerald-600 text-white shadow' : 'text-slate-300 hover:text-white'">One Way</button>
                                <button type="button" @click="tripType = 'round-trip'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200" :class="tripType === 'round-trip' ? 'bg-emerald-600 text-white shadow' : 'text-slate-300 hover:text-white'">Round Trip</button>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-xs text-slate-300 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Flexible Dates
                                </span>
                                <span class="text-xs text-slate-300 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span> Direct Flights Only
                                </span>
                            </div>
                        </div>

                        <!-- Row 2: Origin, Destination, Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Flying From -->
                            <div class="relative bg-white/5 border border-white/10 rounded-2xl p-4 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Flying From</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <select class="w-full bg-transparent text-white font-bold text-base focus:outline-none appearance-none cursor-pointer">
                                        <option value="ISB" class="bg-slate-900 text-white">Islamabad (ISB)</option>
                                        <option value="LHE" class="bg-slate-900 text-white">Lahore (LHE)</option>
                                        <option value="KHI" class="bg-slate-900 text-white">Karachi (KHI)</option>
                                        <option value="PEW" class="bg-slate-900 text-white">Peshawar (PEW)</option>
                                        <option value="UET" class="bg-slate-900 text-white">Quetta (UET)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Flying To -->
                            <div class="relative bg-white/5 border border-white/10 rounded-2xl p-4 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Flying To</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gold-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <select class="w-full bg-transparent text-white font-bold text-base focus:outline-none appearance-none cursor-pointer">
                                        <option value="JED" class="bg-slate-900 text-white">Jeddah (JED) - King Abdulaziz</option>
                                        <option value="MED" class="bg-slate-900 text-white">Madinah (MED) - Prince Mohammad</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Departure Date -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 focus-within:border-emerald-500 transition-all">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Departure Date</label>
                                <input type="date" class="w-full bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer [color-scheme:dark]">
                            </div>

                            <!-- Return Date -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 focus-within:border-emerald-500 transition-all" :class="tripType === 'one-way' ? 'opacity-40 pointer-events-none' : ''">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Return Date</label>
                                <input type="date" class="w-full bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer [color-scheme:dark]" :disabled="tripType === 'one-way'">
                            </div>
                        </div>

                        <!-- Row 3: Travelers, Class, Airline, Promo -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Passengers (Adults/Children) -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Passengers</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select class="bg-transparent text-white text-xs font-semibold focus:outline-none cursor-pointer">
                                        <option value="1" class="bg-slate-900">1 Adult</option>
                                        <option value="2" class="bg-slate-900">2 Adults</option>
                                        <option value="3" class="bg-slate-900">3 Adults</option>
                                        <option value="4" class="bg-slate-900">4+ Adults</option>
                                    </select>
                                    <select class="bg-transparent text-white text-xs font-semibold focus:outline-none cursor-pointer">
                                        <option value="0" class="bg-slate-900">0 Child</option>
                                        <option value="1" class="bg-slate-900">1 Child</option>
                                        <option value="2" class="bg-slate-900">2 Children</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Cabin Class -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Cabin Class</label>
                                <select class="w-full bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer">
                                    <option value="economy" class="bg-slate-900">Economy Class</option>
                                    <option value="premium" class="bg-slate-900">Premium Economy</option>
                                    <option value="business" class="bg-slate-900">Business Class</option>
                                    <option value="first" class="bg-slate-900">First Class</option>
                                </select>
                            </div>

                            <!-- Preferred Airline -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Preferred Airline</label>
                                <select class="w-full bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer">
                                    <option value="any" class="bg-slate-900">Any Airline</option>
                                    <option value="saudi" class="bg-slate-900">Saudi Airlines</option>
                                    <option value="pia" class="bg-slate-900">PIA</option>
                                    <option value="emirates" class="bg-slate-900">Emirates</option>
                                    <option value="airsial" class="bg-slate-900">AirSial</option>
                                </select>
                            </div>

                            <!-- Promo Code -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                                <label class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-1">Promo Code</label>
                                <input type="text" placeholder="UMRAH2026" class="w-full bg-transparent text-white font-bold text-sm placeholder-slate-500 focus:outline-none uppercase">
                            </div>
                        </div>

                        <!-- Row 4: Search Button -->
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-10 py-4.5 rounded-2xl font-extrabold text-base bg-gradient-to-r from-emerald-600 to-gold-600 hover:from-emerald-500 hover:to-gold-500 text-white shadow-xl shadow-emerald-950/40 transform hover:-translate-y-0.5 transition-all duration-150 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search Pilgrimage Flights
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. POPULAR ROUTES -->
    <section id="popular-routes" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full uppercase">Most Booked Routes</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">Popular Direct Flights from Pakistan</h2>
                <p class="mt-3 text-slate-500">Connecting major Pakistani hubs to Jeddah and Madinah at the lowest guaranteed rates.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Route Card 1 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">ISB</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">JED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Daily Flights</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Islamabad to Jeddah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 145,000 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 5h 45m (Direct)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">SV</div>
                            <span class="text-xs font-bold text-slate-600">Saudi Airlines</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>

                <!-- Route Card 2 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">LHE</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">JED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Daily Flights</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Lahore to Jeddah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 148,500 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 5h 55m (Direct)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">PK</div>
                            <span class="text-xs font-bold text-slate-600">PIA</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>

                <!-- Route Card 3 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">KHI</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">MED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Daily Flights</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Karachi to Madinah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 139,000 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 4h 30m (Direct)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">ER</div>
                            <span class="text-xs font-bold text-slate-600">AirSial</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>

                <!-- Route Card 4 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">ISB</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">MED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Weekly Flights</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Islamabad to Madinah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 149,000 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 5h 50m (Direct)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">XY</div>
                            <span class="text-xs font-bold text-slate-600">Flynas</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>

                <!-- Route Card 5 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">PEW</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">JED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Few Seats Left</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Peshawar to Jeddah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 152,000 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 6h 10m (Direct)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">EK</div>
                            <span class="text-xs font-bold text-slate-600">Emirates</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>

                <!-- Route Card 6 -->
                <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:border-emerald-200/60 transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-emerald-700">UET</div>
                            <span class="text-slate-400 font-semibold">➔</span>
                            <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center font-bold text-gold-700">MED</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Weekly Flights</span>
                    </div>
                    <div class="space-y-1 mb-6">
                        <p class="text-xs text-slate-400 font-medium">Quetta to Madinah</p>
                        <p class="text-2xl font-extrabold text-slate-900">PKR 155,000 <span class="text-xs font-normal text-slate-500">/ Return</span></p>
                        <p class="text-xs text-slate-500 flex items-center gap-1">⏱️ Avg Duration: 6h 30m (1 Stop)</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">FZ</div>
                            <span class="text-xs font-bold text-slate-600">Flyadeal</span>
                        </div>
                        <a href="#" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED AIRLINES -->
    <section id="airlines" class="py-20 lg:py-28 bg-slate-50 border-y border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-gold-700 bg-gold-50 border border-gold-200/60 px-3.5 py-1.5 rounded-full uppercase">Official Airline Partners</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">Fly with Trusted Global Carriers</h2>
                <p class="mt-3 text-slate-500">We partner with top-rated airlines offering direct and transit flights to Saudi Arabia.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Airline Card Template (Repeat for requested airlines) -->
                <!-- 1. Saudi Airlines -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">SV</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.8</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Saudi Airlines</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 135,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 2. AirSial -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">ER</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.5</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">AirSial</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 138,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 3. PIA -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">PK</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.2</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">PIA</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 140,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 4. Emirates -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">EK</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.9</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Emirates</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 172,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 5. Qatar Airways -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">QR</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.9</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Qatar Airways</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 175,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 6. Flynas -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">XY</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.3</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Flynas</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 129,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 7. Flyadeal -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">F3</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.1</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Flyadeal</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 125,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>

                <!-- 8. Etihad Airways -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 hover:shadow-lg hover:border-emerald-500/20 transition-all flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-extrabold text-sm text-slate-700">EY</div>
                        <div class="flex items-center gap-1">
                            <span class="text-amber-500">★</span>
                            <span class="text-xs font-bold text-slate-600">4.7</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Etihad Airways</h4>
                        <p class="text-xs text-slate-400">Starting from PKR 168,000</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">View Flights <span class="group-hover:translate-x-0.5 transition-transform">➔</span></a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. WHY BOOK WITH US -->
    <section id="why-us" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full uppercase">The Labbaik Advantage</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">Why Book Your Holy Journey With Us?</h2>
                <p class="mt-3 text-slate-500">Dedicated and tailored support for pilgrims, ensuring absolute peace of mind.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">🏷️</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Lowest Fare Guarantee</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">We compare real-time prices to bring you competitive ticket pricing for your spiritual journey.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">🔒</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Secure Online Payments</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Your financial security is our priority. Pay securely via Alfa, JazzCash, Visa, or Mastercard.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">📞</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">24/7 Customer Support</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">We support you in both Pakistan and Saudi Arabia. Reach out any time during your travel.</p>
                </div>

                <!-- Benefit 4 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">⚡</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Instant E-Ticket</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Receive instantly generated digital e-tickets via WhatsApp and Email right after booking.</p>
                </div>

                <!-- Benefit 5 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">🔄</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Easy Cancellation</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Change your mind or date? Simple web interface to request cancellations and speedy refunds.</p>
                </div>

                <!-- Benefit 6 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-100 hover:shadow-xl hover:bg-white hover:border-emerald-500/20 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-6">🤝</div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Trusted Travel Agency</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Hajj & Umrah Ministry registered partners facilitating over 50,000+ pilgrims annually.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FLIGHT DEALS -->
    <section id="deals" class="py-20 lg:py-28 bg-gradient-to-b from-slate-900 to-slate-950 text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-emerald-500 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-gold-400 bg-gold-400/15 border border-gold-400/20 px-3.5 py-1.5 rounded-full uppercase">Unmissable Flight Offers</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 leading-tight">Limited Time Pilgrim Flight Deals</h2>
                <p class="mt-3 text-slate-400">Exclusive discount tiers for individuals, groups, and families embarking on pilgrimage.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Deal Card 1 -->
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col justify-between hover:border-gold-500/30 transition duration-300">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-emerald-500 text-slate-950">Active Promo</span>
                            <span class="text-xs text-gold-400 font-bold">Save 15%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-white mb-2">Umrah Special Fare</h4>
                        <p class="text-xs text-slate-400 leading-relaxed mb-6">Designed specifically for seasonal Umrah travel on multiple partnering airlines.</p>
                    </div>
                    <div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5 text-center mb-6">
                            <span class="text-xs block text-slate-400">Offers Ends in:</span>
                            <span class="text-sm font-extrabold text-gold-400">02 Days : 14 Hours</span>
                        </div>
                        <a href="#" class="block w-full py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-center rounded-xl text-xs font-bold text-white hover:opacity-90 transition">Book Special Fare</a>
                    </div>
                </div>

                <!-- Deal Card 2 -->
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col justify-between hover:border-gold-500/30 transition duration-300">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-gold-500 text-slate-950">Early Bird</span>
                            <span class="text-xs text-gold-400 font-bold">Save 20%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-white mb-2">Early Bird Discount</h4>
                        <p class="text-xs text-slate-400 leading-relaxed mb-6">Book your Hajj 2026 flights early and secure the lowest absolute pricing brackets.</p>
                    </div>
                    <div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5 text-center mb-6">
                            <span class="text-xs block text-slate-400">Offers Ends in:</span>
                            <span class="text-sm font-extrabold text-gold-400">05 Days : 09 Hours</span>
                        </div>
                        <a href="#" class="block w-full py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-center rounded-xl text-xs font-bold text-white hover:opacity-90 transition">Secure Seat Now</a>
                    </div>
                </div>

                <!-- Deal Card 3 -->
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col justify-between hover:border-gold-500/30 transition duration-300">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-emerald-500 text-slate-950">Group Saver</span>
                            <span class="text-xs text-gold-400 font-bold">Flat PKR 10k Off</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-white mb-2">Family Discount</h4>
                        <p class="text-xs text-slate-400 leading-relaxed mb-6">Applicable to family groups of 4 or more travelers booking together.</p>
                    </div>
                    <div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5 text-center mb-6">
                            <span class="text-xs block text-slate-400">Offers Ends in:</span>
                            <span class="text-sm font-extrabold text-gold-400">01 Day : 22 Hours</span>
                        </div>
                        <a href="#" class="block w-full py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-center rounded-xl text-xs font-bold text-white hover:opacity-90 transition">Apply Family Deal</a>
                    </div>
                </div>

                <!-- Deal Card 4 -->
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col justify-between hover:border-gold-500/30 transition duration-300">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-blue-600 text-white">Student Fare</span>
                            <span class="text-xs text-gold-400 font-bold">Extra Luggage</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-white mb-2">Student Offer</h4>
                        <p class="text-xs text-slate-400 leading-relaxed mb-6">Specially organized student brackets traveling for Islamic higher studies.</p>
                    </div>
                    <div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/5 text-center mb-6">
                            <span class="text-xs block text-slate-400">Offers Ends in:</span>
                            <span class="text-sm font-extrabold text-gold-400">09 Days : 03 Hours</span>
                        </div>
                        <a href="#" class="block w-full py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-center rounded-xl text-xs font-bold text-white hover:opacity-90 transition">Claim Student Fare</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. BOOKING PROCESS -->
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full uppercase">Step-By-Step Journey</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">How It Works</h2>
                <p class="mt-3 text-slate-500">Book your pilgrimage flight tickets in under 5 minutes with our simplified process.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 relative">
                <!-- Connect Line (Desktop) -->
                <div class="hidden md:block absolute top-14 left-[10%] right-[10%] h-0.5 bg-slate-100 z-0"></div>

                <!-- Step 1 -->
                <div class="relative text-center z-10">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-600 text-emerald-800 font-extrabold text-xl flex items-center justify-center mx-auto mb-4">1</div>
                    <h4 class="font-extrabold text-slate-900 text-base mb-1">Search Flights</h4>
                    <p class="text-xs text-slate-500 max-w-[200px] mx-auto">Input your details, dates, and origin-destination parameters.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center z-10">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-600 text-emerald-800 font-extrabold text-xl flex items-center justify-center mx-auto mb-4">2</div>
                    <h4 class="font-extrabold text-slate-900 text-base mb-1">Select Flight</h4>
                    <p class="text-xs text-slate-500 max-w-[200px] mx-auto">Compare flight schedules, pricing, and specific carrier ratings.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center z-10">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-600 text-emerald-800 font-extrabold text-xl flex items-center justify-center mx-auto mb-4">3</div>
                    <h4 class="font-extrabold text-slate-900 text-base mb-1">Passenger Details</h4>
                    <p class="text-xs text-slate-500 max-w-[200px] mx-auto">Provide accurate passport details for visa compliance.</p>
                </div>

                <!-- Step 4 -->
                <div class="relative text-center z-10">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-600 text-emerald-800 font-extrabold text-xl flex items-center justify-center mx-auto mb-4">4</div>
                    <h4 class="font-extrabold text-slate-900 text-base mb-1">Pay Securely</h4>
                    <p class="text-xs text-slate-500 max-w-[200px] mx-auto">Complete transactions using trusted checkout payment tools.</p>
                </div>

                <!-- Step 5 -->
                <div class="relative text-center z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gold-50 border-2 border-gold-500 text-gold-700 font-extrabold text-xl flex items-center justify-center mx-auto mb-4">5</div>
                    <h4 class="font-extrabold text-slate-900 text-base mb-1">Receive E-Ticket</h4>
                    <p class="text-xs text-slate-500 max-w-[200px] mx-auto">Download digital documents instantly and embark securely.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. TESTIMONIALS -->
    <section class="py-20 lg:py-28 bg-slate-50 border-y border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold tracking-wider text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full uppercase">Heartwarming Stories</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">What Our Blessed Pilgrims Say</h2>
                <p class="mt-3 text-slate-500">Over 50,000 satisfied travelers have completed their journey with our platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review Card 1 -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-1 text-gold-500 text-sm mb-4">★★★★★</div>
                    <p class="text-sm text-slate-600 leading-relaxed italic mb-6">"Booking flights from Islamabad to Jeddah was incredibly simple. Customer support helped me change return dates easily when my Umrah visa extended."</p>
                    <div class="flex items-center gap-4">
                        <img class="w-11 h-11 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80" alt="Customer Photo">
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">Muhammad Fahad</h5>
                            <p class="text-[10px] text-slate-400">Islamabad, Pakistan</p>
                        </div>
                    </div>
                </div>

                <!-- Review Card 2 -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-1 text-gold-500 text-sm mb-4">★★★★★</div>
                    <p class="text-sm text-slate-600 leading-relaxed italic mb-6">"The best platform to track Saudi Airlines fares. Managed to secure direct flights at a massive discount for my family of four. Highly recommended!"</p>
                    <div class="flex items-center gap-4">
                        <img class="w-11 h-11 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80" alt="Customer Photo">
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">Amina Razzaq</h5>
                            <p class="text-[10px] text-slate-400">Lahore, Pakistan</p>
                        </div>
                    </div>
                </div>

                <!-- Review Card 3 -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center gap-1 text-gold-500 text-sm mb-4">★★★★★</div>
                    <p class="text-sm text-slate-600 leading-relaxed italic mb-6">"Excellent 24/7 client response. We faced a terminal issue in Karachi Airport, and Labbaik agents resolved everything online instantly. Absolutely brilliant service."</p>
                    <div class="flex items-center gap-4">
                        <img class="w-11 h-11 rounded-full object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80" alt="Customer Photo">
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">Zubair Khan</h5>
                            <p class="text-[10px] text-slate-400">Karachi, Pakistan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. STATISTICS -->
    <section class="py-16 bg-gradient-to-r from-emerald-950 to-emerald-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gold-400">50,000+</p>
                    <p class="text-xs uppercase tracking-widest text-emerald-100">Tickets Booked</p>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gold-400">15+</p>
                    <p class="text-xs uppercase tracking-widest text-emerald-100">Partner Airlines</p>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gold-400">100+</p>
                    <p class="text-xs uppercase tracking-widest text-emerald-100">Daily Flights</p>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gold-400">24/7</p>
                    <p class="text-xs uppercase tracking-widest text-emerald-100">Support Availability</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. FAQ SECTION -->
    <section id="faq" class="py-20 lg:py-28 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold tracking-wider text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-full uppercase font-sans">Frequently Asked Questions</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 leading-tight">Got Questions? We Have Answers</h2>
                <p class="mt-3 text-slate-500">Essential travel insights and ticketing information compiled for Hajj and Umrah pilgrims.</p>
            </div>

            <!-- Accordion using Alpine.js -->
            <div class="space-y-4" x-data="{ active: null }">
                
                <!-- Q1 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-200">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4.5 bg-slate-50 hover:bg-slate-100 flex items-center justify-between text-left text-sm sm:text-base font-bold text-slate-900 transition-colors">
                        <span>How early should I book my Hajj or Umrah flight ticket?</span>
                        <svg class="w-5 h-5 transition-transform duration-200" :class="active === 1 ? 'rotate-180 text-emerald-700' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="px-6 py-5 bg-white border-t border-slate-100 text-sm text-slate-600 leading-relaxed" x-show="active === 1" x-transition x-cloak>
                        We highly recommend booking at least 30 to 45 days in advance during normal Umrah periods, and 3 to 4 months in advance during peak Hajj seasons, to secure preferred dates and the best airline fares.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-200">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4.5 bg-slate-50 hover:bg-slate-100 flex items-center justify-between text-left text-sm sm:text-base font-bold text-slate-900 transition-colors">
                        <span>Can I cancel or modify my flight ticket dates online?</span>
                        <svg class="w-5 h-5 transition-transform duration-200" :class="active === 2 ? 'rotate-180 text-emerald-700' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="px-6 py-5 bg-white border-t border-slate-100 text-sm text-slate-600 leading-relaxed" x-show="active === 2" x-transition x-cloak>
                        Yes, date modifications and cancellation requests can be processed. Depending on your chosen airline tariff or class rules, airline change penalties and processing fees may apply.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-200">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4.5 bg-slate-50 hover:bg-slate-100 flex items-center justify-between text-left text-sm sm:text-base font-bold text-slate-900 transition-colors">
                        <span>Is special assistance for elderly or disabled passengers available?</span>
                        <svg class="w-5 h-5 transition-transform duration-200" :class="active === 3 ? 'rotate-180 text-emerald-700' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="px-6 py-5 bg-white border-t border-slate-100 text-sm text-slate-600 leading-relaxed" x-show="active === 3" x-transition x-cloak>
                        Absolutely. Wheelchair services, special in-flight meals, and priority terminal boarding can be set up. Make sure to specify requirements while inputting details or contact our support team.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-200">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4.5 bg-slate-50 hover:bg-slate-100 flex items-center justify-between text-left text-sm sm:text-base font-bold text-slate-900 transition-colors">
                        <span>What payment modes are accepted on Labbaik Air Travel?</span>
                        <svg class="w-5 h-5 transition-transform duration-200" :class="active === 4 ? 'rotate-180 text-emerald-700' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="px-6 py-5 bg-white border-t border-slate-100 text-sm text-slate-600 leading-relaxed" x-show="active === 4" x-transition x-cloak>
                        We support standard digital payments including direct bank transfers, credit/debit cards (Visa, Mastercard), mobile wallets (JazzCash, EasyPaisa), and physical cash collection at our registered local offices.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. CALL TO ACTION -->
    <section class="py-16 bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between p-8 sm:p-12 lg:p-16 rounded-3xl bg-white/5 border border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full bg-emerald-500 blur-3xl"></div>
                </div>

                <div class="relative z-10 max-w-2xl">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">Need Help Booking Your Ticket?</h3>
                    <p class="mt-3 text-slate-300 text-sm sm:text-base">Our experienced customer representatives are ready to assist you. Get customized flight options and manual reservations immediately.</p>
                </div>

                <div class="relative z-10 mt-8 lg:mt-0 flex flex-wrap gap-4 shrink-0">
                    <a href="https://wa.me/xxxxxxxxxxxx" class="px-6 py-3.5 rounded-xl text-sm font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md flex items-center gap-2 transition-all">
                        💬 Chat on WhatsApp
                    </a>
                    <a href="tel:xxxxxxxxxxx" class="px-6 py-3.5 rounded-xl text-sm font-bold bg-white/10 text-white hover:bg-white/15 border border-white/20 flex items-center gap-2 transition-all">
                        📞 Call Now
                    </a>
                    <a href="#" class="px-6 py-3.5 rounded-xl text-sm font-bold bg-gold-600 text-slate-950 hover:bg-gold-500 shadow-md transition-all">
                        Book Flight
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. PREMIUM FOOTER -->
    <footer class="bg-slate-950 text-slate-300 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 mb-12">
                
                <!-- Col 1: About & Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center shadow-md shadow-emerald-950/40">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-white block leading-tight">LABBAIK</span>
                            <span class="text-xs font-semibold tracking-widest text-gold-500 block uppercase -mt-0.5">Air Travel</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">Labbaik Air Travel is Pakistan's premium online travel and ticketing resource specializing in direct and customized Hajj and Umrah flight packages.</p>
                    <div class="flex items-center gap-3">
                        <!-- Social Icons -->
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <span class="font-bold text-sm">fb</span>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <span class="font-bold text-sm">ig</span>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <span class="font-bold text-sm">yt</span>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Featured Airlines -->
                <div>
                    <h5 class="text-white text-sm font-extrabold tracking-wider uppercase mb-5">Airlines</h5>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Saudi Airlines</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">AirSial</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Pakistan International Airlines</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Emirates</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Flynas</a></li>
                    </ul>
                </div>

                <!-- Col 3: Popular Routes -->
                <div>
                    <h5 class="text-white text-sm font-extrabold tracking-wider uppercase mb-5">Popular Routes</h5>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Islamabad ➔ Jeddah</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Lahore ➔ Jeddah</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Karachi ➔ Madinah</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Peshawar ➔ Jeddah</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Quetta ➔ Madinah</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Legal -->
                <div>
                    <h5 class="text-white text-sm font-extrabold tracking-wider uppercase mb-5">Quick Help</h5>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Contact Support</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Terms & Conditions</a></li>
                        <li><span class="text-slate-500 block">Islamabad, Pakistan</span></li>
                        <li><span class="text-slate-500 block">info@labbaiktravel.com</span></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; 2026 Labbaik Air Travel. All Rights Reserved. Designed with spiritual care.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:underline">Sitemap</a>
                    <a href="#" class="hover:underline">Legal Notice</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>