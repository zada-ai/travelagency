@extends('layouts.public')

@section('title', 'Hujjaj Umrah | Premium Umrah Packages')

@section('content')

{{-- =========================================================
ANIMATION STYLES (self-contained, no extra CSS framework needed)
========================================================= --}}
<style>
    @keyframes floatY {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-16px); }
    }
    @keyframes floatYSlow {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(1.5deg); }
    }
    @keyframes blobPulse {
        0%, 100% { transform: scale(1); opacity: .35; }
        50% { transform: scale(1.15); opacity: .55; }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .anim-float { animation: floatY 4.5s ease-in-out infinite; }
    .anim-float-slow { animation: floatYSlow 6s ease-in-out infinite; }
    .anim-blob { animation: blobPulse 6s ease-in-out infinite; }
    .anim-fade-up { animation: fadeInUp .8s ease-out both; }
    .anim-fade { animation: fadeIn 1s ease-out both; }

    .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s ease-out, transform .7s ease-out; }
    .reveal.is-visible { opacity: 1; transform: translateY(0); }
</style>

{{-- =========================================================
HERO SECTION
========================================================= --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-emerald-950 to-blue-950 text-white">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.30),_transparent_50%)]"></div>
        <div class="anim-blob absolute -left-24 -top-24 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="anim-blob absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-emerald-400/15 blur-3xl" style="animation-delay:1.5s"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid items-center gap-12 lg:grid-cols-2">

            {{-- LEFT: TEXT --}}
            <div class="max-w-2xl anim-fade-up">
                <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-emerald-100 backdrop-blur">
                    <i class="bi bi-stars mr-2 text-emerald-300"></i>
                    Your trusted Umrah &amp; Hajj travel partner
                </span>

                <h1 class="mt-6 text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                    Your Spiritual Journey
                    <span class="block bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">Begins Here</span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                    Discover carefully designed Umrah packages, comfortable hotels, reliable flights
                    and complete travel assistance for your spiritual journey.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('packages.index') }}"
                       class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-900/30 transition hover:-translate-y-0.5 hover:bg-emerald-500">
                        <i class="bi bi-compass mr-2"></i>
                        Explore Umrah Packages
                    </a>
                    <a href="#contact-cta"
                       class="inline-flex items-center rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-sm font-bold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                        <i class="bi bi-telephone mr-2"></i>
                        Contact Us
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap gap-x-8 gap-y-4 text-sm text-slate-300">
                    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-emerald-400"></i> Trusted Service</div>
                    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-blue-400"></i> Complete Assistance</div>
                    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-emerald-400"></i> Easy Booking</div>
                </div>
            </div>

            {{-- RIGHT: ANIMATED IMAGE COLLAGE (2-3 images) --}}
            <div class="relative h-[420px] sm:h-[480px] anim-fade">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="h-72 w-72 rounded-full bg-gradient-to-br from-blue-500/20 to-emerald-500/20 blur-3xl"></div>
                </div>

                {{-- main large image --}}
                <div class="anim-float absolute left-4 top-6 w-[68%] overflow-hidden rounded-3xl border border-white/15 bg-gradient-to-br from-blue-800 via-emerald-900 to-slate-900 shadow-2xl shadow-black/40 sm:left-8 sm:top-8">
                    <img src="{{ asset('images/images.jpg') }}" alt="Kaaba, Makkah"
                         class="h-64 w-full object-cover sm:h-80"
                         onerror="this.remove();">
                </div>

                {{-- top-right floating image --}}
                <div class="anim-float-slow absolute right-2 top-0 w-[42%] overflow-hidden rounded-2xl border border-white/15 bg-gradient-to-br from-emerald-700 to-slate-900 shadow-xl shadow-black/40 sm:right-0"
                     style="animation-delay:.6s">
                    <img src="{{ asset('images/Transport.png') }}" alt="Madinah"
                         class="h-32 w-full object-cover sm:h-40"
                         onerror="this.remove();">
                </div>

                {{-- bottom-right floating image --}}
                <div class="anim-float-slow absolute bottom-4 right-0 w-[46%] overflow-hidden rounded-2xl border border-white/15 bg-gradient-to-br from-blue-700 to-emerald-950 shadow-xl shadow-black/40 sm:bottom-8"
                     style="animation-delay:1.2s">
                    <img src="{{ asset('images/hero-hotel.jpg') }}" alt="Premium hotel"
                         class="h-36 w-full object-cover sm:h-44"
                         onerror="this.remove();">
                </div>

                {{-- floating info badge --}}
                <div class="anim-float absolute bottom-2 left-2 rounded-2xl border border-white/15 bg-white p-4 text-slate-900 shadow-2xl sm:left-6"
                     style="animation-delay:.3s">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-emerald-50 text-emerald-600">
                            <i class="bi bi-moon-stars text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Featured Route</p>
                            <p class="text-sm font-bold text-slate-900">Makkah &amp; Madinah</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- =========================================================
