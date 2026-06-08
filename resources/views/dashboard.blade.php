@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .stat-card { animation: slideUp 0.5s ease-out both; }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.25s; }
    .progress-bar { transition: width 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Selamat datang, {{ auth()->user()->name }}! Berikut adalah ringkasan status ahli bagi tahun {{ $currentYear }}.
        </p>
    </div>

    {{-- Stat Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

        {{-- Aktif --}}
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex-shrink-0 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Aktif</p>
                <p class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 leading-none counter" data-target="{{ $aktifCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pembayaran yuran aktif</p>
            </div>
        </div>

        {{-- Tidak Aktif --}}
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/40 flex-shrink-0 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Tidak Aktif</p>
                <p class="text-4xl font-extrabold text-amber-500 dark:text-amber-400 leading-none counter" data-target="{{ $tidakAktifCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tiada pembayaran semasa</p>
            </div>
        </div>

        {{-- Meninggal --}}
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex-shrink-0 flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Meninggal</p>
                <p class="text-4xl font-extrabold text-gray-500 dark:text-gray-400 leading-none counter" data-target="{{ $meninggalCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Status meninggal dunia</p>
            </div>
        </div>

    </div>

    {{-- Combined Chart + Breakdown Card --}}
    @php
        $total = $totalCount ?: 1;
        $items = [
            [
                'label' => 'Aktif',
                'count' => $aktifCount,
                'pct'   => round($aktifCount / $total * 100, 1),
                'bar'   => 'bg-emerald-500',
                'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                'dot'   => 'bg-emerald-500',
            ],
            [
                'label' => 'Tidak Aktif',
                'count' => $tidakAktifCount,
                'pct'   => round($tidakAktifCount / $total * 100, 1),
                'bar'   => 'bg-amber-400',
                'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                'dot'   => 'bg-amber-400',
            ],
            [
                'label' => 'Meninggal',
                'count' => $meninggalCount,
                'pct'   => round($meninggalCount / $total * 100, 1),
                'bar'   => 'bg-gray-400',
                'badge' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                'dot'   => 'bg-gray-400',
            ],
        ];
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
        {{-- Card Header --}}
        <div class="px-6 pt-6 pb-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Status Ahli {{ $currentYear }}</h2>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Pecahan berdasarkan rekod pembayaran semasa</p>
            </div>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                {{ number_format($totalCount) }} ahli
            </span>
        </div>

        {{-- Card Body --}}
        <div class="p-6 grid grid-cols-1 lg:grid-cols-5 gap-6 items-center">

            {{-- Doughnut Chart --}}
            <div class="lg:col-span-2">
                <div class="chart-container">
                    @if($totalCount > 0)
                        <canvas id="memberStatusChart"></canvas>
                        <div style="position:absolute;top:0;left:0;right:0;bottom:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white" id="chartCenterValue">0</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Jumlah Ahli</span>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500">
                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm">Tiada rekod ahli</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Divider (vertical on lg, horizontal on mobile) --}}
            <div class="hidden lg:block lg:col-span-0 self-stretch w-px bg-gray-100 dark:bg-gray-700 mx-auto"></div>

            {{-- Breakdown Bars + Note --}}
            <div class="lg:col-span-3 flex flex-col gap-5">
                <div class="space-y-4">
                    @foreach($items as $item)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $item['dot'] }} flex-shrink-0"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $item['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($item['count']) }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $item['badge'] }}">{{ $totalCount > 0 ? $item['pct'] : 0 }}%</span>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="progress-bar h-2 rounded-full {{ $item['bar'] }}" style="width: 0%" data-width="{{ $totalCount > 0 ? $item['pct'] : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Nota status tidak aktif --}}
                <div class="rounded-lg border border-amber-200 dark:border-amber-700/50 bg-amber-50 dark:bg-amber-900/20 p-4">
                    <div class="flex gap-2">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-amber-700 dark:text-amber-300 mb-1">Nota: Peraturan Status Tidak Aktif</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 leading-relaxed">
                                Ahli yang membuat bayaran dianggap <strong>aktif</strong> sepanjang tempoh liputan ditambah 1 tahun <em>grace</em>.
                                Sekiranya tiada pembayaran dalam tempoh tersebut, ahli perlu mendaftar semula sebagai ahli baru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Account Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Maklumat Akaun</h2>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Counter animation ──────────────────────────────────────────────────
    function animateCounter(el, target, duration) {
        if (target === 0) { el.textContent = '0'; return; }
        const start = performance.now();
        function tick(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(eased * target).toLocaleString('ms-MY');
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    document.querySelectorAll('.counter').forEach(function (el) {
        animateCounter(el, parseInt(el.dataset.target, 10) || 0, 900);
    });

    // ── Progress bars (trigger after short delay so CSS transition fires) ──
    setTimeout(function () {
        document.querySelectorAll('.progress-bar').forEach(function (bar) {
            bar.style.width = bar.dataset.width;
        });
    }, 200);

    // ── Chart.js doughnut ──────────────────────────────────────────────────
    const canvas = document.getElementById('memberStatusChart');
    if (!canvas) return;

    const isDark = document.documentElement.classList.contains('dark')
        || window.matchMedia('(prefers-color-scheme: dark)').matches;

    const AKTIF       = {{ $aktifCount }};
    const TIDAK_AKTIF = {{ $tidakAktifCount }};
    const MENINGGAL   = {{ $meninggalCount }};
    const TOTAL       = {{ $totalCount }};

    // Animate center total
    const centerEl = document.getElementById('chartCenterValue');
    if (centerEl) animateCounter(centerEl, TOTAL, 1000);

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Tidak Aktif', 'Meninggal'],
            datasets: [{
                data: [AKTIF, TIDAK_AKTIF, MENINGGAL],
                backgroundColor: ['#10b981', '#f59e0b', '#9ca3af'],
                borderColor:     ['#059669', '#d97706', '#6b7280'],
                borderWidth: 2,
                hoverOffset: 12,
                hoverBorderWidth: 3,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1100,
                easing: 'easeInOutQuart',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#ffffff',
                    titleColor:      isDark ? '#f9fafb'  : '#111827',
                    bodyColor:       isDark ? '#d1d5db'  : '#374151',
                    borderColor:     isDark ? '#374151'  : '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    boxWidth: 10,
                    boxHeight: 10,
                    callbacks: {
                        label: function (ctx) {
                            const pct = TOTAL > 0
                                ? ((ctx.parsed / TOTAL) * 100).toFixed(1)
                                : '0.0';
                            return '  ' + ctx.label + ': ' + ctx.parsed.toLocaleString('ms-MY') + ' ahli (' + pct + '%)';
                        },
                    },
                },
            },
        },
    });
})();
</script>
@endpush
