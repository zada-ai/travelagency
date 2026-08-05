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
        @if(session('success'))
            <div class="rounded-[28px] border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-200 shadow-sm ring-1 ring-emerald-500/10">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-[28px] border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-200 shadow-sm ring-1 ring-rose-500/10">
                <p class="font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-slate-100">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Airline Ticket Management</p>
                    <h2 class="mt-2 text-3xl font-semibold text-white">Airline Ticket Management</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Manage airline tickets, flights, bookings and seat inventory.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="#upload-ticket" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/></svg>
                        Upload Ticket
                    </a>
                    <a href="{{ route('admin.airline-flights.index') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-800 bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a1 1 0 00-.894.553L7.382 6H4a1 1 0 000 2h2.618l-1.724 2.447A1 1 0 004 11h3a1 1 0 00.894-.553L9.618 8H14a1 1 0 000-2H9.618l1.488-2.447A1 1 0 0010 3z"/></svg>
                        Flight Management
                    </a>
                    <a href="{{ route('admin.airlines.index') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-800 bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm2 0h10v2H5V5zm0 4h10v2H5V9zm0 4h7v2H5v-2z"/></svg>
                        Airlines
                    </a>
                    <a href="{{ route('admin.airline-bookings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-800 bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 3h8a1 1 0 010 2H6a1 1 0 010-2zm0 4h5a1 1 0 110 2H6a1 1 0 110-2z"/></svg>
                        View Bookings
                    </a>
                </div>
            </div>
        </div>

        @include('admin.airline-ticket-management-settings', ['airlines' => $airlines, 'airports' => $airports])

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Total Flights</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($totalFlights) }}</p>
                <p class="mt-2 text-sm text-slate-400">Total scheduled flights in the system.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Active Flights</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($activeFlights) }}</p>
                <p class="mt-2 text-sm text-slate-400">Flights currently approved or processing.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Available Seats</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($availableSeats) }}</p>
                <p class="mt-2 text-sm text-slate-400">Seats ready to book across all flights.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Booked Seats</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($bookedSeats) }}</p>
                <p class="mt-2 text-sm text-slate-400">Seats already reserved by agents.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Pending Bookings</p>
                <p class="mt-3 text-3xl font-semibold text-amber-300">{{ number_format($pendingBookingsCount) }}</p>
                <p class="mt-2 text-sm text-slate-400">Bookings awaiting confirmation.</p>
            </div>
            <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Confirmed Bookings</p>
                <p class="mt-3 text-3xl font-semibold text-emerald-300">{{ number_format($confirmedBookingsCount) }}</p>
                <p class="mt-2 text-sm text-slate-400">Bookings that are confirmed.</p>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.6fr_1fr_1fr_1fr]">
                <div>
                    <label for="search" class="block text-sm font-semibold text-slate-300 mb-2">Search</label>
                    <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Search tickets, routes, references" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none transition focus:border-blue-500 focus:ring-blue-500/20" />
                </div>
                <div>
                    <label for="airline" class="block text-sm font-semibold text-slate-300 mb-2">Airline</label>
                    <select id="airline" name="airline" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none transition focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Airlines</option>
                        @foreach($airlineNames as $airline)
                            <option value="{{ $airline }}" {{ request('airline') === $airline ? 'selected' : '' }}>{{ $airline }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-300 mb-2">Status</label>
                    <select id="status" name="status" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none transition focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Statuses</option>
                        <option value="Pending"{{ request('status') === 'Pending' ? ' selected' : '' }}>Pending</option>
                        <option value="Approved"{{ request('status') === 'Approved' ? ' selected' : '' }}>Approved</option>
                        <option value="Processing"{{ request('status') === 'Processing' ? ' selected' : '' }}>Processing</option>
                        <option value="Cancelled"{{ request('status') === 'Cancelled' ? ' selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="departure_date" class="block text-sm font-semibold text-slate-300 mb-2">Departure Date</label>
                    <input id="departure_date" name="departure_date" type="date" value="{{ request('departure_date') }}" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none transition focus:border-blue-500 focus:ring-blue-500/20" />
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <section id="upload-ticket" class="col-span-1 w-full min-w-0 lg:col-span-5 rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Upload new ticket</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Add a flight inventory entry</h3>
                    </div>
                </div>

                <form action="{{ route('admin.airline-ticket-management.store') }}" method="POST" class="mt-6 grid gap-4 sm:grid-cols-2">
                    @csrf

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Airline</span>
                        <select name="airline_id" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Select airline</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>{{ $airline->name }} ({{ $airline->code }})</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-500">Choose a registered airline or enter a custom airline name below.</p>
                        @error('airline_id')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                        <input type="text" name="airline" value="{{ old('airline') }}" placeholder="Custom airline name" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('airline')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Route</span>
                        <input type="text" name="route" value="{{ old('route') }}" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        <p class="mt-2 text-xs text-slate-500">Enter the outbound route, for example <span class="font-semibold">Islamabad - Jeddah</span>. Use the optional return date for the return leg.</p>
                        @error('route')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Flight Number</span>
                        <input type="text" name="flight_number" value="{{ old('flight_number') }}" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('flight_number')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Ticket Type</span>
                        <select name="ticket_type" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="One-way"{{ old('ticket_type') === 'One-way' ? ' selected' : '' }}>One-way</option>
                            <option value="Round-trip"{{ old('ticket_type') === 'Round-trip' ? ' selected' : '' }}>Round-trip</option>
                            <option value="Multi-city"{{ old('ticket_type') === 'Multi-city' ? ' selected' : '' }}>Multi-city</option>
                        </select>
                        @error('ticket_type')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Reference</span>
                        <input type="text" name="reference" value="{{ old('reference') }}" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('reference')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Flight Visibility</span>
                        <select name="visibility" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="Both"{{ old('visibility', 'Both') === 'Both' ? ' selected' : '' }}>Both (Agent + Customer)</option>
                            <option value="Agent Only"{{ old('visibility') === 'Agent Only' ? ' selected' : '' }}>Agent Only</option>
                            <option value="Customer Only"{{ old('visibility') === 'Customer Only' ? ' selected' : '' }}>Customer Only</option>
                        </select>
                        @error('visibility')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure Airport</span>
                        <select id="departure_airport_id" name="departure_airport_id" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Select departure airport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                            @endforeach
                        </select>
                        @error('departure_airport_id')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                        <button type="button" id="copy-return-airport" class="mt-3 inline-flex items-center justify-center rounded-full bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-700">
                            Copy departure airport to return fields
                        </button>
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure Date</span>
                        <input type="date" name="departure_date" value="{{ old('departure_date') }}" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('departure_date')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure Time</span>
                        <input type="text" name="departure_time" value="{{ old('departure_time') }}" placeholder="23:10" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('departure_time')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Arrival Airport</span>
                        <select id="arrival_airport_id" name="arrival_airport_id" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Select arrival airport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                            @endforeach
                        </select>
                        @error('arrival_airport_id')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Arrival Time</span>
                        <input type="text" name="arrival_time" value="{{ old('arrival_time') }}" placeholder="04:25" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('arrival_time')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Return Departure Airport</span>
                        <select id="return_departure_airport_id" name="return_departure_airport_id" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Select return departure airport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('return_departure_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                            @endforeach
                        </select>
                        @error('return_departure_airport_id')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Return Date</span>
                        <input type="date" name="return_date" value="{{ old('return_date') }}" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        <p class="mt-2 text-xs text-slate-500">Optional return date for a reverse or return leg.</p>
                        @error('return_date')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Return Arrival Airport</span>
                        <select id="return_arrival_airport_id" name="return_arrival_airport_id" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="">Select return arrival airport</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ old('return_arrival_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->code }} — {{ $airport->city }} ({{ $airport->name }})</option>
                            @endforeach
                        </select>
                        @error('return_arrival_airport_id')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Baggage</span>
                        <input type="text" name="baggage" value="{{ old('baggage') }}" placeholder="30KG" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('baggage')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Meal</span>
                        <input type="text" name="meal" value="{{ old('meal') }}" placeholder="Meal Included" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('meal')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>


                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Refundable</span>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="hidden" name="refundable" value="0" />
                            <input type="checkbox" name="refundable" value="1" {{ old('refundable') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500" />
                            <span class="text-slate-100">Enable refundable fares</span>
                        </div>
                        @error('refundable')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Total Seats</span>
                        <input type="number" name="total_seats" value="{{ old('total_seats') }}" min="1" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('total_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Available Seats</span>
                        <input type="number" name="available_seats" value="{{ old('available_seats', old('total_seats')) }}" min="0" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('available_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Price</span>
                        <input type="text" name="price" value="{{ old('price') }}" placeholder="SAR 24,400" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('price')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Status</span>
                        <select name="status" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="Pending"{{ old('status') === 'Pending' ? ' selected' : '' }}>Pending</option>
                            <option value="Approved"{{ old('status') === 'Approved' ? ' selected' : '' }}>Approved</option>
                            <option value="Processing"{{ old('status') === 'Processing' ? ' selected' : '' }}>Processing</option>
                            <option value="Cancelled"{{ old('status') === 'Cancelled' ? ' selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Adult Price</span>
                        <input type="text" name="adult_price" value="{{ old('adult_price') }}" placeholder="SAR 24,400" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('adult_price')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Child Price</span>
                        <input type="text" name="child_price" value="{{ old('child_price') }}" placeholder="SAR 18,000" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('child_price')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Infant Price</span>
                        <input type="text" name="infant_price" value="{{ old('infant_price') }}" placeholder="SAR 5,000" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('infant_price')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Economy Seats</span>
                        <input type="number" name="economy_seats" value="{{ old('economy_seats') }}" min="0" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('economy_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Premium Economy Seats</span>
                        <input type="number" name="premium_economy_seats" value="{{ old('premium_economy_seats') }}" min="0" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('premium_economy_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Business Seats</span>
                        <input type="number" name="business_seats" value="{{ old('business_seats') }}" min="0" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('business_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">First Class Seats</span>
                        <input type="number" name="first_seats" value="{{ old('first_seats') }}" min="0" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('first_seats')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Tax Rate</span>
                        <input type="text" name="tax_rate" value="{{ old('tax_rate', '0.08') }}" placeholder="0.08" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('tax_rate')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Service Charge Rate</span>
                        <input type="text" name="service_charge_rate" value="{{ old('service_charge_rate', '0.015') }}" placeholder="0.015" class="mt-2 w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                        @error('service_charge_rate')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                    </label>

                    <div class="sm:col-span-2 text-right">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 w-full rounded-3xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 sm:w-auto">
                            Upload Ticket
                        </button>
                    </div>
                </form>
            </section>

            <section class="min-w-0 lg:col-span-7 rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Recent Tickets</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Latest flight inventory</h3>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-3xl bg-slate-800 px-4 py-2 text-xs uppercase tracking-[0.28em] text-slate-400">{{ $tickets->total() }} flights</span>
                        <span class="rounded-3xl bg-slate-800 px-4 py-2 text-xs uppercase tracking-[0.28em] text-slate-400">Page {{ $tickets->currentPage() }}</span>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto rounded-[28px] border border-slate-800 bg-slate-950/80">
                    <table class="min-w-full divide-y divide-slate-800 text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/90 text-slate-400 text-xs uppercase tracking-[0.24em]">
                            <tr>
                                <th class="px-5 py-4">Airline</th>
                                <th class="px-5 py-4">Flight No</th>
                                <th class="px-5 py-4">Route</th>
                                <th class="px-5 py-4">Departure</th>
                                <th class="px-5 py-4">Arrival</th>
                                <th class="px-5 py-4">Return</th>
                                <th class="px-5 py-4">Seats</th>
                                <th class="px-5 py-4">Available</th>
                                <th class="px-5 py-4">Booked</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 bg-slate-950">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-900/80 transition">
                                    <td class="px-5 py-4 font-semibold text-white">{{ $ticket->airlineMaster?->name ?? $ticket->airline }}{{ $ticket->airlineMaster?->code ? ' (' . $ticket->airlineMaster->code . ')' : '' }}</td>
                                    <td class="px-5 py-4">{{ $ticket->flight_number }}</td>
                                    <td class="px-5 py-4">{{ $ticket->departureAirport && $ticket->arrivalAirport ? $ticket->departureAirport->code . ' - ' . $ticket->arrivalAirport->code : $ticket->route }}</td>
                                    <td class="px-5 py-4">{{ $ticket->departure_date?->format('d M') ?? '-' }} · {{ $ticket->departure_time }}</td>
                                    <td class="px-5 py-4">{{ $ticket->arrivalAirport?->code ?? 'N/A' }} · {{ $ticket->arrival_time }}</td>
                                    <td class="px-5 py-4">{{ $ticket->return_route ?? 'N/A' }} · {{ $ticket->return_date?->format('d M') ?? 'N/A' }}</td>
                                    <td class="px-5 py-4">{{ $ticket->total_seats }}</td>
                                    <td class="px-5 py-4">{{ $ticket->available_seats }}</td>
                                    <td class="px-5 py-4">{{ $ticket->booked_seats }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $ticket->status === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($ticket->status === 'Processing' ? 'bg-amber-500/15 text-amber-300' : ($ticket->status === 'Cancelled' ? 'bg-rose-500/15 text-rose-300' : 'bg-slate-700/15 text-slate-200')) }}">{{ $ticket->status }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.airline-flights.show', $ticket) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-700" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.airline-flights.edit', $ticket) }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 010 2.828l-10 10A2 2 0 016 16H4a1 1 0 01-1-1v-2a2 2 0 01.586-1.414l10-10a2 2 0 012.828 0z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.airline-flights.show', $ticket) }}#bookings" class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-600" title="Bookings">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2H4zm3 7a1 1 0 112 0v2a1 1 0 11-2 0v-2zm3-4a1 1 0 10-2 0v1a1 1 0 102 0V7z" clip-rule="evenodd" /></svg>
                                            </a>
                                            <form action="{{ route('admin.airline-flights.destroy', $ticket) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this flight?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-400" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1H4v10a2 2 0 002 2h8a2 2 0 002-2V5h.5a.5.5 0 000-1H15V3a1 1 0 00-1-1H6zm2 4a.5.5 0 011 0v8a.5.5 0 01-1 0V6zm4 0a.5.5 0 011 0v8a.5.5 0 01-1 0V6z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-5 py-12 text-center text-sm text-slate-500">No tickets found yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 rounded-[28px] border border-slate-800 bg-slate-950/90 p-4">
                    {{ $tickets->links() }}
                </div>
            </section>
        </div>
    </div>

    <script>
        (() => {
            const departure = document.getElementById('departure_airport_id');
            const returnDeparture = document.getElementById('return_departure_airport_id');
            const returnArrival = document.getElementById('return_arrival_airport_id');
            const copyButton = document.getElementById('copy-return-airport');

            if (!departure || !returnDeparture || !returnArrival || !copyButton) {
                return;
            }

            copyButton.addEventListener('click', () => {
                const selectedValue = departure.value;
                returnDeparture.value = selectedValue;
                returnArrival.value = selectedValue;

                returnDeparture.dispatchEvent(new Event('change', { bubbles: true }));
                returnArrival.dispatchEvent(new Event('change', { bubbles: true }));
            });
        })();
    </script>
@endsection
