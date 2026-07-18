@extends('admin.layouts.airline')

@section('title', 'Airline Ticket Management')
@section('page-heading', 'Airline Ticket Management')
@section('page-description', 'Upload and manage airline ticket entries for travel agents.')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900">Upload New Ticket</h2>
                <p class="mt-2 text-sm text-slate-500">Add airline tickets that agents will see on the ticket dashboard.</p>

                <form action="{{ route('admin.airline-ticket-management.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Airline</span>
                            <input type="text" name="airline" value="{{ old('airline') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('airline')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Route</span>
                            <input type="text" name="route" value="{{ old('route') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('route')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Flight Number</span>
                            <input type="text" name="flight_number" value="{{ old('flight_number') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('flight_number')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Reference</span>
                            <input type="text" name="reference" value="{{ old('reference') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('reference')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure Date</span>
                            <input type="date" name="departure_date" value="{{ old('departure_date') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('departure_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Return Date</span>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('return_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Departure Time</span>
                            <input type="text" name="departure_time" value="{{ old('departure_time') }}" placeholder="23:10" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('departure_time')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Arrival Time</span>
                            <input type="text" name="arrival_time" value="{{ old('arrival_time') }}" placeholder="04:25" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('arrival_time')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Baggage</span>
                            <input type="text" name="baggage" value="{{ old('baggage') }}" placeholder="30KG" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('baggage')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Meal</span>
                            <input type="text" name="meal" value="{{ old('meal') }}" placeholder="Meal Included" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('meal')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Total Seats</span>
                            <input type="number" name="total_seats" value="{{ old('total_seats') }}" min="1" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('total_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Economy Seats</span>
                            <input type="number" name="economy_seats" value="{{ old('economy_seats') }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('economy_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Premium Economy Seats</span>
                            <input type="number" name="premium_economy_seats" value="{{ old('premium_economy_seats') }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('premium_economy_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Business Seats</span>
                            <input type="number" name="business_seats" value="{{ old('business_seats') }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('business_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">First Class Seats</span>
                            <input type="number" name="first_seats" value="{{ old('first_seats') }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('first_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Adult Price</span>
                            <input type="text" name="adult_price" value="{{ old('adult_price') }}" placeholder="SAR 24,400" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('adult_price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Child Price</span>
                            <input type="text" name="child_price" value="{{ old('child_price') }}" placeholder="SAR 18,000" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('child_price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Infant Price</span>
                            <input type="text" name="infant_price" value="{{ old('infant_price') }}" placeholder="SAR 5,000" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('infant_price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <!-- <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Infant Price</span>
                            <input type="text" name="infant_price" value="{{ old('infant_price') }}" placeholder="SAR 5,000" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('infant_price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label> -->
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Tax Rate</span>
                            <input type="text" name="tax_rate" value="{{ old('tax_rate', '0.08') }}" placeholder="0.08" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('tax_rate')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Service Charge Rate</span>
                            <input type="text" name="service_charge_rate" value="{{ old('service_charge_rate', '0.015') }}" placeholder="0.015" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('service_charge_rate')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Price</span>
                            <input type="text" name="price" value="{{ old('price') }}" placeholder="SAR 24,400" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                            @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm text-slate-700">
                            <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Status</span>
                            <select name="status" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                                <option value="Pending"{{ old('status') === 'Pending' ? ' selected' : '' }}>Pending</option>
                                <option value="Approved"{{ old('status') === 'Approved' ? ' selected' : '' }}>Approved</option>
                                <option value="Processing"{{ old('status') === 'Processing' ? ' selected' : '' }}>Processing</option>
                            </select>
                            @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <div class="block text-right">
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-400">Upload Ticket</button>
                    </div>
                </form>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Recent Tickets</h2>
                        <p class="mt-2 text-sm text-slate-500">Manage uploaded flights and monitor availability from a single panel.</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-3 text-sm text-slate-500">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $tickets->total() }}</p>
                            <p class="text-slate-400">Total tickets</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $tickets->count() }}</p>
                            <p class="text-slate-400">Shown on page</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $tickets->lastItem() ?? 0 }}</p>
                            <p class="text-slate-400">Last item</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-4">
                    @forelse ($tickets as $ticket)
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">{{ $ticket->flight_number }}</p>
                                    <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $ticket->airline }} · {{ $ticket->route }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $ticket->departure_date?->format('d M Y') ?? '-' }} · {{ $ticket->departure_time }} → {{ $ticket->arrival_time }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Ref: {{ $ticket->reference }}</p>
                                </div>
                                <div class="grid gap-2 sm:grid-cols-3 text-sm text-slate-500">
                                    <div class="rounded-3xl bg-white px-4 py-3 shadow-sm">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Seats</p>
                                        <p class="mt-1 font-semibold text-slate-900">{{ $ticket->available_seats }}/{{ $ticket->total_seats }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-white px-4 py-3 shadow-sm">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Status</p>
                                        <p class="mt-1 font-semibold text-slate-900">{{ $ticket->status }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-white px-4 py-3 shadow-sm">
                                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Booked</p>
                                        <p class="mt-1 font-semibold text-slate-900">{{ $ticket->booked_seats }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="{{ route('admin.airline-flights.show', $ticket) }}" class="inline-flex items-center rounded-2xl bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">View</a>
                                <a href="{{ route('admin.airline-flights.edit', $ticket) }}" class="inline-flex items-center rounded-2xl bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">Edit</a>
                                <form action="{{ route('admin.airline-flights.destroy', $ticket) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-2xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-400">Delete</button>
                                </form>
                                <a href="{{ route('admin.airline-flights.show', $ticket) }}#bookings" class="inline-flex items-center rounded-2xl bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-500">View Bookings</a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-slate-500">No tickets uploaded yet.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
