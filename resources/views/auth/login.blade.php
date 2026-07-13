<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Umrah ERP Portal</title>
    <!-- Tailwind CSS Vite Engine -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 antialiased selection:bg-emerald-500/30 selection:text-emerald-400">

    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl relative overflow-hidden group">
        <!-- Background Glow Effect -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 blur-3xl rounded-full transition-all group-hover:bg-emerald-500/15"></div>
        
        <!-- Header / Logo Area -->
        <div class="text-center mb-8 relative z-10">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-emerald-500/10 border border-emerald-500/30 rounded-xl mb-3 text-emerald-400 font-bold text-xl tracking-wider">
                🕋
            </div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">Welcome Back</h1>
            <p class="text-sm text-slate-400 mt-1">Sign in to your Umrah ERP account</p>
        </div>

        <!-- Fortify Error Alerts -->
        @if ($errors->any())
            <div class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm p-3 rounded-xl">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Fortify Login Form Action -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf

            <!-- Email Address Field -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-600 rounded-xl px-4 py-3 text-sm outline-none transition-all focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10"
                    placeholder="name@agency.pk">
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
                    <a href="#" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition-colors">Forgot?</a>
                </div>
                <input type="password" name="password" id="password" required
                    class="w-full bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-600 rounded-xl px-4 py-3 text-sm outline-none transition-all focus:border-emerald-500/50 focus:ring-2 focus:ring-emerald-500/10"
                    placeholder="••••••••">
            </div>

            <!-- Remember Me Choice -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" 
                    class="w-4 h-4 bg-slate-950 border-slate-800 rounded text-emerald-500 focus:ring-offset-slate-900 focus:ring-emerald-500/30">
                <label for="remember" class="ml-2 text-xs font-medium text-slate-400 select-none">Remember this device</label>
            </div>

            <!-- Submit Action -->
            <button type="submit" 
                class="w-full bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] text-slate-950 font-semibold py-3 px-4 rounded-xl text-sm tracking-wide shadow-lg shadow-emerald-500/10 transition-all duration-150">
                Sign In to Dashboard
            </button>
        </form>
    </div>

</body>
</html>