@extends('layouts.dashboard')

@section('title', 'Customers')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Customers</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage registered customers and pilgrims for {{ $agent->company_name ?? 'your agency' }}.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button
                type="button"
                onclick="openAddCustomerModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Customer
            </button>
            <a href="{{ route('travel-agents.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') ?? $errors->first() }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold">&times;</button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Customers</p>
            <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Adults (> 10 Yrs)</p>
            <p class="mt-2 text-3xl font-extrabold text-blue-700">{{ $stats['adults'] }}</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Children (2 - 10 Yrs)</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $stats['children'] }}</p>
        </div>
        <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-purple-600">Infants (0 - 2 Yrs)</p>
            <p class="mt-2 text-3xl font-extrabold text-purple-700">{{ $stats['infants'] }}</p>
        </div>
    </div>

    {{-- Main Content Table Card --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        
        {{-- Table Toolbar / Search --}}
        <div class="p-5 border-b border-gray-100 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('travel-agents.customer-visa.index') }}" id="customerSearchForm" class="flex items-center gap-3 w-full sm:w-auto" onsubmit="event.preventDefault(); filterTableCustomers();">
                <div class="relative w-full sm:w-80">
                    <input
                        type="text"
                        name="search"
                        id="customerSearchInput"
                        value="{{ request('search') }}"
                        oninput="filterTableCustomers()"
                        placeholder="Search by customer name or passport..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 pl-10 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" onclick="filterTableCustomers()" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                    Search
                </button>
                <button type="button" id="clearSearchBtn" onclick="clearCustomerSearch()" class="hidden text-sm font-semibold text-gray-500 hover:text-gray-700">
                    Clear
                </button>
            </form>

            <span id="customerCountDisplay" class="text-xs font-medium text-gray-500">
                Showing {{ $customers->count() }} of {{ $customers->total() }} customer{{ $customers->total() === 1 ? '' : 's' }}
            </span>
        </div>

        {{-- Customers Table --}}
        <div class="overflow-x-auto">
            @if($customers->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">No Customers Found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request('search'))
                            No customers matched your search query. Try searching with different keywords.
                        @else
                            You haven't added any customers yet. Click "Add Customer" above or add customers during voucher creation.
                        @endif
                    </p>
                    <button
                        type="button"
                        onclick="openAddCustomerModal()"
                        class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition"
                    >
                        + Add First Customer
                    </button>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-5 py-3.5 text-left">#</th>
                            <th class="px-5 py-3.5 text-left">Customer Name</th>
                            <th class="px-5 py-3.5 text-left">Passport Number</th>
                            <th class="px-5 py-3.5 text-left">Date of Birth</th>
                            <th class="px-5 py-3.5 text-left">Age</th>
                            <th class="px-5 py-3.5 text-left">Passenger Type</th>
                            <th class="px-5 py-3.5 text-left">Registered On</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($customers as $index => $customer)
                            @php
                                $type = $customer->passenger_type;
                                $badgeColor = match($type) {
                                    'Adult' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Child (5-10)' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Child (2-5)' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Infant (0-2)' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <tr class="customer-table-row hover:bg-gray-50/70 transition" data-search="{{ strtolower($customer->name . ' ' . $customer->passport_no) }}">
                                <td class="px-5 py-4 text-gray-400 font-medium row-index">{{ $customers->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900">{{ $customer->name }}</div>
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-gray-700 tracking-wide">
                                    {{ $customer->passport_no }}
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $customer->date_of_birth?->format('d M Y') ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-800">
                                    {{ $customer->age !== null ? $customer->age . ' Yrs' : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeColor }}">
                                        {{ $type }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-500 text-xs">
                                    {{ $customer->created_at?->format('d M Y, h:i A') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form
                                        action="{{ route('travel-agents.customer-visa.destroy', $customer->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete customer {{ addslashes($customer->name) }}?');"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition"
                                            title="Delete Customer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Add Customer Modal --}}
<div id="addCustomerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Add New Customer</h3>
                <p class="text-xs text-gray-500 mt-0.5">Register a pilgrim/customer for your agency vouchers</p>
            </div>
            <button
                type="button"
                onclick="closeAddCustomerModal()"
                class="rounded-lg p-1 text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Modal Form --}}
        <form action="{{ route('travel-agents.customer-visa.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            {{-- Full Name --}}
            <div>
                <label for="modal_name" class="block text-sm font-semibold text-gray-700 mb-1">
                    Customer Full Name <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="modal_name"
                    required
                    placeholder="Enter customer full name"
                    value="{{ old('name') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            {{-- Passport Number --}}
            <div>
                <label for="modal_passport" class="block text-sm font-semibold text-gray-700 mb-1">
                    Passport Number <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="passport_no"
                    id="modal_passport"
                    required
                    placeholder="e.g. AB1234567"
                    value="{{ old('passport_no') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm uppercase focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            {{-- Date of Birth --}}
            <div>
                <label for="modal_dob" class="block text-sm font-semibold text-gray-700 mb-1">
                    Date of Birth <span class="text-rose-500">*</span>
                </label>
                <input
                    type="date"
                    name="date_of_birth"
                    id="modal_dob"
                    required
                    onchange="calculateModalAge()"
                    value="{{ old('date_of_birth') }}"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            {{-- Calculated Age & Passenger Type Preview --}}
            <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50 rounded-xl border border-gray-200 text-center">
                <div>
                    <span class="block text-[11px] uppercase font-bold text-gray-400">Calculated Age</span>
                    <span id="modalCalculatedAge" class="text-base font-bold text-gray-800">-</span>
                </div>
                <div>
                    <span class="block text-[11px] uppercase font-bold text-gray-400">Passenger Type</span>
                    <span id="modalCalculatedType" class="text-base font-bold text-blue-600">-</span>
                </div>
            </div>

            {{-- Modal Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button
                    type="button"
                    onclick="closeAddCustomerModal()"
                    class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition"
                >
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddCustomerModal() {
        document.getElementById('addCustomerModal').classList.remove('hidden');
        document.getElementById('modal_name').focus();
    }

    function closeAddCustomerModal() {
        document.getElementById('addCustomerModal').classList.add('hidden');
    }

    function calculateModalAge() {
        const dobVal = document.getElementById('modal_dob').value;
        if (!dobVal) {
            document.getElementById('modalCalculatedAge').textContent = '-';
            document.getElementById('modalCalculatedType').textContent = '-';
            return;
        }

        const birthDate = new Date(dobVal);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        let type = 'Adult';
        if (age <= 2) {
            type = 'Infant (0-2)';
        } else if (age <= 5) {
            type = 'Child (2-5)';
        } else if (age <= 10) {
            type = 'Child (5-10)';
        }

    function filterTableCustomers() {
        const input = document.getElementById('customerSearchInput');
        const query = (input ? input.value : '').toLowerCase().trim();
        const rows = document.querySelectorAll('.customer-table-row');
        const clearBtn = document.getElementById('clearSearchBtn');
        const countDisplay = document.getElementById('customerCountDisplay');

        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        let visibleCount = 0;
        rows.forEach(row => {
            const searchData = (row.dataset.search || '').toLowerCase();
            if (!query || searchData.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (countDisplay) {
            countDisplay.textContent = `Showing ${visibleCount} customer${visibleCount === 1 ? '' : 's'}`;
        }
    }

    function clearCustomerSearch() {
        const input = document.getElementById('customerSearchInput');
        if (input) {
            input.value = '';
        }
        filterTableCustomers();
    }

    // Run on initial load if search has pre-filled value
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('customerSearchInput');
        if (input && input.value.trim().length > 0) {
            filterTableCustomers();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAddCustomerModal();
        }
    });
</script>
@endsection
