<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details | Agent Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #020617;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.84);
            backdrop-filter: blur(24px);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[320px_1fr] gap-6">
            <aside class="border-r border-slate-800 bg-slate-950/95 p-6">
                <div class="flex flex-col h-full justify-between">
                    <div class="space-y-8">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-3xl bg-amber-400/10 px-4 py-2 text-sm font-semibold text-amber-300">Hujaj umrah Agent</span>
                            <div class="mt-6 space-y-2">
                                <h1 class="text-2xl font-semibold text-white">Agent Portal</h1>
                                <p class="text-sm text-slate-400">Premium flight booking dashboard</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 shadow-lg shadow-slate-950/30">
                            <div class="flex items-center gap-4">
                                <div class="rounded-3xl bg-slate-800 p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-2-2h-5.5l-1.5-2H6a2 2 0 0 0-2 2v10"/><path d="M3 15h18"/><path d="M8 7v5"/><path d="M16 7v5"/><path d="M8 18h8"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Logged in as</p>
                                    <p class="mt-2 font-semibold text-white">{{ auth('travel_agent')->user()->first_name ?? 'Agent' }} {{ auth('travel_agent')->user()->last_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <nav class="space-y-2 text-sm text-slate-300">
                            <a href="#" class="flex items-center gap-3 rounded-3xl px-4 py-3 font-semibold text-white bg-amber-500/15 text-amber-300"> 
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                                Book Tickets
                            </a>
                            <a href="#" class="flex items-center gap-3 rounded-3xl px-4 py-3 hover:bg-slate-800">Bookings</a>
                            <a href="#" class="flex items-center gap-3 rounded-3xl px-4 py-3 hover:bg-slate-800">Reports</a>
                            <a href="#" class="flex items-center gap-3 rounded-3xl px-4 py-3 hover:bg-slate-800">My Ledger</a>
                            <a href="#" class="flex items-center gap-3 rounded-3xl px-4 py-3 hover:bg-slate-800">Settings</a>
                        </nav>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-5 shadow-lg shadow-slate-950/20">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Support</p>
                        <p class="mt-3 text-sm text-slate-300">Need help with the booking? Contact our 24/7 support team via WhatsApp.</p>
                        <button class="mt-4 w-full rounded-2xl bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 hover:bg-slate-700">Contact Support</button>
                    </div>
                </div>
            </aside>

            <main class="space-y-6 p-6 xl:p-8">
                @if(session('success'))
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900 shadow-sm">{{ session('success') }}</div>
                @endif
                @if(session('info'))
                    <div class="rounded-3xl border border-slate-200 bg-slate-100 p-6 text-slate-900 shadow-sm">{{ session('info') }}</div>
                @endif
                @if($errors->any())
                    <div class="rounded-3xl border border-rose-200 bg-rose-50 p-6 text-slate-900 shadow-sm">
                        <p class="font-semibold text-rose-700">Please fix the following errors:</p>
                        <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <section class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/20 glass-card">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Booking Details</p>
                            <h2 class="mt-2 text-3xl font-semibold text-white">{{ $ticket->airline }} · {{ $ticket->route }}</h2>
                            <p class="mt-2 max-w-2xl text-slate-400">Review flight details before confirmation.</p>
                        </div>
                        <a href="{{ url()->previous() }}" class="inline-flex items-center rounded-2xl bg-orange-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-orange-400">
                            Back to Tickets
                        </a>
                    </div>
                </section>

                @if($ticket->status === 'Approved' && $ticket->available_seats > 0)
                    <form action="{{ route('travel-agents.tickets.book', $ticket) }}" method="POST" class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                        @csrf
                        <input type="hidden" name="status" value="Pending" />
                        <input type="hidden" name="payment_status" value="Unpaid" />
                        <input type="hidden" name="adults" id="adultsInput" value="2" />
                        <input type="hidden" name="children" id="childrenInput" value="0" />
                        <input type="hidden" name="infants" id="infantsInput" value="0" />
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
                        <input type="hidden" name="reference" value="{{ $ticket->reference }}" />

                        <div class="space-y-6">
                            <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                                <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="grid h-20 w-20 place-items-center rounded-3xl bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-lg shadow-orange-500/20">
                                            <span class="text-3xl font-bold">{{ strtoupper(substr($ticket->airline, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $ticket->airline }}</p>
                                            <h3 class="mt-2 text-2xl font-semibold text-white">{{ $ticket->flight_number }}</h3>
                                            <p class="mt-1 text-sm text-slate-400">{{ $ticket->route }} • Economy</p>
                                        </div>
                                    </div>
                                    <div class="grid gap-2 rounded-3xl bg-slate-950/80 px-5 py-4 text-sm text-slate-300">
                                        <div class="flex items-center justify-between gap-4"><span class="text-slate-500">Departure</span><span class="font-semibold text-white">{{ $ticket->departure_date?->format('d M Y') ?? 'TBD' }}</span></div>
                                        <div class="flex items-center justify-between gap-4"><span class="text-slate-500">Time</span><span class="font-semibold text-white">{{ $ticket->departure_time }}</span></div>
                                        <div class="flex items-center justify-between gap-4"><span class="text-slate-500">Arrival</span><span class="font-semibold text-white">{{ $ticket->arrival_time }}</span></div>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Flight route</p>
                                        <p class="mt-3 text-lg font-semibold text-white">{{ $ticket->route }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Baggage</p>
                                        <p class="mt-3 text-lg font-semibold text-white">{{ $ticket->baggage ?? 'Standard' }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Meal</p>
                                        <p class="mt-3 text-lg font-semibold text-white">{{ $ticket->meal ?? 'Included' }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Adult Fare</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ number_format($ticket->adult_fare, 2) }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Child Fare</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ number_format($ticket->child_fare, 2) }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Infant Fare</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ number_format($ticket->infant_fare, 2) }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Seats</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $ticket->available_seats }} / {{ $ticket->total_seats }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Economy</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $ticket->getClassAvailableSeats('Economy') }} / {{ $ticket->economy_seats }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Premium Economy</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $ticket->getClassAvailableSeats('Premium Economy') }} / {{ $ticket->premium_economy_seats }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Business</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $ticket->getClassAvailableSeats('Business') }} / {{ $ticket->business_seats }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">First</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $ticket->getClassAvailableSeats('First') }} / {{ $ticket->first_seats }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 text-sm text-slate-400">Reference: {{ $ticket->reference }}</div>
                            </article>

                            <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Passenger Selection</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Choose passenger count</h3>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 px-4 py-3 text-right text-sm text-slate-400">
                                    <p class="uppercase tracking-[0.3em] text-slate-500">Total pax</p>
                                    <p id="passengerTotal" class="mt-2 text-3xl font-semibold text-white">2</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-3xl bg-slate-950/80 p-5">
                                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Adults</p>
                                    <div class="mt-4 flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3">
                                        <button id="decreaseAdult" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-white">-</button>
                                        <span id="adultCount" class="text-2xl font-semibold text-white">2</span>
                                        <button id="increaseAdult" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-500 text-slate-950">+</button>
                                    </div>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5">
                                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Children</p>
                                    <div class="mt-4 flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3">
                                        <button id="decreaseChild" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-white">-</button>
                                        <span id="childCount" class="text-2xl font-semibold text-white">0</span>
                                        <button id="increaseChild" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-500 text-slate-950">+</button>
                                    </div>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5">
                                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Infants</p>
                                    <div class="mt-4 flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3">
                                        <button id="decreaseInfant" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-white">-</button>
                                        <span id="infantCount" class="text-2xl font-semibold text-white">0</span>
                                        <button id="increaseInfant" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-500 text-slate-950">+</button>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Passenger Details</p>
                                <h3 class="mt-2 text-2xl font-semibold text-white">Traveler information</h3>
                            </div>
                            <div id="passengerForms" class="mt-6 space-y-5"></div>
                        </article>

                        <!-- <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div class="flex flex-col gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Passenger Information</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Primary Traveller Details</h3>
                                </div>

                                <div class="grid gap-4 lg:grid-cols-2">
                                    <label class="block text-sm text-slate-300">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Contact Name</span>
                                <input type="text" name="contact_name" placeholder="Name as shown on ticket" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                            </label>
                            <label class="block text-sm text-slate-300">
                                <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Gender</span>
                                <select name="gender" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                                </label>

                                <div class="grid gap-4 lg:grid-cols-3">
                                    </label>
                                </div>

                                <div class="grid gap-4 lg:grid-cols-4">
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Passport Number</span>
                                        <input type="text" name="passport_number" placeholder="ABCDE1234" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                                    </label>
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Passport Expiry</span>
                                        <input type="date" name="passport_expiry" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                                    </label>
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">CNIC</span>
                                        <input type="text" name="cnic" placeholder="42101-1234567-1" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                                    </label>
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Phone</span>
                                        <input type="text" name="phone" placeholder="+92 312 3456789" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                                    </label>
                                </div> -->

                                <!-- <div class="grid gap-4 lg:grid-cols-2">
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Email</span>
                                        <input type="email" name="contact_email" placeholder="agent@example.com" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" />
                                    </label>
                                    <label class="block text-sm text-slate-300">
                                        <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Preferred Seat</span>
                                        <select name="seat" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none">
                                            <option>Auto assign</option>
                                            <option>Window</option>
                                            <option>Aisle</option>
                                            <option>Extra legroom</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </article> -->

                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Contact Details</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Booking Contact</h3>
                                </div>
                                <span class="rounded-3xl bg-slate-950/80 px-4 py-2 text-sm text-slate-300">Secure</span>
                            </div>

                            <div class="grid gap-4 lg:grid-cols-3 mt-6">
                                <label class="block text-sm text-slate-300">
                                    <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Contact Name</span>
                                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Name as shown on ticket" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                                </label>
                                <label class="block text-sm text-slate-300">
                                    <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Email</span>
                                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="agent@example.com" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                                </label>
                                <label class="block text-sm text-slate-300">
                                    <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">WhatsApp Number</span>
                                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+92 312 3456789" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                                </label>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                                <label class="block text-sm text-slate-300">
                                    <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Cabin Class</span>
                                    <select name="cabin_class" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none">
                                        <option value="Economy" {{ old('cabin_class') === 'Economy' ? 'selected' : '' }}>Economy</option>
                                        <option value="Premium Economy" {{ old('cabin_class') === 'Premium Economy' ? 'selected' : '' }}>Premium Economy</option>
                                        <option value="Business" {{ old('cabin_class') === 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="First" {{ old('cabin_class') === 'First' ? 'selected' : '' }}>First</option>
                                    </select>
                                </label>
                            </div>
                        </article>

                        <!-- <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Additional Services</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Value-added enhancements</h3>
                                </div>
                                <div class="text-sm text-slate-400">Optional</div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-800 bg-slate-950/80 px-4 py-4 text-sm text-slate-300">
                                    <input type="checkbox" name="insurance" class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500" />
                                    <div>
                                        <p class="font-semibold text-white">Travel Insurance</p>
                                        <p class="text-xs text-slate-500">Protect the passenger booking.</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-800 bg-slate-950/80 px-4 py-4 text-sm text-slate-300">
                                    <input type="checkbox" name="extra_baggage" class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500" />
                                    <div>
                                        <p class="font-semibold text-white">Extra Baggage</p>
                                        <p class="text-xs text-slate-500">Add additional baggage allowance.</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-800 bg-slate-950/80 px-4 py-4 text-sm text-slate-300">
                                    <input type="checkbox" name="wheelchair" class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500" />
                                    <div>
                                        <p class="font-semibold text-white">Wheelchair</p>
                                        <p class="text-xs text-slate-500">Request special assistance.</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-800 bg-slate-950/80 px-4 py-4 text-sm text-slate-300">
                                    <input type="checkbox" name="meal_preference" class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500" />
                                    <div>
                                        <p class="font-semibold text-white">Meal Preference</p>
                                        <p class="text-xs text-slate-500">Select a meal option.</p>
                                    </div>
                                </label>
                            </div>
                        </article> -->
                    </div>

                    <aside class="space-y-6">
                        <article class="sticky top-6 rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20 glass-card">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Fare Summary</p>
                                <h3 class="mt-2 text-2xl font-semibold text-white">Estimated payment breakdown</h3>
                            </div>

                            <div class="mt-6 space-y-4 text-sm text-slate-300">
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3">
                                    <span>Adult Fare</span>
                                    <span>PKR <span id="adultFare">178,000</span></span>
                                </div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3">
                                    <span>Child Fare</span>
                                    <span>PKR <span id="childFare">120,000</span></span>
                                </div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3">
                                    <span>Infant Fare</span>
                                    <span>PKR <span id="infantFare">45,000</span></span>
                                </div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3">
                                    <span>Taxes</span>
                                    <span>PKR <span id="taxes">22,400</span></span>
                                </div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3">
                                    <span>Service Charges</span>
                                    <span>PKR <span id="service">5,600</span></span>
                                </div>
                                <div class="flex items-center justify-between rounded-3xl bg-amber-500/10 px-4 py-4 text-white">
                                    <span class="text-sm uppercase tracking-[0.25em] text-slate-200">Grand Total</span>
                                    <span class="text-xl font-semibold">PKR <span id="grandTotal">376,000</span></span>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-3">
                                <button type="submit" id="continueBooking" class="rounded-3xl bg-orange-500 px-4 py-4 text-sm font-semibold text-slate-950 transition hover:bg-orange-400">Continue Booking</button>
                                <button type="button" class="rounded-3xl border border-slate-700 bg-slate-950/90 px-4 py-4 text-sm font-semibold text-slate-200 hover:bg-slate-900">Download Itinerary</button>
                            </div>
                        </article>
                    </aside>
                </section>
            </form>
@else
                    <section class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-slate-950/20 glass-card">
                        <div class="text-center">
                            <h3 class="text-2xl font-semibold text-white">This flight cannot be booked right now.</h3>
                            <p class="mt-4 text-slate-400">{{ $ticket->available_seats <= 0 ? 'Sold out. No seats are available for this flight.' : 'This flight is currently unavailable for booking.' }}</p>
                            <div class="mt-6 inline-flex rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-slate-100">Status: {{ $ticket->status }}</div>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>

    <script>
        const prices = {
            adult: @json($ticket->adult_fare),
            child: @json($ticket->child_fare),
            infant: @json($ticket->infant_fare),
            taxRate: @json($ticket->tax_rate ?? 0.08),
            serviceRate: @json($ticket->service_charge_rate ?? 0.015),
        };

        const state = {
            adult: 2,
            child: 0,
            infant: 0,
        };

        const adultCount = document.getElementById('adultCount');
        const childCount = document.getElementById('childCount');
        const infantCount = document.getElementById('infantCount');
        const passengerTotal = document.getElementById('passengerTotal');
        const adultFare = document.getElementById('adultFare');
        const childFare = document.getElementById('childFare');
        const infantFare = document.getElementById('infantFare');
        const taxesEl = document.getElementById('taxes');
        const serviceEl = document.getElementById('service');
        const grandTotalEl = document.getElementById('grandTotal');
        const adultsInput = document.getElementById('adultsInput');
        const childrenInput = document.getElementById('childrenInput');
        const infantsInput = document.getElementById('infantsInput');
        const passengerForms = document.getElementById('passengerForms');
        const oldPassengers = @json(old('passengers', []));
        const maxPassengers = @json($ticket->available_seats);

        function formatNumber(value) {
            return Number(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function getPassengerType(index, type) {
            const typeOrder = [];
            typeOrder.push(...Array(state.adult).fill('Adult'));
            typeOrder.push(...Array(state.child).fill('Child'));
            typeOrder.push(...Array(state.infant).fill('Infant'));
            return typeOrder[index] ?? 'Adult';
        }

        function buildPassengerCard(index, type, passenger = {}) {
            return `
                <div class="rounded-3xl border border-slate-800 bg-slate-950/90 p-5 text-slate-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Passenger ${ index + 1 }</p>
                            <h4 class="mt-2 text-lg font-semibold text-white">${type}</h4>
                        </div>
                        <span class="rounded-2xl bg-slate-800 px-3 py-2 text-xs text-slate-300">${type}</span>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-3">
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">First name</span>
                            <input type="text" name="passengers[${index}][first_name]" value="${passenger.first_name ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Last name</span>
                            <input type="text" name="passengers[${index}][last_name]" value="${passenger.last_name ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Gender</span>
                            <select name="passengers[${index}][gender]" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required>
                                <option value="" ${!passenger.gender ? 'selected' : ''} disabled>Select gender</option>
                                <option value="Male" ${passenger.gender === 'Male' ? 'selected' : ''}>Male</option>
                                <option value="Female" ${passenger.gender === 'Female' ? 'selected' : ''}>Female</option>
                                <option value="Other" ${passenger.gender === 'Other' ? 'selected' : ''}>Other</option>
                            </select>
                        </label>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Date of birth</span>
                            <input type="date" name="passengers[${index}][date_of_birth]" value="${passenger.date_of_birth ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Nationality</span>
                            <input type="text" name="passengers[${index}][nationality]" value="${passenger.nationality ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Passport number</span>
                            <input type="text" name="passengers[${index}][passport_number]" value="${passenger.passport_number ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                        <label class="block text-sm text-slate-300">
                            <span class="mb-2 block text-xs uppercase tracking-[0.24em] text-slate-500">Passport expiry</span>
                            <input type="date" name="passengers[${index}][passport_expiry]" value="${passenger.passport_expiry ?? ''}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none" required />
                        </label>
                    </div>
                    <input type="hidden" name="passengers[${index}][passenger_type]" value="${type}" />
                </div>
            `;
        }

        function renderPassengerForms() {
            if (!passengerForms) return;

            const orderedPassengers = [];
            for (let i = 0; i < state.adult; i += 1) {
                orderedPassengers.push({ type: 'Adult', values: oldPassengers[i] ?? {} });
            }
            for (let i = 0; i < state.child; i += 1) {
                orderedPassengers.push({ type: 'Child', values: oldPassengers[state.adult + i] ?? {} });
            }
            for (let i = 0; i < state.infant; i += 1) {
                orderedPassengers.push({ type: 'Infant', values: oldPassengers[state.adult + state.child + i] ?? {} });
            }

            passengerForms.innerHTML = orderedPassengers
                .map((passenger, index) => buildPassengerCard(index, passenger.type, passenger.values))
                .join('');
        }

        function recalculate() {
            const adultAmount = state.adult * (prices.adult || 0);
            const childAmount = state.child * (prices.child || 0);
            const infantAmount = state.infant * (prices.infant || 0);
            const subTotal = adultAmount + childAmount + infantAmount;
            const taxes = Math.round(subTotal * prices.taxRate);
            const service = Math.round(subTotal * prices.serviceRate);
            const grandTotal = subTotal + taxes + service;

            passengerTotal.textContent = state.adult + state.child + state.infant;
            adultCount.textContent = state.adult;
            childCount.textContent = state.child;
            infantCount.textContent = state.infant;
            adultFare.textContent = formatNumber(adultAmount);
            childFare.textContent = formatNumber(childAmount);
            infantFare.textContent = formatNumber(infantAmount);
            taxesEl.textContent = formatNumber(taxes);
            serviceEl.textContent = formatNumber(service);
            grandTotalEl.textContent = formatNumber(grandTotal);

            if (adultsInput) {
                adultsInput.value = state.adult;
            }
            if (childrenInput) {
                childrenInput.value = state.child;
            }
            if (infantsInput) {
                infantsInput.value = state.infant;
            }

            renderPassengerForms();
        }

        function createCounter(buttonId, key, min) {
            const button = document.getElementById(buttonId);
            if (!button) return;
            button.addEventListener('click', () => {
                const currentTotal = state.adult + state.child + state.infant;
                if (button.textContent.trim() === '+' && currentTotal >= maxPassengers) {
                    return;
                }

                state[key] = Math.max(min, state[key] + (button.textContent.trim() === '+' ? 1 : -1));
                if (state[key] < min) state[key] = min;
                recalculate();
            });
        }

        createCounter('increaseAdult', 'adult', 1);
        createCounter('decreaseAdult', 'adult', 1);
        createCounter('increaseChild', 'child', 0);
        createCounter('decreaseChild', 'child', 0);
        createCounter('increaseInfant', 'infant', 0);
        createCounter('decreaseInfant', 'infant', 0);

        recalculate();
    </script>
</body>
</html>
