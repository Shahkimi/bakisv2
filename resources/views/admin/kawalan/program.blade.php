@extends('layouts.app')

@section('title', 'Program')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Program'])
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-center gap-4 group">
                <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Program</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus program &amp; aktiviti BAKIS yang dipaparkan di halaman utama.</p>
                </div>
            </div>
            <button type="button" class="btn-create-program group relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="relative mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">Tambah Program</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
        {{-- Loading --}}
        <div id="program-loading" class="py-16 text-center text-sm text-gray-500 dark:text-gray-400">
            <svg class="mx-auto mb-3 h-7 w-7 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memuatkan&hellip;
        </div>

        {{-- Empty state --}}
        <div id="program-empty" class="hidden py-16 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Tiada program lagi</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik &ldquo;Tambah Program&rdquo; untuk menambah program pertama.</p>
        </div>

        {{-- Grid --}}
        <div id="program-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
.program-card { position: relative; display: flex; flex-direction: column; gap: 0.75rem; padding: 1.125rem; border-radius: 1rem; border: 1px solid #f3f4f6; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: box-shadow 0.2s, transform 0.2s; }
.program-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
.dark .program-card { background: #1f2937; border-color: #374151; }
.program-card.is-past { opacity: 0.82; }

.program-datechip { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 3.25rem; height: 3.25rem; border-radius: 0.75rem; background: linear-gradient(160deg, #eef2ff, #e0e7ff); color: #4338ca; flex-shrink: 0; line-height: 1; }
.dark .program-datechip { background: linear-gradient(160deg, #312e81, #3730a3); color: #c7d2fe; }
.program-card.is-past .program-datechip { background: linear-gradient(160deg, #f3f4f6, #e5e7eb); color: #6b7280; }
.dark .program-card.is-past .program-datechip { background: linear-gradient(160deg, #374151, #4b5563); color: #9ca3af; }
.program-datechip .d { font-size: 1.125rem; font-weight: 800; }
.program-datechip .m { font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.125rem; }

.program-status { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.01em; }
.program-status.upcoming { background: #dcfce7; color: #15803d; }
.dark .program-status.upcoming { background: rgba(22,101,52,0.25); color: #86efac; }
.program-status.past { background: #f3f4f6; color: #6b7280; }
.dark .program-status.past { background: #374151; color: #9ca3af; }
.program-status .dot { width: 0.4rem; height: 0.4rem; border-radius: 9999px; background: currentColor; }

/* SweetAlert modal */
.program-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.program-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.program-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.program-edit-swal .program-edit-form .field { margin-bottom: 1.25rem; }
.program-edit-swal .program-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.dark .program-edit-swal .program-edit-form label { color: #d1d5db; }
.program-edit-swal .program-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.program-edit-swal .program-edit-form .input-text:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
.program-edit-swal .program-edit-form .field-row { display: flex; gap: 0.75rem; }
.program-edit-swal .program-edit-form .field-row .field { flex: 1; margin-bottom: 0; }
.program-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.program-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; }
.dark .program-edit-swal .toggle-label { color: #d1d5db; }
.program-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.program-edit-swal .toggle-track.active { background: #6366f1; }
.program-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.program-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const listUrl = '{{ route("admin.kawalan.program.list") }}';
    const storeUrl = '{{ route("admin.kawalan.program.store") }}';
    const baseUrl = '{{ url("admin/kawalan/program") }}';

    const $grid = $('#program-grid');
    const $loading = $('#program-loading');
    const $empty = $('#program-empty');

    const MONTHS = ['Jan','Feb','Mac','Apr','Mei','Jun','Jul','Ogo','Sep','Okt','Nov','Dis'];

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function formatTime(t) {
        if (!t) return '';
        const m = String(t).match(/^(\d{1,2}):(\d{2})/);
        if (!m) return escapeHtml(t);
        let h = parseInt(m[1], 10);
        const min = m[2];
        const ap = h >= 12 ? 'PM' : 'AM';
        h = h % 12; if (h === 0) h = 12;
        return h + ':' + min + ' ' + ap;
    }

    function timeRange(a, b) {
        const fa = formatTime(a), fb = formatTime(b);
        if (fa && fb) return fa + ' &ndash; ' + fb;
        return fa || fb || '';
    }

    const iconEdit = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
    const iconTrash = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
    const iconClock = '<svg class="w-3.5 h-3.5 shrink-0 inline-block -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

    const toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true,
        didOpen: (t) => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    function cardHtml(row) {
        const name = escapeHtml(row.nama_program);
        const parts = (row.tarikh || '').split('-'); // YYYY-MM-DD
        const day = parts[2] || '';
        const monIdx = parts[1] ? parseInt(parts[1], 10) - 1 : 0;
        const mon = MONTHS[monIdx] || '';
        const status = row.is_past
            ? '<span class="program-status past"><span class="dot"></span>Telah Dianjurkan</span>'
            : '<span class="program-status upcoming"><span class="dot"></span>Akan Datang</span>';
        const inactive = row.is_active ? '' : '<span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">Tidak aktif</span>';
        return '' +
            '<div class="program-card ' + (row.is_past ? 'is-past' : '') + '" data-id="' + row.id + '">' +
                '<div class="flex items-start gap-3">' +
                    '<div class="program-datechip"><span class="d">' + escapeHtml(day) + '</span><span class="m">' + escapeHtml(mon) + '</span></div>' +
                    '<div class="min-w-0 flex-1">' +
                        '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100 leading-snug">' + name + inactive + '</p>' +
                        '<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">' + iconClock + timeRange(row.waktu_mula, row.waktu_tamat) + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-gray-700">' +
                    status +
                    '<div class="flex items-center gap-1.5 shrink-0">' +
                        '<button type="button" class="btn-edit-program inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Edit" ' +
                            'data-id="' + row.id + '" data-name="' + name + '" data-tarikh="' + escapeHtml(row.tarikh || '') + '" data-mula="' + escapeHtml(row.waktu_mula || '') + '" data-tamat="' + escapeHtml(row.waktu_tamat || '') + '" data-active="' + (row.is_active ? '1' : '0') + '">' + iconEdit + '</button>' +
                        '<button type="button" class="btn-delete-program inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" title="Padam" ' +
                            'data-id="' + row.id + '" data-name="' + name + '">' + iconTrash + '</button>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function loadList() {
        $.ajax({ url: listUrl, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .done(function(res) {
                const rows = (res && res.data) || [];
                $loading.addClass('hidden');
                if (!rows.length) {
                    $grid.addClass('hidden').empty();
                    $empty.removeClass('hidden');
                    return;
                }
                $empty.addClass('hidden');
                $grid.removeClass('hidden').empty();
                rows.forEach(function(row) { $grid.append(cardHtml(row)); });
            })
            .fail(function() {
                $loading.addClass('hidden');
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Gagal memuatkan data.' });
            });
    }

    // --- Shared modal form (create + edit) ---
    function formHtml(opts) {
        opts = opts || {};
        const name = escapeHtml(opts.name || '');
        const tarikh = escapeHtml(opts.tarikh || '');
        const mula = escapeHtml(opts.mula || '');
        const tamat = escapeHtml(opts.tamat || '');
        const active = opts.active === undefined ? true : !!opts.active;
        return '' +
            '<form id="program-form" class="program-edit-form">' +
                '<div class="field">' +
                    '<label for="swal-name">Nama Program</label>' +
                    '<input type="text" id="swal-name" class="input-text" name="nama_program" value="' + name + '" placeholder="e.g. Majlis Tahunan BAKIS" autocomplete="off" />' +
                '</div>' +
                '<div class="field">' +
                    '<label for="swal-tarikh">Tarikh</label>' +
                    '<input type="date" id="swal-tarikh" class="input-text" name="tarikh" value="' + tarikh + '" />' +
                '</div>' +
                '<div class="field-row">' +
                    '<div class="field">' +
                        '<label for="swal-mula">Waktu Mula</label>' +
                        '<input type="time" id="swal-mula" class="input-text" name="waktu_mula" value="' + mula + '" />' +
                    '</div>' +
                    '<div class="field">' +
                        '<label for="swal-tamat">Waktu Tamat</label>' +
                        '<input type="time" id="swal-tamat" class="input-text" name="waktu_tamat" value="' + tamat + '" />' +
                    '</div>' +
                '</div>' +
                '<div class="field" style="margin-top:1.25rem;">' +
                    '<div class="toggle-wrap">' +
                        '<span class="toggle-label">Status (Aktif)</span>' +
                        '<div class="toggle-track ' + (active ? 'active' : '') + '" id="swal-toggle" role="button" tabindex="0" aria-pressed="' + active + '"><span class="toggle-thumb"></span></div>' +
                    '</div>' +
                    '<input type="hidden" id="swal-active" name="is_active" value="' + (active ? '1' : '0') + '" />' +
                '</div>' +
            '</form>';
    }

    function bindModal() {
        const toggle = document.getElementById('swal-toggle');
        const hidden = document.getElementById('swal-active');
        if (toggle && hidden) {
            const flip = function() { const a = toggle.classList.toggle('active'); hidden.value = a ? '1' : '0'; toggle.setAttribute('aria-pressed', a); };
            toggle.addEventListener('click', flip);
            toggle.addEventListener('keydown', function(e) { if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); flip(); } });
        }
    }

    function validateForm() {
        const name = (document.getElementById('swal-name').value || '').trim();
        const tarikh = (document.getElementById('swal-tarikh').value || '').trim();
        const mula = (document.getElementById('swal-mula').value || '').trim();
        const tamat = (document.getElementById('swal-tamat').value || '').trim();
        if (!name) { Swal.showValidationMessage('Nama program wajib diisi.'); return false; }
        if (!tarikh) { Swal.showValidationMessage('Tarikh wajib diisi.'); return false; }
        if (!mula) { Swal.showValidationMessage('Waktu mula wajib diisi.'); return false; }
        if (!tamat) { Swal.showValidationMessage('Waktu tamat wajib diisi.'); return false; }
        if (tamat < mula) { Swal.showValidationMessage('Waktu tamat mesti selepas waktu mula.'); return false; }
        return true;
    }

    function submitForm(url, isEdit, successMsg) {
        const form = document.getElementById('program-form');
        const fd = new FormData(form);
        fd.append('_token', csrfToken);
        fd.set('is_active', document.getElementById('swal-active').value);
        if (isEdit) fd.append('_method', 'PUT');

        fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
        .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, d: d }; }); })
        .then(function(x) {
            if (x.ok && x.d.success) {
                Swal.fire({ icon: 'success', title: 'Berjaya', text: x.d.message || successMsg, timer: 2000, timerProgressBar: true, showConfirmButton: false });
                loadList();
            } else {
                const errs = x.d.errors || {};
                const msg = (errs.nama_program && errs.nama_program[0]) || (errs.tarikh && errs.tarikh[0]) || (errs.waktu_mula && errs.waktu_mula[0]) || (errs.waktu_tamat && errs.waktu_tamat[0]) || x.d.message || 'Ralat semasa menyimpan.';
                Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
            }
        })
        .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' }); });
    }

    $(document).on('click', '.btn-create-program', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Program</span>',
            html: formHtml({ isEdit: false, active: true }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#6366f1',
            width: '480px', customClass: { popup: 'program-edit-swal' },
            didOpen: bindModal,
            preConfirm: validateForm
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(storeUrl, false, 'Program berjaya ditambah.');
        });
    });

    $(document).on('click', '.btn-edit-program', function() {
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        const tarikh = $(this).data('tarikh') || '';
        const mula = $(this).data('mula') || '';
        const tamat = $(this).data('tamat') || '';
        const active = String($(this).data('active')) === '1';
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Program</span>',
            html: formHtml({ isEdit: true, name: name, tarikh: tarikh, mula: mula, tamat: tamat, active: active }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#6366f1',
            width: '480px', customClass: { popup: 'program-edit-swal' },
            didOpen: bindModal,
            preConfirm: validateForm
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(baseUrl + '/' + id, true, 'Program telah dikemas kini.');
        });
    });

    $(document).on('click', '.btn-delete-program', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Padam Program?',
            text: 'Adakah anda pasti mahu memadam "' + name + '"?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam', cancelButtonText: 'Batal', customClass: { popup: 'program-edit-swal' }
        }).then(function(result) {
            if (!result.isConfirmed) return;
            fetch(baseUrl + '/' + id, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ '_token': csrfToken, '_method': 'DELETE' }).toString()
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, d: d }; }); })
            .then(function(x) {
                if (x.ok && x.d.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: x.d.message || 'Berjaya dipadam.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    loadList();
                } else { Swal.fire({ icon: 'error', title: 'Ralat', text: x.d.message || 'Gagal memadam.' }); }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
        });
    });

    loadList();
});
</script>
@endpush
