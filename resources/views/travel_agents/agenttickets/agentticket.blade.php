<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Ticket Dashboard | Travel Agent Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold">Hujaj umrah</h2>
                    <p class="text-slate-400 mt-2 text-sm">{{ $agent->company_name }}</p>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-950/10 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Agent ID</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ $agent->id ?? 'AGT-000' }}</p>
                </div>

                <nav class="space-y-2 text-sm">
                    <a href="{{ route('travel-agents.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Overview</a>
                    <a href="{{ route('travel-agents.hotels.index') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Hotels</a>
                    <a href="{{ route('travel-agents.tickets') }}" class="block rounded-2xl bg-amber-500 px-4 py-3 text-slate-950 font-semibold">Tickets</a>
                    <a href="{{ route('travel-agents.group-booking') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Group Booking</a>
                </nav>

                <div class="rounded-3xl border border-slate-800 bg-slate-950/10 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Status</p>
                    <p class="mt-2 text-base font-semibold text-emerald-300">Approved</p>
                </div>

                <form action="{{ route('travel-agents.logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Logout</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <header class="rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-xl">
                    <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Ticket Management</p>
                            <h1 class="mt-3 text-4xl font-semibold text-white">Search & Book Flight Tickets</h1>
                            <p class="mt-3 max-w-2xl text-slate-400">Use the filters below to find flight inventory quickly and book for your agency clients.</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl bg-slate-950/70 px-5 py-4 text-slate-300">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Active Tickets</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ count($tickets) }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-950/70 px-5 py-4 text-slate-300">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Next Departure</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $tickets[0]['trip_date'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </header>

                <section class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                    <form action="{{ url()->current() }}" method="GET" class="grid gap-4 xl:grid-cols-[1.4fr_0.6fr]">
                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="block text-sm text-slate-200">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">From</span>
                                <input type="text" name="from" placeholder="ISB" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 focus:border-amber-400 focus:outline-none" />
                            </label>
                            <label class="block text-sm text-slate-200">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">To</span>
                                <input type="text" name="to" placeholder="JED" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 focus:border-amber-400 focus:outline-none" />
                            </label>
                            <label class="block text-sm text-slate-200">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Departure</span>
                                <input type="date" name="departure" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 focus:border-amber-400 focus:outline-none" />
                            </label>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="block text-sm text-slate-200">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Return</span>
                                <input type="date" name="return" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 focus:border-amber-400 focus:outline-none" />
                            </label>
                            <label class="block text-sm text-slate-200">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Airline</span>
                                <select name="airline" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 focus:border-amber-400 focus:outline-none">
                                    <option value="">All Airlines</option>
                                    <option>PIA</option>
                                    <option>Saudi Airlines</option>
                                    <option>Emirates</option>
                                </select>
                            </label>
                            <button type="submit" class="min-h-[58px] rounded-2xl bg-orange-500 px-6 py-4 text-sm font-semibold text-slate-950 transition hover:bg-orange-400">Search Flights</button>
                        </div>
                    </form>
                </section>

                <section class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
                    <div class="grid gap-6">
                        @forelse ($tickets as $ticket)
                            @php
                                $route = $ticket->route ?? 'ISB - JED - ISB';
                                $airline = $ticket->airline ?? 'PIA';
                                $flightNumber = $ticket->flight_number ?? 'PK-201';
                                $departureTime = $ticket->departure_time ?? '23:10';
                                $arrivalTime = $ticket->arrival_time ?? '04:25';
                                $departureDate = $ticket->departure_date?->format('Y-m-d') ?? $ticket->trip_date ?? '2025-08-05';
                                $returnDate = $ticket->return_date?->format('Y-m-d') ?? '2025-08-16';
                                $baggage = $ticket->baggage ?? '30KG';
                                $meal = $ticket->meal ?? 'Meal Included';
                                $price = $ticket->price ? 'SAR '.number_format($ticket->price, 2) : 'SAR 24,400';
                                $status = $ticket->status ?? 'Pending';
                            @endphp
                            <article class="rounded-3xl border border-slate-800 bg-slate-950/60 p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-500/40">
                                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:gap-6">
                                        <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-900 text-2xl font-bold text-amber-300">
                                            {{ strtoupper(substr($airline, 0, 2)) }}
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $airline }}</span>
                                                <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">{{ $flightNumber }}</span>
                                                <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs text-emerald-300">{{ $status }}</span>
                                            </div>
                                            <h2 class="text-2xl font-semibold text-white">{{ $route }}</h2>
                                            <p class="text-sm text-slate-400">Ticket reference: <span class="font-semibold text-slate-100">{{ $ticket->reference }}</span></p>
                                        </div>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-4 xl:items-center xl:gap-4">
                                        <div class="rounded-3xl bg-slate-900 p-4 text-slate-300">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Depart</p>
                                            <p class="mt-2 text-lg font-semibold text-white">{{ $departureTime }}</p>
                                            <p class="text-sm text-slate-500">{{ $departureDate }}</p>
                                        </div>
                                        <div class="rounded-3xl bg-slate-900 p-4 text-slate-300">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Arrive</p>
                                            <p class="mt-2 text-lg font-semibold text-white">{{ $arrivalTime }}</p>
                                            <p class="text-sm text-slate-500">{{ $returnDate }}</p>
                                        </div>
                                        <div class="rounded-3xl bg-slate-900 p-4 text-slate-300">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Baggage</p>
                                            <p class="mt-2 text-lg font-semibold text-white">{{ $baggage }}</p>
                                            <p class="text-sm text-slate-500">Carry-on + checked</p>
                                        </div>
                                        <div class="rounded-3xl bg-slate-900 p-4 text-slate-300">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Meal</p>
                                            <p class="mt-2 text-lg font-semibold text-white">{{ $meal }}</p>
                                            <p class="text-sm text-slate-500">Included</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-3 sm:items-center sm:justify-between border-t border-slate-800 pt-6">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Remaining Seats</p>
                                        <p class="mt-2 text-lg font-semibold text-white">{{ $ticket->available_seats }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure</p>
                                        <p class="mt-2 text-lg font-semibold text-white">{{ $departureDate }}</p>
                                    </div>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                                        @if($ticket->status === 'Approved' && $ticket->available_seats > 0)
                                            <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="inline-flex items-center justify-center rounded-2xl bg-orange-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-orange-400">Book Now</a>
                                        @else
                                            <span class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-6 py-3 text-sm font-semibold text-slate-200">{{ $ticket->available_seats <= 0 ? 'Sold Out' : 'Unavailable' }}</span>
                                        @endif
                                        <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-700 px-6 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-800">Details</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/60 p-10 text-center text-slate-400">
                                <p class="text-lg font-medium text-white">No tickets available yet.</p>
                                <p class="mt-2 text-sm text-slate-400">Upload tickets from the Admin panel under Airline / Ticket Management, then refresh this page.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
