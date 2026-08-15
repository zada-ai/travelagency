@extends('admin.layouts.app')

@section('title', 'Booking Details')
@section('page-heading', 'Booking Details')
@section('page-description', 'Review the full reservation details and manage booking status.')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900 shadow-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- =========================================================
        BOOKING HEADER + DETAILS
    ========================================================== --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">

            <div>
                <h2 class="text-2xl font-semibold text-slate-900">
                    {{ $booking->reference_number }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Booked on {{ $booking->created_at->format('d M Y, h:i A') }}
                </p>
            </div>


            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-3">

                {{-- STATUS --}}
                <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold
                    {{ $booking->status === 'Cancelled'
                        ? 'bg-rose-100 text-rose-700'
                        : ($booking->status === 'Reserved'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-amber-100 text-amber-700') }}">

                    {{ $booking->status }}

                </span>


                {{-- RESERVE --}}
                @if($booking->status === 'Pending')

                    <form
                        action="{{ route('admin.bookings.reserve', $booking) }}"
                        method="POST"
                        onsubmit="return confirm('Mark this booking as Reserved?');"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Reserve
                        </button>

                    </form>

                @endif


                {{-- CANCEL --}}
                @if($booking->status !== 'Cancelled')

                    <form
                        action="{{ route('admin.bookings.cancel', $booking) }}"
                        method="POST"
                        onsubmit="return confirm('Cancel this booking?');"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-600"
                        >
                            Cancel Booking
                        </button>

                    </form>

                @endif


                {{-- =====================================================
                    GENERATE HOTEL VOUCHER
                    Only available after booking is reserved.
                ====================================================== --}}
                @if($booking->status === 'Reserved')

                    <button
                        type="button"
                        onclick="openVoucherStep1()"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Generate Hotel Voucher
                    </button>

                @endif

            </div>

        </div>


        {{-- =========================================================
            GUEST / HOTEL / DATES
        ========================================================== --}}
        <div class="mt-8 grid gap-6 xl:grid-cols-3">

            {{-- GUEST --}}
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">

                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Guest
                </h3>

                <p class="mt-4 text-sm text-slate-700">
                    {{ $booking->contact_name }}
                </p>

                <p class="text-sm text-slate-500">
                    {{ $booking->contact_email }}
                </p>

                <p class="text-sm text-slate-500">
                    {{ $booking->contact_phone }}
                </p>

            </div>


            {{-- STAY --}}
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">

                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Stay
                </h3>

                <p class="mt-4 text-sm text-slate-700">
                    {{ $booking->hotel->hotel_name ?? '-' }}
                </p>

                <p class="text-sm text-slate-500">
                    Room: {{ $booking->roomType->room_name ?? '-' }}
                </p>

                <p class="text-sm text-slate-500">
                    Meal plan: {{ $booking->mealPlan?->meal_name ?? 'None' }}
                </p>

            </div>


            {{-- DATES --}}
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">

                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Dates & Guests
                </h3>

                <p class="mt-4 text-sm text-slate-700">
                    Check-in: {{ $booking->check_in->format('d M Y') }}
                </p>

                <p class="text-sm text-slate-500">
                    Check-out: {{ $booking->check_out->format('d M Y') }}
                </p>

                <p class="text-sm text-slate-500">
                    Adults: {{ $booking->adults }}
                </p>

                <p class="text-sm text-slate-500">
                    Children: {{ $booking->children }}
                </p>

                <p class="text-sm text-slate-500">
                    Infants: {{ $booking->infants }}
                </p>

            </div>

        </div>


        {{-- =========================================================
            BOOKING SUMMARY
        ========================================================== --}}
        <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-semibold text-slate-900">
                Booking Summary
            </h3>

          <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

    {{-- ROOM --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Room Price</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            SAR {{ number_format($booking->room_price, 2) }}
        </p>
    </div>


    {{-- MEAL --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Meal Price</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            SAR {{ number_format($booking->meal_price, 2) }}
        </p>

        <p class="mt-1 text-xs text-slate-400">
            {{ $booking->mealPlan?->meal_name ?? 'Meal plan not selected' }}
        </p>
    </div>


    {{-- TRANSPORT --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Transport</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            {{ $booking->include_transport ? 'Included' : 'Not Included' }}
        </p>

        <p class="mt-1 text-sm text-slate-500">
            SAR {{ number_format($booking->transport_price ?? 0, 2) }}
        </p>
    </div>


    {{-- VISA --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Visa</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            {{ $booking->include_visa ? 'Included' : 'Not Included' }}
        </p>

        <p class="mt-1 text-sm text-slate-500">
            SAR {{ number_format($booking->visa_price ?? 0, 2) }}
        </p>
    </div>


    {{-- TAX --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Taxes</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            SAR {{ number_format($booking->taxes, 2) }}
        </p>
    </div>


    {{-- DISCOUNT --}}
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
        <p class="text-sm text-slate-500">Discount</p>

        <p class="mt-3 text-xl font-semibold text-slate-900">
            SAR {{ number_format($booking->discount ?? 0, 2) }}
        </p>
    </div>


    {{-- GRAND TOTAL --}}
    <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-5 sm:col-span-2 xl:col-span-3">
        <p class="text-sm font-semibold text-indigo-600">
            Grand Total
        </p>

        <p class="mt-3 text-3xl font-bold text-slate-900">
            SAR {{ number_format($booking->grand_total, 2) }}
        </p>
    </div>

</div>

        </div>

    </div>


    {{-- =========================================================
        PASSENGER DETAILS
    ========================================================== --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex items-center justify-between">

            <h2 class="text-xl font-semibold text-slate-900">
                Passenger Details
            </h2>

            <span class="text-sm text-slate-500">
                Total: {{ $booking->passengers->count() }}
            </span>

        </div>


        <div class="mt-6 overflow-x-auto rounded-3xl border border-slate-200">

            <table class="min-w-full border-collapse text-left text-sm">

                <thead class="bg-slate-950 text-white">
    <tr>
        <th class="px-4 py-4 font-semibold">
            Name
        </th>

        <th class="px-4 py-4 font-semibold">
            Type
        </th>

        <th class="px-4 py-4 font-semibold">
            Date of Birth
        </th>

        <th class="px-4 py-4 font-semibold">
            Age
        </th>

      <th class="px-4 py-4 font-semibold">
    Passport
</th>

<th class="px-4 py-4 font-semibold">
    CNIC / ID Card
</th>
    </tr>
</thead>


                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($booking->passengers as $passenger)

                        <tr>

                          <td class="px-4 py-4">
    {{ $passenger->full_name ?? 'Passenger' }}
</td>

<td class="px-4 py-4">
    {{ $passenger->passenger_type ?? '-' }}
</td>

<td class="px-4 py-4">
    {{ $passenger->date_of_birth?->format('d M Y') ?? '-' }}
</td>

<td class="px-4 py-4">
    {{ $passenger->details?->age ?? '-' }}
</td>

<td class="px-4 py-4">
    @if($passenger->passport_document_path)
        <a
            href="{{ $passenger->getPassportDocumentUrl() }}"
            target="_blank"
            class="inline-flex rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100"
        >
            View / Download
        </a>
    @else
        <span class="text-slate-400">Not Uploaded</span>
    @endif
</td>

<td class="px-4 py-4">
    @if($passenger->cnic_document_path)
        <a
            href="{{ $passenger->getCnicDocumentUrl() }}"
            target="_blank"
            class="inline-flex rounded-xl bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100"
        >
            View / Download
        </a>
    @else
        <span class="text-slate-400">Not Uploaded</span>
    @endif
</td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-4 py-8 text-center text-slate-500"
                            >
                                No passenger records available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
        HOTEL VOUCHER MODAL
    ========================================================== --}}
    <div
        id="hotelVoucherModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/60 px-2 py-3 sm:px-4 sm:py-6 backdrop-blur-sm"
    >

        <div
            class="relative flex max-h-[92vh] w-full max-w-[95vw] sm:max-w-6xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl sm:rounded-3xl"
        >


            {{-- =====================================================
                STEP 1
            ====================================================== --}}
            <div id="voucherStep1">

                {{-- HEADER --}}
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4 sm:px-6 sm:py-5">

                    <div class="min-w-0">

                        <h2 class="text-lg font-bold text-slate-900 sm:text-xl">
                            Generate Hotel Voucher
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                            Enter your company information
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="closeVoucherModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200"
                    >
                        ✕
                    </button>

                </div>


                {{-- STEP 1 FORM --}}
                <form
                    action="{{ route('admin.hotel-vouchers.prepare', $booking) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="p-4 sm:p-6">

                        <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5">

                            <h3 class="font-bold text-slate-900">
                                Admin Company Information
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                This company information will appear on the hotel voucher.
                            </p>

                        </div>


                        <div class="mt-6 grid gap-4 sm:gap-6 md:grid-cols-2">


                            {{-- COMPANY NAME --}}
                            <div>

                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Company Name
                                </label>

                                <input
                                    type="text"
                                    name="company_name"
                                    value="{{ old('company_name', session('voucher_company_name')) }}"
                                    required
                                    placeholder="Enter company name"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                >

                            </div>


                            {{-- COMPANY LOGO --}}
                            <div>

                                <label class="mb-2 block text-sm font-semibold text-slate-700">
                                    Company Logo
                                </label>

                                <input
                                    type="file"
                                    name="company_logo"
                                    accept="image/png,image/jpeg,image/webp"
                                    onchange="previewVoucherLogo(event)"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm"
                                >


                                <div
                                    id="voucherLogoPreview"
                                    class="{{ session('voucher_company_logo') ? '' : 'hidden' }} mt-4"
                                >

                                    @if(session('voucher_company_logo'))

                                        <img
                                            src="{{ asset(session('voucher_company_logo')) }}"
                                            alt="Company Logo"
                                            class="h-20 w-20 rounded-2xl border border-slate-200 bg-white object-contain p-2"
                                        >

                                    @else

                                        <img
                                            id="voucherLogoImage"
                                            src=""
                                            alt="Logo Preview"
                                            class="h-20 w-20 rounded-2xl border border-slate-200 bg-white object-contain p-2"
                                        >

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-4 py-4 sm:flex-row sm:justify-end sm:px-6 sm:py-5">

                        <button
                            type="button"
                            onclick="closeVoucherModal()"
                            class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            Next →
                        </button>

                    </div>

                </form>

            </div>



            {{-- =====================================================
                STEP 2
            ====================================================== --}}
            <div
                id="voucherStep2"
                class="hidden min-h-0 flex-1 flex-col"
            >

                {{-- HEADER --}}
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4 sm:px-6 sm:py-5">

                    <div class="min-w-0">

                        <h2 class="text-lg font-bold text-slate-900 sm:text-xl">
                            Hotel Voucher Details
                        </h2>

                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                            Review passenger information and enter passport numbers.
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="closeVoucherModal()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200"
                    >
                        ✕
                    </button>

                </div>


                {{-- SCROLL AREA --}}
                <div class="min-h-0 flex-1 overflow-y-auto p-3 sm:p-6">


                    {{-- =================================================
                        AGENT + ADMIN COMPANY
                    ================================================== --}}
                    <div class="grid gap-4 sm:gap-6 lg:grid-cols-2">


                        {{-- LEFT: AGENT COMPANY --}}
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">

                            <div class="mb-4">

                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                                    Booking Agent
                                </p>

                            </div>


                            @if($booking->travelAgent)

                                @php

                                    $agentName =
                                        $booking->travelAgent->company_name
                                        ?? $booking->travelAgent->name
                                        ?? 'Travel Agent';

                                    $agentLogo =
                                        $booking->travelAgent->company_logo
                                        ?? $booking->travelAgent->logo
                                        ?? null;

                                @endphp


                                <div class="flex items-center gap-4">

                                    @if($agentLogo)

                                        <img
                                            src="{{ asset('storage/' . $agentLogo) }}"
                                            alt="Agent Logo"
                                            class="h-20 w-20 rounded-2xl border border-slate-200 bg-white object-contain p-2"
                                        >

                                    @else

                                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-indigo-100 text-2xl font-bold text-indigo-600">

                                            {{ strtoupper(substr($agentName, 0, 1)) }}

                                        </div>

                                    @endif


                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Agent Company
                                        </p>

                                        <h3 class="mt-1 text-lg font-bold text-slate-900">
                                            {{ $agentName }}
                                        </h3>

                                    </div>

                                </div>


                            @else

                                <div class="flex items-center gap-4">

                                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-200 text-2xl text-slate-400">
                                        —
                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Booking Source
                                        </p>

                                        <h3 class="mt-1 text-lg font-bold text-slate-900">
                                            Direct / Customer Booking
                                        </h3>

                                    </div>

                                </div>

                            @endif

                        </div>



                        {{-- RIGHT: ADMIN COMPANY --}}
                        <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-5">

                            <div class="mb-4">

                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-500">
                                    Voucher Issued By
                                </p>

                            </div>


                            <div class="flex items-center gap-4">

                                @if(session('voucher_company_logo'))

                                    <img
                                        src="{{ asset(session('voucher_company_logo')) }}"
                                        alt="Admin Company Logo"
                                        class="h-20 w-20 rounded-2xl border border-white bg-white object-contain p-2"
                                    >

                                @else

                                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white text-2xl font-bold text-indigo-600">

                                        {{ strtoupper(substr(session('voucher_company_name', 'A'), 0, 1)) }}

                                    </div>

                                @endif


                                <div>

                                    <p class="text-xs text-indigo-500">
                                        Company Name
                                    </p>

                                    <h3 class="mt-1 text-lg font-bold text-slate-900">
                                        {{ session('voucher_company_name', '-') }}
                                    </h3>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        CUSTOMER DETAILS
                    ================================================== --}}
                    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

                        <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">
                            Customer Details
                        </h3>


                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">


                            {{-- NAME --}}
                            <div>

                                <p class="text-xs text-slate-400">
                                    Customer Name
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $booking->contact_name ?: '-' }}
                                </p>

                            </div>


                            {{-- EMAIL --}}
                            <div>

                                <p class="text-xs text-slate-400">
                                    Email
                                </p>

                                <p class="mt-1 text-sm text-slate-700">
                                    {{ $booking->contact_email ?: '-' }}
                                </p>

                            </div>


                            {{-- PHONE --}}
                            <div>

                                <p class="text-xs text-slate-400">
                                    Phone
                                </p>

                                <p class="mt-1 text-sm text-slate-700">
                                    {{ $booking->contact_phone ?: '-' }}
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        HOTEL + STAY
                    ================================================== --}}
                    <div class="mt-6 grid gap-4 sm:gap-6 lg:grid-cols-2">


                        {{-- HOTEL --}}
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">

                            <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">
                                Hotel Details
                            </h3>


                            <div class="mt-5 space-y-4">


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Hotel
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->hotel->hotel_name ?? '-' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Room
                                    </p>

                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $booking->roomType->room_name ?? '-' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Meal Plan
                                    </p>

                                    <p class="mt-1 text-sm text-slate-700">
                                        {{ $booking->mealPlan?->meal_name ?? 'None' }}
                                    </p>

                                </div>

                            </div>

                        </div>



                        {{-- STAY --}}
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">

                            <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">
                                Stay Details
                            </h3>


                            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div>

                                    <p class="text-xs text-slate-400">
                                        Check-in
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->check_in->format('d M Y') }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Check-out
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->check_out->format('d M Y') }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Adults
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->adults }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Children
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->children }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Infants
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->infants }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-slate-400">
                                        Nights
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $booking->total_nights }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        BOOKING SUMMARY
                    ================================================== --}}
                    @php
                        $voucherMealIncluded = !empty($booking->mealPlan) || (float) ($booking->meal_price ?? 0) > 0;
                    @endphp

                    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between gap-4">

                            <h3 class="text-lg font-bold text-slate-900">
                                Booking Summary
                            </h3>

                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                                Total: SAR {{ number_format($booking->grand_total ?? 0, 2) }}
                            </span>

                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs text-slate-400">Meal</p>
                                <p class="mt-2 font-semibold text-slate-900">
                                    {{ $voucherMealIncluded ? 'Included' : 'Not Included' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $booking->mealPlan?->meal_name ?? 'No meal plan selected' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs text-slate-400">Visa</p>
                                <p class="mt-2 font-semibold text-slate-900">
                                    {{ $booking->include_visa ? 'Included' : 'Not Included' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    SAR {{ number_format($booking->visa_price ?? 0, 2) }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs text-slate-400">Transport</p>
                                <p class="mt-2 font-semibold text-slate-900">
                                    {{ $booking->include_transport ? 'Included' : 'Not Included' }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    SAR {{ number_format($booking->transport_price ?? 0, 2) }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                <p class="text-xs text-indigo-500">Grand Total</p>
                                <p class="mt-2 text-xl font-bold text-slate-900">
                                    SAR {{ number_format($booking->grand_total ?? 0, 2) }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        PASSENGERS
                    ================================================== --}}
                    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">


                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <h3 class="text-lg font-bold text-slate-900">
                                    Passenger Details
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Guests included in this hotel reservation
                                </p>

                            </div>


                            <span class="w-fit rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">

                                {{ $booking->passengers->count() }} Passenger(s)

                            </span>

                        </div>



                        {{-- PASSENGER FORM --}}
                        <form
                            action="{{ route('admin.hotel-vouchers.passengers', $booking) }}"
                            method="POST"
                            class="mt-5"
                        >

                            @csrf

                            <div class="mb-5 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Room Number
                                    </label>
                                    <input
                                        type="text"
                                        name="room_number"
                                        value="{{ old('room_number', session('voucher_room_number', $booking->room?->room_number ?? 'Pending')) }}"
                                        placeholder="Enter room number"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                    >
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Payment Status
                                    </label>
                                    <select
                                        name="payment_status"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                    >
                                        <option value="Pending" {{ old('payment_status', session('voucher_payment_status', $booking->payment_status ?? 'Pending')) === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Paid" {{ old('payment_status', session('voucher_payment_status', $booking->payment_status ?? 'Pending')) === 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Partial" {{ old('payment_status', session('voucher_payment_status', $booking->payment_status ?? 'Pending')) === 'Partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="Unpaid" {{ old('payment_status', session('voucher_payment_status', $booking->payment_status ?? 'Pending')) === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-2xl border border-slate-200">

                                <table class="min-w-full text-left text-sm">

                                    <thead class="bg-slate-950 text-white">

                                        <tr>

                                            <th class="px-4 py-4 font-semibold">
                                                #
                                            </th>

                                            <th class="px-4 py-4 font-semibold">
                                                Name
                                            </th>

                                            <th class="px-4 py-4 font-semibold">
                                                Type
                                            </th>

                                            <th class="px-4 py-4 font-semibold">
                                                Age
                                            </th>

                                            <th class="px-4 py-4 font-semibold">
                                                Passport
                                            </th>


                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-slate-200 bg-white">

                                        @forelse($booking->passengers as $index => $passenger)

                                            <tr class="transition hover:bg-slate-50">


                                                {{-- NUMBER --}}
                                                <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-500">
                                                    {{ $index + 1 }}
                                                </td>


                                                {{-- NAME --}}
                                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-900">
                                                    {{ $passenger->full_name ?? 'Passenger' }}
                                                </td>


                                                {{-- TYPE --}}
                                                <td class="whitespace-nowrap px-4 py-4">

                                                    @php
                                                        $type = strtolower($passenger->passenger_type ?? '');
                                                    @endphp


                                                    @if($type === 'adult')

                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                            Adult
                                                        </span>

                                                    @elseif($type === 'child')

                                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                            Child
                                                        </span>

                                                    @elseif($type === 'infant')

                                                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                                            Infant
                                                        </span>

                                                    @else

                                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                            {{ $passenger->passenger_type ?? '-' }}
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- AGE --}}
                                                <td class="whitespace-nowrap px-4 py-4 text-slate-700">
                                                    {{ $passenger->details?->age ?? '-' }}
                                                </td>


                                                {{-- PASSPORT --}}
                                                <td class="min-w-[190px] px-4 py-4">
                                                    <input
                                                        type="text"
                                                        name="passengers[{{ $passenger->id }}][passport_number]"
                                                        value="{{ old(
                                                            'passengers.' . $passenger->id . '.passport_number',
                                                            $passenger->passport_number ?? ''
                                                        ) }}"
                                                        placeholder="Passport number"
                                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                                    >
                                                </td>


                                            </tr>


                                        @empty

                                            <tr>

                                                <td
                                                    colspan="6"
                                                    class="px-4 py-10 text-center text-slate-500"
                                                >
                                                    No passenger records available.
                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>


                            {{-- SAVE PASSPORT --}}
                            <div class="mt-5 flex justify-end">

                                <button
                                    type="submit"
                                    class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                >
                                    Save Passport Details
                                </button>

                            </div>

                        </form>

                    </div>

                </div>



                {{-- =================================================
                    STEP 2 FOOTER
                ================================================== --}}
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-5">

                    <button
                        type="button"
                        onclick="showVoucherStep1()"
                        class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                    >
                        ← Back
                    </button>


                    <a
                        href="{{ route('admin.hotel-vouchers.generate', $booking) }}"
                        target="_blank"
                        class="rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Generate Voucher
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

    /*
    |--------------------------------------------------------------------------
    | OPEN MODAL
    |--------------------------------------------------------------------------
    */

    function openVoucherStep1() {

        const modal = document.getElementById('hotelVoucherModal');

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        showVoucherStep1();
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    function closeVoucherModal() {

        const modal = document.getElementById('hotelVoucherModal');

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 1
    |--------------------------------------------------------------------------
    */

    function showVoucherStep1() {

        const step1 = document.getElementById('voucherStep1');
        const step2 = document.getElementById('voucherStep2');

        if (!step1 || !step2) {
            return;
        }

        step1.classList.remove('hidden');

        step2.classList.add('hidden');
        step2.classList.remove('flex');
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */

    function showVoucherStep2() {

        const step1 = document.getElementById('voucherStep1');
        const step2 = document.getElementById('voucherStep2');

        if (!step1 || !step2) {
            return;
        }

        step1.classList.add('hidden');

        step2.classList.remove('hidden');
        step2.classList.add('flex');
    }


    /*
    |--------------------------------------------------------------------------
    | COMPANY LOGO PREVIEW
    |--------------------------------------------------------------------------
    */

    function previewVoucherLogo(event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const preview = document.getElementById('voucherLogoPreview');

        if (!preview) {
            return;
        }

        let image = document.getElementById('voucherLogoImage');


        if (!image) {

            image = document.createElement('img');

            image.id = 'voucherLogoImage';

            image.alt = 'Company Logo Preview';

            image.className =
                'h-20 w-20 rounded-2xl border border-slate-200 bg-white object-contain p-2';

            preview.innerHTML = '';

            preview.appendChild(image);
        }


        image.src = URL.createObjectURL(file);

        preview.classList.remove('hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeVoucherModal();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | OPEN STEP 2 AFTER PREPARE REQUEST
    |--------------------------------------------------------------------------
    */

    @if(session('voucher_step_2'))

        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('hotelVoucherModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            showVoucherStep2();

        });

    @endif

</script>

@endsection