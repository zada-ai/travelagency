<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application Details | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f7fc; }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Visa Application #{{ $visaApplication->id }}</h1>
                <p class="mt-2 text-sm text-slate-500">Details for the selected customer visa application.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('travel-agents.customer-visa.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">Back</a>
                @if($visaApplication->status === 'Issued' && $visaApplication->visa_copy)
                    <a href="{{ route('travel-agents.customer-visa.download-visa', $visaApplication->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Download Visa Copy</a>
                @endif
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h2 class="text-sm uppercase tracking-[0.28em] text-slate-400 font-semibold">Customer</h2>
                        <p class="mt-3 text-lg font-bold text-slate-900">{{ trim(($visaApplication->customer?->first_name ?? '') . ' ' . ($visaApplication->customer?->last_name ?? '')) ?: ($visaApplication->customer?->customer_code ?? $visaApplication->customer_name ?? 'Unknown') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $visaApplication->customer?->phone ?? $visaApplication->customer?->email ?? $visaApplication->passport_number ?? 'No contact available' }}</p>
                    </div>
                    <div>
                        <h2 class="text-sm uppercase tracking-[0.28em] text-slate-400 font-semibold">Visa Type</h2>
                        <p class="mt-3 text-lg font-bold text-slate-900">{{ $visaApplication->visaType?->name ?? 'N/A' }}</p>
                        <p class="mt-1 text-sm text-slate-500">Status: {{ ucfirst($visaApplication->status) }}</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <h3 class="text-xs uppercase tracking-[0.3em] text-slate-400 font-semibold">Passport</h3>
                        <p class="mt-3 text-base font-semibold text-slate-900">{{ $visaApplication->customer?->passport_no ?? $visaApplication->passport_number ?? 'N/A' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Expiry: {{ optional($visaApplication->customer?->passport_expiry)->format('d M Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <h3 class="text-xs uppercase tracking-[0.3em] text-slate-400 font-semibold">Travel Agent</h3>
                        <p class="mt-3 text-base font-semibold text-slate-900">{{ $agent->company_name ?? 'Agent' }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $agent->email ?? '' }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 font-semibold">Application Notes</h3>
                    <div class="mt-3 rounded-3xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600">{{ $visaApplication->remarks ?? 'No remarks provided.' }}</div>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 font-semibold">Total Persons</p>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ $visaApplication->total_persons ?? '-' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 font-semibold">Created</p>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ $visaApplication->created_at?->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-200">
                <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400 font-semibold">Documents</h3>
                <div class="mt-6 space-y-4">
                    @foreach(['passport_copy' => 'Passport Copy', 'cnic_copy' => 'CNIC Copy', 'photograph' => 'Photograph', 'vaccination_certificate' => 'Vaccination Certificate'] as $field => $label)
                        <div class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $label }}</p>
                                <p class="text-xs text-slate-500">{{ $visaApplication->$field ? 'Uploaded' : 'Missing' }}</p>
                            </div>
                            @if($visaApplication->$field)
                                <a href="{{ route('travel-agents.customer-visa.download-document', [$visaApplication->id, $field]) }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">Download</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
