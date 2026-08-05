<div>
    <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Application #{{ $application->id }}</h2>
                <p class="mt-2 text-sm text-slate-500">Submitted {{ $application->created_at->format('d M Y, H:i') }}</p>
            </div>
            <a href="{{ route('customer.visa.index') }}" class="inline-flex items-center rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">Back to Applications</a>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 mb-6">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-lg font-semibold text-slate-900">Application Details</h3>
                <dl class="mt-4 space-y-3 text-sm text-slate-600">
                    <div><dt class="font-semibold text-slate-800">Total Persons</dt><dd>{{ $application->total_persons }}</dd></div>
                    <div><dt class="font-semibold text-slate-800">Adults</dt><dd>{{ $application->adults }}</dd></div>
                    <div><dt class="font-semibold text-slate-800">Children</dt><dd>{{ $application->children }}</dd></div>
                    <div><dt class="font-semibold text-slate-800">Infants</dt><dd>{{ $application->infants }}</dd></div>
                    <div><dt class="font-semibold text-slate-800">Visa Type</dt><dd>{{ $application->visa_type ?? 'N/A' }}</dd></div>
                    <div><dt class="font-semibold text-slate-800">Status</dt><dd>{{ ucfirst(str_replace('_',' ', $application->status)) }}</dd></div>
                </dl>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-lg font-semibold text-slate-900">History & Remarks</h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $application->remarks ?? 'No remarks available yet. Your application is being processed by our visa team.' }}</p>
            </div>
        </div>

        <div class="space-y-5">
            <h3 class="text-xl font-semibold text-slate-900">Applicants</h3>
            @foreach($application->applicants as $applicant)
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">{{ $applicant->applicant_number }}. {{ $applicant->full_name }}</h4>
                            <p class="text-sm text-slate-500">Nationality: {{ $applicant->nationality ?? 'N/A' }} · DOB: {{ optional($applicant->date_of_birth)->format('d M Y') ?? 'N/A' }}</p>
                        </div>
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Applicant {{ $applicant->applicant_number }}</span>
                    </div>

               <div class="mt-5 grid gap-4 lg:grid-cols-2">
    <div class="space-y-2 text-sm text-slate-600">
        <p>
            <strong class="text-slate-800">Passport Number</strong>:
            {{ $applicant->passport_number ?? 'N/A' }}
        </p>

        <p>
            <strong class="text-slate-800">Passport Expiry</strong>:
            {{ optional($applicant->passport_expiry_date)->format('d M Y') ?? 'N/A' }}
        </p>
    </div>

    <div class="space-y-2 text-sm text-slate-600">
        <p>
            <strong class="text-slate-800">Mobile</strong>:
            {{ $applicant->mobile_number ?? 'N/A' }}
        </p>

        <p>
            <strong class="text-slate-800">Email</strong>:
            {{ $applicant->email ?? 'N/A' }}
        </p>

        <p>
            <strong class="text-slate-800">Address</strong>:
            {{ $applicant->address ?? 'N/A' }}
        </p>
    </div>
</div>

                    <div class="mt-5">
                        <h5 class="text-sm font-semibold text-slate-900">Uploaded Documents</h5>
                        <div class="mt-3 flex flex-wrap gap-3">
                          @foreach(['passport_scan','photo','cnic'] as $field)
    @if($applicant->{$field})
        <a href="{{ asset('storage/' . $applicant->{$field}) }}" target="_blank"
           class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">

            @switch($field)
                @case('passport_scan')
                    Passport Scan
                    @break

                @case('photo')
                    Photo
                    @break

                @case('cnic')
                    CNIC
                    @break
            @endswitch

        </a>
    @endif
@endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
