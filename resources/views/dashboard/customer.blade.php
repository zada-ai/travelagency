@php
    $title = 'Customer Dashboard';
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $userRole = 'customer';
    $isCustomer = true;
    $isVisaOfficer = false;
@endphp

@extends('layouts.dashboard')

@section('content')
    <section class="glass-panel rounded-[30px] border border-slate-200/80 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-emerald-700">Customer Portal</span>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">Welcome back, {{ $customer?->first_name ?? $user->name }}</h1>
                <p class="mt-2 text-sm text-slate-500">Your personal dashboard shows your bookings, visa applications, and account profile in one place.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold uppercase tracking-[0.24em] text-slate-600">Role: Customer</div>
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Browse Flights</a>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5">
            <h2 class="text-lg font-bold text-slate-900">Profile</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-slate-400">Name</dt>
                    <dd class="font-semibold text-slate-800">{{ $customer?->first_name ?? $user->name }} {{ $customer?->last_name ?? '' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Email</dt>
                    <dd class="font-semibold text-slate-800">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Phone</dt>
                    <dd class="font-semibold text-slate-800">{{ $customer?->phone ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">CNIC</dt>
                    <dd class="font-semibold text-slate-800">{{ $customer?->cnic ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Nationality</dt>
                    <dd class="font-semibold text-slate-800">{{ $customer?->nationality ?? 'N/A' }}</dd>
                </div>
            </dl>
        </section>

        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5 lg:col-span-2">
            <h2 class="text-lg font-bold text-slate-900">Visa Applications</h2>
            @if($visaApplications->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No visa applications found for your account.</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-slate-500">
                            <tr>
                                <th class="pb-2">ID</th>
                                <th class="pb-2">Customer</th>
                                <th class="pb-2">Passport</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visaApplications as $application)
                                <tr class="border-t border-slate-100">
                                    <td class="py-3">#{{ $application->id }}</td>
                                    <td class="py-3">{{ $application->customer_name }}</td>
                                    <td class="py-3">{{ $application->passport_number }}</td>
                                    <td class="py-3 font-semibold text-slate-800">{{ $application->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5">
            <h2 class="text-lg font-bold text-slate-900">Hotel Bookings</h2>
            @if($hotelBookings->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No hotel bookings found for your account.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($hotelBookings as $booking)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">{{ $booking->contact_name }}</div>
                            <div class="text-xs text-slate-500">Reference: {{ $booking->reference_number ?? 'N/A' }} · Status: {{ $booking->status }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="glass-panel rounded-[26px] border border-slate-200/80 p-5">
            <h2 class="text-lg font-bold text-slate-900">Flight Bookings</h2>
            @if($flightBookings->isEmpty())
                <p class="mt-3 text-sm text-slate-500">No flight bookings found for your account.</p>
            @else
                <div class="mt-4 space-y-3">
                    @foreach($flightBookings as $booking)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">{{ $booking->contact_name }}</div>
                            <div class="text-xs text-slate-500">Reference: {{ $booking->reference ?? 'N/A' }} · Status: {{ $booking->status }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
