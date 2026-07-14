@extends('admin.layouts.app')

@section('title', 'Room Blocks')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Room Blocks</h1>
            <p class="text-sm text-slate-500">Manage manual room blocks and unavailable room periods.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.room-blocks.create') }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">New Room Block</a>
            <a href="{{ route('admin.room-blocks.calendar') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">View Calendar</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Hotel</label>
                <select name="hotel_id" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All hotels</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ isset($filters['hotel_id']) && $filters['hotel_id'] == $hotel->id ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                <select name="status" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All statuses</option>
                    <option value="Active" {{ isset($filters['status']) && $filters['status'] === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ isset($filters['status']) && $filters['status'] === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-span-2 flex items-end justify-end gap-2">
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.room-blocks.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Hotel</th>
                    <th class="px-4 py-3 text-left font-semibold">Room</th>
                    <th class="px-4 py-3 text-left font-semibold">Blocked From</th>
                    <th class="px-4 py-3 text-left font-semibold">Blocked To</th>
                    <th class="px-4 py-3 text-left font-semibold">Reason</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($blocks as $block)
                    <tr>
                        <td class="px-4 py-3 text-slate-700">{{ $block->hotel?->hotel_name }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $block->room?->room_number ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $block->block_from->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $block->block_to->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $block->reason }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $block->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.room-blocks.edit', $block) }}" class="inline-flex items-center rounded-md bg-slate-900 px-3 py-1 text-xs font-medium text-white hover:bg-slate-700">Edit</a>
                                <form action="{{ route('admin.room-blocks.destroy', $block) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1 text-xs font-medium text-white hover:bg-rose-700" onclick="return confirm('Delete this room block?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No room blocks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $blocks->withQueryString()->links() }}</div>
</div>
@endsection
