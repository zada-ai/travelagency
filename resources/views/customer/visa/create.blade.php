<div>
    <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-xs">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Apply Visa</h2>
                <p class="mt-2 text-sm text-slate-500">Complete the application in two steps and upload documents for each applicant.</p>
            </div>
            <a href="{{ route('customer.visa.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">Back to Applications</a>
        </div>

        @if($errors->any())
            <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="visaForm" action="{{ route('customer.visa.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            <div class="grid gap-4 md:grid-cols-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Total Persons</label>
                    <input type="number" name="total_persons" id="total_persons" value="{{ old('total_persons', 1) }}" min="1" required class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Adults</label>
                    <input type="number" name="adults" id="adults" value="{{ old('adults', 1) }}" min="0" required class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Children</label>
                    <input type="number" name="children" id="children" value="{{ old('children', 0) }}" min="0" required class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Infants</label>
                    <input type="number" name="infants" id="infants" value="{{ old('infants', 0) }}" min="0" required class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Visa Type (optional)</label>
                <input type="text" name="visa_type" value="{{ old('visa_type') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none" />
            </div>

            <div id="validationMessage" class="hidden rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <div class="flex flex-wrap gap-3">
                <button type="button" id="generateApplicants" class="inline-flex items-center justify-center rounded-3xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">Continue to Applicant Details</button>
                <button type="button" id="resetApplicants" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition hidden">Edit Counts</button>
            </div>

            <div id="stepContainer" class="space-y-6 hidden">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Step 2 of 2: Complete details and document uploads for each applicant below.</p>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div><strong>Total:</strong> <span id="summaryTotal">0</span></div>
                        <div><strong>Adults:</strong> <span id="summaryAdults">0</span></div>
                        <div><strong>Children:</strong> <span id="summaryChildren">0</span></div>
                        <div><strong>Infants:</strong> <span id="summaryInfants">0</span></div>
                    </div>
                </div>

                <div id="applicantsContainer" class="space-y-6"></div>
            </div>

            <div class="mt-6">
                <button type="submit" id="submitButton" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition hidden">Submit Application</button>
            </div>
        </form>
    </div>
</div>

