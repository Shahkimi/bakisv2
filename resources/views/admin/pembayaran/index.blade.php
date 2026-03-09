@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl gradient-bg shadow-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pembayaran</h1>
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
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Menunggu</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'approved']) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Disahkan</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'rejected']) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Ditolak</a>
        <a href="{{ route('admin.pembayaran.index', ['status' => 'all']) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-gray-200 text-gray-900 dark:bg-gray-600 dark:text-gray-100' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Semua</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
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
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->member?->nama ?? '–' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->member?->no_kp ?? '–' }}</div>
                                @if($payment->member)
                                    <a href="{{ route('admin.members.edit', $payment->member) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-0.5 inline-block">Edit ahli</a>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $payment->no_resit_transfer ?? '–' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $payment->tahun_bayar }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">RM {{ number_format($payment->jumlah, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $payment->jenis === 'pendaftaran_baru' ? 'Pendaftaran Baru' : ($payment->jenis === 'pembaharuan' ? 'Pembaharuan' : ($payment->yuran?->jenis_yuran ?? '–')) }}</td>
                            <td class="px-4 py-3">
                                @if($payment->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Disahkan</span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Menunggu</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($payment->bukti_bayaran)
                                    <a href="{{ route('admin.pembayaran.bukti', $payment) }}" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Lihat</a>
                                @else
                                    <span class="text-xs text-gray-400">–</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($payment->status === 'pending')
                                    <div class="flex flex-wrap gap-2 items-center">
                                        <form action="{{ route('admin.pembayaran.approve', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Sahkan pembayaran ini? Status ahli akan dikemas kini kepada Aktif.');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-600 text-white hover:bg-green-700 transition-colors">Sahkan</button>
                                        </form>
                                        <div x-data="{ open: false }" class="inline">
                                            <button type="button" @click="open = true" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600 text-white hover:bg-red-700 transition-colors">Tolak</button>
                                            <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="open = false">
                                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-5" @click.stop>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Tolak Pembayaran</h3>
                                                    <form action="{{ route('admin.pembayaran.reject', $payment) }}" method="POST">
                                                        @csrf
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan (pilihan)</label>
                                                        <textarea name="catatan_admin" rows="3" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white mb-4" placeholder="Sebab penolakan..."></textarea>
                                                        <div class="flex gap-2 justify-end">
                                                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                                                            <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">–</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Tiada rekod pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
<style>[x-cloak] { display: none !important; }</style>
@endsection
