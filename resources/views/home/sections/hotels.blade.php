@php
    $featuredHotelTitle = 'Premium Hotels for Your Umrah Stay';
    $featuredHotelSubtitle = 'Explore top-rated hotels with comfortable rooms and easy access to the Haram.';
@endphp

<section id="hotels" class="relative overflow-hidden bg-white py-12 sm:py-16 lg:py-20">
    {{-- Background Decorations --}}
    <div class="pointer-events-none absolute -left-32 top-10 h-56 w-56 rounded-full bg-blue-100/60 blur-3xl sm:h-72 sm:w-72"></div>
    <div class="pointer-events-none absolute -right-32 bottom-10 h-56 w-56 rounded-full bg-emerald-100/60 blur-3xl sm:h-72 sm:w-72"></div>

    <div class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="reveal flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div class="max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 sm:text-sm sm:tracking-[0.25em]">
                    Hotels
                </p>

                <h2 class="mt-2 text-2xl font-black leading-tight tracking-tight text-slate-900 sm:text-3xl md:text-4xl">
                    {{ $featuredHotelTitle }}
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base sm:leading-7">
                    {{ $featuredHotelSubtitle }}
                </p>
            </div>

            {{-- View All Hotels --}}
            <div class="w-full sm:w-auto">
                <a href="{{ route('login') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition duration-300 hover:bg-blue-700 sm:w-auto">
                    View All Hotels
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Hotels Grid --}}
        <div class="mt-8 grid grid-cols-1 gap-5 sm:mt-10 sm:gap-6 md:grid-cols-2 lg:mt-12 lg:grid-cols-3">

            @forelse($featuredHotels as $hotel)

                @php
                    $coverUrl = $hotel->cover_image_url ?? asset('images/hotel-placeholder.jpg');

                    $price = optional(
                        $hotel->roomTypes->sortBy('daily_rate')->first()
                    )->daily_rate ?? 0;

                    $distance = $hotel->distance_from_haram
                        ? number_format($hotel->distance_from_haram, 0)
                        : 'N/A';

                    $categoryLabel = $hotel->category ?: 'Standard';

                    $hotelButtonUrl = auth()->check() || auth()->guard('travel_agent')->check()
                        ? route('hotels.details', $hotel)
                        : route('login');
                @endphp

                {{-- Hotel Card --}}
                <article
                    class="group flex h-full flex-col overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/50 transition duration-300 hover:-translate-y-1 hover:shadow-2xl sm:rounded-[2rem]">

                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden bg-slate-100 sm:h-56">
                        <img
                            src="{{ $coverUrl }}"
                            alt="{{ $hotel->hotel_name }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                        {{-- Category --}}
                        <div class="absolute bottom-4 left-4 rounded-full bg-blue-600 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em] text-white shadow-lg shadow-blue-600/20 sm:bottom-6 sm:left-6 sm:px-4 sm:py-2 sm:text-xs sm:tracking-[0.24em]">
                            {{ $categoryLabel }}
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="flex flex-1 flex-col p-4 sm:p-6">

                        {{-- Hotel Name --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-black text-slate-900 sm:text-xl">
                                    {{ $hotel->hotel_name }}
                                </h3>

                                <p class="mt-1.5 flex items-center gap-1 text-sm text-slate-500 sm:mt-2">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="truncate">{{ $hotel->city }}</span>
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-emerald-700 sm:px-3 sm:py-1 sm:text-[11px]">
                                {{ $hotel->featured ? 'Featured' : 'Verified' }}
                            </span>

                        </div>

                        {{-- Distance + Rooms --}}
                        <div class="mt-5 grid grid-cols-1 gap-3 min-[420px]:grid-cols-2 sm:mt-6">

                            <div class="rounded-2xl bg-blue-50 p-3.5 sm:rounded-3xl sm:p-4">
                                <p class="text-[9px] uppercase tracking-[0.2em] text-blue-600 sm:text-[10px] sm:tracking-[0.24em]">
                                    Distance
                                </p>

                                <p class="mt-1.5 text-xs font-bold leading-5 text-slate-900 sm:mt-2 sm:text-sm">
                                    {{ $distance }} m from Haram
                                </p>
                            </div>

                            <div class="rounded-2xl bg-emerald-50 p-3.5 sm:rounded-3xl sm:p-4">
                                <p class="text-[9px] uppercase tracking-[0.2em] text-emerald-600 sm:text-[10px] sm:tracking-[0.24em]">
                                    Rooms
                                </p>

                                <p class="mt-1.5 text-xs font-bold leading-5 text-slate-900 sm:mt-2 sm:text-sm">
                                    {{ $hotel->roomTypes->count() }} room types
                                </p>
                            </div>

                        </div>

                        {{-- Price --}}
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:mt-6 sm:rounded-3xl sm:p-5">

                            <p class="text-[9px] uppercase tracking-[0.18em] text-slate-400 sm:text-[10px] sm:tracking-[0.2em]">
                                Starting from
                            </p>

                            <div class="mt-1.5 flex flex-wrap items-baseline gap-x-2 gap-y-1 sm:mt-2">

                                <span class="text-xl font-black text-slate-900 sm:text-2xl">
                                    SAR {{ number_format($price, 2) }}
                                </span>

                                <span class="text-[11px] font-semibold text-slate-500 sm:text-xs">
                                    / night
                                </span>

                            </div>

                        </div>

                        {{-- Button --}}
                        <div class="mt-auto pt-5 sm:pt-6">

                            <a href="{{ $hotelButtonUrl }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition duration-300 hover:bg-blue-700 active:scale-[0.98] sm:rounded-2xl">
                                Book Now
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>
                </article>

            @empty

                {{-- Empty State --}}
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center sm:p-12 md:col-span-2 lg:col-span-3">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600 sm:h-16 sm:w-16">
                        <i class="bi bi-building text-2xl sm:text-3xl"></i>
                    </div>

                    <h3 class="mt-4 text-lg font-black text-slate-900 sm:mt-5 sm:text-xl">
                        No hotel highlights available
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Please check back soon for the latest available hotels.
                    </p>

                </div>

            @endforelse

        </div>
    </div>
</section>

