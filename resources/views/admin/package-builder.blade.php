@extends('admin.layouts.app')

@section('title', 'Package Builder')
@section('page-heading', 'Package Builder')
@section('page-description', 'Create and manage travel packages from the shared admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Package Builder</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Package Builder</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Create travel packages while preserving the admin sidebar and header.</p>
            </div>
        </div>
    </div>
</div>
@endsection
