@extends('admin.layouts.app')

@section('title', 'Travel Agent Management')
@section('page-heading', 'Travel Agent Management')
@section('page-description', 'Review agent applications, manage approvals, and export partner data from the central admin dashboard.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Travel Agent Management</p>
                    <h2 class="mt-2 text-3xl font-semibold text-white">Travel Agent Management</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Review operators, update approval status, and export agent records from the central admin dashboard.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.agents.export.csv', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Export CSV</a>
                    <a href="{{ route('admin.agents.export.excel', request()->query()) }}" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Export Excel</a>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Total Agents</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $metrics['total'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Pending</p>
                <p class="mt-3 text-3xl font-semibold text-amber-300">{{ $metrics['pending'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Approved</p>
                <p class="mt-3 text-3xl font-semibold text-emerald-300">{{ $metrics['approved'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800/90 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Rejected</p>
                <p class="mt-3 text-3xl font-semibold text-rose-300">{{ $metrics['rejected'] }}</p>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.6fr_1fr_1fr]">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="q">Search</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Search by company, owner, email or phone" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="country">Country</label>
                    <select id="country" name="country" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @php
            $pageAgents = $agents->getCollection();
            $parentAgents = $pageAgents->whereNull('parent_agent_id')->values();
            $childGroups = $pageAgents->whereNotNull('parent_agent_id')->groupBy('parent_agent_id');
            $orphanChildren = $pageAgents->whereNotNull('parent_agent_id')->filter(function ($agent) use ($parentAgents) {
                return !$parentAgents->contains('id', $agent->parent_agent_id);
            })->values();
        @endphp

        <div class="overflow-x-auto rounded-[28px] border border-slate-800 bg-slate-900/90 shadow-2xl shadow-slate-950/20">
            <table class="min-w-full divide-y divide-slate-800 text-sm text-slate-300">
                <thead class="bg-slate-950 text-left text-xs uppercase tracking-[0.24em] text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Company</th>
                        <th class="px-6 py-4">Owner</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Country</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created By</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-950">
                    @forelse ($parentAgents as $agent)
                        <tr class="border-t border-slate-800 bg-slate-900">
                            <td class="px-6 py-4 font-semibold text-white">
                                <button type="button" onclick="toggleSubAgentRows('parent-{{ $agent->id }}')" class="inline-flex items-center gap-2 text-left">
                                    <span id="toggle-icon-{{ $agent->id }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-slate-200 transition">▶</span>
                                    {{ $agent->company_name }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-slate-200">{{ trim($agent->first_name.' '.$agent->last_name) }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $agent->email }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $agent->mobile }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $agent->country }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $agent->status === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($agent->status === 'Rejected' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $agent->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">Self</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="inline-flex items-center gap-2 rounded-3xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">View</a>
                                    <a href="{{ route('admin.agents.edit', $agent) }}" class="inline-flex items-center gap-2 rounded-3xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-700">Edit</a>
                                    @if ($agent->status !== 'Approved')
                                        <form method="POST" action="{{ route('admin.agents.approve', $agent) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-slate-950 transition hover:bg-emerald-400">Approve</button>
                                        </form>
                                    @endif
                                    @if ($agent->status !== 'Rejected')
                                        <form method="POST" action="{{ route('admin.agents.reject', $agent) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="remarks" value="Rejected by admin" />
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-400">Reject</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="inline" onsubmit="return confirm('Delete this agent permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-slate-700 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @if ($childGroups->has($agent->id))
                            @foreach ($childGroups[$agent->id] as $child)
                                <tr class="sub-agent-row hidden border-t border-slate-800 bg-slate-950" data-parent="parent-{{ $agent->id }}">
                                    <td class="px-6 py-4 pl-16 text-slate-200">{{ $child->company_name }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ trim($child->first_name.' '.$child->last_name) }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $child->email }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $child->mobile }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $child->country }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $child->status === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($child->status === 'Rejected' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $child->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $agent->company_name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.agents.show', $child) }}" class="inline-flex items-center gap-2 rounded-3xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">View</a>
                                            <a href="{{ route('admin.agents.edit', $child) }}" class="inline-flex items-center gap-2 rounded-3xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-700">Edit</a>
                                            @if ($child->status !== 'Approved')
                                                <form method="POST" action="{{ route('admin.agents.approve', $child) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-slate-950 transition hover:bg-emerald-400">Approve</button>
                                                </form>
                                            @endif
                                            @if ($child->status !== 'Rejected')
                                                <form method="POST" action="{{ route('admin.agents.reject', $child) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="remarks" value="Rejected by admin" />
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-400">Reject</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.agents.destroy', $child) }}" class="inline" onsubmit="return confirm('Delete this agent permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-slate-700 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-600">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">No travel agents found.</td>
                        </tr>
                    @endforelse

                    @if ($orphanChildren->isNotEmpty())
                        <tr class="border-t border-slate-800 bg-slate-900">
                            <td colspan="8" class="px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Sub-agents without parent rows in current page</td>
                        </tr>
                        @foreach ($orphanChildren as $child)
                            <tr class="border-t border-slate-800 bg-slate-950">
                                <td class="px-6 py-4 font-semibold text-white">{{ $child->company_name }}</td>
                                <td class="px-6 py-4 text-slate-200">{{ trim($child->first_name.' '.$child->last_name) }}</td>
                                <td class="px-6 py-4 text-slate-200">{{ $child->email }}</td>
                                <td class="px-6 py-4 text-slate-200">{{ $child->mobile }}</td>
                                <td class="px-6 py-4 text-slate-200">{{ $child->country }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $child->status === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($child->status === 'Rejected' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $child->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ optional($child->parentAgent)->company_name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.agents.show', $child) }}" class="inline-flex items-center gap-2 rounded-3xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">View</a>
                                        <a href="{{ route('admin.agents.edit', $child) }}" class="inline-flex items-center gap-2 rounded-3xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-700">Edit</a>
                                        @if ($child->status !== 'Approved')
                                            <form method="POST" action="{{ route('admin.agents.approve', $child) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-slate-950 transition hover:bg-emerald-400">Approve</button>
                                            </form>
                                        @endif
                                        @if ($child->status !== 'Rejected')
                                            <form method="POST" action="{{ route('admin.agents.reject', $child) }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="remarks" value="Rejected by admin" />
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-400">Reject</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.agents.destroy', $child) }}" class="inline" onsubmit="return confirm('Delete this agent permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-slate-700 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-600">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/20">
            {{ $agents->links() }}
        </div>
    </div>

    <script>
        function toggleSubAgentRows(groupId) {
            const icon = document.getElementById('toggle-icon-' + groupId.split('-').pop());
            const rows = document.querySelectorAll('.sub-agent-row[data-parent="' + groupId + '"]');
            const isExpanded = icon.textContent.trim() === '▼';

            rows.forEach(row => row.classList.toggle('hidden', isExpanded));
            icon.textContent = isExpanded ? '▶' : '▼';
        }
    </script>
@endsection
