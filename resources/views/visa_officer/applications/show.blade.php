@extends('visa_officer.layouts.app')

@section('title', "Application #{$application->id} | Visa Officer")

@section('content')
    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Application #{{ $application->id }}</h1>
                <p class="mt-2 text-sm text-slate-500">Review applicant details, documents, and perform status actions.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('visa-office.visa-management') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">Back to List</a>
                <a href="{{ route('visa-office.applications.print', $application) }}" target="_blank" class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700 transition">Print</a>
            </div>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
        <div class="space-y-6">
            <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Applicant Information</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Customer Name</div>
                        <div class="mt-2 font-bold text-slate-900">{{ $application->customer_name }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Passport Number</div>
                        <div class="mt-2 font-bold text-slate-900">{{ $application->passport_number }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Travel Date</div>
                        <div class="mt-2 font-bold text-slate-900">{{ optional($application->travel_date)->format('M d, Y') }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Visa Type</div>
                        <div class="mt-2 font-bold text-slate-900">{{ $application->visaType->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </section>

            <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Document Checklist</h2>
                        <p class="text-sm text-slate-500">Verify uploads before approving or issuing.</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @php
                        $docs = [
                            'passport_copy' => 'Passport Copy',
                            'cnic_copy' => 'CNIC Copy',
                            'photograph' => 'Photograph',
                            'vaccination_certificate' => 'Vaccination Certificate',
                            'visa_copy' => 'Issued Visa Copy',
                        ];
                    @endphp
                    @foreach($docs as $field => $label)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-900">{{ $label }}</div>
                                <div class="text-xs text-slate-500 mt-1">@if($application->$field) Uploaded @else Missing @endif</div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if($application->$field)
                                    <a href="{{ asset('storage/' . $application->$field) }}" target="_blank" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">View</a>
                                    <a href="{{ route('visa-office.applications.document.download', [$application, $field]) }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">Download</a>
                                @else
                                    <span class="rounded-xl bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700">No file</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Status Actions</h2>
                <div class="space-y-3">
                    @if(in_array($application->status, ['Submitted', 'Pending', 'Under Review', 'Documents Required']))
                        <form action="{{ route('visa-office.applications.status.update', $application) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-xs font-bold text-white hover:bg-emerald-700 transition">Approve</button>
                        </form>

                        <form action="{{ route('visa-office.applications.status.update', $application) }}" method="POST" class="space-y-2 p-4 rounded-2xl border border-rose-200 bg-rose-50">
                            @csrf
                            <input type="hidden" name="status" value="Rejected">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Reject reason</label>
                            <input type="text" name="remarks" placeholder="Enter remarks" class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2 text-sm text-slate-700" required>
                            <button type="submit" class="w-full rounded-2xl bg-rose-600 px-4 py-3 text-xs font-bold text-white hover:bg-rose-700 transition">Reject</button>
                        </form>
                    @endif

                    @if($application->status === 'Approved')
                        <form action="{{ route('visa-office.applications.status.update', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-3 rounded-2xl border border-blue-200 bg-blue-50 p-4">
                            @csrf
                            <input type="hidden" name="status" value="Issued">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Upload Visa Copy</label>
                            <input type="file" name="visa_copy" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700" required>
                            <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-xs font-bold text-white hover:bg-blue-700 transition">Issue Visa</button>
                        </form>
                    @endif

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Current Status</div>
                        <div class="mt-2 text-lg font-bold text-slate-900">{{ $application->status }}</div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
@endsection
