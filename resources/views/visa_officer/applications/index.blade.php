@extends('visa_officer.layouts.app')

@section('title', "$title | Visa Officer")

@section('content')
    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">{{ $title }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-8">
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Assigned</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['total'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Pending Reviews</div>
                    <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $metrics['pending'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Under Review</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['under_review'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Documents Required</div>
                    <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $metrics['documents_required'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Approved</div>
                    <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $metrics['approved'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Rejected</div>
                    <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $metrics['rejected'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center col-span-2 lg:col-span-1">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Issued</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['issued'] ?? 0 }}</div>
                </div>
                <div class="rounded-2xl bg-white border border-slate-200 p-4 text-center col-span-2 lg:col-span-1">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Today</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['today'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Application List</h2>
                <p class="text-sm text-slate-500">Only applications assigned to your visa desk are shown here.</p>
            </div>
            <form class="flex flex-col sm:flex-row gap-3" method="GET" action="">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm w-full sm:w-60" />
                <input type="text" name="passport" value="{{ request('passport') }}" placeholder="Passport number" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm w-full sm:w-60" />
                <button type="submit" class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition">Filter</button>
            </form>
        </div>

        @if($applications->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No applications found for the selected criteria.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 bg-slate-100">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Passport</th>
                            <th class="px-4 py-3">Visa Type</th>
                            <th class="px-4 py-3">Agent</th>
                            <th class="px-4 py-3">Travel Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr class="border-t border-slate-200">
                                <td class="px-4 py-3 text-slate-700">#{{ $application->id }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $application->customer_name }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $application->passport_number }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $application->visaType->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $application->travelAgent->company_name ?? 'Direct' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ optional($application->travel_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $application->status }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('visa-office.applications.show', $application) }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 text-white px-3 py-2 text-xs font-semibold hover:bg-blue-700 transition">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        @endif
    </section>
@endsection
