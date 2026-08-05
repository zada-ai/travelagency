@extends('admin.layouts.app')

@section('title', 'Dynamic Package Calculator')
@section('page-heading', 'Dynamic Package Calculator')
@section('page-description', 'Calculate custom travel package pricing inside the shared admin layout.')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Dynamic Package Calculator</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950">Package Calculator</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Run pricing calculations without leaving the shared admin panel.</p>
            </div>
        </div>
    </div>
</div>
@endsection
