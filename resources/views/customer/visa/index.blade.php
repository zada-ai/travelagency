<div>
    <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">My Visa Applications</h2>
                <p class="mt-2 text-sm text-slate-500">Review your visa submissions, statuses, and assigned officer details.</p>
            </div>
            <a href="{{ route('customer.visa.create') }}" class="inline-flex items-center rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">Apply Visa</a>
        </div>

        @if($applicants->isEmpty())
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600">
                No visa applications yet. Start a new application to track your visa status.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-slate-500">
                        <tr>
                            <th class="py-3 pr-6">Application ID</th>
                            <th class="py-3 pr-6">Applicant #</th>
                            <th class="py-3 pr-6">Full Name</th>
                            <th class="py-3 pr-6">Passport No</th>
                            <th class="py-3 pr-6">Nationality</th>
                            <th class="py-3 pr-6">Status</th>
                            <th class="py-3 pr-6">Sales Officer</th>
                            <th class="py-3 pr-6">Updated</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicants as $app)
                            @php
                                $application = $app->application;
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'under_review' => 'bg-sky-100 text-sky-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'issued' => 'bg-indigo-100 text-indigo-700',
                                ];
                                $applicationStatus = $application?->status ?? 'pending';
                                $badgeClass = $statusClasses[$applicationStatus] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <tr class="border-b border-slate-200">
                                <td class="py-4 pr-6">#{{ $application?->id ?? 'N/A' }}</td>
                                <td class="py-4 pr-6">{{ $app->applicant_number }}</td>
                                <td class="py-4 pr-6">{{ $app->full_name }}</td>
                                <td class="py-4 pr-6">{{ $app->passport_number ?? 'N/A' }}</td>
                                <td class="py-4 pr-6">{{ $app->nationality ?? 'N/A' }}</td>
                                <td class="py-4 pr-6">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ', $applicationStatus)) }}</span>
                                </td>
                                <td class="py-4 pr-6">{{ $application?->assignedSalesOfficer?->name ?? 'Unassigned' }}</td>
                                <td class="py-4 pr-6">{{ optional($application?->updated_at)->diffForHumans() ?? 'N/A' }}</td>
                                <td class="py-4">
                                    <a href="{{ route('customer.visa.show', $application?->id ?? 0) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
