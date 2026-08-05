<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sub-Agent | Agent Portal</title>
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
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.55rem;
        }
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border-width: 1px;
            border-color: #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #3b82f6;
        }
        .file-input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            background: #ffffff;
        }
        .file-input-btn {
            background-color: #f8fafc;
            color: #334155;
            padding: 0.95rem 1rem;
            font-size: 0.92rem;
            border-right: 1px solid #e2e8f0;
            font-weight: 600;
            cursor: pointer;
        }
        .file-input-name {
            padding: 0.95rem 1rem;
            color: #64748b;
            font-size: 0.92rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-blue-600 font-bold">Edit Sub-Agent</p>
                <h1 class="mt-2 text-3xl font-extrabold text-slate-900">Update sub-agent profile</h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">Edit the details and documents for the selected sub-agent.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('travel-agents.sub-agents.index') }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Back to Management</a>
                <a href="{{ route('travel-agents.sub-agents.show', $subAgent) }}" class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">View Sub-Agent</a>
            </div>
        </div>

        <section class="glass-panel rounded-[2rem] p-6 shadow-xs">
            @if ($errors->any())
                <div class="mb-6 rounded-3xl border border-red-100 bg-red-50 p-5 text-sm text-red-700">
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-3 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 lg:grid-cols-3">
                    <label class="block">
                        <span class="form-label">First Name</span>
                        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $subAgent->first_name) }}" required class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Last Name</span>
                        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $subAgent->last_name) }}" required class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Company Name</span>
                        <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $subAgent->company_name) }}" required class="form-input" />
                    </label>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <label class="block">
                        <span class="form-label">Email Address</span>
                        <input id="email" name="email" type="email" value="{{ old('email', $subAgent->email) }}" required class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Mobile Number</span>
                        <input id="mobile" name="mobile" type="text" value="{{ old('mobile', $subAgent->mobile) }}" required class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Company Address</span>
                        <input id="company_address" name="company_address" type="text" value="{{ old('company_address', $subAgent->company_address) }}" required class="form-input" />
                    </label>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <label class="block">
                        <span class="form-label">Password</span>
                        <input id="password" name="password" type="password" placeholder="Leave blank to keep current password" class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Confirm Password</span>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password" class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Country</span>
                        <select id="country" name="country" required class="form-select">
                            <option value="" disabled {{ old('country', $subAgent->country) ? '' : 'selected' }}>Select Country</option>
                            @foreach(['Pakistan','Saudi Arabia','United Arab Emirates','United Kingdom','United States','Other'] as $country)
                                <option value="{{ $country }}" {{ old('country', $subAgent->country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <label class="block">
                        <span class="form-label">City</span>
                        <input id="city" name="city" type="text" value="{{ old('city', $subAgent->city) }}" required class="form-input" />
                    </label>
                    <label class="block">
                        <span class="form-label">Company Logo</span>
                        <div class="file-input-wrapper">
                            <label for="company_logo" class="file-input-btn">Choose File</label>
                            <span id="logo-preview" class="file-input-name">{{ $subAgent->company_logo ? basename($subAgent->company_logo) : 'No file chosen' }}</span>
                            <input id="company_logo" name="company_logo" type="file" accept="image/*" class="hidden" onchange="previewFile('company_logo', 'logo-preview')" />
                        </div>
                    </label>
                    <label class="block">
                        <span class="form-label">DTS License</span>
                        <div class="file-input-wrapper">
                            <label for="dts_license" class="file-input-btn">Choose File</label>
                            <span id="license-preview" class="file-input-name">{{ $subAgent->dts_license ? basename($subAgent->dts_license) : 'No file chosen' }}</span>
                            <input id="dts_license" name="dts_license" type="file" accept="image/*,.pdf" class="hidden" onchange="previewFile('dts_license', 'license-preview')" />
                        </div>
                    </label>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <label class="block">
                        <span class="form-label">Owner CNIC Front</span>
                        <div class="file-input-wrapper">
                            <label for="cnic_front" class="file-input-btn">Choose File</label>
                            <span id="cnic-front-preview" class="file-input-name">{{ $subAgent->cnic_front ? basename($subAgent->cnic_front) : 'No file chosen' }}</span>
                            <input id="cnic_front" name="cnic_front" type="file" accept="image/*,.pdf" class="hidden" onchange="previewFile('cnic_front', 'cnic-front-preview')" />
                        </div>
                    </label>
                    <label class="block">
                        <span class="form-label">Owner CNIC Back</span>
                        <div class="file-input-wrapper">
                            <label for="cnic_back" class="file-input-btn">Choose File</label>
                            <span id="cnic-back-preview" class="file-input-name">{{ $subAgent->cnic_back ? basename($subAgent->cnic_back) : 'No file chosen' }}</span>
                            <input id="cnic_back" name="cnic_back" type="file" accept="image/*,.pdf" class="hidden" onchange="previewFile('cnic_back', 'cnic-back-preview')" />
                        </div>
                    </label>
                    <label class="block">
                        <span class="form-label">Remarks</span>
                        <textarea id="remarks" name="remarks" rows="4" class="form-textarea">{{ old('remarks', $subAgent->remarks) }}</textarea>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 hover:bg-blue-700 transition">Update Sub-Agent</button>
                </div>
            </form>
        </section>
    </div>

    <script>
        function previewFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(previewId);
            if (!input || !label) return;

            const fileName = input.files.length > 0 ? input.files[0].name : 'No file chosen';
            label.textContent = fileName;
        }
    </script>
</body>
</html>
