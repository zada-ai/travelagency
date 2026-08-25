@extends('admin.layouts.app')

@section('title', 'Airline Bookings')
@section('page-heading', 'Flight Booking Management')
@section('page-description', 'Review travel agent airline reservations, confirm bookings, and generate flight vouchers.')

@section('content')

@php
    $totalFlightBookings = $bookings->total() ?? $bookings->count();
    $approvedCount = $bookings->where('status', 'Approved')->count();
    $pendingCount = $bookings->where('status', 'Pending')->count();
    $rejectedCount = $bookings->whereIn('status', ['Rejected', 'Cancelled'])->count();
@endphp

<div class="space-y-6">
    

    {{-- Header Banner & Stats --}}
    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600 font-bold text-sm">
                        <i class="bi bi-airplane-fill"></i>
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Airline Ticket Bookings</h2>
                </div>
                <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium">Manage and audit flight reservations submitted by registered travel agencies.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.airline-ticket-management') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition">
                    <i class="bi bi-ticket-detailed-fill text-sky-600"></i>
                    <span>Ticket Inventory</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 hover:opacity-95 transition">
                    <i class="bi bi-speedometer2"></i>
                    <span>Admin Dashboard</span>
                </a>
            </div>
        </div>

        {{-- Top Summary Stats Pill Row --}}
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3.5 pt-6 border-t border-slate-100">
            <div class="rounded-2xl bg-blue-50/50 border border-blue-100 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Bookings</p>
                <p class="mt-1.5 text-2xl font-extrabold text-slate-900">{{ number_format($totalFlightBookings) }}</p>
                <span class="text-[11px] font-semibold text-blue-600 flex items-center gap-1 mt-0.5">
                    <i class="bi bi-airplane text-xs"></i> All Flights
                </span>
            </div>

            <div class="rounded-2xl bg-emerald-50/50 border border-emerald-100 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Approved</p>
                <p class="mt-1.5 text-2xl font-extrabold text-emerald-600">{{ number_format($approvedCount) }}</p>
                <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1 mt-0.5">
                    <i class="bi bi-check-circle text-xs"></i> Confirmed Seats
                </span>
            </div>

            <div class="rounded-2xl bg-amber-50/50 border border-amber-100 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Review</p>
                <p class="mt-1.5 text-2xl font-extrabold text-amber-600">{{ number_format($pendingCount) }}</p>
                <span class="text-[11px] font-semibold text-amber-600 flex items-center gap-1 mt-0.5">
                    <i class="bi bi-clock-history text-xs"></i> Awaiting Action
                </span>
            </div>

            <div class="rounded-2xl bg-rose-50/50 border border-rose-100 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rejected / Cancelled</p>
                <p class="mt-1.5 text-2xl font-extrabold text-rose-600">{{ number_format($rejectedCount) }}</p>
                <span class="text-[11px] font-semibold text-rose-600 flex items-center gap-1 mt-0.5">
                    <i class="bi bi-x-circle text-xs"></i> Cancelled Seats
                </span>
            </div>
        </div>
    </div>

    {{-- Main Bookings Card with Grid Layout --}}
    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Flight Booking Records</h3>
                <p class="text-xs text-slate-500 mt-0.5">List of all incoming and processed ticket reservations.</p>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($bookings as $booking)
                @include('admin.airline-bookings._partial_booking_card', ['booking' => $booking])
            @empty
                <div class="col-span-full text-center text-slate-400 font-medium py-12">
                    <i class="bi bi-airplane text-4xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">No flight bookings found.</p>
                    <p class="text-xs text-slate-400 mt-0.5">Bookings created by travel agents will appear here.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

{{-- Generate Voucher Modal (Redesigned with Blue & Green Theme) --}}
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
    function openVoucherModal(button) {
        const form = document.getElementById('voucherGenerateForm');
        const passengers = JSON.parse(button.dataset.passengers || '[]');
        form.action = button.dataset.action;

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