<script>
    const oldApplicants = @json(old('applicants', []));

    function validateCounts() {
        const total = parseInt(document.getElementById('total_persons').value) || 0;
        const adults = parseInt(document.getElementById('adults').value) || 0;
        const children = parseInt(document.getElementById('children').value) || 0;
        const infants = parseInt(document.getElementById('infants').value) || 0;
        return (adults + children + infants) === total;
    }

    function showError(message) {
        const validation = document.getElementById('validationMessage');
        validation.textContent = message;
        validation.classList.remove('hidden');
        validation.classList.add('block');
    }

    function hideError() {
        const validation = document.getElementById('validationMessage');
        validation.textContent = '';
        validation.classList.add('hidden');
        validation.classList.remove('block');
    }

    function createApplicantCard(index, data = {}) {
        const num = index + 1;
        const prefills = {
            full_name: data.full_name || '',
            father_name: data.father_name || '',
            gender: data.gender || '',
            date_of_birth: data.date_of_birth || '',
            nationality: data.nationality || '',
            passport_number: data.passport_number || '',
            passport_issue_date: data.passport_issue_date || '',
            passport_expiry_date: data.passport_expiry_date || '',
            cnic_number: data.cnic_number || '',
            mobile_number: data.mobile_number || '',
            email: data.email || '',
            address: data.address || '',
        };

        const card = document.createElement('div');
        card.className = 'rounded-3xl border border-slate-200 bg-white p-5 shadow-sm';
        card.innerHTML = `
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Applicant #${num}</h3>
                    <p class="text-sm text-slate-500">Fill in the applicant details and upload required documents.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">Applicant ${num}</span>
            </div>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                <div class="grid gap-4">
                    <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="applicants[${index}][full_name]" value="${prefills.full_name}" required class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                    <label class="block text-sm font-semibold text-slate-700">Father Name</label>
                    <input type="text" name="applicants[${index}][father_name]" value="${prefills.father_name}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                    <label class="block text-sm font-semibold text-slate-700">Gender</label>
                    <select name="applicants[${index}][gender]" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none">
                        <option value="">Select gender</option>
                        <option value="male" ${prefills.gender === 'male' ? 'selected' : ''}>Male</option>
                        <option value="female" ${prefills.gender === 'female' ? 'selected' : ''}>Female</option>
                        <option value="other" ${prefills.gender === 'other' ? 'selected' : ''}>Other</option>
                    </select>
                    <label class="block text-sm font-semibold text-slate-700">Date of Birth</label>
                    <input type="date" name="applicants[${index}][date_of_birth]" value="${prefills.date_of_birth}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="grid gap-4">
                    <label class="block text-sm font-semibold text-slate-700">Nationality</label>
                    <input type="text" name="applicants[${index}][nationality]" value="${prefills.nationality}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                    <label class="block text-sm font-semibold text-slate-700">Passport Number</label>
                    <input type="text" name="applicants[${index}][passport_number]" value="${prefills.passport_number}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                   
                    <label class="block text-sm font-semibold text-slate-700">Passport Expiry Date</label>
                    <input type="date" name="applicants[${index}][passport_expiry_date]" value="${prefills.passport_expiry_date}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                </div>
            </div>

            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                <div class="space-y-4">
                    
                     <label class="block text-sm font-semibold text-slate-700">Mobile Number</label>
                    <input type="text" name="applicants[${index}][mobile_number]" value="${prefills.mobile_number}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                    <label class="block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="applicants[${index}][email]" value="${prefills.email}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-slate-700">Address</label>
                    <textarea name="applicants[${index}][address]" rows="5" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none">${prefills.address}</textarea>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                ${['passport_scan','photo','cnic'].map(field => {
                    const label = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    return `
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">${label}</label>
                            <input type="file" name="applicants[${index}][${field}]" accept="image/*,application/pdf" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none file:rounded-full file:border-none file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:text-blue-700" data-preview-target="${field}_preview_${index}" />
                            <div id="${field}_preview_${index}" class="mt-2"></div>
                        </div>
                    `;
                }).join('')}
            </div>
        `;

        return card;
    }

    function attachFilePreviews(container) {
        container.querySelectorAll('input[type=file]').forEach(function(input) {
            input.addEventListener('change', function() {
                const target = document.getElementById(this.dataset.previewTarget);
                if (!target) return;
                target.innerHTML = '';
                const file = this.files[0];
                if (!file) return;
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'h-28 w-full rounded-3xl object-contain';
                    target.appendChild(img);
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700';
                    badge.textContent = file.name;
                    target.appendChild(badge);
                }
            });
        });
    }

    function renderApplicants(applicants) {
        const total = parseInt(document.getElementById('total_persons').value) || 0;
        const adults = parseInt(document.getElementById('adults').value) || 0;
        const children = parseInt(document.getElementById('children').value) || 0;
        const infants = parseInt(document.getElementById('infants').value) || 0;

        document.getElementById('summaryTotal').textContent = total;
        document.getElementById('summaryAdults').textContent = adults;
        document.getElementById('summaryChildren').textContent = children;
        document.getElementById('summaryInfants').textContent = infants;

        const container = document.getElementById('applicantsContainer');
        container.innerHTML = '';

        for (let i = 0; i < total; i++) {
            const applicantData = applicants[i] || {};
            const card = createApplicantCard(i, applicantData);
            container.appendChild(card);
        }

        attachFilePreviews(container);
        document.getElementById('stepContainer').classList.remove('hidden');
        document.getElementById('submitButton').classList.remove('hidden');
        document.getElementById('resetApplicants').classList.remove('hidden');
    }

    document.getElementById('generateApplicants').addEventListener('click', function() {
        if (!validateCounts()) {
            showError('Adults + Children + Infants must equal Total Persons');
            return;
        }

        hideError();
        renderApplicants(oldApplicants);
    });

    document.getElementById('resetApplicants').addEventListener('click', function() {
        document.getElementById('stepContainer').classList.add('hidden');
        document.getElementById('submitButton').classList.add('hidden');
        this.classList.add('hidden');
    });

    if (oldApplicants.length > 0) {
        renderApplicants(oldApplicants);
    }
</script>
