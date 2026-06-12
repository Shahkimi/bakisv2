@extends('layouts.app')

@section('title', 'Info')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Info'])
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-center gap-4 group">
                <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Info Persatuan</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Muat naik perlembagaan &amp; dokumen persatuan (PDF) yang dipaparkan di halaman utama.</p>
                </div>
            </div>
            <button type="button" class="btn-create-doc group relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:scale-105 hover:shadow-emerald-500/50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="relative mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">Tambah Dokumen</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
        {{-- Loading --}}
        <div id="doc-loading" class="py-16 text-center text-sm text-gray-500 dark:text-gray-400">
            <svg class="mx-auto mb-3 h-7 w-7 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memuatkan&hellip;
        </div>

        {{-- Empty state --}}
        <div id="doc-empty" class="hidden py-16 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Tiada dokumen lagi</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik &ldquo;Tambah Dokumen&rdquo; untuk memuat naik perlembagaan pertama.</p>
        </div>

        {{-- Grid --}}
        <div id="doc-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
.doc-card { position: relative; display: flex; flex-direction: column; gap: 0.75rem; padding: 1.125rem; border-radius: 1rem; border: 1px solid #f3f4f6; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: box-shadow 0.2s, transform 0.2s; }
.doc-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
.dark .doc-card { background: #1f2937; border-color: #374151; }

.doc-icon { display: flex; align-items: center; justify-content: center; width: 3.25rem; height: 3.25rem; border-radius: 0.75rem; background: linear-gradient(160deg, #e7f3f1, #d2ebe7); color: #0E7A66; flex-shrink: 0; }
.dark .doc-icon { background: linear-gradient(160deg, #064e3b, #065f46); color: #6ee7b7; }

.doc-status { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.01em; }
.doc-status.active { background: #dcfce7; color: #15803d; }
.dark .doc-status.active { background: rgba(22,101,52,0.25); color: #86efac; }
.doc-status.inactive { background: #f3f4f6; color: #6b7280; }
.dark .doc-status.inactive { background: #374151; color: #9ca3af; }
.doc-status .dot { width: 0.4rem; height: 0.4rem; border-radius: 9999px; background: currentColor; }

/* SweetAlert modal */
.doc-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.doc-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.doc-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.doc-edit-swal .doc-edit-form .field { margin-bottom: 1.25rem; }
.doc-edit-swal .doc-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.dark .doc-edit-swal .doc-edit-form label { color: #d1d5db; }
.doc-edit-swal .doc-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.doc-edit-swal .doc-edit-form .input-text:focus { outline: none; border-color: #0E7A66; box-shadow: 0 0 0 3px rgba(14, 122, 102, 0.2); }
.doc-edit-swal .doc-edit-form input[type=file].input-text { padding: 0.5rem 0.625rem; }
.doc-edit-swal .doc-edit-form input[type=file]::file-selector-button { margin-right: 0.75rem; padding: 0.375rem 0.75rem; border: 0; border-radius: 0.375rem; background: #0E7A66; color: #fff; font-size: 0.8125rem; font-weight: 600; cursor: pointer; }
.doc-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.doc-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; }
.dark .doc-edit-swal .toggle-label { color: #d1d5db; }
.doc-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.doc-edit-swal .toggle-track.active { background: #0E7A66; }
.doc-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.doc-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const listUrl = '{{ route("admin.kawalan.perlembagaan.list") }}';
    const storeUrl = '{{ route("admin.kawalan.perlembagaan.store") }}';
    const baseUrl = '{{ url("admin/kawalan/perlembagaan") }}';

    const $grid = $('#doc-grid');
    const $loading = $('#doc-loading');
    const $empty = $('#doc-empty');

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    const iconEdit = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
    const iconTrash = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
    const iconDoc = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
    const iconView = '<svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';

    function cardHtml(row) {
        const name = escapeHtml(row.tajuk);
        const fileName = escapeHtml(row.file_name || '');
        const sizeLabel = row.file_size_label ? ' · ' + escapeHtml(row.file_size_label) : '';
        const status = row.is_active
            ? '<span class="doc-status active"><span class="dot"></span>Aktif</span>'
            : '<span class="doc-status inactive"><span class="dot"></span>Tidak aktif</span>';
        const viewLink = row.url
            ? '<a href="' + escapeHtml(row.url) + '" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-medium rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" title="Lihat PDF">' + iconView + 'Lihat PDF</a>'
            : '';
        return '' +
            '<div class="doc-card" data-id="' + row.id + '">' +
                '<div class="flex items-start gap-3">' +
                    '<div class="doc-icon">' + iconDoc + '</div>' +
                    '<div class="min-w-0 flex-1">' +
                        '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100 leading-snug">' + name + '</p>' +
                        '<p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">' + fileName + sizeLabel + '</p>' +
                    '</div>' +
                '</div>' +
                (viewLink ? '<div class="flex items-center gap-1.5 flex-wrap pt-1">' + viewLink + '</div>' : '') +
                '<div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-gray-700">' +
                    status +
                    '<div class="flex items-center gap-1.5 shrink-0">' +
                        '<button type="button" class="btn-edit-doc inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Edit" ' +
                            'data-id="' + row.id + '" data-name="' + name + '" data-active="' + (row.is_active ? '1' : '0') + '">' + iconEdit + '</button>' +
                        '<button type="button" class="btn-delete-doc inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" title="Padam" ' +
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
        const active = opts.active === undefined ? true : !!opts.active;
        const isEdit = !!opts.isEdit;
        const fileHint = isEdit
            ? 'Biarkan kosong untuk kekalkan fail sedia ada.'
            : 'Format PDF sahaja, maksimum 10 MB.';
        return '' +
            '<form id="doc-form" class="doc-edit-form">' +
                '<div class="field">' +
                    '<label for="swal-tajuk">Tajuk Dokumen</label>' +
                    '<input type="text" id="swal-tajuk" class="input-text" name="tajuk" value="' + name + '" placeholder="cth: Perlembagaan BAKIS 2026" autocomplete="off" />' +
                '</div>' +
                '<div class="field">' +
                    '<label for="swal-pdf">Fail PDF</label>' +
                    '<input type="file" id="swal-pdf" class="input-text" name="pdf" accept="application/pdf" />' +
                    '<p class="text-xs text-gray-400" style="margin-top:0.3rem;">' + fileHint + '</p>' +
                '</div>' +
                '<div class="field" style="margin-top:1rem;border-top:1px solid #f1f5f9;padding-top:1rem;">' +
                    '<div class="toggle-wrap">' +
                        '<span class="toggle-label">Status (Aktif)</span>' +
                        '<div class="toggle-track ' + (active ? 'active' : '') + '" id="swal-toggle" role="button" tabindex="0" aria-pressed="' + active + '"><span class="toggle-thumb"></span></div>' +
                    '</div>' +
                    '<input type="hidden" id="swal-active" name="is_active" value="' + (active ? '1' : '0') + '" />' +
                    '<p class="text-xs text-gray-400" style="margin-top:0.25rem;">Hanya dokumen aktif dipaparkan di halaman utama.</p>' +
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

    function validateForm(isEdit) {
        const name = (document.getElementById('swal-tajuk').value || '').trim();
        const fileInput = document.getElementById('swal-pdf');
        const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
        if (!name) { Swal.showValidationMessage('Tajuk dokumen wajib diisi.'); return false; }
        if (!isEdit && !hasFile) { Swal.showValidationMessage('Sila pilih fail PDF.'); return false; }
        if (hasFile) {
            const f = fileInput.files[0];
            const isPdf = f.type === 'application/pdf' || /\.pdf$/i.test(f.name);
            if (!isPdf) { Swal.showValidationMessage('Hanya fail PDF dibenarkan.'); return false; }
            if (f.size > 10 * 1024 * 1024) { Swal.showValidationMessage('Saiz fail tidak boleh melebihi 10 MB.'); return false; }
        }
        return true;
    }

    function submitForm(url, isEdit, successMsg) {
        const form = document.getElementById('doc-form');
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
                const msg = (errs.tajuk && errs.tajuk[0]) || (errs.pdf && errs.pdf[0]) || x.d.message || 'Ralat semasa menyimpan.';
                Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
            }
        })
        .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' }); });
    }

    $(document).on('click', '.btn-create-doc', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Dokumen</span>',
            html: formHtml({ isEdit: false, active: true }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#0E7A66',
            width: '480px', customClass: { popup: 'doc-edit-swal' },
            didOpen: bindModal,
            preConfirm: function() { return validateForm(false); }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(storeUrl, false, 'Dokumen berjaya ditambah.');
        });
    });

    $(document).on('click', '.btn-edit-doc', function() {
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        const active = String($(this).data('active')) === '1';
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Dokumen</span>',
            html: formHtml({ isEdit: true, name: name, active: active }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#0E7A66',
            width: '480px', customClass: { popup: 'doc-edit-swal' },
            didOpen: bindModal,
            preConfirm: function() { return validateForm(true); }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(baseUrl + '/' + id, true, 'Dokumen telah dikemas kini.');
        });
    });

    $(document).on('click', '.btn-delete-doc', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Padam Dokumen?',
            text: 'Adakah anda pasti mahu memadam "' + name + '"?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam', cancelButtonText: 'Batal', customClass: { popup: 'doc-edit-swal' }
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
