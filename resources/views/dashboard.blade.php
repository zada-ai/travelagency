@extends('admin.layouts.app')
@section('title', 'Admin Dashboard')
@section('page-heading', 'Enterprise Dashboard')
@section('page-description', 'A premium travel ERP overview for hotel, flight, booking, and agent operations.')

@php
    use App\Models\Booking;
    use App\Models\FlightBooking;
    use App\Models\Hotel;
    use App\Models\Ticket;
    use App\Models\TravelAgent;
    use App\Models\User;
    use Illuminate\Support\Facades\DB;

    $totalRevenue = Booking::sum('grand_total') + FlightBooking::sum('grand_total');
    $bookingsToday = Booking::whereDate('created_at', today())->count() + FlightBooking::whereDate('created_at', today())->count();
    $hotelsCount = Hotel::count();
    $flightsCount = Ticket::count();
    $customersCount = User::count();
    $agentsCount = TravelAgent::count();
    $pendingPayments = Booking::where('payment_status', 'Pending')->count() + FlightBooking::where('payment_status', 'Unpaid')->count();
    $pendingBookings = Booking::where('status', 'Pending')->count() + FlightBooking::where('status', 'Pending')->count();
    $pendingApprovals = TravelAgent::where('status', 'Pending')->count();
    $recentBookings = Booking::with('hotel')->orderByDesc('created_at')->limit(6)->get();
    $topDestinations = Hotel::select('city', DB::raw('count(*) as total'))->groupBy('city')->orderByDesc('total')->limit(4)->get();
    $topAgents = TravelAgent::orderByDesc('created_at')->limit(4)->get();
    $notifications = [
        ['title' => 'Pending booking reviews', 'count' => Booking::where('status', 'Pending')->count(), 'type' => 'warning'],
        ['title' => 'Payments due', 'count' => $pendingPayments, 'type' => 'danger'],
        ['title' => 'New agent requests', 'count' => $pendingApprovals, 'type' => 'info'],
        ['title' => 'Upcoming check-ins', 'count' => Booking::whereDate('check_in', '>=', today())->count(), 'type' => 'success'],
    ];

    $monthlyLabels = [];
    $monthlyRevenue = [];
    $monthlyBookings = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $monthlyLabels[] = $month->format('M');
        $monthlyRevenue[] = Booking::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->sum('grand_total')
            + FlightBooking::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->sum('grand_total');
        $monthlyBookings[] = Booking::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count()
            + FlightBooking::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
    }

    $upcomingEvents = Booking::whereDate('check_in', '>=', today())->orderBy('check_in')->limit(5)->get();
@endphp

