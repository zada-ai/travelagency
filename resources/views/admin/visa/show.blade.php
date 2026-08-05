@extends('admin.layouts.app')

@section('title', 'Visa Application Details')
@section('page-heading', 'Visa Application Details')
@section('page-description', 'Audit document uploads, execute approvals, and assign officers.')

@section('content')
<section class="max-w-5xl mx-auto space-y-6">
    
    <!-- Top Details Header -->
    <header class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex flex-wrap justify-between items-center gap-4 bg-white border border-slate-200">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl font-bold shadow-md shadow-blue-500/10">
                #{{ $application->id }}
            </div>
            <div>
                <span class="text-xs uppercase tracking-widest text-slate-400 font-bold">Pilgrim Record Detail</span>
                <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 leading-none mt-1">{{ $application->customer_name }}</h2>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.visa-applications.print', $application) }}" target="_blank" class="inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-2.5 transition hover:bg-slate-50 shadow-xs">
                Print Details
            </a>
            <a href="{{ route('admin.visa-applications.edit', $application) }}" class="inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-2.5 transition hover:bg-slate-50 shadow-xs">
                Edit Data
            </a>
            <a href="{{ route('admin.visa-management') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-bold text-white px-4 py-2.5 shadow-sm transition">
                Back to List
            </a>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr] items-start">
        
        <div class="space-y-6">
            <!-- Traveler Particulars Card -->
            <article class="glass-panel rounded-3xl p-6 shadow-xs bg-white border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-5">Traveler Particulars</h3>
                
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Passport Number</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block font-mono">{{ $application->passport_number }}</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Passport Expiry</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $application->passport_expiry?->format('d M Y') }}</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Nationality</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $application->nationality }}</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Booking Travel Agent</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block">{{ $application->travelAgent?->company_name ?? 'Direct Client' }}</span>
                    </div>
                </div>
            </article>

            <!-- Document Verification Grid -->
            <article class="glass-panel rounded-3xl p-6 shadow-xs bg-white border border-slate-200">
                <div class="border-b border-slate-100 pb-3 mb-5">
                    <h3 class="text-lg font-bold text-slate-900">Required Documents List</h3>
                    <p class="text-xs text-slate-500 font-medium">Verify traveler documents before processing the visa request.</p>
                </div>

                <div class="space-y-4">
                    @php
                        $docs = [
                            'passport_copy' => 'Passport Copy',
                            'cnic_copy' => 'CNIC Copy Front/Back',
                            'photograph' => 'Photograph (Passport size)',
                        ];
                    @endphp

                    @foreach ($docs as $field => $label)
                        <div class="rounded-2xl border border-slate-250 bg-slate-50/50 p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $label }}</h4>
                                    @if ($application->$field)
                                        <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">Uploaded & Ready</span>
                                    @else
                                        <span class="text-[10px] text-rose-500 font-bold block mt-0.5">Missing document file</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if ($application->$field)
                                    <a href="{{ asset('storage/' . $application->$field) }}" target="_blank" class="rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[10px] font-bold text-slate-650 px-3 py-2 transition" title="Preview file">
                                        Preview
                                    </a>
                                    <a href="{{ route('admin.visa-applications.document.download', [$application, $field]) }}" class="rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[10px] font-bold text-slate-650 px-3 py-2 transition" title="Download file">
                                        Download
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-500 font-semibold">No document uploaded</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <aside class="space-y-6">
            <!-- Status & Verification controls -->
            <article class="glass-panel rounded-3xl p-5 shadow-xs bg-white border border-slate-200 relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block">Verification Status</span>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">Application Control</h3>
                </div>

                <div class="mt-4 rounded-xl bg-slate-50 border border-slate-100 p-4 text-center">
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block">Current State</span>
                    <span class="mt-2 block text-xl font-black uppercase text-blue-600 tracking-wider">
                        {{ $application->status }}
                    </span>
                </div>

                <!-- Status quick changes forms -->
                <div class="mt-5 space-y-2.5">
                    @if(in_array($application->status, ['Submitted', 'Pending', 'Under Review', 'Documents Required']))
                        <form action="{{ route('admin.visa-applications.status.update', $application) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Approved" />
                            <button type="submit" class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3.5 shadow-sm transition">
                                Approve Visa Request
                            </button>
                        </form>
                        
                        <button type="button" onclick="document.getElementById('rejectBlock').classList.toggle('hidden')" class="w-full rounded-xl border border-rose-200 bg-rose-50 text-rose-700 font-bold text-xs py-3 transition">
                            Reject Request...
                        </button>
                        
                        <form id="rejectBlock" action="{{ route('admin.visa-applications.status.update', $application) }}" method="POST" class="hidden mt-2 p-3 bg-rose-50/50 border border-rose-200/50 rounded-xl space-y-2">
                            @csrf
                            <input type="hidden" name="status" value="Rejected" />
                            <label class="block text-[10px] font-bold text-rose-800 uppercase tracking-wider">Rejection Remarks</label>
                            <input type="text" name="remarks" placeholder="Enter reason..." class="w-full rounded-lg border border-rose-200 px-3 py-2 text-xs" required />
                            <button type="submit" class="w-full rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-[10px] py-2 transition">
                                Confirm Rejection
                            </button>
                        </form>
                    @endif

                    @if($application->status === 'Approved')
                        <form action="{{ route('admin.visa-applications.status.update', $application) }}" method="POST" enctype="multipart/form-data" class="bg-blue-50/50 border border-blue-100 p-4 rounded-xl space-y-3">
                            @csrf
                            <input type="hidden" name="status" value="Issued" />
                            <div>
                                <span class="text-[10px] text-blue-700 font-bold uppercase tracking-wider block">Visa Copy Attachment</span>
                                <p class="text-[9px] text-slate-500 font-semibold mt-0.5">Please attach issued visa to stamp state as Issued.</p>
                            </div>
                            <input type="file" name="visa_copy" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-800" required />
                            <button type="submit" class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] py-2.5 transition">
                                Issue Visa Stamp
                            </button>
                        </form>
                    @endif

                    @if($application->status !== 'Cancelled')
                        <form action="{{ route('admin.visa-applications.status.update', $application) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Cancelled" />
                            <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-650 font-bold text-xs py-3.5 transition" onsubmit="return confirm('Cancel this application?');">
                                Cancel Application
                            </button>
                        </form>
                    @endif
                </div>
            </article>

            <!-- Officer assignment -->
            <article class="glass-panel rounded-3xl p-5 shadow-xs bg-white border border-slate-200">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block">Operation Control</span>
                <h3 class="text-lg font-bold text-slate-900 mt-1">Assign Visa Officer</h3>
                
                <form action="{{ route('admin.visa-applications.assign-officer', $application) }}" method="POST" class="mt-4 flex gap-2">
                    @csrf
                    <select name="visa_officer_id" class="flex-1 rounded-xl premium-input px-3.5 py-2.5 text-xs font-semibold">
                        <option value="">Unassigned</option>
                        @foreach ($officers as $officer)
                            <option value="{{ $officer->id }}" {{ $application->visa_officer_id == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 shadow-xs transition">
                        Assign
                    </button>
                </form>
            </article>

            <!-- Pricing invoice summary -->
            <article class="glass-panel rounded-3xl p-5 shadow-xs bg-white border border-slate-200">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block">Invoice</span>
                <h3 class="text-lg font-bold text-slate-900 mt-1">Pricing Summary</h3>

                <div class="mt-4 space-y-2.5 text-xs text-slate-650">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-100 p-3">
                        <span>Visa Type</span>
                        <span class="font-bold text-slate-800">{{ $application->visaType?->name }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-100 p-3">
                        <span>Base Visa Fee</span>
                        <span class="font-bold text-slate-850">SAR {{ number_format($application->visa_fee, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-100 p-3">
                        <span>Service Charges</span>
                        <span class="font-bold text-slate-850">SAR {{ number_format($application->service_charges, 2) }}</span>
                    </div>
                    
                    <div class="rounded-xl bg-blue-50 border border-blue-100 p-3 flex items-center justify-between font-bold text-blue-800">
                        <span>Total Price</span>
                        <span class="text-base font-black text-blue-900">SAR {{ number_format($application->total_amount, 2) }}</span>
                    </div>
                </div>
            </article>

            <!-- Remarks summary -->
            @if ($application->remarks)
                <article class="glass-panel rounded-3xl p-5 shadow-xs bg-white border border-slate-200">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">Remarks & Log Details</span>
                    <p class="text-xs text-slate-600 leading-relaxed font-semibold">{{ $application->remarks }}</p>
                </article>
            @endif
        </aside>

    </div>
</section>
@endsection
