@extends('admin.layouts.app')

@section('title', 'Accounting')
@section('page-heading', 'Accounting')
@section('page-description', 'Manage invoices, payments, and financial records inside the main admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Accounting</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Accounting Overview</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Track payments, invoices, and financial workflows from the shared admin interface.</p>
            </div>
        </div>
    </div>
</div>
@endsection
