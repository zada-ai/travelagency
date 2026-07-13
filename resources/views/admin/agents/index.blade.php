<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Management | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">
            <h2 class="text-2xl font-semibold mb-6">Admin Menu</h2>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-3 bg-slate-800 hover:bg-slate-700">Dashboard</a>
                <a href="{{ route('admin.agent-management') }}" class="block rounded-2xl px-4 py-3 bg-emerald-500/10 text-emerald-300">Agent Management</a>
                <a href="{{ route('admin.user-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">User Management</a>
                <a href="{{ route('admin.customer-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Customer Management</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold">Travel Agent Applications</h1>
                        <p class="text-slate-400 mt-2">Manage registration requests, approval status, and exports.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.agents.export.csv', request()->query()) }}" class="rounded-2xl bg-slate-800 px-4 py-2 text-sm text-slate-100 hover:bg-slate-700">Export CSV</a>
                        <a href="{{ route('admin.agents.export.excel', request()->query()) }}" class="rounded-2xl bg-slate-800 px-4 py-2 text-sm text-slate-100 hover:bg-slate-700">Export Excel</a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-4 mb-8">
                    <div class="rounded-3xl bg-slate-800 p-6 shadow">
                        <p class="text-sm text-slate-400">Total Agents</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $metrics['total'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-800 p-6 shadow">
                        <p class="text-sm text-slate-400">Pending</p>
                        <p class="mt-3 text-3xl font-semibold text-amber-400">{{ $metrics['pending'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-800 p-6 shadow">
                        <p class="text-sm text-slate-400">Approved</p>
                        <p class="mt-3 text-3xl font-semibold text-emerald-400">{{ $metrics['approved'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-800 p-6 shadow">
                        <p class="text-sm text-slate-400">Rejected</p>
                        <p class="mt-3 text-3xl font-semibold text-rose-400">{{ $metrics['rejected'] }}</p>
                    </div>
                </div>

                <form method="GET" class="mb-6 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2" for="q">Search</label>
                        <input id="q" name="q" value="{{ request('q') }}" placeholder="Name, email, mobile"
                            class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2" for="status">Status</label>
                        <select id="status" name="status" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-100">
                            <option value="">All</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2" for="country">Country</label>
                        <select id="country" name="country" class="w-full rounded-2xl border border-slate-800 bg-slate-900 px-4 py-3 text-slate-100">
                            <option value="">All Countries</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="overflow-x-auto rounded-3xl border border-slate-800 bg-slate-900 shadow-xl">
                    <table class="min-w-full divide-y divide-slate-800 text-sm">
                        <thead class="bg-slate-950 text-left text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Company</th>
                                <th class="px-6 py-4">Owner</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Country</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Joined</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 bg-slate-900">
                            @forelse ($agents as $agent)
                                <tr>
                                    <td class="px-6 py-4 text-slate-200">{{ $agent->company_name }}</td>
                                    <td class="px-6 py-4 text-slate-200">{{ $agent->first_name }} {{ $agent->last_name }}</td>
                                    <td class="px-6 py-4 text-slate-200">{{ $agent->email }}</td>
                                    <td class="px-6 py-4 text-slate-200">{{ $agent->country }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $agent->status === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($agent->status === 'Rejected' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ $agent->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $agent->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <a href="{{ route('admin.agents.show', $agent) }}" class="rounded-2xl bg-emerald-500/10 px-3 py-2 text-xs text-emerald-300 hover:bg-emerald-500/15">View</a>
                                        <a href="{{ route('admin.agents.edit', $agent) }}" class="rounded-2xl bg-slate-800 px-3 py-2 text-xs text-slate-200 hover:bg-slate-700">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">No travel agent applications found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $agents->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
