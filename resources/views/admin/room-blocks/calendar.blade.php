@extends('admin.layouts.app')

@section('title', 'Room Block Calendar')

@section('content')
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Room Block Calendar</h1>
            <p class="text-sm text-slate-500">View room booking and block status for the selected hotel.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.room-blocks.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Back to blocks</a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-6">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Hotel</label>
                <select name="hotel_id" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <option value="">All hotels</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ $hotelId == $hotel->id ? 'selected' : '' }}>{{ $hotel->hotel_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Month</label>
                <input type="month" name="year_month" value="{{ $start->format('Y-m') }}" class="w-full rounded-md border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500" />
            </div>
            <div class="col-span-2 flex items-end justify-end gap-2">
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Show calendar</button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Room</th>
                    @foreach($days as $day)
                        }}{{ 
                        <th class="px-2 py-3 text-center font-semibold text-xs uppercase">{{ 
                            \Illuminate\Support\Carbon::parse($day)->format('d')
                        }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @foreach($roomCalendar as $item)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3">{{ $item['room']->room_number }}</td>
                        @foreach($days as $date)
                            <td class="px-2 py-2 text-center text-xs {{ $item['statusMap'][$date] === 'Booked' ? 'bg-rose-100 text-rose-700' : ($item['statusMap'][$date] === 'Blocked' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }} rounded-lg">
                                {{ $item['statusMap'][$date] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
