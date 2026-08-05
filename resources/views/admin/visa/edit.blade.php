@extends('admin.layouts.app')

@section('title', 'Edit Visa Application')
@section('page-heading', 'Edit Visa Application')
@section('page-description', 'Modify passport details, assigned officers, or edit administrative parameters.')

@section('content')
<section class="max-w-4xl mx-auto space-y-6">
    
    <header class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block mb-1">Edit Record</span>
            <h2 class="text-xl font-bold text-slate-900">Modify Application #{{ $application->id }}</h2>
        </div>
        <a href="{{ route('admin.visa-applications.show', $application) }}" class="inline-flex items-center justify-center rounded-xl text-black bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-2.5 transition hover:bg-slate-50 shadow-xs">
            Back to Details
        </a>
    </header>

    <form action="{{ route('admin.visa-applications.update', $application) }}" method="POST" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs border border-slate-200 bg-white space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Customer Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $firstApplicant?->full_name ?? $application->customer_name) }}" placeholder="Full Name as on Passport" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Nationality <span class="text-rose-500">*</span></label>
                <input type="text" name="nationality" value="{{ old('nationality', $firstApplicant?->nationality ?? $application->nationality) }}" placeholder="e.g. Pakistani" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm" required />
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Passport Number <span class="text-rose-500">*</span></label>
                <input type="text" name="passport_number" value="{{ old('passport_number', $firstApplicant?->passport_number ?? $application->passport_number) }}" placeholder="e.g. PB1234567" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Passport Expiry Date <span class="text-rose-500">*</span></label>
                <input type="date" name="passport_expiry" value="{{ old('passport_expiry', $firstApplicant?->passport_expiry_date?->format('Y-m-d') ?? $application->passport_expiry?->format('Y-m-d')) }}" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm" required />
            </div>
        </div>


        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Visa Product Type <span class="text-rose-500">*</span></label>
                <select name="visa_type_id" id="visaTypeSelect" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm font-semibold" required>
                    @foreach($visaTypes as $type)
                        <option value="{{ $type->id }}" {{ old('visa_type_id', $application->visa_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Booking Travel Agent</label>
                <select name="travel_agent_id" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm font-semibold">
                    <option value="">Direct Client (No Agent)</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ old('travel_agent_id', $application->travel_agent_id) == $agent->id ? 'selected' : '' }}>
                            {{ $agent->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Assigned Visa Officer</label>
                <select name="visa_officer_id" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm font-semibold">
                    <option value="">Unassigned</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}" {{ old('visa_officer_id', $application->visa_officer_id) == $officer->id ? 'selected' : '' }}>
                            {{ $officer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Application Status State <span class="text-rose-500">*</span></label>
                <select name="status" class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm font-semibold" required>
                    <option value="Draft" {{ old('status', $application->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Pending" {{ old('status', $application->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Submitted" {{ old('status', $application->status) === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Under Review" {{ old('status', $application->status) === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                    <option value="Documents Required" {{ old('status', $application->status) === 'Documents Required' ? 'selected' : '' }}>Documents Required</option>
                    <option value="Approved" {{ old('status', $application->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ old('status', $application->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Issued" {{ old('status', $application->status) === 'Issued' ? 'selected' : '' }}>Issued</option>
                    <option value="Cancelled" {{ old('status', $application->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Pricing details -->
        <article class="rounded-2xl bg-blue-50/40 border border-blue-100 p-5 flex flex-wrap justify-between items-center gap-4">
            <div>
                <span class="text-[10px] text-blue-700 font-bold uppercase tracking-widest block">Application Fee Breakdown</span>
                <p class="text-xs text-black mt-1 font-medium">Auto computed according to selected Visa Product</p>
            </div>
            <div class="flex gap-6 text-right">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Visa Fee</span>
                    <span id="labelVisaFee" class="text-sm font-bold text-slate-800">SAR 0.00</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Service Charge</span>
                    <span id="labelServiceCharge" class="text-sm font-bold text-slate-800">SAR 0.00</span>
                </div>
                <div>
                    <span class="text-[10px] text-blue-600 font-bold uppercase block">Total Amount</span>
                    <span id="labelTotalAmount" class="text-xl font-black text-blue-900">SAR 0.00</span>
                </div>
            </div>
        </article>

        <div>
            <label class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Remarks & Administrative Notes</label>
            <textarea name="remarks" rows="3" placeholder="Enter specific instructions or requirements..." class="w-full rounded-xl text-black premium-input px-4 py-3 text-sm">{{ old('remarks', $application->remarks) }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.visa-applications.show', $application) }}" class="rounded-xl text-black border border-slate-200 bg-white hover:bg-slate-50 px-6 py-3 text-sm font-semibold text-slate-650 transition text-center">
                Cancel
            </a>
            <button type="submit" class="rounded-xl text-black bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-750 text-white font-extrabold text-sm px-6 py-3 shadow-md transition">
                Save Modifications
            </button>
        </div>
    </form>
</section>

<!-- Pricing scripts logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visaPricing = {
            @foreach($visaTypes as $type)
                '{{ $type->id }}': { fee: {{ $type->base_fee }}, charge: {{ $type->service_charge }} },
            @endforeach
        };

        const visaTypeSelect = document.getElementById('visaTypeSelect');
        const labelVisaFee = document.getElementById('labelVisaFee');
        const labelServiceCharge = document.getElementById('labelServiceCharge');
        const labelTotalAmount = document.getElementById('labelTotalAmount');

        function formatCurrency(val) {
            return 'SAR ' + Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function updatePricing() {
            const selectedVal = visaTypeSelect.value;
            if (selectedVal && visaPricing[selectedVal]) {
                const fee = visaPricing[selectedVal].fee;
                const charge = visaPricing[selectedVal].charge;
                const total = fee + charge;

                labelVisaFee.textContent = formatCurrency(fee);
                labelServiceCharge.textContent = formatCurrency(charge);
                labelTotalAmount.textContent = formatCurrency(total);
            } else {
                labelVisaFee.textContent = 'SAR 0.00';
                labelServiceCharge.textContent = 'SAR 0.00';
                labelTotalAmount.textContent = 'SAR 0.00';
            }
        }

        visaTypeSelect.addEventListener('change', updatePricing);
        updatePricing(); // Trigger initially to load saved fee values
    });
</script>
@endsection
