@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-heading', 'Super Admin Dashboard')
@section('page-description', 'Manage the Umrah ERP operations from the internal administration portal.')

@section('content')
<section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <a href="{{ route('admin.agent-management') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Operations</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Agent Management</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Review, approve, and manage travel agency accounts.</p>
    </a>

    <a href="{{ route('admin.visa-management') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Visa Operations</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Visa Management</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Track applications, assignments, documents, and approvals.</p>
    </a>

    <a href="{{ route('admin.booking-management') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Bookings</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Booking Management</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Manage hotel, flight, and customer booking workflows.</p>
    </a>

    <a href="{{ route('admin.hotel-management') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Inventory</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Hotel Management</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Configure hotels, rooms, rates, facilities, and inventory.</p>
    </a>

    <a href="{{ route('admin.airline-ticket-management') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Flights</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Airline &amp; Tickets</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Maintain airlines, flights, tickets, and flight bookings.</p>
    </a>

    <a href="{{ route('admin.reports') }}" class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-lg transition hover:border-blue-500">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-400">Insights</p>
        <h2 class="mt-3 text-xl font-semibold text-white">Reports</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Review operational and financial reporting modules.</p>
    </a>
</section>
@endsection
