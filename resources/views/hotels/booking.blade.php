@php
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = auth()->guard('travel_agent')->user() ?? $currentUser;
    $hasWebUser = auth()->check();
    $hasTravelAgentUser = auth()->guard('travel_agent')->check();
    $isCustomer = $hasWebUser && ! $hasTravelAgentUser;
    $isVisaOfficer = false;
    $userRole = $hasTravelAgentUser ? 'travel_agent' : 'customer';

    if (! $isCustomer && ! $hasTravelAgentUser && ! $hasWebUser) {
        $isCustomer = true;
        $userRole = 'customer';
    }

    $portalLabel = $isCustomer ? 'Customer Portal' : 'Agent Portal';
    $portalSystemLabel = $isCustomer ? 'Customer Portal System' : 'Agent Portal System';
@endphp

@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xs border border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-blue-600 bg-blue-50 border border-blue-100 uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Hotel Management
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Search & Book Hotels</h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Find and reserve premium accommodation across holy destinations in Makkah & Madinah.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-2xl text-center">
                    <span class="block text-[10px] font-bold uppercase text-slate-400 tracking-wider">Properties</span>
                    <span class="text-lg font-extrabold text-slate-800">{{ isset($hotels) ? count($hotels) : 0 }} Listed</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-2xl text-center">
                    <span class="block text-[10px] font-bold uppercase text-slate-400 tracking-wider">Service</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 mt-0.5">
                        <i class="bi bi-shield-check"></i> Instant Booking
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xs border border-slate-200/80">
            <form id="hotelSearchForm" action="{{ route('hotels.booking') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Destination</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="bi bi-geo-alt-fill text-blue-600"></i>
                            </span>
                            <select name="city" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-semibold text-slate-800 h-[50px] transition">
                                <option value="" {{ old('city', $city ?? '') === '' ? 'selected' : '' }}>All Destinations</option>
                                <option value="Makkah" {{ old('city', $city ?? '') === 'Makkah' ? 'selected' : '' }}>Makkah Al Mukarramah</option>
                                <option value="Madina" {{ old('city', $city ?? '') === 'Madina' ? 'selected' : '' }}>Madina Al Munawwarah</option>
                            </select>
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Check-In</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="bi bi-calendar-check text-blue-600"></i>
                            </span>
                            <input type="date" name="check_in" value="{{ old('check_in', $checkIn?->format('Y-m-d') ?? '') }}" class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 h-[50px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition" id="checkIn" min="{{ now()->toDateString() }}" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 flex justify-between">
                            Check-Out <span id="nightsCounter" class="text-blue-600 font-semibold lowercase hidden"></span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="bi bi-calendar-x text-blue-600"></i>
                            </span>
                            <input type="date" name="check_out" value="{{ old('check_out', $checkOut?->format('Y-m-d') ?? '') }}" class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 h-[50px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition" id="checkOut" min="{{ now()->toDateString() }}" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="w-full">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold h-[50px] rounded-xl shadow-lg shadow-blue-500/20 transition duration-200 flex items-center justify-center gap-2 text-sm">
                            <i class="bi bi-search"></i> Search Hotels
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6 items-start">
            <aside class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 lg:sticky lg:top-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Refine Results</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Filter hotel options</p>
                    </div>
                    <button type="button" class="hotel-filter-clear-all text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Clear All</button>
                </div>

                <div class="space-y-6">
                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Property Rating</h6>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="checkbox" name="category[]" value="5 Star" {{ in_array('5 Star', request()->input('category', []), true) ? 'checked' : '' }} class="rounded text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                <span class="text-amber-400 font-bold">★★★★★</span>
                                <span class="text-slate-400 text-xs">(5 Star)</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="checkbox" name="category[]" value="4 Star" {{ in_array('4 Star', request()->input('category', []), true) ? 'checked' : '' }} class="rounded text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                <span class="text-amber-400 font-bold">★★★★☆</span>
                                <span class="text-slate-400 text-xs">(4 Star)</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="checkbox" name="category[]" value="3 Star" {{ in_array('3 Star', request()->input('category', []), true) ? 'checked' : '' }} class="rounded text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                <span class="text-amber-400 font-bold">★★★☆☆</span>
                                <span class="text-slate-400 text-xs">(3 Star)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Distance from Haram</h6>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="radio" name="distance" value="0-250" {{ request()->input('distance') === '0-250' ? 'checked' : '' }} class="text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                0–250 meters
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="radio" name="distance" value="250-500" {{ request()->input('distance') === '250-500' ? 'checked' : '' }} class="text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                250–500 meters
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="radio" name="distance" value="500-1000" {{ request()->input('distance') === '500-1000' ? 'checked' : '' }} class="text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                500m–1km
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer font-medium hover:text-blue-600">
                                <input type="radio" name="distance" value="1000+" {{ request()->input('distance') === '1000+' ? 'checked' : '' }} class="text-blue-600 border-slate-300 w-4 h-4 focus:ring-blue-500">
                                Above 1km
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <section id="hotelResultsContainer" class="relative">
                <div id="hotelLoadingIndicator" class="hidden absolute inset-0 z-30 flex items-center justify-center rounded-3xl bg-white/80 backdrop-blur-xs">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-xl border border-slate-100">
                        <span class="h-4 w-4 rounded-full border-2 border-blue-600 border-t-transparent animate-spin"></span>
                        Updating hotel inventory...
                    </div>
                </div>

                <div id="hotelResultsContent" class="space-y-6">
                    @include('hotels.partials.hotel-results', ['hotels' => $hotels])
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hotelSearchForm = document.getElementById('hotelSearchForm');
            const hotelResultsContainer = document.getElementById('hotelResultsContainer');
            const hotelResultsContent = document.getElementById('hotelResultsContent');
            const hotelLoadingIndicator = document.getElementById('hotelLoadingIndicator');
            const clearAllButton = document.querySelector('.hotel-filter-clear-all');
            const asideFilterInputs = document.querySelectorAll('aside input, aside select');
            const filterEndpoint = "{{ route('hotels.filter') }}";
            const checkInInput = document.getElementById('checkIn');
            const checkOutInput = document.getElementById('checkOut');
            const nightsCounter = document.getElementById('nightsCounter');

            function toDateInputValue(date) {
                return new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().slice(0, 10);
            }

            function updateCheckOutMin() {
                if (!checkInInput || !checkOutInput) {
                    return;
                }

                if (!checkInInput.value) {
                    checkOutInput.min = toDateInputValue(new Date());
                    return;
                }

                const nextDay = new Date(`${checkInInput.value}T00:00:00`);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = toDateInputValue(nextDay);
            }

            function calculateNightsDelta() {
                const display = nightsCounter;
                if (!checkInInput || !checkOutInput || !display) {
                    return;
                }

                if (!checkInInput.value || !checkOutInput.value) {
                    display.classList.add('hidden');
                    display.textContent = '';
                    return;
                }

                const start = new Date(`${checkInInput.value}T00:00:00`);
                const end = new Date(`${checkOutInput.value}T00:00:00`);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    display.textContent = `(${diffDays} ${diffDays === 1 ? 'night' : 'nights'})`;
                    display.classList.remove('hidden');
                    return;
                }

                display.classList.add('hidden');
                display.textContent = '';
            }

            function gatherFilterParams() {
                const params = new URLSearchParams();
                const fieldSets = [hotelSearchForm, document.querySelector('aside')].filter(Boolean);

                fieldSets.forEach((fieldSet) => {
                    fieldSet.querySelectorAll('input, select, textarea').forEach((element) => {
                        if (!element.name || element.disabled) {
                            return;
                        }

                        if ((element.type === 'checkbox' || element.type === 'radio') && !element.checked) {
                            return;
                        }

                        if (element.tagName.toLowerCase() === 'select' && element.multiple) {
                            Array.from(element.selectedOptions).forEach((option) => {
                                params.append(element.name, option.value);
                            });
                            return;
                        }

                        params.append(element.name, element.value);
                    });
                });

                return params;
            }

            function setLoading(visible) {
                if (hotelLoadingIndicator) {
                    hotelLoadingIndicator.classList.toggle('hidden', !visible);
                }
                if (hotelResultsContainer) {
                    hotelResultsContainer.classList.toggle('opacity-60', visible);
                }
            }

            async function refreshHotelResults() {
                if (!hotelResultsContainer) {
                    return;
                }

                const url = `${filterEndpoint}?${gatherFilterParams().toString()}`;
                setLoading(true);

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Unable to fetch hotel results.');
                    }

                    const html = await response.text();
                    if (hotelResultsContent) {
                        hotelResultsContent.innerHTML = html;
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    setLoading(false);
                }
            }

            if (checkInInput) {
                checkInInput.addEventListener('change', () => {
                    updateCheckOutMin();
                    calculateNightsDelta();
                });
            }

            if (checkOutInput) {
                checkOutInput.addEventListener('change', calculateNightsDelta);
            }

            if (hotelSearchForm) {
                hotelSearchForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    refreshHotelResults();
                });

                hotelSearchForm.addEventListener('change', (event) => {
                    const target = event.target;
                    if (target.matches('select[name="city"], input[name="check_in"], input[name="check_out"]')) {
                        refreshHotelResults();
                    }
                });
            }

            asideFilterInputs.forEach((input) => {
                input.addEventListener('change', () => {
                    refreshHotelResults();
                });
            });

            if (clearAllButton) {
                clearAllButton.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (!hotelSearchForm) {
                        return;
                    }

                    hotelSearchForm.reset();
                    document.querySelectorAll('input[name="category[]"]').forEach((input) => {
                        input.checked = false;
                    });
                    document.querySelectorAll('input[name="distance"]').forEach((input) => {
                        input.checked = false;
                    });

                    if (checkInInput) {
                        checkInInput.value = '';
                    }
                    if (checkOutInput) {
                        checkOutInput.value = '';
                    }

                    if (nightsCounter) {
                        nightsCounter.classList.add('hidden');
                        nightsCounter.textContent = '';
                    }

                    refreshHotelResults();
                });
            }

            updateCheckOutMin();
            calculateNightsDelta();
        });
    </script>
@endsection