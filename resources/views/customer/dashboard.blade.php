<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Portal | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f4f7fc;
        }
        .glass-panel {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(148,163,184,0.12);
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <section class="glass-panel p-6 md:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">Customer Portal</span>
                    <h1 class="mt-3 text-3xl font-extrabold text-slate-900">Welcome back, {{ $customer?->first_name ?? $user->name }}</h1>
                    <p class="mt-2 text-sm text-slate-500">Your personal dashboard shows only your own bookings, visa applications, and account profile.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 px-5 py-3 text-xs font-bold text-slate-700">
                        Role: Customer
                    </div>
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 shadow-sm">
                        Browse Flights
                    </a>
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <section class="glass-panel p-5">
                <h2 class="text-lg font-bold text-slate-900">Profile</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-slate-400">Name</dt><dd class="font-semibold text-slate-800">{{ $customer?->first_name ?? $user->name }} {{ $customer?->last_name ?? '' }}</dd></div>
                    <div><dt class="text-slate-400">Email</dt><dd class="font-semibold text-slate-800">{{ $user->email }}</dd></div>
                    <div><dt class="text-slate-400">Phone</dt><dd class="font-semibold text-slate-800">{{ $customer?->phone ?? 'N/A' }}</dd></div>
                    <div><dt class="text-slate-400">CNIC</dt><dd class="font-semibold text-slate-800">{{ $customer?->cnic ?? 'N/A' }}</dd></div>
                    <div><dt class="text-slate-400">Nationality</dt><dd class="font-semibold text-slate-800">{{ $customer?->nationality ?? 'N/A' }}</dd></div>
                </dl>
            </section>

            <section class="glass-panel p-5 lg:col-span-2">
                <h2 class="text-lg font-bold text-slate-900">Visa Applications</h2>
                @if($visaApplications->isEmpty())
                    <p class="mt-3 text-sm text-slate-500">No visa applications found for your account.</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-slate-500">
                                <tr>
                                    <th class="pb-2">ID</th>
                                    <th class="pb-2">Customer</th>
                                    <th class="pb-2">Passport</th>
                                    <th class="pb-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visaApplications as $application)
                                    <tr class="border-t border-slate-100">
                                        <td class="py-3">#{{ $application->id }}</td>
                                        <td class="py-3">{{ $application->customer_name }}</td>
                                        <td class="py-3">{{ $application->passport_number }}</td>
                                        <td class="py-3 font-semibold text-slate-800">{{ $application->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <section class="glass-panel p-5">
                <h2 class="text-lg font-bold text-slate-900">Hotel Bookings</h2>
                @if($hotelBookings->isEmpty())
                    <p class="mt-3 text-sm text-slate-500">No hotel bookings found for your account.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach($hotelBookings as $booking)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <div class="font-semibold text-slate-800">{{ $booking->contact_name }}</div>
                                <div class="text-xs text-slate-500">Reference: {{ $booking->reference_number ?? 'N/A' }} · Status: {{ $booking->status }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="glass-panel p-5">
                <h2 class="text-lg font-bold text-slate-900">Flight Bookings</h2>
                @if($flightBookings->isEmpty())
                    <p class="mt-3 text-sm text-slate-500">No flight bookings found for your account.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach($flightBookings as $booking)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <div class="font-semibold text-slate-800">{{ $booking->contact_name }}</div>
                                <div class="text-xs text-slate-500">Reference: {{ $booking->reference ?? 'N/A' }} · Status: {{ $booking->status }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</body>
</html>
