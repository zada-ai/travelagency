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
                    <p class="text-sm leading-7 text-slate-600">{{ $hotel->about ?? $hotel->description ?? 'Stay in a premium hotel with prayer-friendly services, quick shuttle access to Al Haram, and modern guest rooms designed for peaceful spiritual retreats.' }}</p>

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

                    <div class="space-y-5">
                        <div class="rounded-[1.75rem] bg-slate-50 p-5 text-sm text-slate-600">
                            <p class="font-semibold text-slate-900">Hotel</p>
                            <p class="mt-2">{{ $hotel->hotel_name }}</p>
                            <p class="mt-1">{{ $hotel->city }}</p>
                        </div>

                            @php
                                $hotelBookingUrl = auth()->check() || auth()->guard('travel_agent')->check()
                                    ? route('hotels.booking.create', $hotel)
                                    : route('login');
                            @endphp

                            <a href="{{ $hotelBookingUrl }}" class="inline-flex w-full items-center justify-center rounded-3xl bg-blue-600 py-4 text-sm font-semibold text-white hover:bg-blue-700 transition">Book Now</a>

                            <a href="https://wa.me/923001234567" target="_blank" class="inline-flex w-full items-center justify-center rounded-3xl border border-slate-200 bg-white py-4 text-sm font-semibold text-slate-900 hover:bg-slate-50 transition">Contact support</a>
                        </div>
                </div>
            </aside>
        </div>
    </main>

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

            GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                openEffect: 'fade',
                closeEffect: 'fade',
                zoomable: true,
            });
        });
    </script>
</body>
</html>
