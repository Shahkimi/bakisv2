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
                            @php($badgeLabel = $eligible ? 'Boleh dikutip' : 'Tidak diperlukan')
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $renewal['message'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment History Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Rekod Pembayaran</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Senarai pembayaran terkini untuk ahli ini.</p>
                    <div id="historyList" class="mt-4 space-y-3"></div>
                    <div id="historyEmpty" class="mt-4 text-sm text-gray-500 dark:text-gray-400 hidden">
                        Tiada rekod pembayaran.
                    </div>
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
                        <div id="collectLockBadge" class="inline-flex items-center rounded-xl bg-amber-50 dark:bg-amber-900/30 px-3 py-2 text-xs font-semibold text-amber-800 dark:text-amber-200 {{ $eligible ? 'hidden' : '' }}">
                            Tidak boleh kutip
                        </div>
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
                                            <option value="{{ $yuran->id }}" data-tempoh-tahun="{{ (int) $yuran->tempoh_tahun }}">
                                                {{ $yuran->jenis_yuran }} - RM {{ number_format((float) $yuran->jumlah, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tahun liputan keahlian
                                    </label>
                                    <div class="flex flex-wrap items-center gap-3 mt-1">
                                        <input type="number" name="tahun_mula" id="tahun_mula" value="{{ date('Y') }}" min="2000" max="2100" readonly
                                               class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white">
                                        <span class="text-gray-400 shrink-0">hingga</span>
                                        <input type="number" name="tahun_tamat" id="tahun_tamat" value="{{ date('Y') }}" min="2000" max="2100" readonly
                                               class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Nilai liputan dikira automatik berdasarkan jenis yuran.
                                    </p>
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
        const eligible = @json((bool) ($renewal['eligible'] ?? false));
        const historyData = @json($history ?? []);

        const memberAvatar = document.getElementById('memberAvatar');
        const historyList = document.getElementById('historyList');
        const historyEmpty = document.getElementById('historyEmpty');

        const collectForm = document.getElementById('collectForm');
        const btnCollect = document.getElementById('btnCollect');
        const collectLockBadge = document.getElementById('collectLockBadge');
        const collectInlineResult = document.getElementById('collectInlineResult');

        const tahunBayarInput = document.getElementById('tahun_bayar');
        const yuranSelect = document.getElementById('yuran_id');
        const tahunMulaInput = document.getElementById('tahun_mula');
        const tahunTamatInput = document.getElementById('tahun_tamat');

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
            collectForm.querySelectorAll('input, select, button[type="submit"]').forEach(el => {
                el.disabled = !enabled;
            });
            if (collectLockBadge) collectLockBadge.classList.toggle('hidden', enabled);
        }

        function computeCoverage() {
            const tahunBayar = parseInt(tahunBayarInput?.value || '', 10);
            if (!Number.isFinite(tahunBayar)) return;

            const selected = yuranSelect?.selectedOptions && yuranSelect.selectedOptions[0];
            const tempoh = parseInt(selected?.dataset?.tempohTahun || '1', 10);
            const mula = tahunBayar;
            const tamat = tahunBayar + (tempoh - 1);

            if (tahunMulaInput) tahunMulaInput.value = mula;
            if (tahunTamatInput) tahunTamatInput.value = tamat;
        }

        function renderHistory(history) {
            if (!historyList || !historyEmpty) return;

            historyList.innerHTML = '';
            if (!history || history.length === 0) {
                historyEmpty.classList.remove('hidden');
                return;
            }
            historyEmpty.classList.add('hidden');

            history.forEach(row => {
                const status = row.status || '';
                const statusLabel = status === 'approved' ? 'Disahkan' :
                    (status === 'pending' ? 'Menunggu' :
                        (status === 'rejected' ? 'Ditolak' : status));

                const statusBg = status === 'approved'
                    ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 ring-emerald-200 dark:ring-emerald-600'
                    : (status === 'pending'
                        ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 ring-amber-200 dark:ring-amber-600'
                        : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 ring-red-200 dark:ring-red-600');

                const el = document.createElement('div');
                el.className = 'rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-4';
                el.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                Tahun ${escapeHtml(row.tahun_bayar)}${row.tahun_mula && row.tahun_tamat ? ' ( ' + escapeHtml(row.tahun_mula) + ' - ' + escapeHtml(row.tahun_tamat) + ' )' : ''}
                            </div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                ${escapeHtml(row.jenis_label || 'N/A')} - ${escapeHtml(row.jumlah_formatted || '')}
                            </div>
                            <div class="mt-1 text-xs font-mono text-gray-700 dark:text-gray-200">
                                Resit: ${escapeHtml(row.no_resit_sistem || '—')}
                            </div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                Rujukan: ${escapeHtml(row.no_resit_transfer || '—')}
                            </div>
                            ${row.approved_at ? `<div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sah: ${escapeHtml(row.approved_at)}</div>` : ''}
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset ${statusBg}">
                            ${escapeHtml(statusLabel)}
                        </span>
                    </div>
                `;
                historyList.appendChild(el);
            });
        }

        async function doCollect(ev) {
            ev.preventDefault();

            if (!eligible) {
                Swal.fire({ icon: 'warning', title: 'Tidak boleh kutip', text: 'Ahli telah aktif untuk tahun ini.' });
                return;
            }

            if (!collectForm || !btnCollect) return;

            btnCollect.disabled = true;
            collectInlineResult?.classList.add('hidden');

            const formData = new FormData(collectForm);
            formData.append('_token', csrfToken);

            try {
                const res = await fetch(@json(route('admin.kutipan.collect')), {
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
                    const msg = data?.message || 'Gagal merekod bayaran.';
                    if (collectInlineResult) {
                        collectInlineResult.className = 'mt-4 rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200';
                        collectInlineResult.textContent = msg;
                        collectInlineResult.classList.remove('hidden');
                    }
                    return;
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berjaya',
                    text: 'Resit sistem: ' + (data.receipt_no || '—'),
                    timer: 2200,
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

        yuranSelect?.addEventListener('change', computeCoverage);
        computeCoverage();

        renderHistory(historyData);
        setCollectEnabled(eligible);

        collectForm?.addEventListener('submit', doCollect);
    })();
</script>
@endpush
@endsection

