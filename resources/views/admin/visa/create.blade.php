@extends('admin.layouts.app')

@section('title', 'Create Visa Application')
@section('page-heading', 'Create Visa Application')
@section('page-description', 'Register a new pilgrim visa application and upload documents.')

@section('content')
<section class="max-w-4xl mx-auto space-y-6">
    
    <header class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex items-center justify-between">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block mb-1">New Entry</span>
            <h2 class="text-xl font-bold text-slate-900">Add Visa Application</h2>
        </div>
        <a href="{{ route('admin.visa-management') }}" class="inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 px-4 py-2.5 transition hover:bg-slate-50 shadow-xs">
            Back to Dashboard
        </a>
    </header>

    <form action="{{ route('admin.visa-applications.store') }}" method="POST" enctype="multipart/form-data" class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs border border-slate-200 bg-white space-y-6">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Customer Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Full Name as on Passport" class="w-full rounded-xl premium-input px-4 py-3 text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nationality <span class="text-rose-500">*</span></label>
                <input type="text" name="nationality" value="{{ old('nationality', 'Pakistani') }}" placeholder="e.g. Pakistani" class="w-full rounded-xl premium-input px-4 py-3 text-sm" required />
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Passport Number <span class="text-rose-500">*</span></label>
                <input type="text" name="passport_number" value="{{ old('passport_number') }}" placeholder="e.g. PB1234567" class="w-full rounded-xl premium-input px-4 py-3 text-sm" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Passport Expiry Date <span class="text-rose-500">*</span></label>
                <input type="date" name="passport_expiry" value="{{ old('passport_expiry') }}" class="w-full rounded-xl premium-input px-4 py-3 text-sm" required />
            </div>
        </div>


        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Visa Product Type <span class="text-rose-500">*</span></label>
                <select name="visa_type_id" id="visaTypeSelect" class="w-full rounded-xl premium-input px-4 py-3 text-sm font-semibold" required>
                    <option value="" disabled selected>Select Visa Option</option>
                    @foreach($visaTypes as $type)
                        <option value="{{ $type->id }}" {{ old('visa_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Booking Travel Agent</label>
                <select name="travel_agent_id" class="w-full rounded-xl premium-input px-4 py-3 text-sm font-semibold">
                    <option value="">Direct Client (No Agent)</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ old('travel_agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Assigned Visa Officer</label>
                <select name="visa_officer_id" class="w-full rounded-xl premium-input px-4 py-3 text-sm font-semibold">
                    <option value="">Unassigned</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}" {{ old('visa_officer_id') == $officer->id ? 'selected' : '' }}>
                            {{ $officer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Document Uploads Card Block -->
        <article class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block border-b border-slate-200 pb-2">Document Attachments</span>
            <div class="grid gap-4 sm:grid-cols-2 text-xs">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Passport Copy (PDF/JPG) <span class="text-rose-500">*</span></label>
                    <input type="file" name="passport_copy" class="w-full premium-input rounded-xl px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">CNIC Copy Front/Back (PDF/JPG)</label>
                    <input type="file" name="cnic_copy" class="w-full premium-input rounded-xl px-3 py-2" />
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Photograph Passport Size (JPG)</label>
                    <input type="file" name="photograph" class="w-full premium-input rounded-xl px-3 py-2" />
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Vaccination Certificate (PDF/JPG)</label>
                    <input type="file" name="vaccination_certificate" class="w-full premium-input rounded-xl px-3 py-2" />
                </div>
            </div>
        </article>

        <!-- Pricing details -->
        <article class="rounded-2xl bg-blue-50/40 border border-blue-100 p-5 flex flex-wrap justify-between items-center gap-4">
            <div>
                <span class="text-[10px] text-blue-700 font-bold uppercase tracking-widest block">Application Fee Breakdown</span>
                <p class="text-xs text-slate-500 mt-1 font-medium">Auto computed according to selected Visa Product</p>
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
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remarks & Administrative Notes</label>
            <textarea name="remarks" rows="3" placeholder="Enter specific instructions or requirements..." class="w-full rounded-xl premium-input px-4 py-3 text-sm">{{ old('remarks') }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <button type="reset" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-6 py-3 text-sm font-semibold text-slate-650 transition">
                Reset Form
            </button>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-750 text-white font-extrabold text-sm px-6 py-3 shadow-md transition">
                Register & Submit
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
        updatePricing(); // Trigger initially if old inputs value exist
    });
</script>
@endsection
