<div class="space-y-6">
    <!-- Top Filter/Sort Bar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-slate-800">Showing {{ isset($hotels) ? count($hotels) : 0 }} properties near your destination.</p>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Use filters to narrow down the perfect stay for your clients.</p>
        </div>
        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto border-t border-slate-100 sm:border-t-0 pt-3 sm:pt-0">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 shrink-0">Sort by</label>
            <select class="text-xs font-semibold text-slate-700 border-slate-200 rounded-xl bg-slate-50 py-2.5 pl-3 pr-8 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 w-full sm:w-48 transition">
                <option>Relevance</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Rating</option>
            </select>
        </div>
    </div>

    <!-- Hotels List Container -->
    <div class="space-y-6">
        @forelse($hotels ?? [] as $hotel)
            <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col lg:flex-row group transition duration-300 hover:shadow-xl hover:border-slate-300">
                <!-- Image Section -->
                <div class="w-full lg:w-[340px] xl:w-[380px] h-[220px] sm:h-[250px] overflow-hidden relative bg-slate-100 shrink-0">
                    <img src="{{ $hotel->cover_image_url ?? 'https://images.unsplash.com/photo-1549389476-ab3a4dd5115f?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $hotel->hotel_name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 bg-blue-600/90 text-white px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md shadow-md">
                            <i class="bi bi-geo-alt-fill text-[11px]"></i>
                            {{ $hotel->city }}
                        </span>
                        @if($hotel->featured)
                            <span class="inline-flex items-center gap-1 bg-amber-500/90 text-white px-2.5 py-1 rounded-full text-xs font-bold backdrop-blur-md shadow-md">
                                <i class="bi bi-star-fill text-[10px]"></i> Featured
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content Section -->
                <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between gap-6">
                    <div class="space-y-4">
                        <!-- Title & Badges -->
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="max-w-xl space-y-1.5">
                                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight group-hover:text-blue-600 transition">{{ $hotel->hotel_name }}</h3>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                    <span class="text-amber-400 font-bold tracking-wider">{{ $hotel->category === '5 Star' ? '★★★★★' : ($hotel->category === '4 Star' ? '★★★★☆' : '★★★☆☆') }}</span>
                                    <span class="inline-block w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-xs font-semibold text-slate-600">Verified Property</span>
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 self-start sm:self-auto">
                                <i class="bi bi-shield-check text-sm"></i>
                                <span>{{ $hotel->featured ? 'Top Rated' : 'Verified' }}</span>
                            </div>
                        </div>

                        <!-- Info Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-500">
                            <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                                <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-1">Distance</p>
                                <p class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="bi bi-geo-alt text-blue-600"></i>
                                    {{ number_format($hotel->distance_from_haram, 0) }} m from Haram
                                </p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-3.5 border border-slate-100">
                                <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 mb-1">Accommodation</p>
                                <p class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="bi bi-building text-blue-600"></i>
                                    {{ $hotel->category ?? 'Standard Suite' }}
                                </p>
                            </div>
                        </div>

                        <!-- Amenity Tags -->
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200/60 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                <i class="bi bi-check-circle-fill text-[11px]"></i> Free Cancellation
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border border-indigo-200/60 bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">
                                <i class="bi bi-cup-hot-fill text-[11px]"></i> Breakfast Included
                            </span>
                            @if($hotel->seasonalRates?->isNotEmpty())
                                <span class="inline-flex items-center gap-1 rounded-full border border-amber-200/60 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                    <i class="bi bi-tag-fill text-[11px]"></i> Seasonal Offer
                                </span>
                            @endif
                            @if($hotel->mealPlans?->isNotEmpty())
                                <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                    <i class="bi bi-basket-fill text-slate-400"></i> {{ $hotel->mealPlans->first()->meal_plan_name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Pricing and Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-slate-100 mt-auto">
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Starting from</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-2xl sm:text-3xl font-black text-slate-900">SAR {{ number_format(optional($hotel->bestRoomType)->daily_rate ?? ($hotel->roomTypes?->min('daily_rate') ?? 0), 2) }}</span>
                                <span class="text-xs font-medium text-slate-400">/ night <span class="text-[10px] text-slate-300">(Excl. VAT)</span></span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:flex gap-2.5 w-full sm:w-auto">
                            <a href="{{ route('hotels.details', $hotel) }}" class="flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-100 transition active:scale-[0.98]">
                                View Details
                            </a>
                            <a href="{{ route('hotels.booking.create', $hotel) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] px-5 py-3 text-xs sm:text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-xs max-w-xl mx-auto my-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600 text-2xl">
                    <i class="bi bi-file-earmark-x"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">No hotels found</h3>
                <p class="text-sm text-slate-500">We couldn't find any hotels matching your search selection. Try clearing or expanding your filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <nav class="mt-8 flex justify-center overflow-x-auto py-2">
        <ul class="flex items-center gap-1.5 list-none p-0 m-0">
            <li class="disabled"><a class="inline-flex items-center justify-center pointer-events-none rounded-xl px-4 py-2 bg-slate-100 font-semibold text-xs text-slate-400" href="#" tabindex="-1">Prev</a></li>
            <li><a class="inline-flex items-center justify-center rounded-xl px-4 py-2 bg-blue-600 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition" href="#">1</a></li>
            <li><a class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition" href="#">2</a></li>
            <li><a class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition" href="#">3</a></li>
            <li><a class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition" href="#">Next</a></li>
        </ul>
    </nav>
</div>