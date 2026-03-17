@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- Page Header --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Pembayaran</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus dan sahkan transaksi pembayaran ahli</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20 px-4 py-3 text-sm text-green-800 dark:text-green-200">
            <svg class="h-5 w-5 shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            <svg class="h-5 w-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">

            {{-- Status Pill Switcher --}}
            <div class="inline-flex items-center rounded-xl bg-gray-100 dark:bg-gray-700/60 p-1 gap-0.5" id="statusPillGroup">
                @php
                    $pills = [
                        'pending'  => ['label'=>'Menunggu', 'color'=>'amber'],
                        'approved' => ['label'=>'Disahkan', 'color'=>'green'],
                        'rejected' => ['label'=>'Ditolak',  'color'=>'red'],
                        'all'      => ['label'=>'Semua',    'color'=>'indigo'],
                    ];
                @endphp
                @foreach($pills as $val => $cfg)
                    <button type="button"
                        data-status="{{ $val }}"
                        class="status-pill relative px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 focus:outline-none
                               {{ $statusFilter === $val
                                  ? 'bg-white dark:bg-gray-800 shadow-sm text-gray-900 dark:text-white ring-1 ring-gray-200 dark:ring-gray-600'
                                  : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        <span class="flex items-center gap-1.5">
                            @if($val !== 'all')
                                <span class="h-1.5 w-1.5 rounded-full
                                    {{ $cfg['color'] === 'amber' ? 'bg-amber-400' : '' }}
                                    {{ $cfg['color'] === 'green' ? 'bg-green-400' : '' }}
                                    {{ $cfg['color'] === 'red'   ? 'bg-red-400' : '' }}
                                "></span>
                            @endif
                            {{ $cfg['label'] }}
                            @if($val === 'pending')
                                <span id="pendingCountBadge" class="hidden ml-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-bold transition-all duration-200">
                                    <span class="pending-count"></span>
                                </span>
                            @endif
                        </span>
                    </button>
                @endforeach
            </div>
            <input type="hidden" id="statusFilterSelect" value="{{ $statusFilter }}">

            {{-- Divider --}}
            <div class="h-8 w-px bg-gray-200 dark:bg-gray-600 hidden sm:block"></div>

            {{-- Jabatan Filter --}}
            <div class="jabatan-filter-wrap relative shrink-0" style="min-width:220px;">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400 dark:text-gray-500 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <select id="jabatanFilter" class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 pl-10 pr-4 py-2.5 text-sm text-gray-800 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">Semua Jabatan</option>
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
                <span id="filterIndicator" class="absolute -top-1 -right-1 hidden h-4 w-4 items-center justify-center rounded-full bg-indigo-500 text-white text-[9px] font-bold" aria-hidden="true" title="Filter aktif">●</span>
            </div>
        </div>

        {{-- Status Indicator --}}
        <div class="flex items-center gap-2 shrink-0">
            <div id="loadingSpinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-indigo-400 border-t-transparent"></div>
            <span id="statusBadge" class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300 transition-all duration-300">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Siap
            </span>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">

        <div id="pembayaranTableError" class="hidden px-4 py-3 border-b border-red-200 bg-red-50 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200 flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span id="pembayaranTableErrorMsg"></span>
        </div>

        <div class="w-full min-w-0 p-4 pt-5">
            <div class="w-full overflow-x-auto rounded-xl">
                <table class="w-full min-w-full" id="pembayaran-table" style="width:100%;">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200" style="width:30%;min-width:180px;">Maklumat Ahli</th>
                            <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200" style="width:30%;min-width:180px;">No Resit / Rujukan</th>
                            <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200" style="width:15%;min-width:100px;">Status</th>
                            <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-200" style="width:25%;min-width:160px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bukti Lightbox Modal --}}
