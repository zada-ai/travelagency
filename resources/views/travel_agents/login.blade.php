<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | UMRAH BOOKING</title>
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
        .form-input::placeholder {
            color: #94a3b8;
        }
        .form-input:focus {
            border-color: #3b82f6;
            ring: 2px solid #3b82f6;
        }
        .form-label {
            display: block;
            font-size: 0.815rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }
        
        .checkbox-custom {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            accent-color: #3b82f6;
            cursor: pointer;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-center">
    
    <!-- Main Grid Container - Centered Vertically -->
    <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-5 px-4 py-8 lg:py-12 items-start">
        
        <!-- Left Section: Welcome Text -->
        <div class="lg:col-span-6 flex flex-col justify-center text-white p-6 lg:p-12 relative lg:pt-20">
            <!-- Background Subtle Blur Accent Effect -->
            <div class="absolute -top-50 -left-10 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-xl relative z-60 space-y-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400/20 via-yellow-400/10 to-transparent border border-amber-400/30 text-amber-200 font-bold px-4 py-1.5 rounded-full text-xs uppercase tracking-[0.2em] shadow-sm backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Welcome Back
                    </span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-3xl font-extrabold font-display tracking-tight leading-[1.05] text-white py-10">
                    Sign in to <br>
                    <span class="bg-gradient-to-r from-amber-300 via-amber-200 to-yellow-400 bg-clip-text text-transparent drop-shadow-[0_2px_10px_rgba(245,158,11,0.2)]">
                        Umrah Booking Agency
                    </span>
                </h1>

                <p class="text-slate-300 text-sm sm:text-base lg:text-lg font-normal leading-relaxed max-w-lg border-l-2 border-amber-400/45 pl-4">
                    Access your dashboard to manage bookings, view packages, and handle your travel agency operations.
                </p>

                <!-- Quick Stats -->
                <div class="flex gap-6 pt-4">
                    <div>
                        <p class="text-2xl font-bold text-white">500+</p>
                        <p class="text-xs text-slate-400">Happy Clients</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">1.2K+</p>
                        <p class="text-xs text-slate-400">Bookings Made</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">4.9</p>
                        <p class="text-xs text-slate-400">Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Login Form Container -->
        <div class="lg:col-span-5 lg:col-start-8 flex justify-center w-full">
            <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl p-6 sm:p-8 border border-slate-100">
                
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-slate-900 font-display">
                        Welcome Back
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Sign in to your agency account</p>
                </div>

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

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-xs text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('travel-agents.login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 ">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="Enter your email" class="form-input" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="form-label mb-0">Password</label>
                            <a href="{{ route('travel-agents.password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                Forgot Password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input id="password" name="password" type="password" required placeholder="Enter your password" class="form-input pl-10 pr-10" />
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Terms -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="checkbox-custom" />
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded transition duration-150 shadow-md text-sm">
                        Sign In
                    </button>

                    <!-- Divider -->
                    <!-- <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-2 text-slate-400">Or continue with</span>
                        </div>
                    </div> -->

                    <!-- Social Login Buttons -->
                    <!-- <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-lg hover:bg-slate-50 transition text-sm font-medium text-slate-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="#1877F2">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </button>
                        <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-lg hover:bg-slate-50 transition text-sm font-medium text-slate-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Google
                        </button>
                    </div> -->

                    <!-- Register Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-slate-600">
                            Don't have an account? 
                            <a href="{{ route('travel-agents.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Register here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>