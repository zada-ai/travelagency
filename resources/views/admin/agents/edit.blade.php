<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agent | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">
            <h2 class="text-2xl font-semibold mb-6">Admin Menu</h2>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.agent-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Agent Management</a>
                <a href="{{ route('admin.user-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">User Management</a>
                <a href="{{ route('admin.customer-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Customer Management</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-4xl mx-auto rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Edit Agent Profile</h1>
                        <p class="text-slate-400 mt-2">Update details, status, or documents for this travel agent.</p>
                    </div>
                    <a href="{{ route('admin.agents.show', $agent) }}" class="rounded-2xl bg-slate-800 px-4 py-2 text-sm text-slate-100 hover:bg-slate-700">Back to Details</a>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 p-4 text-sm text-rose-200">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.agents.update', $agent) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="first_name">First Name</label>
                            <input id="first_name" name="first_name" value="{{ old('first_name', $agent->first_name) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="last_name">Last Name</label>
                            <input id="last_name" name="last_name" value="{{ old('last_name', $agent->last_name) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="company_name">Company Name</label>
                            <input id="company_name" name="company_name" value="{{ old('company_name', $agent->company_name) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="email">Email Address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $agent->email) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="mobile">Mobile</label>
                            <input id="mobile" name="mobile" value="{{ old('mobile', $agent->mobile) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="country">Country</label>
                            <input id="country" name="country" value="{{ old('country', $agent->country) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="city">City</label>
                            <input id="city" name="city" value="{{ old('city', $agent->city) }}" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="company_address">Address</label>
                            <textarea id="company_address" name="company_address" rows="3" required
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30">{{ old('company_address', $agent->company_address) }}</textarea>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="password">New Password</label>
                            <input id="password" name="password" type="password"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" placeholder="Leave blank to keep current password" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="company_logo">Company Logo</label>
                            <input id="company_logo" name="company_logo" type="file" accept="image/*"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="dts_license">DTS License</label>
                            <input id="dts_license" name="dts_license" type="file" accept="image/*,.pdf"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="cnic_front">CNIC Front</label>
                            <input id="cnic_front" name="cnic_front" type="file" accept="image/*,.pdf"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2" for="cnic_back">CNIC Back</label>
                            <input id="cnic_back" name="cnic_back" type="file" accept="image/*,.pdf"
                                class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2" for="remarks">Admin Remarks</label>
                        <textarea id="remarks" name="remarks" rows="3" class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/30">{{ old('remarks', $agent->remarks) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('admin.agents.show', $agent) }}" class="rounded-2xl bg-slate-700 px-4 py-3 text-sm text-slate-100 hover:bg-slate-600">Cancel</a>
                        <button type="submit" class="rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Save Changes</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
