<!-- Mobile Sidebar Backdrop -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- Sidebar Navigation -->
<aside id="sidebar" class="sidebar-shell fixed top-0 left-0 z-50 h-screen w-64 bg-white border-r border-[#e6efe9] transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    <!-- Sidebar Header -->
    @php
        $brandLogoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    @endphp
    <div class="sidebar-header relative flex flex-col border-b border-[#e6efe9] flex-shrink-0 overflow-hidden">
        {{-- Soft emerald → brass wash behind the logo --}}
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-44 h-44 rounded-full bg-gradient-to-br from-[#16B89A]/15 to-[#C49A4A]/15 blur-2xl"></div>
        </div>

        {{-- Button row: close (mobile) or collapse (desktop), right-aligned --}}
        <div class="relative z-10 flex justify-end px-3 pt-3">
            <button id="closeSidebar"
                    class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-[#0B3D33] hover:bg-[#eef6f2] active:scale-90 transition-all duration-200"
                    aria-label="Tutup bar sisi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <button id="collapseSidebar"
                    class="sidebar-collapse-btn hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-[#0E7A66] hover:bg-[#eef6f2] active:scale-90 transition-all duration-200"
                    title="Lipat bar sisi"
                    aria-label="Lipat bar sisi"
                    aria-expanded="true"
                    data-label-collapse="Lipat bar sisi"
                    data-label-expand="Buka bar sisi">
                <svg class="sidebar-collapse-icon w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        {{-- Logo — clickable, navigates to the dashboard --}}
        <div class="flex flex-col items-center pb-5 px-4 sm:px-6">
        <a href="{{ route('dashboard') }}"
           class="sidebar-brand-link group flex flex-col items-center px-3 py-2 rounded-2xl transition-all duration-200 hover:bg-[#eef6f2]/70 active:scale-[0.97] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0E7A66]/40 focus-visible:ring-offset-2"
           aria-label="Pergi ke papan pemuka {{ config('app.name', 'Laravel') }}">
        <div class="sidebar-logo relative z-10 mb-3">
            @if ($brandLogoUrl)
                <div class="sidebar-logo-frame p-1.5 rounded-2xl bg-white shadow-[0_10px_30px_-12px_rgba(11,61,51,0.35)] ring-1 ring-emerald-100 transition-all duration-300 group-hover:scale-105 group-hover:shadow-[0_14px_34px_-12px_rgba(11,61,51,0.45)]">
                    <img
                        src="{{ $brandLogoUrl }}"
                        alt="{{ config('app.name') }}"
                        class="sidebar-logo-img w-16 h-16 rounded-xl object-contain transition-all duration-300"
                    >
                </div>
            @else
                <div class="sidebar-logo-frame w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-[0_10px_30px_-12px_rgba(11,61,51,0.5)] ring-1 ring-emerald-100 transition-all duration-300 group-hover:scale-105 group-hover:shadow-[0_14px_34px_-12px_rgba(11,61,51,0.55)]">
                    <svg class="sidebar-logo-img w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- App name + subtitle --}}
        <span class="sidebar-label relative z-10 text-base font-bold text-[#0B3D33] text-center leading-tight px-6 truncate max-w-full transition-colors duration-200 group-hover:text-[#0E7A66]">
            {{ config('app.name', 'Laravel') }}
        </span>
        <span class="sidebar-app-name relative z-10 mt-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-[#0E7A66]/70">
            Sistem Keahlian
        </span>
        </a>
        </div>{{-- end logo/name wrapper --}}
    </div>

    <!-- Navigation Menu -->
    @php
        $isAdmin = auth()->user()->isAdmin();
        $px = $isAdmin ? 'admin.' : 'user.';
    @endphp
    <nav class="flex-1 px-3 sm:px-4 py-4 sm:py-5 overflow-y-auto">
        {{-- Utama --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Utama</p>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" data-label="Dashboard" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="sidebar-label font-medium truncate">Dashboard</span>
                </a>

                <a href="{{ route($px.'members.create') }}" data-label="Ahli Baru" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 {{ request()->routeIs($px.'members.create') ? 'is-active' : '' }}">
                    <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="sidebar-label font-medium truncate">Ahli Baru</span>
                </a>

                <a href="{{ route($px.'members.index') }}" data-label="Carian Ahli" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 {{ request()->routeIs($px.'members.index') ? 'is-active' : '' }}">
                    <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="sidebar-label font-medium truncate">Carian Ahli</span>
                </a>
            </div>
        </div>

        {{-- Kewangan --}}
        <div class="sidebar-section mt-5">
            <p class="sidebar-section-label px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Kewangan</p>
            <div class="space-y-1">
                <a href="{{ route($px.'pembayaran.index') }}" data-label="Pembayaran" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 {{ request()->routeIs($px.'pembayaran.*') ? 'is-active' : '' }}">
                    <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-label font-medium truncate">Pembayaran</span>
                </a>

                <a href="{{ route($px.'kutipan.index') }}" data-label="Kutipan Yuran" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 {{ request()->routeIs($px.'kutipan.*') ? 'is-active' : '' }}">
                    <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 13c0 1.657-1.79 3-4 3s-4-1.343-4-3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z" />
                    </svg>
                    <span class="sidebar-label font-medium truncate">Kutipan Yuran</span>
                </a>
            </div>
        </div>

        @if($isAdmin)
        {{-- Persatuan (admin only) --}}
        <div class="sidebar-section mt-5">
            <p class="sidebar-section-label px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Persatuan</p>
            <div class="kawalan-nav-group">
                <details id="infoDetails" class="group/details" {{ request()->routeIs('admin.kawalan.perlembagaan.*') || request()->routeIs('admin.kawalan.program.*') || request()->routeIs('admin.kawalan.acara.*') ? 'open' : '' }}>
                    <summary id="infoSummary" data-label="Info" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                        <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="sidebar-label font-medium truncate flex-1">Info</span>
                        <svg class="sidebar-label w-4 h-4 flex-shrink-0 text-gray-400 transition-transform group-open/details:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="kawalan-submenu mt-1 ml-4 pl-5 border-l border-[#e6efe9] space-y-0.5">                        
                        <a href="{{ route('admin.kawalan.ajk.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.ajk.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-2a3 3 0 10-2.83-4M5 11a3 3 0 11.83-5.83" />
                            </svg>
                            <span class="truncate">Ahli Jawatankuasa</span>
                        </a>
                        <a href="{{ route('admin.kawalan.perlembagaan.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.perlembagaan.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="truncate">Perlembagaan</span>
                        </a>
                        <a href="{{ route('admin.kawalan.program.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.program.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Program</span>
                        </a>
                        <a href="{{ route('admin.kawalan.acara.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.acara.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Acara</span>
                        </a>
                    </div>
                </details>
            </div>
        </div>
        @endif

        @if($isAdmin)
        {{-- Pentadbiran (admin only) --}}
        <div class="sidebar-section mt-5">
            <p class="sidebar-section-label px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Pentadbiran</p>
            <div class="kawalan-nav-group">
                <details id="kawalanDetails" class="group/details" {{ request()->routeIs('admin.kawalan.jabatan.*') || request()->routeIs('admin.kawalan.jawatan.*') || request()->routeIs('admin.kawalan.yuran.*') || request()->routeIs('admin.kawalan.account.*') || request()->routeIs('admin.kawalan.pengguna.*') || request()->routeIs('admin.kawalan.favicon.*') || request()->routeIs('admin.kawalan.keselamatan.*') || request()->routeIs('admin.kawalan.ajk.*') ? 'open' : '' }}>
                    <summary id="kawalanSummary" data-label="Kawalan" class="nav-item relative flex items-center px-3 sm:px-4 py-2.5 text-sm sm:text-[15px] rounded-lg transition-all duration-200 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                        <svg class="nav-icon w-5 h-5 sm:w-6 sm:h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="sidebar-label font-medium truncate flex-1">Kawalan</span>
                        <svg class="sidebar-label w-4 h-4 flex-shrink-0 text-gray-400 transition-transform group-open/details:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="kawalan-submenu mt-1 ml-4 pl-5 border-l border-[#e6efe9] space-y-0.5">
                        <a href="{{ route('admin.kawalan.jabatan.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.jabatan.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">Jabatan</span>
                        </a>
                        <a href="{{ route('admin.kawalan.jawatan.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.jawatan.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Jawatan</span>
                        </a>
                        <a href="{{ route('admin.kawalan.yuran.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.yuran.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">Yuran</span>
                        </a>
                        <a href="{{ route('admin.kawalan.account.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.account.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="truncate">Akaun Bayaran</span>
                        </a>
                        <a href="{{ route('admin.kawalan.pengguna.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.pengguna.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">Pengguna</span>
                        </a>
                        <a href="{{ route('admin.kawalan.favicon.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.favicon.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Logo &amp; Favicon</span>
                        </a>
                        <a href="{{ route('admin.kawalan.keselamatan.index') }}" class="nav-item relative flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.kawalan.keselamatan.*') ? 'is-active' : '' }}">
                            <svg class="nav-icon w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="truncate">Turnstile</span>
                        </a>
                    </div>
                </details>
            </div>
        </div>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="px-3 sm:px-4 py-3 sm:py-4 border-t border-[#e6efe9] flex-shrink-0">
        <div class="relative">
            <div id="sidebarFooterDropdown"
                 class="hidden absolute bottom-full left-0 right-0 mb-2 z-[60] rounded-xl border border-[#e6efe9] bg-white shadow-xl py-1.5 overflow-hidden"
                 role="menu"
                 aria-labelledby="sidebarFooterBtn">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-[#eef6f2] hover:text-[#0B3D33] transition-colors rounded-lg mx-1">
                    <svg class="w-5 h-5 flex-shrink-0 text-[#0E7A66]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium truncate">Tetapan Profil</span>
                </a>
                <div class="my-1.5 border-t border-[#e6efe9]"></div>
                <div class="px-1 pb-0.5">
                    @include('partials.logout-form')
                </div>
            </div>

            <button type="button"
                    id="sidebarFooterBtn"
                    data-label="{{ auth()->user()->name }}"
                    class="w-full flex items-center space-x-2 sm:space-x-3 p-1.5 sm:p-2 rounded-xl hover:bg-[#eef6f2] transition-colors focus:outline-none focus:ring-2 focus:ring-[#0E7A66]/40 text-left"
                    aria-expanded="false"
                    aria-controls="sidebarFooterDropdown"
                    aria-haspopup="menu"
                    aria-label="Menu akaun">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 ring-2 ring-emerald-100 flex items-center justify-center text-white font-semibold text-xs sm:text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-label flex-1 min-w-0 text-left">
                    <p class="text-sm font-semibold text-[#0B3D33] truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg id="sidebarFooterChevron" class="sidebar-label w-4 h-4 sm:w-5 sm:h-5 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </div>
</aside>

<style>
    /* ── BAKIS sidebar — emerald + brass nav states ── */
    #sidebar .nav-item { position: relative; color: #475569; }
    #sidebar .nav-icon { color: #64748b; transition: color .2s ease, transform .2s ease; }

    #sidebar .nav-item:hover { background-color: #eef6f2; color: #0B3D33; }
    #sidebar .nav-item:hover .nav-icon { color: #0E7A66; transform: scale(1.06); }

    #sidebar .nav-item.is-active { background-color: #eaf5f0; color: #0B3D33; font-weight: 600; }
    #sidebar .nav-item.is-active .nav-icon { color: #0E7A66; }

    /* Signature: brass left indicator on the active item */
    #sidebar .nav-item.is-active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 58%;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: #C49A4A;
    }

    /* ── Desktop collapse → icon rail ── */
    @media (min-width: 1024px) {
        #sidebar { transition: width .3s ease, transform .3s ease; }
        #mainContent { transition: padding-left .3s ease; }

        html.sidebar-collapsed #sidebar { width: 5rem; }
        html.sidebar-collapsed #mainContent { padding-left: 5rem; }

        /* Hide all text content, keep icons */
        html.sidebar-collapsed #sidebar .sidebar-label,
        html.sidebar-collapsed #sidebar .sidebar-app-name,
        html.sidebar-collapsed #sidebar .sidebar-section-label { display: none; }

        /* Center the icons and drop label spacing */
        html.sidebar-collapsed #sidebar .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed #sidebar .nav-icon { margin-right: 0; }

        /* No left bar on the rail — the tint alone marks the active item */
        html.sidebar-collapsed #sidebar .nav-item.is-active::before { display: none; }

        /* Keep groups visually separated on the rail with a hairline */
        html.sidebar-collapsed #sidebar .sidebar-section + .sidebar-section {
            margin-top: .5rem;
            padding-top: .5rem;
            border-top: 1px solid #e6efe9;
        }

        /* Compact header + shrink logo */
        html.sidebar-collapsed #sidebar .sidebar-header { padding-left: .5rem; padding-right: .5rem; }
        html.sidebar-collapsed #sidebar .sidebar-header > div:last-child { padding-left: .25rem; padding-right: .25rem; padding-bottom: .5rem; }
        html.sidebar-collapsed #sidebar .sidebar-logo-frame { padding: .25rem; }
        html.sidebar-collapsed #sidebar .sidebar-logo-img { width: 2.5rem; height: 2.5rem; }

        /* Flip the collapse chevron to point outward (expand affordance) */
        html.sidebar-collapsed #sidebar .sidebar-collapse-icon { transform: rotate(180deg); }

        /* On the rail the close button is gone (mobile-only), so the button
           row holds just the collapse toggle — center it under the logo
           instead of leaving it stranded against the right edge. */
        html.sidebar-collapsed #sidebar .sidebar-header > div:first-child + div {
            justify-content: center;
        }

        /* Kawalan submenu has no room on the rail */
        html.sidebar-collapsed #sidebar .kawalan-submenu { display: none; }

        /* Footer collapses to the avatar; widen its dropdown so it stays readable */
        html.sidebar-collapsed #sidebar #sidebarFooterBtn { justify-content: center; }
        html.sidebar-collapsed #sidebarFooterDropdown { width: 15rem; }
    }

    /* Hide scrollbar on the nav when sidebar is expanded — stays scrollable */
    #sidebar nav { scrollbar-width: none; }
    #sidebar nav::-webkit-scrollbar { display: none; }

    @media (prefers-reduced-motion: reduce) {
        #sidebar,
        #mainContent,
        #sidebar .nav-icon,
        #sidebar .sidebar-collapse-icon,
        #sidebar .sidebar-collapse-btn,
        #sidebar .sidebar-brand-link,
        #sidebar .sidebar-logo-frame { transition: none !important; }
    }
</style>
