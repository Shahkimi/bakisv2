@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4 group">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Pembayaran</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Urus dan sahkan transaksi pembayaran ahli</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20 px-4 py-3 text-sm text-green-800 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter section (status + jabatan) and status indicators --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 p-4 shadow-sm">
        <div class="flex flex-nowrap items-center gap-3 overflow-x-auto min-w-0">
            {{-- Status filter --}}
            <div class="relative w-48 shrink-0">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <select id="statusFilterSelect" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Disahkan</option>
                    <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            {{-- Jabatan filter --}}
            <div class="jabatan-filter-wrap relative w-90 shrink-0">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <select id="jabatanFilter" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" style="color:bold;">Semua Jabatan</option>
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
                <span id="filterIndicator" class="absolute -top-0.5 right-0 inline-flex h-4 w-4 translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-amber-500 text-[10px] text-amber-900" style="display: none;" aria-hidden="true" title="Filter aktif">
                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg>
                </span>
            </div>
        </div>
        {{-- Status indicators --}}
        <div class="flex items-center gap-2 shrink-0">
            <div id="loadingSpinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-indigo-500 border-t-transparent" role="status" aria-hidden="true">
                <span class="sr-only">Loading...</span>
            </div>
            <span id="statusBadge" class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-300">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                Siap
            </span>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div id="pembayaranTableError" class="hidden px-4 py-3 border-b border-red-200 bg-red-50 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200"></div>
        <div class="w-full min-w-0 p-4">
            <div class="w-full overflow-x-auto">
                <table class="table table-hover align-middle table-sm w-full min-w-full" id="pembayaran-table" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 20%; min-width: 140px;">Ahli</th>
                            <th class="text-center" style="width: 15%; min-width: 120px;">No. Resit / Rujukan</th>
                            <th class="text-center" style="width: 8%; min-width: 60px;">Tahun</th>
                            <th class="text-center" style="width: 10%; min-width: 80px;">Jumlah</th>
                            <th class="text-center" style="width: 12%; min-width: 80px;">Jenis</th>
                            <th class="text-center" style="width: 10%; min-width: 80px;">Status</th>
                            <th class="text-center" style="width: 10%; min-width: 60px;">Bukti</th>
                            <th class="text-center" style="width: 15%; min-width: 100px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables server-side will populate --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
