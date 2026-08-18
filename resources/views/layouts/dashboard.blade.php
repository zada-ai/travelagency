<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? (($isCustomer ?? false) ? 'Customer Dashboard' : (($userRole ?? '') === 'visa_office' ? 'Visa Office Dashboard' : 'Agent Dashboard')) }} | Umrah ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f4f7fc;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 8px 30px rgba(148, 163, 184, 0.08);
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.1);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">
    <div class="min-h-screen">
        <div class="grid min-h-screen xl:grid-cols-[280px_1fr] relative">
            <x-dashboard-sidebar
                :is-customer="($isCustomer ?? false)"
                :is-visa-officer="($isVisaOfficer ?? false)"
                :current-user="($currentUser ?? auth()->user() ?? auth()->guard('travel_agent')->user())"
                :agent="($agent ?? (auth()->user() ?? auth()->guard('travel_agent')->user()))"
                :portal-label="($portalLabel ?? 'Agent Portal')"
                :portal-system-label="($portalSystemLabel ?? 'Agent Portal System')"
            />

            <main class="p-4 sm:p-6 lg:p-8 space-y-6 overflow-x-hidden">
                <div class="max-w-6xl mx-auto space-y-6">
                    @yield('content')

                    @if(! empty($innerView ?? null))
                        @include($innerView)
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const setBodyOverflow = (locked) => {
            document.body.style.overflow = locked ? 'hidden' : '';
        };

        const closeSidebar = () => {
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');

            if (sidebarOverlay) {
                sidebarOverlay.classList.add('hidden');
            }

            setBodyOverflow(false);
        };

        const openSidebar = () => {
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');

            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('hidden');
            }

            setBodyOverflow(true);
        };

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', openSidebar);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1280) {
                setBodyOverflow('');
            }
        });

        const logoutTrigger = document.getElementById('logoutTrigger');
        const logoutModal = document.getElementById('logoutModal');
        const logoutCancel = document.getElementById('logoutCancel');

        const openLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.remove('hidden');
            logoutModal.style.display = 'flex';
            setBodyOverflow(true);
        };

        const closeLogoutModal = () => {
            if (!logoutModal) return;
            logoutModal.classList.add('hidden');
            logoutModal.style.display = 'none';
            if (window.innerWidth < 1280) {
                setBodyOverflow(false);
            }
        };

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', openLogoutModal);
        }

        if (logoutCancel) {
            logoutCancel.addEventListener('click', closeLogoutModal);
        }
    </script>
</body>
</html>
