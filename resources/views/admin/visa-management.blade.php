@extends('admin.layouts.app')

@section('title', 'Visa Management')
@section('page-heading', 'Visa Management')
@section('page-description', 'Enterprise Visa tracking, application logging, and document processing module.')

@section('content')
<section class="space-y-6">
    
    <!-- Top Action Headers -->
    <header class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-xs">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="max-w-3xl">
                <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Administrative Dashboard</p>
                <h1 class="mt-2 text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 leading-none">Visa Management Panel</h1>
                <p class="mt-3 text-sm text-slate-500 font-medium leading-relaxed">Centralized control center for issuing pilgrims visas, verifying uploads (Passport, CNIC, Photo), tracking ministerial approval pipelines, and auditing agent requests.</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('admin.visa-types.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-3 transition hover:bg-slate-50 shadow-xs">
                    Configure Visa Pricing
                </a>
                <a href="{{ route('admin.visa-reports') }}" class="inline-flex items-center justify-center rounded-2xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-3 transition hover:bg-slate-50 shadow-xs">
                    Visa Analytics Reports
                </a>
                <a href="{{ route('admin.visa-applications.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 text-white hover:bg-blue-700 text-xs font-bold px-5 py-3 shadow-md transition">
                    Create Visa Application
                </a>
            </div>
        </div>

        <!-- Dashboard KPI stats cards grid -->
        <div class="mt-8 grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-2xl bg-slate-900 p-5 text-white shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Total Applications</p>
                <p class="mt-3 text-3xl font-black">{{ number_format($metrics['total']) }}</p>
                <p class="mt-1 text-[10px] text-slate-400 font-semibold">Logged records</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Pending Processing</p>
                <p class="mt-3 text-3xl font-black text-amber-600">{{ number_format($metrics['pending']) }}</p>
                <p class="mt-1 text-[10px] text-slate-500 font-semibold">Under Review</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Approved</p>
                <p class="mt-3 text-3xl font-black text-emerald-600">{{ number_format($metrics['approved']) }}</p>
                <p class="mt-1 text-[10px] text-slate-500 font-semibold">Ready for issuance</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Rejected</p>
                <p class="mt-3 text-3xl font-black text-rose-600">{{ number_format($metrics['rejected']) }}</p>
                <p class="mt-1 text-[10px] text-slate-500 font-semibold">Dismissed cases</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5 shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Issued</p>
                <p class="mt-3 text-3xl font-black text-teal-600">{{ number_format($metrics['issued']) }}</p>
                <p class="mt-1 text-[10px] text-slate-500 font-semibold">Passport visa stamped</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-blue-50/40 p-5 shadow-xs">
                <p class="text-[10px] uppercase tracking-wider text-blue-700 font-bold">Today's Applications</p>
                <p class="mt-3 text-3xl font-black text-blue-800">{{ number_format($metrics['today']) }}</p>
                <p class="mt-1 text-[10px] text-blue-600 font-semibold">New submissions</p>
            </div>
        </div>
    </header>

    <!-- Filters Panel Section -->
    <section class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs border border-slate-200 bg-white">
        <form action="{{ route('admin.visa-management') }}" method="GET" class="grid gap-4 md:grid-cols-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Customer Name</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Zeeshan" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white" />
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Passport Number</label>
                <input type="text" name="passport" value="{{ request('passport') }}" placeholder="e.g. PB12345" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white" />
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
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status State</label>
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

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Travel Agent</label>
                <select name="travel_agent_id" class="w-full rounded-xl premium-input px-3.5 py-2.5 text-xs text-slate-900 border border-slate-200 bg-white font-semibold">
                    <option value="">All Agents / Direct</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('travel_agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full rounded-xl bg-blue-600 text-white hover:bg-blue-700 text-xs font-bold py-3 transition text-center shadow-xs">
                    Apply Filter
                </button>
            </div>
        </form>
    </section>

    <!-- Visa Applications Table Card -->
    <article class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <h2 class="text-xl font-bold text-slate-900">Visa Applications Records</h2>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600 font-semibold">{{ count($applications) }} entries loaded</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="pb-3 pl-3">Application ID</th>
                        <th class="pb-3">Applicant #</th>
                        <th class="pb-3">Applicant</th>
                        <th class="pb-3">Agent</th>
                        <th class="pb-3">Visa Type</th>
                        <th class="pb-3">Passport No</th>
                        <th class="pb-3">Expiry</th>
                        <th class="pb-3">Nationality</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 pr-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($applications as $row)
                        @php
                            $isApplicantRow = $row instanceof \App\Models\VisaApplicant;
                            $application = $isApplicantRow ? $row->application : $row;
                            $displayApplicantNumber = $isApplicantRow ? $row->applicant_number : (optional($application->applicants->sortBy('applicant_number')->first())->applicant_number ?? '1');
                            $displayApplicantName = $isApplicantRow ? $row->full_name : (optional($application->applicants->sortBy('applicant_number')->first())->full_name ?? $application->customer_name);
                            $displayPassportNumber = $isApplicantRow ? $row->passport_number : ($application->passport_number ?? 'N/A');
                            $displayPassportExpiry = $isApplicantRow ? $row->passport_expiry_date : $application->passport_expiry;
                            $displayNationality = $isApplicantRow ? $row->nationality : ($application->nationality ?? 'N/A');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 pl-3 font-bold text-slate-900">#{{ $application?->id ?? 'N/A' }}</td>
                            <td class="py-3.5 text-slate-800">{{ $displayApplicantNumber }}</td>
                            <td class="py-3.5 text-slate-800">{{ $displayApplicantName }}</td>
                            <td class="py-3.5 text-slate-600">{{ $application?->travelAgent?->company_name ?? 'Direct Customer' }}</td>
                            <td class="py-3.5 text-slate-700">{{ $application?->visaType?->name ?? 'N/A' }}</td>
                            <td class="py-3.5 font-mono text-slate-800">{{ $displayPassportNumber }}</td>
                            <td class="py-3.5 text-slate-600">{{ optional($displayPassportExpiry)->format('d M Y') ?? 'N/A' }}</td>
                            <td class="py-3.5 text-slate-600">{{ $displayNationality }}</td>
                            <td class="py-3.5">
                                @php
                                    $statusClasses = [
                                        'Draft' => 'bg-slate-100 text-slate-700',
                                        'Pending' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                        'Submitted' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                        'Under Review' => 'bg-indigo-50 text-indigo-700 border border-indigo-100',
                                        'Documents Required' => 'bg-purple-50 text-purple-700 border border-purple-100',
                                        'Approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                        'Rejected' => 'bg-rose-50 text-rose-700 border border-rose-100',
                                        'Issued' => 'bg-teal-50 text-teal-700 border border-teal-100',
                                        'Cancelled' => 'bg-zinc-100 text-zinc-600',
                                    ];
                                    $applicationStatus = $application?->status ?? 'Unknown';
                                    $class = $statusClasses[$applicationStatus] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $class }}">{{ $applicationStatus }}</span>
                            </td>
                            <td class="py-3.5 pr-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.visa-applications.show', $application) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-blue-600 transition" title="View details">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </a>
                                    <a href="{{ route('admin.visa-applications.edit', $application) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-amber-600 transition" title="Edit details">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 20.089a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </a>
                                    <a href="{{ route('admin.visa-applications.print', $application) }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-emerald-600 transition" title="Print document summary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 01-2.247 2.118H8.587A2.25 2.25 0 016.34 18m11.32-4.171C19.78 13.662 21 11.97 21 10c0-2.21-1.79-4-4-4H7c-2.21 0-4 1.79-4 4 0 1.97 1.22 3.662 3.12 3.829m13.12 0a1.5 1.5 0 001.072-1.076c.214-.645.29-1.323.23-2.002H3.018c-.06.68.016 1.357.23 2.002m14.417-6H4.343M18 10h.008v.008H18V10zm-3 0h.008v.008H15V10z" /></svg>
                                    </a>

                                    <form action="{{ route('admin.visa-applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this application record? All uploaded documents will be deleted too.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition" title="Delete record">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400 font-bold border-t border-dashed border-slate-200">
                                No Visa Applications matching the search query are currently found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

</section>
@endsection
