<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Hotel Booking | Umrah Travel</title>
    
    <!-- Bootstrap 5 & Tailwind CSS CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        .hero-bg {
            background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.85)), 
                              url('https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=1800&q=80');
            background-size: cover;
            background-position: center;
        }
        .custom-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 20px 40px -10px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <section class="hero-bg min-h-[640px] flex items-center relative pt-20 pb-32 px-4">
        <div class="container mx-auto max-w-7xl relative z-10">
            <div class="text-center md:text-left text-white max-w-2xl mb-12">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white mb-4 tracking-wide uppercase">
                    <i class="bi bi-patch-check-fill"></i> Premium Umrah Hospitality
                </span>
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4">Welcome to Hotel Booking</h1>
                <p class="text-lg md:text-xl text-slate-200 font-medium mb-2">Find the Best Hotels in Makkah & Madinah</p>
                <p class="text-base text-slate-300 opacity-90">Search from premium luxury hotels, private family suites, and cozy sharing rooms instantly matching your budget.</p>
            </div>

            <!-- FLOATING BOOKING SEARCH CARD -->
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-2xl border border-slate-100 text-slate-900 custom-shadow translate-y-12">
                <form action="{{ route('hotels.booking') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        
                        <!-- Destination Selection -->
                        <div class="relative">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Destination</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="bi bi-geo-alt-fill text-blue-600"></i>
                                </span>
                                <select name="city" class="form-select w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm font-medium h-[50px]">
                                    <option value="Makkah" {{ old('city', $city ?? '') === 'Makkah' ? 'selected' : '' }}>Makkah Al Mukarramah</option>
                                    <option value="Madina" {{ old('city', $city ?? '') === 'Madina' ? 'selected' : '' }}>Madina Al Munawwarah</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Window / Nights Display -->
                        <div class="grid grid-cols-2 gap-2 lg:col-span-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Check-In</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <i class="bi bi-calendar-check text-blue-600"></i>
                                    </span>
                                    <input type="text" name="check_in" value="{{ old('check_in', $checkIn?->format('Y-m-d') ?? '') }}" class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium h-[50px]" id="checkIn" placeholder="YYYY-MM-DD" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 flex justify-between">
                                    Check-Out <span id="nightsCounter" class="text-blue-600 font-semibold lowercase hidden"></span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <i class="bi bi-calendar-x text-blue-600"></i>
                                    </span>
                                    <input type="text" name="check_out" value="{{ old('check_out', $checkOut?->format('Y-m-d') ?? '') }}" class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium h-[50px]" id="checkOut" placeholder="YYYY-MM-DD" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Guest Selector -->
                        <div class="relative">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Guests & Requirements</label>
                            <button type="button" id="guestToggle" class="w-full text-left pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 flex items-center justify-between h-[50px] transition hover:bg-slate-100">
                                <span class="truncate" id="guestSummary">2 Adults, 0 Children</span>
                                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
                            </button>

                            <!-- Dynamic Dropdown Panel -->
                            <div id="guestPanel" class="absolute left-0 lg:right-0 top-full mt-2 w-full min-w-[320px] bg-white border border-slate-100 shadow-2xl rounded-2xl p-4 hidden z-50 transition-all">
                                <div class="space-y-4 max-h-[380px] overflow-y-auto custom-scrollbar p-1">
                                    
                                    <!-- Counter Rows -->
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Adults</p>
                                            <p class="text-xs text-slate-400">Ages 18+</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="adultDec" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">-</button>
                                            <input type="text" id="adultCount" value="2" class="w-8 text-center text-sm font-bold bg-transparent" readonly>
                                            <button type="button" id="adultInc" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">+</button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Children</p>
                                            <p class="text-xs text-slate-400">Ages 2–17</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="childDec" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">-</button>
                                            <input type="text" id="childCount" value="0" class="w-8 text-center text-sm font-bold bg-transparent" readonly>
                                            <button type="button" id="childInc" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">+</button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Infants</p>
                                            <p class="text-xs text-slate-400">Under 2 years</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="infantDec" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">-</button>
                                            <input type="text" id="infantCount" value="0" class="w-8 text-center text-sm font-bold bg-transparent" readonly>
                                            <button type="button" id="infantInc" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-600 hover:bg-slate-200">+</button>
                                        </div>
                                    </div>

                                    <!-- Dynamic Inputs Sections -->
                                    <div id="adultInputsContainer" class="space-y-3 pt-2 border-t border-slate-100"></div>
                                    <div id="childAgeContainer" class="space-y-3 pt-2 border-t border-slate-100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lower Bar Form Controls -->
                    <div class="flex flex-col sm:flex-row items-center justify-between pt-4 border-t border-slate-100 gap-4">
                        <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-globe text-slate-400"></i>
                                <input type="text" class="border-0 bg-transparent text-sm font-medium text-slate-700 placeholder-slate-400 focus:ring-0 p-0" placeholder="Nationality">
                            </div>
                            <span class="hidden sm:inline text-slate-200">|</span>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-passport text-slate-400"></i>
                                <input type="text" class="border-0 bg-transparent text-sm font-medium text-slate-700 placeholder-slate-400 focus:ring-0 p-0" placeholder="Passport Number (Optional)">
                            </div>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold px-8 py-3.5 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2 text-sm">
                            <i class="bi bi-search"></i> Search Hotels
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- MAIN INTERFACE LAYOUT -->
    <main class="container mx-auto max-w-7xl px-4 pt-24 pb-20">
        <div class="row gx-4">
            
            <!-- LEFT SIDEBAR: FILTERS -->
            <aside class="col-lg-3 mb-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-6 shadow-sm sticky top-6">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <h5 class="text-base font-bold text-slate-900 mb-0">Hotel Filters</h5>
                        <button class="text-xs font-semibold text-blue-600 hover:underline">Clear All</button>
                    </div>

                    <!-- Property Rating -->
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Property Rating</h6>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span class="text-yellow-500 font-serif">★★★★★</span> <span class="text-xs text-slate-400">(5 Star)</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span class="text-yellow-500 font-serif">★★★★☆</span> <span class="text-xs text-slate-400">(4 Star)</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span class="text-yellow-500 font-serif">★★★☆☆</span> <span class="text-xs text-slate-400">(3 Star)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Room Configurations -->
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Room Type</h6>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>Private Room</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>Sharing Room</span>
                            </label>
                        </div>
                    </div>

                    <!-- Distance from Haram -->
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Distance from Haram</h6>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="radio" name="distance" class="text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>0–250 meters</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="radio" name="distance" class="text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>250–500 meters</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="radio" name="distance" class="text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>500m–1km</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="radio" name="distance" class="text-blue-600 focus:ring-blue-500 border-slate-300 w-4 h-4">
                                <span>Above 1km</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Dynamic Slider -->
                    <!-- <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Price Range (SAR)</h6>
                        <input type="range" class="form-range accent-blue-600" min="0" max="2000" step="50" id="priceRange" value="1200">
                        <div class="flex justify-between items-center text-xs text-slate-500 mt-1 font-semibold">
                            <span>0 SAR</span>
                            <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded" id="rangeValDisplay">Max: 1200 SAR</span>
                            <span>2000+ SAR</span>
                        </div>
                    </div> -->

                    <!-- Facilities Checkboxes -->
                    <!-- <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Hotel Facilities</h6>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-blue-600 border-slate-300 w-4 h-4"><span>WiFi Included</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-blue-600 border-slate-300 w-4 h-4"><span>Breakfast Buffet</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-blue-600 border-slate-300 w-4 h-4"><span>Free Parking</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-blue-600 border-slate-300 w-4 h-4"><span>Air Conditioning</span>
                            </label>
                        </div>
                    </div> -->
                </div>
            </aside>

            <!-- RIGHT CONTAINER: HOTELS LIST -->
            <section class="col-lg-9">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Search Results</span>
                        <!-- Blade conditional block fallback example -->
                        <h4 class="text-lg font-bold text-slate-900 mt-0.5">Showing {{ isset($hotels) ? count($hotels) : 32 }} Properties found</h4>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <span class="text-sm font-medium text-slate-500 whitespace-nowrap"><i class="bi bi-sort-down"></i> Sort By</span>
                        <select class="form-select text-sm font-medium border-slate-200 rounded-xl bg-slate-50 py-2 px-3 focus:ring-blue-500">
                            <option>Popularity</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Rating: Superb first</option>
                            <option>Distance to Haram</option>
                        </select>
                    </div>
                </div>

                <!-- HOTEL CARDS CONTAINER -->
                <div class="space-y-4">
                    
                    @forelse($hotels ?? [] as $hotel)
                        <!-- Card Variant Blueprint Loop -->
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-slate-300 transition duration-300 flex flex-col md:flex-row group relative">
                            
                            <!-- Wishlist Action Toggle Pin -->
                            <button class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-white/90 backdrop-blur shadow hover:scale-110 active:scale-95 transition flex items-center justify-center border border-slate-100 text-slate-400 hover:text-red-500">
                                <i class="bi bi-heart-fill text-sm"></i>
                            </button>

                            <!-- Asset Media Column -->
                            <div class="md:w-[320px] relative overflow-hidden bg-slate-100 min-h-[220px]">
                                <img src="{{ $hotel->cover_image_url ?? 'https://images.unsplash.com/photo-1549389476-ab3a4dd5115f?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $hotel->hotel_name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <span class="inline-block bg-blue-600/90 text-white font-semibold rounded-md px-2 py-0.5 text-xs mb-1">{{ $hotel->city }}</span>
                                    <p class="text-xs opacity-90"><i class="bi bi-geo-alt"></i> {{ \Illuminate\Support\Str::limit($hotel->address, 45) }}</p>
                                </div>
                            </div>

                            <!-- Content Core Matrix -->
                            <div class="flex-1 p-4 md:p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-xl font-bold text-slate-900 mb-0">{{ $hotel->hotel_name }}</h3>
                                            <div class="text-yellow-500 text-xs">{{ $hotel->category === '5 Star' ? '★★★★★' : ($hotel->category === '4 Star' ? '★★★★☆' : '★★★☆☆') }}</div>
                                        </div>
                                        <div class="flex items-center gap-1.5 bg-blue-50 px-2.5 py-1 rounded-lg">
                                            <span class="text-sm font-bold text-blue-700">{{ $hotel->featured ? 'Top Rated' : 'Verified' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-slate-500 font-medium mb-3 flex items-center gap-1.5">
                                        <i class="bi bi-cursor-fill text-blue-600"></i> Distance from Haram: <strong>{{ number_format($hotel->distance_from_haram, 0) }} meters</strong>
                                    </p>

                                    <!-- Quick Badges -->
                                    <div class="flex flex-wrap items-center gap-2 mb-4">
                                        <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-emerald-100">Free Cancellation</span>
                                        <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-indigo-100">Breakfast Included</span>
                                        @if($hotel->seasonalRates->isNotEmpty())
                                            <span class="bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-amber-100">Seasonal Offer</span>
                                        @endif
                                        @if($hotel->mealPlans->isNotEmpty())
                                            <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full"><i class="bi bi-basket-fill"></i> {{ $hotel->mealPlans->first()->meal_plan_name }}</span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-slate-400 font-medium">Available room configs: {{ $hotel->roomTypes->pluck('room_name')->join(', ') ?: 'Standard Room' }}</p>
                                </div>

                                <!-- Financial Pricing Anchor & CTA Actions -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-t border-slate-100 pt-4 mt-4 gap-4">
                                    <div>
                                        <span class="text-xs text-slate-400 block font-medium">Starting from per night</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-extrabold text-slate-900">SAR {{ number_format(optional($hotel->bestRoomType)->daily_rate ?? ($hotel->roomTypes->min('daily_rate') ?? 0), 2) }}</span>
                                            <span class="text-xs text-slate-400 font-semibold">Excl. VAT</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">
                                            @if($hotel->availability['status'] === 'Select dates')
                                                Select your dates to show live availability.
                                            @else
                                                {{ $hotel->availability['available_rooms'] > 0 ? $hotel->availability['available_rooms'].' room'.($hotel->availability['available_rooms'] === 1 ? '' : 's').' available for your stay' : 'Sold out for selected dates' }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('hotels.details', $hotel) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">View Details</a>
                                        <a href="{{ route('hotels.details', $hotel) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-blue-600/10 transition">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- FALLBACK NO RESULTS STATE COMPONENT -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center max-w-xl mx-auto my-12 shadow-sm">
                            <div class="w-24 h-24 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                                <i class="bi bi-file-earmark-x"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">No Hotels Found</h3>
                            <p class="text-slate-500 text-sm mb-6">We couldn't locate matching options for your selected dates or filters. Try checking alternative dates or adjusting the filters.</p>
                            <a href="#" class="inline-flex bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition">Reset All Search Filters</a>
                        </div>
                    @endforelse

                </div>

                <!-- PAGINATION NAVIGATION FOOTER -->
                <nav class="mt-12 flex justify-center">
                    <ul class="pagination gap-1 border-0">
                        <li class="page-item disabled"><a class="page-link border-0 rounded-xl px-4 py-2 bg-slate-100 font-semibold text-sm" href="#">Prev</a></li>
                        <li class="page-item active"><a class="page-link border-0 rounded-xl px-4 py-2 bg-blue-600 text-white font-semibold text-sm" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-sm" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-sm" href="#">3</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-sm" href="#">Next</a></li>
                    </ul>
                </nav>
            </section>
        </div>
    </main>

    <!-- PREMIUM TRAVEL PORTAL FOOTER -->
    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="container mx-auto max-w-7xl px-4">
            <div class="row gy-4 mb-12">
                <div class="col-md-4">
                    <h4 class="text-lg font-bold text-slate-900 mb-3">Umrah Hotels Portal</h4>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-sm">Providing direct premium reservations across holy sites in Makkah and Madinah with seamless verification and luxury customer care workflows.</p>
                </div>
                <div class="col-6 col-md-2">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Corporate</h5>
                    <ul class="list-unstyled space-y-2 text-sm">
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">About Us</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">Our Packages</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">Careers Portal</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Legal Support</h5>
                    <ul class="list-unstyled space-y-2 text-sm">
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">Contact Center</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-blue-600 no-underline transition">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Join Exclusive Offers Newsletter</h5>
                    <form class="flex gap-2 mt-2">
                        <input type="email" class="form-control border-slate-200 rounded-xl text-sm px-3 focus:ring-2 focus:ring-blue-500" placeholder="Enter business email...">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold px-4 transition">Subscribe</button>
                    </form>
                    <div class="flex gap-3 mt-4 text-slate-400 text-lg">
                        <a href="#" class="hover:text-blue-600 transition"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="hover:text-blue-400 transition"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="hover:text-pink-600 transition"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-6 text-center text-xs text-slate-400 font-medium">
                &copy; 2026 Umrah Booking Portal. Crafted for exceptional spiritual journeys. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- INLINE SCRIPT MATRIX LOGIC CONSOLE -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let chkInInstance = null;
            let chkOutInstance = null;

            // Range display helper
            const priceSlider = document.getElementById('priceRange');
            if(priceSlider) {
                priceSlider.addEventListener('input', (e) => {
                    document.getElementById('rangeValDisplay').textContent = `Max: ${e.target.value} SAR`;
                });
            }

            // Dual flatpickr link management with automated night delta tracker computation
            chkInInstance = flatpickr('#checkIn', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                onChange: (selectedDates) => {
                    if (selectedDates.length > 0) {
                        const nextDay = new Date(selectedDates[0]);
                        nextDay.setDate(nextDay.getDate() + 1);
                        chkOutInstance.set('minDate', nextDay);
                        calculateNightsDelta();
                    }
                }
            });

            chkOutInstance = flatpickr('#checkOut', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                onChange: () => {
                    calculateNightsDelta();
                }
            });

            function calculateNightsDelta() {
                const start = chkInInstance.selectedDates[0];
                const end = chkOutInstance.selectedDates[0];
                const display = document.getElementById('nightsCounter');
                
                if (start && end) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    display.textContent = `(${diffDays} ${diffDays === 1 ? 'night' : 'nights'})`;
                    display.classList.remove('hidden');
                } else {
                    display.classList.add('hidden');
                }
            }

            // Panel UI Visibility Controls
            const guestToggle = document.getElementById('guestToggle');
            const guestPanel = document.getElementById('guestPanel');
            
            guestToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                guestPanel.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!guestPanel.contains(e.target) && e.target !== guestToggle) {
                    guestPanel.classList.add('hidden');
                }
            });

            // Count nodes selectors
            const counters = {
                adult: { input: document.getElementById('adultCount'), inc: document.getElementById('adultInc'), dec: document.getElementById('adultDec'), min: 1, max: 9 },
                child: { input: document.getElementById('childCount'), inc: document.getElementById('childInc'), dec: document.getElementById('childDec'), min: 0, max: 9 },
                infant: { input: document.getElementById('infantCount'), inc: document.getElementById('infantInc'), dec: document.getElementById('infantDec'), min: 0, max: 9 }
            };

            Object.keys(counters).forEach(key => {
                const conf = counters[key];
                conf.inc.addEventListener('click', (e) => {
                    e.stopPropagation();
                    let val = parseInt(conf.input.value, 10);
                    if (val < conf.max) {
                        conf.input.value = val + 1;
                        synchronizeDOMChanges();
                    }
                });
                conf.dec.addEventListener('click', (e) => {
                    e.stopPropagation();
                    let val = parseInt(conf.input.value, 10);
                    if (val > conf.min) {
                        conf.input.value = val - 1;
                        synchronizeDOMChanges();
                    }
                });
            });

            function synchronizeDOMChanges() {
                const a = parseInt(counters.adult.input.value, 10);
                const c = parseInt(counters.child.input.value, 10);
                const i = parseInt(counters.infant.input.value, 10);
                
                document.getElementById('guestSummary').textContent = `${a} Ad, ${c} Ch, ${i} Inf`;
                
                // Dynamic Adult configuration loops fields generation
                const adultBox = document.getElementById('adultInputsContainer');
                adultBox.innerHTML = '';
                if (a > 1) {
                    const header = document.createElement('p');
                    header.className = 'text-xs font-bold uppercase text-blue-600 tracking-wider mb-2';
                    header.textContent = 'Additional Guest Names';
                    adultBox.appendChild(header);
                    
                    for (let step = 2; step <= a; step++) {
                        const div = document.createElement('div');
                        div.className = 'flex gap-2';
                        div.innerHTML = `<span class="text-xs font-medium text-slate-400 flex items-center shrink-0 w-14">Adult ${step}:</span>
                                         <input type="text" class="form-control form-control-sm border-slate-200 rounded-lg text-xs" placeholder="Full Name As Passport">`;
                        adultBox.appendChild(div);
                    }
                }

                // Dynamic Child Age Selection fields dropdown array block configurations
                const childBox = document.getElementById('childAgeContainer');
                childBox.innerHTML = '';
                if (c > 0) {
                    const header = document.createElement('p');
                    header.className = 'text-xs font-bold uppercase text-blue-600 tracking-wider mb-2';
                    header.textContent = 'Specify Child Age Breakdown';
                    childBox.appendChild(header);

                    for (let step = 1; step <= c; step++) {
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between gap-4';
                        let selectOpts = '';
                        for(let age = 2; age <= 17; age++) { selectOpts += `<option value="${age}">${age} Years Old</option>`; }
                        div.innerHTML = `<span class="text-xs text-slate-600 font-medium">Child ${step} Age at check-in:</span>
                                         <select class="form-select form-select-sm border-slate-200 rounded-lg text-xs w-28 py-1">${selectOpts}</select>`;
                        childBox.appendChild(div);
                    }
                }
            }
            
            // Invoke initial UI state population update sync loop
            synchronizeDOMChanges();
        });
    </script>
</body>
</html>