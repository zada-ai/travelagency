@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-heading', 'User Management')
@section('page-description', 'Manage admin users and access control from the shared admin dashboard.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">User Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Admin Users</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Manage admin accounts while preserving the persistent sidebar and header.</p>
            </div>
        </div>
    </div>
</div>
@endsection
