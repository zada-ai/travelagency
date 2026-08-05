@extends('admin.layouts.app')

@section('title', 'CRM')
@section('page-heading', 'CRM')
@section('page-description', 'Manage customer relationships and communications from the unified admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">CRM</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Customer Relationship Management</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Handle leads, contacts, and support workflows inside the shared admin frame.</p>
            </div>
        </div>
    </div>
</div>
@endsection
