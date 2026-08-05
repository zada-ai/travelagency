@extends('admin.layouts.app')

@section('title', 'Visa Reports')
@section('page-heading', 'Visa Reports')
@section('page-description', 'Generate reports, export Excel tables, and download HTML-PDF charts.')

@section('content')
<section class="space-y-6">

    <header class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex flex-wrap justify-between items-center gap-4 bg-white border border-slate-200">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block mb-1">Reports Engine</span>
            <h2 class="text-xl font-bold text-slate-900">Visa Analytics Reports</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.visa-reports.export.excel', request()->all()) }}" class="inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-2.5 transition hover:bg-slate-50 shadow-xs">
                Export CSV/Excel
            </a>
            <a href="{{ route('admin.visa-reports.export.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white px-4 py-2.5 shadow-sm transition">
                Print PDF Report
            </a>
            <a href="{{ route('admin.visa-management') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-bold text-white px-4 py-2.5 shadow-sm transition">
                Back to Dashboard
            </a>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr] items-start">
        
        <!-- Filter and aggregates sidebar -->
        <div class="space-y-6">
            <article class="glass-panel rounded-3xl p-6 shadow-xs bg-white border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Report Filter</h3>
                <form action="{{ route('admin.visa-reports') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Time Period</label>
                        <select name="period" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white font-semibold">
                            <option value="all" {{ request('period') === 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="daily" {{ request('period') === 'daily' ? 'selected' : '' }}>Today's Applications</option>
                            <option value="monthly" {{ request('period') === 'monthly' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Visa Type</label>
                        <select name="visa_type_id" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white font-semibold">
                            <option value="">All Types</option>
                            @foreach ($visaTypes as $type)
                                <option value="{{ $type->id }}" {{ request('visa_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white font-semibold">
                            <option value="">All Statuses</option>
                            <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Documents Required" {{ request('status') === 'Documents Required' ? 'selected' : '' }}>Documents Required</option>
                            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Issued" {{ request('status') === 'Issued' ? 'selected' : '' }}>Issued</option>
                            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs py-3.5 shadow-sm transition">
                        Generate Report
                    </button>
                </form>
            </article>

            <!-- Aggregates card summary -->
            <article class="glass-panel rounded-3xl p-6 shadow-xs bg-white border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Status Distribution</h3>
                <div class="space-y-2">
                    @foreach ($statusCounts as $st => $count)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-100 p-3 text-xs">
                            <span class="font-bold text-slate-600 uppercase">{{ $st }}</span>
                            <span class="font-black text-slate-800">{{ $count }}</span>
                        </div>
                    @endforeach
                    @if (empty($statusCounts))
                        <p class="text-xs text-slate-400 font-bold text-center">No applications logged in system.</p>
                    @endif
                </div>
            </article>
        </div>

        <!-- Details Listing -->
        <article class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs bg-white border border-slate-200">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Audited Applications list</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="pb-3 pl-3">ID</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Visa Type</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 pr-3 text-right">Total Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($applications as $app)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 pl-3 font-bold text-slate-900">#{{ $app->id }}</td>
                                <td class="py-3 text-slate-800">{{ $app->customer_name }}</td>
                                <td class="py-3 text-slate-600">{{ $app->visaType?->name }}</td>
                                <td class="py-3 text-slate-600">{{ optional($app->travel_from)->format('d M Y') ?? $app->travel_from }}</td>
                                <td class="py-3">
                                    <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase bg-slate-100 text-slate-700">{{ $app->status }}</span>
                                </td>
                                <td class="py-3 pr-3 text-right font-bold text-slate-900">SAR {{ number_format($app->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-bold">
                                    No records found matching generating criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

    </div>
</section>
@endsection
