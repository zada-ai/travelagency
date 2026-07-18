<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Booking | Travel Agent Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-80 bg-slate-900 border-r border-slate-800 p-6">
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold">Group Booking</h2>
                    <p class="text-slate-400 mt-2 text-sm">{{ $agent->company_name }}</p>
                </div>

                <div class="rounded-3xl bg-slate-800 p-5 border border-slate-700 shadow-sm">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400">Create Booking</h3>
                    <p class="mt-3 text-slate-300 text-sm">Start a new group reservation with your preferred package.</p>
                    <a href="#package-list" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">View Packages</a>
                </div>

                <div class="rounded-3xl bg-slate-800 p-5 border border-slate-700 shadow-sm">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400">Recent Tickets</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($recentTickets as $ticket)
                            <div class="rounded-3xl bg-slate-900 p-4 border border-slate-700">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.25em] text-slate-500">{{ $ticket['reference'] }}</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-100">{{ $ticket['client_name'] }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $ticket['status'] === 'Approved' ? 'bg-emerald-500/15 text-emerald-300' : ($ticket['status'] === 'Pending' ? 'bg-amber-500/15 text-amber-300' : 'bg-sky-500/15 text-sky-300') }}">{{ $ticket['status'] }}</span>
                                </div>
                                <div class="mt-3 text-slate-400 text-xs space-y-1">
                                    <p>{{ $ticket['trip_date'] }} · {{ $ticket['group_size'] }}</p>
                                    <p class="font-semibold text-slate-100">{{ $ticket['total'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-800 p-5 border border-slate-700 shadow-sm">
                    <h3 class="text-sm uppercase tracking-[0.3em] text-slate-400">Agent Info</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-300">
                        <p><span class="font-semibold text-slate-100">Name:</span> {{ $agent->first_name }} {{ $agent->last_name ?? '' }}</p>
                        <p><span class="font-semibold text-slate-100">Email:</span> {{ $agent->email }}</p>
                        <p><span class="font-semibold text-slate-100">Status:</span> {{ $agent->status }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto space-y-8">
                <section class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-amber-300">Group Package Portal</p>
                            <h1 class="mt-4 text-3xl font-semibold text-white">Choose a package and send a booking request.</h1>
                            <p class="mt-3 text-slate-400 max-w-2xl">Use the sidebar to review active tickets and the list below to choose the right group package for your clients.</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('travel-agents.dashboard') }}" class="rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-100 hover:bg-slate-700">Back to Dashboard</a>
                            <a href="{{ route('travel-agents.hotels.index', ['package' => 'group']) }}" class="rounded-2xl bg-amber-500 px-4 py-3 text-sm font-semibold text-slate-950 hover:bg-amber-400">Group Hotel Search</a>
                        </div>
                    </div>
                </section>

                <section id="package-list" class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold">Available Group Packages</h2>
                            <p class="text-slate-400 mt-2">Select a package and then create your booking request.</p>
                        </div>
                        <span class="rounded-full bg-slate-800 px-4 py-2 text-sm text-slate-300">{{ count($groupPackages) }} packages</span>
                    </div>

                    <div class="mt-8 space-y-6">
                        @foreach ($groupPackages as $package)
                            <article class="rounded-3xl bg-slate-950 border border-slate-800 p-6 transition hover:-translate-y-1 hover:border-amber-500/30">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-amber-300">{{ $package['duration'] }} · {{ $package['group_size'] }}</p>
                                        <h3 class="mt-3 text-2xl font-semibold text-white">{{ $package['title'] }}</h3>
                                        <p class="mt-2 text-slate-400">{{ $package['subtitle'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Starting at</p>
                                        <p class="mt-2 text-3xl font-semibold text-emerald-300">{{ $package['price'] }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $package['availability'] }} groups available</p>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                                    @foreach ($package['highlights'] as $highlight)
                                        <span class="rounded-2xl bg-slate-900 px-3 py-2 text-sm text-slate-300">{{ $highlight }}</span>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="text-sm text-slate-400">Group size: {{ $package['group_size'] }}</div>
                                    <a href="#" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Book This Package</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl bg-slate-900 border border-slate-800 p-8 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold">Booking Summary</h2>
                            <p class="text-slate-400 mt-2">Your selected package details and request status.</p>
                        </div>
                        <span class="rounded-full bg-slate-800 px-4 py-2 text-sm text-slate-300">Latest activity</span>
                    </div>

                    <div class="mt-8 grid gap-6 lg:grid-cols-3">
                        <div class="rounded-3xl bg-slate-950 p-6 border border-slate-800">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Selected package</p>
                            <h3 class="mt-3 text-xl font-semibold text-white">{{ $groupPackages[0]['title'] }}</h3>
                            <p class="mt-3 text-slate-400">{{ $groupPackages[0]['subtitle'] }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-6 border border-slate-800">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Payment timeline</p>
                            <p class="mt-3 text-slate-400">Booking deposit due within 48 hours. Final payment collected after confirmation.</p>
                        </div>
                        <div class="rounded-3xl bg-slate-950 p-6 border border-slate-800">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Next action</p>
                            <p class="mt-3 text-slate-400">Send request to operations to reserve rooms and transport.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
