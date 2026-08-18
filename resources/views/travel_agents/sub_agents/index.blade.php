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

    $portalLabel = $isCustomer ? 'Customer Portal' : 'Agent Portal';
    $portalSystemLabel = $isCustomer ? 'Customer Portal System' : 'Agent Portal System';
@endphp

@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Sub-Agent Management</p>
                    <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Manage Your Sub-Agents</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-500">View and edit sub-agents created under your agency account.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('travel-agents.dashboard') }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Back to Dashboard</a>
                    <a href="{{ route('travel-agents.sub-agents.create') }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">Create Sub-Agent</a>
                </div>
            </div>

            <section class="glass-panel rounded-[2rem] p-6 shadow-xs">
                @if(session('success'))
                    <div class="mb-5 rounded-3xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-[0.16em] text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">Agent</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Created By</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr class="border-t border-slate-200 bg-slate-50">
                                <td colspan="5" class="px-4 py-4">
                                    <button type="button" class="group inline-flex w-full items-center justify-between gap-3 rounded-3xl border border-slate-200 bg-white px-4 py-4 text-left shadow-sm transition hover:border-slate-300 hover:bg-slate-50" onclick="toggleChildRows()">
                                        <span class="flex items-center gap-3">
                                            <span id="subAgentToggleIcon" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white transition duration-200">▶</span>
                                            <span>
                                                <div class="text-sm font-semibold text-slate-900">Parent Agent</div>
                                                <div class="text-xs text-slate-500">{{ $agent->company_name ?? $agent->first_name ?? 'Current Agent' }}</div>
                                            </span>
                                        </span>
                                        <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Expand to view sub-agents</span>
                                    </button>
                                </td>
                            </tr>

                            <tr class="border-t border-slate-200 bg-white">
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $agent->company_name ?? $agent->first_name ?? 'Parent Agent' }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $agent->email }}</td>
                                <td class="px-4 py-4 uppercase font-semibold text-slate-700">{{ $agent->status ?? 'Approved' }}</td>
                                <td class="px-4 py-4 text-slate-600">Self</td>
                                <td class="px-4 py-4 text-slate-600">&mdash;</td>
                            </tr>

                            @if(empty($subAgents) || $subAgents->isEmpty())
                                <tr class="child-row hidden bg-slate-50">
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">You have not created any sub-agents yet.</td>
                                </tr>
                            @else
                                @foreach($subAgents ?? [] as $subAgent)
                                    <tr class="child-row hidden border-t border-slate-200 bg-white">
                                        <td class="px-4 py-4 pl-10 font-semibold text-slate-800">{{ $subAgent->first_name ?? $subAgent->company_name ?? 'N/A' }} {{ $subAgent->last_name ?? '' }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $subAgent->email ?? 'N/A' }}</td>
                                        <td class="px-4 py-4 uppercase font-semibold text-slate-700">{{ $subAgent->status ?? 'Pending' }}</td>
                                        <td class="px-4 py-4 text-slate-600">{{ $agent->company_name ?? $agent->first_name ?? 'Parent Agent' }}</td>
                                        <td class="px-4 py-4 space-x-2">
                                            <a href="{{ route('travel-agents.sub-agents.show', $subAgent) }}" class="inline-flex items-center rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">View</a>
                                            <a href="{{ route('travel-agents.sub-agents.edit', $subAgent) }}" class="inline-flex items-center rounded-full bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">Edit</a>
                                            <form action="{{ route('travel-agents.sub-agents.destroy', $subAgent) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this sub-agent?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-full bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700 transition">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script>
        function toggleChildRows() {
            const childRows = document.querySelectorAll('.child-row');
            const icon = document.getElementById('subAgentToggleIcon');
            const isExpanded = icon.textContent.trim() === '▼';

            childRows.forEach(row => {
                row.classList.toggle('hidden', isExpanded);
            });

            icon.textContent = isExpanded ? '▶' : '▼';
        }
    </script>
@endsection
