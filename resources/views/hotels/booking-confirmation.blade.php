<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | {{ $booking->hotel->hotel_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="container mx-auto px-4 py-16">
        <div class="bg-white rounded-[2rem] shadow-xl p-10">
            @if(session('success') || session('info'))
                <div class="mb-6 rounded-[1.5rem] border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') ?? session('info') }}
                </div>
            @endif
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold">Booking {{ $booking->status === 'Cancelled' ? 'Status' : 'Confirmed' }}</h1>
                <p class="mt-3 text-slate-500">Your reservation is {{ $booking->status === 'Cancelled' ? 'cancelled and inventory restored' : 'complete and the room has been assigned automatically' }}.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Reference</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $booking->reference_number }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $booking->status }}</p>
                </div>
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Hotel</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $booking->hotel->hotel_name }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $booking->hotel->city }} · {{ $booking->roomType->room_name }}</p>
                </div>
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Room Assigned</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $booking->room->room_number ?? 'Pending assignment' }}</h3>
                    <p class="mt-2 text-sm text-slate-600">Status: {{ $booking->room->status ?? 'Reserved' }}</p>
                </div>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                <div class="rounded-[1.75rem] bg-white border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Stay details</h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Check-in</span><span>{{ $booking->check_in->format('d M Y') }}</span></div>
                        <div class="flex justify-between"><span>Check-out</span><span>{{ $booking->check_out->format('d M Y') }}</span></div>
                        <div class="flex justify-between"><span>Guests</span><span>{{ $booking->total_passengers }}</span></div>
                        <div class="flex justify-between"><span>Meal plan</span><span>{{ $booking->mealPlan->meal_plan_name ?? 'No meals' }}</span></div>
                    </div>
                </div>
                <div class="rounded-[1.75rem] bg-slate-50 border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Price summary</h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Room</span><span>SAR {{ number_format($booking->room_price, 2) }}</span></div>
                        <div class="flex justify-between"><span>Meal</span><span>SAR {{ number_format($booking->meal_price, 2) }}</span></div>
                        <div class="flex justify-between"><span>Tax</span><span>SAR {{ number_format($booking->taxes, 2) }}</span></div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between font-semibold text-slate-900"><span>Total</span><span>SAR {{ number_format($booking->grand_total, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
