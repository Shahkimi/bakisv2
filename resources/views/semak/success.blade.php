@extends('layouts.app')

@section('title', 'Pendaftaran Berjaya')

@section('content')
@php
    $member = $member ?? session('member');
    $maskedKp = $member && $member->no_kp
        ? (substr((string) $member->no_kp, 0, 6).'******')
        : null;
@endphp
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50/40 to-teal-50/30 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-10 sm:py-14 px-4 sm:px-6 flex items-center justify-center">
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -right-24 h-72 w-72 rounded-full bg-orange-300/25 dark:bg-orange-600/15 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-24 h-72 w-72 rounded-full bg-teal-300/25 dark:bg-teal-600/10 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg">
        <div class="rounded-3xl border border-slate-200/90 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 shadow-2xl shadow-slate-900/10 dark:shadow-black/40 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-orange-500 via-amber-400 to-teal-500"></div>
            <div class="p-8 sm:p-10">
                @if($member)
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-lg shadow-teal-600/25 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-center text-slate-900 dark:text-white tracking-tight">
                        Pendaftaran diterima
                    </h1>
                    <p class="mt-3 text-center text-slate-600 dark:text-slate-300 leading-relaxed">
                        Terima kasih, <strong class="text-slate-900 dark:text-white">{{ $member->nama }}</strong>.
                        Permohonan ahli baharu dan bukti bayaran <strong>RM12.00</strong> telah direkodkan untuk semakan admin.
                    </p>
                    @if($maskedKp)
                        <p class="mt-4 text-center text-sm font-mono tabular-nums text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/80 rounded-xl py-2.5 px-3 border border-slate-100 dark:border-slate-700">
                            No. KP: {{ $maskedKp }}
                        </p>
                    @endif

                    <div class="mt-8 rounded-2xl border border-teal-200/80 dark:border-teal-900/50 bg-teal-50/60 dark:bg-teal-950/25 p-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-800 dark:text-teal-300 mb-3">Apa seterusnya?</p>
                        <ul class="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                            <li class="flex gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-[11px] font-bold text-white">1</span>
                                <span>Pendaftaran diterima oleh sistem</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-[11px] font-bold text-white">2</span>
                                <span>Bukti bayaran dihantar untuk semakan</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-300 dark:bg-slate-600 text-[11px] font-bold text-slate-800 dark:text-slate-100">3</span>
                                <span>Admin mengesahkan pembayaran</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-300 dark:bg-slate-600 text-[11px] font-bold text-slate-800 dark:text-slate-100">4</span>
                                <span>No. ahli dijana selepas kelulusan</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-200 dark:bg-slate-700 text-slate-500 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-center text-slate-900 dark:text-white">Sesi tidak sah</h1>
                    <p class="mt-2 text-center text-slate-600 dark:text-slate-400 text-sm">Gunakan Semak Status untuk teruskan.</p>
                @endif

                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('semak.index') }}" class="inline-flex flex-1 items-center justify-center min-h-[52px] rounded-xl bg-gradient-to-r from-orange-600 to-orange-700 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-orange-600/25 hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-4 focus:ring-orange-500/30 transition-all">
                        Kembali ke Semak
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex flex-1 items-center justify-center min-h-[52px] rounded-xl border-2 border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-5 py-3.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Laman utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
