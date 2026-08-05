<div class="rounded-[28px] border border-slate-800/90 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20 ring-1 ring-white/5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-white">Manage Airports & Airlines</h2>
            <p class="mt-2 text-sm text-slate-400">Add or edit airlines and airports used by flight inventory.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-[20px] border border-slate-800 bg-slate-950/90 p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Airlines</h3>
                <a href="{{ route('admin.airlines.create') }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">Add Airline</a>
            </div>
            <div class="mt-4 overflow-x-auto rounded-3xl border border-slate-800 bg-slate-900/80">
                <table class="min-w-full divide-y divide-slate-800 text-sm text-slate-300">
                    <thead class="bg-slate-950/90 text-slate-400 text-xs uppercase tracking-[0.24em]">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Code</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 bg-slate-950">
                        @forelse($airlines as $airline)
                            <tr>
                                <td class="px-4 py-3">{{ $airline->name }}</td>
                                <td class="px-4 py-3">{{ $airline->code }}</td>
                                <td class="px-4 py-3">{{ $airline->status }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.airlines.edit', $airline) }}" class="rounded-2xl bg-slate-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">No airlines added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[20px] border border-slate-800 bg-slate-950/90 p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Airports</h3>
                <a href="{{ route('admin.airports.create') }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">Add Airport</a>
            </div>
            <div class="mt-4 overflow-x-auto rounded-3xl border border-slate-800 bg-slate-900/80">
                <table class="min-w-full divide-y divide-slate-800 text-sm text-slate-300">
                    <thead class="bg-slate-950/90 text-slate-400 text-xs uppercase tracking-[0.24em]">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Code</th>
                            <th class="px-4 py-3 text-left">City</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 bg-slate-950">
                        @forelse($airports as $airport)
                            <tr>
                                <td class="px-4 py-3">{{ $airport->name }}</td>
                                <td class="px-4 py-3">{{ $airport->code }}</td>
                                <td class="px-4 py-3">{{ $airport->city ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.airports.edit', $airport) }}" class="rounded-2xl bg-slate-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">No airports added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
