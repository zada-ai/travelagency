@extends('admin.layouts.app')

@section('title', 'Voucher Management')
@section('page-heading', 'Voucher Management')
@section('page-description', 'Manage vouchers and promotions inside the shared admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Voucher Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Vouchers</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Manage vouchers and promotions without changing the shared layout.</p>
            </div>
        </div>
    </div>
</div>
@endsection
