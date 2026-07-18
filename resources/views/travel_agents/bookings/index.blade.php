<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Agent Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[320px_1fr] gap-6">
            <aside class="border-r border-slate-800 bg-slate-900/95 p-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Hujaj umrah Agent</h2>
                        <p class="text-slate-400 mt-2 text-sm">{{ $agent->company_name }}</p>
                    </div>
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('travel-agents.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Overview</a>
                        <a href="{{ route('travel-agents.tickets') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Tickets</a>
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
                <div class="rounded-[32px] border border-slate-800 bg-slate-900/95 p-6 shadow-2xl shadow-slate-950/20">
                    <h1 class="text-3xl font-semibold text-white">My Bookings</h1>
                    <p class="mt-2 text-slate-400">Track the current status of every booking in your agency.</p>
                </div>

                <div class="space-y-4">
                    @forelse($bookings as $booking)
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/95 p-6 shadow-lg shadow-slate-950/20">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Booking #{{ $booking->id }}</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-white">{{ $booking->ticket->airline }} · {{ $booking->ticket->route }}</h2>
                                    <p class="mt-1 text-sm text-slate-400">{{ $booking->ticket->flight_number }} • {{ $booking->ticket->departure_date?->format('d M Y') }}</p>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-sm text-slate-300">
                                    <div class="rounded-3xl bg-slate-950/80 px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Status</p>
                                        <p class="mt-2 font-semibold text-white">{{ $booking->status }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Passengers</p>
                                        <p class="mt-2 font-semibold text-white">{{ $booking->total_passengers }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-950/80 px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Total</p>
                                        <p class="mt-2 font-semibold text-white">SAR {{ number_format($booking->grand_total, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl bg-slate-950/80 p-4 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Contact</p>
                                    <p class="mt-2 text-white">{{ $booking->contact_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $booking->contact_phone }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-950/80 p-4 text-slate-300">
                                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Urgent Support</p>
                                    <p class="mt-2 text-white">Need your booking urgently? Please contact support.</p>
                                    <p class="mt-2 text-sm text-amber-300">+92-300-1234567</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/95 p-8 text-center text-slate-400">
                            No bookings have been made yet.
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>
</body>
</html>
