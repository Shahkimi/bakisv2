<!-- Mobile Sidebar Backdrop -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- Sidebar Navigation -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    <!-- Sidebar Header -->
    @php
        $brandLogoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    @endphp
    <div class="relative flex flex-col items-center justify-center pt-6 pb-5 px-4 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0 overflow-hidden">
        {{-- Subtle decorative gradient blob behind logo --}}
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-40 h-40 rounded-full bg-gradient-to-br from-indigo-400/20 to-purple-500/20 dark:from-indigo-500/15 dark:to-purple-600/15 blur-2xl"></div>
        </div>

        {{-- Close button --}}
        <button id="closeSidebar"
                class="absolute top-3 right-3 lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 flex-shrink-0"
                aria-label="Close sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Logo --}}
        <div class="relative z-10 mb-3">
            @if ($brandLogoUrl)
                <div class="p-1 rounded-2xl bg-white dark:bg-gray-700 shadow-md ring-1 ring-gray-200 dark:ring-gray-600">
                    <img
                        src="{{ $brandLogoUrl }}"
                        alt="{{ config('app.name') }}"
                        class="w-16 h-16 rounded-xl object-contain"
                    >
                </div>
            @else
                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-lg ring-2 ring-white/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- App name --}}
        <span class="relative z-10 text-base font-bold text-gray-900 dark:text-white text-center leading-tight px-6 truncate max-w-full">
            {{ config('app.name', 'Laravel') }}
        </span>
    </div>

    <!-- Navigation Menu -->
    @php
        $isAdmin = auth()->user()->isAdmin();
        $px = $isAdmin ? 'admin.' : 'user.';
    @endphp
    <nav class="flex-1 px-3 sm:px-4 py-4 sm:py-6 space-y-1 sm:space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium truncate">Dashboard</span>
        </a>

        <!-- New Member -->
        <a href="{{ route($px.'members.create') }}" class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 {{ request()->routeIs($px.'members.create') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span class="font-medium truncate">Ahli Baru</span>
        </a>
        <!-- Carian Member -->
        <a href="{{ route($px.'members.index') }}" class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 {{ request()->routeIs($px.'members.index') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="font-medium truncate">Carian Ahli</span>
        </a>
        <!-- Payment Verification -->
        <a href="{{ route($px.'pembayaran.index') }}" class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 {{ request()->routeIs($px.'pembayaran.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium truncate">Pembayaran</span>
        </a>

        <!-- Kutipan Yuran -->
        <a href="{{ route($px.'kutipan.index') }}" class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 {{ request()->routeIs($px.'kutipan.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 13c0 1.657-1.79 3-4 3s-4-1.343-4-3" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z" />
            </svg>
            <span class="font-medium truncate">Kutipan Yuran</span>
        </a>
        @if($isAdmin)
        <!-- Kawalan (admin only) -->
        <div class="kawalan-nav-group">
            <details class="group/details" {{ request()->routeIs('admin.kawalan.jabatan.*') || request()->routeIs('admin.kawalan.jawatan.*') || request()->routeIs('admin.kawalan.yuran.*') || request()->routeIs('admin.kawalan.account.*') || request()->routeIs('admin.kawalan.pengguna.*') || request()->routeIs('admin.kawalan.acara.*') || request()->routeIs('admin.kawalan.favicon.*') || request()->routeIs('admin.kawalan.ajk.*') || request()->routeIs('admin.kawalan.program.*') ? 'open' : '' }}>
                <summary class="nav-item flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 cursor-pointer list-none hover:bg-gray-100 dark:hover:bg-gray-700 [&::-webkit-details-marker]:hidden">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium truncate flex-1">Kawalan</span>
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-open/details:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="mt-1 ml-4 pl-6 border-l border-gray-200 dark:border-gray-600 space-y-0.5">
                    <a href="{{ route('admin.kawalan.jabatan.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.jabatan.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate">Jabatan</span>
                    </a>
                    <a href="{{ route('admin.kawalan.jawatan.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.jawatan.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Jawatan</span>
                    </a>
                    <a href="{{ route('admin.kawalan.yuran.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.yuran.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="truncate">Yuran</span>
                    </a>
                    <a href="{{ route('admin.kawalan.account.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.account.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="truncate">Akaun Bayaran</span>
                    </a>
                    <a href="{{ route('admin.kawalan.pengguna.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.pengguna.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="truncate">Pengguna</span>
                    </a>
                    <a href="{{ route('admin.kawalan.acara.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.acara.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Acara</span>
                    </a>
                    <a href="{{ route('admin.kawalan.favicon.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.favicon.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Logo & Favicon</span>
                    </a>
                    <a href="{{ route('admin.kawalan.ajk.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.ajk.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-2a3 3 0 10-2.83-4M5 11a3 3 0 11.83-5.83" />
                        </svg>
                        <span class="truncate">Ahli Jawatankuasa</span>
                    </a>
                    <a href="{{ route('admin.kawalan.program.index') }}" class="nav-item flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.program.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">Program</span>
                    </a>
                </div>
            </details>
        </div>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-3 sm:px-4 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="relative">
            <div id="sidebarFooterDropdown"
                 class="hidden absolute bottom-full left-0 right-0 mb-2 z-[60] rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-xl py-1.5 overflow-hidden"
                 role="menu"
                 aria-labelledby="sidebarFooterBtn">
                <a href="{{ route('profile.edit') }}"
                   class="nav-item flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors rounded-lg mx-1">
                    <svg class="w-5 h-5 flex-shrink-0 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium truncate">Tetapan Profil</span>
                </a>
                <div class="my-1.5 border-t border-gray-200 dark:border-gray-600"></div>
                <div class="px-1 pb-0.5">
                    @include('partials.logout-form')
                </div>
            </div>

            <button type="button"
                    id="sidebarFooterBtn"
                    class="w-full flex items-center space-x-2 sm:space-x-3 p-1.5 sm:p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 text-left"
                    aria-expanded="false"
                    aria-controls="sidebarFooterDropdown"
                    aria-haspopup="menu"
                    aria-label="Menu akaun">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full gradient-bg flex items-center justify-center text-white font-semibold text-xs sm:text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg id="sidebarFooterChevron" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500 dark:text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </div>
</aside>
