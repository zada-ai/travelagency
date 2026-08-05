@extends('visa_officer.layouts.app')

@section('title', 'Visa Office Dashboard')

@section('content')
    <section class="glass-panel rounded-3xl p-6 border border-slate-200">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-600 font-bold">Visa Processing Desk</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-900">Welcome back, {{ $agent->name ?? 'Officer' }}</h2>
            </div>
            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 px-5 py-3 text-xs text-emerald-800 font-bold">Active Session</div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Assigned</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalAssigned ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Pending Reviews</div>
                <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $pending ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Under Review</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $underReview ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Documents Required</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $documentsRequired ?? 0 }}</div>
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Approved</div>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $approved ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Rejected</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $rejected ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Issued Today</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $issuedToday ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Today's Tasks</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $todaysTasks ?? 0 }}</div>
            </div>
        </div>
    </section>

    <section class="glass-panel rounded-3xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Assigned Applications</h3>
                <p class="text-sm text-slate-500">Quick view of your most recent assigned cases.</p>
            </div>
            <a href="{{ route('visa-office.assigned') }}" class="text-xs font-semibold text-blue-600">View all</a>
        </div>

        @if($recentApplications->isEmpty())
            <div class="text-sm text-slate-500">No recent assigned applications.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Customer</th>
                            <th class="px-3 py-2">Passport</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApplications as $app)
                            <tr class="border-t">
                                <td class="px-3 py-2">#{{ $app->id }}</td>
                                <td class="px-3 py-2">{{ $app->customer_name }}</td>
                                <td class="px-3 py-2">{{ $app->passport_number }}</td>
                                <td class="px-3 py-2">{{ $app->status }}</td>
                                <td class="px-3 py-2"><a href="{{ route('visa-office.applications.show', $app) }}" class="text-xs font-semibold text-blue-600">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="glass-panel rounded-3xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Document Verification Queue</h3>
                <p class="text-sm text-slate-500">Applications requiring document follow-up.</p>
            </div>
            <a href="{{ route('visa-office.document.queue') }}" class="text-xs font-semibold text-blue-600">View queue</a>
        </div>

        @if($pendingReviews->isEmpty())
            <div class="text-sm text-slate-500">No pending documents right now.</div>
        @else
            <div class="grid gap-4">
                @foreach($pendingReviews as $app)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900">#{{ $app->id }} — {{ $app->customer_name }}</div>
                            <div class="text-xs text-slate-500">Passport: {{ $app->passport_number }}</div>
                        </div>
                        <a href="{{ route('visa-office.applications.show', $app) }}" class="text-xs font-semibold text-emerald-600">Verify</a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="glass-panel rounded-3xl p-6 border border-slate-200 grid gap-6 lg:grid-cols-2">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Recent Issued Visas</h3>
            @if($recentlyIssuedVisas->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No issued visas yet.</p>
            @else
                <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    @foreach($recentlyIssuedVisas as $app)
                        <li>#{{ $app->id }} — {{ $app->customer_name }} ({{ $app->status }})</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">Notifications</h3>
            @if($recentNotifications->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No recent notifications.</p>
            @else
                <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    @foreach($recentNotifications as $notification)
                        <li>{{ $notification->message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
