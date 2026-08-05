<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Umrah ERP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f4f7fc;
            color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        @yield('content')
    </div>
</body>
</html>
