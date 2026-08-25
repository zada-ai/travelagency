{{-- resources/views/agent/vouchers/create.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Add Voucher')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add Voucher</h1>
        <p class="mt-1 text-sm text-gray-500">
            Create a new Umrah voucher
        </p>
    </div>

    <form action="{{ route('travel-agents.vouchers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- =========================
            BASIC INFORMATION
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">
                    Basic Information
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Package --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Package <span class="text-red-500">*</span>
                        </label>

                        <select
    name="voucher_package_id"
    id="voucher_package_id"
    required
    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
>
    <option value="">Select Package</option>

    @foreach($packages as $package)
        <option value="{{ $package->id }}" {{ old('voucher_package_id', $packages->first()?->id) == $package->id ? 'selected' : '' }}>
            {{ $package->name }}
        </option>
    @endforeach
</select>
                    </div>
                      <div>
    <label
        for="visa_company_id"
        class="block text-sm font-semibold text-gray-700 mb-2"
    >
        Visa Company <span class="text-red-500">*</span>
    </label>

    <select
        name="visa_company_id"
        id="visa_company_id"
        required
        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
    >
        <option value="">Select Visa Company</option>

        @foreach($visaCompanies as $company)
            <option value="{{ $company->id }}" {{ old('visa_company_id', $visaCompanies->first()?->id) == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
    </select>
</div>

                    {{-- Agent Company --}}
                   <div>
    <label
        for="agent_company_id"
        class="block text-sm font-semibold text-gray-700 mb-2"
    >
        Agent Company <span class="text-red-500">*</span>
    </label>

    <select
        name="agent_company_id"
        id="agent_company_id"
        required
        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
    >
        <option value="">Select Agent Company</option>

        @foreach($agentCompanies as $company)
            <option value="{{ $company->id }}" {{ old('agent_company_id', $agentCompanies->first()?->id) == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
    </select>
</div>
                     {{-- <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Agent Company <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="agent_company"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Select Agent Company</option>
                            <option>Umrah Booking</option>
                            <option>UMRAH BOOKING (TARIQ SB)</option>
                            <option>ARYAN AIR TRAVEL</option>
                            <option>HAQ BAHOO GROUP</option>
                        </select> --}}
                    </div>

                </div>
            </div>
        </div>


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Select Ticket</h2>
                <p class="mt-1 text-sm text-gray-500">Flight details come from the selected ticket. Enter both PNRs manually.</p>
            </div>
            <div class="p-6">
                <select id="ticket_id" name="ticket_id" class="w-full rounded-lg border border-gray-300 px-4 py-3" required>
                    <option value="">Select a ticket</option>
                    @foreach($tickets as $ticket)
                        @php
                            $ticketDateTime = static function ($date, $time) {
                                return $date && $time ? $date->format('Y-m-d') . 'T' . substr((string) $time, 0, 5) : '';
                            };
                        @endphp
                        <option
                            value="{{ $ticket->id }}"
                            data-arrival-flight-no="{{ $ticket->flight_number }}"
                            data-arrival-pnr="{{ $ticket->pnr }}"
                            data-arrival-departure-time="{{ $ticketDateTime($ticket->departure_date, $ticket->departure_time) }}"
                            data-arrival-arrival-time="{{ $ticketDateTime($ticket->departure_date, $ticket->arrival_time) }}"
                            data-arrival-from="{{ $ticket->departureAirport?->name ?? $ticket->departureAirport?->code }}"
                            data-arrival-to="{{ $ticket->arrivalAirport?->name ?? $ticket->arrivalAirport?->code }}"
                            data-departure-flight-no="{{ $ticket->flight_number }}"
                            data-departure-pnr="{{ $ticket->pnr }}"
                            data-departure-departure-time="{{ $ticketDateTime($ticket->return_date, $ticket->return_departure_time) }}"
                            data-departure-arrival-time="{{ $ticketDateTime($ticket->return_date, $ticket->return_arrival_time) }}"
                            data-departure-from="{{ $ticket->returnDepartureAirport?->name ?? $ticket->returnDepartureAirport?->code }}"
                            data-departure-to="{{ $ticket->returnArrivalAirport?->name ?? $ticket->returnArrivalAirport?->code }}"
                        >
                            {{ $ticket->airline ?? 'Flight' }} - {{ $ticket->flight_number }} - {{ $ticket->route }} ({{ $ticket->departure_date?->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- =========================
            PASSENGER / PAX
        ========================== --}}
       {{-- =========================
    CUSTOMERS / PAX
========================== --}}
{{-- =========================
    PASSENGER / PAX
========================== --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Customers / Passengers
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Select existing customers or add a new customer
                </p>
            </div>

            <button
                type="button"
                onclick="openCustomerForm()"
                class="inline-flex items-center justify-center gap-2
                       px-4 py-2.5 rounded-lg bg-blue-600 text-white
                       hover:bg-blue-700 text-sm font-semibold transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Add New Customer
            </button>

        </div>

    </div>

    <div class="p-6">

        {{-- =========================
            EXISTING CUSTOMERS
        ========================== --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50/70 p-4">

            <button
                type="button"
                onclick="toggleExistingCustomers()"
                class="flex w-full items-center justify-between gap-4 text-left"
                aria-controls="existingCustomersPanel"
                aria-expanded="false"
            >
                <span>
                    <span class="block text-base font-bold text-gray-800">Select Existing Customers</span>
                    <span class="mt-1 block text-xs text-gray-500">Choose customers already registered for this agent</span>
                </span>
                <span class="flex shrink-0 items-center gap-3">
                    <span class="text-xs font-bold text-blue-600"><span id="selectedCustomerCount">0</span> Selected</span>
                    <svg id="existingCustomersIcon" class="h-5 w-5 text-gray-500 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                    </svg>
                </span>
            </button>

            <div id="existingCustomersPanel" class="hidden mt-4 border-t border-gray-200 pt-4">

            {{-- Search --}}
            <div class="relative mb-3">

                <input
                    type="text"
                    id="customerSearch"
                    oninput="filterCustomers()"
                    placeholder="Search customer by name or passport..."
                    class="w-full rounded-lg border border-gray-300
                           px-4 py-2.5 pl-10 text-sm
                           focus:border-blue-500 focus:ring-blue-500"
                >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                    />
                </svg>

            </div>

            {{-- Temporary frontend customers --}}
            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                <p class="text-xs text-gray-500">Select one or more customers to add to this voucher.</p>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="selectAllCustomers()" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100">Select All</button>
                    <button type="button" onclick="clearAllCustomers()" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100">Clear</button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-2">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="sr-only">

                            <th class="px-4 py-3 text-center w-14">
                                <input
                                    type="checkbox"
                                    id="selectAllCustomers"
                                    onchange="toggleAllCustomers(this)"
                                    class="h-4 w-4 rounded border-gray-300
                                           text-blue-600 focus:ring-blue-500"
                                >
                            </th>

                            <th class="px-3 py-2 text-left font-semibold text-gray-600">
                                Customer Name
                            </th>

                            <th class="px-3 py-2 text-left font-semibold text-gray-600">
                                Passport No
                            </th>

                            <th class="px-3 py-2 text-left font-semibold text-gray-600">
                                Date of Birth
                            </th>

                            <th class="px-3 py-2 text-center font-semibold text-gray-600">
                                Age
                            </th>

                            <th class="px-3 py-2 text-center font-semibold text-gray-600">
                                Passenger Type
                            </th>

                        </tr>
                    </thead>

                    <tbody id="existingCustomersBody">

                        {{-- TEMPORARY DATA --}}
                       @forelse($customers as $customer)

    @php
        $age = $customer->date_of_birth
            ? \Carbon\Carbon::parse($customer->date_of_birth)->age
            : null;

        if ($age === null) {
            $passengerType = 'Unknown';
        } elseif ($age >= 10) {
            $passengerType = 'Adult';
        } elseif ($age >= 5) {
            $passengerType = 'Child (5-10)';
        } elseif ($age >= 2) {
            $passengerType = 'Child (2-5)';
        } else {
            $passengerType = 'Infant (0-2)';
        }

        $searchText = strtolower(
            $customer->name . ' ' . $customer->passport_no
        );
    @endphp

    <tr
        class="customer-row block border-b border-gray-100 hover:bg-blue-50/40 transition sm:table-row"
        data-search="{{ $searchText }}"
    >

        <td class="px-4 py-3 text-center">

            <input
                type="checkbox"
                class="customer-checkbox h-4 w-4 rounded border-gray-300
                       text-blue-600 focus:ring-blue-500"
                value="{{ $customer->id }}"
                data-name="{{ $customer->name }}"
                data-passport="{{ $customer->passport_no }}"
                data-dob="{{ optional($customer->date_of_birth)->format('Y-m-d') }}"
                data-age="{{ $age }}"
                data-type="{{ $passengerType }}"
                onchange="handleCustomerSelection(this)"
            >

        </td>

        <td class="px-4 py-3 font-semibold text-gray-800">
            {{ $customer->name }}
        </td>

        <td class="px-4 py-3 font-mono text-gray-700">
            {{ $customer->passport_no }}
        </td>

        <td class="px-4 py-3 text-gray-700">
            {{ optional($customer->date_of_birth)->format('d M Y') }}
        </td>

        <td class="px-4 py-3 text-center font-semibold">
            {{ $age ?? '-' }}
        </td>

        <td class="px-4 py-3 text-center">

            @php
                $badgeClass = match ($passengerType) {
                    'Adult' => 'bg-blue-50 text-blue-700',
                    'Child (5-10)' => 'bg-amber-50 text-amber-700',
                    'Child (2-5)' => 'bg-orange-50 text-orange-700',
                    'Infant (0-2)' => 'bg-purple-50 text-purple-700',
                    default => 'bg-gray-50 text-gray-700',
                };
            @endphp

            <span class="inline-flex items-center rounded-full
                         px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                {{ $passengerType }}
            </span>

        </td>

    </tr>

@empty

    <tr>
        <td colspan="6" class="px-4 py-8 text-center">

            <div class="text-gray-400">

                <p class="text-sm font-semibold text-gray-500">
                    No customers found
                </p>

                <p class="text-xs mt-1">
                    Add a new customer to this travel agent first.
                </p>

            </div>

        </td>
    </tr>

@endforelse
                        

                    </tbody>

                </table>

            </div>

            <div class="mt-3 flex items-center justify-end">
                <button
                    type="button"
                    onclick="addSelectedExistingCustomers()"
                    class="px-4 py-2.5 rounded-lg bg-blue-600 text-white
                           text-sm font-semibold hover:bg-blue-700 transition"
                >
                    Add Selected Customers
                </button>

            </div>

        </div>


        {{-- =========================
            SELECTED CUSTOMERS
        ========================== --}}
        <div id="selectedCustomersSection" class="hidden mb-6">

            <div class="rounded-xl border border-green-200 bg-green-50/50 p-4 sm:p-5">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h3 class="text-base font-bold text-gray-800">
                            Selected Customers
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            Customers selected for this voucher
                        </p>
                    </div>

                    <span
                        id="selectedCustomersBadge"
                        class="inline-flex items-center rounded-full
                               bg-green-100 px-3 py-1 text-xs font-bold text-green-700"
                    >
                        0 Selected
                    </span>

                </div>

                <div id="selectedCustomersList" class="space-y-2"></div>

            </div>

        </div>


        {{-- =========================
            ADD NEW CUSTOMER
        ========================== --}}
        <div
            id="customerForm"
            class="hidden rounded-xl border border-blue-100 bg-blue-50/40 p-4 sm:p-5"
        >

            <div class="flex items-center justify-between mb-5">

                <div>
                    <h3 class="text-base font-bold text-gray-800">
                        Add New Customer
                    </h3>

                    <p class="text-xs text-gray-500 mt-1">
                        Enter customer passport details
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeCustomerForm()"
                    class="w-8 h-8 rounded-lg bg-white border border-gray-200
                           text-gray-500 hover:text-red-600 hover:border-red-200"
                >
                    ✕
                </button>

            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Customer Name
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="customer_name"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Enter customer name"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Passport No
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="customer_passport"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Enter passport number"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Date of Birth
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="customer_dob"
                        onchange="calculateCustomerAge()"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

            </div>

            <div class="mt-4 flex flex-wrap gap-3">

                <div class="rounded-lg bg-white border border-gray-200 px-4 py-2">
                    <span class="text-xs text-gray-500">
                        Age
                    </span>

                    <div id="customerAge" class="font-bold text-gray-800">
                        -
                    </div>
                </div>

                <div class="rounded-lg bg-white border border-gray-200 px-4 py-2">
                    <span class="text-xs text-gray-500">
                        Passenger Type
                    </span>

                    <div id="customerType" class="font-bold text-blue-600">
                        -
                    </div>
                </div>

            </div>

            <div class="mt-5 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeCustomerForm()"
                    class="px-5 py-2.5 rounded-lg border border-gray-300
                           text-gray-700 font-medium hover:bg-white"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    onclick="addCustomer()"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white
                           font-semibold hover:bg-blue-700"
                >
                    Add Customer
                </button>

            </div>

        </div>


        {{-- =========================
            CUSTOMER TOTALS
        ========================== --}}
        <div class="mt-5">

            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">

                <thead>
                    <tr class="bg-gray-50">

                        <th class="px-4 py-3 text-left font-semibold text-gray-700">
                            Total
                        </th>

                        <th class="px-4 py-3 text-center font-semibold text-gray-700">
                            Adults
                        </th>

                        <th class="px-3 py-3 text-center font-semibold text-gray-700">
                            Child 5-10
                        </th>

                        <th class="px-3 py-3 text-center font-semibold text-gray-700">
                            Child 2-5
                        </th>

                        <th class="px-3 py-3 text-center font-semibold text-gray-700">
                            Infants
                        </th>

                    </tr>
                </thead>

                <tbody>
                    <tr class="border-t border-gray-200">

                        <td
                            id="totalPassengers"
                            class="px-4 py-4 font-bold text-gray-800 text-left"
                        >
                            0
                        </td>

                        <td
                            id="totalAdults"
                            class="px-4 py-4 text-center font-bold text-blue-600"
                        >
                            0
                        </td>

                        <td
                            id="totalChildren510"
                            class="px-4 py-4 text-center font-bold text-amber-600"
                        >
                            0
                        </td>

                        <td
                            id="totalChildren25"
                            class="px-4 py-4 text-center font-bold text-orange-600"
                        >
                            0
                        </td>

                        <td
                            id="totalInfants"
                            class="px-4 py-4 text-center font-bold text-purple-600"
                        >
                            0
                        </td>

                    </tr>
                </tbody>

            </table>

        </div>

    </div>
</div>


        {{-- =========================
            TRANSPORTATION
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">
                    Transportation
                </h2>
            </div>

            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Transportation Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Transportation Type
                            <span class="text-red-500">*</span>
                        </label>

                        <select
    name="transportation_type"
    id="transportation_type"
    required
    class="w-full rounded-lg border border-gray-300 px-4 py-3"
>
    <option value="">Select Type</option>

    @foreach($transportationOptions->pluck('type')->unique() as $type)
        <option value="{{ $type }}" {{ old('transportation_type', 'Bus') === $type ? 'selected' : '' }}>
            {{ $type }}
        </option>
    @endforeach
</select>
                    </div>


                    {{-- Transportation Sector --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Transportation Sector
                            <span class="text-red-500">*</span>
                        </label>

                        <select
    name="transportation_sector"
    id="transportation_sector"
    required
    class="w-full rounded-lg border border-gray-300 px-4 py-3"
>
    <option value="">Select Sector</option>

    @foreach($transportationOptions->pluck('sector')->unique() as $sector)
        <option value="{{ $sector }}" {{ old('transportation_sector', 'Jeddah - Makkah - Medina - Makkah - Jeddah') === $sector ? 'selected' : '' }}>
            {{ $sector }}
        </option>
    @endforeach
</select>
                    </div>


                    {{-- Vehicle Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Vehicle Type
                            <span class="text-red-500">*</span>
                        </label>

                       <select
    name="vehicle_type"
    id="vehicle_type"
    required
    class="w-full rounded-lg border border-gray-300 px-4 py-3"
>
    <option value="">Select Vehicle Type</option>

    @foreach($transportationOptions->pluck('vehicle_type')->unique() as $vehicleType)
        <option value="{{ $vehicleType }}" {{ old('vehicle_type', 'Sharing') === $vehicleType ? 'selected' : '' }}>
            {{ $vehicleType }}
        </option>
    @endforeach
</select>
                    </div>


                    {{-- Number of Persons --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Number of Persons
                            <span class="text-red-500">*</span>
                        </label>

                      <input
    type="number"
    min="0"
    name="transport_persons"
    id="transport_persons"
    value="0"
    readonly
    class="w-full rounded-lg border border-gray-300 px-4 py-3
           bg-gray-50 text-gray-700 cursor-not-allowed"
>
<p class="mt-1 text-xs text-gray-500">
    Automatically calculated from total passengers.
</p>
                    </div>

                </div>
            </div>
        </div>


        {{-- =========================
            ARRIVAL TO KSA
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Arrival To KSA
                    </h2>

                    <button
                        type="button"
                        class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs font-bold"
                        title="Arrival flight details"
                    >
                        ?
                    </button>
                </div>
            </div>

            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Flight No <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="arrival_flight_no"
                            id="arrival_flight_no"
                            placeholder="Enter flight number"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Flight PNR <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="arrival_flight_pnr"
                            id="arrival_flight_pnr"
                            placeholder="Enter PNR"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Time of Departure To KSA
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="arrival_departure_time"
                            id="arrival_departure_time"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Time of Arrival To KSA
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="arrival_arrival_time"
                            id="arrival_arrival_time"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Departure From <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="arrival_departure_from"
                            id="arrival_departure_from"
                            placeholder="e.g. Islamabad"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Arrival To <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="arrival_to"
                            id="arrival_to"
                            placeholder="e.g. Jeddah"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                </div>

                {{-- PDF --}}
                <!-- <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Arrival Flight PDF
                    </label>

                    <input
                        type="file"
                        name="arrival_pdf"
                        accept=".pdf"
                        class="block w-full text-sm text-gray-600
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:bg-gray-100 file:text-gray-700
                        hover:file:bg-gray-200"
                    >
                </div> -->

            </div>
        </div>


        {{-- =========================
            DEPARTURE FROM KSA
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Departure From KSA
                    </h2>

                    <button
                        type="button"
                        class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs font-bold"
                    >
                        ?
                    </button>
                </div>
            </div>

            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Flight No <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="departure_flight_no"
                            id="departure_flight_no"
                            placeholder="Enter flight number"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Flight PNR <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="departure_flight_pnr"
                            id="departure_flight_pnr"
                            placeholder="Enter PNR"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Time of Departure From KSA
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="departure_departure_time"
                            id="departure_departure_time"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Time of Arrival
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="departure_arrival_time"
                            id="departure_arrival_time"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Departure From <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="departure_from"
                            id="departure_from"
                            placeholder="e.g. Jeddah"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Arrival To <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="departure_to"
                            id="departure_to"
                            placeholder="e.g. Islamabad"
                            readonly
                            class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        >
                    </div>

                </div>

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Departure Flight PDF
                    </label>

                    <input
                        type="file"
                        name="departure_pdf"
                        accept=".pdf"
                        class="block w-full text-sm text-gray-600
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:bg-gray-100 file:text-gray-700
                        hover:file:bg-gray-200"
                    >
                </div>

            </div>
        </div>


        {{-- =========================
            HOTELS
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Hotel Accommodation
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Add Makkah / Madinah hotel accommodation
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="addHotelRow()"
                        class="inline-flex items-center justify-center gap-2
                        px-4 py-2.5 rounded-lg bg-blue-600 text-white
                        hover:bg-blue-700 text-sm font-medium"
                    >
                        + Add Hotel
                    </button>

                </div>

            </div>

            <div class="p-6">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1100px] text-sm">

                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-3 py-3 text-left">City / Hotel</th>
                                <th class="px-3 py-3 text-left">Check In</th>
                                <th class="px-3 py-3 text-left">Check Out</th>
                                <th class="px-3 py-3 text-left">Nights</th>
                                <th class="px-3 py-3 text-left">Type</th>
                                <th class="px-3 py-3 text-left">Pax</th>
                                <th class="px-3 py-3 text-left">Action</th>
                            </tr>
                        </thead>

                        <tbody id="hotelRows">

                            <tr class="border-b hotel-row">

                                <td class="px-2 py-3">
                                    <select
                                        name="hotels[0][hotel]"
                                        data-hotel-select
                                        class="w-full min-w-[220px] rounded-lg border border-gray-300 px-3 py-2"
                                    >
                                        <option value="">Select Hotel</option>
                                        @foreach($hotels as $hotel)
                                            <option
                                                value="{{ $hotel->hotel_name }}"
                                                data-city="{{ $hotel->city }}"
                                                data-availability="{{ $hotel->inventories->map(fn ($inventory) => [$inventory->inventory_date->format('Y-m-d'), ($inventory->inventory_date_to ?? $inventory->inventory_date)->format('Y-m-d')])->toJson() }}"
                                            >
                                                {{ $hotel->city ? $hotel->city . ' - ' : '' }}{{ $hotel->hotel_name }}{{ $hotel->category ? ' | ' . $hotel->category : '' }}{{ $hotel->distance_from_haram ? ' | ' . (float) $hotel->distance_from_haram . ' KM' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-2 py-3">
                                    <input
                                        type="date"
                                        name="hotels[0][check_in]"
                                        readonly
                                        class="rounded-lg border border-gray-300 px-3 py-2 bg-gray-50 text-gray-700"
                                    >
                                </td>

                                <td class="px-2 py-3">
                                    <input
                                        type="date"
                                        name="hotels[0][check_out]"
                                        readonly
                                        class="rounded-lg border border-gray-300 px-3 py-2 bg-gray-50 text-gray-700"
                                    >
                                </td>

                                <td class="px-2 py-3">
                                    <input
                                        type="number"
                                        name="hotels[0][nights]"
                                        min="1"
                                        value="1"
                                        class="w-20 rounded-lg border border-gray-300 px-3 py-2"
                                    >
                                </td>

                                <td class="px-2 py-3">
                                    <select
                                        name="hotels[0][type]"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    >
                                        <option value="">Select Room Type</option>
                                        @foreach($roomTypes as $roomType)
                                            <option value="{{ $roomType->name }}" {{ old('hotels.0.type', 'Sharing') === $roomType->name ? 'selected' : '' }}>{{ $roomType->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-2 py-3">
                                    <input
                                        type="number"
                                        name="hotels[0][pax]"
                                        min="1"
                                        value="1"
                                        class="w-20 rounded-lg border border-gray-300 px-3 py-2"
                                    >
                                </td>

                                <td class="px-2 py-3">
                                    <button
                                        type="button"
                                        onclick="removeHotelRow(this)"
                                        class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
                                    >
                                        Remove
                                    </button>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>


        {{-- =========================
            REMARKS
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">
                    Remarks
                </h2>
            </div>

            <div class="p-6">

                <textarea
                    name="remarks"
                    rows="5"
                    placeholder="Enter voucher remarks..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 resize-y focus:border-blue-500 focus:ring-blue-500"
                ></textarea>

                <p class="text-xs text-gray-500 mt-2">
                    Agent can add any additional instructions or information here.
                </p>

            </div>
        </div>


        {{-- =========================
            ACTION BUTTONS
        ========================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">

                <button
                    type="button"
                    class="px-6 py-3 rounded-lg border border-gray-300
                    text-gray-700 font-medium hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg bg-blue-600
                    text-white font-medium hover:bg-blue-700"
                >
                    Save Voucher
                </button>

                <button
                    type="button"
                    class="px-6 py-3 rounded-lg bg-gray-800
                    text-white font-medium hover:bg-gray-900"
                >
                    Save & Print
                </button>

            </div>

        </div>

    </form>

</div>


<style>
    @media (max-width: 639px) {
        #existingCustomersBody .customer-row {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 0.15rem 0.65rem;
            margin-bottom: 0.5rem;
            padding: 0.65rem;
            border: 1px solid rgb(243 244 246);
            border-radius: 0.75rem;
        }

        #existingCustomersBody .customer-row:last-child {
            margin-bottom: 0;
        }

        #existingCustomersBody .customer-row > td {
            display: block;
            padding: 0.15rem 0.25rem;
            text-align: left;
        }

        #existingCustomersBody .customer-row > td:nth-child(1) {
            grid-column: 1;
            grid-row: 1 / span 3;
        }

        #existingCustomersBody .customer-row > td:nth-child(2) {
            grid-column: 2;
            grid-row: 1;
        }

        #existingCustomersBody .customer-row > td:nth-child(3) {
            grid-column: 2;
            grid-row: 2;
            font-size: 0.75rem;
        }

        #existingCustomersBody .customer-row > td:nth-child(4) {
            grid-column: 2;
            grid-row: 3;
            font-size: 0.75rem;
            color: rgb(107 114 128);
        }

        #existingCustomersBody .customer-row > td:nth-child(5) {
            grid-column: 3;
            grid-row: 2;
            font-size: 0.75rem;
        }

        #existingCustomersBody .customer-row > td:nth-child(6) {
            grid-column: 3;
            grid-row: 1;
            white-space: nowrap;
        }
    }
</style>

{{-- =========================
    JAVASCRIPT
========================== --}}
<script>

    function populateFlightFields() {
        const option = document.getElementById('ticket_id').selectedOptions[0];

        // Arrival fields
        const arrivalFlightNo = document.getElementById('arrival_flight_no');
        const arrivalFlightPnr = document.getElementById('arrival_flight_pnr');
        const arrivalDepTime = document.getElementById('arrival_departure_time');
        const arrivalArrTime = document.getElementById('arrival_arrival_time');
        const arrivalFrom = document.getElementById('arrival_departure_from');
        const arrivalTo = document.getElementById('arrival_to');

        if (arrivalFlightNo) arrivalFlightNo.value = option?.dataset.arrivalFlightNo ?? '';
        if (arrivalFlightPnr) arrivalFlightPnr.value = option?.dataset.arrivalPnr ?? '';
        if (arrivalDepTime) arrivalDepTime.value = option?.dataset.arrivalDepartureTime ?? '';
        if (arrivalArrTime) arrivalArrTime.value = option?.dataset.arrivalArrivalTime ?? '';
        if (arrivalFrom) arrivalFrom.value = option?.dataset.arrivalFrom ?? '';
        if (arrivalTo) arrivalTo.value = option?.dataset.arrivalTo ?? '';

        // Departure fields
        const depFlightNo = document.getElementById('departure_flight_no');
        const depFlightPnr = document.getElementById('departure_flight_pnr');
        const depDepTime = document.getElementById('departure_departure_time');
        const depArrTime = document.getElementById('departure_arrival_time');
        const depFrom = document.getElementById('departure_from');
        const depTo = document.getElementById('departure_to');

        if (depFlightNo) depFlightNo.value = option?.dataset.departureFlightNo ?? '';
        if (depFlightPnr) depFlightPnr.value = option?.dataset.departurePnr ?? '';
        if (depDepTime) depDepTime.value = option?.dataset.departureDepartureTime ?? '';
        if (depArrTime) depArrTime.value = option?.dataset.departureArrivalTime ?? '';
        if (depFrom) depFrom.value = option?.dataset.departureFrom ?? '';
        if (depTo) depTo.value = option?.dataset.departureTo ?? '';

        refreshAllHotelRows();
    }

    document.getElementById('ticket_id').addEventListener('change', populateFlightFields);

    // -----------------------------------------
    // Passenger Total
    // -----------------------------------------

    // =====================================================
    // CUSTOMER ARRAY
    // =====================================================

    let customers = [];
let selectedExistingCustomers = [];

function toggleExistingCustomers() {
    const panel = document.getElementById('existingCustomersPanel');
    const icon = document.getElementById('existingCustomersIcon');
    const button = panel.previousElementSibling;
    const isCollapsed = panel.classList.toggle('hidden');

    icon.classList.toggle('rotate-180', !isCollapsed);
    button.setAttribute('aria-expanded', String(!isCollapsed));
}

function handleCustomerSelection(checkbox) {

    const customer = {
        id: checkbox.value,
        name: checkbox.dataset.name,
        passport: checkbox.dataset.passport,
        dob: checkbox.dataset.dob,
        age: checkbox.dataset.age,
        type: checkbox.dataset.type
    };

    if (checkbox.checked) {

        const exists = selectedExistingCustomers.some(
            item => item.id === customer.id
        );

        if (!exists) {
            selectedExistingCustomers.push(customer);
        }

    } else {

        selectedExistingCustomers =
            selectedExistingCustomers.filter(
                item => item.id !== customer.id
            );
    }

    updateSelectedCustomerUI();
    updateCustomerMasterCheckbox();
}

function selectAllCustomers() {
    document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
        if (checkbox.closest('.customer-row').style.display !== 'none') {
            checkbox.checked = true;
            handleCustomerSelection(checkbox);
        }
    });
    updateCustomerMasterCheckbox();
}

function toggleAllCustomers(masterCheckbox) {
    document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
        if (checkbox.closest('.customer-row').style.display !== 'none') {
            checkbox.checked = masterCheckbox.checked;
            handleCustomerSelection(checkbox);
        }
    });
}

function clearAllCustomers() {

    document
        .querySelectorAll('.customer-checkbox')
        .forEach(checkbox => {
            checkbox.checked = false;
        });

    document.getElementById('selectAllCustomers').checked = false;

    selectedExistingCustomers = [];

    updateSelectedCustomerUI();
}

function addSelectedExistingCustomers() {

    if (selectedExistingCustomers.length === 0) {

        alert('Please select at least one customer.');

        return;
    }

    selectedExistingCustomers.forEach(customer => {

        const alreadyExists = customers.some(
            item => item.passport === customer.passport
        );

        if (!alreadyExists) {

            customers.push({
                id: 'existing_' + customer.id,
                existingId: customer.id,
                name: customer.name,
                passport: customer.passport,
                dob: customer.dob,
                age: Number(customer.age),
                type: customer.type,
                source: 'existing'
            });
        }
    });

    renderCustomers();
}

function updateSelectedCustomerUI() {
    const section = document.getElementById('selectedCustomersSection');
    const list = document.getElementById('selectedCustomersList');
    const count = document.getElementById('selectedCustomerCount');
    const badge = document.getElementById('selectedCustomersBadge');

    count.textContent = selectedExistingCustomers.length;
    badge.textContent = customers.length + ' Selected';
    list.innerHTML = '';

    if (customers.length === 0) {
        section.classList.add('hidden');
        return;
    }

    section.classList.remove('hidden');

    customers.forEach(customer => {
        const item = document.createElement('div');
        item.className = 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-lg bg-white border border-green-100 px-4 py-3';
        item.innerHTML = `
            <div>
                <div class="font-semibold text-gray-800">${escapeHtml(customer.name)}</div>
               <div class="text-xs text-gray-500 mt-1">
    Passport: ${escapeHtml(customer.passport)}
    &nbsp; • &nbsp;
    DOB: ${escapeHtml(customer.dob)}
    &nbsp; • &nbsp;
    Age: ${escapeHtml(String(customer.age))} Years
</div>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    ${escapeHtml(customer.type)}
                </span>
                <button type="button" data-customer-id="${escapeHtml(String(customer.id))}"
                        class="remove-customer inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                        title="Remove Customer">✕</button>
            </div>
        `;
        list.appendChild(item);
    });

    list.querySelectorAll('.remove-customer').forEach(button => {
        button.addEventListener('click', () => removeCustomer(button.dataset.customerId));
    });
}

function updateCustomerMasterCheckbox() {
    const master = document.getElementById('selectAllCustomers');
    const visible = Array.from(document.querySelectorAll('.customer-checkbox'))
        .filter(checkbox => checkbox.closest('.customer-row').style.display !== 'none');
    master.checked = visible.length > 0 && visible.every(checkbox => checkbox.checked);
    master.indeterminate = visible.some(checkbox => checkbox.checked) && !master.checked;
}

function filterCustomers() {

    const searchValue =
        document
            .getElementById('customerSearch')
            .value
            .toLowerCase()
            .trim();

    const rows =
        document.querySelectorAll('.customer-row');

    rows.forEach(row => {

        const searchableText = row.dataset.search.toLowerCase();

        row.style.display =
            searchableText.includes(searchValue)
                ? ''
                : 'none';
    });

    updateCustomerMasterCheckbox();
}

    // =====================================================
    // OPEN CUSTOMER FORM
    // =====================================================

    function openCustomerForm() {

        document.getElementById('customerForm').classList.remove('hidden');

        document.getElementById('customer_name').focus();
    }


    // =====================================================
    // CLOSE CUSTOMER FORM
    // =====================================================

    function closeCustomerForm() {

        document.getElementById('customerForm').classList.add('hidden');

        resetCustomerForm();
    }


    // =====================================================
    // RESET FORM
    // =====================================================

    function resetCustomerForm() {

        document.getElementById('customer_name').value = '';
        document.getElementById('customer_passport').value = '';
        document.getElementById('customer_dob').value = '';

        document.getElementById('customerAge').textContent = '-';
        document.getElementById('customerType').textContent = '-';
    }


    // =====================================================
    // CALCULATE AGE
    // =====================================================

    function calculateAge(dob) {

        const birthDate = new Date(dob);

        const today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();

        const monthDifference =
            today.getMonth() - birthDate.getMonth();

        if (
            monthDifference < 0 ||
            (
                monthDifference === 0 &&
                today.getDate() < birthDate.getDate()
            )
        ) {
            age--;
        }

        return age;
    }


    // =====================================================
    // DETERMINE PASSENGER TYPE
    // =====================================================

    function determinePassengerType(age) {

        if (age >= 10) {
            return 'Adult';
        }

        if (age >= 5) {
            return 'Child (5-10)';
        }

        if (age >= 2) {
            return 'Child (2-5)';
        }

        if (age >= 0) {
            return 'Infant (0-2)';
        }

        return null;
    }


    // =====================================================
    // DOB CHANGE
    // =====================================================

    function calculateCustomerAge() {

        const dob =
            document.getElementById('customer_dob').value;

        if (!dob) {

            document.getElementById('customerAge').textContent = '-';
            document.getElementById('customerType').textContent = '-';

            return;
        }

        const age = calculateAge(dob);

        const type = determinePassengerType(age);

        document.getElementById('customerAge').textContent =
            age + ' Years';

        document.getElementById('customerType').textContent =
            type ?? '-';
    }


    // =====================================================
    // ADD CUSTOMER
    // =====================================================

   async function addCustomer() {

    const name =
        document.getElementById('customer_name').value.trim();

    const passport =
        document.getElementById('customer_passport').value.trim();

    const dob =
        document.getElementById('customer_dob').value;

    if (!name) {
        alert('Please enter customer name.');
        return;
    }

    if (!passport) {
        alert('Please enter passport number.');
        return;
    }

    if (!dob) {
        alert('Please select date of birth.');
        return;
    }

    const age = calculateAge(dob);
    const type = determinePassengerType(age);

    if (!type) {
        alert('Invalid date of birth.');
        return;
    }

    // Prevent duplicate in the current voucher UI
    if (
        customers.some(
            item =>
                item.passport.toLowerCase() === passport.toLowerCase()
        )
    ) {
        alert('A customer with this passport is already selected.');
        return;
    }

    try {

        const response = await fetch(
            "{{ route('travel-agents.vouchers.customers.store') }}",
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    name: name,
                    passport_no: passport,
                    date_of_birth: dob
                })
            }
        );

        const data = await response.json();

        if (!response.ok) {
            alert(data.message || 'Unable to save customer.');
            return;
        }

        if (!data.success) {
            alert(data.message || 'Unable to save customer.');
            return;
        }

        // Add database-saved customer to current voucher UI
        const customer = {
            id: 'new_' + data.customer.id,
            database_id: data.customer.id,
            name: data.customer.name,
            passport: data.customer.passport_no,
            dob: data.customer.date_of_birth,
            age: age,
            type: type,
            source: 'new'
        };

        customers.push(customer);

        renderCustomers();
        updatePassengerTotals();

        closeCustomerForm();

        alert('Customer added successfully.');

    } catch (error) {

        console.error('Customer save error:', error);

        alert('Something went wrong while saving the customer.');
    }
}

    // =====================================================
    // RENDER CUSTOMERS
    // =====================================================

    function renderCustomers() {
        updateSelectedCustomerUI();
        updatePassengerTotals();
    }


    // =====================================================
    // REMOVE CUSTOMER
    // =====================================================

    function removeCustomer(id) {
        const customer = customers.find(item => String(item.id) === String(id));
        customers = customers.filter(item => String(item.id) !== String(id));

        if (customer && customer.source === 'existing') {
            const checkbox = document.querySelector(`.customer-checkbox[value="${CSS.escape(String(customer.existingId))}"]`);
            if (checkbox) checkbox.checked = false;
            selectedExistingCustomers = selectedExistingCustomers.filter(item => String(item.id) !== String(customer.existingId));
        }

        renderCustomers();
        updateCustomerMasterCheckbox();
    }


    // =====================================================
    // PASSENGER TOTALS
    // =====================================================

    function updatePassengerTotals() {

        let adults = 0;

        let children510 = 0;

        let children25 = 0;

        let infants = 0;


        customers.forEach(customer => {

            if (customer.type === 'Adult') {

                adults++;

            } else if (customer.type === 'Child (5-10)') {

                children510++;

            } else if (customer.type === 'Child (2-5)') {

                children25++;

            } else if (customer.type === 'Infant (0-2)') {

                infants++;

            }

        });


        const total =
            adults +
            children510 +
            children25 +
            infants;
        const transportPersons =
            document.getElementById('transport_persons');

        if (transportPersons) {
            transportPersons.value = total;
        }

        // Automatically set hotel pax to total number of passengers
        document.querySelectorAll('input[name^="hotels"][name$="[pax]"]').forEach(input => {
            input.value = total;
        });

        document.getElementById('totalPassengers').textContent =
            total;

        document.getElementById('totalAdults').textContent =
            adults;

        document.getElementById('totalChildren510').textContent =
            children510;

        document.getElementById('totalChildren25').textContent =
            children25;

        document.getElementById('totalInfants').textContent =
            infants;
    }


    // =====================================================
    // BADGE COLOR
    // =====================================================

    function getPassengerBadgeClass(type) {

        if (type === 'Adult') {

            return 'bg-blue-50 text-blue-700';
        }

        if (type === 'Child (5-10)') {

            return 'bg-amber-50 text-amber-700';
        }

        if (type === 'Child (2-5)') {

            return 'bg-orange-50 text-orange-700';
        }

        if (type === 'Infant (0-2)') {

            return 'bg-purple-50 text-purple-700';
        }

        return 'bg-gray-50 text-gray-700';
    }


    // =====================================================
    // HTML ESCAPE
    // =====================================================

    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value;

        return div.innerHTML;
    }


    document.querySelectorAll(
        '[name="adults"], [name="children_5_10"], [name="children_2_5"], [name="infants"]'
    ).forEach(input => {

        input.addEventListener('input', updatePassengerTotal);

    });


    // -----------------------------------------
    // Hotel Accommodation Logic
    // -----------------------------------------
    let hotelIndex = 1;

    function normalizeCity(city) {
        if (!city) return '';
        const c = city.toString().trim().toLowerCase();
        if (c.includes('makk') || c.includes('mecc')) return 'Makkah';
        if (c.includes('madin') || c.includes('medin')) return 'Madinah';
        return city.toString().trim();
    }

    function getOppositeCity(city) {
        const norm = normalizeCity(city);
        if (norm === 'Makkah') return 'Madinah';
        if (norm === 'Madinah') return 'Makkah';
        return null;
    }

    function addDays(dateString, days) {
        if (!dateString) return '';
        const parts = dateString.split('-');
        if (parts.length !== 3) return '';
        const date = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
        date.setDate(date.getDate() + parseInt(days, 10));
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function refreshAllHotelRows() {
        const rows = Array.from(document.querySelectorAll('.hotel-row'));
        if (rows.length === 0) return;

        const arrivalTime = document.getElementById('arrival_arrival_time')?.value || document.getElementById('arrival_departure_time')?.value || '';
        let arrivalDate = arrivalTime ? arrivalTime.slice(0, 10) : '';
        if (!arrivalDate) {
            const now = new Date();
            arrivalDate = now.toISOString().slice(0, 10);
        }

        const departureTime = document.getElementById('departure_departure_time')?.value || document.getElementById('departure_arrival_time')?.value || '';
        const departureLimit = departureTime ? departureTime.slice(0, 10) : '';

        let currentCheckIn = arrivalDate;
        let prevCity = null;

        rows.forEach((row, index) => {
            const hotelSelect = row.querySelector('[data-hotel-select]');
            const checkInInput = row.querySelector('input[name*="[check_in]"]');
            const checkOutInput = row.querySelector('input[name*="[check_out]"]');
            const nightsInput = row.querySelector('input[name*="[nights]"]');

            // 1. Set Check-In Date for this row
            if (checkInInput) {
                checkInInput.value = currentCheckIn;
            }

            // 2. Calculate remaining available nights up to departure date
            let maxNights = 365;
            if (departureLimit && currentCheckIn) {
                const partsIn = currentCheckIn.split('-').map(Number);
                const partsLimit = departureLimit.split('-').map(Number);
                const dIn = new Date(partsIn[0], partsIn[1] - 1, partsIn[2]);
                const dLimit = new Date(partsLimit[0], partsLimit[1] - 1, partsLimit[2]);
                const diffDays = Math.round((dLimit - dIn) / (1000 * 60 * 60 * 24));
                maxNights = Math.max(1, diffDays);
            }

            if (nightsInput) {
                nightsInput.min = '1';
                if (departureLimit) {
                    nightsInput.max = String(maxNights);
                } else {
                    nightsInput.removeAttribute('max');
                }
            }

            // 3. Set Nights and Calculate Check-Out Date (Check-in + Nights)
            let nightsVal = parseInt(nightsInput?.value, 10);
            if (isNaN(nightsVal) || nightsVal < 1) {
                nightsVal = 1;
            }
            if (departureLimit && nightsVal > maxNights) {
                nightsVal = maxNights;
            }
            if (nightsInput && parseInt(nightsInput.value, 10) !== nightsVal) {
                nightsInput.value = nightsVal;
            }

            const calculatedCheckOut = addDays(currentCheckIn, nightsVal);
            if (checkOutInput) {
                checkOutInput.value = calculatedCheckOut;
            }

            // 4. Determine required City for this row (alternating Makkah <-> Madinah)
            let targetCity = null;
            if (index === 0) {
                targetCity = null; // First hotel can be Makkah or Madinah
            } else if (prevCity) {
                targetCity = getOppositeCity(prevCity);
            }

            // 5. Filter hotel options in this row's dropdown
            if (hotelSelect) {
                const currentSelectedValue = hotelSelect.value;

                Array.from(hotelSelect.options).forEach(option => {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }

                    const optionCity = normalizeCity(option.dataset.city);
                    const isCityAllowed = !targetCity || (optionCity === targetCity);
                    option.hidden = !isCityAllowed;
                });

                if (currentSelectedValue && hotelSelect.selectedOptions[0]?.hidden) {
                    hotelSelect.value = '';
                }

                if (hotelSelect.value) {
                    const selectedOpt = hotelSelect.selectedOptions[0];
                    prevCity = normalizeCity(selectedOpt?.dataset?.city) || targetCity;
                } else if (targetCity) {
                    prevCity = targetCity;
                }
            }

            // 6. Next row's check-in starts at THIS row's check-out date!
            currentCheckIn = calculatedCheckOut;
        });
    }

    function bindHotelRow(row) {
        const hotelSelect = row.querySelector('[data-hotel-select]');
        const nightsInput = row.querySelector('input[name*="[nights]"]');

        if (hotelSelect) {
            hotelSelect.addEventListener('change', () => {
                refreshAllHotelRows();
            });
        }

        if (nightsInput) {
            nightsInput.addEventListener('input', () => {
                refreshAllHotelRows();
            });
            nightsInput.addEventListener('change', () => {
                refreshAllHotelRows();
            });
        }
    }

    document.querySelectorAll('.hotel-row').forEach(bindHotelRow);

    function addHotelRow() {
        const rows = document.querySelectorAll('.hotel-row');
        if (rows.length > 0) {
            const lastRow = rows[rows.length - 1];
            const lastHotelSelect = lastRow.querySelector('[data-hotel-select]');
            if (!lastHotelSelect || !lastHotelSelect.value) {
                alert('Please select a hotel for the current row before adding the next hotel.');
                if (lastHotelSelect) {
                    lastHotelSelect.focus();
                }
                return;
            }

            const lastCheckOut = lastRow.querySelector('input[name*="[check_out]"]')?.value;
            const departureTime = document.getElementById('departure_departure_time')?.value || document.getElementById('departure_arrival_time')?.value || '';
            const departureLimit = departureTime ? departureTime.slice(0, 10) : '';

            if (departureLimit && lastCheckOut && lastCheckOut >= departureLimit) {
                alert('All available nights up to the departure flight date (' + departureLimit + ') are already covered by selected hotels.');
                return;
            }
        }

        const tbody = document.getElementById('hotelRows');
        const row = document.createElement('tr');
        row.className = 'border-b hotel-row';

        row.innerHTML = `
            <td class="px-2 py-3">
                <select
                    name="hotels[${hotelIndex}][hotel]"
                    data-hotel-select
                    class="w-full min-w-[220px] rounded-lg border border-gray-300 px-3 py-2"
                >
                    <option value="">Select Hotel</option>
                    @foreach($hotels as $hotel)
                        <option
                            value="{{ $hotel->hotel_name }}"
                            data-city="{{ $hotel->city }}"
                            data-availability="{{ $hotel->inventories->map(fn ($inventory) => [$inventory->inventory_date->format('Y-m-d'), ($inventory->inventory_date_to ?? $inventory->inventory_date)->format('Y-m-d')])->toJson() }}"
                        >
                            {{ $hotel->city ? $hotel->city . ' - ' : '' }}{{ $hotel->hotel_name }}{{ $hotel->category ? ' | ' . $hotel->category : '' }}{{ $hotel->distance_from_haram ? ' | ' . (float) $hotel->distance_from_haram . ' KM' : '' }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td class="px-2 py-3">
                <input
                    type="date"
                    name="hotels[${hotelIndex}][check_in]"
                    readonly
                    class="rounded-lg border border-gray-300 px-3 py-2 bg-gray-50 text-gray-700"
                >
            </td>

            <td class="px-2 py-3">
                <input
                    type="date"
                    name="hotels[${hotelIndex}][check_out]"
                    readonly
                    class="rounded-lg border border-gray-300 px-3 py-2 bg-gray-50 text-gray-700"
                >
            </td>

            <td class="px-2 py-3">
                <input
                    type="number"
                    name="hotels[${hotelIndex}][nights]"
                    min="1"
                    value="1"
                    class="w-20 rounded-lg border border-gray-300 px-3 py-2"
                >
            </td>

            <td class="px-2 py-3">
                <select
                    name="hotels[${hotelIndex}][type]"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
                    <option value="">Select Room Type</option>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->name }}" {{ $roomType->name === 'Sharing' ? 'selected' : '' }}>{{ $roomType->name }}</option>
                    @endforeach
                </select>
            </td>

            <td class="px-2 py-3">
                <input
                    type="number"
                    name="hotels[${hotelIndex}][pax]"
                    min="1"
                    value="${document.getElementById('transport_persons')?.value || 1}"
                    class="w-20 rounded-lg border border-gray-300 px-3 py-2"
                >
            </td>

            <td class="px-2 py-3">
                <button
                    type="button"
                    onclick="removeHotelRow(this)"
                    class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
                >
                    Remove
                </button>
            </td>
        `;

        tbody.appendChild(row);
        bindHotelRow(row);

        hotelIndex++;
        refreshAllHotelRows();
    }

    function removeHotelRow(button) {
        const rows = document.querySelectorAll('.hotel-row');
        if (rows.length <= 1) {
            return;
        }

        button.closest('tr').remove();
        refreshAllHotelRows();
    }

    // Run initial refresh on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof updatePassengerTotals === 'function') {
            updatePassengerTotals();
        }
        refreshAllHotelRows();
    });

</script>

@endsection