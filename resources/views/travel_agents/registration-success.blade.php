<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted | Umrah ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-10 text-center shadow-2xl">
            <h1 class="text-3xl font-semibold text-white mb-4">Application Received</h1>
            <p class="text-slate-300 mb-6">Your travel agent registration has been submitted successfully. Our team will review your application and notify you by email once it is approved.</p>
            <a href="{{ route('travel-agents.login') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Go to Agent Login</a>
        </div>
    </div>
</body>
</html>
