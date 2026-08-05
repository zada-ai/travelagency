@extends('admin.layouts.app')

@section('title', 'Customer Details')
@section('page-heading', 'Customer Details')
@section('page-description', 'Review the customer profile and referral information for this record.')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('success') }}</div>
        @endif

        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Customer Details</p>
                    <h2 class="mt-2 text-3xl font-semibold text-white">{{ $customer->customer_code }}</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.customer-management') }}" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-slate-800 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-700">Back to customers</a>
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Edit</a>
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this customer record?');" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-400">Delete</button>
                    </form>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-6">
                    <h3 class="text-xl font-semibold text-white">Primary Information</h3>
                    <dl class="mt-4 space-y-4 text-slate-300">
                        <div><dt class="font-medium text-slate-400">Customer Code</dt><dd>{{ $customer->customer_code }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Name</dt><dd>{{ $customer->first_name }} {{ $customer->last_name }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Email</dt><dd>{{ $customer->user?->email ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Phone</dt><dd>{{ $customer->phone }}</dd></div>
                        <div><dt class="font-medium text-slate-400">WhatsApp</dt><dd>{{ $customer->whatsapp_number ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Status</dt><dd class="inline-flex rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-200">{{ ucfirst($customer->status) }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-6">
                    <h3 class="text-xl font-semibold text-white">Referral & Travel Agent</h3>
                    <dl class="mt-4 space-y-4 text-slate-300">
                        <div><dt class="font-medium text-slate-400">Agent Reference</dt><dd>{{ $customer->travelAgent?->email ?? 'Direct registration' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Agent Name</dt><dd>{{ $customer->travelAgent?->company_name ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Registered On</dt><dd>{{ $customer->created_at->format('d M Y') }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-6">
                    <h3 class="text-xl font-semibold text-white">Travel Documents</h3>
                    <dl class="mt-4 space-y-4 text-slate-300">
                        <div><dt class="font-medium text-slate-400">CNIC</dt><dd>{{ $customer->cnic ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Passport No</dt><dd>{{ $customer->passport_no ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Passport Expiry</dt><dd>{{ optional($customer->passport_expiry)->format('d M Y') ?? 'N/A' }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-[28px] border border-slate-800 bg-slate-950/90 p-6">
                    <h3 class="text-xl font-semibold text-white">Personal Details</h3>
                    <dl class="mt-4 space-y-4 text-slate-300">
                        <div><dt class="font-medium text-slate-400">Date of Birth</dt><dd>{{ optional($customer->date_of_birth)->format('d M Y') ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Gender</dt><dd>{{ $customer->gender ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Nationality</dt><dd>{{ $customer->nationality ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">Address</dt><dd>{{ $customer->address ?? 'N/A' }}</dd></div>
                        <div><dt class="font-medium text-slate-400">City / Country</dt><dd>{{ $customer->city ?? 'N/A' }} / {{ $customer->country ?? 'N/A' }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="mt-8 rounded-[28px] border border-slate-800 bg-slate-950/90 p-6">
                <h3 class="text-xl font-semibold text-white">Emergency Contact</h3>
                <dl class="mt-4 space-y-4 text-slate-300">
                    <div><dt class="font-medium text-slate-400">Contact Name</dt><dd>{{ $customer->emergency_contact_name ?? 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-400">Relationship</dt><dd>{{ $customer->relationship ?? 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-400">Contact Number</dt><dd>{{ $customer->emergency_contact_number ?? 'N/A' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
@endsection
