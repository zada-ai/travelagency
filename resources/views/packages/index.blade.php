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
                    Umrah Packages & Hospitality
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Explore Umrah Packages</h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Browse verified all-inclusive Umrah packages complete with flights, hotels, visa, & transport.</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                @if(Route::has('customer.packages.create') || Route::has('packages.create'))
                    <a href="{{ Route::has('customer.packages.create') ? route('customer.packages.create') : url('/customer/packages/create') }}" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl font-bold text-xs shadow-lg shadow-blue-500/20 transition flex items-center justify-center gap-2 shrink-0">
                        <i class="bi bi-plus-lg"></i>
                        <span>Create Package</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 md:p-6 shadow-xs border border-slate-200/80">
            <form action="#" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Search Package</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="bi bi-search text-blue-600"></i>
                        </span>
                        <input type="text" name="query" placeholder="e.g. 15 Days Economy Package" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 h-[48px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Airline Carrier</label>
                    <select name="airline" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 h-[48px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                        <option value="">All Airlines</option>
                        <option value="Saudi Arabian Airlines">Saudi Arabian Airlines</option>
                        <option value="PIA">PIA (Pakistan Int.)</option>
                        <option value="Airblue">Airblue</option>
                        <option value="FlyNAS">FlyNAS</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Duration</label>
                    <select name="duration" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 h-[48px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                        <option value="">Any Duration</option>
                        <option value="7">7 Days</option>
                        <option value="10">10 Days</option>
                        <option value="15">15 Days</option>
                        <option value="21">21 Days</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold h-[48px] rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center justify-center gap-2 text-sm">
                        <i class="bi bi-funnel-fill"></i> Filter Packages
                    </button>
                </div>
            </form>
        </div>

        <div class="flex flex-col gap-6">
            @foreach($displayPackages as $pkg)
                <div class="bg-white border border-slate-200 rounded-[28px] overflow-hidden relative shadow-md group hover:shadow-xl hover:border-slate-300 transition duration-300 flex flex-col lg:flex-row">
                    <div class="flex-1 p-6 relative">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-100 uppercase tracking-wider mb-2">
                                    <i class="bi bi-stars"></i> {{ $pkg->badge ?? 'Premium Package' }}
                                </span>
                                <h3 class="text-xl md:text-2xl font-extrabold text-slate-900 leading-tight group-hover:text-blue-600 transition">
                                    {{ $pkg->title ?? $pkg->name }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium mt-1">Provider: {{ $pkg->airline ?? 'Saudi Arabian Airlines' }} | Route: {{ $pkg->origin ?? 'ISB' }} → {{ $pkg->destination ?? 'JED' }}</p>
                            </div>
                            <div class="text-right hidden sm:block">
                                <span class="inline-block px-3 py-1 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">
                                    {{ $pkg->duration ?? '15 Days / 14 Nights' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div class="flex flex-wrap gap-2 text-[11px]">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 shadow-xs">
                                    <i class="bi bi-check-circle-fill text-[12px]"></i> Visa Included
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-100 shadow-xs">
                                    <i class="bi bi-check-circle-fill text-[12px]"></i> Hotel Included
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-xs">
                                    <i class="bi bi-check-circle-fill text-[12px]"></i> Transport Included
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-sky-50 text-sky-700 font-bold border border-sky-100 shadow-xs">
                                    <i class="bi bi-check-circle-fill text-[12px]"></i> Flight Ticket
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div class="bg-slate-50/80 rounded-2xl p-3.5 border border-slate-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Departure</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Return</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-extrabold text-slate-800 text-sm"><i class="bi bi-calendar-event text-blue-600 mr-1"></i> {{ \Carbon\Carbon::parse($pkg->departure_date ?? '2026-08-15')->format('M d, Y') }}</span>
                                        <span class="text-slate-300 mx-2">→</span>
                                        <span class="font-extrabold text-slate-800 text-sm"><i class="bi bi-calendar-check text-blue-600 mr-1"></i> {{ \Carbon\Carbon::parse($pkg->return_date ?? '2026-08-30')->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="bg-slate-50/80 rounded-2xl p-3.5 border border-slate-100 space-y-1.5">
                                    <div class="flex items-center gap-2 text-xs">
                                        <i class="bi bi-building text-amber-500"></i>
                                        <span class="text-slate-400 font-bold text-[10px] uppercase w-12">Makkah:</span>
                                        <span class="font-semibold text-slate-800 truncate">{{ $pkg->makkah_hotel ?? 'Pullman Zamzam (5★)' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <i class="bi bi-building text-emerald-500"></i>
                                        <span class="text-slate-400 font-bold text-[10px] uppercase w-12">Madinah:</span>
                                        <span class="font-semibold text-slate-800 truncate">{{ $pkg->madinah_hotel ?? 'Anwar Al Madinah (5★)' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:flex flex-col items-center justify-center relative w-10">
                        <div class="absolute top-0 bottom-0 w-px border-l-2 border-dashed border-slate-200"></div>
                        <div class="absolute -top-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200 shadow-inner z-10"></div>
                        <div class="absolute -bottom-3 w-6 h-6 bg-slate-50 rounded-full border border-slate-200 shadow-inner z-10"></div>
                    </div>

                    <div class="lg:hidden w-full h-px border-t-2 border-dashed border-slate-200 my-2 relative">
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 rounded-full border border-slate-200 shadow-inner"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 rounded-full border border-slate-200 shadow-inner"></div>
                    </div>

                    <div class="bg-gradient-to-b from-slate-50 to-blue-50/30 lg:w-[320px] p-6 flex flex-col justify-between">
                        <div>
                            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-5 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Available Seats</span>
                                <div class="flex justify-center items-center gap-3">
                                    <div class="flex gap-1">
                                        @for($i = 0; $i < 3; $i++)
                                            <div class="w-5 h-6 rounded-t-lg bg-blue-100 border border-blue-200 flex items-center justify-center"><i class="bi bi-person-fill text-blue-500 text-[10px]"></i></div>
                                        @endfor
                                    </div>
                                    <span class="font-extrabold text-slate-800 text-lg">{{ $pkg->available_seats ?? 0 }} / {{ $pkg->total_seats ?? 0 }}</span>
                                </div>
                            </div>

                            <div class="text-center space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Package Price</span>
                                <div class="text-3xl font-black text-slate-900 tracking-tight text-blue-600">
                                    <span class="text-sm text-slate-500 mr-1">SAR</span>{{ number_format($pkg->price ?? 14500, 0) }}
                                </div>
                                <p class="text-[10px] font-semibold text-slate-400 mt-1">Per Person (All Inclusive)</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            @if(($pkg->available_seats ?? 0) > 0 && ($pkg->status ?? 'Active') === 'Active')
                                @php
                                    $packageBookRoute = request()->routeIs('travel-agents.packages.index')
                                        ? route('travel-agents.packages.book', $pkg->id)
                                        : route('packages.book', $pkg->id);
                                @endphp
                                <a href="{{ $packageBookRoute }}" class="w-full inline-flex items-center justify-center px-5 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl font-bold text-sm shadow-xl shadow-blue-500/25 transition gap-2">
                                    <i class="bi bi-bag-check-fill text-lg"></i>
                                    <span>Select Package</span>
                                </a>
                            @else
                                <button type="button" disabled class="w-full inline-flex items-center justify-center px-5 py-3.5 bg-slate-300 text-slate-500 rounded-xl font-bold text-sm cursor-not-allowed gap-2">
                                    <i class="bi bi-x-circle-fill text-lg"></i>
                                    <span>Package Full</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
