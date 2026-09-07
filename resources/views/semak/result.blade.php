@extends('layouts.app')
@section('title', 'Keputusan Semakan')

@section('content')
@php
    $result      = $result ?? [];
    $status      = $result['status'] ?? 'not_found';
    $member      = $result['member'] ?? null;
    $payment     = $result['payment'] ?? null;
    $checkedNoKp = $checkedNoKp ?? ($member->no_kp ?? null);
    $needsRenewal = (bool) ($result['needs_renewal'] ?? false);
    $showPayment = in_array($status, ['expired', 'rejected'], true) || ($status === 'active' && $needsRenewal);
    $showRenewalSidebar = $showPayment || $status === 'pending';
    // Fully paid & up to date — no renewal step needed: 2-column layout (officer info | payment history).
    $showBentoSuccess = (bool) $member && ! $showRenewalSidebar;
    $contentGridClass = match(true) {
        $showRenewalSidebar => 'grid lg:grid-cols-[1fr_380px] xl:grid-cols-[1fr_400px] gap-5 lg:items-start',
        $showBentoSuccess => 'grid grid-cols-1 xl:grid-cols-5 gap-5 lg:gap-6 lg:items-start',
        default => 'max-w-2xl mx-auto space-y-5',
    };

    $config = match($status) {
        'active' => [
            'title'      => 'Status Aktif',
            'summary'    => 'Keahlian anda aktif untuk tahun semasa.',
            'nextStep'   => 'Simpan No. Ahli untuk rujukan. Tiada tindakan bayaran diperlukan buat masa ini.',
            'badge'      => 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700',
            'titleClass' => 'text-emerald-900 dark:text-emerald-100',
            'textClass'  => 'text-emerald-700 dark:text-emerald-300',
            'iconBg'     => 'bg-emerald-500',
            'icon'       => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-emerald-500',
            'accentBar'  => 'from-emerald-400 to-emerald-600',
            'stepBg'     => 'bg-emerald-600',
            'noticeBox'  => 'border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/70 dark:bg-emerald-950/25',
            'noticeLabel'=> 'text-emerald-800 dark:text-emerald-300',
            'noticeSub'  => 'text-emerald-700/80 dark:text-emerald-400/80',
        ],
        'pending' => [
            'title'      => 'Sedang Disemak',
            'summary'    => 'Pembayaran anda sedang diproses oleh admin.',
            'nextStep'   => 'Sila tunggu pengesahan. Semak semula selepas beberapa hari untuk status terkini.',
            'badge'      => 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700',
            'titleClass' => 'text-amber-900 dark:text-amber-100',
            'textClass'  => 'text-amber-700 dark:text-amber-300',
            'iconBg'     => 'bg-amber-500',
            'icon'       => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-amber-500',
            'accentBar'  => 'from-amber-400 to-amber-600',
            'stepBg'     => 'bg-amber-600',
            'noticeBox'  => 'border-amber-200 dark:border-amber-800/60 bg-amber-50/70 dark:bg-amber-950/25',
            'noticeLabel'=> 'text-amber-800 dark:text-amber-300',
            'noticeSub'  => 'text-amber-700/80 dark:text-amber-400/80',
        ],
        'rejected' => [
            'title'      => 'Permohonan Ditolak',
            'summary'    => 'Bukti bayaran tidak diluluskan. Sila hantar semula.',
            'nextStep'   => 'Ikuti langkah pembaharuan: pilih akaun bank, muat naik bukti baharu, dan hantar untuk semakan admin.',
            'badge'      => 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700',
            'titleClass' => 'text-red-900 dark:text-red-100',
            'textClass'  => 'text-red-700 dark:text-red-300',
            'iconBg'     => 'bg-red-500',
            'icon'       => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-red-500',
            'accentBar'  => 'from-red-400 to-red-600',
            'stepBg'     => 'bg-red-600',
            'noticeBox'  => 'border-red-200 dark:border-red-800/60 bg-red-50/70 dark:bg-red-950/25',
            'noticeLabel'=> 'text-red-800 dark:text-red-300',
            'noticeSub'  => 'text-red-700/80 dark:text-red-400/80',
        ],
        'expired' => [
            'title'      => 'Belum Diperbaharui',
            'summary'    => 'Keahlian belum diperbaharui untuk tahun ini.',
            'nextStep'   => 'Buat bayaran ke akaun rasmi, pilih tahun pembaharuan, muat naik bukti, kemudian hantar untuk pengesahan.',
            'badge'      => 'bg-orange-100 text-orange-700 border border-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:border-orange-700',
            'titleClass' => 'text-orange-900 dark:text-orange-100',
            'textClass'  => 'text-orange-700 dark:text-orange-300',
            'iconBg'     => 'bg-orange-500',
            'icon'       => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            'dot'        => 'bg-orange-500',
            'accentBar'  => 'from-orange-400 to-orange-600',
            'stepBg'     => 'bg-orange-600',
            'noticeBox'  => 'border-orange-200 dark:border-orange-800/60 bg-orange-50/70 dark:bg-orange-950/25',
            'noticeLabel'=> 'text-orange-800 dark:text-orange-300',
            'noticeSub'  => 'text-orange-700/80 dark:text-orange-400/80',
        ],
        default => [
            'title'      => 'Tiada Rekod Ditemui',
            'summary'    => 'No. KP tidak ditemui dalam rekod semasa.',
            'nextStep'   => 'Kembali ke halaman Semak untuk mendaftar sebagai ahli baharu dengan No. KP yang sama.',
            'badge'      => 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
            'titleClass' => 'text-slate-900 dark:text-slate-100',
            'textClass'  => 'text-slate-600 dark:text-slate-300',
            'iconBg'     => 'bg-slate-500',
            'icon'       => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'dot'        => 'bg-slate-400',
            'accentBar'  => 'from-slate-400 to-slate-500',
            'stepBg'     => 'bg-slate-500',
            'noticeBox'  => 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40',
            'noticeLabel'=> 'text-slate-700 dark:text-slate-300',
            'noticeSub'  => 'text-slate-500 dark:text-slate-400',
        ],
    };

    if ($status === 'active' && $needsRenewal) {
        $config['summary']    = 'Keahlian anda masih aktif dalam tempoh penangguhan, tetapi bayaran tahun semasa belum dibuat.';
        $config['nextStep']   = 'Sila buat bayaran pembaharuan untuk tahun '.date('Y').' bagi mengekalkan status keahlian anda.';
        $config['badge']      = 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700';
        $config['titleClass'] = 'text-amber-900 dark:text-amber-100';
        $config['textClass']  = 'text-amber-700 dark:text-amber-300';
        $config['iconBg']     = 'bg-amber-500';
        $config['dot']        = 'bg-amber-500';
        $config['accentBar']  = 'from-amber-400 to-amber-600';
        $config['stepBg']     = 'bg-amber-600';
        $config['noticeBox']  = 'border-amber-200 dark:border-amber-800/60 bg-amber-50/70 dark:bg-amber-950/25';
        $config['noticeLabel']= 'text-amber-800 dark:text-amber-300';
        $config['noticeSub']  = 'text-amber-700/80 dark:text-amber-400/80';
    }

    $memberPhotoUrl = $member && ! empty($member->gambar)
        ? asset('storage/members/photos/'.$member->gambar)
        : null;
