@extends('layouts.app')

@section('title', 'Senarai Ahli')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform hover:scale-105">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Senarai Ahli</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cari dan semak rekod ahli</p>
            </div>
        </div>
        <a href="{{ route('admin.members.create') }}" class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-105 hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
            <svg class="mr-2 h-5 w-5 transition-transform duration-300 group-hover:-rotate-90 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="relative">Tambah Ahli</span>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <table id="carian-ahli-table" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No. Ahli</th>
                        <th>Nama</th>
                        <th>No. KP</th>
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
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<style>
/* Custom DataTables Styling */
table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
table.dataTable thead th { border-bottom: 2px solid #e5e7eb; padding: 1rem; color: #4b5563; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background-color: #f9fafb; text-align: left; transition: background-color 0.2s; }
.dark table.dataTable thead th { border-bottom-color: #374151; color: #d1d5db; background-color: #1f2937; }
table.dataTable tbody tr { background-color: #ffffff; border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease-in-out; }
table.dataTable tbody tr:hover { background-color: #f9fafb; transform: scale-[1.002]; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); z-index: 10; position: relative; }
.dark table.dataTable tbody tr { background-color: #1f2937; border-bottom-color: #374151; }
.dark table.dataTable tbody tr:hover { background-color: #374151; transform: scale-[1.002]; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1); }
table.dataTable tbody td { padding: 1rem; vertical-align: middle; color: #111827; font-size: 0.875rem; }
.dark table.dataTable tbody td { color: #f3f4f6; }

/* Filter & Search */
.dataTables_wrapper .dataTables_filter { margin-bottom: 1rem; }
.dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 0.75rem; margin-left: 0.5rem; outline: none; transition: all 0.2s; background: #fff; color: #111827; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
.dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
.dark .dataTables_wrapper .dataTables_filter input { border-color: #4b5563; background: #374151; color: #f3f4f6; }
.dark .dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.4); }

/* Length Menu */
.dataTables_wrapper .dataTables_length { margin-bottom: 1rem; color: #6b7280; }
.dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.375rem 2rem 0.375rem 0.75rem; outline: none; background: #fff; color: #111827; margin: 0 0.25rem; }
.dark .dataTables_wrapper .dataTables_length select { border-color: #4b5563; background: #374151; color: #f3f4f6; }

/* Info & Pagination */
.dataTables_wrapper .dataTables_info { color: #6b7280; font-size: 0.875rem; padding-top: 1rem; }
.dark .dataTables_wrapper .dataTables_info { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate { padding-top: 1rem; margin-top: 0.5rem; display: flex; gap: 0.25rem; justify-content: flex-end; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 2px; border-radius: 0.375rem; border: 1px solid #e5e7eb; background: #fff; color: #374151 !important; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 500; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6; color: #111827 !important; border-color: #d1d5db; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #e0e7ff; color: #4338ca !important; border-color: #c7d2fe; font-weight: 600; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #c7d2fe; color: #4338ca !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; background: #f9fafb; color: #9ca3af !important; border-color: #e5e7eb; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button { background: #374151; border-color: #4b5563; color: #d1d5db !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #4b5563; color: #fff !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5; color: #fff !important; border-color: #4338ca; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #4338ca; color: #fff !important; }
.dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { background: #1f2937; color: #6b7280 !important; border-color: #374151; opacity: 0.5; }

/* Status indicator: colored dot + label */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}
.status-dot {
    width: 0.625rem;
    height: 0.625rem;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 0 2px rgba(255,255,255,0.5);
}
.status-aktif .status-dot { background: #22c55e; box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2); }
.status-tidak_aktif .status-dot { background: #94a3b8; box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.2); }
.status-meninggal .status-dot { background: #ef4444; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2); }
.status-pending .status-dot, .status-menunggu_kelulusan .status-dot { background: #eab308; box-shadow: 0 0 0 2px rgba(234, 179, 8, 0.2); }
.status-default .status-dot { background: #94a3b8; }
.dark .status-aktif .status-dot { background: #4ade80; box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2); }
.dark .status-tidak_aktif .status-dot { background: #64748b; box-shadow: 0 0 0 2px rgba(100, 116, 139, 0.2); }
.dark .status-meninggal .status-dot { background: #f87171; box-shadow: 0 0 0 2px rgba(248, 113, 113, 0.2); }
.dark .status-pending .status-dot, .dark .status-menunggu_kelulusan .status-dot { background: #facc15; box-shadow: 0 0 0 2px rgba(250, 204, 21, 0.2); }
.dark .status-default .status-dot { background: #64748b; }

/* Hide default DT sort icons, we use nice custom CSS */
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
    background-image: none !important;
    position: relative;
    padding-right: 1.5rem !important;
    cursor: pointer;
}
table.dataTable thead .sorting::after,
table.dataTable thead .sorting_asc::after,
table.dataTable thead .sorting_desc::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0.75rem;
    height: 0.75rem;
    opacity: 0.5;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}
table.dataTable thead .sorting::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'/%3E%3C/svg%3E");
}
table.dataTable thead .sorting_asc::after {
    opacity: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234f46e5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 15l7-7 7 7'/%3E%3C/svg%3E");
}
table.dataTable thead .sorting_desc::after {
    opacity: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234f46e5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
}
.dark table.dataTable thead .sorting_asc::after,
.dark table.dataTable thead .sorting_desc::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 15l7-7 7 7'/%3E%3C/svg%3E");
}
.dark table.dataTable thead .sorting_desc::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#carian-ahli-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.carian.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'no_ahli', name: 'no_ahli' },
            { data: 'nama', name: 'nama' },
            { data: 'no_kp', name: 'no_kp' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        initComplete: function() {
            $('.dataTables_filter input').attr('placeholder', 'Nama, Kad Pengenalan…');
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
});
</script>
@endpush
@endsection