QUICK SERVICES
========================================================= --}}
<section class="relative z-10 -mt-10 px-4">
    <div class="mx-auto max-w-6xl reveal">
        <div class="grid overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('packages.index') }}"
               class="group flex items-center gap-4 border-b border-slate-100 p-5 transition hover:bg-blue-50 lg:border-b-0 lg:border-r">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white">
                    <i class="bi bi-box-seam text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Umrah Packages</p>
                    <p class="text-xs text-slate-500">Explore packages</p>
                </div>
            </a>

            <a href="#services"
               class="group flex items-center gap-4 border-b border-slate-100 p-5 transition hover:bg-emerald-50 lg:border-b-0 lg:border-r">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                    <i class="bi bi-building text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Hotels</p>
                    <p class="text-xs text-slate-500">Comfortable stays</p>
                </div>
            </a>

            <a href="#services"
               class="group flex items-center gap-4 border-b border-slate-100 p-5 transition hover:bg-blue-50 sm:border-b-0 lg:border-r">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white">
                    <i class="bi bi-airplane text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Flights</p>
                    <p class="text-xs text-slate-500">Travel with ease</p>
                </div>
            </a>

            <a href="#services"
               class="group flex items-center gap-4 p-5 transition hover:bg-emerald-50">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white">
                    <i class="bi bi-passport text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Visa Assistance</p>
                    <p class="text-xs text-slate-500">Complete support</p>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- =========================================================
FEATURED PACKAGES (from database, no fake data)
========================================================= --}}
<section id="packages" class="bg-slate-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-600">Our Packages</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Choose Your Umrah Journey</h2>
                <p class="mt-3 max-w-2xl text-slate-600">
                    Explore our available Umrah packages designed around comfort, convenience and
                    dependable travel arrangements.
                </p>
            </div>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center text-sm font-bold text-blue-600 transition hover:text-emerald-600">
                View All Packages
                <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($featuredPackages ?? [] as $pkg)
                <article class="reveal group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="relative flex h-48 items-end overflow-hidden bg-gradient-to-br from-blue-900 via-emerald-900 to-slate-900 p-5">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.20),_transparent_50%)]"></div>
                        <span class="absolute left-5 top-5 rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-white backdrop-blur">
                            {{ $pkg->badge ?? 'Featured' }}
                        </span>
                        <div class="relative">
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Umrah Package</p>
                            <h3 class="mt-1 text-xl font-black text-white">{{ $pkg->title ?? $pkg->name }}</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <i class="bi bi-calendar3 text-emerald-600"></i>
                            {{ $pkg->duration ?? 'Flexible Duration' }}
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-blue-50/60 p-3">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400">Makkah</p>
                                <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $pkg->makkah_hotel ?? 'Premium Hotel' }}</p>
                            </div>
                            <div class="rounded-xl bg-emerald-50/60 p-3">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400">Madinah</p>
                                <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $pkg->madinah_hotel ?? 'Premium Hotel' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-end justify-between border-t border-slate-100 pt-5">
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-slate-400">Starting From</p>
                                <p class="mt-1 text-xl font-black text-slate-900">
                                    Rs {{ number_format($pkg->price ?? 0, 0) }}
                                </p>
                            </div>
                            <a href="{{ route('packages.book', $pkg->id) }}"
                               class="rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:from-blue-700 hover:to-emerald-700">
                                Book Now
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="reveal rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center md:col-span-2 lg:col-span-3">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-50 to-emerald-50 text-emerald-600">
                        <i class="bi bi-box-seam text-2xl"></i>
                    </div>
                    <h3 class="mt-4 font-bold text-slate-900">Packages Coming Soon</h3>
                    <p class="mt-2 text-sm text-slate-500">Published Umrah packages will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- =========================================================