@endphp

{{-- Toast root --}}
<div id="semak-toast-root" class="fixed bottom-4 right-4 z-[70] flex flex-col items-end gap-2 pointer-events-none sm:bottom-6 sm:right-6"></div>

{{-- Page --}}
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-6 sm:py-10 px-4 sm:px-6">
    <div class="{{ $showBentoSuccess ? 'max-w-6xl mx-auto space-y-5' : 'max-w-5xl mx-auto space-y-5' }}">

        {{-- Back link --}}
        <div class="flex justify-end">
            <a href="{{ route('semak.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                Semak semula
            </a>
        </div>

        {{-- ── Content Grid ─────────────────────────────────────────────────── --}}
        <div class="{{ $contentGridClass }}">

            @if($showBentoSuccess)
                {{-- ── 2-column layout: officer info | payment history ──────────── --}}
                <div class="semak-form-enter xl:col-span-2 min-w-0">
                    @include('semak.partials.member-info-card')
                </div>
                <div class="semak-form-enter xl:col-span-3 min-w-0" style="animation-delay:.08s">
                    @include('semak.partials.payment-history-card')
                </div>
            @else

            {{-- Left column --}}
            <div class="space-y-4 min-w-0">

                @if($member)
                    @include('semak.partials.member-info-card')
                    @include('semak.partials.payment-history-card')
                @else
                    {{-- No. KP not found — bare notice, no member/payment data to show --}}
                    <div class="rounded-xl border {{ $config['noticeBox'] }} p-3.5 space-y-2">
                        <p class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider {{ $config['noticeLabel'] }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
                            </svg>
                            {{ $config['title'] }}
                        </p>
                        @if($checkedNoKp)
                            <p class="text-xs text-slate-500 dark:text-slate-400">No. KP: <span class="font-mono font-semibold">{{ $checkedNoKp }}</span></p>
                        @endif
                        <p class="text-xs text-slate-700 dark:text-slate-300">{{ $config['summary'] }}</p>
                        <p class="text-[11px] {{ $config['noticeSub'] }}">{{ $config['nextStep'] }}</p>
                    </div>
                @endif
            </div>

            {{-- ── Right column: Renewal / Pending ───────────────────────────── --}}
            @if($showRenewalSidebar)
            <div class="space-y-4 lg:sticky lg:top-6 lg:self-start">

            @if($showPayment)
                {{-- Step indicator --}}
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-5" aria-label="Langkah pembaharuan">
                    <p class="text-center text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-4">Aliran Pembaharuan</p>
                    <div class="flex items-center">
                        @foreach([['1','Pilih Tahun'],['2','Akaun Bank'],['3','Muat Naik Bukti']] as $i => $step)
                            <div class="flex items-center {{ $i < 2 ? 'flex-1 min-w-0' : '' }}">
                                <div class="flex flex-col items-center">
                                    <div id="semak-renewal-step-dot-{{ $i }}" data-semak-step="{{ $i }}"
                                         class="semak-renewal-step w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                                         {{ $i === 0 ? 'bg-teal-600 text-white ring-4 ring-teal-100 dark:ring-teal-900/60 scale-105' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500' }}">
                                        {{ $step[0] }}
                                    </div>
                                    <span id="semak-renewal-step-label-{{ $i }}"
                                          class="text-[10px] mt-2 font-semibold text-center leading-tight w-16 transition-colors
                                          {{ $i === 0 ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-slate-500' }}">
                                        {{ $step[1] }}
                                    </span>
                                </div>
                                @if($i < 2)
                                <div class="semak-renewal-connector flex-1 h-0.5 bg-slate-200 dark:bg-slate-600 mx-2 mb-5 rounded-full transition-all duration-300" data-connector-end="{{ $i }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Renewal payment (2-step: pilih tahun -> bank & bukti) --}}
                @php
                    $semakHasAccounts = !empty($paymentAccounts) && count($paymentAccounts) > 0;
                    $semakCurrentYear = (int) date('Y');
                    $semakMaxYear = $semakCurrentYear + \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD;
                    $semakMaxYearSlots = 1 + \App\Services\KutipanService::RENEWAL_SELECTABLE_YEARS_AHEAD;
                    $semakMaxRm = number_format(10 * $semakMaxYearSlots, 2);
                    $semakPaidYears = collect();
                    if ($member) {
                        foreach ($member->payments as $semakP) {
                            if ($semakP->status !== 'approved') continue;
                            $semakStart = $semakP->tahun_mula ?? $semakP->tahun_bayar;
                            $semakEnd   = $semakP->tahun_tamat ?? $semakP->tahun_mula ?? $semakP->tahun_bayar;
                            for ($semakY = (int)$semakStart; $semakY <= (int)$semakEnd; $semakY++) {
                                $semakPaidYears->push($semakY);
                            }
                        }
                        $semakPaidYears = $semakPaidYears->unique()->sort()->values();
                    }
                    $semakFirstUnpaidYear = null;
                    for ($y = $semakCurrentYear; $y <= $semakMaxYear; $y++) {
                        if (! $semakPaidYears->contains($y)) {
                            $semakFirstUnpaidYear = $y;
                            break;
                        }
                    }
                @endphp
                <form method="POST" action="{{ route('semak.bayar') }}" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    <input type="hidden" name="no_kp" value="{{ $checkedNoKp }}">

                {{-- Step 1: Year selection --}}
                <div id="semakStep1" class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-1 h-4 rounded-full bg-teal-500 shrink-0"></span>
                            <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Tahun Pembaharuan</h3>
                        </div>
                        <span class="shrink-0 text-[11px] font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-900/30 border border-teal-200 dark:border-teal-700 px-2.5 py-1 rounded-lg">
                            RM10 – RM{{ $semakMaxRm }}
                        </span>
                    </div>
                    <div class="p-4 space-y-4">
                        {{-- Year selection --}}
                        <div class="space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p id="semakRenewalYearLegend" class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                        Pilih tahun <span class="text-red-500">*</span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">RM10.00 setiap tahun</p>
                                </div>
                                <div class="flex gap-1.5 shrink-0">
                                    <button type="button" id="semakSelectAllYears"
                                        class="min-h-[36px] px-3 py-1.5 rounded-lg border border-teal-200 dark:border-teal-700 bg-teal-50 dark:bg-teal-900/30 text-xs font-semibold text-teal-700 dark:text-teal-300 hover:bg-teal-100 dark:hover:bg-teal-900/50 transition-colors">
                                        Semua
                                    </button>
                                    <button type="button" id="semakClearYears"
                                        class="min-h-[36px] px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-xs font-medium text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                        Kosong
                                    </button>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden divide-y divide-slate-100 dark:divide-slate-700"
                                role="group" aria-labelledby="semakRenewalYearLegend" aria-describedby="semakRenewalYearsHint">
                                @for($year = $semakCurrentYear; $year <= $semakMaxYear; $year++)
                                    @php $semakIsPaid = $semakPaidYears->contains($year); @endphp
                                    <label for="semakYear{{ $year }}"
                                        class="flex items-center gap-3 px-4 py-3.5 min-h-[52px] cursor-pointer bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-50 has-[:disabled]:hover:bg-transparent dark:has-[:disabled]:hover:bg-transparent">
                                        <input type="checkbox"
                                            name="years[]"
                                            value="{{ $year }}"
                                            id="semakYear{{ $year }}"
                                            class="semak-year-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-500 text-teal-600 focus:ring-teal-500 focus:ring-offset-0 dark:bg-slate-700"
                                            @checked(! $semakIsPaid && $year === $semakFirstUnpaidYear)
                                            @disabled($semakIsPaid)>
                                        <div class="flex-1 min-w-0 flex items-center justify-between gap-2">
                                            <div>
                                                <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $year }}</span>
                                                @if($semakIsPaid)
                                                    <span class="text-[11px] text-slate-400 dark:text-slate-500">Sudah dibayar</span>
                                                @else
                                                    <span class="text-[11px] text-teal-600 dark:text-teal-400">RM10.00</span>
                                                @endif
                                            </div>
                                            @if($semakIsPaid)
                                                <span class="shrink-0 inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                                    LUNAS
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            <p id="semakRenewalYearsHint" class="text-[11px] text-slate-400 dark:text-slate-500">Pilih sekurang-kurangnya satu tahun.</p>
                            @error('years')
                                <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Total --}}
                        <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-teal-50 dark:bg-teal-900/20 border border-teal-100 dark:border-teal-800">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Jumlah bayaran</span>
                            <span class="font-bold text-teal-700 dark:text-teal-300" id="semakTotalLine">
                                RM10 × <span id="semakYearCount">0</span> tahun = <span class="text-base">RM<span id="semakTotalPrice">0.00</span></span>
                            </span>
                        </div>

                        <button type="button" id="semakGoStep2"
                            class="w-full min-h-[48px] mt-1 flex items-center justify-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-sm shadow-teal-500/20 transition-all">
                            Seterusnya: Akaun Bank
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Step 2: Bank accounts --}}
                <div id="semakStep2" class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
                        <button type="button" id="semakGoStep1" aria-label="Kembali ke Tahun Pembaharuan"
                            class="shrink-0 w-7 h-7 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                            </svg>
                        </button>
                        <span class="w-1 h-4 rounded-full bg-indigo-500 shrink-0"></span>
                        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Akaun Pembayaran</h3>
                    </div>
                    <div class="p-4 sm:p-5 space-y-4">
                        @if($semakHasAccounts)
                        <div class="space-y-2.5">
                            <p class="text-xs text-slate-400 dark:text-slate-500">Bank in ke akaun di bawah untuk tahun yang dipilih. Imbas QR atau salin nombor akaun.</p>

                        @foreach($paymentAccounts as $account)
                        <div class="rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20 hover:border-teal-200 dark:hover:border-teal-700 hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-all duration-200 p-3 space-y-3">
                            <div class="flex items-center gap-3">
                                @if(!empty($account->qr_image_url))
                                <button type="button"
                                    class="js-qr-preview shrink-0 w-14 h-14 bg-white dark:bg-slate-800 rounded-xl p-1 border border-slate-200 dark:border-slate-600 shadow-sm hover:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                                    data-qr-url="{{ $account->qr_image_url }}"
                                    data-qr-account="{{ $account->account_name }}"
                                    aria-label="Besarkan QR {{ $account->account_name }}">
                                    <img src="{{ $account->qr_image_url }}" alt="QR {{ $account->account_name }}" class="w-full h-full object-contain rounded-lg"/>
                                </button>
                                @else
                                <div class="shrink-0 w-14 h-14 rounded-xl bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center border border-teal-100 dark:border-teal-800">
                                    <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-slate-800 dark:text-slate-100 truncate">{{ $account->account_name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $account->account_number }}</p>
                                    @if(!empty($account->qr_image_url))
                                        <p class="text-[10px] text-teal-600 dark:text-teal-400 mt-0.5">Ketik QR untuk besarkan</p>
                                    @endif
                                </div>
                                <button type="button"
                                    class="js-copy-account shrink-0 w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-300 dark:text-slate-600 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-all"
                                    data-account-number="{{ $account->account_number }}"
                                    title="Salin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/>
                                    </svg>
                                </button>
                            </div>
                            @if(!empty($account->qr_image_url))
                            <button type="button"
                                class="js-qr-preview w-full flex items-center justify-center rounded-xl border border-dashed border-teal-200 dark:border-teal-800 bg-white dark:bg-slate-800 p-3 hover:border-teal-400 hover:bg-teal-50/60 dark:hover:bg-teal-900/20 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                                data-qr-url="{{ $account->qr_image_url }}"
                                data-qr-account="{{ $account->account_name }}"
                                aria-label="Besarkan QR {{ $account->account_name }}">
                                <img src="{{ $account->qr_image_url }}" alt="Kod QR {{ $account->account_name }}" class="w-32 h-32 object-contain"/>
                            </button>
                            @endif
                        </div>
                        @endforeach

                        {{-- Bank transfer reference instructions --}}
                        <div class="rounded-xl border border-amber-200 dark:border-amber-800/60 bg-amber-50/70 dark:bg-amber-950/25 p-3.5 space-y-2">
                            <p class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                                </svg>
                                Penting: Isi ruangan rujukan bank
                            </p>
                            <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300">
                                <p class="flex flex-wrap items-baseline gap-x-2">
                                    <span class="shrink-0 font-bold text-amber-700 dark:text-amber-400">Rujukan 1:</span>
                                    <span class="font-semibold">Nama Ahli</span>
                                </p>
                                <p class="flex flex-wrap items-baseline gap-x-2">
                                    <span class="shrink-0 font-bold text-amber-700 dark:text-amber-400">Rujukan 2:</span>
                                    <span class="font-semibold">Pendaftaran / Pembaharuan BAKIS</span>
                                </p>
                            </div>
                            <p class="text-[11px] text-amber-700/80 dark:text-amber-400/80">Rujukan ini memudahkan pentadbir mengesahkan bayaran anda dengan lebih pantas.</p>
                        </div>

                        </div>
                        @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">Tiada akaun pembayaran dikonfigurasi buat masa ini. Sila teruskan ke langkah muat naik bukti bayaran.</p>
                        @endif

                        <button type="button" id="semakGoStep3"
                            class="w-full min-h-[48px] mt-1 flex items-center justify-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-sm shadow-teal-500/20 transition-all">
                            Seterusnya: Muat Naik Bukti
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Step 3: Upload proof --}}
                <div id="semakStep3" class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
                        <button type="button" id="semakGoStep2Back" aria-label="Kembali ke Akaun Pembayaran"
                            class="shrink-0 w-7 h-7 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                            </svg>
                        </button>
                        <span class="w-1 h-4 rounded-full bg-indigo-500 shrink-0"></span>
                        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Muat Naik Bukti Bayaran</h3>
                    </div>
                    <div class="p-4 sm:p-5 space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    Bukti Bayaran <span class="text-red-500">*</span>
                                    <span class="font-normal text-xs text-slate-400 dark:text-slate-500 ml-1">JPG, PNG, PDF — maks. 5MB</span>
                                </label>
                                <label for="bukti_bayaran"
                                    class="group flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl cursor-pointer bg-slate-50/50 dark:bg-slate-900/20 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 hover:border-teal-400 dark:hover:border-teal-600 transition-all duration-200 @error('bukti_bayaran') border-red-300 @enderror"
                                    id="dropzone">
                                    <div class="flex flex-col items-center justify-center gap-2 text-center px-4" id="dropzone-default">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 group-hover:bg-teal-100 dark:group-hover:bg-teal-900/40 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            Klik atau seret fail ke sini
                                        </p>
                                    </div>
                                    <div class="hidden flex-col items-center justify-center gap-2 text-center px-4" id="dropzone-preview">
                                        <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-teal-700 dark:text-teal-400 break-all max-w-full px-2" id="file-name-display">Fail dipilih</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500">Klik untuk tukar</p>
                                    </div>
                                    <input type="file" id="bukti_bayaran" name="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required>
                                </label>
                                @error('bukti_bayaran')
                                    <p class="text-xs text-red-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            @if (! empty($turnstileEnabled))
                                <div class="mb-4">
                                    <div style="display:flex; justify-content:center; min-height:65px;">
                                        <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                                    </div>
                                    @error('cf-turnstile-response')
                                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            {{-- Submit --}}
                            <button type="submit" id="submitBtn"
                                class="w-full min-h-[52px] flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-semibold text-sm shadow-md shadow-teal-500/20 hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                </svg>
                                Hantar Pembayaran Pembaharuan
                            </button>
                    </div>
                </div>
                </form>
            @elseif($status === 'pending')
                @php
                    $pendingPayments = $member
                        ? $member->payments->where('status', 'pending')->sortBy(fn ($p) => $p->coverageStart())->values()
                        : collect();
                    $pendingTotal = $pendingPayments->sum('jumlah');
                @endphp

                {{-- Status summary card --}}
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
                        <span class="w-1 h-4 rounded-full bg-amber-500 shrink-0"></span>
                        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Status Pembayaran</h3>
                    </div>
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-amber-900 dark:text-amber-100">Menunggu Kelulusan Admin</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-snug">Bukti bayaran anda telah diterima. Semakan biasanya mengambil masa beberapa hari bekerja.</p>
                        </div>
                    </div>
                </div>

                {{-- Pending payments detail --}}
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-2.5">
                        <span class="w-1 h-4 rounded-full bg-amber-500 shrink-0"></span>
                        <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">Bayaran Menunggu Pengesahan</h3>
                    </div>

                    @if($pendingPayments->isEmpty())
                        <div class="py-10 flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 px-4 text-center">Tiada butiran bayaran menunggu ditemui.</p>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @foreach($pendingPayments as $pp)
                            <div class="p-4 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $pp->coverageLabel() }}</span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500 truncate">
                                            {{ $pp->jenis === 'pendaftaran_baru' ? 'Pendaftaran Baru' : ($pp->jenis === 'pembaharuan' ? 'Pembaharuan' : ($pp->yuran?->jenis_yuran ?? '–')) }}
                                        </span>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                        Menunggu
                                    </span>
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Dihantar {{ optional($pp->created_at)->format('d/m/Y') }} &middot; Bukti bayaran diterima
                                    </p>
                                    <span class="font-mono text-sm font-semibold text-slate-700 dark:text-slate-300 shrink-0">RM {{ number_format($pp->jumlah, 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between px-4 py-3.5 m-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Jumlah menunggu pengesahan</span>
                            <span class="font-bold text-amber-700 dark:text-amber-300 text-base">RM {{ number_format($pendingTotal, 2) }}</span>
                        </div>
                    @endif
                </div>

                {{-- What happens next --}}
                <div class="rounded-xl border border-amber-200 dark:border-amber-800/60 bg-amber-50/70 dark:bg-amber-950/25 p-3.5 space-y-2">
                    <p class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}"/>
                        </svg>
                        Apa Seterusnya?
                    </p>
                    <ul class="space-y-1 text-xs text-slate-700 dark:text-slate-300 list-disc list-inside">
                        <li>Admin akan menyemak bukti bayaran yang dihantar.</li>
                        <li>Status akan bertukar ke <span class="font-semibold text-emerald-700 dark:text-emerald-400">AKTIF</span> sebaik sahaja diluluskan.</li>
                        <li>Semak semula halaman ini selepas beberapa hari untuk status terkini.</li>
                    </ul>
                </div>
            @endif

            </div>
            @endif
            @endif
        </div>
    </div>
</div>

{{-- QR Preview Modal --}}
<div id="qrPreviewModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div id="qrPreviewBackdrop" class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-2xl overflow-hidden"
             role="dialog" aria-modal="true" aria-labelledby="qrPreviewTitle">
            <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <h3 id="qrPreviewTitle" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Kod QR</h3>
                    <p id="qrPreviewSubtitle" class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate"></p>
                </div>
                <button type="button" id="qrPreviewClose"
                    class="shrink-0 w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                    aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-5 flex items-center justify-center bg-slate-50 dark:bg-slate-900/40">
                <img id="qrPreviewImage" src="" alt="Preview Kod QR"
                     class="max-h-64 w-full object-contain rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 shadow-inner"/>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
/* ── Sejarah Pembayaran DataTable — reskinned to match the card UI ── */
#semak-payments-table_wrapper { font-size: .8125rem; }

#semak-payments-table_wrapper .dataTables_length { padding: .75rem 1.25rem 0; }
#semak-payments-table_wrapper .dataTables_length label {
    display: inline-flex; align-items: center; gap: .5rem; margin: 0;
    color: #64748b; font-weight: 500; font-size: .6875rem; text-transform: uppercase; letter-spacing: .04em;
}
.dark #semak-payments-table_wrapper .dataTables_length label { color: #94a3b8; }
#semak-payments-table_wrapper .dataTables_length select {
    border: 1.5px solid #e2e8f0; border-radius: .5rem; padding: .3rem 1.75rem .3rem .625rem;
    background: #fff; color: #1e293b; font-size: .8125rem; font-weight: 600; cursor: pointer; outline: none;
    transition: border-color .15s, box-shadow .15s; -webkit-appearance: none; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.1 1.02l-4.25 4.5a.75.75 0 01-1.1 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right .5rem center; background-size: 1rem;
}
#semak-payments-table_wrapper .dataTables_length select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.dark #semak-payments-table_wrapper .dataTables_length select { background-color: #1e293b; border-color: #475569; color: #e2e8f0; }

