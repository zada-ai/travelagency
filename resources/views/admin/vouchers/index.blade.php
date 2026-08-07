@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Voucher Management</h1>
            <p class="text-sm text-slate-500 mt-1">
                Manage new flight and package vouchers separately from the legacy voucher system.
            </p>
        </div>

        <form method="GET"
              action="{{ route('admin.vouchers.index') }}"
              class="flex flex-wrap items-center gap-2">

            <input
                name="q"
                value="{{ request('q') }}"
                placeholder="Search voucher, customer, booking ref"
                class="border border-slate-200 rounded-2xl px-3 py-2 shadow-sm text-slate-700 focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
            />

            <select name="type" class="border border-slate-200 rounded-2xl px-3 py-2 shadow-sm text-slate-700 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="">All types</option>
                <option value="flight" {{ request('type') === 'flight' ? 'selected' : '' }}>
                    Flight
                </option>
                <option value="package" {{ request('type') === 'package' ? 'selected' : '' }}>
                    Package
                </option>
            </select>

            <button class="px-4 py-2 rounded-lg bg-slate-900 text-white">
                Filter
            </button>
        </form>
    </div>


    {{-- ========================================================= --}}
    {{-- FLIGHT BOOKINGS READY FOR VOUCHER --}}
    {{-- ========================================================= --}}

    <div class="bg-white border border-slate-200 shadow-sm rounded-3xl p-6 mb-10">

        <div class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            <div class="space-y-3">
                <div class="text-lg font-semibold text-slate-900">
                    Confirmed Flight Bookings
                </div>
                <p class="max-w-2xl text-sm leading-6 text-slate-500">
                    Confirmed flight bookings without a voucher are shown here. Generate a voucher directly from the action column.
                </p>
            </div>

        </div>


        <div class="overflow-x-auto rounded-3xl border border-slate-200">

            <table class="min-w-full text-sm divide-y divide-slate-200">

                <thead class="bg-slate-50 text-slate-500 uppercase tracking-[0.12em] text-[11px]">
                    <tr>
                        <th class="px-4 py-4 font-semibold text-left">Booking Ref</th>
                        <th class="px-4 py-4 font-semibold text-left">Customer</th>
                        <th class="px-4 py-4 font-semibold text-left">Flight / Airline</th>
                        <th class="px-4 py-4 font-semibold text-left">Amount</th>
                        <th class="px-4 py-4 font-semibold text-left">Booking Date</th>
                        <th class="px-4 py-4 font-semibold text-left">Status</th>
                        <th class="px-4 py-4 font-semibold text-left">Action</th>
                    </tr>
                </thead>


                <tbody>

                @forelse($approvedFlightBookings as $booking)

                    <tr class="border-b border-slate-200 bg-white hover:bg-slate-50 transition-colors">

                        {{-- Booking Ref --}}
                        <td class="px-4 py-4 font-semibold text-slate-900 whitespace-nowrap">
                            {{ $booking->reference ?? 'N/A' }}
                        </td>


                        {{-- Customer --}}
                        <td class="px-4 py-4 text-slate-600">

                            {{ $booking->contact_name
                                ?? optional($booking->user)->name
                                ?? 'N/A'
                            }}

                            @if($booking->contact_email)
                                <div class="text-xs text-slate-400">
                                    {{ $booking->contact_email }}
                                </div>
                            @endif

                        </td>


                        {{-- Flight --}}
                        <td class="px-4 py-4 text-slate-600">

                            @if($booking->ticket)

                                <div class="font-semibold">
                                    {{ $booking->ticket->flight_number ?? '-' }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ $booking->ticket->airline ?? '-' }}
                                </div>

                            @else

                                <span class="text-slate-400">
                                    Flight information unavailable
                                </span>

                            @endif

                        </td>


                        {{-- Amount --}}
                        <td class="px-4 py-4 font-semibold text-slate-900">
                            SAR {{ number_format((float) $booking->grand_total, 2) }}
                        </td>


                        {{-- Booking Date --}}
                        <td class="px-4 py-4 text-slate-600">

                            {{ $booking->created_at
                                ? $booking->created_at->format('d M Y')
                                : '-'
                            }}

                        </td>


                        {{-- Status --}}
                        <td class="px-4 py-4">

                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                {{ $booking->status }}
                            </span>

                        </td>


                        {{-- Action --}}
                        <td class="px-4 py-4">

                            @if($booking->voucher)

                                <div class="flex flex-wrap gap-2 items-center">

                                    <a
                                        href="{{ route('admin.vouchers.show', ['voucher' => $booking->voucher->id]) }}"
                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-sky-600 hover:bg-sky-50 transition"
                                    >
                                        View Voucher
                                    </a>


                                    <a
                                        href="{{ route('admin.vouchers.download', ['voucher' => $booking->voucher->id]) }}"
                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition"
                                    >
                                        Download PDF
                                    </a>


                                    <button
                                        type="button"
                                        onclick="window.open('{{ route('admin.vouchers.download', ['voucher' => $booking->voucher->id]) }}', '_blank')"
                                        class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition"
                                    >
                                        Print
                                    </button>

                                </div>

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

                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500 transition"
                                    onclick="openVoucherModal('{{ route('admin.vouchers.generate.flight', $booking) }}', @json($voucherPassengers))"
                                >
                                    Generate Voucher
                                </button>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500">
                            No flight vouchers are available for approved bookings.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-10">

        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900">
                Generated Vouchers
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                All vouchers generated through the new voucher system.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm divide-y divide-slate-200">

                <thead class="bg-slate-50 text-slate-600 uppercase tracking-[0.12em] text-[11px]">

                    <tr>

                        <th class="px-4 py-4 font-semibold text-left">Voucher</th>
                        <th class="px-4 py-4 font-semibold text-left">Booking Ref</th>
                        <th class="px-4 py-4 font-semibold text-left">Customer</th>
                        <th class="px-4 py-4 font-semibold text-left">Type</th>
                        <th class="px-4 py-4 font-semibold text-left">Status</th>
                        <th class="px-4 py-4 font-semibold text-left">Created</th>
                        <th class="px-4 py-4 font-semibold text-left">Actions</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($vouchers as $v)

                    @php
                        $flightBooking = $v->flightBooking;
                        $packageBooking = $v->packageBooking;

                        $booking = $flightBooking ?? $packageBooking;

                        $customerName =
                            optional($flightBooking)->contact_name
                            ?? optional(optional($flightBooking)->user)->name
                            ?? optional(optional($packageBooking)->user)->name
                            ?? 'N/A';

                        $bookingReference =
                            optional($flightBooking)->reference
                            ?? optional($packageBooking)->reference_number
                            ?? 'N/A';

                        $type = $flightBooking ? 'Flight' : 'Package';
                    @endphp


                    <tr class="border-b border-slate-200 bg-white hover:bg-slate-50 transition-colors">


                        {{-- Voucher --}}
                        <td class="px-4 py-4 font-semibold text-slate-900 whitespace-nowrap">
                            {{ $v->voucher_number }}
                        </td>


                        {{-- Booking Ref --}}
                        <td class="px-4 py-4 text-slate-600">
                            {{ $bookingReference }}
                        </td>


                        {{-- Customer --}}
                        <td class="px-4 py-4 text-slate-600">
                            {{ $customerName }}
                        </td>


                        {{-- Type --}}
                        <td class="px-4 py-4">

                            @if($type === 'Flight')

                                <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-semibold text-sky-700">
                                    Flight
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-violet-100 px-2.5 py-1 text-[11px] font-semibold text-violet-700">
                                    Package
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                {{ $v->status }}
                            </span>
                        </td>


                        {{-- Created --}}
                        <td class="px-4 py-4 text-slate-500 text-sm">

                            {{ $v->created_at
                                ? $v->created_at->format('d M Y')
                                : '-'
                            }}

                        </td>


                        {{-- Actions --}}
                        <td class="px-4 py-4">

                            <div class="flex flex-wrap gap-2 items-center">

                                <a
                                    href="{{ route('admin.vouchers.show', ['voucher' => $v->id]) }}"
                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-sky-600 hover:bg-sky-50 transition"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route('admin.vouchers.download', ['voucher' => $v->id]) }}"
                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition"
                                >
                                    Download
                                </a>


                                <button
                                    type="button"
                                    onclick="window.open('{{ route('admin.vouchers.download', ['voucher' => $v->id]) }}', '_blank')"
                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition"
                                >
                                    Print
                                </button>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="7" class="p-8 text-center text-slate-500">
                            No vouchers have been generated yet.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="p-4">
            {{ $vouchers->links() }}
        </div>

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
                <div id="voucherPassengerFields" class="space-y-4"></div>
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
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeVoucherModal()" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm text-slate-700">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Create Voucher</button>
                </div>
            </form>
        </div>
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
            notice.className = 'rounded-2xl bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800';
            notice.textContent = 'No passenger data available for this booking.';
            container.appendChild(notice);
        }

        passengers.forEach((passenger, index) => {
            const passengerBlock = document.createElement('div');
            passengerBlock.className = 'rounded-2xl bg-slate-50 border border-slate-200 p-4';

            const grid = document.createElement('div');
            grid.className = 'grid gap-3 sm:grid-cols-3';

            const infoCol = document.createElement('div');
            infoCol.className = 'sm:col-span-2';

            const passengerLabel = document.createElement('label');
            passengerLabel.className = 'block text-sm font-semibold text-slate-700';
            passengerLabel.textContent = 'Passenger';

            const passengerInput = document.createElement('input');
            passengerInput.type = 'text';
            passengerInput.value = passenger.name || 'Passenger ' + (index + 1);
            passengerInput.readOnly = true;
            passengerInput.className = 'mt-2 w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-900';

            infoCol.appendChild(passengerLabel);
            infoCol.appendChild(passengerInput);

            const passportCol = document.createElement('div');

            const passportLabel = document.createElement('label');
            passportLabel.className = 'block text-sm font-semibold text-slate-700';
            passportLabel.textContent = 'Passport Number';

            const passportInput = document.createElement('input');
            passportInput.type = 'text';
            passportInput.name = `passengers[${index}][passport_number]`;
            passportInput.value = passenger.passport_number || '';
            passportInput.placeholder = 'Enter passport #';
            passportInput.required = true;
            passportInput.className = 'mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-900';

            passportCol.appendChild(passportLabel);
            passportCol.appendChild(passportInput);

            const hiddenId = document.createElement('input');
            hiddenId.type = 'hidden';
            hiddenId.name = `passengers[${index}][id]`;
            hiddenId.value = passenger.id;

            grid.appendChild(infoCol);
            grid.appendChild(passportCol);
            passengerBlock.appendChild(grid);
            passengerBlock.appendChild(hiddenId);

            container.appendChild(passengerBlock);
        });

        document.getElementById('voucherModal').classList.remove('hidden');
    }

    function closeVoucherModal() {
        document.getElementById('voucherModal').classList.add('hidden');
    }
</script>
@endsection