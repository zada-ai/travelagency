<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub-Agent Details | Agent Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f4f7fc;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 8px 30px rgba(148, 163, 184, 0.08);
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="max-w-6xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Sub-Agent Details</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">{{ $subAgent->company_name ?? $subAgent->first_name }}</h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">Review the sub-agent's profile and documents.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('travel-agents.sub-agents.index') }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Back to Management</a>
                <a href="{{ route('travel-agents.sub-agents.edit', $subAgent) }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">Edit Sub-Agent</a>
            </div>
        </div>

        <section class="glass-panel rounded-[2rem] p-6 shadow-xs">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-sm uppercase tracking-[0.24em] text-slate-400">Contact</h2>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $subAgent->first_name ?? '' }} {{ $subAgent->last_name ?? '' }}</p>
                        <p class="text-sm text-slate-600">{{ $subAgent->email }}</p>
                        <p class="text-sm text-slate-600">{{ $subAgent->mobile }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm uppercase tracking-[0.24em] text-slate-400">Company</h2>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $subAgent->company_name }}</p>
                        <p class="text-sm text-slate-600">{{ $subAgent->company_address }}</p>
                        <p class="text-sm text-slate-600">{{ $subAgent->city }}, {{ $subAgent->country }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <h2 class="text-sm uppercase tracking-[0.24em] text-slate-400">Status</h2>
                        <p class="mt-2 text-lg font-semibold text-slate-900 uppercase">{{ $subAgent->status }}</p>
                        <p class="mt-2 text-sm text-slate-500">Created on {{ optional($subAgent->created_at)->format('M d, Y') }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <h2 class="text-sm uppercase tracking-[0.24em] text-slate-400">Notes</h2>
                        <p class="mt-2 text-sm text-slate-600">{{ $subAgent->remarks ?? 'No remarks provided.' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl bg-white border border-slate-200 p-5">
                    <h3 class="text-sm uppercase tracking-[0.24em] text-slate-400">Company Logo</h3>
                    <div class="mt-4 h-52 overflow-hidden rounded-3xl bg-slate-100">
                        @if($subAgent->company_logo)
                            <img src="{{ asset('storage/' . $subAgent->company_logo) }}" alt="Company Logo" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-slate-400">No logo available</div>
                        @endif
                    </div>
                </div>
                <div class="rounded-3xl bg-white border border-slate-200 p-5">
                    <h3 class="text-sm uppercase tracking-[0.24em] text-slate-400">DTS License</h3>
                    <div class="mt-4 rounded-3xl bg-slate-100 p-4 text-sm text-slate-600">
                        @if($subAgent->dts_license)
                            <a href="{{ asset('storage/' . $subAgent->dts_license) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View license document</a>
                        @else
                            <span>No license uploaded</span>
                        @endif
                    </div>
                </div>
                <div class="rounded-3xl bg-white border border-slate-200 p-5">
                    <h3 class="text-sm uppercase tracking-[0.24em] text-slate-400">CNIC Files</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        @if($subAgent->cnic_front)
                            <a href="{{ asset('storage/' . $subAgent->cnic_front) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View CNIC Front</a>
                        @endif
                        @if($subAgent->cnic_back)
                            <a href="{{ asset('storage/' . $subAgent->cnic_back) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View CNIC Back</a>
                        @endif
                        @if(!$subAgent->cnic_front && !$subAgent->cnic_back)
                            <span>No CNIC files uploaded</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
