@extends('layouts.app')

@section('title', 'Kawalan Yuran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Kawalan Yuran</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus senarai yuran</p>
            </div>
        </div>
        <button type="button" class="btn-create-yuran group relative inline-flex items-center justify-center py-3 px-6 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 overflow-hidden">
            <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
            <svg class="relative w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="relative">Tambah Yuran</span>
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6 transform transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <table id="yuran-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Yuran</th>
                    <th>Jumlah (MYR)</th>
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
/* Custom DataTable Styling */
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb; padding: 1rem; color: #4b5563; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background-color: #f9fafb; text-align: left; transition: background-color 0.2s; }
.dark table.dataTable thead th { border-bottom-color: #374151; color: #d1d5db; background-color: #1f2937; }
table.dataTable tbody tr { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease-in-out; }
table.dataTable tbody tr:hover { background-color: #f9fafb; transform: scale-[1.002]; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); z-index: 10; position: relative; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; transform: scale-[1.002]; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1); }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }
.dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; outline: none; transition: all 0.2s; background: #fff; color: #111827; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dark .dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.4); }
.dataTables_wrapper .dataTables_length { margin-bottom: 1rem; color: #6b7280; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 2rem 0.375rem 0.75rem; outline: none; background: #fff; color: #111827; margin: 0 0.25rem; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; padding-top: 1rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top: 1rem; margin-top: 0.5rem; display: flex; gap: 0.25rem; justify-content: flex-end; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 2px; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6; color: #111827 !important; border-color: #d1d5db; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #e0e7ff; color: #4338ca !important; border-color: #c7d2fe; font-weight: 600; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; background: #f9fafb; color: #9ca3af !important; border-color: #e5e7eb; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #4b5563; color: #fff !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5; color: #fff !important; border-color: #4338ca; }

table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc { background-image: none !important; position: relative; padding-right: 1.5rem !important; cursor: pointer; }
table.dataTable thead .sorting::after, table.dataTable thead .sorting_asc::after, table.dataTable thead .sorting_desc::after { content: ''; position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); width: 0.75rem; height: 0.75rem; opacity: 0.5; background-size: contain; background-repeat: no-repeat; background-position: center; }
table.dataTable thead .sorting::after { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'/%3E%3C/svg%3E"); }
table.dataTable thead .sorting_asc::after { opacity: 1; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234f46e5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 15l7-7 7 7'/%3E%3C/svg%3E"); }
table.dataTable thead .sorting_desc::after { opacity: 1; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234f46e5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); }
.dark table.dataTable thead .sorting_asc::after, .dark table.dataTable thead .sorting_desc::after { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 15l7-7 7 7'/%3E%3C/svg%3E"); }
.dark table.dataTable thead .sorting_desc::after { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); }

/* Edit Yuran SweetAlert modal */
.yuran-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.yuran-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.yuran-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.yuran-edit-swal .yuran-edit-form .field { margin-bottom: 1.25rem; }
.yuran-edit-swal .yuran-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.yuran-edit-swal .yuran-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.yuran-edit-swal .yuran-edit-form .input-text:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
.yuran-edit-swal .yuran-edit-form .input-text::placeholder { color: #9ca3af; }
.yuran-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.yuran-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; cursor: pointer; }
.yuran-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.yuran-edit-swal .toggle-track.active { background: #6366f1; }
.yuran-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.yuran-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
.yuran-edit-swal .swal2-actions { padding: 0 1.5rem 1.5rem; gap: 0.75rem; }
.yuran-edit-swal .swal2-confirm { border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
.yuran-edit-swal .swal2-cancel { border-radius: 0.5rem; padding: 0.5rem 1.25rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function escapeAttr(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function renderStatusBadge(row) {
        const id = row.id;
        const active = !!row.is_active;
        const label = active ? 'Aktif' : 'Tidak aktif';
        const bgClass = active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
        const dotClass = active ? 'bg-green-500' : 'bg-gray-400';
        return `<button type="button" class="btn-toggle-status inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full ${bgClass} hover:opacity-80 transition cursor-pointer"
            data-id="${id}" data-active="${active ? '1' : '0'}" data-jenis-yuran="${escapeAttr(row.jenis_yuran)}" title="Klik untuk tukar status">
            <span class="w-1.5 h-1.5 rounded-full ${dotClass}"></span> ${label}</button>`;
    }

    function renderActionsButton(row) {
        const a = row.actions || {};
        const id = a.id;
        const jenisYuran = escapeAttr(a.jenis_yuran);
        const jumlah = a.jumlah != null ? String(a.jumlah) : '';
        const active = a.is_active ? '1' : '0';
        return '<div class="flex items-center gap-2">' +
            '<button type="button" class="btn-edit-yuran inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" ' +
            'data-id="' + id + '" data-jenis-yuran="' + jenisYuran + '" data-jumlah="' + escapeAttr(jumlah) + '" data-active="' + active + '">Edit</button>' +
            '<button type="button" class="btn-delete-yuran inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" ' +
            'data-id="' + id + '" data-jenis-yuran="' + jenisYuran + '">Padam</button></div>';
    }

    const table = $('#yuran-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.kawalan.yuran.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '80px' },
            { data: 'jenis_yuran', name: 'jenis_yuran' },
            { data: 'jumlah_formatted', name: 'jumlah', orderable: true, searchable: false },
            { data: 'is_active', name: 'is_active', orderable: false, render: function(d, type, row) { return renderStatusBadge(row); } },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(d, type, row) { return renderActionsButton(row); } }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        initComplete: function() {
            $('.dataTables_filter input').attr('placeholder', 'Cari jenis yuran…');
        },
        language: {
            processing: 'Memuatkan...',
            search: 'Cari:',
            lengthMenu: 'Papar _MENU_ rekod',
            info: 'Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod',
            infoFiltered: '(ditapis daripada _MAX_ rekod)',
            paginate: {
                first: 'Pertama',
                last: 'Akhir',
                next: 'Seterusnya',
                previous: 'Sebelumnya'
            },
            zeroRecords: 'Tiada rekod sepadan'
        }
    });

    $(document).on('click', '.btn-edit-yuran', function() {
        const id = $(this).data('id');
        const jenisYuran = $(this).data('jenis-yuran');
        const jumlah = $(this).data('jumlah');
        const active = $(this).data('active') === 1 || $(this).data('active') === '1';
        const safeJenis = (jenisYuran || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Yuran</span>',
            html: `
                <form id="edit-yuran-form" class="yuran-edit-form">
                    <div class="field">
                        <label for="swal-jenis">Jenis Yuran</label>
                        <input type="text" id="swal-jenis" class="input-text" name="jenis_yuran" value="${safeJenis}" placeholder="e.g. Yuran Tahunan" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label for="swal-jumlah">Jumlah (RM)</label>
                        <input type="number" id="swal-jumlah" class="input-text" name="jumlah" value="${escapeAttr(jumlah)}" placeholder="0.00" min="0" step="0.01" autocomplete="off" />
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Status</span>
                            <div class="toggle-track ${active ? 'active' : ''}" id="swal-toggle" role="button" tabindex="0" aria-pressed="${active}">
                                <span class="toggle-thumb"></span>
                            </div>
                        </div>
                        <input type="hidden" id="swal-active" name="is_active" value="${active ? '1' : '0'}" />
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<span style="display:inline-flex;align-items:center;gap:0.35rem;">Simpan</span>',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1',
            width: '420px',
            customClass: { popup: 'yuran-edit-swal' },
            showClass: { popup: 'swal2-show', backdrop: 'swal2-backdrop-show' },
            didOpen: function() {
                const input = document.getElementById('swal-jenis');
                const toggle = document.getElementById('swal-toggle');
                const hidden = document.getElementById('swal-active');
                if (input) {
                    input.focus();
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') { e.preventDefault(); Swal.clickConfirm(); }
                    });
                }
                if (toggle && hidden) {
                    toggle.addEventListener('click', function() {
                        const isActive = toggle.classList.toggle('active');
                        hidden.value = isActive ? '1' : '0';
                    });
                    toggle.addEventListener('keydown', function(e) {
                        if (e.key === ' ' || e.key === 'Enter') {
                            e.preventDefault();
                            toggle.click();
                        }
                    });
                }
            },
            preConfirm: function() {
                const jenisVal = (document.getElementById('swal-jenis').value || '').trim();
                const jumlahVal = document.getElementById('swal-jumlah').value;
                if (!jenisVal) {
                    Swal.showValidationMessage('Jenis yuran wajib diisi.');
                    return false;
                }
                if (jumlahVal === '' || isNaN(parseFloat(jumlahVal)) || parseFloat(jumlahVal) < 0) {
                    Swal.showValidationMessage('Jumlah wajib diisi dan mesti sekurang-kurangnya 0.');
                    return false;
                }
                const isActive = document.getElementById('swal-active').value === '1';
                return { jenis_yuran: jenisVal, jumlah: parseFloat(jumlahVal), is_active: isActive };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;

            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');
            formData.append('jenis_yuran', result.value.jenis_yuran);
            formData.append('jumlah', result.value.jumlah);
            formData.append('is_active', result.value.is_active ? '1' : '0');

            fetch('{{ url("admin/kawalan/yuran") }}/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Yuran telah dikemas kini.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && (data.errors.jenis_yuran && data.errors.jenis_yuran[0]) || (data.errors.jumlah && data.errors.jumlah[0])) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });

    $(document).on('click', '.btn-create-yuran', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Yuran</span>',
            html: `
                <form id="create-yuran-form" class="yuran-edit-form">
                    <div class="field">
                        <label for="swal-jenis">Jenis Yuran</label>
                        <input type="text" id="swal-jenis" class="input-text" name="jenis_yuran" value="" placeholder="e.g. Yuran Tahunan" autocomplete="off" />
                    </div>
                    <div class="field">
                        <label for="swal-jumlah">Jumlah (RM)</label>
                        <input type="number" id="swal-jumlah" class="input-text" name="jumlah" value="" placeholder="0.00" min="0" step="0.01" autocomplete="off" />
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Status</span>
                            <div class="toggle-track active" id="swal-toggle" role="button" tabindex="0" aria-pressed="true">
                                <span class="toggle-thumb"></span>
                            </div>
                        </div>
                        <input type="hidden" id="swal-active" name="is_active" value="1" />
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<span style="display:inline-flex;align-items:center;gap:0.35rem;">Simpan</span>',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1',
            width: '420px',
            customClass: { popup: 'yuran-edit-swal' },
            showClass: { popup: 'swal2-show', backdrop: 'swal2-backdrop-show' },
            didOpen: function() {
                const input = document.getElementById('swal-jenis');
                const toggle = document.getElementById('swal-toggle');
                const hidden = document.getElementById('swal-active');
                if (input) {
                    input.focus();
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') { e.preventDefault(); Swal.clickConfirm(); }
                    });
                }
                if (toggle && hidden) {
                    toggle.addEventListener('click', function() {
                        const isActive = toggle.classList.toggle('active');
                        hidden.value = isActive ? '1' : '0';
                    });
                    toggle.addEventListener('keydown', function(e) {
                        if (e.key === ' ' || e.key === 'Enter') {
                            e.preventDefault();
                            toggle.click();
                        }
                    });
                }
            },
            preConfirm: function() {
                const jenisVal = (document.getElementById('swal-jenis').value || '').trim();
                const jumlahVal = document.getElementById('swal-jumlah').value;
                if (!jenisVal) {
                    Swal.showValidationMessage('Jenis yuran wajib diisi.');
                    return false;
                }
                if (jumlahVal === '' || isNaN(parseFloat(jumlahVal)) || parseFloat(jumlahVal) < 0) {
                    Swal.showValidationMessage('Jumlah wajib diisi dan mesti sekurang-kurangnya 0.');
                    return false;
                }
                const isActive = document.getElementById('swal-active').value === '1';
                return { jenis_yuran: jenisVal, jumlah: parseFloat(jumlahVal), is_active: isActive };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;

            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            formData.append('jenis_yuran', result.value.jenis_yuran);
            formData.append('jumlah', result.value.jumlah);
            formData.append('is_active', result.value.is_active ? '1' : '0');

            fetch('{{ route("admin.kawalan.yuran.store") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Yuran berjaya ditambah.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && (data.errors.jenis_yuran && data.errors.jenis_yuran[0]) || (data.errors.jumlah && data.errors.jumlah[0])) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });

    $(document).on('click', '.btn-delete-yuran', function() {
        const id = $(this).data('id');
        const jenisYuran = $(this).data('jenis-yuran');

        Swal.fire({
            title: 'Padam Yuran?',
            text: `Adakah anda pasti mahu memadam yuran "${jenisYuran}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            customClass: { popup: 'yuran-edit-swal' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("admin/kawalan/yuran") }}/' + id, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        '_token': csrfToken,
                        '_method': 'DELETE'
                    }).toString()
                })
                .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
                .then(function({ ok, data }) {
                    if (ok && data.success) {
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Yuran berjaya dipadam.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam yuran.' });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                });
            }
        });
    });

    $(document).on('click', '.btn-toggle-status', function() {
        const btn = $(this);
        const id = btn.data('id');
        const rowData = table.row(btn.closest('tr')).data();
        const jenisYuran = (rowData && rowData.actions && rowData.actions.jenis_yuran) ? rowData.actions.jenis_yuran : '';
        const jumlah = (rowData && rowData.actions && rowData.actions.jumlah != null) ? rowData.actions.jumlah : 0;
        const newActive = btn.data('active') != 1;

        const formData = new URLSearchParams();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('jenis_yuran', jenisYuran);
        formData.append('jumlah', jumlah);
        formData.append('is_active', newActive ? '1' : '0');

        fetch('{{ url("admin/kawalan/yuran") }}/' + id, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
        .then(function({ ok, data }) {
            if (ok && data.success) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                toast.fire({
                    icon: 'success',
                    title: 'Status dikemas kini'
                });
                table.ajax.reload(null, false);
            } else {
                Swal.fire({ icon: 'error', title: 'Ralat', text: (data.data && data.data.message) || data.message || 'Gagal mengemaskini status.' });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
        });
    });
});
</script>
@endpush
