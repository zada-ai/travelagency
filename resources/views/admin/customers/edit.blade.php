@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('page-heading', 'Edit Customer')
@section('page-description', 'Update the customer profile, referral agent, or contact information for this record.')

@section('content')
    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-3xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-[28px] border border-slate-800 bg-slate-900/90 p-6 shadow-2xl shadow-slate-950/20">
            <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="customer_code" class="block text-sm font-semibold text-slate-300 mb-2">Customer Code</label>
                        <input id="customer_code" name="customer_code" type="text" value="{{ old('customer_code', $customer->customer_code) }}" required class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="first_name" class="block text-sm font-semibold text-slate-300 mb-2">First Name</label>
                        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $customer->first_name) }}" required class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-slate-300 mb-2">Last Name</label>
                        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $customer->last_name) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $customer->user?->email) }}" required class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-300 mb-2">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $customer->phone) }}" required class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-semibold text-slate-300 mb-2">WhatsApp Number</label>
                        <input id="whatsapp_number" name="whatsapp_number" type="text" value="{{ old('whatsapp_number', $customer->whatsapp_number) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-slate-300 mb-2">Gender</label>
                        <input id="gender" name="gender" type="text" value="{{ old('gender', $customer->gender) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-slate-300 mb-2">Date of Birth</label>
                        <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($customer->date_of_birth)->format('Y-m-d')) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="nationality" class="block text-sm font-semibold text-slate-300 mb-2">Nationality</label>
                        <input id="nationality" name="nationality" type="text" value="{{ old('nationality', $customer->nationality) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="passport_no" class="block text-sm font-semibold text-slate-300 mb-2">Passport Number</label>
                        <input id="passport_no" name="passport_no" type="text" value="{{ old('passport_no', $customer->passport_no) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="passport_expiry" class="block text-sm font-semibold text-slate-300 mb-2">Passport Expiry</label>
                        <input id="passport_expiry" name="passport_expiry" type="date" value="{{ old('passport_expiry', optional($customer->passport_expiry)->format('Y-m-d')) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="cnic" class="block text-sm font-semibold text-slate-300 mb-2">CNIC</label>
                        <input id="cnic" name="cnic" type="text" value="{{ old('cnic', $customer->cnic) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="country" class="block text-sm font-semibold text-slate-300 mb-2">Country</label>
                        <input id="country" name="country" type="text" value="{{ old('country', $customer->country) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-semibold text-slate-300 mb-2">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city', $customer->city) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-300 mb-2">Status</label>
                        <select id="status" name="status" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-300 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20">{{ old('address', $customer->address) }}</textarea>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-semibold text-slate-300 mb-2">Emergency Contact Name</label>
                        <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name', $customer->emergency_contact_name) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                    <div>
                        <label for="emergency_contact_number" class="block text-sm font-semibold text-slate-300 mb-2">Emergency Contact Number</label>
                        <input id="emergency_contact_number" name="emergency_contact_number" type="text" value="{{ old('emergency_contact_number', $customer->emergency_contact_number) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div>
                    <label for="relationship" class="block text-sm font-semibold text-slate-300 mb-2">Relationship</label>
                    <input id="relationship" name="relationship" type="text" value="{{ old('relationship', $customer->relationship) }}" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20" />
                </div>

                <div>
                    <label for="travel_agent_id" class="block text-sm font-semibold text-slate-300 mb-2">Agent Reference</label>
                    <select id="travel_agent_id" name="travel_agent_id" class="w-full rounded-3xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-blue-500 focus:ring-blue-500/20">
                        <option value="">Direct registration</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('travel_agent_id', $customer->travel_agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->company_name }} ({{ $agent->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="rounded-3xl bg-slate-700 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:bg-slate-600">Cancel</a>
                    <button type="submit" class="rounded-3xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
