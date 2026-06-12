<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">

    {{-- Restore the collapsed sidebar state before paint to avoid a layout flash --}}
    <script>
        try {
            if (localStorage.getItem('bakis-sidebar-collapsed') === '1') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (e) {}
    </script>

    @include('partials.favicon-links')

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    @endif
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    @if (session('show_splash'))
        @php($splashLogo = app(\App\Services\SiteSettingService::class)->logoPublicUrl())
        <style>
            #bakis-splash {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 1.1rem;
                background:
                    radial-gradient(circle at 50% 38%, rgba(22, 184, 154, 0.16) 0%, transparent 55%),
                    linear-gradient(160deg, #f6faf8 0%, #f2f6f3 55%, #eef4f0 100%);
                opacity: 1;
                transition: opacity 0.55s ease, visibility 0.55s ease;
            }
            #bakis-splash.is-done { opacity: 0; visibility: hidden; }
            #bakis-splash .splash-logo-wrap {
                position: relative;
                width: 88px;
                height: 88px;
                display: grid;
                place-items: center;
            }
            #bakis-splash .splash-ring {
                position: absolute;
                inset: 0;
                border-radius: 50%;
                border: 2px solid #16B89A;
                opacity: 0;
                animation: bakisRing 1.8s ease-out infinite;
            }
            #bakis-splash .splash-ring.r2 { animation-delay: 0.6s; }
            #bakis-splash .splash-logo,
            #bakis-splash .splash-badge {
                width: 64px;
                height: 64px;
                border-radius: 16px;
                object-fit: contain;
                background: #fff;
                border: 1px solid rgba(11, 61, 51, 0.10);
                box-shadow: 0 18px 40px rgba(11, 61, 51, 0.18);
                animation: bakisPop 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
            }
            #bakis-splash .splash-badge {
                display: grid;
                place-items: center;
                font-family: "Sora", system-ui, sans-serif;
                font-weight: 700;
                font-size: 1.9rem;
                color: #fff;
                background: linear-gradient(135deg, #0E7A66, #0B3D33);
            }
            #bakis-splash .splash-word {
                font-family: "Sora", system-ui, sans-serif;
                font-weight: 700;
                font-size: 1.55rem;
                letter-spacing: 0.06em;
                color: #0B3D33;
                margin: 0;
                animation: bakisFade 0.7s ease-out 0.18s both;
            }
            #bakis-splash .splash-sub {
                font-size: 0.82rem;
                color: #5C6B64;
                margin: -0.5rem 0 0;
                animation: bakisFade 0.7s ease-out 0.32s both;
            }
            @keyframes bakisPop {
                from { opacity: 0; transform: scale(0.82); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes bakisFade {
                from { opacity: 0; transform: translateY(6px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes bakisRing {
                0%   { opacity: 0.55; transform: scale(0.78); }
                100% { opacity: 0; transform: scale(1.35); }
            }
            @media (prefers-reduced-motion: reduce) {
                #bakis-splash .splash-ring { display: none; }
                #bakis-splash .splash-logo,
                #bakis-splash .splash-badge,
                #bakis-splash .splash-word,
                #bakis-splash .splash-sub { animation: none; }
            }
        </style>
        <div id="bakis-splash" role="status" aria-label="Memuatkan BAKIS">
            <div class="splash-logo-wrap">
                <span class="splash-ring" aria-hidden="true"></span>
                <span class="splash-ring r2" aria-hidden="true"></span>
                @if ($splashLogo)
                    <img class="splash-logo" src="{{ $splashLogo }}" alt="Logo BAKIS">
                @else
                    <div class="splash-badge">BK</div>
                @endif
            </div>
            <p class="splash-word">BAKIS</p>
            <p class="splash-sub" style="font-weight: bold;">Badan Kebajikan Islam · Hospital Sultanah Bahiyah</p>
       
        </div>
        <script>
            (function () {
                var el = document.getElementById('bakis-splash');
                if (!el) return;
                var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                document.documentElement.style.overflow = 'hidden';
                var hold = reduce ? 900 : 1900;
                function dismiss() {
                    el.classList.add('is-done');
                    document.documentElement.style.overflow = '';
                    setTimeout(function () { if (el && el.parentNode) el.parentNode.removeChild(el); }, 600);
                }
                setTimeout(dismiss, hold);
            })();
        </script>
    @endif

    @if(auth()->check())
        <!-- Sidebar Navigation -->
        @include('partials.sidebar')

        <!-- Main Layout -->
        <div id="mainContent" class="lg:pl-64">
            {{-- Mobile: sidebar open control (no top header bar) --}}
            <button type="button"
                    id="toggleSidebar"
                    class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 shadow-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                    aria-label="Toggle sidebar">
                <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Main Content -->
            <main class="min-h-screen pt-14 lg:pt-0">
                @yield('content')
            </main>
        </div>
    @else
        <!-- Guest Layout (no sidebar) -->
        <main class="min-h-screen">
            @yield('content')
        </main>
    @endif

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .dark .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>

    @stack('scripts')

    <script>
        // Sidebar Toggle Functionality
        (function() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('toggleSidebar');
            const closeButton = document.getElementById('closeSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const hamburgerIcon = document.getElementById('hamburgerIcon');
            const closeIcon = document.getElementById('closeIcon');
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const sidebarFooterBtn = document.getElementById('sidebarFooterBtn');
            const sidebarFooterDropdown = document.getElementById('sidebarFooterDropdown');
            const sidebarFooterChevron = document.getElementById('sidebarFooterChevron');

            let isSidebarOpen = false;
            let isUserDropdownOpen = false;
            let isFooterDropdownOpen = false;

            function closeSidebarFooterDropdown() {
                if (!sidebarFooterDropdown || !sidebarFooterBtn) {
                    return;
                }
                sidebarFooterDropdown.classList.add('hidden');
                sidebarFooterBtn.setAttribute('aria-expanded', 'false');
                if (sidebarFooterChevron) {
                    sidebarFooterChevron.classList.remove('rotate-180');
                }
                isFooterDropdownOpen = false;
            }

            if (sidebarFooterBtn && sidebarFooterDropdown) {
                sidebarFooterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isFooterDropdownOpen = !isFooterDropdownOpen;
                    sidebarFooterDropdown.classList.toggle('hidden', !isFooterDropdownOpen);
                    sidebarFooterBtn.setAttribute('aria-expanded', isFooterDropdownOpen ? 'true' : 'false');
                    if (sidebarFooterChevron) {
                        sidebarFooterChevron.classList.toggle('rotate-180', isFooterDropdownOpen);
                    }
                });

                document.addEventListener('click', function(e) {
                    if (isFooterDropdownOpen && !sidebarFooterBtn.contains(e.target) && !sidebarFooterDropdown.contains(e.target)) {
                        closeSidebarFooterDropdown();
                    }
                });
            }

            // Desktop collapse → icon rail (persisted, independent of mobile off-canvas)
            const collapseButton = document.getElementById('collapseSidebar');
            const kawalanSummary = document.getElementById('kawalanSummary');
            const kawalanDetails = document.getElementById('kawalanDetails');
            const SIDEBAR_COLLAPSE_KEY = 'bakis-sidebar-collapsed';

            function isSidebarCollapsed() {
                return document.documentElement.classList.contains('sidebar-collapsed');
            }

            function applyNavTooltips(collapsed) {
                // Native title tooltips only while collapsed (avoids scroll-container clipping)
                document.querySelectorAll('#sidebar [data-label]').forEach(function (item) {
                    if (collapsed) {
                        item.setAttribute('title', item.getAttribute('data-label'));
                    } else {
                        item.removeAttribute('title');
                    }
                });
            }

            function syncCollapseButton(collapsed) {
                if (!collapseButton) return;
                collapseButton.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                const label = collapsed
                    ? collapseButton.getAttribute('data-label-expand')
                    : collapseButton.getAttribute('data-label-collapse');
                if (label) collapseButton.setAttribute('aria-label', label);
            }

            function setSidebarCollapsed(collapsed) {
                document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
                try {
                    if (collapsed) {
                        localStorage.setItem(SIDEBAR_COLLAPSE_KEY, '1');
                    } else {
                        localStorage.removeItem(SIDEBAR_COLLAPSE_KEY);
                    }
                } catch (e) {}
                applyNavTooltips(collapsed);
                syncCollapseButton(collapsed);
            }

            // Initialise from the class set by the no-flash head script
            applyNavTooltips(isSidebarCollapsed());
            syncCollapseButton(isSidebarCollapsed());

            if (collapseButton) {
                collapseButton.addEventListener('click', function () {
                    setSidebarCollapsed(!isSidebarCollapsed());
                });
            }

            // While collapsed there is no room for the Kawalan submenu — expand the rail instead
            if (kawalanSummary && kawalanDetails) {
                kawalanSummary.addEventListener('click', function (e) {
                    if (isSidebarCollapsed()) {
                        e.preventDefault();
                        setSidebarCollapsed(false);
                        kawalanDetails.open = true;
                    }
                });
            }

            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('-translate-x-full');
                if (backdrop) {
                    backdrop.classList.remove('hidden');
                    backdrop.classList.add('block');
                }
                if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
                isSidebarOpen = true;
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('-translate-x-full');
                if (backdrop) {
                    backdrop.classList.add('hidden');
                    backdrop.classList.remove('block');
                }
                if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
                isSidebarOpen = false;
                document.body.style.overflow = '';
            }

            // Toggle sidebar
            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    if (isSidebarOpen) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            // Close sidebar button
            if (closeButton) {
                closeButton.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking backdrop
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            // Handle window resize - auto-close sidebar on mobile when resizing to desktop
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024 && isSidebarOpen) {
                        closeSidebar();
                    }
                }, 250);
            });

            // User Dropdown Toggle
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isUserDropdownOpen = !isUserDropdownOpen;
                    if (isUserDropdownOpen) {
                        userDropdown.classList.remove('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'true');
                    } else {
                        userDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (isUserDropdownOpen && !userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                        isUserDropdownOpen = false;
                    }
                });
            }

            // Escape: footer dropdown first, then header user menu, then mobile sidebar
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Escape') {
                    return;
                }
                if (isFooterDropdownOpen) {
                    closeSidebarFooterDropdown();
                    return;
                }
                if (userMenuButton && userDropdown && isUserDropdownOpen) {
                    userDropdown.classList.add('hidden');
                    userMenuButton.setAttribute('aria-expanded', 'false');
                    isUserDropdownOpen = false;
                    return;
                }
                if (isSidebarOpen) {
                    closeSidebar();
                }
            });

            // Close sidebar when clicking on nav items (mobile only)
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 1024 && isSidebarOpen) {
                        setTimeout(closeSidebar, 150);
                    }
                });
            });
        })();
    </script>
</body>
</html>