WHY CHOOSE US
========================================================= --}}
<section id="services" class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-blue-600">Why Choose Us</p>
            <h2 class="mt-2 text-3xl font-black text-slate-900 sm:text-4xl">Everything You Need For Your Journey</h2>
            <p class="mt-4 text-slate-600">
                From planning to departure, our services are designed to make your Umrah experience
                simple and comfortable.
            </p>
        </div>

        @php
            $services = [
                ['icon' => 'bi-passport', 'title' => 'Visa Assistance', 'desc' => 'Guidance and support throughout your visa process and documentation.', 'color' => 'blue'],
                ['icon' => 'bi-building', 'title' => 'Quality Hotels', 'desc' => 'Choose comfortable accommodation options in Makkah and Madinah.', 'color' => 'emerald'],
                ['icon' => 'bi-airplane', 'title' => 'Flight Support', 'desc' => 'Reliable flight options and booking assistance for your journey.', 'color' => 'blue'],
                ['icon' => 'bi-bus-front', 'title' => 'Transport', 'desc' => 'Convenient transportation arrangements for a smoother pilgrimage.', 'color' => 'emerald'],
                ['icon' => 'bi-headset', 'title' => 'Dedicated Support', 'desc' => 'Get assistance whenever you need help with your travel arrangements.', 'color' => 'blue'],
                ['icon' => 'bi-shield-check', 'title' => 'Trusted Service', 'desc' => 'Transparent and organized services designed around your peace of mind.', 'color' => 'emerald'],
            ];
        @endphp

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <div class="reveal group rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-{{ $service['color'] }}-200 hover:shadow-lg">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $service['color'] }}-50 text-{{ $service['color'] }}-600 transition group-hover:scale-110 group-hover:bg-{{ $service['color'] }}-600 group-hover:text-white">
                        <i class="bi {{ $service['icon'] }} text-2xl"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $service['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================
HOW IT WORKS
========================================================= --}}
<section id="about" class="bg-slate-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <div class="reveal">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-600">Simple Process</p>
                <h2 class="mt-3 text-3xl font-black text-slate-900 sm:text-4xl">Plan Your Journey In Three Simple Steps</h2>
                <p class="mt-5 leading-7 text-slate-600">
                    We make the booking process straightforward so you can focus on your spiritual journey.
                </p>
                <a href="{{ route('packages.index') }}"
                   class="mt-7 inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-emerald-600">
                    Start Exploring
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="space-y-4">
                <div class="reveal flex gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white">01</div>
                    <div>
                        <h3 class="font-bold text-slate-900">Choose Your Package</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Browse our available Umrah packages and select the option that best matches your travel requirements.</p>
                    </div>
                </div>

                <div class="reveal flex gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-sm font-black text-white">02</div>
                    <div>
                        <h3 class="font-bold text-slate-900">Provide Traveller Details</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Complete your booking details and provide the required information for your journey.</p>
                    </div>
                </div>

                <div class="reveal flex gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-black text-white">03</div>
                    <div>
                        <h3 class="font-bold text-slate-900">Travel With Confidence</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Receive clear booking information and support throughout your travel journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
CONTACT CTA (visual only - no fake POST endpoint)
========================================================= --}}
<section id="contact-cta" class="bg-white py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="reveal overflow-hidden rounded-3xl border border-emerald-100 bg-gradient-to-br from-slate-950 via-blue-950 to-emerald-950 p-8 text-white shadow-xl sm:p-12">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-300">Get In Touch</p>
                    <h2 class="mt-2 text-2xl font-black sm:text-3xl">Have Questions About Your Journey?</h2>
                    <p class="mt-4 text-sm text-slate-300">Reach out for package details, hotels or visa assistance.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 text-slate-900 shadow-lg">
                    <div class="space-y-3">
                        <a href="tel:+923163334590" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-sm font-semibold text-slate-800 transition hover:border-blue-300 hover:bg-blue-50">
                            <i class="bi bi-telephone-fill text-blue-600"></i> +92 316 3334590
                        </a>
                        <a href="mailto:info@umrahbooking.pk" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-sm font-semibold text-slate-800 transition hover:border-emerald-300 hover:bg-emerald-50">
                            <i class="bi bi-envelope-fill text-emerald-600"></i> info@umrahbooking.pk
                        </a>
                        <a href="{{ route('packages.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 p-3 text-sm font-bold text-white transition hover:from-blue-700 hover:to-emerald-700">
                            Explore Packages <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
FINAL CTA
========================================================= --}}
<section class="relative overflow-hidden bg-gradient-to-r from-blue-700 to-emerald-700 py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.15),_transparent_45%)]"></div>
    <div class="relative mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
        <div class="anim-float mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 backdrop-blur">
            <i class="bi bi-moon-stars text-3xl"></i>
        </div>
        <h2 class="mt-6 text-3xl font-black sm:text-4xl">Ready To Begin Your Umrah Journey?</h2>
        <p class="mx-auto mt-4 max-w-2xl text-blue-50">
            Explore our available packages and take the first step towards a peaceful and memorable
            pilgrimage.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('packages.index') }}"
               class="rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-blue-700 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                Explore Packages
            </a>
            <a href="#services"
               class="rounded-xl border border-white/30 bg-white/10 px-6 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-white/15">
                Our Services
            </a>
        </div>
    </div>
</section>

{{-- =========================================================
SCROLL-REVEAL SCRIPT (vanilla JS, no extra dependency)
========================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var revealEls = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            revealEls.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach(function (el) { observer.observe(el); });
    });
</script>

@endsection