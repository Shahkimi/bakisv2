 @extends('layouts.app')

@section('title', 'Kawalan Acara')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Kawalan Acara'])
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-center gap-4 group">
                <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kawalan Acara</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus acara, jana poster QR &amp; rekod kehadiran</p>
                </div>
            </div>
            <button type="button" class="btn-create-acara group relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="relative mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="relative">Tambah Acara</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden p-6 transform transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <table id="acara-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Acara</th>
                    <th>Waktu</th>
                    <th>Pautan</th>
                    <th>Tamat Tempoh</th>
                    <th>Hadir</th>
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
table.dataTable tbody tr:hover { background-color: #f9fafb; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }
.dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; outline: none; transition: all 0.2s; background: #fff; color: #111827; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_length { margin-bottom: 1rem; color: #6b7280; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 2rem 0.375rem 0.75rem; outline: none; background: #fff; color: #111827; margin: 0 0.25rem; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; padding-top: 1rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top: 1rem; margin-top: 0.5rem; display: flex; gap: 0.25rem; justify-content: flex-end; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 2px; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6; color: #111827 !important; border-color: #d1d5db; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #e0e7ff; color: #4338ca !important; border-color: #c7d2fe; font-weight: 600; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #4b5563; color: #fff !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5; color: #fff !important; border-color: #4338ca; }

.acara-swal.swal2-popup { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
.acara-swal .swal2-title { padding: 1.25rem 1.5rem 0.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1.25rem; }
.acara-swal .swal2-html-container { margin: 0; padding: 0 1.5rem 1.5rem; text-align: left; }
.acara-swal .acara-form .field { margin-bottom: 1.1rem; }
.acara-swal .acara-form label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.4rem; }
.acara-swal .acara-form .input-text { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9375rem; transition: border-color 0.2s, box-shadow 0.2s; }
.acara-swal .acara-form .input-text:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
.acara-swal .acara-form .hint { font-size: 0.75rem; color: #9ca3af; margin-top: 0.3rem; }
.acara-swal .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; }
.acara-swal .toggle-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; cursor: pointer; }
.acara-swal .toggle-track { width: 2.75rem; height: 1.5rem; background: #e5e7eb; border-radius: 9999px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
.acara-swal .toggle-track.active { background: #6366f1; }
.acara-swal .toggle-thumb { position: absolute; top: 0.25rem; left: 0.25rem; width: 1rem; height: 1rem; background: #fff; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: transform 0.2s; }
.acara-swal .toggle-track.active .toggle-thumb { transform: translateX(1.25rem); }
.acara-swal .swal2-actions { padding: 0 1.5rem 1.5rem; gap: 0.75rem; }
.acara-swal .swal2-confirm { border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
.acara-swal .swal2-cancel { border-radius: 0.5rem; padding: 0.5rem 1.25rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const baseUrl = '{{ url("admin/kawalan/acara") }}';

    function escapeAttr(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });

    function renderAcara(row) {
        return '<div class="font-semibold text-gray-900 dark:text-white">' + escapeAttr(row.nama_acara) + '</div>' +
            '<div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' +
            escapeAttr(row.lokasi) + '</div>';
    }

    function renderLink(row) {
        return '<button type="button" class="btn-copy-link inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition" data-url="' + escapeAttr(row.public_url) + '" title="Salin pautan">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>' +
            '<span class="font-mono">/' + escapeAttr(row.code) + '</span></button>';
    }

    function renderExpiry(row) {
        const open = !!row.is_open;
        const badge = open
            ? '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif</span>'
            : '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Tamat Tempoh</span>';
        return '<div class="text-sm text-gray-700 dark:text-gray-300">' + escapeAttr(row.expires_at_formatted) + '</div>' +
            '<div class="mt-1">' + badge + '</div>';
    }

    function renderCount(row) {
        return '<span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1 text-sm font-semibold rounded-lg bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">' + (row.kehadiran_count || 0) + '</span>';
    }

    function renderActions(row) {
        const d = row.actions;
        const id = d.id;
        const btn = (cls, href, title, icon, extra) =>
            (href ? '<a href="' + href + '" ' : '<button type="button" ') +
            'class="' + cls + ' inline-flex items-center justify-center w-9 h-9 rounded-lg transition shadow-sm hover:shadow" title="' + title + '" aria-label="' + title + '" ' + (extra || '') + '>' + icon +
            (href ? '</a>' : '</button>');

        const iPoster = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 16h4m10 0h4M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>';
        const iList = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>';
        const iPdf = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        const iEdit = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
        const iTrash = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

        return '<div class="flex flex-wrap items-center gap-1.5">' +
            btn('bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800/50', baseUrl + '/' + id + '/poster', 'Muat turun poster', iPoster) +
            btn('bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800/50', baseUrl + '/' + id + '/kehadiran', 'Lihat kehadiran', iList) +
            btn('bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 dark:hover:bg-emerald-800/50', baseUrl + '/' + id + '/kehadiran/pdf', 'Muat turun senarai kehadiran', iPdf) +
            btn('btn-edit-acara bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600', null, 'Edit acara', iEdit, 'data-json="' + escapeAttr(JSON.stringify(d)) + '"') +
            btn('btn-delete-acara bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50', null, 'Padam acara', iTrash, 'data-id="' + id + '" data-nama="' + escapeAttr(d.nama_acara) + '"') +
            '</div>';
    }

    const table = $('#acara-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ route("admin.kawalan.acara.data") }}', type: 'GET' },
        columns: [
            { data: 'nama_acara', name: 'nama_acara', render: function(d, t, row) { return renderAcara(row); } },
            { data: 'waktu', name: 'waktu' },
            { data: 'code', name: 'code', orderable: false, render: function(d, t, row) { return renderLink(row); } },
            { data: 'expires_at', name: 'expires_at', render: function(d, t, row) { return renderExpiry(row); } },
            { data: 'kehadiran_count', name: 'kehadiran_count', orderable: false, searchable: false, render: function(d, t, row) { return renderCount(row); } },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: function(d, t, row) { return renderActions(row); } }
        ],
        order: [[3, 'desc']],
        pageLength: 5,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Semua']],
        initComplete: function() { $('.dataTables_filter input').attr('placeholder', 'Cari acara…'); },
        language: {
            processing: 'Memuatkan...', search: 'Cari:', lengthMenu: 'Papar _MENU_ rekod',
            info: 'Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod', infoEmpty: 'Tiada rekod',
            infoFiltered: '(ditapis daripada _MAX_ rekod)',
            paginate: { first: 'Pertama', last: 'Akhir', next: 'Seterusnya', previous: 'Sebelumnya' },
            zeroRecords: 'Tiada rekod sepadan'
        }
    });

    function acaraFormHtml(v) {
        v = v || {};
        const active = v.is_active === undefined ? true : !!v.is_active;
        return `
            <form id="acara-form" class="acara-form">
                <div class="field">
                    <label for="swal-nama">Nama Acara</label>
                    <input type="text" id="swal-nama" class="input-text" value="${escapeAttr(v.nama_acara || '')}" placeholder="cth: Perhimpunan Tahunan Bakis" autocomplete="off" />
                </div>
                <div class="field">
                    <label for="swal-lokasi">Lokasi</label>
                    <input type="text" id="swal-lokasi" class="input-text" value="${escapeAttr(v.lokasi || '')}" placeholder="cth: Dewan Auditorium HSB" autocomplete="off" />
                </div>
                <div class="field">
                    <label for="swal-waktu">Waktu</label>
                    <input type="text" id="swal-waktu" class="input-text" value="${escapeAttr(v.waktu || '')}" placeholder="cth: 8:00 Pagi Sehingga 12 Tengah Hari" autocomplete="off" />
                </div>
                <div class="field">
                    <label for="swal-expires">Pautan Tamat Pada</label>
                    <input type="datetime-local" id="swal-expires" class="input-text" value="${escapeAttr(v.expires_at || '')}" />
                    <p class="hint">Pautan kehadiran akan tamat selepas tarikh &amp; masa ini.</p>
                </div>
                <div class="field">
                    <div class="toggle-wrap">
                        <span class="toggle-label">Aktif</span>
                        <div class="toggle-track ${active ? 'active' : ''}" id="swal-toggle" role="button" tabindex="0" aria-pressed="${active}"><span class="toggle-thumb"></span></div>
                    </div>
                    <input type="hidden" id="swal-active" value="${active ? '1' : '0'}" />
                </div>
            </form>`;
    }

    function bindForm() {
        const toggle = document.getElementById('swal-toggle');
        const hidden = document.getElementById('swal-active');
        if (toggle && hidden) {
            const flip = () => { hidden.value = toggle.classList.toggle('active') ? '1' : '0'; };
            toggle.addEventListener('click', flip);
            toggle.addEventListener('keydown', e => { if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); flip(); } });
        }
    }

    function readForm() {
        const nama = (document.getElementById('swal-nama').value || '').trim();
        const lokasi = (document.getElementById('swal-lokasi').value || '').trim();
        const waktu = (document.getElementById('swal-waktu').value || '').trim();
        const expires = (document.getElementById('swal-expires').value || '').trim();
        if (!nama) { Swal.showValidationMessage('Nama acara wajib diisi.'); return false; }
        if (!lokasi) { Swal.showValidationMessage('Lokasi wajib diisi.'); return false; }
        if (!waktu) { Swal.showValidationMessage('Waktu wajib diisi.'); return false; }
        if (!expires) { Swal.showValidationMessage('Tarikh tamat wajib diisi.'); return false; }
        return {
            nama_acara: nama, lokasi: lokasi, waktu: waktu, expires_at: expires,
            is_active: document.getElementById('swal-active').value === '1'
        };
    }

    function submitForm(url, method, payload, okMsg) {
        const body = new URLSearchParams();
        body.append('_token', csrfToken);
        if (method === 'PUT') body.append('_method', 'PUT');
        Object.keys(payload).forEach(k => body.append(k, payload[k] === true ? '1' : (payload[k] === false ? '0' : payload[k])));

        fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || okMsg, timer: 2000, timerProgressBar: true, showConfirmButton: false });
                table.ajax.reload(null, false);
            } else {
                const firstErr = data.errors ? Object.values(data.errors)[0][0] : null;
                Swal.fire({ icon: 'error', title: 'Ralat', text: firstErr || data.message || 'Ralat semasa menyimpan.' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' }));
    }

    $(document).on('click', '.btn-create-acara', function() {
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah Acara</span>',
            html: acaraFormHtml({}), showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1', width: '460px', customClass: { popup: 'acara-swal' },
            didOpen: bindForm, preConfirm: readForm
        }).then(r => { if (r.isConfirmed && r.value) submitForm('{{ route("admin.kawalan.acara.store") }}', 'POST', r.value, 'Acara berjaya ditambah.'); });
    });

    $(document).on('click', '.btn-edit-acara', function() {
        let v = {};
        try { v = JSON.parse($(this).attr('data-json')); } catch (e) {}
        Swal.fire({
            title: '<span style="display:flex;align-items:center;gap:0.5rem;"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Acara</span>',
            html: acaraFormHtml(v), showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1', width: '460px', customClass: { popup: 'acara-swal' },
            didOpen: bindForm, preConfirm: readForm
        }).then(r => { if (r.isConfirmed && r.value) submitForm(baseUrl + '/' + v.id, 'PUT', r.value, 'Acara telah dikemas kini.'); });
    });

    $(document).on('click', '.btn-delete-acara', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Padam Acara?', text: `Adakah anda pasti mahu memadam "${nama}"? Semua rekod kehadiran turut akan dipadam.`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Padam', cancelButtonText: 'Batal', customClass: { popup: 'acara-swal' }
        }).then(result => {
            if (!result.isConfirmed) return;
            fetch(baseUrl + '/' + id, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ '_token': csrfToken, '_method': 'DELETE' }).toString()
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message, timer: 2000, timerProgressBar: true, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam acara.' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' }));
        });
    });

    $(document).on('click', '.btn-copy-link', function() {
        const url = $(this).data('url');
        const done = () => toast.fire({ icon: 'success', title: 'Pautan disalin' });
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(done).catch(() => fallbackCopy(url, done));
        } else { fallbackCopy(url, done); }
    });

    function fallbackCopy(text, cb) {
        const ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); cb(); } catch (e) {}
        document.body.removeChild(ta);
    }
});
</script>
@endpush
