@php
    $title = 'Visa Office Dashboard';
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = $agent ?? $currentUser;
    $userRole = 'visa_office';
    $isCustomer = false;
    $isVisaOfficer = true;
@endphp

@extends('layouts.dashboard')

@section('content')
    <section class="glass-panel rounded-[30px] border border-slate-200/80 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-emerald-600">Visa Processing Desk</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Welcome back, {{ $agent->name ?? 'Officer' }}</h1>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold uppercase tracking-[0.24em] text-emerald-700">Active Session</div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Assigned</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalAssigned ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Pending</div>
                <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $pending ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Under Review</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $underReview ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Documents Required</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $documentsRequired ?? 0 }}</div>
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Approved</div>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $approved ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Rejected</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $rejected ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Issued Today</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $issuedToday ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-400">Today's Tasks</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $todaysTasks ?? 0 }}</div>
            </div>
        </div>
    </section>

    <section class="glass-panel mt-6 rounded-[26px] border border-slate-200/80 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Assigned Applications</h2>
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
                            <tr class="border-t border-slate-100">
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
@endsection
