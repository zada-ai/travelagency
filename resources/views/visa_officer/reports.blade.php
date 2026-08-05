@extends('visa_officer.layouts.app')

@section('title', 'Reports | Visa Officer')

@section('content')
    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Visa Officer Reports</h1>
                <p class="mt-2 text-sm text-slate-500">Overview of your assigned applications and status distribution.</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Assigned</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['total'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Approved</div>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $metrics['approved'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Rejected</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $metrics['rejected'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                <div class="text-xs text-slate-400 uppercase font-bold">Issued</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['issued'] ?? 0 }}</div>
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Status Summary</h2>
                @if($applicationsByStatus->isEmpty())
                    <p class="text-sm text-slate-500">No application data available yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($applicationsByStatus as $status => $count)
                            <div class="flex items-center justify-between rounded-2xl bg-white border border-slate-200 p-4">
                                <span class="text-sm text-slate-700">{{ $status }}</span>
                                <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Workflow Metrics</h2>
                <div class="space-y-3">
                    <div class="rounded-2xl bg-white border border-slate-200 p-4">
                        <div class="text-xs uppercase tracking-wider text-slate-400 font-bold">Pending Reviews</div>
                        <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $metrics['pending'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-4">
                        <div class="text-xs uppercase tracking-wider text-slate-400 font-bold">Under Review</div>
                        <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['under_review'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-4">
                        <div class="text-xs uppercase tracking-wider text-slate-400 font-bold">Documents Required</div>
                        <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $metrics['documents_required'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-4">
                        <div class="text-xs uppercase tracking-wider text-slate-400 font-bold">Today</div>
                        <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['today'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
