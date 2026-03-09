<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    @if(auth()->check())
        <!-- Sidebar Navigation -->
        @include('partials.sidebar')

        <!-- Main Layout -->
        <div class="lg:pl-64">
            <!-- Header -->
            @include('partials.header')

            <!-- Main Content -->
            <main class="min-h-[calc(100vh-4rem)]">
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

            let isSidebarOpen = false;

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

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isSidebarOpen) {
                    closeSidebar();
                }
            });

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
                let isDropdownOpen = false;

                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isDropdownOpen = !isDropdownOpen;
                    if (isDropdownOpen) {
                        userDropdown.classList.remove('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'true');
                    } else {
                        userDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (isDropdownOpen && !userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                        isDropdownOpen = false;
                    }
                });

                // Close dropdown on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isDropdownOpen) {
                        userDropdown.classList.add('hidden');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                        isDropdownOpen = false;
                    }
                });
            }

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
