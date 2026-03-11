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

    {{-- Status filter --}}
    <div class="mb-3 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <div class="w-full sm:w-64">
            <label for="statusFilterSelect" class="block text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Filter status</label>
            <select id="statusFilterSelect" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 px-4 py-2.5 text-sm text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Disahkan</option>
                <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
    </div>
    <div class="mb-6 inline-flex flex-wrap p-1.5 gap-1 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 w-full sm:w-auto overflow-x-auto">
        <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="flex-1 sm:flex-none text-center inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $statusFilter === 'pending' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow shadow-indigo-500/10' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">Menunggu</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'approved']) }}" class="flex-1 sm:flex-none text-center inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $statusFilter === 'approved' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow shadow-indigo-500/10' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">Disahkan</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'rejected']) }}" class="flex-1 sm:flex-none text-center inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $statusFilter === 'rejected' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow shadow-indigo-500/10' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">Ditolak</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'all']) }}" class="flex-1 sm:flex-none text-center inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow shadow-indigo-500/10' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">Semua</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <div id="pembayaranTableError" class="hidden px-4 py-3 border-b border-red-200 bg-red-50 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200"></div>
        <div class="overflow-x-auto max-h-[600px] overflow-y-auto hidden-scrollbar">
            <table id="pembayaran-table" class="ui celled striped table" style="width:100%">
                <thead class="bg-gray-50/90 dark:bg-gray-900/90 backdrop-blur-sm sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ahli</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No. Resit / Rujukan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Bukti</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    {{-- DataTables server-side will populate --}}
                </tbody>
            </table>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.4/dist/semantic.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.semanticui.min.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.4/dist/semantic.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.semanticui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const statusSelect = document.getElementById('statusFilterSelect');

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

    const table = $('#pembayaran-table').DataTable({
        processing: true,
        serverSide: true,
        dom: "<'ui stackable grid'<'row'<'eight wide column'l><'eight wide column'f>>>" +
             "<'row dt-table'<'sixteen wide column'tr>>" +
             "<'row'<'seven wide column'i><'nine wide column'p>>>",
        ajax: {
            url: '{{ route("admin.pembayaran.data", [], false) }}',
            type: 'GET',
            data: function(d) {
                d.status = statusSelect ? statusSelect.value : '{{ $statusFilter }}';
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
            $('.dataTables_filter input').attr('placeholder', 'Nama, Kad Pengenalan, Resit…');
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
        }
    });

    $('#pembayaran-table').on('error.dt', function(e, settings, techNote, message) {
        const el = document.getElementById('pembayaranTableError');
        if (!el) return;
        el.classList.remove('hidden');
        el.textContent = 'Gagal memuatkan data pembayaran. ' + (message || '');
    });

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
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
                    table.ajax.reload(null, false);
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
