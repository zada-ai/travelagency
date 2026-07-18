<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Account | UMRAH BOOKING</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.55)), 
                        url('https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?auto=format&fit=crop&w=1920&q=80'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .font-display { font-family: 'Manrope', sans-serif; }

        .form-input, .form-select {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: #ffffff;
            color: #0f172a;
            outline: none;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .form-input::placeholder {
            color: #94a3b8;
        }
        .form-input:focus, .form-select:focus {
            border-color: #3b82f6;
        }
        .form-label {
            display: block;
            font-size: 0.815rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }
        
        .file-input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            overflow: hidden;
            background: #ffffff;
        }
        .file-input-btn {
            background-color: #f1f5f9;
            color: #334155;
            padding: 0.65rem 0.85rem;
            font-size: 0.815rem;
            border-right: 1px solid #e2e8f0;
            font-weight: 500;
            cursor: pointer;
        }
        .file-input-name {
            padding: 0.65rem 0.85rem;
            color: #64748b;
            font-size: 0.815rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-center">
    
    <!-- Main Grid Container - Centered Vertically -->
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-5 px-4 py-8 lg:py-12 items-center">
        
        <!-- Left Section: Welcome Text -->
        <div class="lg:col-span-4 flex flex-col justify-center text-white p-6 relative">
            <!-- Background Subtle Blur Accent Effect -->
            <div class="absolute -top-50 -left-10 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-xl relative z-60 space-y-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400/20 via-yellow-400/10 to-transparent border border-amber-400/30 text-amber-200 font-bold px-4 py-1.5 rounded-full text-xs uppercase tracking-[0.2em] shadow-sm backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Find Next Destination
                    </span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-4xl font-extrabold font-display tracking-tight leading-[1.1] text-white">
                    Welcome to <br>
                    <span class="bg-gradient-to-r from-amber-300 via-amber-200 to-yellow-400 bg-clip-text text-transparent drop-shadow-[0_2px_10px_rgba(245,158,11,0.2)]">
                        Umrah Booking Agency
                    </span>
                </h1>

                <p class="text-slate-300 text-sm font-normal leading-relaxed max-w-lg border-l-2 border-amber-400/45 pl-4">
                    Best place to find your next destination either be it flight, hotel, car and much more...
                </p>
            </div>
        </div>

        <!-- Right Section: Complete Form Container -->
        <div class="lg:col-span-8 flex justify-center w-full">
            <div class="w-full max-w-4xl bg-white rounded-xl shadow-2xl p-6 sm:p-8 border border-slate-100">
                
                <h2 class="text-2xl font-extrabold text-slate-900 font-display mb-6 text-center">
                    Signup Account
                </h2>

                <!-- Laravel Session Errors -->
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-600">
                        <ul class="list-inside list-disc space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('travel-agents.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Row 1: First Name, Last Name & Company Name -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required placeholder="First Name" class="form-input" />
                        </div>
                        <div>
                            <label for="last_name" class="form-label">Last Name</label>
                            <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required placeholder="Last Name" class="form-input" />
                        </div>
                        <div>
                            <label for="company_name" class="form-label">Company Name</label>
                            <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required placeholder="Company Name" class="form-input" />
                        </div>
                    </div>

                    <!-- Row 2: Email, Mobile & Company Address -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="Enter Email Address" class="form-input" />
                        </div>
                        <div>
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" required placeholder="Enter Mobile Number" class="form-input" />
                        </div>
                        <div>
                            <label for="company_address" class="form-label">Company Address</label>
                            <input id="company_address" name="company_address" type="text" value="{{ old('company_address') }}" required placeholder="Enter Company Address" class="form-input" />
                        </div>
                    </div>

                    <!-- Row 3: Passwords & Country -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="relative">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password" required placeholder="••••••••" class="form-input" />
                            <button type="button" onclick="togglePassword()" class="absolute right-3 bottom-2.5 text-slate-400 hover:text-slate-600 text-xs">👁</button>
                        </div>
                        <div>
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••" class="form-input" />
                        </div>
                        <div>
                            <label for="country" class="form-label">Country</label>
                            <select id="country" name="country" required class="form-select text-xs sm:text-sm">
                                <option value="" disabled selected>Select Country</option>
                                <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                <option value="Saudi Arabia" {{ old('country') == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                <option value="United Arab Emirates" {{ old('country') == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                                <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: City & Password Strength Block (Keeping it aligned nicely) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                        <div>
                            <label for="city" class="form-label">City</label>
                            <input id="city" name="city" type="text" value="{{ old('city') }}" required placeholder="Select City" class="form-input" />
                        </div>
                        <div class="md:col-span-2 space-y-1 pb-3">
                            <div class="flex items-center justify-between text-[10px] text-slate-500">
                                <span>Password strength: <span id="strength-text" class="font-semibold text-slate-700">Weak</span></span>
                            </div>
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 5: Company Logo, DTS License & Single Owner CNIC Upload -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">Company Logo</label>
                            <div class="file-input-wrapper">
                                <label for="company_logo" class="file-input-btn">Choose File</label>
                                <span id="logo-preview" class="file-input-name">No file chosen</span>
                                <input id="company_logo" name="company_logo" type="file" accept="image/*" required class="hidden" onchange="previewFile('company_logo', 'logo-preview')" />
                            </div>
                        </div>
                        <div>
                            <label class="form-label">DTS License</label>
                            <div class="file-input-wrapper">
                                <label for="dts_license" class="file-input-btn">Choose File</label>
                                <span id="license-preview" class="file-input-name">No file chosen</span>
                                <input id="dts_license" name="dts_license" type="file" accept="image/*,.pdf" required class="hidden" onchange="previewFile('dts_license', 'license-preview')" />
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Owner CNIC</label>
                            <div class="file-input-wrapper">
                                <label for="cnic_front" class="file-input-btn">Choose File</label>
                                <span id="cnic-front-preview" class="file-input-name">No file chosen</span>
                                <!-- Note: Input id and name are kept as 'cnic_front' so your backend controller remains unchanged -->
                                <input id="cnic_front" name="cnic_front" type="file" accept="image/*,.pdf" required class="hidden" onchange="previewFile('cnic_front', 'cnic-front-preview')" />
                            </div>
                        </div>
                    </div>

                    <!-- Terms checkbox -->
                    <div class="flex items-center gap-2 py-1">
                        <input type="checkbox" id="terms" name="terms" value="1" required class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                        <label for="terms" class="text-xs text-slate-600">I agree to the terms and conditions.</label>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3 pt-3 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold p-3.5 rounded transition duration-150 shadow-md text-sm">
                            Submit Application
                        </button>
                        <div class="text-center">
                            <a href="{{ route('travel-agents.login') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                Already registered? Login
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.addEventListener('input', function () {
                const value = this.value;
                const strengthText = document.getElementById('strength-text');
                const strengthBar = document.getElementById('strength-bar');
                let score = 0;
                if (value.length > 7) score += 1;
                if (/[A-Z]/.test(value)) score += 1;
                if (/[0-9]/.test(value)) score += 1;
                if (/[^A-Za-z0-9]/.test(value)) score += 1;

                switch (score) {
                    case 0:
                    case 1:
                        strengthText.textContent = 'Weak';
                        strengthText.className = 'font-semibold text-red-500';
                        strengthBar.className = 'h-full w-1/4 bg-red-500 transition-all duration-300';
                        break;
                    case 2:
                        strengthText.textContent = 'Fair';
                        strengthText.className = 'font-semibold text-amber-500';
                        strengthBar.className = 'h-full w-2/4 bg-amber-500 transition-all duration-300';
                        break;
                    case 3:
                        strengthText.textContent = 'Good';
                        strengthText.className = 'font-semibold text-blue-500';
                        strengthBar.className = 'h-full w-3/4 bg-blue-500 transition-all duration-300';
                        break;
                    case 4:
                        strengthText.textContent = 'Strong';
                        strengthText.className = 'font-semibold text-green-500';
                        strengthBar.className = 'h-full w-full bg-green-500 transition-all duration-300';
                        break;
                }
            });
        }

        function previewFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input.files && input.files[0]) {
                preview.textContent = input.files[0].name;
                preview.style.color = '#1e293b';
            } else {
                preview.textContent = 'No file chosen';
                preview.style.color = '#64748b';
            }
        }
    </script>
</body>
</html>