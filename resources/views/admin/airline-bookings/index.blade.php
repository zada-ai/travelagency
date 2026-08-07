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
                                        @php
                                            $voucherPassengers = $booking->passengers->map(function ($p) {
                                                return [
                                                    'id' => $p->id,
                                                    'name' => trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')),
                                                    'passport_number' => $p->passport_number,
                                                ];
                                            });
                                        @endphp
                                        <button type="button"
                                            data-action="{{ route('admin.vouchers.generate.flight', $booking) }}"
                                            data-passengers='@json($voucherPassengers)'
                                            onclick="openVoucherModal(this)"
                                            class="rounded-2xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500"
                                        >Generate Voucher</button>
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

    {{-- Generate Voucher Modal --}}
    <div id="voucherModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-xl rounded-3xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-semibold">Generate Voucher</h2>
                <button type="button" onclick="closeVoucherModal()" class="text-slate-500 hover:text-slate-800">Close</button>
            </div>
            <form id="voucherGenerateForm" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Admin / Company Name</label>
                    <input type="text" name="admin_company_name" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Admin / Company Logo</label>
                    <input type="file" name="admin_company_logo" accept=".jpg,.jpeg,.png,.webp" class="mt-2 w-full text-sm" required>
                </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Transport Type (optional)</label>
                                <select name="transport_type" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                                    <option value="">None</option>
                                    <option value="Camry">Camry</option>
                                    <option value="Staria">Staria</option>
                                    <option value="GMC">GMC</option>
                                    <option value="Hiace">Hiace</option>
                                    <option value="Coaster">Coaster</option>
                                    <option value="Bus">Bus</option>
                                </select>
                            </div>
                <div id="voucherPassengerFields" class="space-y-4"></div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeVoucherModal()" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm text-slate-700">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Create Voucher</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openVoucherModal(button) {
            const form = document.getElementById('voucherGenerateForm');
            const passengers = JSON.parse(button.dataset.passengers || '[]');
            form.action = button.dataset.action;

            const container = document.getElementById('voucherPassengerFields');
            container.innerHTML = '';

            if (!passengers.length) {
                const notice = document.createElement('div');
                notice.className = 'rounded-2xl bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800';
                notice.textContent = 'No passenger data available for this booking.';
                container.appendChild(notice);
            }

            passengers.forEach((passenger, index) => {
                const passengerBlock = document.createElement('div');
                passengerBlock.className = 'rounded-2xl bg-slate-50 border border-slate-200 p-4';
                passengerBlock.innerHTML = `
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700">Passenger</label>
                            <input type="text" value="${passenger.name || 'Passenger ' + (index + 1)}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-900" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Passport Number</label>
                            <input type="text" name="passengers[${index}][passport_number]" value="${passenger.passport_number || ''}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900" placeholder="Enter passport #" required>
                        </div>
                    </div>
                    <input type="hidden" name="passengers[${index}][id]" value="${passenger.id}" />
                `;
                container.appendChild(passengerBlock);
            });

            document.getElementById('voucherModal').classList.remove('hidden');
        }

        function closeVoucherModal() {
            document.getElementById('voucherModal').classList.add('hidden');
        }
    </script>
@endsection
