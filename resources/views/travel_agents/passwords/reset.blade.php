<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | UMRAH BOOKING</title>
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
        .form-input {
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
        .form-input::placeholder { color: #94a3b8; }
        .form-input:focus { border-color: #3b82f6; ring: 2px solid #3b82f6; }
        .form-label { display: block; font-size: 0.815rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
        .checkbox-custom { width: 16px; height: 16px; border-radius: 4px; border: 1px solid #cbd5e1; accent-color: #3b82f6; cursor: pointer; }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-center">
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-5 px-4 py-8 lg:py-12 items-start">
        <div class="lg:col-span-6 flex flex-col justify-center text-white p-6 lg:p-12 relative lg:pt-20">
            <div class="absolute -top-50 -left-10 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="max-w-xl relative z-60 space-y-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400/20 via-yellow-400/10 to-transparent border border-amber-400/30 text-amber-200 font-bold px-4 py-1.5 rounded-full text-xs uppercase tracking-[0.2em] shadow-sm backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Secure Your Account
                    </span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-3xl font-extrabold font-display tracking-tight leading-[1.05] text-white py-10">
                    Reset your password
                    <span class="bg-gradient-to-r from-amber-300 via-amber-200 to-yellow-400 bg-clip-text text-transparent drop-shadow-[0_2px_10px_rgba(245,158,11,0.2)]">
                        and continue managing bookings.
                    </span>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base lg:text-lg font-normal leading-relaxed max-w-lg border-l-2 border-amber-400/45 pl-4">
                    Enter a new password for your account. Once finished, you can sign in again using your updated credentials.
                </p>
            </div>
        </div>

        <div class="lg:col-span-5 lg:col-start-8 flex justify-center w-full">
            <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl p-6 sm:p-8 border border-slate-100">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-slate-900 font-display">Set New Password</h2>
                    <p class="text-sm text-slate-500 mt-1">Complete your reset with the email and token sent to you.</p>
                </div>

                <form method="POST" action="{{ route('travel-agents.password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="form-input" />
                    </div>

                    <div>
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" name="password" type="password" required class="form-input" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input" />
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-[#003B95] px-4 py-3 text-sm font-semibold text-white hover:bg-blue-800 transition">Reset Password</button>
                </form>

                <div class="mt-6 text-center text-xs text-slate-500">
                    <a href="{{ route('travel-agents.login') }}" class="font-semibold text-[#003B95] hover:text-blue-800">Back to login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
