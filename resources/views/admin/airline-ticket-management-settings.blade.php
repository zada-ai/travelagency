<div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="bi bi-geo-alt-fill text-sm"></i>
                </span>
                <h3 class="text-lg font-bold text-slate-900">Airports & Airlines Management</h3>
            </div>
            <p class="mt-1 text-xs font-medium text-slate-500">Configure master airline codes and airport hubs used across flight inventory.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.airlines.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-blue-500/20 hover:bg-blue-700 transition">
                <i class="bi bi-plus-lg"></i> Add Airline
            </a>
            <a href="{{ route('admin.airports.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-emerald-500/20 hover:bg-emerald-700 transition">
                <i class="bi bi-plus-lg"></i> Add Airport
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Airlines Section --}}
        <section class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <i class="bi bi-airplane-fill text-xs"></i>
                    </span>
                    <h4 class="text-sm font-bold text-slate-900">Airlines Directory</h4>
                </div>
                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-bold text-blue-700 border border-blue-100">
                    {{ count($airlines) }} registered
                </span>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
                <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($airlines as $airline)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $airline->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                        {{ $airline->code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold {{ $airline->status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $airline->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.airlines.edit', $airline) }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-xs font-medium text-slate-400">No airlines registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Airports Section --}}
        <section class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                        <i class="bi bi-geo-alt text-xs"></i>
                    </span>
                    <h4 class="text-sm font-bold text-slate-900">Airports Directory</h4>
                </div>
                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-100">
                    {{ count($airports) }} registered
                </span>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
                <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">IATA / City</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($airports as $airport)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $airport->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 mr-1">
                                        {{ $airport->code }}
                                    </span>
                                    <span class="text-slate-500 text-[11px]">{{ $airport->city ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.airports.edit', $airport) }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-xs hover:bg-slate-50 hover:border-slate-300 transition">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-xs font-medium text-slate-400">No airports registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

