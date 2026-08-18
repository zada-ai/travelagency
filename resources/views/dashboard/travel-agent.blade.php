@php
    $title = 'Travel Agent Dashboard';
    $currentUser = $currentUser ?? auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = $agent ?? $currentUser;
    $userRole = $userRole ?? ($agent instanceof \App\Models\TravelAgent ? 'travel_agent' : 'web_user');
    $isCustomer = false;
    $isVisaOfficer = false;
@endphp

@extends('layouts.dashboard')

@section('content')
    <section class="glass-panel rounded-[30px] border border-slate-200/80 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-blue-600">Agent Dashboard</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Welcome back, {{ $viewAgent->company_name ?? $viewAgent->name ?? 'Agent' }}</h1>
                <p class="mt-2 text-sm text-slate-500">Track bookings, commission, visa activity, and customer requests from one place.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold uppercase tracking-[0.24em] text-slate-600">Portal: Agent</div>
        </div>
    </section>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="glass-panel rounded-[24px] border border-slate-200/80 p-4">
            <div class="text-[10px] font-bold uppercase tracking-[0.26em] text-slate-400">Bookings</div>
            <div class="mt-3 text-3xl font-extrabold text-slate-900">{{ $totalBookings ?? 0 }}</div>
        </div>
        <div class="glass-panel rounded-[24px] border border-slate-200/80 p-4">
            <div class="text-[10px] font-bold uppercase tracking-[0.26em] text-slate-400">Tickets</div>
            <div class="mt-3 text-3xl font-extrabold text-slate-900">{{ $totalTickets ?? 0 }}</div>
        </div>
        <div class="glass-panel rounded-[24px] border border-slate-200/80 p-4">
            <div class="text-[10px] font-bold uppercase tracking-[0.26em] text-slate-400">Commission</div>
            <div class="mt-3 text-3xl font-extrabold text-emerald-600">{{ $commission ?? 0 }}</div>
        </div>
        <div class="glass-panel rounded-[24px] border border-slate-200/80 p-4">
            <div class="text-[10px] font-bold uppercase tracking-[0.26em] text-slate-400">Sub Agents</div>
            <div class="mt-3 text-3xl font-extrabold text-slate-900">{{ $subAgents->count() }}</div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5">
            <h2 class="text-lg font-bold text-slate-900">Recent Bookings</h2>
            @if(($recentBookings ?? collect())->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No recent bookings yet.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($recentBookings as $booking)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $booking->booking_reference ?? $booking->reference_number ?? 'Booking' }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->created_at?->format('d M Y') ?? '' }}</div>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ $booking->status ?? 'Active' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5">
            <h2 class="text-lg font-bold text-slate-900">Sub Agents</h2>
            @if($subAgents->isEmpty())
                <p class="mt-3 text-sm text-slate-500">You do not have any sub agents yet.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($subAgents as $subAgent)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">{{ $subAgent->company_name ?? $subAgent->name ?? 'Sub Agent' }}</div>
                            <div class="text-xs text-slate-500">{{ $subAgent->email ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
