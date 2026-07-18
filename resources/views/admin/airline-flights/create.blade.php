@extends('admin.layouts.airline')

@section('title', 'Create Flight')
@section('page-heading', 'Create Flight')
@section('page-description', 'Add a new flight to the airline reservation system.')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.airline-flights.store') }}" method="POST" class="space-y-6">
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
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
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
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Total Seats</span>
                    <input type="number" name="total_seats" value="{{ old('total_seats', 150) }}" min="1" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('total_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Adult Price</span>
                    <input type="text" name="adult_price" value="{{ old('adult_price') }}" placeholder="SAR 24,400" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('adult_price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Economy Seats</span>
                    <input type="number" name="economy_seats" value="{{ old('economy_seats', 100) }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('economy_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Premium Economy Seats</span>
                    <input type="number" name="premium_economy_seats" value="{{ old('premium_economy_seats', 30) }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('premium_economy_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Business Seats</span>
                    <input type="number" name="business_seats" value="{{ old('business_seats', 15) }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('business_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">First Class Seats</span>
                    <input type="number" name="first_seats" value="{{ old('first_seats', 5) }}" min="0" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('first_seats')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
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
            <div class="grid gap-4 sm:grid-cols-2">
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

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Status</span>
                    <select name="status" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none">
                        <option value="Pending"{{ old('status') === 'Pending' ? ' selected' : '' }}>Pending</option>
                        <option value="Approved"{{ old('status') === 'Approved' ? ' selected' : '' }}>Approved</option>
                        <option value="Processing"{{ old('status') === 'Processing' ? ' selected' : '' }}>Processing</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm text-slate-700">
                    <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Client</span>
                    <input type="text" name="client" value="{{ old('client') }}" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 focus:border-amber-500 focus:outline-none" />
                    @error('client')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="text-right">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-400">Create Flight</button>
            </div>
        </form>
    </div>
@endsection
