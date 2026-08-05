@extends('admin.layouts.app')

@section('title', 'Payment Management')
@section('page-heading', 'Payment Management')
@section('page-description', 'Handle financial workflows and transaction records inside the shared admin layout.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Payment Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Payments</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Monitor payments and transaction status from the unified admin panel.</p>
            </div>
        </div>
    </div>
</div>
@endsection
