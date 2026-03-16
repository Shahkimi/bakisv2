@extends('layouts.app')

@section('title', 'Kawalan Akaun Bayaran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 shadow-lg shadow-teal-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Kawalan Akaun Bayaran</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus akaun bayaran dan imej QR</p>
            </div>
        </div>
        <button type="button" class="btn-create-account group relative inline-flex items-center justify-center py-3 px-6 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-500 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 shadow-lg hover:shadow-teal-500/30 transform hover:-translate-y-0.5 overflow-hidden">
            <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
            <svg class="relative w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="relative">Tambah Akaun</span>
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6 transform transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <table id="account-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Akaun</th>
                    <th>No. Akaun</th>
                    <th>Imej QR</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                {{-- DataTables server-side will populate --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<style>
/* Custom DataTable Styling - reuse from Yuran */
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb; padding: 1rem; color: #4b5563; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background-color: #f9fafb; text-align: left; transition: background-color 0.2s; }
.dark table.dataTable thead th { border-bottom-color: #374151; color: #d1d5db; background-color: #1f2937; }
table.dataTable tbody tr { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease-in-out; }
table.dataTable tbody tr:hover { background-color: #f9fafb; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 0.75rem; margin: 0 0.25rem; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; font-size: 0.875rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #e0e7ff; color: #4338ca !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5; color: #fff !important; }

table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc { background-image: none !important; padding-right: 1.5rem !important; cursor: pointer; }

/* Account modal */
.account-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.account-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.account-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.account-edit-swal .account-edit-form .field { margin-bottom: 1.25rem; }
.account-edit-swal .account-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.account-edit-swal .account-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; }
.account-edit-swal .account-edit-form .input-text:focus { outline: none; border-color: #6366f1; }
.account-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.account-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; cursor: pointer; }
.account-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.account-edit-swal .toggle-track.active { background: #6366f1; }
.account-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.account-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
.account-edit-swal .swal2-actions { padding: 0 1.5rem 1.5rem; gap: 0.75rem; }
.account-edit-swal .qr-thumb { width: 40px; height: 40px; object-fit: contain; border-radius: 0.5rem; border: 1px solid #e5e7eb; }

/* File input: overlay on prompt so any click opens file dialog (reliable across browsers) */
.qr-upload-zone {
    position: relative;
}
.qr-upload-zone .upload-prompt {
    position: relative;
}
.qr-upload-zone .file-input-hidden {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 1;
    margin: 0;
    padding: 0;
}

/* QR Upload Zone */
.qr-upload-zone {
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    padding: 1.75rem 1.5rem;
    text-align: center;
    transition: all 0.25s ease;
    background: #fafafa;
    cursor: pointer;
    user-select: none;
}
.qr-upload-zone:hover,
.qr-upload-zone.drag-over {
    border-color: #0d9488;
    background: #f0fdfa;
}
.dark .qr-upload-zone {
    background: #1f2937;
    border-color: #4b5563;
}
.dark .qr-upload-zone:hover,
.dark .qr-upload-zone.drag-over {
    border-color: #14b8a6;
    background: #134e4a;
}
.upload-icon {
    width: 3.5rem;
    height: 3.5rem;
    margin: 0 auto 0.875rem;
    color: #0d9488;
    filter: drop-shadow(0 2px 6px rgba(13,148,136,0.25));
}
.upload-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}
.upload-subtitle {
    font-size: 0.8125rem;
    color: #9ca3af;
    margin-bottom: 0.875rem;
}
.dark .upload-title {
    color: #e5e7eb;
}
.upload-button {
    color: #0d9488;
    font-size: 0.8125rem;
    font-weight: 500;
    padding: 0.4375rem 1.125rem;
    border: 1.5px solid #0d9488;
    border-radius: 0.5rem;
    background: white;
    transition: all 0.2s;
    cursor: pointer;
}
.upload-button:hover {
    background: #0d9488;
    color: white;
    box-shadow: 0 2px 8px rgba(13,148,136,0.25);
}
.dark .upload-button {
    background: #374151;
    color: #14b8a6;
    border-color: #14b8a6;
}
.dark .upload-button:hover {
    background: #0f766e;
    color: white;
}
.upload-formats {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.625rem;
}
/* Preview */
.upload-preview {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1rem;
    background: #f0fdfa;
    border-radius: 0.625rem;
    border: 1px solid #99f6e4;
    animation: slideUpFade 0.25s ease;
}
@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dark .upload-preview {
    background: #134e4a;
    border-color: #0f766e;
}
.preview-image {
    width: 4.5rem;
    height: 4.5rem;
    object-fit: contain;
    border-radius: 0.5rem;
    border: 1px solid #99f6e4;
    background: white;
    flex-shrink: 0;
}
.dark .preview-image {
    border-color: #0f766e;
    background: #1f2937;
}
.preview-details {
    flex: 1;
    text-align: left;
    min-width: 0;
}
.preview-filename {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dark .preview-filename {
    color: #f0fdfa;
}
.preview-filesize {
    font-size: 0.75rem;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
.preview-filesize::before {
    content: "✓";
    color: #0d9488;
    font-weight: 700;
}
.dark .preview-filesize {
    color: #9ca3af;
}
.preview-remove {
    padding: 0.4375rem;
    color: #dc2626;
    border-radius: 0.375rem;
    border: 1px solid transparent;
    transition: all 0.2s;
    flex-shrink: 0;
    cursor: pointer;
    background: transparent;
}
.preview-remove:hover {
    background: #fee2e2;
    border-color: #fca5a5;
}
.dark .preview-remove:hover {
    background: #7f1d1d;
    border-color: #991b1b;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const accountBaseUrl = '{{ url("admin/kawalan/account") }}';
    const accountQrUrl = (id) => accountBaseUrl + '/' + id + '/qr';

    function escapeAttr(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function renderQrThumb(row) {
        if (!row.has_qr) return '<span class="text-gray-400 text-xs">–</span>';
        const url = accountQrUrl(row.id);
        return '' +
            '<button type="button" class="js-qr-preview inline-flex items-center justify-center" ' +
                'data-qr-url="' + escapeAttr(url) + '" ' +
                'aria-label="Lihat imej QR">' +
                '<img src="' + escapeAttr(url) + '" alt="QR" class="qr-thumb" width="40" height="40">' +
            '</button>';
    }

    function renderStatusBadge(row) {
        const id = row.id;
        const active = !!row.is_active;
        const label = active ? 'Aktif' : 'Tidak aktif';
        const bgClass = active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
        const dotClass = active ? 'bg-green-500' : 'bg-gray-400';
        return '<button type="button" class="btn-toggle-status inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full ' + bgClass + ' hover:opacity-80 transition cursor-pointer" data-id="' + id + '" data-active="' + (active ? '1' : '0') + '" data-account-name="' + escapeAttr(row.actions?.account_name) + '" data-account-number="' + escapeAttr(row.actions?.account_number) + '"><span class="w-1.5 h-1.5 rounded-full ' + dotClass + '"></span> ' + label + '</button>';
    }

    function renderActionsButton(row) {
        const a = row.actions || {};
        const id = a.id;
        const accountName = escapeAttr(a.account_name);
        const accountNumber = escapeAttr(a.account_number);
        const active = a.is_active ? '1' : '0';
        const hasQr = !!row.has_qr;
        const qrUrl = hasQr ? accountQrUrl(row.id) : '';
        return '<div class="flex items-center gap-2">' +
            '<button type="button" class="btn-edit-account inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-id="' + id + '" data-account-name="' + accountName + '" data-account-number="' + accountNumber + '" data-active="' + active + '" data-has-qr="' + (hasQr ? '1' : '0') + '" data-qr-url="' + escapeAttr(qrUrl) + '">Edit</button>' +
            '<button type="button" class="btn-delete-account inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" data-id="' + id + '" data-account-name="' + accountName + '">Padam</button></div>';
    }

    const table = $('#account-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ route("admin.kawalan.account.data") }}', type: 'GET' },
        columns: [
            { data: 'id', name: 'id', width: '80px' },
            { data: 'account_name', name: 'account_name' },
            { data: 'account_number', name: 'account_number' },
            { data: 'qr_image_path', name: 'qr_image_path', orderable: false, searchable: false, render: function(d, type, row) { return renderQrThumb(row); } },
            { data: 'is_active', name: 'is_active', orderable: false, render: function(d, type, row) { return renderStatusBadge(row); } },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(d, type, row) { return renderActionsButton(row); } }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        initComplete: function() { $('.dataTables_filter input').attr('placeholder', 'Cari nama atau no. akaun…'); },
        language: {
            processing: 'Memuatkan...',
            search: 'Cari:',
            lengthMenu: 'Papar _MENU_ rekod',
            info: 'Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod',
            infoFiltered: '(ditapis daripada _MAX_ rekod)',
            paginate: { first: 'Pertama', last: 'Akhir', next: 'Seterusnya', previous: 'Sebelumnya' },
            zeroRecords: 'Tiada rekod sepadan'
        }
    });

    // QR thumbnail preview in SweetAlert
    $(document).on('click', '.js-qr-preview', function () {
        const url = $(this).data('qr-url');
        if (!url) return;

        Swal.fire({
            title: 'Preview QR',
            imageUrl: url,
            imageAlt: 'Imej QR',
            showConfirmButton: false,
            showCloseButton: true,
            width: 360,
            padding: '1.5rem',
        });
    });

    function bindToggleInModal(modalEl) {
        const toggle = modalEl.querySelector('#swal-toggle');
        const hidden = modalEl.querySelector('#swal-active');
        if (toggle && hidden) {
            toggle.addEventListener('click', function() {
                const isActive = toggle.classList.toggle('active');
                hidden.value = isActive ? '1' : '0';
            });
        }
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function initializeFileUpload(uploadZoneId, fileInputId, promptId, previewId) {
        const uploadZone = document.getElementById(uploadZoneId);
        if (!uploadZone) return;
        const fileInput = uploadZone.querySelector('input[type="file"]') || document.getElementById(fileInputId);
        const prompt = uploadZone.querySelector('#' + promptId);
        const preview = uploadZone.querySelector('#' + previewId);
        const selectBtn = uploadZone.querySelector('#select-file-btn');
        const removeBtn = uploadZone.querySelector('#preview-remove');
        if (!fileInput) return;

        function handleFileSelect(file) {
            if (!file || !file.type.match(/^image\//)) {
                Swal.showValidationMessage('Sila pilih fail imej (JPEG, PNG, SVG).');
                fileInput.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                Swal.showValidationMessage('Saiz fail mesti kurang dari 2MB.');
                fileInput.value = '';
                return;
            }
            var previewImg = uploadZone.querySelector('#preview-image');
            var previewFilenameEl = uploadZone.querySelector('#preview-filename');
            var previewFilesizeEl = uploadZone.querySelector('#preview-filesize');
            if (!previewImg || !previewFilenameEl || !previewFilesizeEl) return;

            previewFilenameEl.textContent = file.name;
            previewFilesizeEl.textContent = formatFileSize(file.size);
            if (prompt) {
                prompt.classList.add('hidden');
                prompt.style.display = 'none';
            }
            if (preview) {
                preview.classList.remove('hidden');
                preview.style.display = 'flex';
            }

            previewImg.alt = '';
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block'; 
            };
            reader.onerror = function() {
                previewImg.removeAttribute('src');
                previewImg.alt = 'Preview';
            };
            reader.readAsDataURL(file);
        }

        uploadZone.addEventListener('click', function(e) {
            if (removeBtn && (e.target === removeBtn || removeBtn.contains(e.target))) return;
            if (e.target === fileInput) return;
            if (selectBtn && (e.target === selectBtn || selectBtn.contains(e.target))) return;
            e.preventDefault();
            fileInput.click();
        });

        if (selectBtn) {
            selectBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });
        }

        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadZone.classList.add('drag-over');
        });

        uploadZone.addEventListener('dragleave', function() {
            uploadZone.classList.remove('drag-over');
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadZone.classList.remove('drag-over');
            if (e.dataTransfer.files && e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(fileInput.files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files.length) {
                handleFileSelect(fileInput.files[0]);
            }
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                var previewImg = uploadZone.querySelector('#preview-image');
                var previewFilenameEl = uploadZone.querySelector('#preview-filename');
                var previewFilesizeEl = uploadZone.querySelector('#preview-filesize');
                if (previewImg) { previewImg.removeAttribute('src'); previewImg.style.display = 'none'; }
                if (previewFilenameEl) previewFilenameEl.textContent = '';
                if (previewFilesizeEl) previewFilesizeEl.textContent = '';
                showPrompt();
            });
        }
    }

    $(document).on('click', '.btn-create-account', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg> Tambah Akaun Bayaran</span>',
            html: `
                <form id="create-account-form" class="account-edit-form">
                    <div class="field">
                        <label for="swal-account-name">Nama Akaun</label>
                        <input type="text" id="swal-account-name" class="input-text" name="account_name" placeholder="e.g. CIMB BAKIS" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label for="swal-account-number">No. Akaun</label>
                        <input type="text" id="swal-account-number" class="input-text" name="account_number" placeholder="e.g. 1234567890" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Imej QR</label>
                        <div class="qr-upload-zone" id="qr-upload-zone">
                            <div class="upload-prompt" id="upload-prompt">
                                <input type="file" id="swal-qr-image" name="qr_image" accept=".jpg,.jpeg,.png,.svg,image/*" class="file-input-hidden" />
                                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="upload-title">Seret & lepas fail di sini</p>
                                <p class="upload-subtitle">atau</p>
                                <span class="upload-button" id="select-file-btn">Pilih Fail untuk Dimuat Naik</span>
                                <p class="upload-formats">JPEG, PNG, SVG &bull; Maks 2MB</p>
                            </div>
                            <div class="upload-preview" id="upload-preview" style="display:none;">
                                <img class="preview-image" id="preview-image" alt="" style="display:none;" />
                                <div class="preview-details">
                                    <p class="preview-filename" id="preview-filename"></p>
                                    <p class="preview-filesize" id="preview-filesize"></p>
                                </div>
                                <button type="button" class="preview-remove" id="preview-remove" title="Buang imej">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Status Aktif</span>
                            <div class="toggle-track active" id="swal-toggle" role="button" tabindex="0"><span class="toggle-thumb"></span></div>
                        </div>
                        <input type="hidden" id="swal-active" name="is_active" value="1" />
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d9488',
            width: '420px',
            customClass: { popup: 'account-edit-swal' },
            didOpen: function() {
                bindToggleInModal(document.querySelector('.account-edit-swal'));
                initializeFileUpload('qr-upload-zone', 'swal-qr-image', 'upload-prompt', 'upload-preview');
            },
            preConfirm: function() {
                const name = (document.getElementById('swal-account-name').value || '').trim();
                const number = (document.getElementById('swal-account-number').value || '').trim();
                const fileInput = document.getElementById('swal-qr-image');
                if (!name) { Swal.showValidationMessage('Nama akaun wajib diisi.'); return false; }
                if (!number) { Swal.showValidationMessage('No. akaun wajib diisi.'); return false; }
                if (!fileInput || !fileInput.files || !fileInput.files[0]) { Swal.showValidationMessage('Imej QR wajib dimuat naik.'); return false; }
                return true;
            }
        }).then(function(result) {
            if (!result.isConfirmed) return;
            const form = document.getElementById('create-account-form');
            const formData = new FormData(form);
            formData.append('_token', csrfToken);
            formData.append('is_active', document.getElementById('swal-active').value);

            fetch('{{ route("admin.kawalan.account.store") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Akaun berjaya ditambah.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && (data.errors.account_name && data.errors.account_name[0]) || (data.errors.account_number && data.errors.account_number[0]) || (data.errors.qr_image && data.errors.qr_image[0])) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' }); });
        });
    });

    $(document).on('click', '.btn-edit-account', function() {
        const id = $(this).data('id');
        const accountName = $(this).data('account-name') || '';
        const accountNumber = $(this).data('account-number') || '';
        const active = $(this).data('active') === 1 || $(this).data('active') === '1';
        const hasQr = $(this).data('has-qr') === 1 || $(this).data('has-qr') === '1';
        const existingQrUrl = $(this).data('qr-url') || '';

        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Akaun Bayaran</span>',
            html: `
                <form id="edit-account-form" class="account-edit-form">
                    <div class="field">
                        <label for="swal-account-name">Nama Akaun</label>
                        <input type="text" id="swal-account-name" class="input-text" name="account_name" value="${escapeAttr(accountName)}" placeholder="e.g. CIMB BAKIS" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label for="swal-account-number">No. Akaun</label>
                        <input type="text" id="swal-account-number" class="input-text" name="account_number" value="${escapeAttr(accountNumber)}" placeholder="e.g. 1234567890" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imej QR (pilihan – kosongkan untuk kekalkan)</label>
                        <div class="qr-upload-zone" id="qr-upload-zone">
                            <div class="upload-prompt" id="upload-prompt">
                                <input type="file" id="swal-qr-image" name="qr_image" accept=".jpg,.jpeg,.png,.svg,image/*" class="file-input-hidden" />
                                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="upload-title">Seret & lepas fail di sini</p>
                                <p class="upload-subtitle">atau</p>
                                <span class="upload-button" id="select-file-btn">Pilih Fail untuk Dimuat Naik</span>
                                <p class="upload-formats">JPEG, PNG, SVG &bull; Maks 2MB</p>
                            </div>
                            <div class="upload-preview" id="upload-preview" style="display:none;">
                                <img class="preview-image" id="preview-image" alt="" style="display:none;" />
                                <div class="preview-details">
                                    <p class="preview-filename" id="preview-filename"></p>
                                    <p class="preview-filesize" id="preview-filesize"></p>
                                </div>
                                <button type="button" class="preview-remove" id="preview-remove" title="Buang imej">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Status Aktif</span>
                            <div class="toggle-track ${active ? 'active' : ''}" id="swal-toggle" role="button" tabindex="0"><span class="toggle-thumb"></span></div>
                        </div>
                        <input type="hidden" id="swal-active" name="is_active" value="${active ? '1' : '0'}" />
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d9488',
            width: '420px',
            customClass: { popup: 'account-edit-swal' },
            didOpen: function() {
                const modalEl = document.querySelector('.account-edit-swal');
                bindToggleInModal(modalEl);
                initializeFileUpload('qr-upload-zone', 'swal-qr-image', 'upload-prompt', 'upload-preview');

                // Prefill existing QR preview for edit
                if (hasQr && existingQrUrl) {
                    const uploadZone = modalEl.querySelector('#qr-upload-zone');
                    if (uploadZone) {
                        const prompt = uploadZone.querySelector('#upload-prompt');
                        const preview = uploadZone.querySelector('#upload-preview');
                        const previewImg = uploadZone.querySelector('#preview-image');
                        const previewFilenameEl = uploadZone.querySelector('#preview-filename');
                        const previewFilesizeEl = uploadZone.querySelector('#preview-filesize');

                        if (preview && previewImg && previewFilenameEl && previewFilesizeEl) {
                            if (prompt) {
                                prompt.classList.add('hidden');
                                prompt.style.display = 'none';
                            }

                            preview.classList.remove('hidden');
                            preview.style.display = 'flex';

                            previewImg.src = existingQrUrl;
                            previewImg.alt = 'Imej QR sedia ada';
                            previewImg.style.display = 'block';

                            previewFilenameEl.textContent = 'Imej QR sedia ada';
                            previewFilesizeEl.textContent = '';
                        }
                    }
                }
            },
            preConfirm: function() {
                const name = (document.getElementById('swal-account-name').value || '').trim();
                const number = (document.getElementById('swal-account-number').value || '').trim();
                if (!name) { Swal.showValidationMessage('Nama akaun wajib diisi.'); return false; }
                if (!number) { Swal.showValidationMessage('No. akaun wajib diisi.'); return false; }
                return true;
            }
        }).then(function(result) {
            if (!result.isConfirmed) return;
            const form = document.getElementById('edit-account-form');
            const formData = new FormData(form);
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');
            formData.append('account_name', document.getElementById('swal-account-name').value);
            formData.append('account_number', document.getElementById('swal-account-number').value);
            formData.append('is_active', document.getElementById('swal-active').value);
            const fileInput = document.getElementById('swal-qr-image');
            if (fileInput && fileInput.files && fileInput.files[0]) formData.append('qr_image', fileInput.files[0]);

            fetch(accountBaseUrl + '/' + id, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Akaun telah dikemas kini.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && (data.errors.account_name && data.errors.account_name[0]) || (data.errors.account_number && data.errors.account_number[0]) || (data.errors.qr_image && data.errors.qr_image[0])) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
        });
    });

    $(document).on('click', '.btn-delete-account', function() {
        const id = $(this).data('id');
        const accountName = $(this).data('account-name');

        Swal.fire({
            title: 'Padam Akaun?',
            text: 'Adakah anda pasti mahu memadam akaun "' + accountName + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            customClass: { popup: 'account-edit-swal' }
        }).then(function(result) {
            if (!result.isConfirmed) return;
            fetch(accountBaseUrl + '/' + id, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ _token: csrfToken, _method: 'DELETE' }).toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Akaun berjaya dipadam.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else { Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam akaun.' }); }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
        });
    });

    $(document).on('click', '.btn-toggle-status', function() {
        const btn = $(this);
        const id = btn.data('id');
        const rowData = table.row(btn.closest('tr')).data();
        const accountName = (rowData && rowData.actions && rowData.actions.account_name) ? rowData.actions.account_name : '';
        const accountNumber = (rowData && rowData.actions && rowData.actions.account_number) ? rowData.actions.account_number : '';
        const newActive = btn.data('active') != 1;

        const formData = new URLSearchParams();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('account_name', accountName);
        formData.append('account_number', accountNumber);
        formData.append('is_active', newActive ? '1' : '0');

        fetch(accountBaseUrl + '/' + id, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
        .then(function({ ok, data }) {
            if (ok && data.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Status dikemas kini', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                table.ajax.reload(null, false);
            } else { Swal.fire({ icon: 'error', title: 'Ralat', text: (data.data && data.data.message) || data.message || 'Gagal mengemaskini status.' }); }
        })
        .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
    });
});
</script>
@endpush
