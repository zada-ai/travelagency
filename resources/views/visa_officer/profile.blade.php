@extends('visa_officer.layouts.app')

@section('title', 'Profile | Visa Officer')

@section('content')
    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Visa Officer Profile</h1>
                <p class="mt-2 text-sm text-slate-500">Your officer details and contact information.</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Name</span>
                <div class="mt-2 font-bold text-slate-900">{{ $user->name }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Email</span>
                <div class="mt-2 font-bold text-slate-900">{{ $user->email }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Phone</span>
                <div class="mt-2 font-bold text-slate-900">{{ $user->phone ?? $user->mobile ?? 'N/A' }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Designation</span>
                <div class="mt-2 font-bold text-slate-900">{{ $user->designation ?? 'Visa Officer' }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Employee ID</span>
                <div class="mt-2 font-bold text-slate-900">{{ $user->employee_id ?? 'N/A' }}</div>
            </div>
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Status</span>
                <div class="mt-2 font-bold text-slate-900">{{ ucfirst($user->status ?? 'Active') }}</div>
            </div>
        </div>
    </section>
@endsection
