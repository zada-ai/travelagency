@extends('admin.layouts.app')

@section('title', 'Customer Management')
@section('page-heading', 'Customer Management')
@section('page-description', 'Manage customer accounts, referrals, and profile records from the admin dashboard.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Customer Management</p>
                    <h2 class="mt-2 text-3xl font-semibold text-white">Customer Administration</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">View all registered customers and their referring agents from one central admin panel.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Total Customers</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $metrics['total'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Active</p>
                <p class="mt-3 text-3xl font-semibold text-emerald-300">{{ $metrics['active'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Inactive</p>
                <p class="mt-3 text-3xl font-semibold text-amber-300">{{ $metrics['inactive'] }}</p>
            </div>
            <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-5 shadow-xl ring-1 ring-white/5">
                <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Agent Referred</p>
                <p class="mt-3 text-3xl font-semibold text-sky-300">{{ $metrics['with_agent'] }}</p>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20">
            <form method="GET" class="grid gap-4 xl:grid-cols-[1.6fr_1fr_1fr]">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="q">Search</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Search by name, code, phone or email" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2" for="agent_id">Agent Reference</label>
                    <select id="agent_id" name="agent_id" class="w-full rounded-[24px] border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">All Customers</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->company_name }} — {{ $agent->email }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-[28px] border border-slate-800 bg-slate-900/90 shadow-2xl shadow-slate-950/20">
            <table class="min-w-full divide-y divide-slate-800 text-sm text-slate-300">
                <thead class="bg-slate-950 text-left text-xs uppercase tracking-[0.24em] text-slate-400">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Agent Reference</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Registered</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-950">
                    @forelse ($customers as $customer)
                        <tr class="border-t border-slate-800 hover:bg-slate-900/70">
                            <td class="px-6 py-4 font-medium text-slate-100">{{ $customer->id }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $customer->user?->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $customer->phone }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $customer->travelAgent?->email ?? 'Direct' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $customer->status === 'active' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-amber-500/15 text-amber-300' }}">{{ ucfirst($customer->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $customer->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center gap-2 rounded-3xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-500">View</a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center gap-2 rounded-3xl bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-100 transition hover:bg-slate-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Delete this customer permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-400">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">No customers found for the current filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-4 shadow-2xl shadow-slate-950/20">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
