@php
    $currentUser = auth()->user() ?? auth()->guard('travel_agent')->user();
    $agent = auth()->guard('travel_agent')->user() ?? $currentUser;
    $hasWebUser = auth()->check();
    $hasTravelAgentUser = auth()->guard('travel_agent')->check();
    $isCustomer = (bool) ($hasWebUser && ! $hasTravelAgentUser);
    $isVisaOfficer = false;
    $userRole = $hasTravelAgentUser ? 'travel_agent' : 'customer';

    if (! $isCustomer && ! $hasTravelAgentUser && ! $hasWebUser) {
        $isCustomer = true;
        $userRole = 'customer';
    }
@endphp

@extends('layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-2 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Customer Visa Applications</h1>
                <p class="mt-2 text-sm text-slate-500">Showing applications for your customers and assigned travel agent cases.</p>
            </div>
            <a href="{{ route('travel-agents.dashboard') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">Back to Dashboard</a>
        </div>

        <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400 font-semibold">Agent</p>
                    <p class="mt-1 text-base font-bold text-slate-900">{{ $agent->company_name ?? 'Agent' }}</p>
                </div>
                <div class="grid gap-2 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Applications</p>
                        <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $visaApplications->total() }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Visa Types</p>
                        <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $visaTypes->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 table-container">
                @if($visaApplications->isEmpty())
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                        No customer visa applications were found for your agency yet.
                    </div>
                @else
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">ID</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Customer</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Passport</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Visa Type</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Status</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Created</th>
                                <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.2em]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($visaApplications as $application)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-700">#{{ $application->id }}</td>
                                    <td class="px-4 py-4 text-slate-700">
                                        {{ $application->customer?->first_name ?? 'Unknown' }} {{ $application->customer?->last_name ?? '' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-700">{{ $application->customer?->passport_no ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $application->visaType?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($application->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500">{{ $application->created_at?->format('d M Y') }}</td>
                                    <td class="px-4 py-4">
                                        <a href="{{ route('travel-agents.customer-visa.show', $application->id) }}" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-[0.15em] text-white hover:bg-slate-800 transition">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">{{ $visaApplications->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
