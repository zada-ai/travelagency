@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('page-heading', 'Booking Management')
@section('page-description', 'Review bookings and reservation workflows within the shared admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Booking Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Booking Administration</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Access booking workflows and reservation controls inside the shared layout.</p>
            </div>
        </div>
    </div>
</div>
@endsection
