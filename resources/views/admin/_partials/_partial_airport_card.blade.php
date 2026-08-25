<div class="rounded-3xl bg-slate-950 p-5 shadow-lg border border-slate-800 hover:shadow-xl transition">
    <div class="flex justify-between items-center mb-3">
        <h4 class="text-lg font-semibold text-white">{{ $airport->name }}</h4>
        <a href="{{ route('admin.airports.edit', $airport) }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-500">Edit</a>
    </div>
    <p class="text-sm text-slate-400 mb-1">Code: <span class="text-slate-200">{{ $airport->code }}</span></p>
    <p class="text-sm text-slate-400">City: <span class="text-slate-200">{{ $airport->city ?? '—' }}</span></p>
</div>
