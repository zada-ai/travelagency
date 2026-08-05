@extends('admin.layouts.app')

@section('title', 'Quote Management')
@section('page-heading', 'Quote Management')
@section('page-description', 'Manage quotes and proposals from the central admin panel.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Quote Management</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Quotes</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Handle price quotations and proposals without replacing the entire admin frame.</p>
            </div>
        </div>
    </div>
</div>
@endsection
