<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Details | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6">
            <h2 class="text-2xl font-semibold mb-6">Admin Menu</h2>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.agent-management') }}" class="block rounded-2xl px-4 py-3 bg-emerald-500/10 text-emerald-300">Agent Management</a>
                <a href="{{ route('admin.user-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">User Management</a>
                <a href="{{ route('admin.customer-management') }}" class="block rounded-2xl px-4 py-3 hover:bg-slate-800">Customer Management</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-5xl mx-auto space-y-6">
                @if(session('success'))
                    <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-900">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="rounded-3xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-900">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-semibold">{{ $agent->company_name }}</h1>
                            <p class="text-slate-400 mt-2">{{ $agent->first_name }} {{ $agent->last_name }} — {{ $agent->email }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.agents.edit', $agent) }}" class="rounded-2xl bg-slate-800 px-4 py-2 text-sm text-slate-100 hover:bg-slate-700">Edit</a>
                            @if($agent->status !== 'Approved')
                                <form action="{{ route('admin.agents.approve', $agent) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm text-slate-950 hover:bg-emerald-400">Approve</button>
                                </form>
                            @endif
                            @if($agent->status !== 'Rejected')
                                <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden');" class="rounded-2xl bg-rose-500 px-4 py-2 text-sm text-slate-950 hover:bg-rose-400">Reject</button>
                            @endif
                            <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-2xl bg-slate-700 px-4 py-2 text-sm text-slate-100 hover:bg-slate-600">Delete</button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h2 class="text-lg font-semibold text-white">Owner Details</h2>
                            <dl class="mt-4 space-y-3 text-slate-300">
                                <div><dt class="font-medium">Full Name</dt><dd>{{ $agent->first_name }} {{ $agent->last_name }}</dd></div>
                                <div><dt class="font-medium">Email</dt><dd>{{ $agent->email }}</dd></div>
                                <div><dt class="font-medium">Mobile</dt><dd>{{ $agent->mobile }}</dd></div>
                                <div><dt class="font-medium">Status</dt><dd>{{ $agent->status }}</dd></div>
                            </dl>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h2 class="text-lg font-semibold text-white">Company Details</h2>
                            <dl class="mt-4 space-y-3 text-slate-300">
                                <div><dt class="font-medium">Address</dt><dd>{{ $agent->company_address }}</dd></div>
                                <div><dt class="font-medium">City</dt><dd>{{ $agent->city }}</dd></div>
                                <div><dt class="font-medium">Country</dt><dd>{{ $agent->country }}</dd></div>
                                <div><dt class="font-medium">Registered On</dt><dd>{{ $agent->created_at->format('d M Y') }}</dd></div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h2 class="text-lg font-semibold text-white">Uploaded Files</h2>
                            <div class="mt-4 space-y-2 text-slate-300">
                                <a href="{{ asset('storage/'.$agent->company_logo) }}" target="_blank" class="block text-emerald-300 hover:text-emerald-200">Company Logo</a>
                                <a href="{{ asset('storage/'.$agent->dts_license) }}" target="_blank" class="block text-emerald-300 hover:text-emerald-200">DTS License</a>
                                <a href="{{ asset('storage/'.$agent->cnic_front) }}" target="_blank" class="block text-emerald-300 hover:text-emerald-200">Owner CNIC</a>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-slate-800 p-6">
                            <h2 class="text-lg font-semibold text-white">Review Notes</h2>
                            <div class="mt-4 text-slate-300">
                                @if ($agent->remarks)
                                    <p>{{ $agent->remarks }}</p>
                                @else
                                    <p class="text-slate-500">No remarks yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/90 p-4">
        <div class="w-full max-w-xl rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold">Reject Agent</h2>
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden');" class="text-slate-400 hover:text-slate-200">Close</button>
            </div>
            <form action="{{ route('admin.agents.reject', $agent) }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="remarks" class="block text-sm font-semibold text-slate-300 mb-2">Rejection Remarks</label>
                    <textarea id="remarks" name="remarks" rows="5" required class="w-full rounded-2xl border border-slate-800 bg-slate-950 px-4 py-3 text-slate-100 focus:border-rose-500 focus:ring-rose-500/20"></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden');" class="rounded-2xl bg-slate-700 px-4 py-2 text-sm text-slate-100 hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-2xl bg-rose-500 px-4 py-2 text-sm text-slate-950 hover:bg-rose-400">Reject</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
