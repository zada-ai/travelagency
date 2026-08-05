@extends('admin.layouts.app')

@section('title', 'Reports')
@section('page-heading', 'Reports')
@section('page-description', 'View analytics and reports inside the central admin dashboard.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Reports</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Analytics Reports</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Access reports and data insights while preserving the shared sidebar and header.</p>
            </div>
        </div>
    </div>
</div>
@endsection
