@php
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    | Customer, Agent and other users are authenticated through the default
    | web guard. Do NOT use auth()->guard('customer') because that guard
    | is not configured in config/auth.php.
    */
    $authUser = auth()->user();

    $currentUser = $authUser;

    /*
    |--------------------------------------------------------------------------
    | Detect Portal
    |--------------------------------------------------------------------------
    */
    $isCustomerRoute = request()->is('customer*');

    $isCustomer = $isCustomerRoute || ($currentUser && (
        (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Customer'))
        || in_array(
            strtolower((string) ($currentUser->role ?? $currentUser->designation ?? '')),
            ['customer'],
            true
        )
    ));

    $isVisaOfficer = !$isCustomer && ($currentUser && (
        (method_exists($currentUser, 'hasRole') && $currentUser->hasRole('Visa Officer'))
        || in_array(
            strtolower((string) ($currentUser->role ?? $currentUser->designation ?? '')),
            ['visa_officer', 'visa office', 'visa officer'],
            true
        )
    ));

    $portalLabel = $isCustomer
        ? 'Customer Portal'
        : ($isVisaOfficer ? 'Visa Portal' : 'Agent Portal');

    $portalSystemLabel = $isCustomer
        ? 'Customer Portal System'
        : ($isVisaOfficer ? 'Visa Office System' : 'Agent Portal System');

    /*
    |--------------------------------------------------------------------------
    | User Name
    |--------------------------------------------------------------------------
    */
    if ($isCustomer && $currentUser) {
        $userName = trim(
            ($currentUser->first_name ?? '') . ' ' .
            ($currentUser->last_name ?? '')
        );

        $userName = $userName ?: ($currentUser->name ?? 'Customer');
    } else {
        $userName = $currentUser->company_name
            ?? $currentUser->name
            ?? 'Guest User';
    }

    $userInitial = strtoupper(substr($userName, 0, 1));

    $currentRoute = request()->route()
        ? request()->route()->getName()
        : '';
@endphp


<!-- Mobile Header -->
<header
    class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-4 backdrop-blur-md xl:hidden sticky top-0 z-40"
>
    <div class="flex items-center gap-3">

        <div
            class="h-9 w-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="w-5 h-5 text-white"
            >
                <path
                    d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z"
                />
            </svg>
        </div>

        <span class="text-lg font-bold text-slate-800">
            {{ $portalLabel }}
        </span>
    </div>

    <button
        id="mobileMenuToggle"
        type="button"
        class="rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-slate-500 hover:text-slate-800 transition hover:bg-slate-100"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.8"
            stroke="currentColor"
            class="h-6 w-6"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
            />
        </svg>
    </button>
</header>


<!-- Mobile Overlay -->
<div
    id="sidebarOverlay"
    class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300 xl:hidden"
></div>


<!-- Sidebar -->
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-[280px] -translate-x-full border-r border-slate-200 bg-white p-6 transition-transform duration-300 xl:static xl:translate-x-0 flex flex-col justify-between shadow-sm"
>

    <div class="space-y-6">

        <!-- Brand -->
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div
                    class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="w-6 h-6 text-white"
                    >
                        <path
                            d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight">
                        Hujaj Umrah
                    </h1>

                    <p class="text-xs text-slate-500">
                        {{ $portalSystemLabel }}
                    </p>
                </div>

            </div>

            <button
                id="mobileMenuClose"
                type="button"
                class="xl:hidden p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

        </div>


        <!-- User Profile -->
        <div
            class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 shadow-inner"
        >
            <div class="flex items-center gap-3">

                <div
                    class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm uppercase shrink-0 shadow-md shadow-blue-500/20"
                >
                    {{ $userInitial }}
                </div>

                <div class="overflow-hidden">

                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                        {{ $isCustomer ? 'Customer Account' : ($isVisaOfficer ? 'Visa Officer' : 'Agency Company') }}
                    </p>

                    <p
                        class="font-bold text-slate-800 truncate text-sm mt-0.5"
                        title="{{ $userName }}"
                    >
                        {{ $userName }}
                    </p>

                </div>

            </div>
        </div>


        <!-- Navigation -->
        <nav class="space-y-1 text-sm font-semibold">

            <!-- Dashboard -->
            @php
                if ($isCustomer) {
                    $dashRoute = Route::has('customer.dashboard')
                        ? route('customer.dashboard')
                        : url('/customer/dashboard');

                    $isDashActive = $currentRoute === 'customer.dashboard'
                        || request()->is('customer/dashboard*');

                } else {
                    $dashRoute = Route::has('travel-agents.dashboard')
                        ? route('travel-agents.dashboard')
                        : url('/dashboard');

                    $isDashActive = $currentRoute === 'travel-agents.dashboard'
                        || request()->is('dashboard*');
                }
            @endphp

            <a
                href="{{ $dashRoute }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                {{ $isDashActive
                    ? 'bg-blue-50 text-blue-600 font-bold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 {{ $isDashActive ? 'text-blue-600' : 'text-slate-400' }}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                    />
                </svg>

                <span>Overview</span>
            </a>


            <!-- Search Flights -->
            @php
                $flightRoute = Route::has('tickets.index')
                    ? route('tickets.index')
                    : url('/tickets');

                $isFlightActive =
                    request()->is('*ticket*')
                    || $currentRoute === 'tickets.index';
            @endphp

            <a
                href="{{ $flightRoute }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                {{ $isFlightActive
                    ? 'bg-blue-50 text-blue-600 font-bold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 {{ $isFlightActive ? 'text-blue-600' : 'text-slate-400' }}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"
                    />
                </svg>

                <span>Search Flights</span>
            </a>


            <!-- Hotel Booking -->
            @php
                $hotelRoute = Route::has('hotels.booking')
                    ? route('hotels.booking')
                    : url('/hotels/booking');

                $isHotelActive =
                    request()->is('*hotel*')
                    || $currentRoute === 'hotels.booking';
            @endphp

            <a
                href="{{ $hotelRoute }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                {{ $isHotelActive
                    ? 'bg-blue-50 text-blue-600 font-bold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 {{ $isHotelActive ? 'text-blue-600' : 'text-slate-400' }}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6h1.5m-1.5 3h1.5m-1.5 3h1.5M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
                    />
                </svg>

                <span>Hotel Booking</span>
            </a>


            <!-- Umrah Packages -->
            @php
                /*
                |--------------------------------------------------------------------------
                | Customer Package Route
                |--------------------------------------------------------------------------
                */
                if ($isCustomer) {

                    $pkgRoute = Route::has('customer.packages.create')
                        ? route('customer.packages.create')
                        : url('/customer/packages/create');

                } else {

                    $pkgRoute = Route::has('packages.index')
                        ? route('packages.index')
                        : url('/packages');
                }

                $isPkgActive = request()->is('*package*');
            @endphp

            <a
                href="{{ $pkgRoute }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                {{ $isPkgActive
                    ? 'bg-blue-50 text-blue-600 font-bold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 {{ $isPkgActive ? 'text-blue-600' : 'text-slate-400' }}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"
                    />
                </svg>

                <span>Umrah Packages</span>
            </a>


            <!-- Visa Applications -->
            @php
                $visaRoute = Route::has('travel-agent.visa-applications.index')
                    ? route('travel-agent.visa-applications.index')
                    : url('/visa-applications');

                $isVisaActive = request()->is('*visa*');
            @endphp

            {{--
            <a
                href="{{ $visaRoute }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                {{ $isVisaActive
                    ? 'bg-blue-50 text-blue-600 font-bold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <span>Visa Applications</span>
            </a>
            --}}

        </nav>
    </div>


    <!-- Support -->
    <div class="mt-8">

        <div
            class="rounded-2xl bg-gradient-to-b from-blue-50/60 to-blue-100/40 p-4 border border-blue-100 text-center"
        >

            <span
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wider mb-2"
            >
                <span
                    class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"
                ></span>

                24/7 Support
            </span>

            <p class="text-xs text-slate-600 font-medium leading-relaxed mb-3">
                Need instant booking assistance? Reach out directly via WhatsApp.
            </p>

            <a
                href="https://wa.me/"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white py-2.5 px-4 text-xs font-bold shadow-md shadow-blue-500/20 transition"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-4 h-4"
                >
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12c0 1.82.49 3.53 1.35 5L2 22l5.13-1.32C8.54 21.52 10.22 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.59 0-3.09-.43-4.39-1.18l-.31-.18-3.04.78.81-2.94-.2-.33C4.1 14.88 3.6 13.49 3.6 12c0-4.63 3.77-8.4 8.4-8.4s8.4 3.77 8.4 8.4-3.77 8.4-8.4 8.4z"
                    />
                </svg>

                WhatsApp Support
            </a>

        </div>

    </div>

</aside>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        if (!sidebar || !sidebarOverlay) {
            return;
        }

        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
    }

    function closeSidebar() {
        if (!sidebar || !sidebarOverlay) {
            return;
        }

        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', openSidebar);
    }

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

});
</script>