<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Visa Officer | Umrah ERP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f4f7fc;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 8px 30px rgba(148, 163, 184, 0.08);
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <div class="grid gap-6 xl:grid-cols-[280px_1fr]">
            @include('visa_officer.layouts.sidebar', ['agent' => $agent ?? auth()->user()])
            <main class="space-y-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