[x-cloak] { display: none !important; }
.hidden-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.hidden-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.hidden-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 20px;
}
.dark .hidden-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}
</style>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://datatables.net/legacy/v1/media/css/dataTables.tailwindcss.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<style>
/* Custom DataTables Styling */
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb; padding: 1rem; color: #4b5563; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background-color: #f9fafb; text-align: left; }
.dark table.dataTable thead th { border-bottom-color: #374151; color: #d1d5db; background-color: #1f2937; }
table.dataTable tbody tr { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; }
table.dataTable tbody tr:hover { background-color: #f9fafb; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; outline: none; background: #fff; color: #111827; }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 2rem 0.375rem 0.75rem; background: #fff; color: #111827; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; padding-top: 1rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top: 1rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 2px; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #e0e7ff; color: #4338ca !important; border-color: #c7d2fe; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #4b5563; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5; color: #fff !important; }
/* Select2 jabatan filter – match Status dropdown UI (rounded-xl, border, bg, padding), search in open dropdown */
.jabatan-filter-wrap .select2-container { width: 100% !important; }
.jabatan-filter-wrap svg[aria-hidden="true"] { z-index: 10; }
.jabatan-filter-wrap .select2-container .select2-selection--single {
    min-height: 2.75rem; padding-left: 2.5rem; padding-right: 1rem;
    border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: #fff;
    font-size: 0.875rem; color: #111827; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
.jabatan-filter-wrap .select2-container--focus .select2-selection--single,
.jabatan-filter-wrap .select2-container--open .select2-selection--single { border-color: #6366f1; outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__rendered { padding-left: 0; line-height: 2.1rem; }
.jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__arrow { height: 2.75rem; right: 0.5rem; }
.dark .jabatan-filter-wrap .select2-container .select2-selection--single { background-color: #1f2937; border-color: #4b5563; color: #f3f4f6; }
.dark .jabatan-filter-wrap .select2-container--focus .select2-selection--single,
.dark .jabatan-filter-wrap .select2-container--open .select2-selection--single { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.dark .jabatan-filter-wrap .select2-container .select2-selection--single .select2-selection__rendered { color: #f3f4f6; }
.dark .jabatan-filter-wrap .select2-container .select2-dropdown { background-color: #1f2937; border: 1px solid #4b5563; border-radius: 0.75rem; }
.dark .jabatan-filter-wrap .select2-container .select2-results__option { color: #f3f4f6; }
.dark .jabatan-filter-wrap .select2-container .select2-search--dropdown .select2-search__field { background-color: #374151; border: 1px solid #4b5563; color: #f3f4f6; border-radius: 0.375rem; padding: 0.375rem 0.5rem; }
.jabatan-filter-wrap .select2-container .select2-dropdown { border: 1px solid #e5e7eb; border-radius: 0.75rem; }
.jabatan-filter-wrap .select2-container .select2-search--dropdown .select2-search__field { border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.375rem 0.5rem; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const statusSelect = document.getElementById('statusFilterSelect');
    const jabatanSelect = document.getElementById('jabatanFilter');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const statusBadge = document.getElementById('statusBadge');
    const filterIndicator = document.getElementById('filterIndicator');

    $('#jabatanFilter').select2({
        placeholder: 'Semua Jabatan',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return 'Tiada jabatan sepadan'; },
            searching: function() { return 'Mencari...'; },
            inputTooShort: function() { return 'Sila taip untuk cari'; }
        }
    });

    function setLoadingState(loading) {
        if (loadingSpinner) loadingSpinner.classList.toggle('hidden', !loading);
        if (statusBadge) {
            statusBadge.classList.remove('bg-green-100', 'text-green-700', 'dark:bg-green-900/30', 'dark:text-green-300', 'bg-amber-100', 'text-amber-700', 'dark:bg-amber-900/30', 'dark:text-amber-300', 'bg-red-100', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-300');
            if (loading) {
                statusBadge.classList.add('bg-amber-100', 'text-amber-700', 'dark:bg-amber-900/30', 'dark:text-amber-300');
                statusBadge.innerHTML = '<svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memuat...';
            } else {
                statusBadge.classList.add('bg-green-100', 'text-green-700', 'dark:bg-green-900/30', 'dark:text-green-300');
                statusBadge.innerHTML = '<svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Siap';
            }
        }
    }

    function setErrorState() {
        if (loadingSpinner) loadingSpinner.classList.add('hidden');
        if (statusBadge) {
            statusBadge.classList.remove('bg-green-100', 'text-green-700', 'dark:bg-green-900/30', 'dark:text-green-300', 'bg-amber-100', 'text-amber-700', 'dark:bg-amber-900/30', 'dark:text-amber-300');
            statusBadge.classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900/30', 'dark:text-red-300');
            statusBadge.innerHTML = '<svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg> Ralat';
        }
    }

    function updateFilterIndicator() {
        if (!filterIndicator) return;
        const hasJabatan = jabatanSelect && jabatanSelect.value !== '';
        filterIndicator.style.display = hasJabatan ? 'inline-flex' : 'none';
    }

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderMemberCell(row) {
        const member = row.member || {};
        const nama = escapeHtml(member.nama || '–');
        const noKp = escapeHtml(member.no_kp || '–');

        let editLink = '';
        if (member.id) {
            const url = '/admin/members/' + member.id + '/edit';
            editLink = `<a href="${url}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-0.5 inline-block">Edit ahli</a>`;
        }

        return `
            <div class="text-sm font-medium text-gray-900 dark:text-white">${nama}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">${noKp}</div>
            ${editLink}
        `;
    }

    function renderStatusBadge(status) {
        if (status === 'approved') {
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Disahkan</span>';
        }
        if (status === 'pending') {
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Menunggu</span>';
        }
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Ditolak</span>';
    }

    function renderBuktiCell(row) {
        if (row.bukti_bayaran) {
            const url = '/admin/pembayaran/' + row.id + '/bukti';
            return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Lihat</a>`;
        }
        return '<span class="text-xs text-gray-400">–</span>';
    }

    function renderActionsCell(row) {
        if (row.status !== 'pending') {
            return '<span class="text-xs text-gray-400">–</span>';
        }

        const approveUrl = '/admin/pembayaran/' + row.id + '/approve';
        const rejectUrl = '/admin/pembayaran/' + row.id + '/reject';

        return `
            <div class="flex flex-wrap gap-2 items-center">
                <form action="${approveUrl}" method="POST" class="inline js-approve-form">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-600 text-white hover:bg-green-700 transition-colors">Sahkan</button>
                </form>
                <button type="button" data-reject-url="${rejectUrl}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors js-reject-btn">Tolak</button>
            </div>
        `;
    }

    var table = $('#pembayaran-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.pembayaran.data") }}',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            data: function(d) {
                d.status = statusSelect ? statusSelect.value : '{{ $statusFilter }}';
                d.jabatan_filter = jabatanSelect ? jabatanSelect.value : '';
            },
            beforeSend: function() {
                setLoadingState(true);
            },
            complete: function() {
                setLoadingState(false);
            },
            error: function(xhr) {
                setErrorState();
                var errEl = document.getElementById('pembayaranTableError');
                if (errEl) {
                    errEl.classList.remove('hidden');
                    errEl.textContent = 'Gagal memuatkan data. ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : (xhr.status ? 'HTTP ' + xhr.status : 'Ralat rangkaian.'));
                }
            }
        },
        columns: [
            { data: 'member', name: 'member', orderable: false, render: function(d, type, row) { return renderMemberCell(row); } },
            { data: 'no_resit_transfer', name: 'no_resit_transfer' },
            { data: 'tahun_bayar', name: 'tahun_bayar' },
            { data: 'jumlah_formatted', name: 'jumlah', searchable: false },
            { data: 'jenis_label', name: 'jenis', orderable: false, searchable: false },
            { data: 'status', name: 'status', render: function(d) { return renderStatusBadge(d); } },
            { data: 'bukti_bayaran', name: 'bukti_bayaran', orderable: false, searchable: false, render: function(d, type, row) { return renderBuktiCell(row); } },
            { data: 'id', name: 'actions', orderable: false, searchable: false, render: function(d, type, row) { return renderActionsCell(row); } }
        ],
        order: [[2, 'desc']],
        pageLength: 10,
        initComplete: function() {
            $('.dataTables_filter input').attr('placeholder', 'Nama, Kad Pengenalan');
        },
        language: {
            processing: 'Memuatkan...',
            search: 'Cari:',
            lengthMenu: 'Papar _MENU_ rekod',
            info: 'Menunjukkan _START_ hingga _END_ daripada _TOTAL_ rekod',
            infoEmpty: 'Tiada rekod',
            infoFiltered: '(ditapis daripada _MAX_ rekod)',
            paginate: { first: 'Pertama', last: 'Akhir', next: 'Seterusnya', previous: 'Sebelumnya' },
            zeroRecords: 'Tiada rekod sepadan'
        },
        drawCallback: function() {
            updateFilterIndicator();
        }
    });

    $('#pembayaran-table').on('error.dt', function(e, settings, techNote, message) {
        const el = document.getElementById('pembayaranTableError');
        if (!el) return;
        el.classList.remove('hidden');
        el.textContent = 'Gagal memuatkan data pembayaran. ' + (message || '');
    });

    if (statusSelect && table) {
        statusSelect.addEventListener('change', function() {
            table.ajax.reload();
        });
    }
    if (jabatanSelect && table) {
        jabatanSelect.addEventListener('change', function() {
            table.ajax.reload();
        });
    }

    $(document).on('submit', '.js-approve-form', function() {
        return confirm('Sahkan pembayaran ini? Status ahli akan dikemas kini kepada Aktif.');
    });

    $(document).on('click', '.js-reject-btn', function() {
        const rejectUrl = $(this).data('reject-url');

        Swal.fire({
            title: 'Tolak Pembayaran',
            input: 'textarea',
            inputLabel: 'Catatan (pilihan)',
            inputPlaceholder: 'Sebab penolakan...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (!result.isConfirmed) return;

            const formData = new URLSearchParams();
            formData.append('_token', csrfToken);
            if (result.value) {
                formData.append('catatan_admin', result.value);
            }

            fetch(rejectUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(function(res) {
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Berjaya', text: 'Pembayaran telah ditolak.', timer: 2000, showConfirmButton: false });
                    if (table && table.ajax) table.ajax.reload(null, false);
                    return;
                }
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Gagal menolak pembayaran.' });
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian. Sila cuba lagi.' });
            });
        });
    });
});
</script>
@endpush
@endsection
