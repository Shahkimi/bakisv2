@extends('layouts.app')

@section('title', 'Ahli Jawatankuasa')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Ahli Jawatankuasa'])
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-center gap-4 group">
                <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-2a3 3 0 10-2.83-4M5 11a3 3 0 11.83-5.83" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Ahli Jawatankuasa</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus barisan ahli jawatankuasa &mdash; seret kad untuk menyusun mengikut hierarki.</p>
                </div>
            </div>
            <button type="button" class="btn-create-ajk group relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="relative mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">Tambah Ahli Jawatankuasa</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
        {{-- Loading --}}
        <div id="ajk-loading" class="py-16 text-center text-sm text-gray-500 dark:text-gray-400">
            <svg class="mx-auto mb-3 h-7 w-7 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memuatkan&hellip;
        </div>

        {{-- Empty state --}}
        <div id="ajk-empty" class="hidden py-16 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Tiada ahli jawatankuasa lagi</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik &ldquo;Tambah Ahli Jawatankuasa&rdquo; untuk menambah barisan pertama.</p>
        </div>

        {{-- Sortable grid --}}
        <div id="ajk-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
.ajk-card { position: relative; display: flex; align-items: center; gap: 0.875rem; padding: 1rem; border-radius: 1rem; border: 1px solid #f3f4f6; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: box-shadow 0.2s, transform 0.2s; }
.ajk-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
.dark .ajk-card { background: #1f2937; border-color: #374151; }
.ajk-card.sortable-ghost { opacity: 0.4; }
.ajk-card.sortable-chosen { box-shadow: 0 12px 32px rgba(79,70,229,0.25); transform: scale(1.01); }
.ajk-rank { position: absolute; top: 0.5rem; right: 0.625rem; font-size: 0.6875rem; font-weight: 700; color: #9ca3af; letter-spacing: 0.05em; }
.ajk-handle { cursor: grab; color: #9ca3af; flex-shrink: 0; touch-action: none; }
.ajk-handle:active { cursor: grabbing; }
.ajk-photo { width: 3.5rem; height: 3.5rem; border-radius: 0.875rem; object-fit: cover; flex-shrink: 0; border: 1px solid #e5e7eb; background: #f9fafb; }
.dark .ajk-photo { border-color: #374151; background: #111827; }
.ajk-photo-fallback { display: flex; align-items: center; justify-content: center; color: #c7d2fe; }
.dark .ajk-photo-fallback { color: #4338ca; }

/* SweetAlert modal */
.ajk-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.ajk-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.ajk-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.ajk-edit-swal .ajk-edit-form .field { margin-bottom: 1.25rem; }
.ajk-edit-swal .ajk-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.dark .ajk-edit-swal .ajk-edit-form label { color: #d1d5db; }
.ajk-edit-swal .ajk-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.ajk-edit-swal .ajk-edit-form .input-text:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
.ajk-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.ajk-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; }
.ajk-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.ajk-edit-swal .toggle-track.active { background: #6366f1; }
.ajk-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.ajk-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }

/* Upload zone (adapted from Akaun Bayaran) */
.photo-upload-zone { position: relative; border: 2px dashed #d1d5db; border-radius: 0.75rem; padding: 1.5rem 1.25rem; text-align: center; transition: all 0.25s ease; background: #fafafa; cursor: pointer; user-select: none; }
.photo-upload-zone:hover, .photo-upload-zone.drag-over { border-color: #6366f1; background: #eef2ff; }
.dark .photo-upload-zone { background: #1f2937; border-color: #4b5563; }
.dark .photo-upload-zone:hover, .dark .photo-upload-zone.drag-over { border-color: #818cf8; background: #312e81; }
.photo-upload-zone .upload-prompt { position: relative; }
.photo-upload-zone .file-input-hidden { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 1; margin: 0; padding: 0; }
.photo-upload-zone .upload-icon { width: 3rem; height: 3rem; margin: 0 auto 0.75rem; color: #6366f1; }
.photo-upload-zone .upload-title { font-size: 0.9375rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem; }
.dark .photo-upload-zone .upload-title { color: #e5e7eb; }
.photo-upload-zone .upload-subtitle { font-size: 0.8125rem; color: #9ca3af; margin-bottom: 0.75rem; }
.photo-upload-zone .upload-button { color: #6366f1; font-size: 0.8125rem; font-weight: 500; padding: 0.4375rem 1.125rem; border: 1.5px solid #6366f1; border-radius: 0.5rem; background: white; transition: all 0.2s; cursor: pointer; }
.photo-upload-zone .upload-button:hover { background: #6366f1; color: white; }
.dark .photo-upload-zone .upload-button { background: #374151; color: #a5b4fc; border-color: #818cf8; }
.photo-upload-zone .upload-formats { font-size: 0.75rem; color: #9ca3af; margin-top: 0.625rem; }
.photo-upload-zone .upload-preview { display: flex; align-items: center; gap: 0.875rem; padding: 0.875rem 1rem; background: #eef2ff; border-radius: 0.625rem; border: 1px solid #c7d2fe; }
.dark .photo-upload-zone .upload-preview { background: #312e81; border-color: #4338ca; }
.photo-upload-zone .preview-image { width: 4rem; height: 4rem; object-fit: cover; border-radius: 0.5rem; border: 1px solid #c7d2fe; background: white; flex-shrink: 0; }
.photo-upload-zone .preview-details { flex: 1; text-align: left; min-width: 0; }
.photo-upload-zone .preview-filename { font-size: 0.8125rem; font-weight: 600; color: #0f172a; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dark .photo-upload-zone .preview-filename { color: #eef2ff; }
.photo-upload-zone .preview-filesize { font-size: 0.75rem; color: #6b7280; }
.photo-upload-zone .preview-remove { padding: 0.4375rem; color: #dc2626; border-radius: 0.375rem; border: 1px solid transparent; transition: all 0.2s; flex-shrink: 0; cursor: pointer; background: transparent; }
.photo-upload-zone .preview-remove:hover { background: #fee2e2; border-color: #fca5a5; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const listUrl = '{{ route("admin.kawalan.ajk.list") }}';
    const storeUrl = '{{ route("admin.kawalan.ajk.store") }}';
    const reorderUrl = '{{ route("admin.kawalan.ajk.reorder") }}';
    const baseUrl = '{{ url("admin/kawalan/ajk") }}';

    const $grid = $('#ajk-grid');
    const $loading = $('#ajk-loading');
    const $empty = $('#ajk-empty');
    let sortable = null;

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function formatFileSize(bytes) {
        if (!bytes) return '0 B';
        const k = 1024, sizes = ['B', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    const iconEdit = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
    const iconTrash = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
    const iconHandle = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h.01M8 12h.01M8 18h.01M12 6h.01M12 12h.01M12 18h.01M16 6h.01M16 12h.01M16 18h.01" /></svg>';
    const photoFallback = '<svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-5 0-9 2.5-9 6v2h18v-2c0-3.5-4-6-9-6z"/></svg>';

    const toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true,
        didOpen: (t) => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    function cardHtml(row, rank) {
        const name = escapeHtml(row.name);
        const jawatan = escapeHtml(row.jawatan);
        const photo = row.photo_url
            ? '<img src="' + escapeHtml(row.photo_url) + '" alt="' + name + '" class="ajk-photo">'
            : '<div class="ajk-photo ajk-photo-fallback">' + photoFallback + '</div>';
        const inactive = row.is_active ? '' : '<span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">Tidak aktif</span>';
        return '' +
            '<div class="ajk-card" data-id="' + row.id + '">' +
                '<span class="ajk-rank">#' + rank + '</span>' +
                '<span class="ajk-handle" title="Seret untuk susun">' + iconHandle + '</span>' +
                photo +
                '<div class="min-w-0 flex-1">' +
                    '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">' + name + inactive + '</p>' +
                    '<p class="mt-0.5 text-xs text-indigo-600 dark:text-indigo-300 font-medium truncate">' + jawatan + '</p>' +
                '</div>' +
                '<div class="flex items-center gap-1.5 shrink-0">' +
                    '<button type="button" class="btn-edit-ajk inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Edit" ' +
                        'data-id="' + row.id + '" data-name="' + name + '" data-jawatan="' + jawatan + '" data-active="' + (row.is_active ? '1' : '0') + '" data-photo-url="' + escapeHtml(row.photo_url || '') + '">' + iconEdit + '</button>' +
                    '<button type="button" class="btn-delete-ajk inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" title="Padam" ' +
                        'data-id="' + row.id + '" data-name="' + name + '">' + iconTrash + '</button>' +
                '</div>' +
            '</div>';
    }

    function renderRanks() {
        $grid.find('.ajk-card').each(function(i) {
            $(this).find('.ajk-rank').text('#' + (i + 1));
        });
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
                rows.forEach(function(row, i) { $grid.append(cardHtml(row, i + 1)); });
                initSortable();
            })
            .fail(function() {
                $loading.addClass('hidden');
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Gagal memuatkan data.' });
            });
    }

    function initSortable() {
        if (sortable) { sortable.destroy(); sortable = null; }
        sortable = Sortable.create($grid[0], {
            handle: '.ajk-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                renderRanks();
                const order = $grid.find('.ajk-card').map(function() { return $(this).data('id'); }).get();
                fetch(reorderUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: order })
                })
                .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, d: d }; }); })
                .then(function(x) {
                    if (x.ok && x.d.success) { toast.fire({ icon: 'success', title: 'Susunan dikemas kini' }); }
                    else { Swal.fire({ icon: 'error', title: 'Ralat', text: x.d.message || 'Gagal menyimpan susunan.' }); loadList(); }
                })
                .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); loadList(); });
            }
        });
    }

    // --- Shared modal form (create + edit) ---
    function formHtml(opts) {
        opts = opts || {};
        const name = escapeHtml(opts.name || '');
        const jawatan = escapeHtml(opts.jawatan || '');
        const active = opts.active === undefined ? true : !!opts.active;
        const photoLabel = opts.isEdit ? 'Gambar (pilihan &ndash; kosongkan untuk kekalkan)' : 'Gambar';
        const existingPreview = (opts.isEdit && opts.photoUrl)
            ? '<div class="mt-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"><img src="' + escapeHtml(opts.photoUrl) + '" alt="" style="width:2.5rem;height:2.5rem;object-fit:cover;border-radius:0.5rem;border:1px solid #e5e7eb"> Gambar semasa</div>'
            : '';
        return '' +
            '<form id="ajk-form" class="ajk-edit-form">' +
                '<div class="field">' +
                    '<label for="swal-name">Nama</label>' +
                    '<input type="text" id="swal-name" class="input-text" name="name" value="' + name + '" placeholder="e.g. Ahmad bin Ali" autocomplete="off" />' +
                '</div>' +
                '<div class="field">' +
                    '<label for="swal-jawatan">Jawatan</label>' +
                    '<input type="text" id="swal-jawatan" class="input-text" name="jawatan" value="' + jawatan + '" placeholder="e.g. Pengerusi, Setiausaha" autocomplete="off" />' +
                '</div>' +
                '<div class="field">' +
                    '<label>' + photoLabel + '</label>' +
                    '<div class="photo-upload-zone" id="photo-upload-zone">' +
                        '<div class="upload-prompt" id="upload-prompt">' +
                            '<input type="file" id="swal-photo" name="photo" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" class="file-input-hidden" />' +
                            '<svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>' +
                            '<p class="upload-title">Seret &amp; lepas fail di sini</p>' +
                            '<p class="upload-subtitle">atau</p>' +
                            '<span class="upload-button" id="select-file-btn">Pilih Fail</span>' +
                            '<p class="upload-formats">JPEG, PNG, GIF, WEBP &bull; Maks 2MB</p>' +
                        '</div>' +
                        '<div class="upload-preview" id="upload-preview" style="display:none;">' +
                            '<img class="preview-image" id="preview-image" alt="" style="display:none;" />' +
                            '<div class="preview-details"><p class="preview-filename" id="preview-filename"></p><p class="preview-filesize" id="preview-filesize"></p></div>' +
                            '<button type="button" class="preview-remove" id="preview-remove" title="Buang"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg></button>' +
                        '</div>' +
                    '</div>' +
                    existingPreview +
                '</div>' +
                '<div class="field">' +
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
        initFileUpload();
    }

    function initFileUpload() {
        const zone = document.getElementById('photo-upload-zone');
        if (!zone) return;
        const fileInput = zone.querySelector('input[type="file"]');
        const prompt = zone.querySelector('#upload-prompt');
        const preview = zone.querySelector('#upload-preview');
        const selectBtn = zone.querySelector('#select-file-btn');
        const removeBtn = zone.querySelector('#preview-remove');
        if (!fileInput) return;

        function showPrompt() { if (prompt) prompt.style.display = 'block'; if (preview) preview.style.display = 'none'; }

        function handleFile(file) {
            if (!file || !file.type.match(/^image\//)) { Swal.showValidationMessage('Sila pilih fail imej.'); fileInput.value = ''; return; }
            if (file.size > 2 * 1024 * 1024) { Swal.showValidationMessage('Saiz fail mesti kurang dari 2MB.'); fileInput.value = ''; return; }
            const img = zone.querySelector('#preview-image');
            const fn = zone.querySelector('#preview-filename');
            const fs = zone.querySelector('#preview-filesize');
            if (fn) fn.textContent = file.name;
            if (fs) fs.textContent = formatFileSize(file.size);
            if (prompt) prompt.style.display = 'none';
            if (preview) preview.style.display = 'flex';
            const reader = new FileReader();
            reader.onload = function(e) { if (img) { img.src = e.target.result; img.style.display = 'block'; } };
            reader.readAsDataURL(file);
        }

        zone.addEventListener('click', function(e) {
            if (removeBtn && (e.target === removeBtn || removeBtn.contains(e.target))) return;
            if (e.target === fileInput) return;
            if (selectBtn && (e.target === selectBtn || selectBtn.contains(e.target))) return;
            e.preventDefault(); fileInput.click();
        });
        if (selectBtn) selectBtn.addEventListener('click', function(e) { e.stopPropagation(); fileInput.click(); });
        zone.addEventListener('dragover', function(e) { e.preventDefault(); e.stopPropagation(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', function() { zone.classList.remove('drag-over'); });
        zone.addEventListener('drop', function(e) {
            e.preventDefault(); e.stopPropagation(); zone.classList.remove('drag-over');
            if (e.dataTransfer.files && e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; handleFile(fileInput.files[0]); }
        });
        fileInput.addEventListener('change', function() { if (fileInput.files && fileInput.files.length) handleFile(fileInput.files[0]); });
        if (removeBtn) removeBtn.addEventListener('click', function(e) {
            e.stopPropagation(); fileInput.value = '';
            const img = zone.querySelector('#preview-image');
            if (img) { img.removeAttribute('src'); img.style.display = 'none'; }
            showPrompt();
        });
    }

    function validateForm(requirePhoto) {
        const name = (document.getElementById('swal-name').value || '').trim();
        const jawatan = (document.getElementById('swal-jawatan').value || '').trim();
        const fileInput = document.getElementById('swal-photo');
        if (!name) { Swal.showValidationMessage('Nama wajib diisi.'); return false; }
        if (!jawatan) { Swal.showValidationMessage('Jawatan wajib diisi.'); return false; }
        if (requirePhoto && (!fileInput || !fileInput.files || !fileInput.files[0])) { Swal.showValidationMessage('Gambar wajib dimuat naik.'); return false; }
        return true;
    }

    function submitForm(url, isEdit, successMsg) {
        const form = document.getElementById('ajk-form');
        const fd = new FormData(form);
        fd.append('_token', csrfToken);
        fd.set('is_active', document.getElementById('swal-active').value);
        if (isEdit) fd.append('_method', 'PUT');
        // Drop empty photo field on edit so "keep existing" works.
        const fileInput = document.getElementById('swal-photo');
        if (!fileInput || !fileInput.files || !fileInput.files[0]) fd.delete('photo');

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
                const msg = (errs.name && errs.name[0]) || (errs.jawatan && errs.jawatan[0]) || (errs.photo && errs.photo[0]) || x.d.message || 'Ralat semasa menyimpan.';
                Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
            }
        })
        .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' }); });
    }

    $(document).on('click', '.btn-create-ajk', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Ahli Jawatankuasa</span>',
            html: formHtml({ isEdit: false, active: true }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#6366f1',
            width: '460px', customClass: { popup: 'ajk-edit-swal' },
            didOpen: bindModal,
            preConfirm: function() { return validateForm(true); }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(storeUrl, false, 'Ahli jawatankuasa berjaya ditambah.');
        });
    });

    $(document).on('click', '.btn-edit-ajk', function() {
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        const jawatan = $(this).data('jawatan') || '';
        const active = String($(this).data('active')) === '1';
        const photoUrl = $(this).data('photo-url') || '';
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Ahli Jawatankuasa</span>',
            html: formHtml({ isEdit: true, name: name, jawatan: jawatan, active: active, photoUrl: photoUrl }),
            showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#6366f1',
            width: '460px', customClass: { popup: 'ajk-edit-swal' },
            didOpen: bindModal,
            preConfirm: function() { return validateForm(false); }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            submitForm(baseUrl + '/' + id, true, 'Ahli jawatankuasa telah dikemas kini.');
        });
    });

    $(document).on('click', '.btn-delete-ajk', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Padam Ahli Jawatankuasa?',
            text: 'Adakah anda pasti mahu memadam "' + name + '"?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam', cancelButtonText: 'Batal', customClass: { popup: 'ajk-edit-swal' }
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
