<!DOCTYPE html>
<html lang="en" class="h-full font-sans antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Umrah ERP Portal')</title>
    <!-- Tailwind CSS Vite Engine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#0B2A2E;
            --ink-2:#123B3F;
            --gold:#C89B3C;
            --gold-light:#E8C874;
            --cream:#FBF7EE;
            --cream-2:#F1EADA;
            --charcoal:#1F2620;
        }
        .font-display{ font-family:'Fraunces', serif; }
        .font-ui{ font-family:'Manrope', sans-serif; }
    </style>
</head>
<body class="h-full font-ui" style="background:var(--cream-2); color:var(--charcoal);">

    <!-- Ambient background wash -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10" style="background:var(--cream-2);">
        <div class="absolute -top-32 -left-24 w-[28rem] h-[28rem] rounded-full blur-[110px] opacity-40" style="background:var(--gold-light);"></div>
        <div class="absolute -bottom-40 -right-24 w-[28rem] h-[28rem] rounded-full blur-[110px] opacity-30" style="background:var(--ink-2);"></div>
    </div>

    <div class="min-h-screen w-full flex items-center justify-center p-0 sm:p-6 lg:p-10" 
        x-data="swipeNav()"
        @touchstart="startSwipe($event)"
        @touchend="endSwipe($event)">
        <div class="w-full sm:max-w-[1200px] min-h-screen sm:min-h-[720px] bg-white sm:rounded-[2rem] overflow-hidden grid grid-cols-1 lg:grid-cols-12 shadow-none sm:shadow-[0_30px_80px_-20px_rgba(11,42,46,0.25)] sm:border sm:border-black/5"
            :style="{ transform: `translateX(${translateX}px)`, transition: isTransitioning ? 'transform 0.3s ease-out' : 'none' }"
            id="authContainer">

            <!-- LEFT SIDE (Dynamic Content Inject Hoga Yahan) -->
            <div class="lg:col-span-6 xl:col-span-5 px-6 py-8 sm:p-10 md:p-12 xl:p-16 flex flex-col justify-between" style="background:var(--cream);">
                <div id="contentLoader" class="flex items-center justify-center w-full h-full" style="display:none;">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-[#E4DDCB] border-t-[#C89B3C] rounded-full animate-spin mx-auto mb-3"></div>
                        <p style="color:#9AA79C;" class="text-sm font-medium">Loading...</p>
                    </div>
                </div>
                <div id="contentArea">
                    @yield('content')
                </div>
            </div>

            <!-- RIGHT SIDE (Static Artwork - Hamesha Same Rahega) -->
            <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 relative flex-col justify-between p-12 xl:p-16 overflow-hidden" style="background:linear-gradient(160deg, var(--ink) 0%, var(--ink-2) 100%);">
                <!-- Tessellated star-pattern backdrop -->
                <svg class="absolute inset-0 w-full h-full opacity-[0.16]" preserveAspectRatio="xMidYMid slice">
                    <defs>
                        <pattern id="star8reg" width="120" height="120" patternUnits="userSpaceOnUse">
                            <g stroke="var(--gold-light)" stroke-width="1" fill="none">
                                <rect x="20" y="20" width="80" height="80" transform="rotate(0 60 60)"/>
                                <rect x="20" y="20" width="80" height="80" transform="rotate(45 60 60)"/>
                                <circle cx="60" cy="60" r="4" fill="var(--gold-light)" stroke="none"/>
                            </g>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#star8reg)"/>
                </svg>

                <div class="relative z-10 flex justify-end">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full backdrop-blur-md" style="background:rgba(255,255,255,0.07); border:1px solid rgba(232,200,116,0.25);">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--gold-light);"></span>
                        <span class="text-[11px] font-bold tracking-wide" style="color:var(--gold-light);">Onboarding takes under 5 minutes</span>
                    </div>
                </div>

                <div class="relative z-10 my-auto flex flex-col items-center text-center">
                    <svg width="240" height="240" viewBox="0 0 260 260" class="xl:w-[280px] xl:h-[280px]">
                        <circle cx="130" cy="130" r="126" fill="none" stroke="rgba(232,200,116,0.25)" stroke-width="1"/>
                        <circle cx="130" cy="130" r="104" fill="rgba(255,255,255,0.04)" stroke="rgba(232,200,116,0.35)" stroke-width="1"/>
                        <g stroke="var(--gold-light)" stroke-width="1.4" fill="none" opacity="0.9">
                            <rect x="55" y="55" width="150" height="150" transform="rotate(0 130 130)"/>
                            <rect x="55" y="55" width="150" height="150" transform="rotate(45 130 130)"/>
                        </g>
                        <circle cx="130" cy="130" r="10" fill="var(--gold-light)"/>
                    </svg>

                    <h2 class="mt-8 text-2xl xl:text-3xl font-semibold tracking-tight font-display text-white">Built for growing Umrah agencies</h2>
                    <p class="text-sm mt-3 max-w-sm mx-auto leading-relaxed" style="color:rgba(240,235,220,0.7);">
                        Set up your team, connect the Ministry API, and start booking groups the same day you register.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 items-start">
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0" style="background:rgba(232,200,116,0.16); border:1px solid rgba(232,200,116,0.4);">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--gold-light)" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                            </span>
                            <span class="text-sm" style="color:rgba(240,235,220,0.85);">Unlimited pilgrim group manifests</span>
                        </div>
                        <!-- Baki checkmarks waise hi -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Swipe & AJAX Navigation Script -->
    <script>
        window.showStep = function(step) {
            const targetStep = document.querySelector(`[data-step="${step}"]`);
            if (!targetStep) {
                return;
            }

            document.querySelectorAll('[data-step]').forEach(function (panel) {
                panel.style.display = panel === targetStep ? '' : 'none';
            });
            targetStep.classList.remove('hidden');

            const bars = [
                document.getElementById('progressBar1'),
                document.getElementById('progressBar2'),
                document.getElementById('progressBar3')
            ];
            bars.forEach(function (bar, index) {
                if (bar) {
                    bar.style.background = (index + 1 <= step) ? 'var(--ink)' : '#E4DDCB';
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.showStep === 'function') {
                window.showStep(1);
            }
        });

        function swipeNav() {
            return {
                translateX: 0,
                isTransitioning: false,
                startX: 0,
                currentX: 0,
                isDragging: false,
                swipeThreshold: 50,
                
                init() {
                    this.attachLinkListeners();
                },
                
                attachLinkListeners() {
                    // Get all login/register links
                    const links = document.querySelectorAll('a[href*="login"], a[href*="register"]');
                    links.forEach(link => {
                        link.addEventListener('click', (e) => this.handleLinkClick(e, link));
                    });
                },
                
                async handleLinkClick(e, link) {
                    const href = link.getAttribute('href');
                    
                    // Only handle if it's a login/register route
                    if (!href.includes('login') && !href.includes('register')) {
                        return;
                    }
                    
                    e.preventDefault();
                    
                    // Show loading spinner
                    const loader = document.getElementById('contentLoader');
                    const content = document.getElementById('contentArea');
                    
                    loader.style.display = 'flex';
                    this.isTransitioning = true;
                    
                    try {
                        // Slide out to the right
                        this.translateX = window.innerWidth;
                        
                        // Wait for animation
                        await new Promise(resolve => setTimeout(resolve, 150));
                        
                        // Fetch new content
                        const response = await fetch(href, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!response.ok) throw new Error('Failed to load page');
                        
                        const html = await response.text();
                        
                        // Extract content area from HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('contentArea');
                        
                        if (!newContent) throw new Error('Content not found');
                        
                        // Replace content
                        content.innerHTML = newContent.innerHTML;
                        
                        // Re-attach listeners to new links
                        this.attachLinkListeners();
                        
                        // Reinitialize page-specific interactive scripts
                        if (typeof window.showStep === 'function') {
                            window.showStep(1);
                        }

                        await new Promise(resolve => setTimeout(resolve, 150));
                        
                        this.translateX = 0;
                        
                        // Hide loader
                        loader.style.display = 'none';
                        
                    } catch (error) {
                        console.error('Navigation error:', error);
                        loader.style.display = 'none';
                        this.translateX = 0;
                        // Fallback to normal navigation
                        window.location.href = href;
                    }
                    
                    this.isTransitioning = false;
                },
                
                startSwipe(e) {
                    // Only swipe if not typing in form
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                    
                    this.startX = e.touches[0].clientX;
                    this.isDragging = true;
                    this.isTransitioning = false;
                },
                
                endSwipe(e) {
                    if (!this.isDragging) return;
                    
                    const endX = e.changedTouches[0].clientX;
                    const swipeDistance = this.startX - endX;
                    
                    this.isTransitioning = true;
                    this.isDragging = false;
                    
                    // Swipe from RIGHT to LEFT (show register page)
                    if (swipeDistance > this.swipeThreshold && window.location.pathname.includes('login')) {
                        const registerLink = document.querySelector('a[href*="register"]');
                        if (registerLink) {
                            this.handleLinkClick({ preventDefault: () => {} }, registerLink);
                        }
                    }
                    // Swipe from LEFT to RIGHT (show login page)
                    else if (swipeDistance < -this.swipeThreshold && window.location.pathname.includes('register')) {
                        const loginLink = document.querySelector('a[href*="login"]');
                        if (loginLink) {
                            this.handleLinkClick({ preventDefault: () => {} }, loginLink);
                        }
                    } else {
                        // Reset if swipe didn't meet threshold
                        this.translateX = 0;
                    }
                }
            };
        }
    </script>
</body>
</html>