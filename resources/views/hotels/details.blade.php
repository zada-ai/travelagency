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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #0f172a; }
        .hero-slider { min-height: 620px; position: relative; }
        .hero-slide { min-height: 620px; position: relative; overflow: hidden; }
        .hero-slide img { width: 100%; height: 100%; object-fit: cover; }
        .hero-slide::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15,23,42,0.1), rgba(15,23,42,0.75)); }
        .hero-no-images { min-height: 620px; background: linear-gradient(135deg, #0f172a, #1e293b); display: flex; align-items: center; justify-content: center; }
        .hero-no-images h2 { color: #fff; font-size: 2rem; text-align: center; }
        .hero-overlay { position: absolute; inset: 0; z-index: 10; display: flex; align-items: flex-end; }
        .sticky-booking { position: sticky; top: 24px; }
        .hotel-badge { background: rgba(255,255,255,.92); backdrop-filter: blur(12px); }
        .drawer-shadow { box-shadow: 0 24px 64px rgba(15,23,42,.08); }
        .swiper-button-next,
        .swiper-button-prev { color: #ffffff; }
        .swiper-pagination-bullet { background: rgba(255,255,255,0.75); }
        .swiper-pagination-bullet-active { background: #ffffff; }
        .thumbnail-badge { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(15, 23, 42, .72); color: #ffffff; font-weight: 700; font-size: 1rem; }
        .thumbnail-item { position: relative; overflow: hidden; border-radius: 1.5rem; }
        .thumbnail-item img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body>
    <header class="relative">
        @if($hotel->images->isNotEmpty())
            <div class="hero-slider swiper w-full h-[350px] md:h-[450px] lg:h-[500px] rounded-3xl overflow-hidden shadow-xl">
                <div class="swiper-wrapper">
                    @foreach($hotel->images as $image)
                        <div class="swiper-slide">
                            <img
                                src="{{ Storage::disk('public')->url($image->path) }}"
                                alt="{{ $hotel->hotel_name }} image {{ $loop->iteration }}"
                                class="w-full h-full object-cover object-center"
                            >
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
        @else
            <div class="hero-no-images rounded-3xl shadow-xl">
                <h2>No hotel images available yet. Please upload from the Admin gallery.</h2>
            </div>
        @endif

        <div class="hero-overlay">
            <div class="container mx-auto px-4 pb-12">
                <div class="max-w-4xl rounded-[2rem] bg-white/10 p-8 backdrop-blur-xl border border-white/10 shadow-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs uppercase tracking-[0.24em] text-slate-100/90 mb-4">Premium hotel details</span>
                    <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">{{ $hotel->hotel_name }}</h1>
                    <p class="mt-4 text-base md:text-lg text-slate-200 max-w-2xl">{{ $hotel->city }} · {{ $hotel->category }} · {{ number_format($hotel->distance_from_haram, 0) }}m from Haram</p>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <section class="lg:col-span-8 space-y-8">
                @if($hotel->images->isNotEmpty())
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        @foreach($hotel->images->take(4) as $image)
                            <a href="{{ Storage::disk('public')->url($image->path) }}" class="thumbnail-item group overflow-hidden rounded-[2rem] shadow-xl bg-white" data-gallery="hotel-gallery">
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $hotel->hotel_name }} image {{ $loop->iteration }}" class="w-full h-[240px] object-cover transition duration-500 ease-in-out group-hover:scale-105">
                                @if($loop->last && $hotel->images->count() > 4)
                                    <div class="thumbnail-badge">+{{ $hotel->images->count() - 4 }} more</div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    @foreach($hotel->images->skip(4) as $image)
                        <a href="{{ Storage::disk('public')->url($image->path) }}" class="d-none glightbox" data-gallery="hotel-gallery"></a>
                    @endforeach
                @else
                    <div class="rounded-[2rem] overflow-hidden shadow-xl bg-white">
                        <div class="hero-no-images rounded-[2rem] shadow-xl p-10">
                            <h2>No hotel images available yet. Please upload from the Admin gallery.</h2>
                        </div>
                    </div>
                @endif

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
                                    <p class="font-semibold text-slate-900">Live room availability</p>
                                    <p class="mt-1">{{ $hotel->roomTypes->count() }} room categories ready to reserve with real-time availability.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-blue-600 text-lg mt-1"><i class="bi bi-check2-circle"></i></span>
                                <div>
                                    <p class="font-semibold text-slate-900">Current availability</p>
                                    <p class="mt-1">{{ $availableRoomsNow }} rooms currently available across the property.</p>
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
                                        @php
                                            $typeAvailableRooms = $roomTypeAvailabilities[$roomType->id]['available_rooms'] ?? $roomType->hotelRooms->where('status', 'Available')->count();
                                        @endphp
                                        <p class="mt-2 text-sm {{ isset($checkIn) && isset($checkOut) ? ($typeAvailableRooms > 0 ? 'text-emerald-600' : 'text-rose-600') : 'text-slate-500' }}" data-room-availability="{{ $roomType->id }}">
                                            @if(isset($checkIn) && isset($checkOut))
                                                {{ $typeAvailableRooms > 0 ? $typeAvailableRooms . ' room' . ($typeAvailableRooms === 1 ? '' : 's') . ' available for your stay' : 'Sold out for selected dates' }}
                                            @else
                                                Select your stay dates to see live availability.
                                            @endif
                                        </p>
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

                                @if($roomType->hotelRooms->isNotEmpty())
                                    <div class="mt-5 rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                        <p class="font-semibold text-slate-900 mb-2">Rooms from admin panel</p>
                                        <p class="leading-6">
                                            {{ $roomType->hotelRooms->pluck('room_number')->join(', ') }}
                                        </p>
                                    </div>
                                @endif
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

                    <form id="hotelBookingForm" action="{{ route('hotels.book') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                        <div id="bookingErrorContainer" class="hidden rounded-3xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                            <p class="font-semibold mb-2">Please fix the following issues:</p>
                            <ul id="bookingErrorList" class="list-disc list-inside space-y-1"></ul>
                        </div>

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
                                    <option value="{{ $roomType->id }}" data-rate="{{ $roomType->daily_rate }}" data-capacity="{{ $roomType->max_occupancy }}" data-available="0" data-status="Select your dates to check availability">
                                        {{ $roomType->room_name }} · SAR {{ number_format($roomType->daily_rate, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_room_type_id')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
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
                                <input type="text" id="detailCheckIn" name="check_in" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Select date" value="{{ old('check_in', optional($checkIn)->format('Y-m-d')) }}" readonly required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Check-out</label>
                                <input type="text" id="detailCheckOut" name="check_out" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Select date" value="{{ old('check_out', optional($checkOut)->format('Y-m-d')) }}" readonly required>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Adults</label>
                                <select name="adults" id="detailAdults" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ old('adults', 2) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('adults')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Children</label>
                                <select name="children" id="detailChildren" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 0; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ old('children', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('children')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <p id="childLimitMessage" class="mt-2 text-xs text-red-600 hidden">For more than 5 children, please book 2 rooms.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Infants</label>
                                <select name="infants" id="detailInfants" class="form-select rounded-3xl border-slate-200 bg-slate-50 text-sm">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('infants', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('infants')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
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
                                    Select your stay dates to see live room availability for each room type.
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
                            @error('meal_plan_id')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
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
                                    @error('contact_name')
                                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Email</label>
                                    <input type="email" name="contact_email" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Email address" required>
                                    @error('contact_email')
                                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Phone</label>
                                    <input type="text" name="contact_phone" class="form-control rounded-3xl border-slate-200 bg-slate-50 text-sm" placeholder="Phone number" required>
                                    @error('contact_phone')
                                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="guestNamesContainer" class="space-y-4 rounded-[1.75rem] border border-slate-200 bg-white p-4 hidden">
                            <p class="text-sm font-semibold text-slate-900">Passenger details</p>
                            <div id="adultNamesList" class="space-y-4"></div>
                            <div id="childNamesList" class="space-y-4"></div>
                            <div id="infantNamesList" class="space-y-4"></div>
                        </div>

                        <div class="rounded-[1.75rem] bg-blue-600 p-5 text-white">
                            <p class="text-xs uppercase tracking-[0.24em] font-semibold">Booking summary</p>
                            <div class="mt-4 space-y-3 text-sm">
                                <div class="flex items-center justify-between"><span>Room charge</span><span id="summaryRoomCharge">SAR {{ number_format($hotel->roomTypes->first()->daily_rate ?? 0, 2) }}</span></div>
                                <div class="flex items-center justify-between"><span>Meal plan</span><span id="summaryMealCharge">SAR {{ number_format($hotel->mealPlans->first()->price_per_person ?? 0, 2) }}</span></div>
                                <div class="flex items-center justify-between"><span>Taxes & fees</span><span id="summaryTaxes">SAR 0.00</span></div>
                                <div class="border-t border-white/30 pt-3 flex items-center justify-between text-lg font-semibold"><span>Total</span><span id="summaryTotal">SAR {{ number_format(($hotel->roomTypes->first()->daily_rate ?? 0) + ($hotel->mealPlans->first()->price_per_person ?? 0), 2) }}</span></div>
                                <div class="mt-2 text-sm flex items-center justify-between"><span class="text-white/80">Total in PKR</span><span id="summaryTotalPKR" class="text-white/80">PKR 0.00</span></div>
                            </div>
                        </div>

                        <button type="submit" id="reserveSubmitButton" class="w-full rounded-3xl bg-blue-600 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <span id="reserveButtonSpinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                            <span id="reserveButtonText">Reserve now</span>
                        </button>
                        <button type="button" class="w-full rounded-3xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50 transition">
                        <a  href="https://wa.me/923001234567"
   target="_blank">     
                        Contact support </a></button>
                    </form>
                </div>
            </aside>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@14/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heroSliderEl = document.querySelector('.hero-slider');

            if (heroSliderEl) {
                new Swiper(heroSliderEl, {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },
                });
            }

            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                openEffect: 'fade',
                closeEffect: 'fade',
                zoomable: true,
            });

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
            const summaryTotalPKR = document.getElementById('summaryTotalPKR');
            const roomAvailabilityText = document.getElementById('roomAvailabilityText');
            const inventorySummaryText = document.getElementById('inventorySummaryText');
            const bookingErrorContainer = document.getElementById('bookingErrorContainer');
            const bookingErrorList = document.getElementById('bookingErrorList');
            const reserveButton = document.getElementById('reserveSubmitButton');
            const bookingForm = document.getElementById('hotelBookingForm');
            const reserveButtonText = document.getElementById('reserveButtonText');
            const reserveButtonSpinner = document.getElementById('reserveButtonSpinner');
            const oldPassengers = @json(old('passengers', []));
            const initialRoomTypes = @json($initialRoomTypes);
            let availabilityLoading = false;
            let liveRoomTypes = initialRoomTypes;

            function getSelectedRoomRate() {
                return parseFloat(roomSelect.selectedOptions[0].dataset.rate || 0);
            }

            function getSelectedMealRate() {
                return parseFloat(mealSelect.selectedOptions[0].dataset.price || 0);
            }

            function updateRoomTypeCardAvailability() {
                const selectedStartDate = checkIn.input.value;
                const selectedEndDate = checkOut.input.value;
                const roomCards = document.querySelectorAll('[data-room-availability]');

                roomCards.forEach((card) => {
                    const roomTypeId = parseInt(card.dataset.roomAvailability, 10);
                    const roomType = liveRoomTypes.find((type) => type.id === roomTypeId);

                    if (!selectedStartDate || !selectedEndDate) {
                        card.textContent = 'Select your stay dates to see live availability.';
                        card.classList.remove('text-emerald-600', 'text-rose-600');
                        card.classList.add('text-slate-500');
                        return;
                    }

                    if (!roomType) {
                        card.textContent = 'Sold out for selected dates';
                        card.classList.remove('text-slate-500', 'text-emerald-600');
                        card.classList.add('text-rose-600');
                        return;
                    }

                    const availableRooms = parseInt(roomType.available_rooms || '0', 10);
                    if (availableRooms > 0) {
                        card.textContent = `${availableRooms} room${availableRooms === 1 ? '' : 's'} available for your stay`;
                        card.classList.remove('text-slate-500', 'text-rose-600');
                        card.classList.add('text-emerald-600');
                    } else {
                        card.textContent = 'Sold out for selected dates';
                        card.classList.remove('text-slate-500', 'text-emerald-600');
                        card.classList.add('text-rose-600');
                    }
                });
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
                const hasSelectedDates = Boolean(checkIn.input.value && checkOut.input.value);
                const hasValidDateRange = hasSelectedDates && nights > 0;
                const canReserve = !availabilityLoading && hasValidDateRange && availability > 0;

                summaryRoomCharge.textContent = `SAR ${roomCharge.toFixed(2)}`;
                summaryMealCharge.textContent = `SAR ${mealCharge.toFixed(2)}`;
                summaryTaxes.textContent = `SAR ${taxes}`;
                summaryTotal.textContent = `SAR ${total}`;
                summaryTotalPKR.textContent = `PKR ${(total * 83).toFixed(2)}`;
                selectedGuestCount.textContent = `${totalGuests} guest${totalGuests === 1 ? '' : 's'}`;
                renderGuestFields();
                updateRoomTypeCardAvailability();
                syncDisabledDatesForSelectedRoomType();

                if (!hasSelectedDates) {
                    roomAvailabilityText.textContent = 'Select stay dates to check availability.';
                    roomAvailabilityText.classList.remove('text-slate-500');
                    roomAvailabilityText.classList.add('text-red-600');
                    reserveButton.setAttribute('disabled', 'disabled');
                } else if (!hasValidDateRange) {
                    roomAvailabilityText.textContent = 'Please choose a checkout date after check-in.';
                    roomAvailabilityText.classList.remove('text-slate-500');
                    roomAvailabilityText.classList.add('text-red-600');
                    reserveButton.setAttribute('disabled', 'disabled');
                } else if (availability > 0) {
                    roomAvailabilityText.textContent = `${availability} room${availability === 1 ? '' : 's'} available for selected dates.`;
                    roomAvailabilityText.classList.remove('text-red-600');
                    roomAvailabilityText.classList.add('text-slate-500');
                    reserveButton.removeAttribute('disabled');
                } else if (availabilityLoading) {
                    roomAvailabilityText.textContent = 'Checking availability...';
                    roomAvailabilityText.classList.remove('text-red-600');
                    roomAvailabilityText.classList.add('text-slate-500');
                    reserveButton.setAttribute('disabled', 'disabled');
                } else {
                    roomAvailabilityText.textContent = status === 'Sold Out' ? 'Sold out for selected dates.' : 'Select stay dates to check availability.';
                    roomAvailabilityText.classList.remove('text-slate-500');
                    roomAvailabilityText.classList.add('text-red-600');
                    reserveButton.setAttribute('disabled', 'disabled');
                }

                reserveButton.disabled = !canReserve;

                const totalAvailable = Array.from(roomSelect.options).reduce((sum, option) => {
                    return sum + parseInt(option.dataset.available || '0', 10);
                }, 0);

                inventorySummaryText.textContent = totalAvailable > 0
                    ? `${totalAvailable} rooms available across selected room types.`
                    : availabilityLoading
                        ? 'Checking live availability for selected dates...'
                        : 'No live availability data found for selected dates. Please adjust your dates.';

                if (!availabilityLoading && totalAvailable === 0 && checkIn.selectedDates.length && checkOut.selectedDates.length) {
                    bookingErrorContainer.classList.remove('hidden');
                    bookingErrorList.innerHTML = '<li>No available rooms exist for the selected dates. Please change your dates or room type.</li>';
                } else {
                    bookingErrorContainer.classList.add('hidden');
                    bookingErrorList.innerHTML = '';
                }
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
                const totalGuests = getTotalGuests();
                const showNames = totalGuests > 0;

                guestNamesContainer.classList.toggle('hidden', !showNames);
                adultNamesList.innerHTML = '';
                childNamesList.innerHTML = '';
                infantNamesList.innerHTML = '';

                if (!showNames) {
                    return;
                }

                let index = 0;

                const fieldGroupMarkup = (type, count) => `
                    <div class="grid gap-4 lg:grid-cols-2 rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 mb-2">${type} ${count}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">First Name</label>
                                <input type="text" name="passengers[${index}][first_name]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="First name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Last Name</label>
                                <input type="text" name="passengers[${index}][last_name]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Last name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Nationality</label>
                                <input type="text" name="passengers[${index}][nationality]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Nationality" required>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Date of Birth</label>
                                <input type="text" name="passengers[${index}][date_of_birth]" class="form-control rounded-3xl border-slate-200 bg-white text-sm passenger-dob" placeholder="YYYY-MM-DD" required readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Passport Number</label>
                                <input type="text" name="passengers[${index}][passport_number]" class="form-control rounded-3xl border-slate-200 bg-white text-sm" placeholder="Passport number" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">Passport Expiry</label>
                                <input type="text" name="passengers[${index}][passport_expiry]" class="form-control rounded-3xl border-slate-200 bg-white text-sm passenger-expiry" placeholder="YYYY-MM-DD" required readonly>
                            </div>
                        </div>
                    </div>
                `;

                for (let count = 1; count <= adultCount; count += 1) {
                    adultNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Adult">
                            ${fieldGroupMarkup('Adult', count)}
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= childCount; count += 1) {
                    childNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Child">
                            ${fieldGroupMarkup('Child', count)}
                        </div>
                    `);
                    index += 1;
                }

                for (let count = 1; count <= infantCount; count += 1) {
                    infantNamesList.insertAdjacentHTML('beforeend', `
                        <div>
                            <input type="hidden" name="passengers[${index}][passenger_type]" value="Infant">
                            ${fieldGroupMarkup('Infant', count)}
                        </div>
                    `);
                    index += 1;
                }

                setupPassengerDatepickers();
            }

            function setupPassengerDatepickers() {
                document.querySelectorAll('.passenger-dob').forEach((element) => {
                    if (!element._flatpickr) {
                        flatpickr(element, { dateFormat: 'Y-m-d', maxDate: 'today' });
                    }
                });
                document.querySelectorAll('.passenger-expiry').forEach((element) => {
                    if (!element._flatpickr) {
                        flatpickr(element, { dateFormat: 'Y-m-d', minDate: 'today' });
                    }
                });
            }

            function renderRoomTypeOptions(roomTypes, placeholderText = 'No rooms available for selected dates') {
                roomSelect.innerHTML = '';

                if (!roomTypes || roomTypes.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.disabled = true;
                    option.selected = true;
                    option.dataset.rate = 0;
                    option.dataset.capacity = 0;
                    option.dataset.available = 0;
                    option.dataset.status = placeholderText;
                    option.textContent = placeholderText;
                    roomSelect.appendChild(option);
                    roomSelect.disabled = true;
                    return;
                }

                roomSelect.disabled = false;

                roomTypes.forEach((roomType, index) => {
                    const option = document.createElement('option');
                    option.value = roomType.id;
                    option.dataset.rate = roomType.rate;
                    option.dataset.capacity = roomType.capacity;
                    option.dataset.available = roomType.available_rooms;
                    option.dataset.status = roomType.status;
                    option.dataset.unavailable = roomType.unavailable_dates ? roomType.unavailable_dates.join(',') : '';
                    option.textContent = `${roomType.room_name} · SAR ${Number(roomType.rate).toFixed(2)} / night`;

                    if (roomType.available_rooms === 0 && roomType.status !== 'Select your dates to check availability') {
                        option.disabled = true;
                    }

                    if (index === 0) {
                        option.selected = true;
                    }
                    roomSelect.appendChild(option);
                });
            }

            function buildRoomOptionsForDates(availableRoomTypes) {
                return initialRoomTypes.map((baseType) => {
                    const matching = availableRoomTypes.find((type) => type.id === baseType.id);

                    if (matching) {
                        return {
                            ...baseType,
                            available_rooms: matching.available_rooms,
                            status: matching.status || 'Available',
                            unavailable_dates: matching.unavailable_dates || [],
                        };
                    }

                    return {
                        ...baseType,
                        available_rooms: 0,
                        status: 'Sold out for selected dates',
                        unavailable_dates: [],
                    };
                });
            }

            function fetchAvailability() {
                const startDate = checkIn.input.value;
                const endDate = checkOut.input.value;

                if (!startDate || !endDate) {
                    availabilityLoading = false;
                    liveRoomTypes = initialRoomTypes;
                    renderRoomTypeOptions(initialRoomTypes, 'Select stay dates to see available room types');
                    updateSummary();
                    return;
                }

                availabilityLoading = true;
                renderRoomTypeOptions(initialRoomTypes, 'Checking availability...');
                updateSummary();

                fetch(`${window.location.pathname}?check_in=${encodeURIComponent(startDate)}&check_out=${encodeURIComponent(endDate)}&ajax=1`)
                    .then((response) => response.json())
                    .then((data) => {
                        availabilityLoading = false;
                        const mergedRoomTypes = buildRoomOptionsForDates(data.roomTypeAvailabilities || []);
                        liveRoomTypes = mergedRoomTypes;
                        renderRoomTypeOptions(mergedRoomTypes, 'No rooms available for selected dates');
                        syncDisabledDatesForSelectedRoomType();
                        updateSummary();
                    })
                    .catch(() => {
                        availabilityLoading = false;
                        const mergedRoomTypes = buildRoomOptionsForDates([]);
                        liveRoomTypes = mergedRoomTypes;
                        renderRoomTypeOptions(mergedRoomTypes, 'No rooms available for selected dates');
                        syncDisabledDatesForSelectedRoomType();
                        updateSummary();
                    });
            }

            function syncDisabledDatesForSelectedRoomType() {
                const selectedOption = roomSelect.selectedOptions[0];
                const unavailableString = selectedOption?.dataset?.unavailable || '';
                const disabledDates = unavailableString
                    .split(',')
                    .map((date) => date.trim())
                    .filter((date) => date.length > 0);

                checkIn.set('disable', disabledDates);
                checkOut.set('disable', disabledDates);
            }

            function handleBookingSubmit() {
                reserveButton.disabled = true;
                reserveButtonText.textContent = 'Submitting...';
                reserveButtonSpinner.classList.remove('hidden');
            }

            function populateOldPassengerValues() {
                if (!oldPassengers.length) {
                    return;
                }

                oldPassengers.forEach((passenger, index) => {
                    if (!passenger) {
                        return;
                    }

                    const setValue = (field, value) => {
                        const input = document.querySelector(`[name="passengers[${index}][${field}]"]`);
                        if (input && value !== undefined && value !== null) {
                            input.value = value;
                        }
                    };

                    setValue('first_name', passenger.first_name);
                    setValue('last_name', passenger.last_name);
                    setValue('nationality', passenger.nationality);
                    setValue('date_of_birth', passenger.date_of_birth);
                    setValue('passport_number', passenger.passport_number);
                    setValue('passport_expiry', passenger.passport_expiry);
                });
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
            checkIn.input.addEventListener('change', fetchAvailability);
            checkOut.input.addEventListener('change', fetchAvailability);
            bookingForm.addEventListener('submit', function (event) {
                const availability = parseInt(roomSelect.selectedOptions[0].dataset.available || '0', 10);
                const hasDates = checkIn.input.value && checkOut.input.value;

                if (!hasDates || availability <= 0) {
                    event.preventDefault();

                    if (!hasDates) {
                        bookingErrorContainer.classList.remove('hidden');
                        bookingErrorList.innerHTML = '<li>Please select valid check-in and check-out dates before reserving.</li>';
                        roomAvailabilityText.textContent = 'Select stay dates to check availability.';
                        roomAvailabilityText.classList.remove('text-slate-500');
                        roomAvailabilityText.classList.add('text-red-600');
                    } else {
                        bookingErrorContainer.classList.remove('hidden');
                        bookingErrorList.innerHTML = '<li>No available rooms exist for the selected dates. Please change your dates or room type.</li>';
                    }

                    return;
                }

                handleBookingSubmit();
            });

            fetchAvailability();
        });
    </script>
</body>
</html>
