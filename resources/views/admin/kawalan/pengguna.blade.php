@extends('layouts.app')

@section('title', 'Kawalan Pengguna')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Kawalan Pengguna'])
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-center gap-4 group">
                <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kawalan Pengguna</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus pengguna dan jemputan pendaftaran</p>
                </div>
            </div>
            <button type="button" class="btn-create-user group relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="relative mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">Tambah / Jemput</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6 transform transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <table id="pengguna-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>E-mel</th>
                    <th>No. KP</th>
                    <th>Peranan</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<style>
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb; padding: 1rem; color: #4b5563; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background-color: #f9fafb; text-align: left; transition: background-color 0.2s; }
.dark table.dataTable thead th { border-bottom-color: #374151; color: #d1d5db; background-color: #1f2937; }
table.dataTable tbody tr { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease-in-out; }
table.dataTable tbody tr:hover { background-color: #f9fafb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); z-index: 10; position: relative; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }
.dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; outline: none; transition: all 0.2s; background: #fff; color: #111827; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2); }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_length { margin-bottom: 1rem; color: #6b7280; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 2rem 0.375rem 0.75rem; outline: none; background: #fff; color: #111827; margin: 0 0.25rem; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; padding-top: 1rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top: 1rem; margin-top: 0.5rem; display: flex; gap: 0.25rem; justify-content: flex-end; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 2px; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6; color: #111827 !important; border-color: #d1d5db; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #ede9fe; color: #5b21b6 !important; border-color: #ddd6fe; font-weight: 600; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; background: #f9fafb; color: #9ca3af !important; border-color: #e5e7eb; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #4b5563; color: #fff !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #7c3aed; color: #fff !important; border-color: #6d28d9; }
table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc { background-image: none !important; position: relative; padding-right: 1.5rem !important; cursor: pointer; }
.pengguna-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.pengguna-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; font-size: 1.25rem; }
.pengguna-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.pengguna-edit-swal .field { margin-bottom: 1.25rem; }
.pengguna-edit-swal label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.pengguna-edit-swal .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.pengguna-edit-swal .input-text:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2); }
.pengguna-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.pengguna-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; cursor: pointer; }
.pengguna-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.pengguna-edit-swal .toggle-track.active { background: #7c3aed; }
.pengguna-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.pengguna-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.penggunaCurrentUserId = {{ auth()->id() }};
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

    function escapeHtmlText(s) {
        if (s == null || s === '') return '';
        const d = document.createElement('div');
        d.textContent = String(s);
        return d.innerHTML;
    }

    function renderNoKpCell(data) {
        if (data == null || data === '') {
            return '<span class="text-gray-400 dark:text-gray-500">—</span>';
        }
        return escapeHtmlText(data);
    }

    function renderActions(row) {
        const a = row.actions || {};
        const iconEdit = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
        const iconTrash = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
        const iconResend = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>';
        if (a.row_type === 'user') {
            const id = a.id;
            const isSelf = id === window.penggunaCurrentUserId;
            const name = escapeAttr(a.name);
            const email = escapeAttr(a.email);
            const noKp = escapeAttr(a.no_kp || '');
            const role = a.role === 1 ? '1' : '0';
            let btns = '<div class="flex flex-wrap items-center gap-1.5">' +
                '<button type="button" class="btn-edit-user inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition shadow-sm hover:shadow" ' +
                'title="Edit pengguna" aria-label="Edit pengguna" data-id="' + id + '" data-name="' + name + '" data-email="' + email + '" data-no-kp="' + noKp + '" data-role="' + role + '">' + iconEdit + '</button>';
            if (!isSelf) {
                btns += '<button type="button" class="btn-delete-user inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition shadow-sm hover:shadow" ' +
                    'title="Padam pengguna" aria-label="Padam pengguna" data-id="' + id + '" data-name="' + name + '">' + iconTrash + '</button>';
            }
            btns += '</div>';
            return btns;
        }
        const id = a.id;
        const expired = a.expired;
        let html = '<div class="flex flex-wrap items-center gap-1.5">';
        if (!expired) {
            html += '<button type="button" class="btn-resend-invite inline-flex items-center justify-center w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300 hover:bg-sky-200 dark:hover:bg-sky-800/40 transition shadow-sm hover:shadow" title="Hantar semula jemputan" aria-label="Hantar semula jemputan" data-id="' + id + '">' + iconResend + '</button>';
        }
        html += '<button type="button" class="btn-delete-invite inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition shadow-sm hover:shadow" title="Buang jemputan" aria-label="Buang jemputan" data-id="' + id + '">' + iconTrash + '</button></div>';
        return html;
    }

    const table = $('#pengguna-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.kawalan.pengguna.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'id_display', name: 'id', width: '90px' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'no_kp', name: 'no_kp', width: '120px', render: function(d) { return renderNoKpCell(d); } },
            { data: 'role_badge', name: 'role', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: true, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(d, t, row) { return renderActions(row); } }
        ],
        order: [[1, 'asc']],
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Semua']],
        initComplete: function() {
            $('.dataTables_filter input').attr('placeholder', 'Cari nama, e-mel atau No. KP…');
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

    $(document).on('click', '.btn-create-user', function() {
        Swal.fire({
            title: 'Jemput pengguna baharu',
            html: `
                <form id="create-user-form" class="pengguna-edit-swal pengguna-edit-form text-left">
                    <div class="field">
                        <label for="swal-name">Nama</label>
                        <input type="text" id="swal-name" class="input-text" placeholder="Nama penuh" autocomplete="name" />
                    </div>
                    <div class="field">
                        <label for="swal-email">E-mel</label>
                        <input type="email" id="swal-email" class="input-text" placeholder="email@domain.com" autocomplete="email" />
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Peranan: Admin</span>
                            <div class="toggle-track" id="swal-role-toggle" role="button" tabindex="0" aria-pressed="false">
                                <span class="toggle-thumb"></span>
                            </div>
                        </div>
                        <input type="hidden" id="swal-role" name="role" value="0" />
                        <p class="text-xs text-gray-500 mt-1">Togol untuk Admin (1), matikan untuk Pengguna (0).</p>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Hantar jemputan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#7c3aed',
            width: '440px',
            customClass: { popup: 'pengguna-edit-swal' },
            didOpen: function() {
                const toggle = document.getElementById('swal-role-toggle');
                const hidden = document.getElementById('swal-role');
                if (toggle && hidden) {
                    toggle.addEventListener('click', function() {
                        const on = toggle.classList.toggle('active');
                        hidden.value = on ? '1' : '0';
                    });
                }
                document.getElementById('swal-name')?.focus();
            },
            preConfirm: function() {
                const name = (document.getElementById('swal-name').value || '').trim();
                const email = (document.getElementById('swal-email').value || '').trim();
                const role = document.getElementById('swal-role').value;
                if (!name) { Swal.showValidationMessage('Nama wajib diisi.'); return false; }
                if (!email) { Swal.showValidationMessage('E-mel wajib diisi.'); return false; }
                return { name: name, email: email, role: parseInt(role, 10) };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            const fd = new URLSearchParams();
            fd.append('_token', csrfToken);
            fd.append('name', result.value.name);
            fd.append('email', result.value.email);
            fd.append('role', String(result.value.role));
            fetch('{{ route("admin.kawalan.pengguna.store") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: fd.toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Jemputan dihantar.', timer: 2200, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && (Object.values(data.errors).flat()[0])) || data.message || 'Ralat.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
        });
    });

    $(document).on('click', '.btn-edit-user', function() {
        const $btn = $(this);
        const id = $btn.data('id');
        const name = $btn.data('name');
        const email = $btn.data('email');
        const noKpRaw = $btn.attr('data-no-kp') || '';
        const role = $btn.data('role') === 1 || $btn.data('role') === '1';
        const safeName = escapeAttr(name);
        const safeEmail = escapeAttr(email);
        const safeNoKp = escapeAttr(noKpRaw);

        Swal.fire({
            title: 'Edit pengguna',
            html: `
                <form class="pengguna-edit-swal pengguna-edit-form text-left">
                    <div class="field">
                        <label for="edit-name">Nama</label>
                        <input type="text" id="edit-name" class="input-text" value="${safeName}" />
                    </div>
                    <div class="field">
                        <label for="edit-email">E-mel</label>
                        <input type="email" id="edit-email" class="input-text" value="${safeEmail}" />
                    </div>
                    <div class="field">
                        <label for="edit-no-kp">No. KP</label>
                        <input type="text" id="edit-no-kp" class="input-text" value="${safeNoKp}" inputmode="numeric" maxlength="12" pattern="\\d{12}" autocomplete="off" placeholder="12 digit" />
                        <p class="text-xs text-gray-500 mt-1">Digunakan untuk tetapan semula kata laluan (lupa kata laluan). Kosongkan jika tiada.</p>
                    </div>
                    <div class="field">
                        <div class="toggle-wrap">
                            <span class="toggle-label">Peranan: Admin</span>
                            <div class="toggle-track ${role ? 'active' : ''}" id="edit-role-toggle" role="button" tabindex="0">
                                <span class="toggle-thumb"></span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-role" value="${role ? '1' : '0'}" />
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#7c3aed',
            width: '440px',
            customClass: { popup: 'pengguna-edit-swal' },
            didOpen: function() {
                const toggle = document.getElementById('edit-role-toggle');
                const hidden = document.getElementById('edit-role');
                if (toggle && hidden) {
                    toggle.addEventListener('click', function() {
                        const on = toggle.classList.toggle('active');
                        hidden.value = on ? '1' : '0';
                    });
                }
            },
            preConfirm: function() {
                const n = (document.getElementById('edit-name').value || '').trim();
                const e = (document.getElementById('edit-email').value || '').trim();
                const nk = (document.getElementById('edit-no-kp').value || '').trim();
                const r = document.getElementById('edit-role').value === '1';
                if (!n || !e) { Swal.showValidationMessage('Nama dan e-mel wajib diisi.'); return false; }
                return { name: n, email: e, no_kp: nk, role: r };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;
            const fd = new URLSearchParams();
            fd.append('_token', csrfToken);
            fd.append('_method', 'PUT');
            fd.append('name', result.value.name);
            fd.append('email', result.value.email);
            fd.append('no_kp', result.value.no_kp || '');
            fd.append('role', result.value.role ? '1' : '0');
            fetch(@json(url('admin/kawalan/pengguna/user')).replace(/\/$/, '') + '/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: fd.toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Dikemas kini.', timer: 2000, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    let msg = data.message || 'Gagal menyimpan.';
                    if (data.errors) {
                        const flat = Object.values(data.errors).flat();
                        if (flat.length) msg = flat[0];
                    }
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }); });
        });
    });

    $(document).on('click', '.btn-delete-user', function() {
        const id = $(this).data('id');
        const nama = $(this).data('name');
        Swal.fire({
            title: 'Padam pengguna?',
            text: 'Padam "' + nama + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, padam',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            fetch(@json(url('admin/kawalan/pengguna/user')).replace(/\/$/, '') + '/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ '_token': csrfToken, '_method': 'DELETE' }).toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message, timer: 2000, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal.' });
                }
            });
        });
    });

    $(document).on('click', '.btn-delete-invite', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Buang jemputan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            fetch('{{ url('admin/kawalan/pengguna/jemputan') }}/' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ '_token': csrfToken, '_method': 'DELETE' }).toString()
            })
            .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
            .then(function({ ok, data }) {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message, timer: 2000, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal.' });
                }
            });
        });
    });

    $(document).on('click', '.btn-resend-invite', function() {
        const id = $(this).data('id');
        fetch(@json(url('admin/kawalan/pengguna/jemputan')).replace(/\/$/, '') + '/' + id + '/hantar', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ '_token': csrfToken }).toString()
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
        .then(function({ ok, data }) {
            if (ok && data.success) {
                Swal.fire({ icon: 'success', title: 'Dihantar', text: data.message, timer: 2200, showConfirmButton: false });
                table.ajax.reload(null, false);
            } else {
                Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal.' });
            }
        });
    });
});
</script>
@endpush
