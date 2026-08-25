@extends('admin.layouts.app')
@section('title', 'Enterprise Overview')
@section('page-heading', 'Enterprise Overview')
@section('page-description', 'Real-time overview of hotel stays, flight bookings, revenue velocity, and agency operations.')

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
    <div class="space-y-8">
        {{-- Banner & Quick Metrics --}}
        <section class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100 mb-2">Executive Overview</span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Umrah ERP Performance</h2>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-xl">Live financial velocity, daily reservations, and operational activity across agency portals.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            <a href="{{ route('admin.hotels.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:opacity-95 transition">
                                <i class="bi bi-building"></i>
                                <span>View Hotels</span>
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                                <i class="bi bi-journal-text text-emerald-600"></i>
                                <span>All Bookings</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- 4 Stat Metric Cards --}}
                    <div class="mt-6 grid gap-4 grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl bg-gradient-to-br from-blue-50/60 to-white border border-blue-100 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Revenue</p>
                            <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-900">AED {{ number_format($totalRevenue, 0) }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-blue-600">Hotels &amp; Flights</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50/60 to-white border border-emerald-100 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Bookings Today</p>
                            <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($bookingsToday) }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-emerald-600">New arrivals</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-teal-50/60 to-white border border-teal-100 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hotels</p>
                            <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($hotelsCount) }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-teal-600">Active properties</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-sky-50/60 to-white border border-sky-100 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Flights</p>
                            <p class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($flightsCount) }}</p>
                            <p class="mt-1 text-[11px] font-semibold text-sky-600">Available tickets</p>
                        </div>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Financial Trend</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900">Revenue Velocity</h3>
                            </div>
                            <span class="rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700">Last 6 Mos</span>
                        </div>
                        <div class="mt-6 h-[200px]">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Orders</p>
                                    <h3 class="mt-1 text-lg font-bold text-slate-900">Booking Volume</h3>
                                </div>
                                <span class="rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700">Growth</span>
                            </div>
                            <div class="mt-6 h-[200px]">
                                <canvas id="bookingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aside Quick Insight Cards --}}
            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Activity Pulse</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-900">Operations Status</h3>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-blue-50/50 border border-blue-100 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Customers</p>
                            <p class="mt-1 text-xl font-extrabold text-slate-800">{{ number_format($customersCount) }}</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50/50 border border-emerald-100 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Agents</p>
                            <p class="mt-1 text-xl font-extrabold text-slate-800">{{ number_format($agentsCount) }}</p>
                        </div>
                        <div class="rounded-2xl bg-amber-50/50 border border-amber-100 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Pay</p>
                            <p class="mt-1 text-xl font-extrabold text-amber-600">{{ number_format($pendingPayments) }}</p>
                        </div>
                        <div class="rounded-2xl bg-rose-50/50 border border-rose-100 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Book</p>
                            <p class="mt-1 text-xl font-extrabold text-rose-600">{{ number_format($pendingBookings) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Quick Shortcuts</p>
                    <h3 class="mt-1 text-lg font-bold text-slate-900">Direct Actions</h3>
                    <div class="mt-4 grid gap-2.5">
                        <a href="{{ route('travel-agents.vouchers.create') }}" class="flex items-center justify-between rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-4 py-3 text-xs font-bold text-white shadow-md shadow-blue-500/15 hover:opacity-95 transition">
                            <span>Create New Voucher</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition">
                            <span>Manage Bookings</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('admin.packages.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition">
                            <span>Package Builder</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('admin.agent-management') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition">
                            <span>Agent Approvals</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </aside>
        </section>

        {{-- Recent Bookings Table Section --}}
        <section class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-block text-[11px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100 mb-1">Live Feed</span>
                    <h3 class="text-xl font-bold text-slate-900">Recent Customer Bookings</h3>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 px-4 py-2 text-xs font-bold transition">
                    <span>View all bookings</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200/80">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs">
                        <tr>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Reference</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Customer</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Hotel</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Payment</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Amount</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider">Date</th>
                            <th class="px-5 py-3.5 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-3.5 font-bold text-slate-900 font-mono">{{ $booking->reference_number }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $booking->contact_name }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $booking->hotel->hotel_name ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $booking->status === 'Reserved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($booking->status === 'Cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-semibold {{ $booking->payment_status === 'Paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ $booking->payment_status }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900">AED {{ number_format($booking->grand_total, 2) }}</td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $booking->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white px-3 py-1.5 text-xs font-bold transition">
                                        <span>View</span>
                                        <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-slate-400 font-medium">No recent bookings recorded in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    label: 'Revenue (AED)',
                    data: @json($monthlyRevenue),
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb',
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
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#64748b', font: { size: 11 } } }
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
                    backgroundColor: '#059669',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#64748b', precision: 0, font: { size: 11 } } }
                }
            }
        });
    }
</script>
@endpush
