<aside class="space-y-6">
    <div class="glass-panel rounded-3xl p-6 border border-slate-200">
        <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-xl font-bold">VO</div>
            <div>
                <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Visa Officer</p>
                <h1 class="text-xl font-bold text-slate-900">{{ $agent->name ?? 'Officer' }}</h1>
            </div>
        </div>
        <div class="mt-4 space-y-2 text-sm text-slate-600">
            <div>Employee ID: <span class="font-semibold text-slate-900">{{ $agent->employee_id ?? 'N/A' }}</span></div>
            <div>Designation: <span class="font-semibold text-slate-900">{{ $agent->designation ?? 'Visa Officer' }}</span></div>
            <div>Email: <span class="font-semibold text-slate-900">{{ $agent->email ?? 'N/A' }}</span></div>
        </div>
    </div>

    <nav class="glass-panel rounded-3xl p-6 border border-slate-200 space-y-2">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => 'visa-office.dashboard'],
                ['label' => 'Assigned Applications', 'route' => 'visa-office.assigned'],
                ['label' => 'Document Verification', 'route' => 'visa-office.document.queue'],
                ['label' => 'Issued Visas', 'route' => 'visa-office.issued'],
                ['label' => 'Rejected Applications', 'route' => 'visa-office.rejected'],
                ['label' => 'Notifications', 'route' => 'visa-office.notifications'],
                ['label' => 'Reports', 'route' => 'visa-office.reports'],
                ['label' => 'Profile', 'route' => 'visa-office.profile'],
            ];
            $currentRoute = Route::currentRouteName();
        @endphp

        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="block rounded-2xl px-4 py-3 text-sm font-semibold transition duration-200 {{ $currentRoute === $item['route'] ? 'text-white bg-blue-600' : 'text-slate-700 bg-slate-50 hover:bg-slate-100' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        <form action="{{ route('logout') }}" method="POST" class="mt-4 pt-4 border-t border-slate-100">
            @csrf
            <button type="submit" class="w-full rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 transition">Logout</button>
        </form>
    </nav>
</aside>