<div id="buktiModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="buktiModalTitle">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="buktiModalOverlay"></div>
    <div class="relative z-10 w-full max-w-2xl rounded-2xl bg-white dark:bg-gray-900 shadow-2xl overflow-hidden flex flex-col">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40">
                    <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span id="buktiModalTitle" class="text-sm font-semibold text-gray-800 dark:text-white">Bukti Pembayaran</span>
            </div>
            <button id="buktiModalClose" class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors focus:outline-none" aria-label="Tutup">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Image Area --}}
        <div class="relative flex min-h-64 items-center justify-center bg-gray-50 dark:bg-gray-800/50 p-4">
            <div id="buktiLoading" class="absolute inset-0 flex items-center justify-center">
                <div class="h-8 w-8 animate-spin rounded-full border-2 border-indigo-400 border-t-transparent"></div>
            </div>
            <img id="buktiImg" src="" alt="Bukti Pembayaran"
                class="hidden max-h-[70vh] max-w-full rounded-xl object-contain shadow-md ring-1 ring-gray-200 dark:ring-gray-700"
                onload="document.getElementById('buktiLoading').classList.add('hidden');this.classList.remove('hidden');"
                onerror="document.getElementById('buktiLoading').classList.add('hidden');document.getElementById('buktiImgError').classList.remove('hidden');">
            <iframe id="buktiFrame" src="" title="Bukti Pembayaran"
                class="hidden h-[70vh] w-full rounded-xl bg-white dark:bg-gray-900 shadow-md ring-1 ring-gray-200 dark:ring-gray-700"
                loading="lazy"
                referrerpolicy="no-referrer"></iframe>
            <div id="buktiImgError" class="hidden flex-col items-center gap-3 text-center">
                <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gambar tidak dapat dipaparkan</p>
            </div>
        </div>
        {{-- Footer --}}
        <div class="flex justify-end gap-2 border-t border-gray-100 dark:border-gray-700 px-5 py-3">
            <a id="buktiDownloadLink" href="#" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Buka Penuh
            </a>
            <button id="buktiModalCloseBtn"
                class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
