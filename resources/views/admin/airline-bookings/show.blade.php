@extends('admin.layouts.app')

@section('title', 'Flight Booking #' . $flightBooking->id)
@section('page-heading', 'Flight Booking Details')
@section('page-description', 'Review passenger passports, seat allocations, payment status, and issue travel vouchers.')

@section('content')

@php
    $statusClass = match($flightBooking->status) {
        'Approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'Pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'Rejected', 'Cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200',
        default => 'bg-slate-100 text-slate-700 border border-slate-200',
    };

    $voucherPassengers = $flightBooking->passengers->map(function ($p) {
        return [
            'id' => $p->id,
            'name' => trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')),
            'passport_number' => $p->passport_number,
        ];
    });
@endphp

<div class="space-y-6">

    {{-- Session Message --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm font-semibold flex items-center gap-2 shadow-xs">
            <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Main Booking Overview Card --}}
    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
        
        {{-- Top Bar with Reference & Status --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                        {{ $flightBooking->reference ?? '#FL-' . $flightBooking->id }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Booking #{{ $flightBooking->id }}</h2>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-slate-700 font-semibold">
                        <i class="bi bi-airplane-fill text-sky-600"></i>
                        {{ $flightBooking->ticket->flight_number ?? 'Flight' }}
                    </span>
                    <span>•</span>
                    <span class="text-slate-600">{{ $flightBooking->ticket->route ?? 'N/A' }}</span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <span class="inline-flex rounded-full px-3.5 py-1 text-xs font-bold {{ $statusClass }}">
                    {{ $flightBooking->status }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-3.5 py-1 text-xs font-semibold text-slate-700">
                    <i class="bi bi-briefcase text-emerald-600"></i>
                    <span>Agent: {{ $flightBooking->agent->company_name ?? 'Direct' }}</span>
                </span>
            </div>
        </div>

        {{-- 4 Stat Tiles --}}
        <div class="mt-6 grid gap-4 grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-blue-50/50 border border-blue-100 p-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Passengers</p>
                <p class="mt-2 text-xl font-extrabold text-slate-900">{{ $flightBooking->total_passengers }} Pax</p>
                <span class="text-[11px] font-semibold text-blue-600">Confirmed seats</span>
            </div>

            <div class="rounded-2xl bg-emerald-50/50 border border-emerald-100 p-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Allocated Seats</p>
                <p class="mt-2 text-xl font-extrabold text-slate-900 truncate">{{ implode(', ', $flightBooking->seat_numbers ?? []) ?: 'Auto' }}</p>
                <span class="text-[11px] font-semibold text-emerald-600">Seat assignments</span>
            </div>

            <div class="rounded-2xl bg-teal-50/50 border border-teal-100 p-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Cabin Class</p>
                <p class="mt-2 text-xl font-extrabold text-slate-900">{{ $flightBooking->cabin_class ?? 'Economy' }}</p>
                <span class="text-[11px] font-semibold text-teal-600">Flight tier</span>
            </div>

            <div class="rounded-2xl bg-sky-50/50 border border-sky-100 p-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Grand Total</p>
                <p class="mt-2 text-xl font-extrabold text-slate-900">SAR {{ number_format($flightBooking->grand_total, 2) }}</p>
                <span class="text-[11px] font-semibold text-sky-600">Invoice amount</span>
            </div>
        </div>

        {{-- Add-ons if any --}}
        @if($flightBooking->include_visa || $flightBooking->include_transport)
            <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 sm:p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                    <i class="bi bi-stars text-emerald-600"></i>
                    <span>Included Optional Add-ons</span>
                </h3>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold text-slate-700">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-emerald-100">
                        <span>Visa Processing Service</span>
                        <span class="font-bold text-slate-900">SAR {{ number_format($flightBooking->visa_price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-emerald-100">
                        <span>Ground Transport Service</span>
                        <span class="font-bold text-slate-900">SAR {{ number_format($flightBooking->transport_price, 2) }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Contact Details & Special Requests Grid --}}
        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mb-3">
                    <i class="bi bi-person-lines-fill text-blue-600"></i>
                    <span>Primary Contact Details</span>
                </h3>
                <div class="space-y-1.5 text-sm">
                    <p class="font-bold text-slate-900">{{ $flightBooking->contact_name }}</p>
                    <p class="text-xs text-slate-600 flex items-center gap-1.5">
                        <i class="bi bi-envelope text-slate-400"></i>
                        <span>{{ $flightBooking->contact_email }}</span>
                    </p>
                    <p class="text-xs text-slate-600 flex items-center gap-1.5">
                        <i class="bi bi-telephone text-slate-400"></i>
                        <span>{{ $flightBooking->contact_phone }}</span>
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mb-3">
                    <i class="bi bi-chat-left-text-fill text-teal-600"></i>
                    <span>Special Instructions</span>
                </h3>
                <p class="text-xs text-slate-700 font-medium leading-relaxed">
                    {{ $flightBooking->special_requests ?: 'No special instructions recorded for this flight reservation.' }}
                </p>
            </div>
        </div>

        {{-- Passenger Details Cards --}}
        <div class="mt-6 pt-6 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 mb-4">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-bold">
                    <i class="bi bi-people-fill"></i>
                </span>
                <span>Passenger Manifest ({{ $flightBooking->passengers->count() }} Records)</span>
            </h3>

            <div class="grid gap-3 sm:grid-cols-2">
                @forelse($flightBooking->passengers as $index => $passenger)
                    <div class="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-xs hover:border-blue-300 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pax #{{ $index + 1 }}</span>
                                <h4 class="font-bold text-slate-900 text-sm mt-0.5">
                                    {{ $passenger->first_name ?? '' }} {{ $passenger->last_name ?? '' }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 mt-1">
                                    <span class="font-medium">Passport: <strong class="font-mono text-slate-800">{{ $passenger->passport_number ?? '-' }}</strong></span>
                                    <span>•</span>
                                    <span>{{ $passenger->gender ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span>{{ optional($passenger->date_of_birth)->format('d M Y') ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <span class="inline-flex rounded-md bg-blue-50 border border-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700">
                                {{ $passenger->passenger_type ?? 'Adult' }}
                            </span>
                        </div>

                        {{-- Document Links --}}
                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                            @if(!empty($passenger->passport_upload))
                                <a href="{{ asset('storage/' . $passenger->passport_upload) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 px-2.5 py-1 text-xs font-semibold text-blue-700 transition">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>Passport Doc</span>
                                </a>
                            @endif

                            @if(!empty($passenger->cnic_upload))
                                <a href="{{ asset('storage/' . $passenger->cnic_upload) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 px-2.5 py-1 text-xs font-semibold text-blue-700 transition">
                                    <i class="bi bi-person-badge"></i>
                                    <span>CNIC Doc</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-6 text-center text-slate-400 text-sm font-medium">
                        No passenger records attached to this booking.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Bottom Actions Bar --}}
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('admin.airline-bookings.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Flight Bookings</span>
            </a>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                @if($flightBooking->status === 'Approved')
                    @if($flightBooking->voucher)
                        <a href="{{ route('admin.vouchers.show', ['voucher' => $flightBooking->voucher->id]) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-xs font-bold shadow-md shadow-blue-500/20 transition">
                            <i class="bi bi-receipt"></i>
                            <span>View Flight Voucher</span>
                        </a>
                    @else
                        <button type="button" onclick="openVoucherModal('{{ route('admin.vouchers.generate.flight', $flightBooking) }}', @json($voucherPassengers))" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:opacity-95 transition">
                            <i class="bi bi-plus-circle"></i>
                            <span>Generate Voucher</span>
                        </button>
                    @endif
                @endif

                @if($flightBooking->status !== 'Approved')
                    <a href="{{ route('admin.airline-bookings.confirm', $flightBooking) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 text-xs font-bold shadow-md shadow-emerald-500/20 transition">
                        <i class="bi bi-check-lg text-sm"></i>
                        <span>Approve Booking</span>
                    </a>
                @endif

                @if(! in_array($flightBooking->status, ['Cancelled', 'Rejected'], true))
                    <form action="{{ route('admin.airline-bookings.status.update', $flightBooking) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-600 hover:text-white px-4 py-2.5 text-xs font-bold transition">
                            <i class="bi bi-x-lg"></i>
                            <span>Reject Booking</span>
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.airline-bookings.destroy', $flightBooking) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this flight booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 p-2.5 transition" title="Delete Booking">
                        <i class="bi bi-trash3 text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

{{-- Generate Voucher Modal (Blue & Green Theme) --}}
<div id="voucherModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="w-full max-w-xl rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden my-8">
        <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-blue-600 to-emerald-600 px-6 py-4 text-white">
            <div class="flex items-center gap-2">
                <i class="bi bi-receipt text-lg"></i>
                <h2 class="text-base sm:text-lg font-bold">Generate Flight Voucher</h2>
            </div>
            <button type="button" onclick="closeVoucherModal()" class="rounded-lg p-1 text-white/80 hover:bg-white/10 hover:text-white transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <form id="voucherGenerateForm" method="POST" enctype="multipart/form-data" class="space-y-4 p-6">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Admin / Company Name <span class="text-rose-500">*</span></label>
                <input type="text" name="admin_company_name" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Hujaj Umrah Services" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Admin / Company Logo <span class="text-rose-500">*</span></label>
                <input type="file" name="admin_company_logo" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-slate-200 bg-slate-50 p-2 text-xs font-medium text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-bold hover:file:bg-blue-700" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Transport Type (Optional)</label>
                <select name="transport_type" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">None / Self Arranged</option>
                    <option value="Camry">Camry</option>
                    <option value="Staria">Staria</option>
                    <option value="GMC">GMC</option>
                    <option value="Hiace">Hiace</option>
                    <option value="Coaster">Coaster</option>
                    <option value="Bus">Bus</option>
                </select>
            </div>
            <div id="voucherPassengerFields" class="space-y-3"></div>
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeVoucherModal()" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:opacity-95 transition">Issue Voucher</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVoucherModal(action, passengers = []) {
        const form = document.getElementById('voucherGenerateForm');
        form.action = action;

        const container = document.getElementById('voucherPassengerFields');
        container.innerHTML = '';

        if (!passengers.length) {
            const notice = document.createElement('div');
            notice.className = 'rounded-xl bg-amber-50 border border-amber-200 p-3.5 text-xs text-amber-800 font-semibold';
            notice.textContent = 'No passenger data available for this booking.';
            container.appendChild(notice);
        }

        passengers.forEach((passenger, index) => {
            const passengerBlock = document.createElement('div');
            passengerBlock.className = 'rounded-xl bg-slate-50 border border-slate-200 p-3.5';
            passengerBlock.innerHTML = `
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Passenger Name</label>
                        <input type="text" value="${passenger.name || 'Passenger ' + (index + 1)}" class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-800" readonly>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Passport No <span class="text-rose-500">*</span></label>
                        <input type="text" name="passengers[${index}][passport_number]" value="${passenger.passport_number || ''}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-800" placeholder="Passport #" required>
                    </div>
                </div>
                <input type="hidden" name="passengers[${index}][id]" value="${passenger.id}" />
            `;
            container.appendChild(passengerBlock);
        });

        const modal = document.getElementById('voucherModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeVoucherModal() {
        const modal = document.getElementById('voucherModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