@section('content')
    <div class="space-y-6">
        <section class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="space-y-6">
                <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Enterprise overview</p>
                            <h2 class="mt-2 text-3xl font-semibold text-white">Premium Travel ERP Dashboard</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Monitor hotel performance, bookings, agents and revenue from a single control center.</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                            <a href="{{ route('admin.hotels.index') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">View Hotels</a>
                            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl border border-slate-800 bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Open Bookings</a>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Total Revenue</p>
                            <p class="mt-3 text-3xl font-semibold text-white">AED {{ number_format($totalRevenue, 0) }}</p>
                            <p class="mt-2 text-sm text-slate-400">Reflects hotel and flight sales</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Bookings Today</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($bookingsToday) }}</p>
                            <p class="mt-2 text-sm text-slate-400">Live booking arrivals</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Hotels</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($hotelsCount) }}</p>
                            <p class="mt-2 text-sm text-slate-400">Active properties</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-5 shadow-xl ring-1 ring-white/5">
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Flights</p>
                            <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($flightsCount) }}</p>
                            <p class="mt-2 text-sm text-slate-400">Ticket inventory</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                    <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Revenue Overview</p>
                                <h3 class="mt-2 text-2xl font-semibold text-white">Last 6 months</h3>
                            </div>
                            <span class="rounded-3xl bg-slate-800 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-slate-300">Live</span>
                        </div>
                        <div class="mt-6">
                            <canvas id="revenueChart" height="180"></canvas>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Booking Velocity</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Monthly trends</h3>
                                </div>
                                <span class="rounded-3xl bg-emerald-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-300">Growth</span>
                            </div>
                            <div class="mt-6">
                                <canvas id="bookingsChart" height="180"></canvas>
                            </div>
                        </div>
                        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Top Destinations</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-white">Most active cities</h3>
                                </div>
                            </div>
                            <div class="mt-6 space-y-3">
                                @foreach($topDestinations as $destination)
                                    <div class="rounded-3xl bg-slate-950/90 px-4 py-3 text-sm text-slate-200">
                                        <div class="flex items-center justify-between gap-4">
                                            <span>{{ $destination->city }}</span>
                                            <span class="font-semibold text-white">{{ $destination->total }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="space-y-6">
                <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Insight cards</p>
                            <h3 class="mt-2 text-2xl font-semibold text-white">Operations pulse</h3>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-3xl bg-slate-950/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Customers</p>
                            <p class="mt-2 text-3xl font-semibold text-white">{{ number_format($customersCount) }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Agents</p>
                            <p class="mt-2 text-3xl font-semibold text-white">{{ number_format($agentsCount) }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Pending Payments</p>
                            <p class="mt-2 text-3xl font-semibold text-amber-300">{{ number_format($pendingPayments) }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Pending Bookings</p>
                            <p class="mt-2 text-3xl font-semibold text-rose-300">{{ number_format($pendingBookings) }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                    <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Quick Actions</p>
                    <h3 class="mt-2 text-2xl font-semibold text-white">Launch tasks</h3>
                    <div class="mt-6 grid gap-3">
                        <a href="{{ route('admin.bookings.index') }}" class="block rounded-3xl bg-blue-600 px-4 py-4 text-sm font-semibold text-white transition hover:bg-blue-500">Manage Bookings</a>
                        <a href="{{ route('admin.hotels.create') }}" class="block rounded-3xl bg-slate-950/90 px-4 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">Add Hotel</a>
                        <a href="{{ route('admin.airline-flights.create') }}" class="block rounded-3xl bg-slate-950/90 px-4 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">Add Flight</a>
                        <a href="{{ route('admin.package-builder') }}" class="block rounded-3xl bg-slate-950/90 px-4 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">Add Package</a>
                        <a href="{{ route('admin.customer-management') }}" class="block rounded-3xl bg-slate-950/90 px-4 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">Customer CRM</a>
                        <a href="{{ route('admin.agent-management') }}" class="block rounded-3xl bg-slate-950/90 px-4 py-4 text-sm font-semibold text-white transition hover:bg-slate-800">Agent Panel</a>
                    </div>
                </div>
            </aside>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Recent Activity</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Live timeline</h3>
                    </div>
                    <span class="rounded-3xl bg-slate-800 px-4 py-2 text-xs uppercase tracking-[0.28em] text-slate-400">Latest</span>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach($notifications as $notification)
                        <div class="rounded-3xl bg-slate-950/90 p-4 ring-1 ring-white/5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $notification['title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-400">Real-time dashboard alert.</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $notification['type'] === 'success' ? 'bg-emerald-500/15 text-emerald-300' : ($notification['type'] === 'danger' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $notification['count'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Upcoming Events</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Booking calendar</h3>
                    </div>
                    <span class="rounded-3xl bg-slate-800 px-4 py-2 text-xs uppercase tracking-[0.28em] text-slate-400">Next 5</span>
                </div>
                <div class="mt-6 space-y-3">
                    @foreach($upcomingEvents as $event)
                        <div class="rounded-3xl bg-slate-950/90 p-4 ring-1 ring-white/5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">Check-in: {{ $event->hotel->hotel_name ?? 'Hotel booking' }}</p>
                                    <p class="mt-1 text-sm text-slate-400">Guest: {{ $event->contact_name }}</p>
                                </div>
                                <span class="rounded-2xl bg-blue-500/10 px-3 py-2 text-xs uppercase tracking-[0.24em] text-blue-300">{{ $event->check_in->format('d M') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Booking performance</p>
                    <h3 class="mt-2 text-2xl font-semibold text-white">Latest hotel bookings</h3>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">View all bookings</a>
            </div>
            <div class="mt-6 overflow-x-auto rounded-[28px] border border-slate-800 bg-slate-950/80">
                <table class="min-w-full divide-y divide-slate-800 text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/90 text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Booking ID</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Customer</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Hotel</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Status</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Payment</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Amount</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Date</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-[0.24em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 bg-slate-950/80">
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-white">{{ $booking->reference_number }}</td>
                                <td class="px-6 py-4">{{ $booking->contact_name }}</td>
                                <td class="px-6 py-4">{{ $booking->hotel->hotel_name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $booking->status === 'Reserved' ? 'bg-emerald-500/15 text-emerald-300' : ($booking->status === 'Cancelled' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $booking->status }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $booking->payment_status }}</td>
                                <td class="px-6 py-4">AED {{ number_format($booking->grand_total, 2) }}</td>
                                <td class="px-6 py-4">{{ $booking->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-200 hover:bg-blue-500/20">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">No recent bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[0.7fr_1.3fr]">
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Top agents</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">Rising performers</h3>
                <div class="mt-6 space-y-4">
                    @foreach($topAgents as $agent)
                        <div class="rounded-3xl bg-slate-950/90 p-4 ring-1 ring-white/5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $agent->company_name ?? trim($agent->first_name . ' ' . $agent->last_name) }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $agent->country ?? 'Unknown region' }}</p>
                                </div>
                                <span class="rounded-2xl bg-blue-500/10 px-3 py-1 text-xs uppercase tracking-[0.24em] text-blue-300">New</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Top customers</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">High-value accounts</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-950/90 p-5 ring-1 ring-white/5">
                        <p class="text-sm text-slate-400">Customers</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($customersCount) }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/90 p-5 ring-1 ring-white/5">
                        <p class="text-sm text-slate-400">Active Agents</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($agentsCount) }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    const revenueChart = document.getElementById('revenueChart');
    const bookingsChart = document.getElementById('bookingsChart');

    if (revenueChart) {
        new Chart(revenueChart, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($monthlyRevenue),
                    backgroundColor: 'rgba(56, 189, 248, 0.16)',
                    borderColor: 'rgba(56, 189, 248, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#38bdf8',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(148, 163, 184, 0.15)' }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    }

    if (bookingsChart) {
        new Chart(bookingsChart, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Bookings',
                    data: @json($monthlyBookings),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 12,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                    y: { grid: { color: 'rgba(148, 163, 184, 0.15)' }, ticks: { color: '#94a3b8', precision: 0 } }
                }
            }
        });
    }
</script>
@endpush