table.dataTable#semak-payments-table { border-collapse: collapse; width: 100% !important; margin: 0 !important; }
#semak-payments-table thead th {
    position: relative; padding: .875rem 1.25rem !important; border: 0 !important; cursor: pointer; user-select: none;
    transition: color .15s;
}
#semak-payments-table thead th.sorting_disabled { cursor: default; }
#semak-payments-table thead th:not(.sorting_disabled):hover { color: #4f46e5; }
.dark #semak-payments-table thead th:not(.sorting_disabled):hover { color: #818cf8; }
#semak-payments-table thead th.sorting:after,
#semak-payments-table thead th.sorting_asc:after,
#semak-payments-table thead th.sorting_desc:after {
    content: '⇅'; position: absolute; right: .625rem; top: 50%; transform: translateY(-50%);
    font-size: .7rem; opacity: .35; transition: opacity .15s;
}
#semak-payments-table thead th.sorting_asc:after { content: '↑'; opacity: 1; color: #4f46e5; }
#semak-payments-table thead th.sorting_desc:after { content: '↓'; opacity: 1; color: #4f46e5; }
.dark #semak-payments-table thead th.sorting_asc:after,
.dark #semak-payments-table thead th.sorting_desc:after { color: #818cf8; }

#semak-payments-table tbody tr { transition: background-color .12s; }
#semak-payments-table tbody tr:hover { background: #f8fafc; }
.dark #semak-payments-table tbody tr:hover { background: rgba(51,65,85,.35); }
#semak-payments-table tbody td { border-top: 1px solid #f1f5f9 !important; }
.dark #semak-payments-table tbody td { border-top-color: #334155 !important; }
#semak-payments-table tbody tr.odd,
#semak-payments-table tbody tr.even { background: transparent; }

