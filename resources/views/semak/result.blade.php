@extends('layouts.app')
@section('title', 'Keputusan Semakan')

@section('content')
@php
    $result      = $result ?? [];
    $status      = $result['status'] ?? 'not_found';
    $member      = $result['member'] ?? null;
    $payment     = $result['payment'] ?? null;
    $checkedNoKp = $checkedNoKp ?? ($member->no_kp ?? null);
    $showPayment = in_array($status, ['expired', 'rejected'], true);

    $config = match($status) {
        'active' => [
            'title'      => 'Status Aktif',
            'summary'    => 'Keahlian anda aktif untuk tahun semasa.',
            'nextStep'   => 'Simpan No. Ahli untuk rujukan. Tiada tindakan bayaran diperlukan buat masa ini.',
            'badge'      => 'bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700',
            'panel'      => 'border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/20',
            'titleClass' => 'text-emerald-900 dark:text-emerald-100',
            'textClass'  => 'text-emerald-700 dark:text-emerald-300',
            'iconBg'     => 'bg-emerald-100 dark:bg-emerald-900/50',
            'iconColor'  => 'text-emerald-600 dark:text-emerald-400',
            'icon'       => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-emerald-500',
        ],
        'pending' => [
            'title'      => 'Sedang Disemak',
            'summary'    => 'Pembayaran anda sedang diproses oleh admin.',
            'nextStep'   => 'Sila tunggu pengesahan. Semak semula selepas beberapa hari untuk status terkini.',
            'badge'      => 'bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700',
            'panel'      => 'border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/20',
            'titleClass' => 'text-amber-900 dark:text-amber-100',
            'textClass'  => 'text-amber-700 dark:text-amber-300',
            'iconBg'     => 'bg-amber-100 dark:bg-amber-900/50',
            'iconColor'  => 'text-amber-600 dark:text-amber-400',
            'icon'       => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-amber-500',
        ],
        'rejected' => [
            'title'      => 'Permohonan Ditolak',
            'summary'    => 'Bukti bayaran tidak diluluskan. Sila hantar semula.',
            'nextStep'   => 'Ikuti langkah pembaharuan: pilih akaun bank, muat naik bukti baharu, dan hantar untuk semakan admin.',
            'badge'      => 'bg-red-100 text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700',
            'panel'      => 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20',
            'titleClass' => 'text-red-900 dark:text-red-100',
            'textClass'  => 'text-red-700 dark:text-red-300',
            'iconBg'     => 'bg-red-100 dark:bg-red-900/50',
            'iconColor'  => 'text-red-600 dark:text-red-400',
            'icon'       => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-red-500',
        ],
        'expired' => [
            'title'      => 'Belum Diperbaharui',
            'summary'    => 'Keahlian belum diperbaharui untuk tahun ini.',
            'nextStep'   => 'Buat bayaran ke akaun rasmi, pilih tahun pembaharuan, muat naik bukti, kemudian hantar untuk pengesahan.',
            'badge'      => 'bg-orange-100 text-orange-800 border border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-700',
            'panel'      => 'border-orange-200 bg-orange-50 dark:border-orange-800 dark:bg-orange-900/20',
            'titleClass' => 'text-orange-900 dark:text-orange-100',
            'textClass'  => 'text-orange-700 dark:text-orange-300',
            'iconBg'     => 'bg-orange-100 dark:bg-orange-900/50',
            'iconColor'  => 'text-orange-600 dark:text-orange-400',
            'icon'       => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            'dot'        => 'bg-orange-500',
        ],
        default => [
            'title'      => 'Tiada Rekod Ditemui',
            'summary'    => 'No. KP tidak ditemui dalam rekod semasa.',
            'nextStep'   => 'Kembali ke halaman Semak untuk mendaftar sebagai ahli baharu dengan No. KP yang sama.',
            'badge'      => 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
            'panel'      => 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-700/50',
            'titleClass' => 'text-slate-900 dark:text-slate-100',
            'textClass'  => 'text-slate-600 dark:text-slate-300',
            'iconBg'     => 'bg-slate-100 dark:bg-slate-700',
            'iconColor'  => 'text-slate-500 dark:text-slate-400',
            'icon'       => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-slate-400',
        ],
    };

    $memberPhotoUrl = $member && ! empty($member->gambar)
        ? asset('storage/members/photos/'.$member->gambar)
        : null;
@endphp

