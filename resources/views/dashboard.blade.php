@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 350px;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes softPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.85; }
    }
    .stat-card {
        animation: slideUp 0.5s ease-out both;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.25s; }
    .stat-card:hover {
        transform: translateY(-2px) scale(1.02);
    }
    .chart-card {
        animation: fadeIn 0.6s ease-out 0.2s both;
    }
    .chart-center-value {
        animation: softPulse 2.5s ease-in-out infinite;
    }
    .progress-bar {
        transition: width 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .progress-bar-emerald {
        background: linear-gradient(90deg, #059669, #10b981, #34d399);
    }
    .progress-bar-amber {
        background: linear-gradient(90deg, #d97706, #f59e0b, #fbbf24);
    }
    .progress-bar-gray {
        background: linear-gradient(90deg, #6b7280, #9ca3af, #d1d5db);
    }
    .breakdown-item {
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .breakdown-item:hover {
        transform: translateX(4px);
    }
    .breakdown-item-clickable {
        cursor: pointer;
    }
    .breakdown-item-clickable:hover {
        background-color: rgba(245, 158, 11, 0.08);
        border-color: rgba(245, 158, 11, 0.2);
    }
    .dark .breakdown-item-clickable:hover {
        background-color: rgba(245, 158, 11, 0.12);
    }
    @keyframes skeletonPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .skeleton-pulse {
        animation: skeletonPulse 1.5s ease-in-out infinite;
    }
    .chart-container-inner {
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.04) 0%, transparent 70%);
    }
    .dark .chart-container-inner {
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl xl:max-w-screen-xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Selamat datang, {{ auth()->user()->name }}! Berikut adalah ringkasan status ahli bagi tahun {{ $currentYear }}.
        </p>
    </div>

    {{-- Stat Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">

        {{-- Aktif --}}
        <div class="stat-card group relative overflow-hidden bg-gradient-to-br from-white to-emerald-50/50 dark:from-gray-800 dark:to-emerald-950/20 rounded-xl border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-800/50 p-5 flex items-center gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            <div class="relative w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 shrink-0 flex items-center justify-center ring-4 ring-emerald-50 dark:ring-emerald-900/20 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="relative min-w-0">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Aktif</p>
                <p class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 leading-none counter tabular-nums" data-target="{{ $aktifCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Pembayaran yuran aktif</p>
            </div>
        </div>

        {{-- Tidak Aktif --}}
        <div class="stat-card group relative overflow-hidden bg-gradient-to-br from-white to-amber-50/50 dark:from-gray-800 dark:to-amber-950/20 rounded-xl border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800/50 p-5 flex items-center gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-amber-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            <div class="relative w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 shrink-0 flex items-center justify-center ring-4 ring-amber-50 dark:ring-amber-900/20 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="relative min-w-0">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Tidak Aktif</p>
                <p class="text-4xl font-extrabold text-amber-500 dark:text-amber-400 leading-none counter tabular-nums" data-target="{{ $tidakAktifCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Tiada pembayaran semasa</p>
            </div>
        </div>

        {{-- Meninggal --}}
        <div class="stat-card group relative overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900/50 rounded-xl border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 p-5 flex items-center gap-4 md:col-span-2 lg:col-span-1">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-500/0 to-gray-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            <div class="relative w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700 shrink-0 flex items-center justify-center ring-4 ring-gray-50 dark:ring-gray-800 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/>
                </svg>
            </div>
            <div class="relative min-w-0">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">Meninggal</p>
                <p class="text-4xl font-extrabold text-gray-500 dark:text-gray-400 leading-none counter tabular-nums" data-target="{{ $meninggalCount }}">0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">Status meninggal dunia</p>
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
                'bar'   => 'progress-bar-emerald',
                'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                'dot'   => 'bg-emerald-500',
            ],
            [
                'label' => 'Tidak Aktif',
                'count' => $tidakAktifCount,
                'pct'   => round($tidakAktifCount / $total * 100, 1),
                'bar'   => 'progress-bar-amber',
                'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                'dot'   => 'bg-amber-400',
            ],
            [
                'label' => 'Meninggal',
                'count' => $meninggalCount,
                'pct'   => round($meninggalCount / $total * 100, 1),
                'bar'   => 'progress-bar-gray',
                'badge' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                'dot'   => 'bg-gray-400',
            ],
        ];
    @endphp

    <div class="chart-card relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-300 mb-6">
        {{-- Gradient accent border --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-amber-400 to-gray-400"></div>

        {{-- Card Header --}}
        <div class="px-6 pt-7 pb-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Status Ahli {{ $currentYear }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pecahan berdasarkan rekod pembayaran semasa</p>
            </div>
            <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700/80 px-4 py-1.5 rounded-full shrink-0">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ number_format($totalCount) }} ahli
            </span>
        </div>

        {{-- Card Body: Doughnut (left) + Breakdown (right) --}}
        <div class="p-6 grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8 items-center">

            {{-- Doughnut Chart --}}
            <div class="lg:col-span-2">
                <div class="chart-container chart-container-inner rounded-2xl p-4">
                    @if($totalCount > 0)
                        <canvas id="memberStatusChart" role="img" aria-label="Carta status ahli"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-white chart-center-value tabular-nums" id="chartCenterValue">0</span>
                            <span class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mt-1">Jumlah Ahli</span>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500">
                            <svg class="w-14 h-14 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm font-medium">Tiada rekod ahli</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Breakdown Bars + Note --}}
            <div class="lg:col-span-3 flex flex-col gap-5 lg:border-l lg:border-gray-100 lg:dark:border-gray-700 lg:pl-8">
                <div class="space-y-5">
                    @foreach($items as $index => $item)
                    @php $isTidakAktif = $item['label'] === 'Tidak Aktif'; @endphp
                    <div
                        @if($isTidakAktif)
                            id="tidakAktifTrigger"
                            role="button"
                            tabindex="0"
                            aria-label="Lihat senarai pegawai tidak aktif"
                        @endif
                        class="breakdown-item rounded-lg px-3 py-2 -mx-3 {{ $isTidakAktif ? 'breakdown-item-clickable border border-transparent' : 'hover:bg-gray-50/80 dark:hover:bg-gray-700/30' }} {{ $index < count($items) - 1 ? 'border-b border-gray-100 dark:border-gray-700/50 pb-5' : '' }}"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-3 h-3 rounded-full {{ $item['dot'] }} shrink-0 ring-2 ring-white dark:ring-gray-800"></span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $item['label'] }}</span>
                                @if($isTidakAktif)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 dark:text-amber-400 shrink-0">
                                        <span class="hidden sm:inline">Lihat senarai</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <span class="text-sm font-bold text-gray-900 dark:text-white tabular-nums">{{ number_format($item['count']) }}</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold {{ $item['badge'] }}">{{ $totalCount > 0 ? $item['pct'] : 0 }}%</span>
                            </div>
                        </div>
                        <div class="h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                            <div
                                class="progress-bar h-2.5 rounded-full {{ $item['bar'] }} shadow-sm"
                                style="width: 0%"
                                data-width="{{ $totalCount > 0 ? $item['pct'] : 0 }}%"
                                role="progressbar"
                                aria-valuenow="{{ $totalCount > 0 ? $item['pct'] : 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-label="{{ $item['label'] }}: {{ $totalCount > 0 ? $item['pct'] : 0 }}%"
                            ></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Nota status tidak aktif --}}
                <div class="rounded-xl border border-amber-200/80 dark:border-amber-700/50 bg-gradient-to-br from-amber-50 to-amber-50/50 dark:from-amber-900/20 dark:to-amber-900/10 p-4 shadow-sm">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-amber-800 dark:text-amber-300 mb-1">Nota: Peraturan Status Tidak Aktif</p>
                            <p class="text-xs text-amber-700/90 dark:text-amber-400 leading-relaxed">
                                Ahli yang membuat bayaran dianggap <strong>aktif</strong> sepanjang tempoh liputan ditambah 1 tahun <em>grace</em>.
                                Sekiranya tiada pembayaran dalam tempoh tersebut, ahli perlu mendaftar semula sebagai ahli baru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    const TIDAK_AKTIF_URL = @json(route('dashboard.tidak-aktif'));
    const TIDAK_AKTIF_TOTAL = {{ $tidakAktifCount }};
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatNoKp(noKp) {
        const digits = String(noKp || '').replace(/\D/g, '');
        if (digits.length === 12) {
            return digits.slice(0, 6) + '-' + digits.slice(6, 8) + '-' + digits.slice(8);
        }
        return noKp || '—';
    }

    function getInitials(name) {
        const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
        if (parts.length === 0) return '?';
        if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
        return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    }

    function renderSkeleton() {
        return Array.from({ length: 3 }, function () {
            return '<div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700 skeleton-pulse">'
                + '<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 shrink-0"></div>'
                + '<div class="flex-1 space-y-2">'
                + '<div class="h-3.5 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>'
                + '<div class="h-3 bg-gray-100 dark:bg-gray-600 rounded w-1/2"></div>'
                + '</div>'
                + '<div class="h-6 w-20 bg-gray-100 dark:bg-gray-600 rounded-full"></div>'
                + '</div>';
        }).join('');
    }

    function renderOfficerRow(officer) {
        const lastPayment = officer.last_payment
            ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Bayaran Akhir: ' + escapeHtml(officer.last_payment) + '</span>'
            : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Tiada</span>';

        return '<div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-amber-50/50 dark:hover:bg-amber-900/10 transition-colors">'
            + '<div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-white font-bold text-sm flex items-center justify-center shrink-0">' + escapeHtml(getInitials(officer.nama)) + '</div>'
            + '<div class="flex-1 min-w-0">'
            + '<p class="text-sm font-bold text-gray-900 dark:text-white truncate">' + escapeHtml(officer.nama) + '</p>'
            + '<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono">' + escapeHtml(formatNoKp(officer.no_kp)) + '</p>'
            + '</div>'
            + '<div class="shrink-0">' + lastPayment + '</div>'
            + '</div>';
    }

    function renderResults(data) {
        if (!data || data.length === 0) {
            return '<div class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-500">'
                + '<svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                + '<p class="text-sm font-medium">Tiada pegawai ditemui</p>'
                + '</div>';
        }

        return '<div class="space-y-2">' + data.map(renderOfficerRow).join('') + '</div>';
    }

    function fetchTidakAktif(search) {
        const params = new URLSearchParams();
        if (search) {
            params.set('search', search);
        }

        return fetch(TIDAK_AKTIF_URL + (params.toString() ? '?' + params.toString() : ''), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            credentials: 'same-origin',
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Request failed');
            }
            return response.json();
        });
    }

    function buildModalHtml() {
        return '<div class="text-left">'
            + '<div class="relative mb-4">'
            + '<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
            + '<input type="text" id="tidakAktifSearch" placeholder="Cari nama atau No. KP..." class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition" autocomplete="off">'
            + '</div>'
            + '<div id="tidakAktifResults" class="max-h-80 overflow-y-auto">' + renderSkeleton() + '</div>'
            + '<p class="text-xs text-gray-400 dark:text-gray-500 mt-3 text-center">Menunjukkan sehingga 5 hasil — gunakan carian untuk menapis.</p>'
            + '</div>';
    }

    function loadTidakAktifResults(search) {
        const container = document.getElementById('tidakAktifResults');
        if (!container) return;

        container.innerHTML = renderSkeleton();

        fetchTidakAktif(search)
            .then(function (json) {
                container.innerHTML = renderResults(json.data || []);
            })
            .catch(function () {
                container.innerHTML = '<div class="text-center py-8 text-sm text-red-500">Ralat memuatkan data. Sila cuba lagi.</div>';
            });
    }

    function openTidakAktifModal() {
        Swal.fire({
            title: '<div class="flex flex-wrap items-center justify-center gap-2">'
                + '<span class="text-lg font-bold text-gray-900 dark:text-white">Pegawai Tidak Aktif</span>'
                + '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">'
                + TIDAK_AKTIF_TOTAL.toLocaleString('ms-MY') + ' pegawai</span></div>',
            html: buildModalHtml(),
            width: 640,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-2xl',
                htmlContainer: 'text-left',
            },
            didOpen: function () {
                const searchInput = document.getElementById('tidakAktifSearch');
                let debounceTimer = null;

                loadTidakAktifResults('');

                if (searchInput) {
                    searchInput.focus();
                    searchInput.addEventListener('input', function () {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(function () {
                            loadTidakAktifResults(searchInput.value.trim());
                        }, 300);
                    });
                }
            },
        });
    }

    const tidakAktifTrigger = document.getElementById('tidakAktifTrigger');
    if (tidakAktifTrigger) {
        tidakAktifTrigger.addEventListener('click', openTidakAktifModal);
        tidakAktifTrigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openTidakAktifModal();
            }
        });
    }

    function animateCounter(el, target, duration) {
        if (target === 0) { el.textContent = '0'; return; }
        const start = performance.now();
        function tick(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(eased * target).toLocaleString('ms-MY');
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    document.querySelectorAll('.counter').forEach(function (el) {
        animateCounter(el, parseInt(el.dataset.target, 10) || 0, 900);
    });

    setTimeout(function () {
        document.querySelectorAll('.progress-bar').forEach(function (bar) {
            bar.style.width = bar.dataset.width;
        });
    }, 200);

    const canvas = document.getElementById('memberStatusChart');
    if (!canvas) return;

    const isDark = document.documentElement.classList.contains('dark')
        || window.matchMedia('(prefers-color-scheme: dark)').matches;

    const AKTIF       = {{ $aktifCount }};
    const TIDAK_AKTIF = {{ $tidakAktifCount }};
    const MENINGGAL   = {{ $meninggalCount }};
    const TOTAL       = {{ $totalCount }};

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
