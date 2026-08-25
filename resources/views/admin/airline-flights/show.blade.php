@extends('admin.layouts.app')

@section('title', 'Flight Details')
@section('page-heading', 'Flight Details')
@section('page-description', 'Review flight availability, current bookings, and monitor ticket statistics.')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Flight</h3>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $ticket->flight_number }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $ticket->airline }} · {{ $ticket->route }}</p>
                    <div class="mt-5 space-y-3 text-sm text-slate-700">
                        <div class="flex items-center justify-between"><span>Departure</span><span>{{ $ticket->departure_time }} · {{ $ticket->departure_date?->format('d M Y') }}</span></div>
                        <div class="flex items-center justify-between"><span>Arrival</span><span>{{ $ticket->arrival_time }} · {{ $ticket->return_date?->format('d M Y') }}</span></div>
                        <div class="flex items-center justify-between"><span>Seats</span><span>{{ $ticket->available_seats }} / {{ $ticket->total_seats }}</span></div>
                        <div class="flex items-center justify-between"><span>Economy</span><span>{{ $ticket->getClassAvailableSeats('Economy') }} / {{ $ticket->economy_seats }}</span></div>
                        <div class="flex items-center justify-between"><span>Premium Economy</span><span>{{ $ticket->getClassAvailableSeats('Premium Economy') }} / {{ $ticket->premium_economy_seats }}</span></div>
                        <div class="flex items-center justify-between"><span>Business</span><span>{{ $ticket->getClassAvailableSeats('Business') }} / {{ $ticket->business_seats }}</span></div>
                        <div class="flex items-center justify-between"><span>First</span><span>{{ $ticket->getClassAvailableSeats('First') }} / {{ $ticket->first_seats }}</span></div>
                        <div class="flex items-center justify-between"><span>Status</span><span>{{ $ticket->status }}</span></div>
                    </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Confirmed bookings</h3>
                            <p class="mt-2 text-sm text-slate-500">Bookings that have been confirmed for this flight.</p>
                        </div>
                        <div class="rounded-3xl bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-600">{{ $ticket->approved_bookings_count }} confirmed</div>
                    </div>
                    @php $approvedBookings = $ticket->bookings->where('status', 'Confirmed'); @endphp
                    @if($approvedBookings->isEmpty())
                        <p class="mt-4 text-sm text-slate-500">No approved bookings yet.</p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($approvedBookings as $booking)
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Booking #{{ $booking->id }}</p>
                                            <p class="text-sm text-slate-500">Passengers: {{ $booking->total_passengers }}</p>
                                        </div>
                                        <div class="text-sm text-slate-600">
                                            <div class="flex items-center gap-2"><span class="font-semibold">Departure:</span> {{ $ticket->departure_date?->format('d M Y') }}</div>
                                            <div class="flex items-center gap-2"><span class="font-semibold">Return:</span> {{ $ticket->return_date?->format('d M Y') ?? 'N/A' }}</div>
                                            <div class="flex items-center gap-2"><span class="font-semibold">Approved:</span> {{ $booking->updated_at?->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Booking summary</h3>
                        <p class="mt-2 text-sm text-slate-500">Current passengers and recent reservations.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mt-6 rounded-3xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-6 rounded-3xl border border-rose-100 bg-rose-50 p-4 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Total bookings</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $ticket->bookings_count }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Booked seats</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $ticket->booked_seats }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Available seats</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $ticket->available_seats }}</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <form action="{{ route('admin.airline-flights.status.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Approve Flight</button>
                    </form>
                    <form action="{{ route('admin.airline-flights.status.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="rounded-2xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-600">Reject Flight</button>
                    </form>
                </div>

                <div id="bookings" class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h4 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Recent bookings</h4>
                    @if($ticket->bookings->isEmpty())
                        <p class="mt-4 text-sm text-slate-500">No bookings have been made for this flight yet.</p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($ticket->bookings as $booking)
                                <div class="rounded-3xl border border-slate-200 p-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Booking #{{ $booking->id }}</p>
                                            <p class="text-sm text-slate-500">{{ $booking->total_passengers }} passengers · {{ ucfirst($booking->status) }}</p>
                                        </div>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $booking->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="mt-3 grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                        <div>Adults: {{ $booking->adults }}</div>
                                        <div>Children: {{ $booking->children }}</div>
                                        <div>Infants: {{ $booking->infants }}</div>
                                        <div>Class: {{ $booking->cabin_class ?? 'N/A' }}</div>
                                        <div>Grand total: {{ number_format($booking->grand_total, 2) }}</div>
                                        <div>Contact: {{ $booking->contact_name ?? 'N/A' }}</div>
                                        <div>Email: {{ $booking->contact_email ?? 'N/A' }}</div>
                                        <div>Phone: {{ $booking->contact_phone ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