#semak-payments-table_wrapper .dataTables_info {
    padding: .875rem 1.25rem; color: #94a3b8; font-size: .75rem;
}
.dark #semak-payments-table_wrapper .dataTables_info { color: #64748b; }
#semak-payments-table_wrapper .dataTables_paginate {
    padding: .75rem 1.25rem; display: flex !important; align-items: center; gap: .25rem;
}
#semak-payments-table_wrapper .dataTables_paginate .paginate_button {
    display: inline-flex !important; align-items: center; justify-content: center;
    min-width: 1.875rem; height: 1.875rem; padding: 0 .5rem; margin: 0 !important; border-radius: .5rem;
    border: 1px solid transparent !important; color: #64748b; font-size: .75rem; font-weight: 600; cursor: pointer;
    transition: all .15s; background: transparent !important;
}
#semak-payments-table_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #eef2ff !important; color: #4338ca; }
#semak-payments-table_wrapper .dataTables_paginate .paginate_button.current {
    background: #4f46e5 !important; color: #fff !important; box-shadow: 0 1px 3px rgba(79,70,229,.35);
}
#semak-payments-table_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .35; cursor: not-allowed; }
.dark #semak-payments-table_wrapper .dataTables_paginate .paginate_button { color: #94a3b8; }
.dark #semak-payments-table_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: rgba(99,102,241,.18) !important; color: #a5b4fc; }
.dark #semak-payments-table_wrapper .dataTables_paginate .paginate_button.current { background: #6366f1 !important; }