{{-- Page Wrapper --}}
<div class="min-h-screen bg-gradient-to-br from-slate-100/90 via-orange-50/30 to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-6 sm:py-8 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto space-y-5 sm:space-y-6 lg:space-y-8">

        {{-- Status hero --}}
        <section class="relative rounded-3xl border-2 {{ $config['panel'] }} shadow-xl shadow-slate-900/5">
            <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-3xl">
                <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-transparent dark:from-white/5"></div>
            </div>
            <div class="relative p-5 sm:p-7 lg:p-8 flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-8">
                <div class="flex flex-1 min-w-0 gap-4 sm:gap-5">
                    <div class="flex-shrink-0 w-14 h-14 sm:w-16 sm:h-16 rounded-2xl {{ $config['iconBg'] }} flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 sm:w-9 sm:h-9 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="min-w-0 space-y-3">
                        <p class="text-[11px] sm:text-xs font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Keputusan semakan</p>
                        <div class="flex flex-wrap items-center gap-2 gap-y-2">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold {{ $config['titleClass'] }}">{{ $config['title'] }}</h1>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $config['badge'] }}">
                                <span class="w-2 h-2 rounded-full {{ $config['dot'] }}" aria-hidden="true"></span>
                                <span class="sr-only">Kod status:</span>
                                {{ strtoupper($status) }}
                            </span>
                        </div>
                        @if($checkedNoKp)
                            <p class="text-sm font-mono tabular-nums text-slate-600 dark:text-slate-300 break-all">
                                No. KP: <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $checkedNoKp }}</span>
                            </p>
                        @endif
                        <p class="text-sm sm:text-base {{ $config['textClass'] }} leading-relaxed max-w-2xl">{{ $config['summary'] }}</p>
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-600/60 bg-white/70 dark:bg-slate-900/40 px-4 py-3 sm:px-5 sm:py-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-1">Langkah seterusnya</p>
                            <p class="text-sm text-slate-700 dark:text-slate-200 leading-relaxed">{{ $config['nextStep'] }}</p>
                        </div>
                        @if($showPayment)
                            <div class="w-full max-w-full min-w-0 rounded-2xl border border-slate-200/90 dark:border-slate-600/70 bg-white/95 dark:bg-slate-800/95 px-4 py-3.5 shadow-sm">
                                <div class="flex gap-3 items-start min-w-0">
                                    <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="min-w-0 flex-1 text-sm sm:text-base leading-relaxed text-slate-800 dark:text-slate-100">
                                        <span class="font-semibold {{ $config['titleClass'] }}">Yuran pembaharuan:</span>
                                        <span class="font-extrabold {{ $config['titleClass'] }}"> RM10.00</span>
                                        <span class="text-slate-600 dark:text-slate-300"> setiap tahun</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('semak.index') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 w-full lg:w-auto min-h-[52px] px-5 py-3.5 rounded-2xl bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-600 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-orange-300 dark:hover:border-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 shrink-0 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Semak semula
                </a>
            </div>
        </section>

        <div id="semak-toast-root" class="fixed bottom-4 left-4 right-4 z-[70] flex justify-center pointer-events-none sm:left-auto sm:right-6 sm:bottom-6"></div>

        <div class="{{ $showPayment ? 'grid lg:grid-cols-2 gap-5 lg:gap-6 lg:items-start' : 'max-w-2xl mx-auto space-y-4 sm:space-y-5' }}">
            {{-- Left Column --}}
            <div class="space-y-4 sm:space-y-5 min-w-0">

                {{-- ── Member Info Card ─────────────────────────────────────────── --}}
                @if($member)
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-lg shadow-slate-900/5 overflow-hidden">
                    <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <h3 class="text-xs font-semibold tracking-widest uppercase text-slate-500 dark:text-slate-400">Ringkasan ahli</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        @php $memberInitial = strtoupper(mb_substr($member->nama ?? 'A', 0, 1)); @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
                            <div class="relative h-16 w-16 shrink-0">
                                @if($memberPhotoUrl)
                                    <img src="{{ $memberPhotoUrl }}" alt="" width="64" height="64" class="member-avatar-photo h-16 w-16 rounded-2xl object-cover border-2 border-slate-100 dark:border-slate-600 shadow-md" onerror="this.classList.add('hidden'); document.getElementById('member-avatar-fallback-{{ $member->id ?? 'x' }}')?.classList.remove('hidden');">
                                @endif
                                <div id="member-avatar-fallback-{{ $member->id ?? 'x' }}" class="{{ $memberPhotoUrl ? 'hidden' : '' }} h-16 w-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-md ring-2 ring-white dark:ring-slate-800">
                                    <span class="text-white font-bold text-xl" aria-hidden="true">{{ $memberInitial }}</span>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 dark:text-slate-50 text-lg leading-tight">{{ $member->nama ?? '–' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono tabular-nums break-all">{{ $member->no_kp ?? '–' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-xl border border-slate-100 dark:border-slate-600/80 bg-slate-50/80 dark:bg-slate-700/40 px-4 py-3.5">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-1">No. ahli</p>
                                <p class="text-base font-bold text-slate-900 dark:text-slate-100 font-mono tabular-nums">{{ $member->no_ahli ?? '–' }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 dark:border-slate-600/80 bg-slate-50/80 dark:bg-slate-700/40 px-4 py-3.5">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-1">Status rekod</p>
                                @php
                                    $recordStatus = strtolower((string) ($member->status ?? ''));
                                    $recordBadge = match($recordStatus) {
                                        'active', 'aktif' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                        'expired' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300',
                                        default => 'bg-slate-200 text-slate-800 dark:bg-slate-600 dark:text-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex mt-0.5 text-xs font-bold px-3 py-1 rounded-lg {{ $recordBadge }}">
                                    {{ strtoupper($member->status ?? '–') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Payment History ──────────────────────────────────────────── --}}
                @if($member)
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        <h3 class="text-xs font-semibold tracking-widest uppercase text-slate-500 dark:text-slate-400">Sejarah Pembayaran</h3>
                    </div>
                    @if(empty($member->payments) || $member->payments->isEmpty())
                        <div class="py-10 flex flex-col items-center justify-center gap-2 text-slate-400 dark:text-slate-500">
                            <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-sm font-medium">Tiada rekod pembayaran.</p>
                        </div>
                    @else
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-700/40">
                                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tahun</th>
                                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis</th>
                                        <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                    @foreach($member->payments->sortByDesc('tahun_bayar') as $p)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-4 sm:px-5 py-3.5 font-semibold text-slate-800 dark:text-slate-200">{{ $p->tahun_bayar }}</td>
                                        <td class="px-4 sm:px-5 py-3.5 font-mono font-medium text-slate-700 dark:text-slate-300">
                                            RM {{ number_format($p->jumlah, 2) }}
                                        </td>
                                        <td class="px-4 sm:px-5 py-3.5 text-slate-600 dark:text-slate-400">
                                            {{ $p->jenis === 'pendaftaran_baru' ? 'Pendaftaran Baru' : ($p->jenis === 'pembaharuan' ? 'Pembaharuan' : ($p->yuran?->jenis_yuran ?? '–')) }}
                                        </td>
                                        <td class="px-4 sm:px-5 py-3.5">
                                            @if($p->status === 'approved')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                                    Disahkan
                                                </span>
                                            @elseif($p->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                                    Menunggu
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="block sm:hidden space-y-3 p-3">
                            @foreach($member->payments->sortByDesc('tahun_bayar') as $p)
                            <div class="rounded-2xl border border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-700/20 p-4 space-y-3 shadow-sm">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Tahun</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $p->tahun_bayar }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Jumlah</span>
                                    <span class="font-mono font-medium text-slate-700 dark:text-slate-300">RM {{ number_format($p->jumlah, 2) }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 shrink-0">Jenis</span>
                                    <span class="text-right text-sm text-slate-600 dark:text-slate-400">{{ $p->jenis === 'pendaftaran_baru' ? 'Pendaftaran Baru' : ($p->jenis === 'pembaharuan' ? 'Pembaharuan' : ($p->yuran?->jenis_yuran ?? '–')) }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</span>
                                    <div>
                                        @if($p->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                                Disahkan
                                            </span>
                                        @elseif($p->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                                Menunggu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Right Column --}}
            @if($showPayment)
            <div class="space-y-4 sm:space-y-5 lg:sticky lg:top-6 lg:self-start">
                {{-- ── Payment Section (expired / rejected only) ───────────────── --}}

            {{-- Step Indicator --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-white/80 dark:bg-slate-800/80 px-3 py-4 sm:px-4 shadow-sm" aria-label="Langkah pembaharuan">
                <p class="text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Pusat tindakan pembaharuan</p>
                <div class="flex items-center gap-0 px-0.5 sm:px-1">
                    @foreach([['1','Pilih akaun / QR'], ['2','Muat naik bukti'], ['3','Semakan admin']] as $i => $step)
                        <div class="flex items-center {{ $i < 2 ? 'flex-1 min-w-0' : '' }}">
                            <div class="flex flex-col items-center w-full">
                                <div id="semak-renewal-step-dot-{{ $i }}" data-semak-step="{{ $i }}" class="semak-renewal-step w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold transition-all duration-300
                                    {{ $i === 0 ? 'bg-teal-600 text-white ring-2 sm:ring-4 ring-teal-100 dark:ring-teal-900/80 scale-105' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                    {{ $step[0] }}
                                </div>
                                <span id="semak-renewal-step-label-{{ $i }}" class="text-[10px] sm:text-xs mt-1.5 font-semibold text-center leading-tight max-w-[5rem] sm:max-w-none px-0.5 transition-colors {{ $i === 0 ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-slate-500' }}">{{ $step[1] }}</span>
                            </div>
                            @if($i < 2)
                            <div class="semak-renewal-connector flex-1 h-0.5 bg-slate-200 dark:bg-slate-600 mx-0.5 sm:mx-1 mb-6 sm:mb-7 shrink min-w-[6px] rounded-full transition-colors" data-connector-end="{{ $i }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bank Accounts --}}
            @if(!empty($paymentAccounts) && count($paymentAccounts) > 0)
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/>
                    </svg>
                    <div>
                        <h3 class="text-xs font-semibold tracking-widest uppercase text-slate-500 dark:text-slate-400">Bayar ke Akaun</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                        Sila bank in ke salah satu akaun di bawah. Imbas kod QR atau salin no. akaun.
                    </p>
                    <div class="grid gap-3 md:grid-cols-2">
                        @foreach($paymentAccounts as $account)
                        <div class="group relative flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/40 hover:border-teal-300 dark:hover:border-teal-600 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-all duration-200">
                            @if(!empty($account->qr_image_url))
                            <button type="button"
                                class="js-qr-preview flex-shrink-0 w-14 h-14 min-h-[44px] min-w-[44px] bg-white dark:bg-slate-800 rounded-lg p-1 border border-slate-200 dark:border-slate-600 shadow-sm hover:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                                data-qr-url="{{ $account->qr_image_url }}"
                                data-qr-account="{{ $account->account_name }}"
                                aria-label="Lihat imej QR {{ $account->account_name }}">
                                <img src="{{ $account->qr_image_url }}" alt="QR Code {{ $account->account_name }}" class="w-full h-full object-contain rounded"/>
                            </button>
                            @else
                            <div class="flex-shrink-0 w-14 h-14 bg-teal-100 dark:bg-teal-900/40 rounded-lg flex items-center justify-center">
                                <svg class="w-7 h-7 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $account->account_name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-mono">{{ $account->account_number }}</p>
                            </div>
                            <button type="button" onclick="copyText('{{ $account->account_number }}', this)"
                                class="flex-shrink-0 min-h-[44px] min-w-[44px] inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-100 dark:hover:bg-teal-900/30 dark:hover:text-teal-400 transition-all"
                                title="Salin No. Akaun">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment Submission Form --}}
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-4 h-4 shrink-0 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <h3 class="text-xs font-semibold tracking-widest uppercase text-slate-500 dark:text-slate-400">Hantar Bukti Pembayaran</h3>
                    </div>
                    @php
                        $semakMaxYearSlots = 1 + \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD;
                        $semakMaxRm = number_format(10 * $semakMaxYearSlots, 2);
                    @endphp
                    <span class="text-xs font-bold text-teal-700 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/30 border border-teal-200 dark:border-teal-700 px-3 py-1.5 rounded-full whitespace-nowrap">
                        RM10.00 – RM{{ $semakMaxRm }}
                    </span>
                </div>
                <div class="p-4 sm:p-5">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                        Selepas bank in, isi maklumat di bawah dan hantar untuk pengesahan admin.
                    </p>
                    <form method="POST" action="{{ route('semak.bayar') }}" enctype="multipart/form-data" class="space-y-5" id="paymentForm">
                        @csrf
                        <input type="hidden" name="no_kp" value="{{ $checkedNoKp }}">

                        @php
                            $semakCurrentYear = (int) date('Y');
                            $semakMaxYear = $semakCurrentYear + \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD;
                            $semakPaidYears = collect();
                            if ($member) {
                                foreach ($member->payments as $semakP) {
                                    if ($semakP->status !== 'approved') {
                                        continue;
                                    }
                                    $semakStart = $semakP->tahun_mula ?? $semakP->tahun_bayar;
                                    $semakEnd = $semakP->tahun_tamat ?? $semakP->tahun_mula ?? $semakP->tahun_bayar;
                                    for ($semakY = (int) $semakStart; $semakY <= (int) $semakEnd; $semakY++) {
                                        $semakPaidYears->push($semakY);
                                    }
                                }
                                $semakPaidYears = $semakPaidYears->unique()->sort()->values();
                            }
                        @endphp
                        <div class="space-y-3">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-2">
                                <p id="semakRenewalYearLegend" class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Pilih Tahun Pembaharuan
                                    <span class="text-red-500 ml-0.5">*</span>
                                    <span class="block font-normal text-slate-400 dark:text-slate-500 text-xs mt-0.5">Tandakan tahun yang ingin diperbaharui (RM10 setiap tahun).</span>
                                </p>
                                <div class="flex flex-wrap gap-2 shrink-0">
                                    <button type="button" id="semakSelectAllYears"
                                        class="inline-flex items-center justify-center min-h-[44px] sm:min-h-0 rounded-lg border border-teal-200 dark:border-teal-700 bg-teal-50 dark:bg-teal-900/30 px-4 sm:px-3 py-2.5 sm:py-1.5 text-xs font-semibold text-teal-800 dark:text-teal-200 hover:bg-teal-100 dark:hover:bg-teal-900/50 transition flex-1 sm:flex-initial">
                                        Pilih semua
                                    </button>
                                    <button type="button" id="semakClearYears"
                                        class="inline-flex items-center justify-center min-h-[44px] sm:min-h-0 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 sm:px-3 py-2.5 sm:py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition flex-1 sm:flex-initial">
                                        Kosongkan
                                    </button>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50/80 dark:bg-slate-900/30 overflow-hidden divide-y divide-slate-200 dark:divide-slate-600"
                                role="group"
                                aria-labelledby="semakRenewalYearLegend"
                                aria-describedby="semakRenewalYearsHint">
                                @for($year = $semakCurrentYear; $year <= $semakMaxYear; $year++)
                                    @php $semakIsPaid = $semakPaidYears->contains($year); @endphp
                                    <label for="semakYear{{ $year }}"
                                        class="flex items-center gap-3 sm:gap-4 px-3 sm:px-4 py-4 sm:py-3.5 min-h-[52px] cursor-pointer transition-colors hover:bg-white dark:hover:bg-slate-800/80 has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-55 has-[:disabled]:hover:bg-transparent">
                                        <input type="checkbox"
                                            name="years[]"
                                            value="{{ $year }}"
                                            id="semakYear{{ $year }}"
                                            class="semak-year-checkbox h-5 w-5 shrink-0 rounded border-slate-300 dark:border-slate-500 text-teal-600 focus:ring-2 focus:ring-teal-500 focus:ring-offset-0 dark:bg-slate-700 dark:focus:ring-offset-slate-900"
                                            @checked(! $semakIsPaid)
                                            @disabled($semakIsPaid)>
                                        <span class="flex-1 min-w-0">
                                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">Tahun {{ $year }}</span>
                                            @if($semakIsPaid)
                                                <span class="block text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sudah dibayar (disahkan)</span>
                                            @else
                                                <span class="block text-[10px] sm:text-xs text-teal-600 dark:text-teal-400 mt-0.5">RM10.00 — belum diperbaharui</span>
                                            @endif
                                        </span>
                                        @if(! $semakIsPaid)
                                            <span class="hidden sm:inline-flex items-center rounded-full bg-teal-100 dark:bg-teal-900/40 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-teal-800 dark:text-teal-200">Pilih</span>
                                        @endif
                                    </label>
                                @endfor
                            </div>
                            <p id="semakRenewalYearsHint" class="text-xs text-slate-400 dark:text-slate-500">Pilih sekurang-kurangnya satu tahun yang belum dibayar. Jumlah bank in hendaklah sepadan dengan jumlah di bawah.</p>
                            @error('years')
                                <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-4 sm:p-5 rounded-xl bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Jumlah bayaran (anggaran)</span>
                            <span class="text-base font-bold text-teal-700 dark:text-teal-400" id="semakTotalLine">
                                RM10.00 × <span id="semakYearCount">0</span> tahun = RM<span id="semakTotalPrice">0.00</span>
                            </span>
                        </div>

                        {{-- File Upload --}}
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Bukti Bayaran
                                <span class="text-red-500 ml-0.5">*</span>
                                <span class="font-normal text-slate-400 dark:text-slate-500 text-xs ml-1">(JPG, PNG, PDF — maks. 5MB)</span>
                            </label>
                            <label for="bukti_bayaran"
                                class="group flex flex-col items-center justify-center w-full min-h-[7rem] sm:min-h-[9rem] h-28 sm:h-36 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-700/40 hover:bg-teal-50 dark:hover:bg-teal-900/10 hover:border-teal-400 dark:hover:border-teal-600 transition-all duration-200 @error('bukti_bayaran') border-red-300 @enderror"
                                id="dropzone">
                                <div class="flex flex-col items-center justify-center gap-1.5 sm:gap-2 text-center px-3 sm:px-4 py-2" id="dropzone-default">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-slate-100 dark:bg-slate-600 group-hover:bg-teal-100 dark:group-hover:bg-teal-900/40 flex items-center justify-center transition-colors">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            Klik untuk muat naik atau seret fail ke sini
                                        </p>
                                        <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-500 mt-0.5">JPG, PNG atau PDF</p>
                                    </div>
                                </div>
                                <div class="hidden flex-col items-center justify-center gap-1.5 sm:gap-2 text-center px-3 sm:px-4 py-2" id="dropzone-preview">
                                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm font-semibold text-teal-700 dark:text-teal-400 break-all max-w-full px-1" id="file-name-display">Fail dipilih</p>
                                    <p class="text-[11px] sm:text-xs text-slate-400">Klik untuk tukar fail</p>
                                </div>
                                <input type="file" id="bukti_bayaran" name="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required>
                            </label>
                            @error('bukti_bayaran')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            id="submitBtn"
                            class="w-full min-h-[52px] flex items-center justify-center gap-2.5 px-6 py-4 sm:py-3.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-semibold text-sm sm:text-base shadow-md shadow-teal-500/20 hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Hantar Pembayaran Pembaharuan
                        </button>
                    </form>
                </div>
            </div>
            </div>
            @endif
        </div>

    </div>{{-- /max-w-2xl --}}
</div>{{-- /page-wrapper --}}

<div id="qrPreviewModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div id="qrPreviewBackdrop" class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-md rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-2xl overflow-hidden" role="dialog" aria-modal="true" aria-labelledby="qrPreviewTitle">
            <div class="px-3 sm:px-4 py-3 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                <div class="min-w-0 pr-2">
                    <h3 id="qrPreviewTitle" class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">Preview Kod QR</h3>
                    <p id="qrPreviewSubtitle" class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5"></p>
                </div>
                <button type="button" id="qrPreviewClose" class="shrink-0 min-h-[44px] min-w-[44px] inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" aria-label="Tutup preview kod QR">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6 flex items-center justify-center bg-gradient-to-b from-slate-50 to-white dark:from-slate-900/50 dark:to-slate-800/80">
                <img id="qrPreviewImage" src="" alt="Preview Kod QR" class="max-h-[58vh] sm:max-h-[72vh] w-full max-w-sm object-contain rounded-2xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-inner"/>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showSemakToast(message, variant) {
        const root = document.getElementById('semak-toast-root');
        if (!root) return;
        const el = document.createElement('div');
        const bg = variant === 'error'
            ? 'bg-red-600 text-white'
            : 'bg-slate-900 text-white dark:bg-white dark:text-slate-900';
        el.className = 'pointer-events-auto max-w-md rounded-xl px-4 py-3 text-sm font-medium shadow-2xl ' + bg;
        el.setAttribute('role', 'status');
        el.textContent = message;
        root.appendChild(el);
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(6px)';
            el.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            setTimeout(() => { el.remove(); }, 220);
        }, 2800);
    }

    function setRenewalStepState(fileChosen) {
        const activeMuted = 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400';
        const activeCurrent = 'bg-teal-600 text-white ring-2 sm:ring-4 ring-teal-100 dark:ring-teal-900/80 scale-105';
        const doneStyle = 'bg-emerald-600 text-white ring-2 ring-emerald-100 dark:ring-emerald-900/60';
        const labelMuted = 'text-slate-400 dark:text-slate-500';
        const labelActive = 'text-teal-700 dark:text-teal-400';
        const labelDone = 'text-emerald-700 dark:text-emerald-400';
        for (let i = 0; i < 3; i++) {
            const dot = document.getElementById('semak-renewal-step-dot-' + i);
            const label = document.getElementById('semak-renewal-step-label-' + i);
            if (!dot || !label) continue;
            dot.textContent = (fileChosen && i === 0) ? '✓' : String(i + 1);
            dot.className = 'semak-renewal-step w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold transition-all duration-300 ' + activeMuted;
            label.className = 'text-[10px] sm:text-xs mt-1.5 font-semibold text-center leading-tight max-w-[5rem] sm:max-w-none px-0.5 transition-colors ' + labelMuted;
            if (!fileChosen) {
                if (i === 0) {
                    dot.className = 'semak-renewal-step w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold transition-all duration-300 ' + activeCurrent;
                    label.className = 'text-[10px] sm:text-xs mt-1.5 font-semibold text-center leading-tight max-w-[5rem] sm:max-w-none px-0.5 transition-colors ' + labelActive;
                }
            } else if (i === 0) {
                dot.className = 'semak-renewal-step w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold transition-all duration-300 ' + doneStyle;
                label.className = 'text-[10px] sm:text-xs mt-1.5 font-semibold text-center leading-tight max-w-[5rem] sm:max-w-none px-0.5 transition-colors ' + labelDone;
            } else if (i === 1) {
                dot.className = 'semak-renewal-step w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-[10px] sm:text-xs font-bold transition-all duration-300 ' + activeCurrent;
                label.className = 'text-[10px] sm:text-xs mt-1.5 font-semibold text-center leading-tight max-w-[5rem] sm:max-w-none px-0.5 transition-colors ' + labelActive;
            }
        }
        document.querySelectorAll('.semak-renewal-connector').forEach((line, idx) => {
            if (fileChosen && idx === 0) {
                line.classList.add('bg-teal-400', 'dark:bg-teal-600');
                line.classList.remove('bg-slate-200', 'dark:bg-slate-600');
            } else {
                line.classList.remove('bg-teal-400', 'dark:bg-teal-600');
                line.classList.add('bg-slate-200', 'dark:bg-slate-600');
            }
        });
    }

    // ── Copy to clipboard ───────────────────────────────────────────────
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            showSemakToast('No. akaun disalin ke papan keratan.', 'success');
            const original = btn.innerHTML;
            btn.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`;
            setTimeout(() => { btn.innerHTML = original; }, 1800);
        }).catch(() => {
            showSemakToast('Tidak dapat menyalin. Cuba semula.', 'error');
        });
    }

    // ── QR image preview modal ──────────────────────────────────────────
    const qrPreviewModal = document.getElementById('qrPreviewModal');
    const qrPreviewImage = document.getElementById('qrPreviewImage');
    const qrPreviewClose = document.getElementById('qrPreviewClose');
    const qrPreviewBackdrop = document.getElementById('qrPreviewBackdrop');
    const qrPreviewButtons = document.querySelectorAll('.js-qr-preview');

    const qrPreviewSubtitle = document.getElementById('qrPreviewSubtitle');

    function openQrPreview(url, accountName) {
        if (!qrPreviewModal || !qrPreviewImage || !url) return;
        qrPreviewImage.src = url;
        qrPreviewImage.alt = accountName ? `Kod QR ${accountName}` : 'Preview Kod QR';
        if (qrPreviewSubtitle) {
            qrPreviewSubtitle.textContent = accountName || '';
        }
        qrPreviewModal.classList.remove('hidden');
        qrPreviewModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeQrPreview() {
        if (!qrPreviewModal || !qrPreviewImage) return;
        qrPreviewModal.classList.add('hidden');
        qrPreviewModal.setAttribute('aria-hidden', 'true');
        qrPreviewImage.removeAttribute('src');
        qrPreviewImage.alt = 'Preview Kod QR';
        if (qrPreviewSubtitle) {
            qrPreviewSubtitle.textContent = '';
        }
        document.body.style.overflow = '';
    }

    qrPreviewButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            openQrPreview(btn.dataset.qrUrl, btn.dataset.qrAccount || '');
        });
    });

    if (qrPreviewClose) {
        qrPreviewClose.addEventListener('click', closeQrPreview);
    }

    if (qrPreviewBackdrop) {
        qrPreviewBackdrop.addEventListener('click', closeQrPreview);
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && qrPreviewModal && !qrPreviewModal.classList.contains('hidden')) {
            closeQrPreview();
        }
    });

    // ── File upload preview ─────────────────────────────────────────────
    const fileInput   = document.getElementById('bukti_bayaran');
    const dzDefault   = document.getElementById('dropzone-default');
    const dzPreview   = document.getElementById('dropzone-preview');
    const fileNameEl  = document.getElementById('file-name-display');

    function resetDropzoneUi() {
        if (!dzPreview || !dzDefault || !fileNameEl) return;
        fileNameEl.textContent = 'Fail dipilih';
        dzPreview.classList.add('hidden');
        dzPreview.classList.remove('flex');
        dzDefault.classList.remove('hidden');
        dzDefault.classList.add('flex');
    }

    if (fileInput) {
        fileInput.addEventListener('change', () => {
            if (!fileInput.files || !fileInput.files[0]) {
                resetDropzoneUi();
                setRenewalStepState(false);
                return;
            }
            const name = fileInput.files[0].name;
            const file = fileInput.files[0];
            const maxBytes = 5120 * 1024;

            const allowedExt = /\.(jpe?g|png|pdf)$/i;
            if (!allowedExt.test(name)) {
                showSemakToast('Jenis fail tidak sah. Hanya JPG, PNG atau PDF dibenarkan.', 'error');
                fileInput.value = '';
                resetDropzoneUi();
                setRenewalStepState(false);
                return;
            }

            const suspicious = /(php|phtml|phar|exe|sh|bash|bat|cmd|js|html?|svg|xml)/i;
            if (suspicious.test(name)) {
                showSemakToast('Fail yang dicurigai dikesan. Muat naik ditolak.', 'error');
                fileInput.value = '';
                resetDropzoneUi();
                setRenewalStepState(false);
                return;
            }

            if (file.size > maxBytes) {
                showSemakToast('Fail terlalu besar. Maksimum 5MB.', 'error');
                fileInput.value = '';
                resetDropzoneUi();
                setRenewalStepState(false);
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (file.type && !allowedTypes.includes(file.type)) {
                showSemakToast('Jenis fail tidak sah (MIME).', 'error');
                fileInput.value = '';
                resetDropzoneUi();
                setRenewalStepState(false);
                return;
            }

            fileNameEl.textContent = name.length > 32 ? name.substring(0, 32) + '…' : name;
            dzDefault.classList.add('hidden');
            dzDefault.classList.remove('flex');
            dzPreview.classList.remove('hidden');
            dzPreview.classList.add('flex');
            setRenewalStepState(true);
        });
    }

    if (document.getElementById('semak-renewal-step-dot-0')) {
        setRenewalStepState(false);
    }

    // ── Drag & Drop ─────────────────────────────────────────────────────
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        ['dragenter','dragover'].forEach(e => dropzone.addEventListener(e, ev => {
            ev.preventDefault();
            dropzone.classList.add('border-teal-500', 'bg-teal-50', 'dark:bg-teal-950/30', 'ring-4', 'ring-teal-400/50', 'scale-[1.01]');
        }));
        ['dragleave','drop'].forEach(e => dropzone.addEventListener(e, ev => {
            ev.preventDefault();
            dropzone.classList.remove('border-teal-500', 'bg-teal-50', 'dark:bg-teal-950/30', 'ring-4', 'ring-teal-400/50', 'scale-[1.01]');
        }));
        dropzone.addEventListener('drop', ev => {
            ev.preventDefault();
            if (fileInput && ev.dataTransfer.files && ev.dataTransfer.files[0]) {
                fileInput.files = ev.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // ── Semak multi-year total (checkboxes) ────────────────────────────
    function getSemakYearCheckboxes() {
        return document.querySelectorAll('input.semak-year-checkbox:not([disabled])');
    }
    function updateSemakRenewalTotal() {
        let count = 0;
        getSemakYearCheckboxes().forEach((cb) => {
            if (cb.checked) count++;
        });
        const yearCountEl = document.getElementById('semakYearCount');
        const totalPriceEl = document.getElementById('semakTotalPrice');
        if (yearCountEl) yearCountEl.textContent = String(count);
        if (totalPriceEl) totalPriceEl.textContent = (count * 10).toFixed(2);
    }
    getSemakYearCheckboxes().forEach((cb) => {
        cb.addEventListener('change', updateSemakRenewalTotal);
    });
    updateSemakRenewalTotal();

    document.getElementById('semakSelectAllYears')?.addEventListener('click', () => {
        getSemakYearCheckboxes().forEach((cb) => {
            cb.checked = true;
        });
        updateSemakRenewalTotal();
    });
    document.getElementById('semakClearYears')?.addEventListener('click', () => {
        getSemakYearCheckboxes().forEach((cb) => {
            cb.checked = false;
        });
        updateSemakRenewalTotal();
    });

    // ── Submit button loading state ─────────────────────────────────────
    const paymentForm = document.getElementById('paymentForm');
    const submitBtn   = document.getElementById('submitBtn');
    if (paymentForm && submitBtn) {
        paymentForm.addEventListener('submit', (e) => {
            let selected = 0;
            getSemakYearCheckboxes().forEach((cb) => {
                if (cb.checked) selected++;
            });
            if (selected === 0) {
                e.preventDefault();
                showSemakToast('Sila pilih sekurang-kurangnya satu tahun pembaharuan.', 'error');
                return;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Menghantar...`;
        });
    }
</script>
@endpush

@endsection
