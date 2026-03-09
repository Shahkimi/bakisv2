@extends('layouts.app')

@section('title', 'Carian Ahli')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl gradient-bg shadow-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Carian Ahli</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Cari dan semak rekod ahli</p>
            </div>
        </div>
        <a href="{{ route('admin.members.create') }}" class="inline-flex items-center justify-center py-3 px-5 border border-transparent text-sm font-semibold rounded-lg text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Tambah Ahli
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<style>
/* Status indicator: colored dot + label */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}
.status-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    flex-shrink: 0;
}
.status-aktif .status-dot { background: #22c55e; }
.status-tidak_aktif .status-dot { background: #94a3b8; }
.status-meninggal .status-dot { background: #ef4444; }
.status-pending .status-dot { background: #eab308; }
.status-menunggu_kelulusan .status-dot { background: #eab308; }
.status-default .status-dot { background: #94a3b8; }
.dark .status-aktif .status-dot { background: #4ade80; }
.dark .status-tidak_aktif .status-dot { background: #64748b; }
.dark .status-meninggal .status-dot { background: #f87171; }
.dark .status-pending .status-dot { background: #facc15; }
.dark .status-default .status-dot { background: #64748b; }
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
