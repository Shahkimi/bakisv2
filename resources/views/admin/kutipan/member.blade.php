@extends('layouts.app')

@section('title', 'Kutipan')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-3">
        <nav class="text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('admin.kutipan.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 hover:underline">Kutipan Yuran</a>
            <span class="px-2">/</span>
            <span class="text-gray-700 dark:text-gray-200 font-semibold">{{ $member['nama'] ?? 'Ahli' }}</span>
        </nav>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4 group">
                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 13c0 1.657-1.79 3-4 3s-4-1.343-4-3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $member['nama'] ?? 'Kutipan' }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Rekod pembayaran & kutipan yuran pembaharuan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            <svg class="h-5 w-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Member Panel --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Maklumat Ahli</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Rekod pembayaran & status pembaharuan.</p>
                        </div>
                        <div id="memberAvatar" class="member-avatar w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-sm">
                            AH
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nama</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white text-right">{{ $member['nama'] ?? '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No. KP</span>
                            <span class="text-sm font-mono text-gray-900 dark:text-white">{{ $member['no_kp'] ?? '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No. Ahli</span>
                            <span class="text-sm font-mono text-gray-900 dark:text-white">{{ $member['no_ahli'] ?: '—' }}</span>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 px-4 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">Status Pembaharuan</div>
                            @php($eligible = (bool) ($renewal['eligible'] ?? false))
                            @php($badgeClass = $eligible
                                ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-600'
                                : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 ring-amber-200 dark:ring-amber-600')
                            @php($badgeLabel = $eligible ? 'Tahun Tertunggak' : 'Tidak diperlukan')
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $renewal['message'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment History Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Rekod Pembayaran
                        </h2>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Senarai pembayaran terkini untuk ahli ini.</p>
                    </div>
                    <span id="historyCountBadge" class="hidden inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-200 dark:ring-indigo-600"></span>
                </div>
                <div id="historyList" class="p-4 space-y-2 max-h-[520px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-700"></div>
                <div id="historyEmpty" class="hidden flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiada rekod pembayaran</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Rekod akan muncul selepas kutipan dibuat.</p>
                </div>
            </div>
        </div>

        {{-- Collect Panel --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Kutip & Rekod Bayaran</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Sistem akan auto-sahkan dan aktifkan ahli serta-merta.
                            </p>
                        </div>
                        @php($maxSelectableYear = (int) date('Y') + \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD)
                        @php($canCollect = count($unpaid_years ?? []) > 0)
                        @php($firstUnpaidYear = !empty($unpaid_years) ? $unpaid_years[0] : null)
                        @php($unpaidCount = count($unpaid_years ?? []))
                        @php($tooltipUnpaidYears = array_values(array_slice($unpaid_years ?? [], 1)))
                        @php($tooltipUnpaidCount = count($tooltipUnpaidYears))
                        @if($canCollect)
                            <div id="collectLockBadge" class="relative inline-flex items-center group">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-600 cursor-help">
                                    Tahun Tertunggak - {{ $firstUnpaidYear }}
                                </span>

                                @if($tooltipUnpaidCount > 0)
                                    <div class="absolute top-full right-0 mt-2 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 pointer-events-none">
                                        <div class="bg-gray-900 dark:bg-gray-700 text-white rounded-lg shadow-xl p-3 text-xs">
                                            <div class="font-semibold mb-2 text-emerald-300">Tahun Tertunggak Lagi ({{ $tooltipUnpaidCount }})</div>
                                            <ul class="space-y-1 list-none">
                                                @foreach($tooltipUnpaidYears as $year)
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                        <span>{{ $year }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="absolute -top-1 right-4 w-2 h-2 bg-gray-900 dark:bg-gray-700 transform rotate-45"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <form id="collectForm" class="mt-5 space-y-5" enctype="multipart/form-data">
                        <input type="hidden" name="member_id" id="member_id" value="{{ (int) ($member['id'] ?? 0) }}">

                        {{-- Section: Butiran bayaran --}}
                        <section class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-5" aria-labelledby="section-bayaran">
                            <h3 id="section-bayaran" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                                Butiran bayaran
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="tahun_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Bayar <span class="text-red-500">*</span></label>
                                    <input type="number" name="tahun_bayar" id="tahun_bayar" value="{{ date('Y') }}" min="2000" max="2100" readonly
                                           class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                </div>

                                <div class="space-y-2">
                                    <label for="yuran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Yuran <span class="text-red-500">*</span></label>
                                    <select name="yuran_id" id="yuran_id"
                                            class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                        @foreach($yurans as $yuran)
                                            <option value="{{ $yuran->id }}" data-tempoh-tahun="{{ (int) $yuran->tempoh_tahun }}" data-jumlah="{{ (float) $yuran->jumlah }}">
                                                {{ $yuran->jenis_yuran }} - RM {{ number_format((float) $yuran->jumlah, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tahun untuk Pembaharuan <span class="text-red-500">*</span>
                                    </label>

                                    <div id="yearFieldsContainer" class="space-y-2"></div>

                                    <button type="button" id="btnAddYear"
                                            class="inline-flex items-center gap-2 rounded-lg border-2 border-dashed border-indigo-300 dark:border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 px-4 py-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Tambah Tahun
                                    </button>

                                    @if(! $canCollect)
                                        <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                            Tiada tahun tertunggak atau prabayar dalam julat sistem (2020–{{ $maxSelectableYear }})@if(! empty($renewal_min_year)), atau selepas tahun Pendaftaran Keahlian (mulai {{ $renewal_min_year }})@endif.
                                        </p>
                                    @else
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            @if(! empty($renewal_min_year))
                                                <span class="block mb-1">Tahun pembaharuan dipaparkan bermula <span class="font-medium text-gray-700 dark:text-gray-200">{{ $renewal_min_year }}</span> (selepas tahun liputan Pendaftaran Keahlian).</span>
                                            @endif
                                            <span class="block mb-1">Anda boleh pilih sehingga <span class="font-medium text-gray-700 dark:text-gray-200">{{ $maxSelectableYear }}</span> (termasuk sehingga {{ \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD }} tahun hadapan berbanding tahun semasa) untuk kutipan prabayar jika diperlukan.</span>
                                            Klik &ldquo;Tambah Tahun&rdquo; untuk pilih tahun tambahan. Sistem akan cipta rekod berasingan untuk setiap tahun.
                                        </p>
                                    @endif
                                </div>

                                <div class="space-y-2 sm:col-span-2">
                                    <div class="rounded-xl border border-indigo-200 dark:border-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-4 py-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Bayaran</span>
                                            <span id="totalAmount" class="text-lg font-bold text-indigo-600 dark:text-indigo-400">RM 0.00</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span id="yearCount">0</span> tahun dipilih × <span id="yuranUnitLabel">RM 10.00</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Section: Resit & bukti --}}
                        <section class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-5" aria-labelledby="section-resit">
                            <h3 id="section-resit" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">2</span>
                                Resit & bukti
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="no_resit_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        No. Resit / Rujukan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="no_resit_transfer" id="no_resit_transfer"
                                           placeholder="Rujukan bank atau no. resit"
                                           class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                </div>

                                <div class="space-y-2">
                                    <label for="catatan_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Catatan (pilihan)
                                    </label>
                                    <input type="text" name="catatan_admin" id="catatan_admin"
                                           placeholder="Contoh: Pindahan melalui ..."
                                           class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <label for="bukti_bayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Bukti bayaran (PDF/JPG/PNG)
                                </label>
                                <div class="relative rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/30 p-4 transition hover:border-indigo-400 dark:hover:border-indigo-500 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20">
                                    <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf"
                                           class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 file:font-medium file:cursor-pointer focus:outline-none">
                                </div>
                            </div>
                        </section>

                        {{-- CTA --}}
                        <section class="rounded-xl border-2 border-indigo-200 dark:border-indigo-500/50 bg-indigo-50/70 dark:bg-indigo-900/25 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Tindakan
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        Anda sahkan kutipan untuk ahli yang dipilih. Resit sistem akan dijana selepas berjaya.
                                    </p>
                                </div>
                                <button id="btnCollect" type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18a9 9 0 100-18 9 9 0 000 18z"/>
                                    </svg>
                                    Kutip Bayaran
                                </button>
                            </div>

                            <div id="collectInlineResult" class="hidden mt-4 rounded-xl border px-4 py-3 text-sm"></div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const canCollect = @json($canCollect ?? false);
        const unpaidYears = @json($unpaid_years ?? []);
        const currentCalendarYear = @json((int) date('Y'));
        const historyData = @json($history ?? []);

        const memberAvatar = document.getElementById('memberAvatar');
        const historyList = document.getElementById('historyList');
        const historyEmpty = document.getElementById('historyEmpty');

        const collectForm = document.getElementById('collectForm');
        const btnCollect = document.getElementById('btnCollect');
        const collectLockBadge = document.getElementById('collectLockBadge');
        const collectInlineResult = document.getElementById('collectInlineResult');

        const yuranSelect = document.getElementById('yuran_id');
        const totalAmountDisplay = document.getElementById('totalAmount');
        const yearCountDisplay = document.getElementById('yearCount');
        const yuranUnitLabel = document.getElementById('yuranUnitLabel');
        const yearFieldsContainer = document.getElementById('yearFieldsContainer');
        const btnAddYear = document.getElementById('btnAddYear');

        let yearFieldIndex = 0;

        function escapeHtml(s) {
            if (s == null) return '';
            return String(s)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function initials(nama) {
            const parts = String(nama || '').trim().split(/\s+/).filter(Boolean);
            const firstTwo = parts.slice(0, 2).map(p => p[0]).join('');
            return firstTwo ? firstTwo.toUpperCase() : 'AH';
        }

        function setCollectEnabled(enabled) {
            if (!collectForm) return;
            collectForm.querySelectorAll('input, select, button[type="submit"], button#btnAddYear').forEach(el => {
                el.disabled = !enabled;
            });
            // Badge is rendered only when $canCollect=true; still keep logic aligned with "hide when disabled".
            if (collectLockBadge) collectLockBadge.classList.toggle('hidden', !enabled);
        }

        function getJumlahPerYear() {
            const selected = yuranSelect?.selectedOptions && yuranSelect.selectedOptions[0];
            const jumlahPerYear = parseFloat(selected?.dataset?.jumlah || '10');
            return Number.isFinite(jumlahPerYear) ? jumlahPerYear : 10;
        }

        function updateYuranUnitLabel() {
            const j = getJumlahPerYear();
            if (yuranUnitLabel) {
                yuranUnitLabel.textContent = 'RM ' + j.toFixed(2);
            }
        }

        function createYearField(defaultYear = null) {
            if (!yearFieldsContainer) return;

            const fieldId = 'year_' + (++yearFieldIndex);
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center gap-2';
            wrapper.dataset.fieldId = fieldId;

            const select = document.createElement('select');
            select.name = 'years[]';
            select.id = fieldId;
            select.required = true;
            select.className = 'flex-1 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Pilih tahun...';
            placeholder.disabled = true;
            placeholder.selected = true;
            select.appendChild(placeholder);

            unpaidYears.forEach(year => {
                const opt = document.createElement('option');
                opt.value = String(year);
                const y = Number(year);
                opt.textContent = y > currentCalendarYear
                    ? String(year) + ' (prabayar)'
                    : String(year);
                if (defaultYear !== null && Number(year) === Number(defaultYear)) {
                    opt.selected = true;
                    placeholder.selected = false;
                }
                select.appendChild(opt);
            });

            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.className = 'flex-shrink-0 h-11 w-11 rounded-lg border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition flex items-center justify-center';
            btnRemove.setAttribute('aria-label', 'Buang tahun');
            btnRemove.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            btnRemove.onclick = () => {
                wrapper.remove();
                updateTotal();
            };

            wrapper.appendChild(select);
            wrapper.appendChild(btnRemove);
            yearFieldsContainer.appendChild(wrapper);

            select.addEventListener('change', updateTotal);
            updateTotal();
        }

        function updateTotal() {
            const selects = yearFieldsContainer?.querySelectorAll('select[name="years[]"]') || [];
            const selectedYears = Array.from(selects)
                .map(s => parseInt(s.value, 10))
                .filter(y => Number.isFinite(y));

            const count = selectedYears.length;
            const jumlah = getJumlahPerYear();
            const total = count * jumlah;

            if (totalAmountDisplay) totalAmountDisplay.textContent = 'RM ' + total.toFixed(2);
            if (yearCountDisplay) yearCountDisplay.textContent = String(count);
        }

        function renderHistory(history) {
            if (!historyList || !historyEmpty) return;
            historyList.innerHTML = '';

            if (!history || history.length === 0) {
                historyEmpty.classList.remove('hidden');
                const badge = document.getElementById('historyCountBadge');
                if (badge) badge.classList.add('hidden');
                return;
            }

            historyEmpty.classList.add('hidden');

            // Update count badge
            const badge = document.getElementById('historyCountBadge');
            if (badge) {
                badge.textContent = history.length + ' rekod';
                badge.classList.remove('hidden');
            }

            history.forEach(row => {
                const status = row.status;
                const statusLabel = status === 'approved' ? 'Disahkan'
                    : status === 'pending' ? 'Menunggu' : 'Ditolak';

                const statusBadge = status === 'approved'
                    ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-600'
                    : status === 'pending'
                        ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 ring-amber-200 dark:ring-amber-600'
                        : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 ring-red-200 dark:ring-red-600';

                const stripColor = status === 'approved'
                    ? 'bg-emerald-400'
                    : status === 'pending'
                        ? 'bg-amber-400' : 'bg-red-400';

                const iconBg = status === 'approved'
                    ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400'
                    : status === 'pending'
                        ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'
                        : 'bg-red-100 dark:bg-red-900/40 text-red-500 dark:text-red-400';

                const statusIcon = status === 'approved'
                    ? `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`
                    : status === 'pending'
                        ? `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
                        : `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>`;

                const yearRange = row.tahunmula !== row.tahuntamat
                    ? `<span class="font-normal text-gray-400 dark:text-gray-500">(${escapeHtml(row.tahunmula)} – ${escapeHtml(row.tahuntamat)})</span>`
                    : '';

                const approvedRow = row.approvedat ? `
                    <div class="col-span-2 pt-1">
                        <div class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Tarikh Disahkan</div>
                        <div class="text-xs text-gray-600 dark:text-gray-300">${escapeHtml(row.approvedat)}</div>
                    </div>` : '';

                const el = document.createElement('div');
                el.className = 'group relative rounded-xl border border-gray-100 dark:border-gray-700/80 bg-gray-50/60 dark:bg-gray-900/30 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm transition-all duration-200 overflow-hidden';
                el.innerHTML = `
                    <div class="absolute left-0 top-0 bottom-0 w-1 ${stripColor}"></div>
                    <div class="p-3 pl-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-2.5 min-w-0">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg ${iconBg} flex items-center justify-center mt-0.5">
                                    ${statusIcon}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">
                                        Tahun ${escapeHtml(row.tahunbayar)} ${yearRange}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        ${escapeHtml(row.jenislabel)}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset ${statusBadge}">
                                    ${escapeHtml(statusLabel)}
                                </span>
                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                    ${escapeHtml(row.jumlahformatted)}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2.5 pt-2.5 border-t border-gray-200/60 dark:border-gray-700/60 grid grid-cols-2 gap-x-4 gap-y-1.5">
                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">No. Resit</div>
                                <div class="text-xs font-mono font-medium text-gray-700 dark:text-gray-200">
                                    ${escapeHtml(row.noresitsistem) || '—'}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Rujukan</div>
                                <div class="text-xs font-mono font-medium text-gray-700 dark:text-gray-200">
                                    ${escapeHtml(row.noresittransfer) || '—'}
                                </div>
                            </div>
                            ${approvedRow}
                        </div>
                    </div>
                `;
                historyList.appendChild(el);
            });
        }

        async function doCollect(ev) {
            ev.preventDefault();

            if (!canCollect) {
                Swal.fire({ icon: 'warning', title: 'Tidak boleh kutip', text: 'Tiada tahun tertunggak untuk dikutip dalam julat sistem.' });
                return;
            }

            if (!collectForm || !btnCollect || !yearFieldsContainer) return;

            const selects = yearFieldsContainer.querySelectorAll('select[name="years[]"]');
            const selectedYears = Array.from(selects)
                .map(s => parseInt(s.value, 10))
                .filter(y => Number.isFinite(y));

            if (selectedYears.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Tiada tahun dipilih', text: 'Sila pilih sekurang-kurangnya satu tahun.' });
                return;
            }

            const uniqueYears = [...new Set(selectedYears)];
            if (uniqueYears.length !== selectedYears.length) {
                Swal.fire({ icon: 'warning', title: 'Tahun pendua', text: 'Terdapat tahun yang dipilih lebih daripada sekali.' });
                return;
            }

            btnCollect.disabled = true;
            collectInlineResult?.classList.add('hidden');

            const formData = new FormData(collectForm);
            formData.append('_token', csrfToken);

            try {
                const res = await fetch(@json(route('admin.kutipan.collect-multi-year')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (!res.ok || data?.success !== true) {
                    let msg = data?.message || 'Gagal merekod bayaran.';
                    if (res.status === 422 && data?.errors) {
                        const flat = Object.values(data.errors).flat();
                        if (flat.length) {
                            msg = flat.join(' ');
                        }
                    }
                    if (collectInlineResult) {
                        collectInlineResult.className = 'mt-4 rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200';
                        collectInlineResult.textContent = msg;
                        collectInlineResult.classList.remove('hidden');
                    }
                    return;
                }

                const yearsLabel = Array.isArray(data.years) ? data.years.join(', ') : '';
                await Swal.fire({
                    icon: 'success',
                    title: 'Berjaya',
                    html: '<div class="text-sm">' +
                        '<p class="font-semibold">Resit: ' + escapeHtml(String(data.receipt_no || '—')) + '</p>' +
                        (yearsLabel ? '<p class="mt-1">Tahun: ' + escapeHtml(yearsLabel) + '</p>' : '') +
                        '<p class="mt-1">Jumlah: RM ' + escapeHtml(String(data.total_amount || '')) + '</p>' +
                        '</div>',
                    timer: 3500,
                    showConfirmButton: false
                });

                window.location.reload();
            } catch (e) {
                console.error(e);
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            } finally {
                btnCollect.disabled = false;
            }
        }

        memberAvatar.textContent = initials(@json($member['nama'] ?? ''));

        yuranSelect?.addEventListener('change', () => {
            updateYuranUnitLabel();
            updateTotal();
        });
        updateYuranUnitLabel();

        if (unpaidYears.length > 0) {
            createYearField(unpaidYears[0]);
        }

        btnAddYear?.addEventListener('click', () => createYearField());

        renderHistory(historyData);
        setCollectEnabled(canCollect);

        collectForm?.addEventListener('submit', doCollect);
    })();
</script>
@endpush
@endsection

