@extends('admin.layouts.airline')

@section('title', 'Airline Bookings')
@section('page-heading', 'Airline Booking Management')
@section('page-description', 'Review flight bookings, update statuses, and manage seat availability.')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Flight Bookings</h2>
            <p class="mt-2 text-sm text-slate-500">Bookings submitted by travel agents are listed below.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm overflow-x-auto">
            <table class="min-w-full border-collapse text-left text-sm">
                <thead class="bg-slate-950 text-white">
                    <tr>
                        <th class="px-4 py-4">ID</th>
                        <th class="px-4 py-4">Reference</th>
                        <th class="px-4 py-4">Customer</th>
                        <th class="px-4 py-4">Flight</th>
                        <th class="px-4 py-4">Route</th>
                        <th class="px-4 py-4">Travel Date</th>
                        <th class="px-4 py-4">Amount</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-900">{{ $booking->id }}</td>
                            <td class="px-4 py-4">{{ $booking->reference }}</td>
                            <td class="px-4 py-4">{{ optional($booking->user)->name ?? $booking->contact_name ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $booking->ticket->flight_number ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $booking->ticket->route ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ optional($booking->ticket->departure_date)->format('d M Y') ?? 'N/A' }}</td>
                            <td class="px-4 py-4">SAR {{ number_format($booking->grand_total, 2) }}</td>
                            <td class="px-4 py-4"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">{{ $booking->status }}</span></td>
                            <td class="px-4 py-4 space-x-2">
                                <a href="{{ route('admin.airline-bookings.show', $booking) }}" class="rounded-2xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">View</a>
                                @if($booking->status === 'Approved')
                                    @if($booking->voucher)
                                        <a href="{{ route('admin.vouchers.show', ['voucher' => $booking->voucher->id]) }}" class="rounded-2xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-500">View Voucher</a>
                                    @else
                                        <form action="{{ route('admin.vouchers.generate.flight', $booking) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="rounded-2xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500">Generate Voucher</button>
                                        </form>
                                    @endif
                                @endif
                                @if($booking->status !== 'Approved')
                                    <a href="{{ route('admin.airline-bookings.confirm', $booking) }}" class="rounded-2xl bg-emerald-500 px-3 py-2 text-xs font-semibold text-slate-950 hover:bg-emerald-400">Approve</a>
                                @endif
                                @if(! in_array($booking->status, ['Cancelled', 'Rejected'], true))
                                    <form action="{{ route('admin.airline-bookings.status.update', $booking) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Rejected" />
                                        <button type="submit" class="rounded-2xl bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600">Reject</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.airline-bookings.destroy', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-2xl bg-slate-700 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-500">No flight bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $bookings->links() }}</div>
    </div>
@endsection
