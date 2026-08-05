@extends('admin.layouts.app')

@section('title', 'Airlines')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Airlines</h1>
            <p class="text-sm text-slate-500">Manage airline master data used for ticket inventory.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.airlines.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">Add Airline</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Code</th>
                        <th class="px-4 py-3 text-left font-semibold">Country</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse($airlines as $airline)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3">{{ $airline->name }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $airline->code }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $airline->country ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $airline->status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ $airline->status }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.airlines.edit', $airline) }}" class="rounded-md bg-slate-900 px-3 py-1 text-sm font-medium text-white transition hover:bg-slate-700">Edit</a>
                                    <form action="{{ route('admin.airlines.destroy', $airline) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this airline?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-rose-600 px-3 py-1 text-sm font-medium text-white transition hover:bg-rose-500">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">No airlines found. Add one to start.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $airlines->links() }}</div>
</div>
@endsection
