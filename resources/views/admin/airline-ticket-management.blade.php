@extends('admin.layouts.app')

@section('title', 'Airline Ticket Management')
@section('page-heading', 'Airline Ticket Management')
@section('page-description', 'Manage airline tickets, flights, bookings and seat inventory.')

@php
    use App\Models\FlightBooking;
    use App\Models\Ticket;

    $totalFlights = Ticket::count();
    $activeFlights = Ticket::whereIn('status', ['Approved', 'Processing'])->count();
    $availableSeats = Ticket::sum('available_seats');
    $bookedSeats = FlightBooking::where('status', '!=', 'Cancelled')->sum('total_passengers');
    $pendingBookingsCount = FlightBooking::where('status', 'Pending')->count();
    $confirmedBookingsCount = FlightBooking::where('status', 'Confirmed')->count();
    $airlineNames = Ticket::select('airline')->distinct()->orderBy('airline')->pluck('airline');
@endphp

@section('content')
<div class="space-y-6">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <i class="bi bi-check-circle-fill text-lg text-emerald-600"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800 shadow-sm">
            <div class="flex items-center gap-2 font-bold text-rose-900 mb-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
                Please fix the following errors:
            </div>
            <ul class="list-disc space-y-1 pl-6 text-xs text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Executive Header Banner --}}
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-100">
                        <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Flight Operations Control
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-100">
                        Live System
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Airline Ticket Management
                </h1>
                <p class="mt-1 text-sm text-slate-500 max-w-2xl font-medium">
                    Centralized hub to schedule flight inventory, manage airline/airport hubs, allocate cabin seats, and supervise booking requests.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="switchTab('upload-ticket')" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:from-blue-700 hover:to-blue-800 transition cursor-pointer">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                    Upload New Ticket
                </button>
                <a href="{{ route('admin.airline-bookings.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                    <i class="bi bi-ticket-perforated-fill text-blue-600"></i>
                    View Bookings
                </a>
            </div>
        </div>
    </div>

    {{-- Metrics Summary Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Flights</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="bi bi-airplane-engines text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format($totalFlights) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Scheduled flights</p>
        </div>

        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Flights</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="bi bi-check2-circle text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600">{{ number_format($activeFlights) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Approved / Processing</p>
        </div>

        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Available Seats</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                    <i class="bi bi-person-check text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-cyan-700">{{ number_format($availableSeats) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Ready for booking</p>
        </div>

        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Booked Seats</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="bi bi-people-fill text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-indigo-700">{{ number_format($bookedSeats) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Reserved by agents</p>
        </div>

        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="bi bi-clock-history text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-amber-600">{{ number_format($pendingBookingsCount) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Awaiting confirmation</p>
        </div>

        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-xs transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Confirmed</p>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <i class="bi bi-shield-check text-sm"></i>
                </span>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-teal-700">{{ number_format($confirmedBookingsCount) }}</p>
            <p class="mt-0.5 text-[11px] font-medium text-slate-400">Active reservations</p>
        </div>
    </div>

    {{-- Clean Segmented Tab Bar --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-2">
        <button type="button" onclick="switchTab('inventory')" id="tab-btn-inventory" class="tab-btn inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-xs sm:text-sm font-bold transition shadow-xs">
            <i class="bi bi-list-columns-reverse"></i>
            Flight Inventory & Tickets
        </button>
        <button type="button" onclick="switchTab('upload-ticket')" id="tab-btn-upload-ticket" class="tab-btn inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-xs sm:text-sm font-bold transition text-slate-600 hover:bg-slate-100">
            <i class="bi bi-plus-circle"></i>
            Add / Upload Flight Ticket
        </button>
        <button type="button" onclick="switchTab('settings')" id="tab-btn-settings" class="tab-btn inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-xs sm:text-sm font-bold transition text-slate-600 hover:bg-slate-100">
            <i class="bi bi-buildings"></i>
            Airlines & Airports Master
        </button>
    </div>

    {{-- TAB 1: Flight Inventory & Tickets --}}
    <div id="tab-pane-inventory" class="tab-pane space-y-6">
        {{-- Search & Filter Toolbar --}}
        <div class="rounded-3xl border border-slate-200/90 bg-white p-5 shadow-sm">
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="search" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Search Flights</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Route, flight no, reference..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-9 pr-4 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>
                <div>
                    <label for="airline" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Airline Filter</label>
                    <select id="airline" name="airline" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">All Airlines</option>
                        @foreach($airlineNames as $airline)
                            <option value="{{ $airline }}" {{ request('airline') === $airline ? 'selected' : '' }}>{{ $airline }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status Filter</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">All Statuses</option>
                        <option value="Pending"{{ request('status') === 'Pending' ? ' selected' : '' }}>Pending</option>
                        <option value="Approved"{{ request('status') === 'Approved' ? ' selected' : '' }}>Approved</option>
                        <option value="Processing"{{ request('status') === 'Processing' ? ' selected' : '' }}>Processing</option>
                        <option value="Cancelled"{{ request('status') === 'Cancelled' ? ' selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="departure_date" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Departure Date</label>
                    <div class="flex items-center gap-2">
                        <input id="departure_date" name="departure_date" type="date" value="{{ request('departure_date') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Flights Table Card --}}
        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Current Flight Inventory</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Showing scheduled flights, seat occupancy and booking status.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                        {{ $tickets->total() }} total flights
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white">
                <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-4">Airline & Flight</th>
                            <th class="px-5 py-4">Route</th>
                            <th class="px-5 py-4">Departure</th>
                            <th class="px-5 py-4">Arrival / Return</th>
                            <th class="px-5 py-4 text-center">Total Seats</th>
                            <th class="px-5 py-4 text-center">Available</th>
                            <th class="px-5 py-4 text-center">Booked</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">
                                        {{ $ticket->airlineMaster?->name ?? $ticket->airline }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="font-mono text-[11px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">
                                            {{ $ticket->flight_number }}
                                        </span>
                                        @if($ticket->ticket_type)
                                            <span class="text-[10px] text-slate-400 font-semibold">({{ $ticket->ticket_type }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $ticket->departureAirport && $ticket->arrivalAirport ? $ticket->departureAirport->code . ' ➔ ' . $ticket->arrivalAirport->code : $ticket->route }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $ticket->departureAirport?->city ?? '' }} to {{ $ticket->arrivalAirport?->city ?? '' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $ticket->departure_date?->format('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-mono">
                                        <i class="bi bi-clock mr-0.5"></i> {{ $ticket->departure_time ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $ticket->arrivalAirport?->code ?? 'N/A' }} · {{ $ticket->arrival_time ?? '-' }}
                                    </div>
                                    @if($ticket->return_date)
                                        <div class="text-[11px] text-indigo-600 font-medium mt-0.5">
                                            <i class="bi bi-arrow-repeat"></i> Return: {{ $ticket->return_date->format('d M') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-slate-900">
                                    {{ $ticket->total_seats }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 font-bold text-emerald-700 border border-emerald-200">
                                        {{ $ticket->available_seats }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 font-bold text-slate-700">
                                        {{ $ticket->booked_seats }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $ticket->status === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($ticket->status === 'Processing' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($ticket->status === 'Cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600')) }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.airline-flights.show', $ticket) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition" title="View Flight Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.airline-flights.edit', $ticket) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition" title="Edit Ticket">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.airline-flights.show', $ticket) }}#bookings" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition" title="View Bookings">
                                            <i class="bi bi-ticket-detailed"></i>
                                        </a>
                                        <form action="{{ route('admin.airline-flights.destroy', $ticket) }}" method="POST" class="inline-flex" onsubmit="return confirm('Are you sure you want to delete this flight ticket?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-rose-500 shadow-xs hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center text-slate-400 font-medium">
                                    <i class="bi bi-airplane text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-sm font-semibold text-slate-700">No flight tickets found.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Use the "Add Flight Ticket" tab above to create inventory entries.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    {{-- TAB 2: Add / Upload Flight Ticket Form --}}
    <div id="tab-pane-upload-ticket" class="tab-pane hidden space-y-6">
        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Upload New Flight Inventory</h3>
                    <p class="text-xs text-slate-500 mt-1">Fill out outbound schedule, return legs, seat allocation, and fare pricing.</p>
                </div>
                <button type="button" onclick="switchTab('inventory')" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    <i class="bi bi-arrow-left"></i> Back to Inventory
                </button>
            </div>

            <form action="{{ route('admin.airline-ticket-management.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- 1. Flight Identity Section --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">1</span>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Flight & Airline Identity</h4>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Registered Airline</label>
                            <select name="airline_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Select master airline</option>
                                @foreach($airlines as $airline)
                                    <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>{{ $airline->name }} ({{ $airline->code }})</option>
                                @endforeach
                            </select>
                            <input type="text" name="airline" value="{{ old('airline') }}" placeholder="Or custom airline name" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Flight Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="flight_number" value="{{ old('flight_number') }}" placeholder="e.g. PK-741" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Ticket Type <span class="text-rose-500">*</span></label>
                            <select name="ticket_type" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="One-way" {{ old('ticket_type') === 'One-way' ? 'selected' : '' }}>One-way</option>
                                <option value="Round-trip" {{ old('ticket_type', 'Round-trip') === 'Round-trip' ? 'selected' : '' }}>Round-trip</option>
                                <option value="Multi-city" {{ old('ticket_type') === 'Multi-city' ? 'selected' : '' }}>Multi-city</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Outbound Route Name</label>
                            <input type="text" name="route" value="{{ old('route') }}" placeholder="e.g. Islamabad - Jeddah" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Reference / PNR Code</label>
                            <input type="text" name="reference" value="{{ old('reference') }}" placeholder="e.g. PNR-98124" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Visibility Access</label>
                            <select name="visibility" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="Both" {{ old('visibility', 'Both') === 'Both' ? 'selected' : '' }}>Both (Travel Agent + Direct Customer)</option>
                                <option value="Agent Only" {{ old('visibility') === 'Agent Only' ? 'selected' : '' }}>Agent Portal Only</option>
                                <option value="Customer Only" {{ old('visibility') === 'Customer Only' ? 'selected' : '' }}>Customer Portal Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 2. Schedule & Airports Section --}}
                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold">2</span>
                            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Flight Routing & Timetable</h4>
                        </div>
                        <button type="button" id="copy-return-airport" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-100 transition">
                            <i class="bi bi-arrow-left-right"></i> Copy Outbound to Return
                        </button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Departure Airport <span class="text-rose-500">*</span></label>
                            <select id="departure_airport_id" name="departure_airport_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Select origin airport</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Departure Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="departure_date" value="{{ old('departure_date') }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Departure Time</label>
                            <input type="text" name="departure_time" value="{{ old('departure_time') }}" placeholder="23:10" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Arrival Airport <span class="text-rose-500">*</span></label>
                            <select id="arrival_airport_id" name="arrival_airport_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Select destination airport</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Arrival Time</label>
                            <input type="text" name="arrival_time" value="{{ old('arrival_time') }}" placeholder="04:25" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Return Departure Hub</label>
                            <select id="return_departure_airport_id" name="return_departure_airport_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Optional return origin</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('return_departure_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Return Date</label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Return Arrival Hub</label>
                            <select id="return_arrival_airport_id" name="return_arrival_airport_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Optional return destination</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('return_arrival_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 3. Seats & Inventory Section --}}
                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold">3</span>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Seat Inventory Allocation</h4>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Total Seats <span class="text-rose-500">*</span></label>
                            <input type="number" name="total_seats" value="{{ old('total_seats') }}" min="1" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Available Seats</label>
                            <input type="number" name="available_seats" value="{{ old('available_seats') }}" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Economy Seats</label>
                            <input type="number" name="economy_seats" value="{{ old('economy_seats') }}" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Business Seats</label>
                            <input type="number" name="business_seats" value="{{ old('business_seats') }}" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Premium Economy</label>
                            <input type="number" name="premium_economy_seats" value="{{ old('premium_economy_seats') }}" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">First Class Seats</label>
                            <input type="number" name="first_seats" value="{{ old('first_seats') }}" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Status</label>
                            <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="Approved" {{ old('status', 'Approved') === 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ old('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 4. Pricing & Policies Section --}}
                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-teal-100 text-teal-700 text-xs font-bold">4</span>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">Pricing, Baggage & Policies</h4>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Base Price</label>
                            <input type="text" name="price" value="{{ old('price') }}" placeholder="SAR 24,400" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Adult Fare</label>
                            <input type="text" name="adult_price" value="{{ old('adult_price') }}" placeholder="SAR 24,400" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Child Fare</label>
                            <input type="text" name="child_price" value="{{ old('child_price') }}" placeholder="SAR 18,000" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Infant Fare</label>
                            <input type="text" name="infant_price" value="{{ old('infant_price') }}" placeholder="SAR 5,000" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Baggage Allowance</label>
                            <input type="text" name="baggage" value="{{ old('baggage', '30KG') }}" placeholder="30KG" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Meal Service</label>
                            <input type="text" name="meal" value="{{ old('meal', 'Meal Included') }}" placeholder="Meal Included" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tax Rate</label>
                            <input type="text" name="tax_rate" value="{{ old('tax_rate', '0.08') }}" placeholder="0.08" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Service Charge Rate</label>
                            <input type="text" name="service_charge_rate" value="{{ old('service_charge_rate', '0.015') }}" placeholder="0.015" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100 transition mt-2">
                                <input type="hidden" name="refundable" value="0" />
                                <input type="checkbox" name="refundable" value="1" {{ old('refundable', 1) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Refundable Ticket Policy</div>
                                    <div class="text-[11px] text-slate-500">Allow refunds according to standard cancellation fee guidelines.</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="button" onclick="switchTab('inventory')" class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-emerald-700 transition">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Upload Flight Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB 3: Airlines & Airports Master --}}
    <div id="tab-pane-settings" class="tab-pane hidden space-y-6">
        @include('admin.airline-ticket-management-settings', ['airlines' => $airlines, 'airports' => $airports])
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Update URL hash
        window.location.hash = tabId;

        // Hide all panes
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));

        // Reset button states
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-xs sm:text-sm font-bold transition text-slate-600 hover:bg-slate-100';
        });

        // Show targeted pane
        const targetPane = document.getElementById('tab-pane-' + tabId);
        if (targetPane) {
            targetPane.classList.remove('hidden');
        }

        // Active button state
        const targetBtn = document.getElementById('tab-btn-' + tabId);
        if (targetBtn) {
            targetBtn.className = 'tab-btn inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-xs sm:text-sm font-bold transition bg-blue-600 text-white shadow-sm shadow-blue-500/20';
        }
    }

    // Handle hash on load
    document.addEventListener('DOMContentLoaded', () => {
        let hash = window.location.hash.replace('#', '');
        if (hash && (hash === 'inventory' || hash === 'upload-ticket' || hash === 'settings')) {
            switchTab(hash);
        } else {
            switchTab('inventory');
        }

        // Copy departure airport to return airport logic
        const departure = document.getElementById('departure_airport_id');
        const returnDeparture = document.getElementById('return_departure_airport_id');
        const returnArrival = document.getElementById('return_arrival_airport_id');
        const copyButton = document.getElementById('copy-return-airport');

        if (departure && returnDeparture && returnArrival && copyButton) {
            copyButton.addEventListener('click', () => {
                const selectedValue = departure.value;
                returnDeparture.value = selectedValue;
                returnArrival.value = selectedValue;
            });
        }
    });
</script>
@endsection
