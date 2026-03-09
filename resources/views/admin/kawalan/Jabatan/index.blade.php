@extends('layouts.app')

@section('title', 'Kawalan Jabatan')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl gradient-bg shadow-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kawalan Jabatan</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Urus senarai jabatan</p>
            </div>
        </div>
        <button type="button" class="btn-create-jabatan inline-flex items-center justify-center py-2.5 px-5 border border-transparent text-sm font-semibold rounded-lg text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Jabatan
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
        <table id="jabatan-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jabatan</th>
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
.dataTables_wrapper { padding: 1.5rem; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.25rem 2rem 0.25rem 0.5rem; outline: none; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.4rem 0.75rem; margin-left: 0.5rem; outline: none; transition: all 0.2s; }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 1rem 0 !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb !important; padding: 0.75rem 1rem !important; text-align: left; font-weight: 600; color: #374151; }
table.dataTable tbody td { padding: 0.75rem 1rem !important; border-bottom: 1px solid #f3f4f6; color: #4b5563; vertical-align: middle; }
table.dataTable.display tbody tr:hover { background-color: #f9fafb; }
table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb; }

/* Edit Jabatan SweetAlert modal */
.jabatan-edit-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.jabatan-edit-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.jabatan-edit-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.jabatan-edit-swal .jabatan-edit-form .field { margin-bottom: 1.25rem; }
.jabatan-edit-swal .jabatan-edit-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.jabatan-edit-swal .jabatan-edit-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.jabatan-edit-swal .jabatan-edit-form .input-text:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
.jabatan-edit-swal .jabatan-edit-form .input-text::placeholder { color: #9ca3af; }
.jabatan-edit-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; }
.jabatan-edit-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; cursor: pointer; }
.jabatan-edit-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.jabatan-edit-swal .toggle-track.active { background: #6366f1; }
.jabatan-edit-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.jabatan-edit-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
.jabatan-edit-swal .swal2-actions { padding: 0 1.5rem 1.5rem; gap: 0.75rem; }
.jabatan-edit-swal .swal2-confirm { border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
.jabatan-edit-swal .swal2-cancel { border-radius: 0.5rem; padding: 0.5rem 1.25rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Status badge HTML - clickable to toggle
    function renderStatusBadge(row) {
        const id = row.id;
        const active = !!row.is_active; // Make sure it's boolean
        const label = active ? 'Aktif' : 'Tidak aktif';
        const bgClass = active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
        const dotClass = active ? 'bg-green-500' : 'bg-gray-400';
        
        return `<button type="button" class="btn-toggle-status inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full ${bgClass} hover:opacity-80 transition cursor-pointer" 
            data-id="${id}" data-active="${active ? '1' : '0'}" data-nama="${escapeAttr(row.nama_jabatan)}" title="Klik untuk tukar status">
            <span class="w-1.5 h-1.5 rounded-full ${dotClass}"></span> ${label}</button>`;
    }

    // Actions button HTML (was actions partial) — escape attr for safety
    function escapeAttr(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
    function renderActionsButton(row) {
        const id = row.id;
        const nama = escapeAttr(row.nama_jabatan);
        const active = row.is_active ? '1' : '0';
        return '<div class="flex items-center gap-2">' +
            '<button type="button" class="btn-edit-jabatan inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" ' +
            'data-id="' + id + '" data-nama="' + nama + '" data-active="' + active + '">Edit</button>' +
            '<button type="button" class="btn-delete-jabatan inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition" ' +
            'data-id="' + id + '" data-nama="' + nama + '">Padam</button></div>';
    }

    const table = $('#jabatan-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.kawalan.jabatan.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', width: '80px' },
            { data: 'nama_jabatan', name: 'nama_jabatan' },
            { data: 'is_active', name: 'is_active', orderable: false, render: function(d, type, row) { return renderStatusBadge(row); } },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(d) { return renderActionsButton(d); } }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        initComplete: function() {
            $('.dataTables_filter input').attr('placeholder', 'Cari jabatan…');
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

    $(document).on('click', '.btn-edit-jabatan', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const active = $(this).data('active') === 1 || $(this).data('active') === '1';
        const safeNama = (nama || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Jabatan</span>',
            html: `
                <form id="edit-jabatan-form" class="jabatan-edit-form">
                    <div class="field">
                        <label for="swal-nama">Nama Jabatan</label>
                        <input type="text" id="swal-nama" class="input-text" name="nama_jabatan" value="${safeNama}" placeholder="e.g. Bendahari, Setiausaha" autocomplete="off" />
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
            customClass: { popup: 'jabatan-edit-swal' },
            showClass: { popup: 'swal2-show', backdrop: 'swal2-backdrop-show' },
            didOpen: function() {
                const popup = document.querySelector('.jabatan-edit-swal');
                const input = document.getElementById('swal-nama');
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
                const namaVal = (document.getElementById('swal-nama').value || '').trim();
                if (!namaVal) {
                    Swal.showValidationMessage('Nama jabatan wajib diisi.');
                    return false;
                }
                const isActive = document.getElementById('swal-active').value === '1';
                return { nama_jabatan: namaVal, is_active: isActive };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;

            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');
            formData.append('nama_jabatan', result.value.nama_jabatan);
            formData.append('is_active', result.value.is_active ? '1' : '0');

            fetch('{{ url("admin/kawalan/jabatan") }}/' + id, {
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
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Jabatan telah dikemas kini.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && data.errors.nama_jabatan && data.errors.nama_jabatan[0]) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });

    // Create Jabatan Handler
    $(document).on('click', '.btn-create-jabatan', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg> Tambah Jabatan</span>',
            html: `
                <form id="create-jabatan-form" class="jabatan-edit-form">
                    <div class="field">
                        <label for="swal-nama">Nama Jabatan</label>
                        <input type="text" id="swal-nama" class="input-text" name="nama_jabatan" value="" placeholder="e.g. Bendahari, Setiausaha" autocomplete="off" />
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
            customClass: { popup: 'jabatan-edit-swal' },
            showClass: { popup: 'swal2-show', backdrop: 'swal2-backdrop-show' },
            didOpen: function() {
                const input = document.getElementById('swal-nama');
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
                const namaVal = (document.getElementById('swal-nama').value || '').trim();
                if (!namaVal) {
                    Swal.showValidationMessage('Nama jabatan wajib diisi.');
                    return false;
                }
                const isActive = document.getElementById('swal-active').value === '1';
                return { nama_jabatan: namaVal, is_active: isActive };
            }
        }).then(function(result) {
            if (!result.isConfirmed || !result.value) return;

            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            formData.append('nama_jabatan', result.value.nama_jabatan);
            formData.append('is_active', result.value.is_active ? '1' : '0');

            fetch('{{ route("admin.kawalan.jabatan.store") }}', {
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
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Jabatan berjaya ditambah.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    const msg = (data.errors && data.errors.nama_jabatan && data.errors.nama_jabatan[0]) || data.message || 'Ralat semasa menyimpan.';
                    Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });

    // Delete Jabatan Handler
    $(document).on('click', '.btn-delete-jabatan', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        Swal.fire({
            title: 'Padam Jabatan?',
            text: `Adakah anda pasti mahu memadam jabatan "${nama}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam',
            cancelButtonText: 'Batal',
            customClass: { popup: 'jabatan-edit-swal' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("admin/kawalan/jabatan") }}/' + id, {
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
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Jabatan berjaya dipadam.', timer: 2000, timerProgressBar: true, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam jabatan.' });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                });
            }
        });
    });

    // Toggle Status Handler
    $(document).on('click', '.btn-toggle-status', function() {
        const btn = $(this);
        const id = btn.data('id');
        const nama = btn.data('nama');
        const currentActive = btn.data('active') == 1;
        const newActive = !currentActive;

        const formData = new URLSearchParams();
        formData.append('_token', csrfToken);
        formData.append('_method', 'PUT');
        formData.append('nama_jabatan', nama);
        formData.append('is_active', newActive ? '1' : '0');

        fetch('{{ url("admin/kawalan/jabatan") }}/' + id, {
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
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
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
