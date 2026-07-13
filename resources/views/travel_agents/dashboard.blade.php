<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold">Agent Portal</h2>
                    <p class="text-slate-400 mt-2 text-sm">{{ $agent->company_name }}</p>
                </div>
                <nav class="space-y-2 text-sm">
                    <a href="#overview" class="block rounded-2xl px-4 py-3 bg-slate-800 text-slate-100 hover:bg-slate-700">Overview</a>
                    <a href="{{ route('travel-agents.hotels.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Hotels</a>
                    <a href="#profile" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Profile</a>
                    <a href="#documents" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Documents</a>
                    <a href="#status" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Approval Status</a>
                    <form action="{{ route('travel-agents.logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Logout</button>
                    </form>
                </nav>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                <section id="overview" class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-semibold">Welcome back, {{ $agent->first_name }}</h1>
                            <p class="text-slate-400 mt-2">Your agency is currently <span class="font-semibold text-slate-100">{{ $agent->status }}</span>.</p>
                        </div>
                        <div class="rounded-3xl bg-emerald-500/10 px-4 py-3 text-emerald-300">Member since {{ $agent->created_at->format('M d, Y') }}</div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <p class="text-sm text-slate-400">Email</p>
                            <p class="mt-2 font-semibold text-slate-100">{{ $agent->email }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <p class="text-sm text-slate-400">Mobile</p>
                            <p class="mt-2 font-semibold text-slate-100">{{ $agent->mobile }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <p class="text-sm text-slate-400">Location</p>
                            <p class="mt-2 font-semibold text-slate-100">{{ $agent->city }}, {{ $agent->country }}</p>
                        </div>
                    </div>
                </section>

                <section id="profile" class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <h2 class="text-2xl font-semibold">Company Profile</h2>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h3 class="text-sm uppercase tracking-[0.2em] text-slate-500">Company Name</h3>
                            <p class="mt-3 text-slate-100 font-semibold">{{ $agent->company_name }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h3 class="text-sm uppercase tracking-[0.2em] text-slate-500">Address</h3>
                            <p class="mt-3 text-slate-100 font-semibold">{{ $agent->company_address }}</p>
                        </div>
                    </div>
                </section>

                <section id="documents" class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-2xl font-semibold">Uploaded Documents</h2>
                        <span class="text-sm text-slate-400">Only approved files are shown here</span>
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-3xl bg-slate-800 p-5">
                            <p class="text-sm text-slate-400">Company Logo</p>
                            <a href="{{ asset('storage/'.$agent->company_logo) }}" target="_blank" class="mt-3 block text-emerald-400 hover:text-emerald-300 text-sm">View file</a>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-5">
                            <p class="text-sm text-slate-400">DTS License</p>
                            <a href="{{ asset('storage/'.$agent->dts_license) }}" target="_blank" class="mt-3 block text-emerald-400 hover:text-emerald-300 text-sm">View file</a>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-5">
                            <p class="text-sm text-slate-400">CNIC Front/Back</p>
                            <a href="{{ asset('storage/'.$agent->cnic_front) }}" target="_blank" class="mt-3 block text-emerald-400 hover:text-emerald-300 text-sm">Front</a>
                            <a href="{{ asset('storage/'.$agent->cnic_back) }}" target="_blank" class="mt-1 block text-emerald-400 hover:text-emerald-300 text-sm">Back</a>
                        </div>
                    </div>
                </section>

                <section id="status" class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <h2 class="text-2xl font-semibold">Approval Status</h2>
                    <div class="mt-6 rounded-3xl bg-slate-800 p-6">
                        <p class="text-sm text-slate-400">Current status</p>
                        <p class="mt-3 text-3xl font-semibold {{ $agent->status === 'Approved' ? 'text-emerald-400' : ($agent->status === 'Rejected' ? 'text-rose-400' : 'text-amber-400') }}">{{ $agent->status }}</p>
                        @if ($agent->remarks)
                            <p class="mt-4 text-slate-300">Remarks: {{ $agent->remarks }}</p>
                        @endif
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
