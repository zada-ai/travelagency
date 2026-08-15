<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $hotel->hotel_name }} | Umrah Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .form-section { border-radius: 1.75rem; border: 1px solid #e2e8f0; background: #ffffff; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-6xl mx-auto space-y-8">
            <div class="rounded-[2rem] bg-white p-8 shadow-xl">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-blue-600 font-semibold">Hotel booking</p>
                        <h1 class="mt-3 text-4xl font-bold text-slate-900">Book {{ $hotel->hotel_name }}</h1>
                        <p class="mt-2 text-sm text-slate-600">Complete your reservation details on this dedicated booking page.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5 text-sm text-slate-600">
                        <p class="font-semibold text-slate-900">Hotel location</p>
                        <p class="mt-2">{{ $hotel->city }}</p>
                        <p class="mt-1">{{ $hotel->address }}</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="rounded-[1.75rem] bg-red-50 border border-red-200 p-6 text-sm text-red-700">
                    <p class="font-semibold mb-3">Please fix the following issues:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('hotels.book.review') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Stay information</p>
                            @php $activeRoomTypes = $hotel->roomTypes->where('status', 'Active'); @endphp
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Room type</label>
                                    @if ($activeRoomTypes->isEmpty())
                                        <div class="rounded-3xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">No active room types available for this hotel.</div>
                                    @else
                                        <select name="hotel_room_type_id" class="form-select w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" required>
                                            @foreach($activeRoomTypes as $roomType)
                                                <option value="{{ $roomType->id }}" data-rate="{{ $roomType->daily_rate }}" {{ old('hotel_room_type_id', $activeRoomTypes->first()?->id) == $roomType->id ? 'selected' : '' }}>
                                                    {{ $roomType->room_name }} · SAR {{ number_format($roomType->daily_rate, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">City</label>
                                    <input type="text" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" value="{{ $hotel->city }}" readonly>
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2 mt-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Check-in</label>
                                    <input type="text" id="bookingCheckIn" name="check_in" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="YYYY-MM-DD" value="{{ old('check_in') }}" required readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Check-out</label>
                                    <input type="text" id="bookingCheckOut" name="check_out" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="YYYY-MM-DD" value="{{ old('check_out') }}" required readonly>
                                </div>
                            </div>
                            <div class="mt-4 rounded-3xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-700">
                                <p class="font-medium">Booking summary</p>
                                <p class="mt-2 text-sm text-slate-600">Nights: <span id="bookingNights">0</span></p>
                                <p class="text-sm text-slate-600">Estimated total: <span id="bookingTotalPrice">SAR 0.00</span></p>
                                <p id="bookingStatusMessage" class="mt-3 text-sm font-semibold text-slate-700"></p>
                            </div>
                            <input type="hidden" id="bookingNightsField" name="nights" value="{{ old('nights', 0) }}">
                            <input type="hidden" id="bookingTotalPriceField" name="estimated_total_price" value="{{ old('estimated_total_price', '0.00') }}">
                        </div>
                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Guest counts</p>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Adults</label>
                                    <select name="adults" id="bookingAdults" class="form-select w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" required>
                                        @for ($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('adults', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Children</label>
                                    <select name="children" id="bookingChildren" class="form-select w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" required>
                                        @for ($i = 0; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('children', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Infants</label>
                                    <select name="infants" id="bookingInfants" class="form-select w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" required>
                                        @for ($i = 0; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('infants', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Meal plan</p>
                            <div class="grid gap-4 sm:grid-cols-2 items-end">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Meal plan</label>
                                    <select name="meal_plan_id" id="bookingMealPlan" class="form-select w-full rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                        @foreach ($hotel->mealPlans as $mealPlan)
                                            <option value="{{ $mealPlan->id }}" {{ old('meal_plan_id', $hotel->mealPlans->first()?->id) == $mealPlan->id ? 'selected' : '' }}>
                                                {{ $mealPlan->meal_plan_name }} (SAR {{ number_format($mealPlan->price_per_person, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                    <input id="bookingIncludeMeal" name="include_meal" type="checkbox" class="form-check-input h-4 w-4 text-blue-600 rounded" {{ old('include_meal', true) ? 'checked' : '' }}>
                                    <label for="bookingIncludeMeal" class="text-sm font-medium text-slate-700">Include meal for selected guests</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Add-on services</p>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 cursor-pointer">
                                    <input id="bookingIncludeVisa" name="include_visa" type="checkbox" class="form-check-input h-4 w-4 text-blue-600 rounded" {{ old('include_visa') ? 'checked' : '' }}>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Visa processing</p>
                                        <p class="text-xs text-slate-500">
                                            {{ $visaType ? $visaType->name . ' - SAR ' . number_format($visaType->total_cost, 2) . ' per booking' : 'Fixed SAR 1400 per booking' }}
                                        </p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-4 cursor-pointer">
                                    <input id="bookingIncludeTransport" name="include_transport" type="checkbox" class="form-check-input h-4 w-4 text-blue-600 rounded" {{ old('include_transport') ? 'checked' : '' }}>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Transport service</p>
                                        <p class="text-xs text-slate-500">Adults SAR 520, Children SAR 600, Infants SAR 520</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Contact information</p>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Contact name</label>
                                    <input type="text" name="contact_name" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Lead guest name" value="{{ old('contact_name') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Email</label>
                                    <input type="email" name="contact_email" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Email address" value="{{ old('contact_email') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Phone</label>
                                    <input type="text" name="contact_phone" class="form-control w-full rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Phone number" value="{{ old('contact_phone') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section p-6">
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold mb-4">Passenger details</p>
                            <p class="text-sm text-slate-600 mb-4">Passenger details are required and must match the selected guest counts.</p>
                            <div id="passengerFields" class="space-y-6"></div>
                        </div>

                        <button type="submit" class="w-full rounded-3xl bg-blue-600 py-4 text-sm font-semibold text-white hover:bg-blue-700 transition">Book Now</button>
                    </form>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[1.75rem] bg-white border border-slate-200 p-6 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold">Hotel</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">{{ $hotel->hotel_name }}</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $hotel->city }}</p>
                        <p class="mt-4 text-sm text-slate-500">{{ $hotel->description ?? 'A premium hotel with comfortable rooms and great location.' }}</p>
                    </div>

                    <div class="rounded-[1.75rem] bg-slate-50 border border-slate-200 p-6">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold">Room options</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-700">
                            @foreach ($hotel->roomTypes->where('status', 'Active') as $roomType)
                                <div class="rounded-3xl bg-white border border-slate-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $roomType->room_name }}</span>
                                        <span class="font-semibold">SAR {{ number_format($roomType->daily_rate, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomTypeDateRanges = @json($roomTypeDateRanges ?? []);
            const roomTypeAvailabilities = @json($roomTypeAvailabilities ?? []);
            const roomTypeSelector = document.querySelector('select[name="hotel_room_type_id"]');
            const checkInInput = document.getElementById('bookingCheckIn');
            const checkOutInput = document.getElementById('bookingCheckOut');
            const bookingSubmit = document.querySelector('button[type="submit"]');
            const availabilityEndpoint = '{{ route('hotels.availability') }}';
            const bookingStatus = document.getElementById('bookingStatusMessage');

            const adults = document.getElementById('bookingAdults');
            const children = document.getElementById('bookingChildren');
            const infants = document.getElementById('bookingInfants');
            const passengerFields = document.getElementById('passengerFields');
            const oldPassengers = @json(old('passengers', []));

            function parseDate(value) {
                return value ? new Date(value) : null;
            }

            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function addDays(date, days) {
                const result = new Date(date);
                result.setDate(result.getDate() + days);
                return result;
            }

            function getRoomTypeBounds() {
                const roomTypeId = roomTypeSelector?.value;
                return roomTypeDateRanges[roomTypeId] ?? null;
            }

            function getCheckInMinDate() {
                const bounds = getRoomTypeBounds();
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (!bounds) {
                    return today;
                }

                const minDate = parseDate(bounds.min_date);
                return minDate && minDate > today ? minDate : today;
            }

            function getCheckInMaxDate() {
                const bounds = getRoomTypeBounds();
                if (!bounds) {
                    return null;
                }

                return parseDate(bounds.max_date);
            }

            function getCheckOutMaxDate() {
                const bounds = getRoomTypeBounds();
                if (!bounds) {
                    return null;
                }

                const maxDate = parseDate(bounds.max_date);
                return maxDate ? addDays(maxDate, 1) : null;
            }

            function getCheckOutMinDate() {
                const selectedCheckIn = checkIn?.selectedDates[0];
                if (selectedCheckIn) {
                    return addDays(selectedCheckIn, 1);
                }

                return addDays(getCheckInMinDate(), 1);
            }

            function updateDatePickerBounds() {
                const minCheckIn = getCheckInMinDate();
                const maxCheckIn = getCheckInMaxDate();
                const maxCheckOut = getCheckOutMaxDate();
                const selectedCheckIn = checkIn?.selectedDates[0] || minCheckIn;
                const minCheckOut = getCheckOutMinDate();

                checkIn.set('minDate', minCheckIn);
                checkIn.set('maxDate', maxCheckIn || null);

                checkOut.set('minDate', minCheckOut);
                checkOut.set('maxDate', maxCheckOut || null);

                if (checkIn.selectedDates[0] && (checkIn.selectedDates[0] < minCheckIn || (maxCheckIn && checkIn.selectedDates[0] > maxCheckIn))) {
                    checkIn.clear();
                    checkOut.clear();
                }

                const selectedCheckOut = checkOut.selectedDates[0];
                if (selectedCheckOut && maxCheckOut && selectedCheckOut > maxCheckOut) {
                    checkOut.clear();
                }

                if (minCheckOut && maxCheckOut && minCheckOut > maxCheckOut) {
                    if (bookingStatus) {
                        bookingStatus.textContent = 'No checkout date is available for this check-in and room type. Please select a different room or date.';
                        bookingStatus.className = 'mt-3 text-sm font-semibold text-rose-700';
                    }
                    if (bookingSubmit) {
                        bookingSubmit.disabled = true;
                    }
                }

                calculateNightsAndPrice();
            }

            const checkIn = flatpickr('#bookingCheckIn', {
                dateFormat: 'Y-m-d',
                defaultDate: checkInInput.value || null,
                disable: [
                    (date) => {
                        const minDate = getCheckInMinDate();
                        const maxDate = getCheckInMaxDate();
                        return date < minDate || (maxDate && date > maxDate);
                    },
                ],
                onChange: (selectedDates) => {
                    if (selectedDates.length > 0) {
                        const nextDay = addDays(selectedDates[0], 1);
                        checkOut.set('minDate', nextDay);
                        checkOut.set('maxDate', getCheckOutMaxDate() || null);
                        if (checkOut.selectedDates.length && checkOut.selectedDates[0] <= selectedDates[0]) {
                            checkOut.clear();
                        }
                        calculateNightsAndPrice();
                        fetchAvailability();
                    }
                },
            });

            const checkOut = flatpickr('#bookingCheckOut', {
                dateFormat: 'Y-m-d',
                defaultDate: checkOutInput.value || null,
                disable: [
                    (date) => {
                        const minDate = getCheckOutMinDate();
                        const maxDate = getCheckOutMaxDate();
                        return date < minDate || (maxDate && date > maxDate);
                    },
                ],
                onChange: () => {
                    calculateNightsAndPrice();
                    fetchAvailability();
                },
                allowInput: true,
            });

            if (roomTypeSelector) {
                roomTypeSelector.addEventListener('change', () => {
                    updateDatePickerBounds();
                    calculateNightsAndPrice();
                });
            }

            function getRoomTypeRate() {
                const selectedOption = roomTypeSelector?.selectedOptions?.[0];
                return selectedOption ? parseFloat(selectedOption.dataset.rate || '0') : 0;
            }

            function getSelectedRoomTypeAvailability() {
                const roomTypeId = roomTypeSelector?.value;
                return roomTypeAvailabilities[roomTypeId] ?? null;
            }

            function updateBookingStatus() {
                if (!bookingStatus) {
                    return;
                }

                const checkInDate = checkIn.selectedDates[0];
                const checkOutDate = checkOut.selectedDates[0];
                const availability = getSelectedRoomTypeAvailability();

                if (!checkInDate || !checkOutDate) {
                    bookingStatus.textContent = 'Select check-in and check-out dates to verify availability.';
                    bookingStatus.className = 'mt-3 text-sm text-slate-500';
                    if (bookingSubmit) bookingSubmit.disabled = false;
                    return;
                }

                if (!availability) {
                    bookingStatus.textContent = 'Checking availability for selected dates...';
                    bookingStatus.className = 'mt-3 text-sm text-slate-500';
                    if (bookingSubmit) bookingSubmit.disabled = true;
                    return;
                }

                if (availability.available_rooms > 0) {
                    bookingStatus.textContent = `Room available for selected dates (remaining: ${availability.available_rooms}).`;
                    bookingStatus.className = 'mt-3 text-sm font-semibold text-emerald-700';
                    if (bookingSubmit) bookingSubmit.disabled = false;
                } else {
                    bookingStatus.textContent = 'No rooms available for this room type on the selected dates.';
                    bookingStatus.className = 'mt-3 text-sm font-semibold text-rose-700';
                    if (bookingSubmit) bookingSubmit.disabled = true;
                }
            }

            async function fetchAvailability() {
                const checkInDate = checkIn.selectedDates[0];
                const checkOutDate = checkOut.selectedDates[0];
                if (!checkInDate || !checkOutDate || !roomTypeSelector) {
                    return;
                }

                const params = new URLSearchParams({
                    hotel_id: '{{ $hotel->id }}',
                    check_in: formatDate(checkInDate),
                    check_out: formatDate(checkOutDate),
                    adults: adults.value,
                    children: children.value,
                    infants: infants.value,
                });

                try {
                    const response = await fetch(`${availabilityEndpoint}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Unable to load availability data.');
                    }

                    const json = await response.json();
                    if (Array.isArray(json.roomTypes)) {
                        json.roomTypes.forEach((roomType) => {
                            roomTypeAvailabilities[roomType.id] = roomType;
                        });
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    updateBookingStatus();
                }
            }

            function calculateNightsAndPrice() {
                const checkInDate = checkIn.selectedDates[0];
                const checkOutDate = checkOut.selectedDates[0];
                const nightsField = document.getElementById('bookingNightsField');
                const totalPriceField = document.getElementById('bookingTotalPriceField');
                const nightsDisplay = document.getElementById('bookingNights');
                const totalPriceDisplay = document.getElementById('bookingTotalPrice');

                if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
                    const diffTime = checkOutDate.getTime() - checkInDate.getTime();
                    const nights = Math.round(diffTime / (1000 * 60 * 60 * 24));
                    const rate = getRoomTypeRate();
                    const total = rate * nights;

                    if (nightsField) {
                        nightsField.value = nights;
                    }
                    if (totalPriceField) {
                        totalPriceField.value = total.toFixed(2);
                    }
                    if (nightsDisplay) {
                        nightsDisplay.textContent = nights;
                    }
                    if (totalPriceDisplay) {
                        totalPriceDisplay.textContent = `SAR ${total.toFixed(2)}`;
                    }
                    updateBookingStatus();
                    return;
                }

                if (nightsField) {
                    nightsField.value = 0;
                }
                if (totalPriceField) {
                    totalPriceField.value = '0.00';
                }
                if (nightsDisplay) {
                    nightsDisplay.textContent = '0';
                }
                if (totalPriceDisplay) {
                    totalPriceDisplay.textContent = 'SAR 0.00';
                }
                updateBookingStatus();
            }

            if (roomTypeSelector) {
                roomTypeSelector.addEventListener('change', () => {
                    updateDatePickerBounds();
                    calculateNightsAndPrice();
                    fetchAvailability();
                });
            }

            const availabilityTriggers = [checkIn, checkOut];
            availabilityTriggers.forEach((picker) => {
                picker.config.onChange.push(() => {
                    calculateNightsAndPrice();
                    fetchAvailability();
                });
            });

            updateDatePickerBounds();
            calculateNightsAndPrice();
            fetchAvailability();

            function createPassengerBlock(type, count, index) {
                return `
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="font-semibold text-slate-900">${type} ${count}</p>
                            <span class="text-xs text-slate-500">${type}</span>
                        </div>
                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="lg:col-span-2">
                                <label class="block text-xs uppercase tracking-[0.24em] text-slate-500 mb-2">Full name</label>
                                <input type="text" name="passengers[${index}][full_name]" class="form-control w-full rounded-3xl border-slate-200 bg-white text-sm p-3" placeholder="Enter full name" required>
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-[0.24em] text-slate-500 mb-2">Date of birth</label>
                                <input type="text" name="passengers[${index}][date_of_birth]" class="form-control w-full rounded-3xl border-slate-200 bg-white text-sm p-3 passenger-dob" placeholder="YYYY-MM-DD" required readonly>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-xs uppercase tracking-[0.24em] text-slate-500 mb-2">Passport / Document <span class="text-rose-500">*</span> (PDF, PNG, JPG)</label>
                                <input type="file" name="passengers[${index}][passport_document]" class="form-control w-full rounded-3xl border-slate-200 bg-white text-sm p-3" accept=".pdf,.png,.jpg,.jpeg,.gif" required>
                                <p class="text-xs text-slate-400 mt-1">Supported formats: PDF, PNG, JPG, JPEG, GIF (Max 5MB)</p>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-xs uppercase tracking-[0.24em] text-slate-500 mb-2">CNIC / ID Card <span class="text-rose-500">*</span> (PDF, PNG, JPG)</label>
                                <input type="file" name="passengers[${index}][cnic_document]" class="form-control w-full rounded-3xl border-slate-200 bg-white text-sm p-3" accept=".pdf,.png,.jpg,.jpeg,.gif" required>
                                <p class="text-xs text-slate-400 mt-1">Supported formats: PDF, PNG, JPG, JPEG, GIF (Max 5MB)</p>
                            </div>
                        </div>
                        <input type="hidden" name="passengers[${index}][passenger_type]" value="${type}">
                    </div>
                `;
            }

            function renderPassengerFields() {
                const adultCount = parseInt(adults.value, 10);
                const childCount = parseInt(children.value, 10);
                const infantCount = parseInt(infants.value, 10);
                let index = 0;
                passengerFields.innerHTML = '';

                for (let count = 1; count <= adultCount; count += 1) {
                    passengerFields.insertAdjacentHTML('beforeend', createPassengerBlock('Adult', count, index));
                    index += 1;
                }
                for (let count = 1; count <= childCount; count += 1) {
                    passengerFields.insertAdjacentHTML('beforeend', createPassengerBlock('Child', count, index));
                    index += 1;
                }
                for (let count = 1; count <= infantCount; count += 1) {
                    passengerFields.insertAdjacentHTML('beforeend', createPassengerBlock('Infant', count, index));
                    index += 1;
                }

                setupPassengerPickers();
                populateOldPassengerValues();
            }

            function setupPassengerPickers() {
                document.querySelectorAll('.passenger-dob').forEach((element) => {
                    if (!element._flatpickr) {
                        flatpickr(element, { dateFormat: 'Y-m-d', maxDate: 'today' });
                    }
                });
            }

            function populateOldPassengerValues() {
                if (!oldPassengers.length) {
                    return;
                }

                oldPassengers.forEach((passenger, index) => {
                    if (!passenger) {
                        return;
                    }

                    Object.entries(passenger).forEach(([field, value]) => {
                        const input = document.querySelector(`[name="passengers[${index}][${field}]"]`);
                        if (input && value !== undefined && value !== null) {
                            input.value = value;
                        }
                    });
                });
            }

            adults.addEventListener('change', renderPassengerFields);
            children.addEventListener('change', renderPassengerFields);
            infants.addEventListener('change', renderPassengerFields);
            renderPassengerFields();
        });
    </script>
</body>
</html>
