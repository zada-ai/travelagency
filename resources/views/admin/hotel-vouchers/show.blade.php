<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hotel Voucher - {{ $booking->reference_number }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .voucher {
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-900">

<div class="min-h-screen py-8 px-4">

    {{-- Print / Back Buttons --}}
    <div class="no-print max-w-5xl mx-auto mb-5 flex justify-end gap-3">

        <a
            href="{{ url()->previous() }}"
            class="rounded-xl bg-slate-600 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700"
        >
            ← Back
        </a>

        <button
            onclick="window.print()"
            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            🖨 Print Voucher
        </button>

    </div>


    {{-- ============================================================
         VOUCHER
    ============================================================= --}}
    <div class="voucher max-w-5xl mx-auto overflow-hidden rounded-3xl bg-white shadow-xl border border-slate-200">


        {{-- ========================================================
             HEADER
        ========================================================= --}}
        <div class="border-b border-slate-200 px-8 py-7">

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                {{-- Company --}}
                <div class="flex items-center gap-4">

                    @if($voucherSetting?->logo)

                        <img
                            src="{{ asset($voucherSetting->logo) }}"
                            alt="{{ $voucherSetting->company_name ?? 'Company Logo' }}"
                            class="h-20 w-20 rounded-2xl object-contain border border-slate-200 bg-white p-2"
                        >

                    @else

                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-indigo-600 text-2xl font-bold text-white">
                            {{ strtoupper(substr($voucherSetting->company_name ?? 'V', 0, 1)) }}
                        </div>

                    @endif

                    <div>

                        <h1 class="text-2xl font-bold text-slate-900">
                            {{ $voucherSetting->company_name ?? config('app.name', 'Travel Agency') }}
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Hotel Reservation & Accommodation Services
                        </p>

                    </div>

                </div>


                {{-- Voucher --}}
                <div class="text-left sm:text-right">

                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-indigo-600">
                        Hotel Voucher
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $booking->reference_number }}
                    </h2>

                    <div class="mt-2 flex gap-2 sm:justify-end">

                        <span class="inline-flex rounded-full bg-emerald-100 px-4 py-1.5 text-xs font-semibold text-emerald-700">
                            {{ $booking->status }}
                        </span>

                        @if($booking->payment_status)

                            <span class="inline-flex rounded-full bg-amber-100 px-4 py-1.5 text-xs font-semibold text-amber-700">
                                Payment: {{ $booking->payment_status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             BOOKING INFORMATION
        ========================================================= --}}
        <div class="px-8 py-7">

            <div class="mb-5">

                <h2 class="text-lg font-bold text-slate-900">
                    Booking Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Complete reservation information
                </p>

            </div>


            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Reference --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Booking Reference
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $booking->reference_number }}
                    </p>

                </div>


                {{-- Booking Date --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Booking Date
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ optional($booking->created_at)->format('d M Y') }}
                    </p>

                </div>


                {{-- Status --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Booking Status
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $booking->status ?? '-' }}
                    </p>

                </div>


                {{-- Payment --}}
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Payment Status
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $booking->payment_status ?? '-' }}
                    </p>

                </div>

            </div>

        </div>



        {{-- ========================================================
             HOTEL DETAILS
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <h2 class="mb-5 text-lg font-bold text-slate-900">
                Hotel Details
            </h2>


            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                @if($booking->hotel?->cover_image_url)
                    <div class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2">
                        <img
                            src="{{ $booking->hotel->cover_image_url }}"
                            alt="{{ $booking->hotel->hotel_name ?? 'Hotel Image' }}"
                            class="h-32 w-full rounded-xl object-cover"
                        >
                    </div>
                @endif

                <div class="grid gap-6 md:grid-cols-2">

                    {{-- Hotel --}}
                    <div>

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Hotel
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-900">
                            {{ $booking->hotel->hotel_name ?? '-' }}
                        </p>

                    </div>


                    {{-- City --}}
                    <div>

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Hotel Location
                        </p>

                        <p class="mt-2 font-semibold text-slate-900">
                            {{ $booking->hotel->city ?? 'Makkah' }}
                        </p>

                    </div>


                    {{-- Address --}}
                    <div class="md:col-span-2">

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Hotel Address
                        </p>

                        <p class="mt-2 text-sm text-slate-700">
                            {{ $booking->hotel->address
                                ?? $booking->hotel->location
                                ?? '-' }}
                        </p>

                    </div>


                    {{-- Description --}}
                    @if($booking->hotel->description ?? null)

                        <div class="md:col-span-2">

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Hotel Description
                            </p>

                            <p class="mt-2 text-sm leading-6 text-slate-700">
                                {{ $booking->hotel->description }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- ========================================================
             STAY INFORMATION
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <h2 class="mb-5 text-lg font-bold text-slate-900">
                Stay Information
            </h2>


            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

                {{-- Room --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Room Type
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $booking->roomType->room_name ?? '-' }}
                    </p>

                    @if(isset($booking->room_price))

                        <p class="mt-1 text-sm text-slate-500">
                            SAR {{ number_format($booking->room_price, 2) }}
                        </p>

                    @endif

                </div>


                {{-- Check In --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Check-in
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ optional($booking->check_in)->format('d M Y') }}
                    </p>

                </div>


                {{-- Check Out --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Check-out
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ optional($booking->check_out)->format('d M Y') }}
                    </p>

                </div>


                {{-- Nights --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Nights
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">

                        @if($booking->check_in && $booking->check_out)
                            {{ $booking->check_in->diffInDays($booking->check_out) }}
                        @else
                            -
                        @endif

                    </p>

                </div>


                {{-- Room Number --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Room Number
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $voucherRoomNumber }}
                    </p>

                </div>


                {{-- Payment Status --}}
                <div class="rounded-2xl border border-slate-200 p-4">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Payment Status
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $voucherPaymentStatus }}
                    </p>

                </div>

            </div>


            {{-- Meal --}}
            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs uppercase tracking-wide text-slate-400">
                            Meal Plan
                        </p>

                        <p class="mt-1 font-bold text-slate-900">
                            @if($booking->mealPlan || (float) ($booking->meal_price ?? 0) > 0)
                                {{ $booking->mealPlan?->meal_name ?? 'Included' }}
                            @else
                                No meals
                            @endif
                        </p>

                    </div>

                    <div class="text-left sm:text-right">

                        <p class="text-xs text-slate-400">
                            Meal Price
                        </p>

                        <p class="font-bold text-slate-900">
                            SAR {{ number_format($booking->meal_price ?? 0, 2) }}
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             GUEST COUNTS
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <h2 class="mb-5 text-lg font-bold text-slate-900">
                Guest Counts
            </h2>


            <div class="grid gap-4 sm:grid-cols-3">

                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-5">

                    <p class="text-xs uppercase tracking-wide text-blue-500">
                        Adults
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $booking->adults ?? 0 }}
                    </p>

                </div>


                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Children
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $booking->children ?? 0 }}
                    </p>

                </div>


                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Infants
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ $booking->infants ?? 0 }}
                    </p>

                </div>

            </div>

        </div>



        {{-- ========================================================
             CONTACT INFORMATION
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <h2 class="mb-5 text-lg font-bold text-slate-900">
                Contact Information
            </h2>


            <div class="grid gap-4 md:grid-cols-3">

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Contact Name
                    </p>

                    <p class="mt-2 font-bold text-slate-900">
                        {{ $booking->contact_name ?? '-' }}
                    </p>

                </div>


                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Email
                    </p>

                    <p class="mt-2 text-sm font-semibold text-slate-900 break-all">
                        {{ $booking->contact_email ?? '-' }}
                    </p>

                </div>


                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                    <p class="text-xs uppercase tracking-wide text-slate-400">
                        Phone
                    </p>

                    <p class="mt-2 font-semibold text-slate-900">
                        {{ $booking->contact_phone ?? '-' }}
                    </p>

                </div>

            </div>

        </div>



        {{-- ========================================================
             PASSENGER DETAILS
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <div class="mb-5">

                <h2 class="text-lg font-bold text-slate-900">
                    Passenger Details
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Passenger information and uploaded documents
                </p>

            </div>


            <div class="space-y-5">

                @forelse($booking->passengers as $passenger)

                    @php
                        $passportUrl = method_exists($passenger, 'getPassportDocumentUrl')
                            ? $passenger->getPassportDocumentUrl()
                            : null;

                        $cnicUrl = method_exists($passenger, 'getCnicDocumentUrl')
                            ? $passenger->getCnicDocumentUrl()
                            : null;
                    @endphp


                    <div class="overflow-hidden rounded-2xl border border-slate-200">


                        {{-- Passenger Header --}}
                        <div class="flex items-center justify-between bg-slate-950 px-5 py-4 text-white">

                            <div>

                                <p class="text-xs uppercase tracking-wider text-slate-400">
                                    Passenger {{ $loop->iteration }}
                                </p>

                                <p class="mt-1 text-lg font-bold">
                                    {{ $passenger->full_name ?? 'Passenger' }}
                                </p>

                            </div>


                            <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold">
                                {{ $passenger->passenger_type ?? '-' }}
                            </span>

                        </div>


                        {{-- Passenger Information --}}
                        <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">

                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Full Name
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $passenger->full_name ?? '-' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Passenger Type
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $passenger->passenger_type ?? '-' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Age
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                   {{ $passenger->details?->age ?? '-' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Date of Birth
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">

                                    @if($passenger->date_of_birth)

                                        {{ $passenger->date_of_birth->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-wide text-slate-400">
                                    Passport Number
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $passenger->passport_number ?? '-' }}
                                </p>

                            </div>

                        </div>


                        {{-- Documents --}}
                        <div class="border-t border-slate-200 bg-slate-50 p-5">

                            <p class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Uploaded Documents
                            </p>


                            <div class="grid gap-4 sm:grid-cols-2">


                                {{-- Passport --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-4">

                                    <div class="flex items-center justify-between gap-3">

                                        <div>

                                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                                Passport / Document
                                            </p>

                                            @if($passenger->passport_document_path)

                                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                                    Document Uploaded
                                                </p>

                                            @else

                                                <p class="mt-1 text-sm text-slate-500">
                                                    Not uploaded
                                                </p>

                                            @endif

                                        </div>


                                        @if($passportUrl)

                                            <a
                                                href="{{ $passportUrl }}"
                                                target="_blank"
                                                class="shrink-0 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700"
                                            >
                                                View
                                            </a>

                                        @endif

                                    </div>

                                </div>


                                {{-- CNIC --}}
                                <div class="rounded-xl border border-slate-200 bg-white p-4">

                                    <div class="flex items-center justify-between gap-3">

                                        <div>

                                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                                CNIC / ID Card
                                            </p>

                                            @if($passenger->cnic_document_path)

                                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                                    Document Uploaded
                                                </p>

                                            @else

                                                <p class="mt-1 text-sm text-slate-500">
                                                    Not uploaded
                                                </p>

                                            @endif

                                        </div>


                                        @if($cnicUrl)

                                            <a
                                                href="{{ $cnicUrl }}"
                                                target="_blank"
                                                class="shrink-0 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700"
                                            >
                                                View
                                            </a>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        No passenger records available.
                    </div>

                @endforelse

            </div>

        </div>



        {{-- ========================================================
             ADD-ON SERVICES
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <h2 class="mb-5 text-lg font-bold text-slate-900">
                Add-on Services
            </h2>


            <div class="grid gap-4 md:grid-cols-3">


                {{-- Meal --}}
                <div class="rounded-2xl border border-slate-200 p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Meal Plan
                            </p>

                            @if($booking->mealPlan || (float) ($booking->meal_price ?? 0) > 0)

                                <p class="mt-2 font-bold text-slate-900">
                                    Included
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $booking->mealPlan?->meal_name ?? 'Meal plan selected' }}
                                </p>

                            @else

                                <p class="mt-2 font-semibold text-slate-500">
                                    Not Included
                                </p>

                            @endif

                        </div>

                        @if($booking->mealPlan || (float) ($booking->meal_price ?? 0) > 0)

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                YES
                            </span>

                        @else

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                NO
                            </span>

                        @endif

                    </div>


                    @if($booking->mealPlan)

                        <div class="mt-4 border-t border-slate-200 pt-4 flex justify-between">

                            <span class="text-sm text-slate-500">
                                Meal Price
                            </span>

                            <span class="font-bold text-slate-900">
                                SAR {{ number_format($booking->meal_price ?? 0, 2) }}
                            </span>

                        </div>

                    @endif

                </div>


                {{-- Visa --}}
                <div class="rounded-2xl border border-slate-200 p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Visa Processing
                            </p>

                            @if($booking->include_visa)

                                <p class="mt-2 font-bold text-slate-900">
                                    Included
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Individual Pilgrim Visa
                                </p>

                            @else

                                <p class="mt-2 font-semibold text-slate-500">
                                    Not Included
                                </p>

                            @endif

                        </div>


                        @if($booking->include_visa)

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                YES
                            </span>

                        @else

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                NO
                            </span>

                        @endif

                    </div>


                    @if($booking->include_visa)

                        <div class="mt-4 border-t border-slate-200 pt-4 flex justify-between">

                            <span class="text-sm text-slate-500">
                                Visa Price
                            </span>

                            <span class="font-bold text-slate-900">
                                SAR {{ number_format($booking->visa_price ?? 0, 2) }}
                            </span>

                        </div>

                    @endif

                </div>



                {{-- Transport --}}
                <div class="rounded-2xl border border-slate-200 p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Transport Service
                            </p>

                            @if($booking->include_transport)

                                <p class="mt-2 font-bold text-slate-900">
                                    Included
                                </p>

                            @else

                                <p class="mt-2 font-semibold text-slate-500">
                                    Not Included
                                </p>

                            @endif

                        </div>


                        @if($booking->include_transport)

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                YES
                            </span>

                        @else

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                NO
                            </span>

                        @endif

                    </div>


                    @if($booking->include_transport)

                        <div class="mt-4 space-y-2 border-t border-slate-200 pt-4">

                            <div class="flex justify-between text-sm">

                                <span class="text-slate-500">
                                    Transport Total
                                </span>

                                <span class="font-bold text-slate-900">
                                    SAR {{ number_format($booking->transport_price ?? 0, 2) }}
                                </span>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- ========================================================
             PRICE SUMMARY
        ========================================================= --}}
        <div class="border-t border-slate-200 px-8 py-7">

            <div class="grid gap-8 lg:grid-cols-2">


                {{-- Price --}}
                <div>

                    <h2 class="mb-5 text-lg font-bold text-slate-900">
                        Price Summary
                    </h2>


                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 space-y-4">


                        {{-- Room --}}
                        <div class="flex justify-between gap-4">

                            <span class="text-sm text-slate-500">
                                Room
                            </span>

                            <span class="font-semibold text-slate-900">
                                SAR {{ number_format($booking->room_price ?? 0, 2) }}
                            </span>

                        </div>


                        {{-- Meal --}}
                        <div class="flex justify-between gap-4">

                            <span class="text-sm text-slate-500">
                                Meal
                            </span>

                            <span class="font-semibold text-slate-900">
                                SAR {{ number_format($booking->meal_price ?? 0, 2) }}
                            </span>

                        </div>


                        {{-- Visa --}}
                        @if($booking->include_visa || ($booking->visa_price ?? 0) > 0)

                            <div class="flex justify-between gap-4">

                                <span class="text-sm text-slate-500">
                                    Visa
                                </span>

                                <span class="font-semibold text-slate-900">
                                    SAR {{ number_format($booking->visa_price ?? 0, 2) }}
                                </span>

                            </div>

                        @endif


                        {{-- Transport --}}
                        @if($booking->include_transport || ($booking->transport_price ?? 0) > 0)

                            <div class="flex justify-between gap-4">

                                <span class="text-sm text-slate-500">
                                    Transport
                                </span>

                                <span class="font-semibold text-slate-900">
                                    SAR {{ number_format($booking->transport_price ?? 0, 2) }}
                                </span>

                            </div>

                        @endif


                        {{-- Taxes --}}
                        <div class="flex justify-between gap-4">

                            <span class="text-sm text-slate-500">
                                Taxes
                            </span>

                            <span class="font-semibold text-slate-900">
                                SAR {{ number_format($booking->taxes ?? 0, 2) }}
                            </span>

                        </div>


                        {{-- Discount --}}
                        @if(($booking->discount ?? 0) > 0)

                            <div class="flex justify-between gap-4">

                                <span class="text-sm text-slate-500">
                                    Discount
                                </span>

                                <span class="font-semibold text-emerald-600">
                                    - SAR {{ number_format($booking->discount ?? 0, 2) }}
                                </span>

                            </div>

                        @endif


                        {{-- Grand Total --}}
                        <div class="border-t border-slate-200 pt-4 flex justify-between gap-4">

                            <span class="font-bold text-slate-900">
                                Grand Total
                            </span>

                            <span class="text-2xl font-bold text-indigo-600">
                                SAR {{ number_format($booking->grand_total ?? 0, 2) }}
                            </span>

                        </div>

                    </div>

                </div>



                {{-- Booking Source --}}
                <div>

                    <h2 class="mb-5 text-lg font-bold text-slate-900">
                        Booking Source
                    </h2>


                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">

                        @if($booking->travelAgent)

                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Travel Agent
                            </p>

                            <p class="mt-2 text-lg font-bold text-slate-900">

                                {{ $booking->travelAgent->company_name
                                    ?? trim(
                                        ($booking->travelAgent->first_name ?? '') . ' ' .
                                        ($booking->travelAgent->last_name ?? '')
                                    )
                                    ?: 'Travel Agent' }}

                            </p>


                            @if($booking->travelAgent->email)

                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $booking->travelAgent->email }}
                                </p>

                            @endif


                            @if($booking->travelAgent->mobile)

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $booking->travelAgent->mobile }}
                                </p>

                            @endif

                        @else

                            <p class="text-sm font-semibold text-slate-700">
                                Direct / Customer Booking
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                This reservation was created directly by the customer.
                            </p>

                        @endif

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             FOOTER
        ========================================================= --}}
        <div class="border-t border-slate-200 bg-slate-50 px-8 py-6">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm font-semibold text-slate-900">

                        Thank you for choosing
                        {{ $voucherSetting->company_name ?? config('app.name', 'Travel Agency') }}.

                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Please present this voucher at the hotel during check-in.
                    </p>

                </div>


                <div class="text-left sm:text-right">

                    <p class="text-xs text-slate-400">
                        Generated on
                    </p>

                    <p class="text-sm font-semibold text-slate-700">
                        {{ now()->format('d M Y, h:i A') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>