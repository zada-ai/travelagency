@extends('layouts.dashboard')

@section('title', 'Tickets Inventory & Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header Summary Panel --}}
    <div class="rounded-3xl bg-white p-6 md:p-8 shadow-sm border border-gray-200 flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600">Ticket Inventory</span>
            </div>
            <h1 class="mt-2 text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Search & Book Flight Tickets</h1>
            <p class="mt-1 text-sm text-gray-500 max-w-2xl">Browse available flight inventory in real-time, view seat capacity, and book tickets for your pilgrims.</p>
        </div>
        <div class="grid gap-3 grid-cols-2">
            <div class="rounded-2xl bg-gray-50 border border-gray-100 px-5 py-4 text-center sm:text-left">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Total Active Flights</p>
                <p class="mt-1 text-2xl font-black text-gray-900 leading-none">{{ count($tickets) }}</p>
            </div>
            <div class="rounded-2xl bg-blue-50/60 border border-blue-100 px-5 py-4 text-center sm:text-left">
                <p class="text-[10px] uppercase tracking-wider text-blue-600 font-bold">Available Seats</p>
                <p class="mt-1 text-2xl font-black text-blue-700 leading-none">{{ $tickets->sum('available_seats') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-200">
        <form action="{{ url()->current() }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end">
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Departure City (From)</label>
                <input type="text" name="from" value="{{ request('from') }}" placeholder="e.g. Islamabad, Lahore" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Arrival City (To)</label>
                <input type="text" name="to" value="{{ request('to') }}" placeholder="e.g. Jeddah, Madinah" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Departure Date</label>
                <input type="date" name="departure" value="{{ request('departure') }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Airline</label>
                <select name="airline" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Airlines</option>
                    <option value="PIA" {{ request('airline') === 'PIA' ? 'selected' : '' }}>PIA</option>
                    <option value="Saudi Airlines" {{ request('airline') === 'Saudi Airlines' ? 'selected' : '' }}>Saudi Airlines</option>
                    <option value="Emirates" {{ request('airline') === 'Emirates' ? 'selected' : '' }}>Emirates</option>
                    <option value="Fly Jinnah" {{ request('airline') === 'Fly Jinnah' ? 'selected' : '' }}>Fly Jinnah</option>
                    <option value="Airblue" {{ request('airline') === 'Airblue' ? 'selected' : '' }}>Airblue</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2 shadow-sm transition text-center">
                    Search
                </button>
                @if(request()->hasAny(['from', 'to', 'departure', 'return', 'airline']))
                    <a href="{{ url()->current() }}" class="rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table / List View --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-600">
                Available Flights ({{ count($tickets) }})
            </span>
            <span class="text-xs text-gray-400 font-medium">All times are local</span>
        </div>

        <div class="overflow-x-auto">
            @if(count($tickets) === 0)
                <div class="p-12 text-center text-gray-400">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 text-gray-400 mb-3">
                        ✈
                    </div>
                    <h3 class="text-base font-bold text-gray-800">No Flights Found</h3>
                    <p class="mt-1 text-sm text-gray-500">No flights matched your search criteria. Try modifying your filters or clearing search terms.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-[11px] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="px-5 py-3.5 text-left">Airline / Flight</th>
                            <th class="px-5 py-3.5 text-left">Sector (Route)</th>
                            <th class="px-5 py-3.5 text-left">Departure</th>
                            <th class="px-5 py-3.5 text-left">Return</th>
                            <th class="px-5 py-3.5 text-left">Baggage & Meal</th>
                            <th class="px-5 py-3.5 text-left">Available Seats</th>
                            <th class="px-5 py-3.5 text-left">Price (SAR)</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($tickets as $ticket)
                            @php
                                $airline = $ticket->airline ?? 'PIA';
                                $flightNumber = $ticket->flight_number ?? 'PK-201';
                                $pnr = $ticket->pnr ?? $ticket->reference ?? '-';
                                $route = $ticket->route ?? 'ISB - JED';
                                $departureDate = $ticket->departure_date?->format('d M Y') ?? $ticket->trip_date ?? 'N/A';
                                $departureTime = $ticket->departure_time ?? 'N/A';
                                $returnDate = $ticket->return_date?->format('d M Y');
                                $returnTime = $ticket->return_arrival_time ?? $ticket->arrival_time ?? '';
                                $baggage = $ticket->baggage ?? '30';
                                $meal = $ticket->meal;
                                $price = $ticket->price ? number_format($ticket->price, 2) : '24,400.00';
                                $availableSeats = $ticket->available_seats;
                                $totalSeats = $ticket->total_seats ?: 150;
                                $seatPercent = $totalSeats > 0 ? min(100, round(($availableSeats / $totalSeats) * 100)) : 0;
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition duration-150">
                                
                                {{-- Airline & Flight --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-xs font-bold text-white shadow-xs flex-shrink-0">
                                            {{ strtoupper(substr($airline, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-gray-900 text-sm">{{ $airline }}</span>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="inline-block rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-bold text-gray-700">{{ $flightNumber }}</span>
                                                @if($pnr && $pnr !== '-')
                                                    <span class="text-[10px] font-mono text-gray-400 font-medium">PNR: {{ $pnr }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Sector / Route --}}
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900 text-sm tracking-wide">
                                        {{ $route }}
                                    </div>
                                    <span class="inline-flex items-center mt-0.5 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $returnDate ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $returnDate ? 'Round Trip' : 'One Way' }}
                                    </span>
                                </td>

                                {{-- Departure --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-800 text-sm">{{ $departureDate }}</div>
                                    <div class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $departureTime }}
                                    </div>
                                </td>

                                {{-- Return --}}
                                <td class="px-5 py-4">
                                    @if($returnDate)
                                        <div class="font-semibold text-gray-800 text-sm">{{ $returnDate }}</div>
                                        <div class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $returnTime ?: 'Confirmed' }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">No Return</span>
                                    @endif
                                </td>

                                {{-- Baggage & Meal --}}
                                <td class="px-5 py-4">
                                    <div class="text-xs font-semibold text-gray-800">{{ $baggage }} Kg Baggage</div>
                                    <div class="mt-0.5 text-[11px] {{ in_array($meal, ['yes', '1', 1], true) ? 'text-emerald-600 font-medium' : 'text-gray-400' }}">
                                        {{ in_array($meal, ['yes', '1', 1], true) ? '✓ Food Included' : '✕ No Meal' }}
                                    </div>
                                </td>

                                {{-- Available Seats --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-sm {{ $availableSeats > 5 ? 'text-emerald-700' : ($availableSeats > 0 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ $availableSeats }}
                                        </span>
                                        <span class="text-xs text-gray-400">/ {{ $totalSeats }}</span>
                                    </div>
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $availableSeats > 5 ? 'bg-emerald-500' : ($availableSeats > 0 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $seatPercent }}%"></div>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        Eco: {{ $ticket->getClassAvailableSeats('Economy') }} · Biz: {{ $ticket->getClassAvailableSeats('Business') }}
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td class="px-5 py-4">
                                    <div class="font-black text-gray-900 text-sm">
                                        SAR {{ $price }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-medium">per passenger</div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($ticket->status === 'Approved' && $availableSeats > 0)
                                            <a
                                                href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                                            >
                                                Book
                                            </a>
                                        @else
                                            <span class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-3 py-1.5 text-[11px] font-bold text-gray-400">
                                                Sold Out
                                            </span>
                                        @endif
                                        <a
                                            href="{{ route('ticket.details', ['ticket' => $ticket->id]) }}"
                                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs"
                                            title="View Details"
                                        >
                                            Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
