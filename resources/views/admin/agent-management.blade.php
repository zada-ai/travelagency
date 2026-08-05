@extends('admin.layouts.app')

@section('title', 'Agent Management')
@section('page-heading', 'Agent Management')
@section('page-description', 'Manage travel agents, approvals, and partner accounts within the admin console.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Agent Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Agent Panel</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Review and manage travel agents without leaving the shared admin layout.</p>
            </div>
        </div>
    </div>
</div>
@endsection