#semak-payments-table_wrapper .dataTables_processing {
    background: rgba(255,255,255,.94) !important; border-radius: .75rem; box-shadow: 0 4px 16px rgba(15,23,42,.08);
    color: #4f46e5 !important; font-weight: 600; padding: .5rem 1rem !important; border: 0 !important;
}
.dark #semak-payments-table_wrapper .dataTables_processing { background: rgba(30,41,59,.94) !important; color: #a5b4fc !important; }

#semak-payments-mobile > div { transition: background-color .12s; }
#semak-payments-mobile > div:active { background: #f8fafc; }
.dark #semak-payments-mobile > div:active { background: rgba(51,65,85,.35); }

/* ── Entrance animation — same convention as semak/index.blade.php ── */
@media (prefers-reduced-motion: no-preference) {
    @keyframes semak-form-rise { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .semak-form-enter { animation: semak-form-rise .45s cubic-bezier(.16,1,.3,1) both; }
}
@media (prefers-reduced-motion: reduce) {
    .semak-form-enter { animation: none !important; }
}

/* Inline copy button (e.g. No. KP) */
.js-copy-value.copy-inline {
    display: inline-flex; align-items: center; gap: .3rem; cursor: pointer;
    border-radius: .375rem; padding: .0625rem .25rem; margin: -.0625rem -.25rem;
    transition: background-color .15s ease;
}
.js-copy-value.copy-inline:hover { background: rgba(20,184,166,.1); }
.dark .js-copy-value.copy-inline:hover { background: rgba(45,212,191,.12); }
.js-copy-value.copy-inline:focus-visible { outline: none; box-shadow: 0 0 0 2px rgba(20,184,166,.5); }
.js-copy-value.copy-inline .js-copy-icon { opacity: .45; transition: opacity .15s ease; }
.js-copy-value.copy-inline:hover .js-copy-icon,
.js-copy-value.copy-inline:focus-visible .js-copy-icon { opacity: 1; }
</style>
@endpush

@push('scripts')
@if (! empty($turnstileEnabled))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    function showSemakToast(message, variant) {
        const root = document.getElementById('semak-toast-root');
        if (!root) return;
        const el = document.createElement('div');
        const bg = variant === 'error'
            ? 'bg-red-600 text-white'
            : 'bg-slate-900 text-white dark:bg-white dark:text-slate-900';
        el.className = 'pointer-events-auto max-w-sm rounded-xl px-4 py-3 text-sm font-medium shadow-2xl ' + bg;
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

    function setRenewalStepState(currentStep) {
        const activeMuted = 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500';
        const activeCurrent = 'bg-teal-600 text-white ring-4 ring-teal-100 dark:ring-teal-900/60 scale-105';
        const doneStyle = 'bg-emerald-600 text-white ring-2 ring-emerald-100 dark:ring-emerald-900/60';
        const labelMuted = 'text-slate-400 dark:text-slate-500';
        const labelActive = 'text-teal-700 dark:text-teal-400';
        const labelDone = 'text-emerald-700 dark:text-emerald-400';

        for (let i = 0; i < 3; i++) {
            const dot = document.getElementById('semak-renewal-step-dot-' + i);
            const label = document.getElementById('semak-renewal-step-label-' + i);
            if (!dot || !label) continue;
            const state = i < currentStep ? 'done' : (i === currentStep ? 'current' : 'muted');
            dot.textContent = state === 'done' ? '✓' : String(i + 1);
            const dotClass = state === 'done' ? doneStyle : (state === 'current' ? activeCurrent : activeMuted);
            const labelClass = state === 'done' ? labelDone : (state === 'current' ? labelActive : labelMuted);
            dot.className = 'semak-renewal-step w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 ' + dotClass;
            label.className = 'text-[10px] mt-2 font-semibold text-center leading-tight w-16 transition-colors ' + labelClass;
        }

        document.querySelectorAll('.semak-renewal-connector').forEach((line, idx) => {
            const filled = idx < currentStep;
            line.classList.toggle('bg-teal-400', filled);
            line.classList.toggle('dark:bg-teal-600', filled);
            line.classList.toggle('bg-slate-200', !filled);
            line.classList.toggle('dark:bg-slate-600', !filled);
        });
    }

    // Multistep: Pilih Tahun -> Akaun Bank -> Muat Naik Bukti
    const semakSteps = [
        document.getElementById('semakStep1'),
        document.getElementById('semakStep2'),
        document.getElementById('semakStep3'),
    ];
    let semakCurrentStepIdx = 0;

    function goToSemakStep(idx) {
        semakCurrentStepIdx = idx;
        semakSteps.forEach((el, i) => el && el.classList.toggle('hidden', i !== idx));
        setRenewalStepState(idx);
        if (idx > 0 && semakSteps[idx]) semakSteps[idx].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    document.getElementById('semakGoStep2')?.addEventListener('click', () => {
        const anyYearChecked = document.querySelectorAll('input.semak-year-checkbox:checked').length > 0;
        if (!anyYearChecked) {
            showSemakToast('Sila pilih sekurang-kurangnya satu tahun.', 'error');
            return;
        }
        goToSemakStep(1);
    });
    document.getElementById('semakGoStep3')?.addEventListener('click', () => goToSemakStep(2));
    document.getElementById('semakGoStep1')?.addEventListener('click', () => goToSemakStep(0));
    document.getElementById('semakGoStep2Back')?.addEventListener('click', () => goToSemakStep(1));

    // QR preview modal
    const qrPreviewModal    = document.getElementById('qrPreviewModal');
    const qrPreviewImage    = document.getElementById('qrPreviewImage');
    const qrPreviewClose    = document.getElementById('qrPreviewClose');
    const qrPreviewBackdrop = document.getElementById('qrPreviewBackdrop');
    const qrPreviewSubtitle = document.getElementById('qrPreviewSubtitle');

    function openQrPreview(url, accountName) {
        if (!qrPreviewModal || !qrPreviewImage || !url) return;
        qrPreviewImage.src = url;
        qrPreviewImage.alt = accountName ? `Kod QR ${accountName}` : 'Preview Kod QR';
        if (qrPreviewSubtitle) qrPreviewSubtitle.textContent = accountName || '';
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
        if (qrPreviewSubtitle) qrPreviewSubtitle.textContent = '';
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-qr-preview').forEach((btn) => {
        btn.addEventListener('click', () => openQrPreview(btn.dataset.qrUrl, btn.dataset.qrAccount || ''));
    });

    document.querySelectorAll('.js-copy-account').forEach((btn) => {
        btn.addEventListener('click', () => {
            const number = btn.dataset.accountNumber;
            if (!number) return;
            const original = btn.innerHTML;
            const succeed = () => {
                showSemakToast('No. akaun disalin ke papan keratan.', 'success');
                btn.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`;
                setTimeout(() => { btn.innerHTML = original; }, 1800);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(number).then(succeed).catch(() => fallbackCopy(number, succeed));
            } else {
                fallbackCopy(number, succeed);
            }
        });
    });

    // Generic "click to copy" for member identifiers (No. Ahli / No. KP) — reuses the same
    // clipboard + fallback + toast mechanics as the bank account copy button above.
    document.querySelectorAll('.js-copy-value').forEach((btn) => {
        btn.addEventListener('click', () => {
            const value = btn.dataset.copyValue;
            const label = btn.dataset.copyLabel || 'Nilai';
            if (!value) return;
            const iconEl = btn.querySelector('.js-copy-icon');
            const originalIcon = iconEl ? iconEl.innerHTML : '';
            const succeed = () => {
                showSemakToast(label + ' disalin ke papan keratan.', 'success');
                if (iconEl) {
                    iconEl.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`;
                    setTimeout(() => { iconEl.innerHTML = originalIcon; }, 1800);
                }
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(value).then(succeed).catch(() => fallbackCopy(value, succeed));
            } else {
                fallbackCopy(value, succeed);
            }
        });
    });

    function fallbackCopy(text, onSuccess) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            onSuccess();
        } catch (e) {
            showSemakToast('Tidak dapat menyalin. Cuba semula.', 'error');
        }
        document.body.removeChild(ta);
    }
    if (qrPreviewClose)    qrPreviewClose.addEventListener('click', closeQrPreview);
    if (qrPreviewBackdrop) qrPreviewBackdrop.addEventListener('click', closeQrPreview);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && qrPreviewModal && !qrPreviewModal.classList.contains('hidden')) closeQrPreview();
    });

    // File upload
    const fileInput  = document.getElementById('bukti_bayaran');
    const dzDefault  = document.getElementById('dropzone-default');
    const dzPreview  = document.getElementById('dropzone-preview');
    const fileNameEl = document.getElementById('file-name-display');

    function resetDropzoneUi() {
        if (!dzPreview || !dzDefault || !fileNameEl) return;
        fileNameEl.textContent = 'Fail dipilih';
        dzPreview.classList.add('hidden'); dzPreview.classList.remove('flex');
        dzDefault.classList.remove('hidden'); dzDefault.classList.add('flex');
    }

    if (fileInput) {
        fileInput.addEventListener('change', () => {
            if (!fileInput.files || !fileInput.files[0]) { resetDropzoneUi(); return; }
            const name = fileInput.files[0].name;
            const file = fileInput.files[0];
            const maxBytes = 5120 * 1024;
            if (!/\.(jpe?g|png|pdf)$/i.test(name)) {
                showSemakToast('Jenis fail tidak sah. Hanya JPG, PNG atau PDF.', 'error');
                fileInput.value = ''; resetDropzoneUi(); return;
            }
            if (/(php|phtml|phar|exe|sh|bash|bat|cmd|js|html?|svg|xml)/i.test(name)) {
                showSemakToast('Fail yang dicurigai dikesan. Muat naik ditolak.', 'error');
                fileInput.value = ''; resetDropzoneUi(); return;
            }
            if (file.size > maxBytes) {
                showSemakToast('Fail terlalu besar. Maksimum 5MB.', 'error');
                fileInput.value = ''; resetDropzoneUi(); return;
            }
            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (file.type && !allowedTypes.includes(file.type)) {
                showSemakToast('Jenis fail tidak sah (MIME).', 'error');
                fileInput.value = ''; resetDropzoneUi(); return;
            }
            fileNameEl.textContent = name.length > 32 ? name.substring(0, 32) + '…' : name;
            dzDefault.classList.add('hidden'); dzDefault.classList.remove('flex');
            dzPreview.classList.remove('hidden'); dzPreview.classList.add('flex');
        });
    }

    if (document.getElementById('semak-renewal-step-dot-0')) setRenewalStepState(semakCurrentStepIdx);

    // Drag & drop
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        ['dragenter','dragover'].forEach(e => dropzone.addEventListener(e, ev => {
            ev.preventDefault();
            dropzone.classList.add('border-teal-500', 'bg-teal-50', 'dark:bg-teal-950/30', 'ring-4', 'ring-teal-400/40', 'scale-[1.01]');
        }));
        ['dragleave','drop'].forEach(e => dropzone.addEventListener(e, ev => {
            ev.preventDefault();
            dropzone.classList.remove('border-teal-500', 'bg-teal-50', 'dark:bg-teal-950/30', 'ring-4', 'ring-teal-400/40', 'scale-[1.01]');
        }));
        dropzone.addEventListener('drop', ev => {
            ev.preventDefault();
            if (fileInput && ev.dataTransfer.files && ev.dataTransfer.files[0]) {
                fileInput.files = ev.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // Year checkboxes & total
    function getSemakYearCheckboxes() {
        return document.querySelectorAll('input.semak-year-checkbox:not([disabled])');
    }
    function updateSemakRenewalTotal() {
        let count = 0;
        getSemakYearCheckboxes().forEach(cb => { if (cb.checked) count++; });
        const yearCountEl  = document.getElementById('semakYearCount');
        const totalPriceEl = document.getElementById('semakTotalPrice');
        if (yearCountEl)  yearCountEl.textContent  = String(count);
        if (totalPriceEl) totalPriceEl.textContent = (count * 10).toFixed(2);
    }
    getSemakYearCheckboxes().forEach(cb => cb.addEventListener('change', updateSemakRenewalTotal));
    updateSemakRenewalTotal();

    document.getElementById('semakSelectAllYears')?.addEventListener('click', () => {
        getSemakYearCheckboxes().forEach(cb => { cb.checked = true; });
        updateSemakRenewalTotal();
    });
    document.getElementById('semakClearYears')?.addEventListener('click', () => {
        getSemakYearCheckboxes().forEach(cb => { cb.checked = false; });
        updateSemakRenewalTotal();
    });

    // Submit loading state
    const paymentForm = document.getElementById('paymentForm');
    const submitBtn   = document.getElementById('submitBtn');
    if (paymentForm && submitBtn) {
        paymentForm.addEventListener('submit', (e) => {
            let selected = 0;
            getSemakYearCheckboxes().forEach(cb => { if (cb.checked) selected++; });
            if (selected === 0) {
                e.preventDefault();
                showSemakToast('Sila pilih sekurang-kurangnya satu tahun pembaharuan.', 'error');
                return;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Menghantar...`;
        });
    }
</script>
<script>
(function () {
    var tableEl = document.getElementById('semak-payments-table');
    if (!tableEl || typeof jQuery === 'undefined') return;

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    var statusBadges = {
        approved: '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>Disahkan</span>',
        pending: '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>Menunggu</span>',
        rejected: '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>Ditolak</span>',
    };

    function statusBadge(status) {
        return statusBadges[status] || statusBadges.rejected;
    }

    function receiptCell(row, mobile) {
        if (!row.receipt_url) {
            return mobile ? '' : '<span class="text-slate-200 dark:text-slate-600">—</span>';
        }
        var cls = mobile
            ? 'inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800 no-underline'
            : 'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors no-underline';
        return '<a href="' + row.receipt_url + '" class="' + cls + '" aria-label="Muat turun resit PDF"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>PDF</a>';
    }

    var emptyStateHtml = '<div class="py-12 flex flex-col items-center gap-3">' +
        '<div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">' +
        '<svg class="w-6 h-6 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>' +
        '</div><p class="text-sm font-medium text-slate-400 dark:text-slate-500">Tiada rekod pembayaran.</p></div>';

    function renderMobileList(rows) {
        var mobileEl = document.getElementById('semak-payments-mobile');
        if (!mobileEl) return;
        if (!rows.length) {
            mobileEl.innerHTML = emptyStateHtml;
            return;
        }
        mobileEl.innerHTML = rows.map(function (row) {
            return '<div class="p-4 space-y-2.5">' +
                '<div class="flex items-center justify-between gap-2">' +
                '<div><span class="font-bold text-slate-800 dark:text-slate-200">' + escapeHtml(row.tahun_label) + '</span>' +
                '<span class="ml-2 font-mono text-sm text-slate-500 dark:text-slate-400">' + escapeHtml(row.jumlah_formatted) + '</span></div>' +
                statusBadge(row.status) +
                '</div>' +
                '<div class="flex items-center justify-between gap-2">' +
                '<span class="text-xs text-slate-400 dark:text-slate-500">' + escapeHtml(row.jenis_label) + '</span>' +
                receiptCell(row, true) +
                '</div></div>';
        }).join('');
    }

    var table = jQuery(tableEl).DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: '{{ route("semak.payments.data") }}',
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            data: function (d) { d.no_kp = '{{ $checkedNoKp }}'; }
        },
        columns: [
            { data: 'tahun_label', className: 'px-5 py-4 font-bold text-slate-800 dark:text-slate-200' },
            { data: 'jumlah_formatted', orderable: false, className: 'px-5 py-4 font-mono font-semibold text-slate-700 dark:text-slate-300' },
            { data: 'jenis_label', orderable: false, className: 'px-5 py-4 text-slate-500 dark:text-slate-400' },
            { data: 'status', className: 'px-5 py-4', render: function (status) { return statusBadge(status); } },
            { data: null, orderable: false, className: 'px-5 py-4', render: function (row) { return receiptCell(row, false); } },
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25], [5, 10, 25]],
        language: {
            processing: 'Memuatkan…',
            lengthMenu: 'Papar _MENU_ rekod',
            info: 'Rekod _START_–_END_ daripada _TOTAL_',
            infoEmpty: 'Tiada rekod',
            infoFiltered: '',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' },
            emptyTable: emptyStateHtml,
        },
        drawCallback: function () {
            var api = this.api();
            renderMobileList(api.rows({ page: 'current' }).data().toArray());

            // Hide pagination chrome entirely when everything already fits on one page.
            var info = api.page.info();
            var fitsOnePage = info.recordsTotal <= info.length;
            var wrapper = jQuery(tableEl).closest('.dataTables_wrapper');
            wrapper.find('.dataTables_paginate, .dataTables_length').toggle(!fitsOnePage);
        },
    });
})();
</script>
@endpush

@endsection
