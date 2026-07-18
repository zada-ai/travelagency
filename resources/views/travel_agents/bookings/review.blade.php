<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Review | Agent Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[320px_1fr] gap-6">
            <aside class="border-r border-slate-800 bg-slate-900/95 p-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Hujaj umrah Agent</h2>
                        <p class="text-slate-400 mt-2 text-sm">{{ $booking['contact_name'] }}</p>
                    </div>
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('travel-agents.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Overview</a>
                        <a href="{{ route('travel-agents.tickets') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Search Flights</a>
                        <a href="{{ route('travel-agents.bookings') }}" class="block rounded-2xl bg-amber-500 px-4 py-3 text-slate-950 font-semibold">My Bookings</a>
                    </nav>
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
                <div class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/20">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Review & Confirm</p>
                            <h1 class="mt-2 text-3xl font-semibold text-white">Booking Review</h1>
                            <p class="mt-2 text-slate-400">Confirm all details before finalizing the booking.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950/80 px-4 py-3 text-sm text-slate-300">
                            <p class="uppercase tracking-[0.3em] text-slate-500">Booking Status</p>
                            <p class="mt-2 text-white">{{ $booking['status'] }}</p>
                        </div>
                    </div>
                </div>

                <section class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                    <div class="space-y-6">
                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20">
                            <h2 class="text-xl font-semibold text-white">Flight Details</h2>
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Airline</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $booking['ticket_airline'] }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Flight Number</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $booking['ticket_flight_number'] }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Route</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $booking['ticket_route'] }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Departure Date</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $ticket->departure_date?->format('d M Y') }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Return Date</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $ticket->return_date?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Departure Time</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $ticket->departure_time }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Arrival Time</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $ticket->arrival_time }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-5 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Cabin Class</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $booking['cabin_class'] }}</p>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-white">Passenger Details</h2>
                                    <p class="text-slate-400">Review each passenger record before confirmation.</p>
                                </div>
                                <span class="rounded-3xl bg-slate-950/80 px-4 py-2 text-sm text-slate-300">Total: {{ $booking['total_passengers'] }}</span>
                            </div>

                            <div class="mt-6 space-y-4">
                                @foreach($booking['passengers'] as $passenger)
                                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-4 text-slate-100">
                                        <div class="flex flex-wrap items-center justify-between gap-4">
                                            <div>
                                                <p class="font-semibold text-white">{{ $passenger['first_name'] }} {{ $passenger['last_name'] }}</p>
                                                <p class="text-sm text-slate-500">{{ $passenger['passenger_type'] }} • {{ ucfirst(strtolower($passenger['gender'])) }}</p>
                                            </div>
                                            <div class="text-right text-sm text-slate-400">
                                                <p>{{ $passenger['date_of_birth'] }}</p>
                                                <p>{{ $passenger['nationality'] }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-4 grid gap-4 sm:grid-cols-3 text-sm text-slate-400">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.24em]">Passport</p>
                                                <p class="mt-1 text-white">{{ $passenger['passport_number'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.24em]">Passport Expiry</p>
                                                <p class="mt-1 text-white">{{ $passenger['passport_expiry'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.24em]">Phone / Email</p>
                                                <p class="mt-1 text-white">{{ $booking['contact_phone'] }} / {{ $booking['contact_email'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    </div>

                    <aside class="space-y-6">
                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20">
                            <h2 class="text-xl font-semibold text-white">Booking Details</h2>
                            <div class="mt-6 space-y-3 text-sm text-slate-300">
                                <div class="flex items-center justify-between"><span>Adults</span><span>{{ $booking['adults'] }}</span></div>
                                <div class="flex items-center justify-between"><span>Children</span><span>{{ $booking['children'] }}</span></div>
                                <div class="flex items-center justify-between"><span>Infants</span><span>{{ $booking['infants'] }}</span></div>
                                <div class="flex items-center justify-between"><span>Seats Booked</span><span>{{ $booking['total_passengers'] }}</span></div>
                                <div class="flex items-center justify-between"><span>Seat Numbers</span><span>{{ implode(', ', $booking['seat_numbers']) }}</span></div>
                                <div class="flex items-center justify-between"><span>Booking Date</span><span>{{ now()->format('d M Y') }}</span></div>
                            </div>
                        </article>

                        <article class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20">
                            <h2 class="text-xl font-semibold text-white">Payment Summary</h2>
                            <div class="mt-6 space-y-3 text-sm text-slate-300">
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3"><span>Price Per Seat</span><span>SAR {{ number_format($booking['subtotal'] / max($booking['total_passengers'], 1), 2) }}</span></div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3"><span>Seats Booked</span><span>{{ $booking['total_passengers'] }}</span></div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3"><span>Subtotal</span><span>SAR {{ number_format($booking['subtotal'], 2) }}</span></div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3"><span>Taxes</span><span>SAR {{ number_format($booking['taxes'], 2) }}</span></div>
                                <div class="flex items-center justify-between rounded-3xl bg-slate-950/80 px-4 py-3"><span>Service Charges</span><span>SAR {{ number_format($booking['service_charge'], 2) }}</span></div>
                                <div class="flex items-center justify-between rounded-3xl bg-amber-500/10 px-4 py-4 text-white"><span class="text-sm uppercase tracking-[0.25em] text-slate-200">Grand Total</span><span class="text-xl font-semibold">SAR {{ number_format($booking['grand_total'], 2) }}</span></div>
                            </div>
                        </article>

                        <form action="{{ route('travel-agents.bookings.confirm') }}" method="POST" class="grid gap-3">
                            @csrf
                            <button type="submit" class="rounded-3xl bg-emerald-500 px-4 py-4 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Confirm Booking</button>
                        </form>
                        <form action="{{ route('travel-agents.bookings.cancel-review') }}" method="POST" class="grid gap-3">
                            @csrf
                            <button type="submit" class="rounded-3xl bg-rose-500 px-4 py-4 text-sm font-semibold text-white hover:bg-rose-600">Cancel Booking</button>
                        </form>
                        <a href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-700 bg-slate-950/90 px-4 py-4 text-sm font-semibold text-slate-200 hover:bg-slate-900">Back to Edit</a>
                    </aside>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
