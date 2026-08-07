@extends('admin.layouts.airline')

@section('title', 'Airline Booking Details')
@section('page-heading', 'Airline Booking Details')
@section('page-description', 'Review the booking record and update status as needed.')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Booking #{{ $flightBooking->id }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Flight: {{ $flightBooking->ticket->flight_number ?? '-' }} · {{ $flightBooking->ticket->route ?? '-' }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">{{ $flightBooking->status }}</span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Agent: {{ $flightBooking->agent->company_name ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Total Passengers</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ $flightBooking->total_passengers }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Seats</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ implode(', ', $flightBooking->seat_numbers ?? []) }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Cabin Class</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ $flightBooking->cabin_class }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Grand Total</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">SAR {{ number_format($flightBooking->grand_total, 2) }}</p>
                </div>
            </div>

            @if($flightBooking->include_visa || $flightBooking->include_transport)
                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Optional Add-ons</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Visa processing</span>
                            <span>SAR {{ number_format($flightBooking->visa_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Transport service</span>
                            <span>SAR {{ number_format($flightBooking->transport_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Contact</h3>
                    <p class="mt-4 text-slate-900">{{ $flightBooking->contact_name }}</p>
                    <p class="text-sm text-slate-500">{{ $flightBooking->contact_email }}</p>
                    <p class="text-sm text-slate-500">{{ $flightBooking->contact_phone }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Selected Seats</h3>
                    <p class="mt-4 text-slate-900">{{ implode(', ', $flightBooking->seat_numbers ?? []) }}</p>
                    <h3 class="mt-6 text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Special Requests</h3>
                    <p class="mt-4 text-slate-900">{{ $flightBooking->special_requests ?? 'None' }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Passenger Details</h3>
                <div class="mt-4 grid gap-4">
                    @forelse($flightBooking->passengers as $passenger)
                        <div class="rounded-3xl bg-white p-4 shadow-sm">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $passenger->first_name ?? '' }} {{ $passenger->last_name ?? '' }}</p>
                                    <p class="text-sm text-slate-500">{{ $passenger->passenger_type ?? '' }}</p>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <p>{{ $passenger->gender ?? '' }}</p>
                                    <p>{{ optional($passenger->date_of_birth)->format('Y-m-d') ?? '' }}</p>
                                </div>
                            </div>
                            <div class="mt-3 grid gap-4 sm:grid-cols-3 text-sm text-slate-600">
                                <div>
                                    <p class="font-semibold text-slate-900">Passport Upload</p>
                                    @if(! empty($passenger->passport_upload))
                                        <a href="{{ asset('storage/'.$passenger->passport_upload) }}" target="_blank" class="text-blue-600 underline">View file</a>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">CNIC Upload</p>
                                    @if(! empty($passenger->cnic_upload))
                                        <a href="{{ asset('storage/'.$passenger->cnic_upload) }}" target="_blank" class="text-blue-600 underline">View file</a>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Passenger Type</p>
                                    <p>{{ $passenger->passenger_type ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl bg-white p-4 shadow-sm">
                            <p class="font-semibold text-slate-900">No passenger records found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @if($flightBooking->status === 'Approved')
                    @php
                        $voucherPassengers = $flightBooking->passengers->map(function ($p) {
                            return [
                                'id' => $p->id,
                                'name' => trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')),
                                'passport_number' => $p->passport_number,
                            ];
                        });
                    @endphp
                    @if($flightBooking->voucher)
                        <a href="{{ route('admin.vouchers.show', ['voucher' => $flightBooking->voucher->id]) }}" class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-500">View Voucher</a>
                    @else
                        <button type="button" onclick="openVoucherModal('{{ route('admin.vouchers.generate.flight', $flightBooking) }}', @json($voucherPassengers))" class="rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500">Generate Voucher</button>
                    @endif
                @endif
                @if($flightBooking->status !== 'Approved')
                    <a href="{{ route('admin.airline-bookings.confirm', $flightBooking) }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Approve Booking</a>
                @endif
                @if(! in_array($flightBooking->status, ['Cancelled', 'Rejected'], true))
                    <form action="{{ route('admin.airline-bookings.status.update', $flightBooking) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="rounded-2xl bg-rose-500 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-600">Reject</button>
                    </form>
                @endif
                <form action="{{ route('admin.airline-bookings.destroy', $flightBooking) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-2xl bg-slate-700 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-600">Delete</button>
                </form>
            </div>
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
