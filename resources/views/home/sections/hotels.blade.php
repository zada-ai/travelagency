@php
    $featuredHotelTitle = 'Premium Hotels for Your Umrah Stay';
    $featuredHotelSubtitle = 'Explore top-rated hotels with comfortable rooms and easy access to the Haram.';
@endphp

<section id="hotels" class="relative overflow-hidden bg-white py-20">
    <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-100/60 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-12 h-72 w-72 rounded-full bg-emerald-100/60 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-blue-600">Hotels</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $featuredHotelTitle }}</h2>
                <p class="mt-3 max-w-2xl text-slate-600">{{ $featuredHotelSubtitle }}</p>
            </div>
            <a href="{{ route('hotels.booking') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">
                View All Hotels
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-6 lg:grid-cols-3">
            @forelse($featuredHotels as $hotel)
                @php
                    $coverUrl = $hotel->cover_image_url ?? asset('images/hotel-placeholder.jpg');
                    $price = optional($hotel->roomTypes->sortBy('daily_rate')->first())->daily_rate ?? 0;
                    $distance = $hotel->distance_from_haram ? number_format($hotel->distance_from_haram, 0) : 'N/A';
                    $categoryLabel = $hotel->category ?: 'Standard';
                @endphp

                <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/50 transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="relative h-56 overflow-hidden bg-slate-100">
                        <img src="{{ $coverUrl }}" alt="{{ $hotel->hotel_name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                        <div class="absolute left-6 bottom-6 rounded-full bg-blue-600 px-4 py-2 text-xs font-bold uppercase tracking-[0.24em] text-white shadow-lg shadow-blue-600/20">
                            {{ $categoryLabel }}
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">{{ $hotel->hotel_name }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $hotel->city }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                                {{ $hotel->featured ? 'Featured' : 'Verified' }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl bg-blue-50 p-4">
                                <p class="text-[10px] uppercase tracking-[0.24em] text-blue-600">Distance</p>
                                <p class="mt-2 text-sm font-bold text-slate-900">{{ $distance }} m from Haram</p>
                            </div>
                            <div class="rounded-3xl bg-emerald-50 p-4">
                                <p class="text-[10px] uppercase tracking-[0.24em] text-emerald-600">Rooms</p>
                                <p class="mt-2 text-sm font-bold text-slate-900">{{ $hotel->roomTypes->count() }} room types</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400">Starting from</p>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-2xl font-black text-slate-900">SAR {{ number_format($price, 2) }}</span>
                                <span class="text-xs font-semibold text-slate-500">/ night</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3">
                            @php
                                $hotelButtonUrl = auth()->check() || auth()->guard('travel_agent')->check()
                                    ? route('hotels.details', $hotel)
                                    : route('login');
                            @endphp

                            <a href="{{ $hotelButtonUrl }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">
                                Book Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center lg:col-span-3">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <i class="bi bi-building text-3xl"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-black text-slate-900">No hotel highlights available</h3>
                    <p class="mt-2 text-sm text-slate-500">Please check back soon for the latest available hotels.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
