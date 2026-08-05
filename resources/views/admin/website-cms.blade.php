@extends('admin.layouts.app')

@section('title', 'Website CMS')
@section('page-heading', 'Website CMS')
@section('page-description', 'Manage website content and CMS settings from the shared admin layout.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Website CMS</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Content Management</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Edit website content while keeping the admin sidebar and header fixed.</p>
            </div>
        </div>
    </div>
</div>
@endsection