.hidden-scrollbar::-webkit-scrollbar { width:6px; height:6px; }
.hidden-scrollbar::-webkit-scrollbar-track { background:transparent; }
.hidden-scrollbar::-webkit-scrollbar-thumb { background-color:rgba(156,163,175,.5); border-radius:20px; }
.dark .hidden-scrollbar::-webkit-scrollbar-thumb { background-color:rgba(75,85,99,.5); }
</style>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"/>
<style>
/* ── DataTable Core ── */
table.dataTable { border-collapse:collapse !important; width:100% !important; }
table.dataTable thead th { padding:0.875rem 1rem; border:0; vertical-align:middle; }
#pembayaran-table thead tr { background:#f9fafb !important; }
#pembayaran-table thead th { background:transparent !important; color:#374151 !important; }
.dark #pembayaran-table thead tr { background:rgba(55,65,81,.5) !important; }
.dark #pembayaran-table thead th { color:#e5e7eb !important; }
table.dataTable tbody tr { background:#fff; border-bottom:1px solid #e5e7eb; transition:background .15s; }
table.dataTable tbody tr:hover { background:#f5f3ff; }
.dark table.dataTable tbody tr { background:#1f2937; border-bottom-color:#374151; }
.dark table.dataTable tbody tr:hover { background:#312e81/10; }
table.dataTable tbody td { padding:.875rem 1rem; vertical-align:middle; font-size:.875rem; color:#111827; }
.dark table.dataTable tbody td { color:#f3f4f6; }
table.dataTable tbody tr:last-child td { border-bottom:0; }

/* ── Wrapper Controls ── */
.dataTables_wrapper { font-size:.875rem; }
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter { padding:0.5rem 0; }
.dataTables_wrapper .dataTables_filter { text-align:right; }
.dataTables_wrapper > .row:first-child { margin-bottom:1rem; align-items:center; }
.dataTables_wrapper table.dataTable { margin-top:0 !important; }
.dataTables_wrapper .dataTables_filter label { display:inline-flex; align-items:center; gap:.5rem; color:#374151; font-weight:500; }
.dataTables_wrapper .dataTables_filter input {
    border:2px solid #e5e7eb !important; border-radius:.75rem !important; padding:.5rem 1rem !important;
    background:#fff !important; color:#111827 !important; outline:none !important;
    font-size:.875rem !important; min-width:12rem; transition:border .2s, box-shadow .2s;
}
.dataTables_wrapper .dataTables_filter input:focus { border-color:#4f46e5 !important; box-shadow:0 0 0 3px rgba(79,70,229,.2) !important; }
.dark .dataTables_wrapper .dataTables_filter input { border-color:#4b5563 !important; background:#374151 !important; color:#f3f4f6 !important; }
.dataTables_wrapper .dataTables_length label { display:inline-flex; align-items:center; gap:.5rem; color:#374151; font-weight:500; }
.dataTables_wrapper .dataTables_length select {
    border:2px solid #e5e7eb !important; border-radius:.75rem !important; padding:.5rem 2rem .5rem .75rem !important;
    background:#fff !important; color:#111827 !important; cursor:pointer !important; font-size:.875rem !important;
}
.dataTables_wrapper .dataTables_length select:focus { border-color:#4f46e5 !important; outline:none !important; }
.dark .dataTables_wrapper .dataTables_length select { border-color:#4b5563 !important; background:#374151 !important; color:#f3f4f6 !important; }
.dataTables_wrapper .dataTables_info { color:#6b7280; padding-top:1rem; font-size:.875rem; }
.dark .dataTables_wrapper .dataTables_info { color:#9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top:1rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button {
    display:inline-flex !important; align-items:center; justify-content:center;
    min-width:2.25rem; height:2.25rem; padding:0 .5rem;
    margin:0 2px; border-radius:.5rem !important; border:2px solid #e5e7eb !important;
    background:#fff !important; color:#374151 !important; font-size:.875rem !important; font-weight:500;
    cursor:pointer; transition:all .2s;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#f3f4f6 !important; border-color:#d1d5db !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background:#4f46e5 !important; color:#fff !important; border-color:#4f46e5 !important; font-weight:600; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity:.45; cursor:not-allowed; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background:#374151 !important; border-color:#4b5563 !important; color:#d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#4b5563 !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background:#4f46e5 !important; color:#fff !important; border-color:#4f46e5 !important; }
.dataTables_wrapper .dataTables_processing { background:rgba(255,255,255,.9); border-radius:.75rem; border:1px solid #e5e7eb; box-shadow:0 4px 12px rgba(0,0,0,.08); color:#6366f1; font-weight:600; }

/* ── Select2 Jabatan ── */
.jabatan-filter-wrap .select2-container { width:100% !important; }
.jabatan-filter-wrap svg[aria-hidden="true"] { z-index:10; }
.jabatan-filter-wrap .select2-container .select2-selection--single {
    min-height:2.625rem; padding-left:2.25rem; border-radius:.75rem;
    border:1px solid #e5e7eb; background:#fff; font-size:.875rem; color:#111827;
    box-shadow:0 1px 2px rgba(0,0,0,.05);
}
.jabatan-filter-wrap .select2-container--focus .select2-selection--single,
.jabatan-filter-wrap .select2-container--open  .select2-selection--single
    { border-color:#6366f1 !important; box-shadow:0 0 0 3px rgba(99,102,241,.15) !important; }
.jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__rendered { padding-left:0; line-height:2.1rem; }
.jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__arrow { height:2.625rem; right:.5rem; }
.dark .jabatan-filter-wrap .select2-container .select2-selection--single { background:#374151; border-color:#4b5563; color:#f3f4f6; }
.dark .jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__rendered { color:#f3f4f6; }
.dark .jabatan-filter-wrap .select2-container .select2-dropdown { background:#1f2937; border:1px solid #4b5563; border-radius:.75rem; }
.dark .jabatan-filter-wrap .select2-container .select2-results__option { color:#f3f4f6; }
.dark .jabatan-filter-wrap .select2-container .select2-search--dropdown .select2-search__field { background:#374151; border-color:#4b5563; color:#f3f4f6; border-radius:.375rem; padding:.375rem .5rem; }
.jabatan-filter-wrap .select2-container .select2-dropdown { border:1px solid #e5e7eb; border-radius:.75rem; overflow:hidden; }
.jabatan-filter-wrap .select2-container .select2-search--dropdown .select2-search__field { border:1px solid #e5e7eb; border-radius:.375rem; padding:.375rem .5rem; }

/* ── Status Pill Switcher ── */
.status-pill { cursor:pointer; user-select:none; }

/* ── Avatar ── */
.member-avatar { width:2rem; height:2rem; border-radius:.625rem; display:inline-flex; align-items:center; justify-content:center; font-size:.6875rem; font-weight:700; flex-shrink:0; }

/* ── Bukti modal transition ── */
#buktiModal.flex { display:flex; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const statusInput   = document.getElementById('statusFilterSelect');
    const jabatanSelect = document.getElementById('jabatanFilter');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const statusBadge    = document.getElementById('statusBadge');
    const filterIndicator = document.getElementById('filterIndicator');

    // ── Select2 init ──────────────────────────────────────────────────
    $('#jabatanFilter').select2({
        placeholder: 'Semua Jabatan',
        allowClear: true,
        width: '100%',
        language: {
            noResults:    () => 'Tiada jabatan sepadan',
            searching:    () => 'Mencari...',
            inputTooShort:() => 'Sila taip untuk cari'
        }
    });

    // ── State helpers ─────────────────────────────────────────────────
    function setLoadingState(loading) {
        loadingSpinner?.classList.toggle('hidden', !loading);
        if (!statusBadge) return;
        statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition-all duration-300 ' +
            (loading ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'
                     : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300');
        statusBadge.innerHTML = loading
            ? '<svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memuat...'
            : '<svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Siap';
    }

    function setErrorState() {
        loadingSpinner?.classList.add('hidden');
        if (!statusBadge) return;
        statusBadge.className = 'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300';
        statusBadge.innerHTML = '<svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> Ralat';
    }

    function updateFilterIndicator() {
        if (!filterIndicator) return;
        const hasJabatan = jabatanSelect && jabatanSelect.value !== '';
        filterIndicator.classList.toggle('hidden', !hasJabatan);
        filterIndicator.classList.toggle('inline-flex', hasJabatan);
    }

    // ── Helpers ───────────────────────────────────────────────────────
    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function avatarColor(name) {
        const colors = [
            ['bg-indigo-100 dark:bg-indigo-900/40','text-indigo-700 dark:text-indigo-300'],
            ['bg-violet-100 dark:bg-violet-900/40','text-violet-700 dark:text-violet-300'],
            ['bg-sky-100 dark:bg-sky-900/40','text-sky-700 dark:text-sky-300'],
            ['bg-emerald-100 dark:bg-emerald-900/40','text-emerald-700 dark:text-emerald-300'],
            ['bg-rose-100 dark:bg-rose-900/40','text-rose-700 dark:text-rose-300'],
            ['bg-amber-100 dark:bg-amber-900/40','text-amber-700 dark:text-amber-300'],
        ];
        let h = 0;
        for (let i = 0; i < name.length; i++) h = name.charCodeAt(i) + ((h << 5) - h);
        return colors[Math.abs(h) % colors.length];
    }

    function renderMemberCell(row) {
        const member = row.member || {};
        const nama = escapeHtml(member.nama || '–');
        const noKp = escapeHtml(member.no_kp || '–');
        const initials = nama.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase() || '?';
        const [bg, fg] = avatarColor(nama);
        let editLink = member.id
            ? `<a href="/admin/members/${member.id}/edit" class="mt-1 inline-flex items-center gap-1 text-xs text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 hover:underline">
                   <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                   Edit ahli
               </a>` : '';
        return `<div class="flex items-center gap-2.5">
            <div class="member-avatar ${bg} ${fg}">${initials}</div>
            <div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">${nama}</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 font-mono">${noKp}</div>
                ${editLink}
            </div>
        </div>`;
    }

    function renderResitDetailsCell(row) {
        const noResit = escapeHtml(row.no_resit_transfer || '–');
        const jenis = escapeHtml(row.jenis_label || '–');
        const jumlah = escapeHtml(row.jumlah_formatted || '–');
        const tahun = escapeHtml(String(row.tahun_bayar || '–'));
        const line2 = jenis && jumlah ? `${jenis} - ${jumlah}` : (jumlah || jenis);
        return `<div class="flex flex-col gap-0.5">
            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">${noResit}</span>
            <span class="text-xs text-gray-600 dark:text-gray-400">${line2}</span>
            <span class="text-xs text-gray-500 dark:text-gray-500 font-mono">${tahun}</span>
        </div>`;
    }

    function renderStatusBadge(status) {
        const map = {
            approved: { bg:'bg-emerald-50 dark:bg-emerald-900/30', text:'text-emerald-700 dark:text-emerald-300', label:'Disahkan',
                icon:'<svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' },
            pending:  { bg:'bg-amber-50 dark:bg-amber-900/30',   text:'text-amber-700 dark:text-amber-300',   label:'Menunggu',
                icon:'<svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>' },
            rejected: { bg:'bg-red-50 dark:bg-red-900/30',       text:'text-red-700 dark:text-red-300',       label:'Ditolak',
                icon:'<svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' },
        };
        const s = map[status] || map.rejected;
        return `<span class="inline-flex items-center gap-1.5 rounded-full ${s.bg} ${s.text} px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ring-current/10">${s.icon}${s.label}</span>`;
    }

    function renderStatusWithLihat(row) {
        const statusHtml = renderStatusBadge(row.status);
        if (!row.bukti_bayaran) {
            return `<div class="flex flex-col items-center gap-1">${statusHtml}</div>`;
        }
        const imgUrl = '/admin/pembayaran/' + row.id + '/bukti';
        const lihatBtn = `<button type="button"
            data-bukti-url="${imgUrl}"
            data-member="${escapeHtml((row.member||{}).nama||'')}"
            class="js-bukti-btn group mt-1.5 inline-flex items-center gap-2 rounded-lg border border-indigo-200 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/25 px-3 py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-300 shadow-sm hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 active:scale-[0.98]">
            <svg class="h-4 w-4 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Lihat
        </button>`;
        return `<div class="flex flex-col items-center gap-0.5">${statusHtml}${lihatBtn}</div>`;
    }

    function renderBuktiCell(row) {
        if (!row.bukti_bayaran) return '<span class="text-xs text-gray-300 dark:text-gray-600">—</span>';
        const imgUrl = '/admin/pembayaran/' + row.id + '/bukti';
        return `<button type="button"
            data-bukti-url="${imgUrl}"
            data-member="${escapeHtml((row.member||{}).nama||'')}"
            class="js-bukti-btn group inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 px-2.5 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:border-indigo-300 transition-all">
            <svg class="h-3.5 w-3.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Lihat
        </button>`;
    }

    function renderActionsCell(row) {
        const parts = [];
        if (row.status === 'pending') {
            const approveUrl = '/admin/pembayaran/' + row.id + '/approve';
            const rejectUrl  = '/admin/pembayaran/' + row.id + '/reject';
            parts.push(`<form action="${approveUrl}" method="POST" class="inline js-approve-form">
                <input type="hidden" name="_token" value="${csrfToken}">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-95 px-3 py-1.5 text-xs font-semibold text-white shadow-sm shadow-emerald-500/30 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Sahkan
                </button>
            </form>
            <button type="button" data-reject-url="${rejectUrl}"
                class="js-reject-btn inline-flex items-center gap-1.5 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 active:scale-95 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 transition-all">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                Tolak
            </button>`);
        }
        if (parts.length === 0) return '<span class="text-xs text-gray-300 dark:text-gray-600">—</span>';
        return `<div class="flex flex-wrap items-center justify-center gap-1.5">${parts.join('')}</div>`;
    }

    // ── DataTable ─────────────────────────────────────────────────────
    var table = $('#pembayaran-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.pembayaran.data") }}',
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            data: function (d) {
                d.status = statusInput ? statusInput.value : '{{ $statusFilter }}';
                d.jabatan_filter = jabatanSelect ? jabatanSelect.value : '';
            },
            beforeSend: () => setLoadingState(true),
            complete:   () => setLoadingState(false),
            error: function (xhr) {
                setErrorState();
                const el = document.getElementById('pembayaranTableError');
                const msg = document.getElementById('pembayaranTableErrorMsg');
                if (el && msg) {
                    el.classList.remove('hidden');
                    msg.textContent = 'Gagal memuatkan data. ' + (xhr.responseJSON?.message || (xhr.status ? 'HTTP ' + xhr.status : 'Ralat rangkaian.'));
                }
            }
        },
        columns: [
            { data:'member',             name:'member',             orderable:false, render:(_,__,row) => renderMemberCell(row) },
            { data:'no_resit_transfer',  name:'no_resit_transfer',  render:(_,__,row) => renderResitDetailsCell(row) },
            { data:'status',             name:'status',             className:'text-center', render:(_,__,row) => renderStatusWithLihat(row) },
            { data:'id',                 name:'actions',            orderable:false, searchable:false, className:'text-center', render:(_,__,row) => renderActionsCell(row) },
        ],
        order: [[1,'desc']],
        pageLength: 10,
        initComplete: function () {
            $('.dataTables_filter input').attr('placeholder', 'Cari nama, kad pengenalan…');
        },
        language: {
            processing: '<span class="text-indigo-600 font-medium">Memuatkan...</span>',
            search: '',
            searchPlaceholder: 'Cari…',
            lengthMenu: 'Papar _MENU_ rekod',
            info: 'Rekod _START_–_END_ daripada _TOTAL_',
            infoEmpty: 'Tiada rekod',
            infoFiltered: '(ditapis)',
            paginate: { first:'«', last:'»', next:'›', previous:'‹' },
            zeroRecords: '<div class="py-10 text-center text-sm text-gray-400">Tiada rekod sepadan ditemui.</div>'
        },
        drawCallback: () => updateFilterIndicator()
    });

    // ── Status pill switcher ──────────────────────────────────────────
    document.querySelectorAll('.status-pill').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.status-pill').forEach(b => {
                b.className = b.className.replace('bg-white dark:bg-gray-800 shadow-sm text-gray-900 dark:text-white ring-1 ring-gray-200 dark:ring-gray-600', '');
                b.classList.add('text-gray-500', 'dark:text-gray-400');
            });
            this.classList.remove('text-gray-500', 'dark:text-gray-400');
            this.classList.add('bg-white', 'dark:bg-gray-800', 'shadow-sm', 'text-gray-900', 'dark:text-white', 'ring-1', 'ring-gray-200', 'dark:ring-gray-600');
            if (statusInput) statusInput.value = this.dataset.status;
            table.ajax.reload();
        });
    });

    function updatePendingCount() {
        const jabatanValue = jabatanSelect ? jabatanSelect.value : '';
        const url = '{{ route("admin.pembayaran.pending-count") }}?jabatan_filter=' + encodeURIComponent(jabatanValue);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('pendingCountBadge');
                const badge = document.querySelector('.pending-count');

                if (!container || !badge) {
                    return;
                }

                if (data.count === 0) {
                    container.classList.add('hidden');
                    badge.textContent = '';
                    return;
                }

                container.classList.remove('hidden');
                badge.textContent = data.count;
            })
            .catch(err => console.error('Failed to fetch pending count:', err));
    }

    updatePendingCount();

    if (jabatanSelect) {
        jabatanSelect.addEventListener('change', () => {
            table.ajax.reload();
            updatePendingCount();
        });
    }

    // ── Bukti lightbox ────────────────────────────────────────────────
    const buktiModal   = document.getElementById('buktiModal');
    const buktiImg     = document.getElementById('buktiImg');
    const buktiFrame   = document.getElementById('buktiFrame');
    const buktiImgErr  = document.getElementById('buktiImgError');
    const buktiLoading = document.getElementById('buktiLoading');
    const buktiTitle   = document.getElementById('buktiModalTitle');
    const buktiDl      = document.getElementById('buktiDownloadLink');

    function openBuktiModal(url, memberNama) {
        buktiImg.classList.add('hidden');
        buktiFrame?.classList.add('hidden');
        buktiImgErr.classList.add('hidden');
        buktiImgErr.classList.remove('flex');
        buktiLoading.classList.remove('hidden');
        buktiImg.src = '';
        if (buktiFrame) buktiFrame.src = '';
        buktiTitle.textContent = memberNama ? 'Bukti Pembayaran – ' + memberNama : 'Bukti Pembayaran';
        buktiDl.href = url;
        setTimeout(() => { buktiImg.src = url; }, 60);
        buktiModal.classList.remove('hidden');
        buktiModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeBuktiModal() {
        buktiModal.classList.add('hidden');
        buktiModal.classList.remove('flex');
        buktiImg.src = '';
        if (buktiFrame) buktiFrame.src = '';
        document.body.style.overflow = '';
    }

    // If the proof is a PDF, <img> will fail; fall back to iframe viewer.
    buktiImg?.addEventListener('error', () => {
        if (!buktiFrame || !buktiDl?.href) {
            return;
        }

        buktiLoading.classList.add('hidden');
        buktiImgErr.classList.add('hidden');
        buktiFrame.classList.remove('hidden');
        buktiFrame.src = buktiDl.href;
    });

    $(document).on('click', '.js-bukti-btn', function () {
        openBuktiModal(this.dataset.buktiUrl, this.dataset.member);
    });

    document.getElementById('buktiModalClose')?.addEventListener('click', closeBuktiModal);
    document.getElementById('buktiModalCloseBtn')?.addEventListener('click', closeBuktiModal);
    document.getElementById('buktiModalOverlay')?.addEventListener('click', closeBuktiModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeBuktiModal(); });

    // ── Approve / Reject ──────────────────────────────────────────────
    $(document).on('submit', '.js-approve-form', function () {
        return confirm('Sahkan pembayaran ini? Status ahli akan dikemas kini kepada Aktif.');
    });

    $(document).on('click', '.js-reject-btn', function () {
        const rejectUrl = $(this).data('reject-url');
        Swal.fire({
            title: 'Tolak Pembayaran',
            input: 'textarea',
            inputLabel: 'Catatan (pilihan)',
            inputPlaceholder: 'Sebab penolakan...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            customClass: { popup: 'rounded-2xl' }
        }).then(result => {
            if (!result.isConfirmed) return;
            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            if (result.value) formData.append('catatan_admin', result.value);
            fetch(rejectUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            }).then(res => {
                if (res.ok) {
                    Swal.fire({ icon:'success', title:'Berjaya', text:'Pembayaran telah ditolak.', timer:2000, showConfirmButton:false });
                    table?.ajax.reload(null, false);
                    return;
                }
                Swal.fire({ icon:'error', title:'Ralat', text:'Gagal menolak pembayaran.' });
            }).catch(() => {
                Swal.fire({ icon:'error', title:'Ralat', text:'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });

    $('#pembayaran-table').on('error.dt', function (e, settings, techNote, message) {
        const el  = document.getElementById('pembayaranTableError');
        const msg = document.getElementById('pembayaranTableErrorMsg');
        if (el && msg) { el.classList.remove('hidden'); msg.textContent = 'Gagal memuatkan data pembayaran. ' + (message || ''); }
    });
});
</script>
@endpush
@endsection
