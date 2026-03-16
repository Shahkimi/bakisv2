@extends('layouts.app')

@section('title', 'Keputusan Semakan')

@section('content')
@php
    $result = $result ?? [];
    $status = $result['status'] ?? 'not_found';
    $member = $result['member'] ?? null;
    $payment = $result['payment'] ?? null;
    $checkedNoKp = $checkedNoKp ?? ($member->no_kp ?? null);

    $config = match($status) {
        'active' => [
            'title' => 'Status Aktif',
            'summary' => 'Keahlian anda aktif untuk tahun semasa.',
            'panel' => 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20',
            'titleClass' => 'text-green-900 dark:text-green-100',
            'textClass' => 'text-green-800 dark:text-green-200',
            'icon' => 'M5 13l4 4L19 7',
        ],
        'pending' => [
            'title' => 'Sedang Disemak',
            'summary' => 'Pembayaran anda sedang diproses oleh admin.',
            'panel' => 'border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20',
            'titleClass' => 'text-amber-900 dark:text-amber-100',
            'textClass' => 'text-amber-800 dark:text-amber-200',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'rejected' => [
            'title' => 'Permohonan Ditolak',
            'summary' => 'Bukti bayaran tidak diluluskan. Sila hantar semula.',
            'panel' => 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20',
            'titleClass' => 'text-red-900 dark:text-red-100',
            'textClass' => 'text-red-800 dark:text-red-200',
            'icon' => 'M6 18L18 6M6 6l12 12',
        ],
        'expired' => [
            'title' => 'Belum Diperbaharui',
            'summary' => 'Keahlian belum diperbaharui untuk tahun ini.',
            'panel' => 'border-orange-200 bg-orange-50 dark:border-orange-800 dark:bg-orange-900/20',
            'titleClass' => 'text-orange-900 dark:text-orange-100',
            'textClass' => 'text-orange-800 dark:text-orange-200',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        default => [
            'title' => 'Tiada Rekod Ditemui',
            'summary' => 'No. KP tidak ditemui dalam rekod semasa.',
            'panel' => 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700/50',
            'titleClass' => 'text-gray-900 dark:text-gray-100',
            'textClass' => 'text-gray-700 dark:text-gray-300',
            'icon' => 'M12 8h.01M12 12h.01M12 16h.01',
        ],
    };
@endphp

<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-stone-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">
        <section class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Keputusan Semakan</p>
                    <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $config['title'] }}</h1>
                    @if($checkedNoKp)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">No. KP Disemak: <span class="font-semibold">{{ $checkedNoKp }}</span></p>
                    @endif
                </div>
                <a href="{{ route('semak.index') }}" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Semak Semula
                </a>
            </div>

            @if(session('success'))
                <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20 p-4 text-sm text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 p-4 text-sm text-red-800 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mt-5 rounded-2xl border {{ $config['panel'] }} p-5">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 shrink-0 rounded-full bg-white/70 dark:bg-gray-800/60 flex items-center justify-center">
                        <svg class="h-5 w-5 {{ $config['textClass'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold {{ $config['titleClass'] }}">{{ $config['title'] }}</p>
                        <p class="mt-1 text-sm {{ $config['textClass'] }}">{{ $config['summary'] }}</p>
                        @if($status === 'expired')
                            <p class="mt-1 text-sm {{ $config['textClass'] }}">Yuran pembaharuan: <span class="font-semibold">RM10.00</span>.</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($member)
                <div class="mt-5 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Maklumat Ahli</h2>
                    <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Nama</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $member->nama ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">No. Ahli</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $member->no_ahli ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">No. KP</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $member->no_kp ?? $checkedNoKp ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Status Rekod</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ strtoupper($status) }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            @if($member)
                <div class="mt-5 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Sejarah Pembayaran</h2>
                    @php $payments = $member->payments->sortByDesc('tahun_bayar'); @endphp
                    @if($payments->isEmpty())
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Tiada rekod pembayaran.</p>
                    @else
                        <div class="mt-3 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th class="text-left py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Tahun</th>
                                        <th class="text-left py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Jumlah</th>
                                        <th class="text-left py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Jenis</th>
                                        <th class="text-left py-2 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($payments as $p)
                                        <tr>
                                            <td class="py-2 pr-4 text-gray-900 dark:text-white">{{ $p->tahun_bayar }}</td>
                                            <td class="py-2 pr-4 text-gray-900 dark:text-white">RM {{ number_format($p->jumlah, 2) }}</td>
                                            <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">{{ $p->jenis === 'pendaftaran_baru' ? 'Pendaftaran Baru' : ($p->jenis === 'pembaharuan' ? 'Pembaharuan' : ($p->yuran?->jenis_yuran ?? '–')) }}</td>
                                            <td class="py-2">
                                                @if($p->status === 'approved')
                                                    <span class="text-green-600 dark:text-green-400 font-medium">Disahkan</span>
                                                @elseif($p->status === 'pending')
                                                    <span class="text-amber-600 dark:text-amber-400 font-medium">Menunggu</span>
                                                @else
                                                    <span class="text-red-600 dark:text-red-400 font-medium">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

            @if($member && in_array($status, ['expired', 'rejected'], true))
                @php $paymentAccounts = $paymentAccounts ?? collect(); @endphp
                @if($paymentAccounts->isNotEmpty())
                    <div class="mt-5 rounded-2xl border border-teal-200 dark:border-teal-800/50 bg-teal-50/70 dark:bg-teal-900/10 p-4 sm:p-5">
                        <h2 class="text-sm font-semibold text-teal-900 dark:text-teal-200">Bayar ke Akaun</h2>
                        <p class="mt-1 text-xs text-teal-800/90 dark:text-teal-300">Sila bank in ke salah satu akaun di bawah. Imbas kod QR atau salin no. akaun.</p>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($paymentAccounts as $account)
                                <div class="rounded-xl border border-teal-200/80 dark:border-teal-700/50 bg-white dark:bg-gray-800/50 p-4 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                    @if($account->qr_image_url ?? null)
                                        <img src="{{ $account->qr_image_url }}" alt="QR {{ $account->account_name }}" class="w-24 h-24 sm:w-20 sm:h-20 object-contain rounded-lg border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</p>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">No. Akaun: <span class="font-mono font-medium">{{ $account->account_number }}</span></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="mt-5 rounded-2xl border border-orange-200 dark:border-orange-800/50 bg-orange-50/70 dark:bg-orange-900/10 p-4 sm:p-5">
                    <h2 class="text-sm font-semibold text-orange-900 dark:text-orange-200">Bayar Pembaharuan</h2>
                    <p class="mt-1 text-xs text-orange-800/90 dark:text-orange-300">Yuran pembaharuan RM10.00. Admin akan mengesahkan pembayaran anda.</p>
                    <form action="{{ route('semak.bayar') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <input type="hidden" name="no_kp" value="{{ $member->no_kp ?? $checkedNoKp }}">
                        <div>
                            <label for="no_resit_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Resit / Rujukan Bank *</label>
                            <input type="text" name="no_resit_transfer" id="no_resit_transfer" value="{{ old('no_resit_transfer') }}" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('no_resit_transfer') border-red-500 @enderror">
                            @error('no_resit_transfer')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="bukti_bayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Bayaran (JPG, PNG, PDF) *</label>
                            <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-orange-100 dark:file:bg-orange-900/30 file:px-4 file:py-2.5 file:font-medium file:text-orange-700 dark:file:text-orange-300">
                            @error('bukti_bayaran')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-teal-600 to-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                            Hantar Pembayaran Pembaharuan
                        </button>
                    </form>
                </div>
            @endif

            @if($payment)
                <div class="mt-4 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Maklumat Pembayaran</h2>
                    <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Rujukan</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->no_resit_transfer ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Jumlah</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">
                                RM {{ number_format((float) $payment->jumlah, 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('semak.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 hover:from-orange-700 hover:to-orange-800">
                    Semak No. KP Lain
                </a>
                @if(in_array($status, ['expired', 'rejected', 'not_found'], true))
                    <a href="{{ route('semak.index', ['no_kp' => $checkedNoKp, 'register' => 1]) }}" class="inline-flex items-center justify-center rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-semibold text-teal-800 hover:bg-teal-100 dark:border-teal-800 dark:bg-teal-900/20 dark:text-teal-200">
                        Daftar / Kemas Kini Permohonan
                    </a>
                @else
                    <a href="/" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Kembali ke Laman Utama
                    </a>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
