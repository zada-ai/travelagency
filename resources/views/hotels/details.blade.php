<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel->hotel_name }} | Premium Hotel Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #0f172a; }
        .hero-banner { background: linear-gradient(135deg, rgba(15,23,42,.75), rgba(15,23,42,.4)), url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1800&q=80') center/cover no-repeat; }
        .sticky-booking { position: sticky; top: 24px; }
        .hotel-badge { background: rgba(255,255,255,.92); backdrop-filter: blur(12px); }
        .drawer-shadow { box-shadow: 0 24px 64px rgba(15,23,42,.08); }
    </style>
</head>
<body>
    <header class="hero-banner min-h-[420px] relative flex items-end">
        <div class="container mx-auto px-4 pb-12">
            <div class="max-w-4xl rounded-[2rem] bg-white/10 p-8 backdrop-blur-xl border border-white/10 shadow-2xl">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs uppercase tracking-[0.24em] text-slate-100/90 mb-4">Premium hotel details</span>
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">{{ $hotel->hotel_name }}</h1>
                <p class="mt-4 text-base md:text-lg text-slate-200 max-w-2xl">{{ $hotel->city }} · {{ $hotel->category }} · {{ number_format($hotel->distance_from_haram, 0) }}m from Haram</p>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <section class="lg:col-span-8 space-y-8">
                <div class="grid gap-4 md:grid-cols-2">
                    @if($hotel->images->isNotEmpty())
                        @foreach($hotel->images->take(2) as $image)
                            <div class="rounded-[2rem] overflow-hidden shadow-xl bg-white">
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $hotel->hotel_name }} image {{ $loop->iteration }}" class="w-full h-[320px] object-cover">
                            </div>
                        @endforeach
                    @else
                        <div class="rounded-[2rem] overflow-hidden shadow-xl bg-white">
                            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80" alt="Hotel interior" class="w-full h-[320px] object-cover">
                        </div>
                        <div class="rounded-[2rem] overflow-hidden shadow-xl bg-white">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80" alt="Hotel exterior" class="w-full h-[320px] object-cover">
                        </div>
                    @endif
                </div>

                <div class="rounded-[2rem] p-8 bg-white shadow-xl border border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs uppercase tracking-[0.18em] text-blue-700">Highest rated</span>
                            <h2 class="mt-4 text-2xl font-semibold text-slate-900">About this hotel</h2>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-500">
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-2">
                                <i class="bi bi-star-fill text-amber-500"></i> 4.8
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-2">
                                <i class="bi bi-geo-alt text-blue-600"></i> {{ $hotel->address }}
                            </div>
                        </div>
                    </div>
                    <p class="text-sm leading-7 text-slate-600">{{ $hotel->description ?? 'Stay in a premium hotel with prayer-friendly services, quick shuttle access to Al Haram, and modern guest rooms designed for peaceful spiritual retreats.' }}</p>

                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                        <h3 class="text-xl font-semibold text-slate-900 mb-4">Key Amenities</h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($hotel->facilities->take(6) as $facility)
                                <div class="rounded-3xl bg-slate-50 p-4 flex items-center gap-3 text-sm text-slate-600">
                                    <i class="bi bi-check-circle-fill text-blue-600"></i>
                                    <span>{{ $facility->facility_name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                        <h3 class="text-xl font-semibold text-slate-900 mb-4">Rates & availability</h3>
                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="flex items-start gap-3">
                                <span class="text-blue-600 text-lg mt-1"><i class="bi bi-clock-history"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Flexible seasonal pricing</p>
                                    <p class="mt-1">Season rates refresh instantly for your chosen stay dates.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-blue-600 text-lg mt-1"><i class="bi bi-houses"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Available room types</p>
                                    <p class="mt-1">{{ $hotel->roomTypes->count() }} room categories ready to reserve.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-blue-600 text-lg mt-1"><i class="bi bi-check2-circle"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Inventory status</p>
                                    <p class="mt-1">{{ $hotel->inventories->where('available_rooms', '>', 0)->count() }} slots currently available.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <h3 class="text-xl font-semibold text-slate-900">Room options</h3>
                        <span class="text-sm uppercase tracking-[0.24em] text-slate-400">Select your preferred stay</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($hotel->roomTypes as $roomType)
                            <div class="rounded-[1.75rem] border border-slate-200 p-5 shadow-sm hover:border-blue-500 transition">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.20em] text-slate-400">{{ $roomType->room_name }}</p>
                                        <h4 class="mt-2 text-xl font-semibold text-slate-900">{{ $roomType->room_name }} · {{ $roomType->max_occupancy }} guests</h4>
                                        <p class="mt-2 text-sm text-slate-500">{{ $roomType->available_rooms }} rooms available</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-slate-500">From</p>
                                        <p class="mt-2 text-3xl font-extrabold text-slate-900">SAR {{ number_format($roomType->daily_rate, 2) }}</p>
                                        <p class="text-xs text-slate-400 mt-1">Per night, excl. taxes</p>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">Extra bed price: SAR {{ number_format($roomType->extra_bed_price, 2) }}</div>
                                    <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">Popular for family travel & pilgrims</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                        <h3 class="text-xl font-semibold text-slate-900 mb-4">Stay policies</h3>
                        <div class="space-y-4">
                            @foreach($policyHighlights as $policy)
                                <div class="rounded-3xl bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $policy['title'] }}</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ $policy['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                        <h3 class="text-xl font-semibold text-slate-900 mb-4">Traveler reviews</h3>
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="rounded-3xl bg-slate-50 p-5">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $review['name'] }}</p>
                                            <p class="text-sm text-slate-500 mt-1">{{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}</p>
                                        </div>
                                        <span class="text-xs uppercase tracking-[0.24em] text-slate-400">Verified stay</span>
                                    </div>
                                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ $review['comment'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white border border-slate-200 p-8 shadow-xl">
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Similar hotels in {{ $hotel->city }}</h3>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach($recommendations as $similar)
                            <div class="rounded-[1.75rem] bg-slate-50 p-4 shadow-sm">
                                <p class="text-sm text-slate-500">{{ $similar->hotel_name }}</p>
                                <div class="mt-2 text-lg font-semibold text-slate-900">SAR {{ number_format($similar->roomTypes->min('daily_rate') ?? 0, 2) }}</div>
                                <p class="mt-2 text-sm text-slate-400">{{ number_format($similar->distance_from_haram, 0) }}m to Haram</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <aside class="lg:col-span-4">
                <div class="hotel-badge rounded-[2rem] bg-white border border-slate-200 p-6 shadow-xl sticky-booking drawer-shadow">
                    <div class="mb-6">
                        <p class="text-xs uppercase tracking-[0.24em] text-blue-600 font-semibold">Secure your room</p>
                        <h2 class="mt-3 text-3xl font-bold text-slate-900">Best available rate</h2>
                        <p class="mt-2 text-sm text-slate-500">Instant confirmation. Manage your booking securely.</p>
                    </div>

                    <form action="{{ route('hotels.book') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                        @if ($errors->any())
                            <div class="rounded-3xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                                <p class="font-semibold mb-2">Please fix the following issues:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Room type</label>
                            <select name="hotel_room_type_id" id="detailRoomType" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                @foreach($hotel->roomTypes->where('status', 'Active') as $roomType)
                                    <option value="{{ $roomType->id }}"
                                        data-rate="{{ $roomType->daily_rate }}"
                                        data-capacity="{{ $roomType->max_occupancy }}"
                                        data-available="{{ $roomTypeAvailabilities[$roomType->id]['available_rooms'] ?? 0 }}"
                                        data-status="{{ $roomTypeAvailabilities[$roomType->id]['status'] ?? 'Select dates' }}"
                                    >
                                        {{ $roomType->room_name }} · SAR {{ number_format($roomType->daily_rate, 2) }} / night
                                    </option>
                                @endforeach
                            </select>
                            <p id="roomAvailabilityText" class="mt-2 text-xs text-slate-500">
                                @if(isset($checkIn) && isset($checkOut))
                                    @php $firstType = $hotel->roomTypes->where('status','Active')->first(); @endphp
                                    @if($firstType)
                                        {{ $roomTypeAvailabilities[$firstType->id]['available_rooms'] ?? 0 }} rooms available for selected dates
                                    @else
                                        Select your room type and dates to see availability.
                                    @endif
                                @else
                                    Select your stay dates to see live room availability.
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">City</label>
                            <input type="text" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" value="{{ $hotel->city }}" readonly>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Check-in</label>
                                <input type="text" id="detailCheckIn" name="check_in" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Select date" readonly required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Check-out</label>
                                <input type="text" id="detailCheckOut" name="check_out" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Select date" readonly required>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Adults</label>
                                <select name="adults" id="detailAdults" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $i === 2 ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Children</label>
                                <select name="children" id="detailChildren" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 0; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <p id="childLimitMessage" class="mt-2 text-xs text-red-600 hidden">For more than 5 children, please book 2 rooms.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Infants</label>
                                <select name="infants" id="detailInfants" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="rounded-[1.75rem] bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-slate-900">Selected guests</p>
                                <span id="selectedGuestCount" class="text-slate-500 text-xs">3 guests</span>
                            </div>
<p class="mt-2" id="inventorySummaryText">
                                    @if(isset($checkIn) && isset($checkOut))
                                        {{ $hotel->roomTypes->where('status','Active')->sum(fn($roomType) => $roomTypeAvailabilities[$roomType->id]['available_rooms'] ?? 0) }} rooms available across selected room types.
                                    @else
                                        Select your stay dates to see live inventory availability for each room type.
                                    @endif
                                </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Meal plan</label>
                            <select name="meal_plan_id" id="detailMealPlan" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                @foreach($hotel->mealPlans as $mealPlan)
                                    <option value="{{ $mealPlan->id }}" data-price="{{ $mealPlan->price_per_person }}">{{ $mealPlan->meal_plan_name }} (SAR {{ number_format($mealPlan->price_per_person, 2) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            <input id="includeMeal" name="include_meal" type="checkbox" class="form-check-input mt-1 h-4 w-4 text-blue-600 border-slate-300 rounded" checked>
                            <label for="includeMeal" class="font-medium text-slate-700">Include meal for selected guests</label>
                        </div>

                        <div class="rounded-[1.75rem] bg-white p-5 border border-slate-200">
                            <p class="text-sm font-semibold text-slate-900 mb-3">Contact information</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="form-label text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Contact name</label>
                                    <input type="text" name="contact_name" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Lead guest name" required>
                                </div>
                                <div>
                                    <label class="form-label text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Email</label>
                                    <input type="email" name="contact_email" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Email address" required>
                                </div>
                                <div>
                                    <label class="form-label text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Phone</label>
                                    <input type="text" name="contact_phone" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Phone number" required>
                                </div>
                            </div>
                        </div>

                        <div id="guestNamesContainer" class="space-y-4 rounded-[1.75rem] border border-slate-200 bg-white p-4 hidden">
                            <p class="text-sm font-semibold text-slate-900">Passenger details</p>
                            <div id="adultNamesList" class="space-y-3"></div>
                            <div id="childNamesList" class="space-y-3"></div>
                            <div id="infantNamesList" class="space-y-3"></div>
                        </div>

                        <div class="rounded-[1.75rem] bg-blue-600 p-5 text-white">
                            <p class="text-xs uppercase tracking-[0.24em] font-semibold">Booking summary</p>
                            <div class="mt-4 space-y-3 text-sm">
                                <div class="flex items-center justify-between"><span>Room charge</span><span id="summaryRoomCharge">SAR {{ number_format($hotel->roomTypes->first()->daily_rate ?? 0, 2) }}</span></div>
                                <div class="flex items-center justify-between"><span>Meal plan</span><span id="summaryMealCharge">SAR {{ number_format($hotel->mealPlans->first()->price_per_person ?? 0, 2) }}</span></div>
                                <div class="flex items-center justify-between"><span>Taxes & fees</span><span id="summaryTaxes">SAR 0.00</span></div>
                                <div class="border-t border-white/30 pt-3 flex items-center justify-between text-lg font-semibold"><span>Total</span><span id="summaryTotal">SAR {{ number_format(($hotel->roomTypes->first()->daily_rate ?? 0) + ($hotel->mealPlans->first()->price_per_person ?? 0), 2) }}</span></div>
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-3xl bg-blue-600 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Reserve now</button>
                        <button type="button" class="w-full rounded-3xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50 transition">Contact support</button>
                    </form>
                </div>
            </aside>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkIn = flatpickr('#detailCheckIn', { dateFormat: 'Y-m-d', minDate: 'today', onChange: updateSummary });
            const checkOut = flatpickr('#detailCheckOut', { dateFormat: 'Y-m-d', minDate: 'today', onChange: updateSummary });
            const roomSelect = document.getElementById('detailRoomType');
            const mealSelect = document.getElementById('detailMealPlan');
            const adults = document.getElementById('detailAdults');
            const children = document.getElementById('detailChildren');
            const infants = document.getElementById('detailInfants');
            const includeMeal = document.getElementById('includeMeal');
            const childLimitMessage = document.getElementById('childLimitMessage');
            const selectedGuestCount = document.getElementById('selectedGuestCount');
            const guestNamesContainer = document.getElementById('guestNamesContainer');
            const adultNamesList = document.getElementById('adultNamesList');
            const childNamesList = document.getElementById('childNamesList');
            const infantNamesList = document.getElementById('infantNamesList');
            const summaryRoomCharge = document.getElementById('summaryRoomCharge');
            const summaryMealCharge = document.getElementById('summaryMealCharge');
            const summaryTaxes = document.getElementById('summaryTaxes');
            const summaryTotal = document.getElementById('summaryTotal');
            const roomAvailabilityText = document.getElementById('roomAvailabilityText');
            const inventorySummaryText = document.getElementById('inventorySummaryText');
            const reserveButton = document.querySelector('button[type="submit"]');

            function getSelectedRoomRate() {
                return parseFloat(roomSelect.selectedOptions[0].dataset.rate || 0);
            }

            function getSelectedMealRate() {
                return parseFloat(mealSelect.selectedOptions[0].dataset.price || 0);
            }

            function updateSummary() {
                const nights = calculateNights();
                const roomRate = getSelectedRoomRate();
                const mealRate = getSelectedMealRate();
                const totalGuests = getTotalGuests();
                const roomCharge = roomRate * Math.max(nights, 1);
                const mealCharge = includeMeal.checked ? mealRate * totalGuests : 0;
                const taxes = parseFloat((roomCharge + mealCharge) * 0.10).toFixed(2);
                const total = (roomCharge + mealCharge + parseFloat(taxes)).toFixed(2);
                const availability = parseInt(roomSelect.selectedOptions[0].dataset.available || '0', 10);
                const status = roomSelect.selectedOptions[0].dataset.status || 'Select dates';

                summaryRoomCharge.textContent = `SAR ${roomCharge.toFixed(2)}`;
                summaryMealCharge.textContent = `SAR ${mealCharge.toFixed(2)}`;
                summaryTaxes.textContent = `SAR ${taxes}`;
                summaryTotal.textContent = `SAR ${total}`;
                selectedGuestCount.textContent = `${totalGuests} guest${totalGuests === 1 ? '' : 's'}`;
                renderGuestFields();

                if (availability > 0) {
                    roomAvailabilityText.textContent = `${availability} room${availability === 1 ? '' : 's'} available for selected dates.`;
                    roomAvailabilityText.classList.remove('text-red-600');
                    roomAvailabilityText.classList.add('text-slate-500');
                    reserveButton.removeAttribute('disabled');
                } else {
                    roomAvailabilityText.textContent = status === 'Sold Out' ? 'Sold out for selected dates.' : 'Select stay dates to check availability.';
                    roomAvailabilityText.classList.remove('text-slate-500');
                    roomAvailabilityText.classList.add('text-red-600');
                    reserveButton.setAttribute('disabled', 'disabled');
                }

                const totalAvailable = Array.from(roomSelect.options).reduce((sum, option) => {
                    return sum + parseInt(option.dataset.available || '0', 10);
                }, 0);

                inventorySummaryText.textContent = totalAvailable > 0
                    ? `${totalAvailable} rooms available across selected room types.`
                    : 'No live availability data found for selected dates. Please adjust your dates.';
            }

            function calculateNights() {
                const start = checkIn.selectedDates[0];
                const end = checkOut.selectedDates[0];
                if (!start || !end) return 1;
                const diffTime = Math.max(0, end - start);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }

            function getTotalGuests() {
                return parseInt(adults.value, 10) + parseInt(children.value, 10) + parseInt(infants.value, 10);
            }

            function renderGuestFields() {
                const adultCount = parseInt(adults.value, 10);
                const childCount = parseInt(children.value, 10);
                const infantCount = parseInt(infants.value, 10);
                const showNames = includeMeal.checked;

                guestNamesContainer.classList.toggle('hidden', !showNames);
                adultNamesList.innerHTML = '';
                childNamesList.innerHTML = '';
                infantNamesList.innerHTML = '';

                if (!showNames) {
                    return;
                }

                let index = 0;

                for (let count = 1; count <= adultCount; count += 1) {
                    adultNamesList.insertAdjacentHTML('beforeend', `
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Adult ${count} full name</label>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Adult">
                            <input type="text" name="passengers[${index}][first_name]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Enter first name" required>
                            <input type="text" name="passengers[${index}][last_name]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Enter last name">
                            <input type="number" name="passengers[${index}][age]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Age" min="18" required>
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= childCount; count += 1) {
                    childNamesList.insertAdjacentHTML('beforeend', `
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Child ${count} name</label>
                                <input type="hidden" name="passengers[${index}][passenger_type]" value="Child">
                                <input type="text" name="passengers[${index}][first_name]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Child full name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Child ${count} age</label>
                                <input type="number" name="passengers[${index}][age]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Age" min="2" required>
                            </div>
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= infantCount; count += 1) {
                    infantNamesList.insertAdjacentHTML('beforeend', `
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Infant ${count} full name</label>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Infant">
                            <input type="text" name="passengers[${index}][first_name]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Infant full name" required>
                            <input type="number" name="passengers[${index}][age]" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Age" min="0" max="2" required>
                        </div>
                    `);
                    index += 1;
                }
            }

            function enforceChildLimit() {
                const childCount = parseInt(children.value, 10);
                if (childCount > 5) {
                    childLimitMessage.classList.remove('hidden');
                    window.alert('More than 5 children requires booking a second room. Please adjust your selection.');
                    children.value = '5';
                    childLimitMessage.textContent = 'Maximum 5 children allowed. Book 2 rooms for larger groups.';
                    return true;
                }

                childLimitMessage.classList.add('hidden');
                childLimitMessage.textContent = 'For more than 5 children, please book 2 rooms.';
                return false;
            }

            roomSelect.addEventListener('change', updateSummary);
            includeMeal.addEventListener('change', updateSummary);
            mealSelect.addEventListener('change', updateSummary);
            adults.addEventListener('change', updateSummary);
            children.addEventListener('change', () => {
                if (!enforceChildLimit()) {
                    updateSummary();
                }
            });
            infants.addEventListener('change', updateSummary);

            updateSummary();
        });
    </script>
</body>
</html>
