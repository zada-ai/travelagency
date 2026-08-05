<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="mx-auto max-w-6xl px-4 py-12">
        <div class="rounded-[2rem] bg-white p-8 shadow-xl shadow-slate-200/50">
            <div class="mb-8 text-center">
                <p class="text-sm uppercase tracking-[0.28em] text-blue-500 font-semibold">Booking Confirmed</p>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-900">Thank you for your booking</h1>
                <p class="mt-3 text-base text-slate-600">We received your booking request. Our team will contact you shortly to confirm the details.</p>
            </div>

            @if(session('success') || session('info'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                    {{ session('success') ?? session('info') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Booking Reference</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $booking->reference }}</h2>
                    <p class="mt-2 text-sm text-slate-600">Status: {{ $booking->status }}</p>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Flight</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $booking->ticket->airline }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $booking->ticket->route }} · {{ $booking->ticket->flight_number }}</p>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Cabin Class</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $booking->cabin_class }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $booking->total_passengers }} passengers booked</p>
                </div>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Contact</p>
                    <p class="mt-3 text-sm text-slate-900 font-semibold">{{ $booking->contact_name }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $booking->contact_email }}</p>
                    <p class="text-sm text-slate-600">{{ $booking->contact_phone }}</p>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Seat details</p>
                    <p class="mt-3 text-sm text-slate-900 font-semibold">Assigned Seats</p>
                    <p class="mt-2 text-sm text-slate-600">{{ implode(', ', $booking->seat_numbers ?? []) }}</p>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Payment summary</p>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Subtotal</span><span>SAR {{ number_format($booking->price, 2) }}</span></div>
                        <div class="flex justify-between"><span>Taxes</span><span>SAR {{ number_format($booking->taxes, 2) }}</span></div>
                        <div class="flex justify-between"><span>Service Charge</span><span>SAR {{ number_format($booking->service_charge, 2) }}</span></div>
                        <div class="flex justify-between"><span>Visa</span><span>SAR {{ number_format($booking->visa_price, 2) }}</span></div>
                        <div class="flex justify-between"><span>Transport</span><span>SAR {{ number_format($booking->transport_price, 2) }}</span></div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between font-semibold text-slate-900"><span>Total</span><span>SAR {{ number_format($booking->grand_total, 2) }}</span></div>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-[1.75rem] bg-blue-50 border border-blue-100 p-6 text-slate-700">
                <p class="text-sm font-semibold text-blue-900">Thank you for choosing our service.</p>
                <p class="mt-2 text-sm text-slate-600">We will contact you soon to finalize the booking details. If you need immediate support, please reach out through your portal or call the number listed in your account.</p>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ Auth::guard('travel_agent')->check() ? route('travel-agents.bookings') : route('customer.bookings') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Back to Bookings</a>
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Search More Flights</a>
            </div>
        </div>
    </div>
</body>
</html>
