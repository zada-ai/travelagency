@php
    $featuredFlightsTitle = 'Find Your Perfect Flight';
    $featuredFlightsSubtitle = 'Explore available flights for your Umrah journey and choose the option that suits you best.';
@endphp

<section id="flights" class="relative overflow-hidden bg-gradient-to-b from-white via-blue-50/40 to-emerald-50/30 py-20">

    {{-- Decorative Background --}}
    <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full bg-blue-100/50 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 bottom-10 h-72 w-72 rounded-full bg-emerald-100/50 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="reveal flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    Flight Deals
                </div>

                <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    {{ $featuredFlightsTitle }}
                </h2>

                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                    {{ $featuredFlightsSubtitle }}
                </p>
            </div>

            <a href="{{ route('tickets.index') }}"
               class="group inline-flex items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 md:self-auto">
                View All Flights
                <i class="bi bi-arrow-right transition group-hover:translate-x-1"></i>
            </a>

        </div>


        {{-- Flights --}}
        <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

            @forelse($featuredFlights as $ticket)

                @php
                    $economyPrice = $ticket->getCabinPrice('Economy');
                    $premiumPrice = $ticket->getCabinPrice('Premium Economy');
                    $businessPrice = $ticket->getCabinPrice('Business');
                    $firstPrice = $ticket->getCabinPrice('First');

                    $bestPrice = $economyPrice ?? $ticket->adult_price ?? $ticket->price;

                    $departureCode = $ticket->departureAirport?->code ?? '---';
                    $arrivalCode = $ticket->arrivalAirport?->code ?? '---';

                    $departureCity = $ticket->departureAirport?->city ?? 'Departure';
                    $arrivalCity = $ticket->arrivalAirport?->city ?? 'Arrival';

                    $routeLabel = $ticket->route ?: trim($departureCity . ' - ' . $arrivalCity);
                @endphp


                {{-- Flight Card --}}
                <article class="group relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/50 transition-transform duration-300 hover:-translate-y-2 hover:shadow-2xl">

                    {{-- Top Accent --}}
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500"></div>


                    {{-- Airline Header --}}
                    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                                <i class="bi bi-airplane-fill text-lg"></i>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">
                                    Airline
                                </p>

                                <p class="mt-1 text-xl font-black text-slate-900">
                                    {{ $ticket->airline }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Flight {{ $ticket->flight_number }}
                                </p>
                            </div>

                        </div>


                        {{-- Ticket Type --}}
                        <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-blue-700">
                            {{ $ticket->ticket_type }}
                        </span>

                    </div>


                    <div class="p-6">

                        {{-- Route --}}
                        <div class="relative flex items-center justify-between">

                            {{-- Departure --}}
                            <div class="w-[32%]">

                                <p class="text-3xl font-black tracking-tight text-slate-900">
                                    {{ $departureCode }}
                                </p>

                                <p class="mt-1 truncate text-xs font-medium text-slate-500">
                                    {{ $departureCity }}
                                </p>

                                <div class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1.5">
                                    <i class="bi bi-clock text-blue-600"></i>

                                    <span class="text-xs font-bold text-blue-700">
                                        {{ $ticket->departure_time ?? '--:--' }}
                                    </span>
                                </div>

                            </div>


                            {{-- Flight Path --}}
                            <div class="flex flex-1 flex-col items-center px-3">

                                <span class="mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    {{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}
                                </span>

                                <div class="flex w-full items-center">

                                    <div class="h-2 w-2 rounded-full bg-blue-600"></div>

                                    <div class="relative h-px flex-1 bg-gradient-to-r from-blue-500 to-emerald-500">

                                        <div class="absolute left-1/2 top-1/2 flex h-8 w-8 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border-4 border-white bg-gradient-to-r from-blue-600 to-emerald-500 text-white shadow-md">
                                            <i class="bi bi-airplane-fill text-xs"></i>
                                        </div>

                                    </div>

                                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>

                                </div>

                                <span class="mt-3 max-w-full truncate text-center text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                    {{ $routeLabel }}
                                </span>

                            </div>


                            {{-- Arrival --}}
                            <div class="w-[32%] text-right">

                                <p class="text-3xl font-black tracking-tight text-slate-900">
                                    {{ $arrivalCode }}
                                </p>

                                <p class="mt-1 truncate text-xs font-medium text-slate-500">
                                    {{ $arrivalCity }}
                                </p>

                                <div class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1.5">
                                    <i class="bi bi-clock text-emerald-700"></i>

                                    <span class="text-xs font-bold text-emerald-700">
                                        {{ $ticket->arrival_time ?? '--:--' }}
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- Divider --}}
                        <div class="my-6 border-t border-dashed border-slate-200"></div>


                        {{-- Flight Information --}}
                        <div class="grid grid-cols-2 gap-3">

                            <div class="rounded-2xl bg-slate-50 p-4 transition group-hover:bg-blue-50/60">

                                <div class="flex items-center gap-2">

                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <i class="bi bi-person-check"></i>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            Seats
                                        </p>

                                        <p class="mt-0.5 text-sm font-extrabold text-slate-900">
                                            {{ $ticket->available_seats }}
                                        </p>
                                    </div>

                                </div>

                            </div>


                            <div class="rounded-2xl bg-slate-50 p-4 transition group-hover:bg-emerald-50/60">

                                <div class="flex items-center gap-2">

                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                        <i class="bi bi-calendar3"></i>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            Date
                                        </p>

                                        <p class="mt-0.5 text-sm font-extrabold text-slate-900">
                                            {{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Price --}}
                        <div class="mt-4 overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-slate-50 to-emerald-50 p-5">

                            <div class="flex items-end justify-between gap-4">

                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                                        Starting From
                                    </p>

                                    <div class="mt-1 flex items-baseline gap-1">

                                        <span class="text-xs font-bold text-slate-500">
                                            SAR
                                        </span>

                                        <span class="text-2xl font-black text-slate-900">
                                            {{ number_format($bestPrice, 2) }}
                                        </span>

                                    </div>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Per passenger · Economy
                                    </p>
                                </div>


                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-emerald-600 shadow-sm">
                                    <i class="bi bi-tag-fill"></i>
                                </div>

                            </div>

                        </div>


                        {{-- Cabin Prices --}}
                        @if($economyPrice !== null || $businessPrice !== null)

                            <div class="mt-4 grid grid-cols-2 gap-2">

                                @if($economyPrice !== null)
                                    <div class="rounded-xl border border-slate-100 bg-white px-3 py-2.5">

                                        <div class="flex items-center justify-between">

                                            <span class="text-xs text-slate-500">
                                                Economy
                                            </span>

                                            <span class="text-xs font-extrabold text-blue-600">
                                                SAR {{ number_format($economyPrice, 0) }}
                                            </span>

                                        </div>

                                    </div>
                                @endif


                                @if($businessPrice !== null)
                                    <div class="rounded-xl border border-slate-100 bg-white px-3 py-2.5">

                                        <div class="flex items-center justify-between">

                                            <span class="text-xs text-slate-500">
                                                Business
                                            </span>

                                            <span class="text-xs font-extrabold text-emerald-600">
                                                SAR {{ number_format($businessPrice, 0) }}
                                            </span>

                                        </div>

                                    </div>
                                @endif

                            </div>

                        @endif


                        {{-- Buttons --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">

                            {{-- <a href="{{ route('ticket.details', $ticket) }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-3 text-sm font-bold text-blue-600 transition hover:bg-blue-50">

                                <i class="bi bi-eye"></i>
                                Details

                            </a> --}}

                            @php
                                $bookNowUrl = auth()->check() || auth()->guard('travel_agent')->check()
                                    ? route('ticket.details', $ticket)
                                    : route('login');
                            @endphp

                            <a href="{{ $bookNowUrl }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">

                                Book Now
                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    </div>

                </article>

            @empty

                {{-- Empty State --}}
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center md:col-span-2 xl:col-span-3">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="bi bi-airplane text-3xl"></i>
                    </div>

                    <h3 class="mt-5 text-xl font-black text-slate-900">
                        No Featured Flights Available
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Check back soon for the latest flight availability and special Umrah travel deals.
                    </p>

                    <a href="{{ route('tickets.index') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">

                        Explore Flights
                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            @endforelse

        </div>

    </div>
</section>