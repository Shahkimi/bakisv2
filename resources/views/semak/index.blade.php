@extends('layouts.app')

@section('title', 'Semak Status Ahli')

@section('content')
@php
    $prefillNoKp = old('no_kp', $prefillNoKp ?? session('no_kp', ''));
    $showRegisterForm = (bool) (($showRegisterForm ?? false) || old('nama'));
    $lookupAlert = session('lookup_alert');
    $semakNegeriList = [
        'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis',
        'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu',
        'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya',
    ];
    $semakRegHasAccounts = !empty($paymentAccounts) && count($paymentAccounts) > 0;

    $regStep1Fields = ['nama', 'no_kp', 'jantina', 'jabatan_id', 'jawatan_id'];
    $regStep2Fields = ['email', 'alamat1', 'alamat2', 'poskod', 'bandar', 'negeri', 'no_tel', 'no_hp'];
    $regStep3Fields = ['bukti_bayaran', 'cf-turnstile-response'];
    $regInitialStep = 0;
    if ($errors->hasAny($regStep2Fields) && ! $errors->hasAny($regStep1Fields)) {
        $regInitialStep = 1;
    }
    if ($errors->hasAny($regStep3Fields) && ! $errors->hasAny(array_merge($regStep1Fields, $regStep2Fields))) {
        $regInitialStep = 2;
    }
@endphp
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50/40 to-stone-100 dark:from-slate-950 dark:via-gray-900 dark:to-slate-950 pb-28 xl:pb-10 py-6 sm:py-8 lg:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">
        {{-- Hero --}}
        <section class="relative overflow-hidden rounded-3xl border border-orange-200/80 dark:border-orange-900/50 bg-white dark:bg-slate-900 shadow-xl shadow-orange-900/5">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/[0.06] via-transparent to-teal-500/[0.05] pointer-events-none"></div>
            <div class="absolute -top-24 -right-16 h-48 w-48 rounded-full bg-orange-300/40 dark:bg-orange-600/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-10 h-44 w-44 rounded-full bg-teal-300/35 dark:bg-teal-600/15 blur-3xl pointer-events-none"></div>
            <div class="relative p-5 sm:p-7 lg:p-9">
                <p class="inline-flex items-center gap-2 rounded-full border border-orange-200 dark:border-orange-800/60 bg-orange-50 dark:bg-orange-950/40 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-orange-800 dark:text-orange-200">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-orange-500 animate-pulse" aria-hidden="true"></span>
                    Portal Semakan BAKIS
                </p>
                <h1 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Semak Status Keahlian Anda
                </h1>
                <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                    Masukkan No. KP 12 digit untuk semakan segera. Jika rekod tidak ditemui, anda boleh daftar ahli baharu di halaman ini — semuanya dalam satu aliran.
                </p>
                <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['1', 'Masukkan No. KP', 'Pastikan 12 digit tanpa tanda', 'text-orange-600 dark:text-orange-400', 'border-orange-200 dark:border-orange-900/50 bg-orange-50/80 dark:bg-orange-950/30'],
                        ['2', 'Semak status', 'Lihat keputusan serta-merta', 'text-teal-600 dark:text-teal-400', 'border-teal-200 dark:border-teal-900/50 bg-teal-50/80 dark:bg-teal-950/30'],
                        ['3', 'Daftar atau bayar', 'Ikut arahan pada skrin seterusnya', 'text-emerald-600 dark:text-emerald-400', 'border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/80 dark:bg-emerald-950/30'],
                    ] as $step)
                        <div class="flex gap-3 rounded-2xl border p-4 {{ $step[3] }} shadow-sm">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white dark:bg-slate-800 text-sm font-bold text-slate-800 dark:text-white border border-slate-200/80 dark:border-slate-600">
                                {{ $step[0] }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Langkah {{ $step[0] }}</p>
                                <p class="mt-0.5 text-sm font-bold text-slate-900 dark:text-white">{{ $step[1] }}</p>
                                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 leading-snug">{{ $step[2] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if($lookupAlert)
            @php
                $alertType = $lookupAlert['type'] ?? 'info';
                $alertClass = match($alertType) {
                    'warning' => 'border-amber-300 bg-amber-50 text-amber-950 dark:border-amber-800 dark:bg-amber-950/30 dark:text-amber-100',
                    'error' => 'border-red-300 bg-red-50 text-red-950 dark:border-red-900 dark:bg-red-950/30 dark:text-red-100',
                    default => 'border-sky-300 bg-sky-50 text-sky-950 dark:border-sky-800 dark:bg-sky-950/30 dark:text-sky-100',
                };
            @endphp
            <section class="rounded-2xl border-2 {{ $alertClass }} p-4 sm:p-5 shadow-sm" role="status">
                <p class="font-semibold">{{ $lookupAlert['title'] ?? 'Makluman' }}</p>
                @if(!empty($lookupAlert['message']))
                    <p class="mt-1.5 text-sm opacity-95 leading-relaxed">{{ $lookupAlert['message'] }}</p>
                @endif
            </section>
        @endif

        <div id="toast-root" class="fixed bottom-20 left-4 right-4 z-[60] flex justify-center pointer-events-none xl:bottom-6"></div>

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-5 lg:gap-6">
            {{-- Lookup --}}
            <section class="xl:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/90 p-5 sm:p-6 shadow-lg shadow-slate-900/5">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Semak No. KP</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Nombor sahaja — sistem akan membersihkan simbol secara automatik.</p>
                    </div>
                </div>

                <form id="semak-lookup-form" action="{{ route('semak.check') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label for="no_kp" class="flex items-center justify-between gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            <span>No. KP (12 digit)</span>
                            <span class="text-xs font-mono font-normal text-slate-500 dark:text-slate-400"><span id="kp-digit-count">0</span>/12</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="no_kp"
                                id="no_kp"
                                value="{{ $prefillNoKp }}"
                                maxlength="12"
                                pattern="[0-9]{12}"
                                inputmode="numeric"
                                autocomplete="off"
                                class="block w-full min-h-[52px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 pl-11 pr-3 py-3.5 text-base tabular-nums text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 @error('no_kp') border-red-500 focus:border-red-500 focus:ring-red-500/15 @enderror"
                                placeholder="Contoh: 900101011234"
                                required
                                autofocus
                            >
                        </div>
                        <p class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span class="font-medium text-slate-600 dark:text-slate-300">Format:</span>
                            <kbd id="kp-mask-preview" class="rounded-md border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/60 px-2 py-0.5 font-mono text-[11px] sm:text-xs text-slate-700 dark:text-slate-200 tracking-wider" aria-live="polite">______-__-____</kbd>
                        </p>
                        @error('no_kp')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @if (! empty($turnstileEnabled))
                        <div>
                            <div style="display:flex; justify-content:center; min-height:65px;">
                                <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                            </div>
                            @error('cf-turnstile-response')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <button type="submit" id="lookup-submit-btn" class="hidden xl:flex w-full min-h-[52px] items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3.5 text-sm sm:text-base font-semibold text-white shadow-lg shadow-orange-600/25 transition hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-4 focus:ring-orange-500/30 disabled:opacity-60 disabled:pointer-events-none">
                        <span class="lookup-btn-label">Semak status</span>
                    </button>
                </form>

                <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                    <a href="/login" class="inline-flex items-center justify-center min-h-[44px] rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/80 transition-colors">
                        Log masuk
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center min-h-[44px] rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/80 transition-colors">
                        Laman utama
                    </a>
                </div>
            </section>

            {{-- Registration / empty --}}
            <section id="register-form-section" class="xl:col-span-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/90 p-5 sm:p-6 lg:p-7 shadow-lg shadow-slate-900/5">
                @if($showRegisterForm)
                    <div class="mb-6 rounded-2xl border border-teal-200 dark:border-teal-800/60 bg-gradient-to-br from-teal-50/90 to-emerald-50/50 dark:from-teal-950/40 dark:to-emerald-950/20 p-4 sm:p-5">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar ahli baharu</h2>
                        <p class="mt-2 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            No. KP <span class="font-mono font-semibold text-teal-800 dark:text-teal-200">{{ $prefillNoKp ?: '—' }}</span> tiada dalam rekod.
                            Lengkapkan 3 langkah di bawah. Yuran pendaftaran <strong>RM12.00</strong>.
                        </p>
                    </div>

                    {{-- Step indicator --}}
                    <div class="mb-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-5" aria-label="Langkah pendaftaran">
                        <div class="flex items-center">
                            @foreach([['1','Peribadi'],['2','Perhubungan'],['3','Pembayaran']] as $i => $step)
                                <div class="flex items-center {{ $i < 2 ? 'flex-1 min-w-0' : '' }}">
                                    <div class="flex flex-col items-center">
                                        <div id="reg-step-dot-{{ $i }}" data-reg-step="{{ $i }}"
                                             class="reg-step w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                                             {{ $i === $regInitialStep ? 'bg-teal-600 text-white ring-4 ring-teal-100 dark:ring-teal-900/60 scale-105' : ($i < $regInitialStep ? 'bg-emerald-600 text-white ring-2 ring-emerald-100 dark:ring-emerald-900/60' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500') }}">
                                            {{ $i < $regInitialStep ? '✓' : $step[0] }}
                                        </div>
                                        <span id="reg-step-label-{{ $i }}"
                                              class="text-[10px] mt-2 font-semibold text-center leading-tight w-16 transition-colors
                                              {{ $i === $regInitialStep ? 'text-teal-700 dark:text-teal-400' : ($i < $regInitialStep ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500') }}">
                                            {{ $step[1] }}
                                        </span>
                                    </div>
                                    @if($i < 2)
                                    <div class="reg-step-connector flex-1 h-0.5 mx-2 mb-5 rounded-full transition-all duration-300 {{ $i < $regInitialStep ? 'bg-teal-400 dark:bg-teal-600' : 'bg-slate-200 dark:bg-slate-600' }}" data-connector-end="{{ $i }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form id="register-member-form" action="{{ route('semak.register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Step 1: Peribadi --}}
                        <div id="regStep1" class="{{ $regInitialStep === 0 ? '' : 'hidden' }} rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50/50 dark:bg-slate-900/30 p-4 sm:p-6 space-y-4 sm:space-y-5">
                            <div class="flex items-center gap-3 pb-1 border-b border-slate-200/80 dark:border-slate-600/80">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-600 text-sm font-bold text-white shadow-sm">1</span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Maklumat peribadi</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">No. KP, nama, jantina, jabatan &amp; jawatan</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="md:col-span-2">
                                    <label for="reg_no_kp" class="flex items-center justify-between gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                        <span>No. KP (12 digit) <span class="text-red-500">*</span></span>
                                        <span class="text-xs font-mono font-normal text-slate-500 dark:text-slate-400"><span id="reg-kp-digit-count">0</span>/12</span>
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" aria-hidden="true">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/>
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            name="no_kp"
                                            id="reg_no_kp"
                                            value="{{ $prefillNoKp }}"
                                            maxlength="12"
                                            pattern="[0-9]{12}"
                                            inputmode="numeric"
                                            autocomplete="off"
                                            required
                                            class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 pl-11 pr-3 py-3 text-base sm:text-sm tabular-nums text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 @error('no_kp') border-red-500 focus:border-red-500 focus:ring-red-500/15 @enderror"
                                            placeholder="Contoh: 900101011234"
                                        >
                                    </div>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Sahkan No. KP betul — digunakan sebagai pengenalan rekod keahlian.</p>
                                    @error('no_kp')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="nama" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Nama penuh <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" aria-hidden="true">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </span>
                                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 pl-11 pr-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 @error('nama') border-red-500 @enderror">
                                    </div>
                                    @error('nama')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Jantina <span class="text-red-500">*</span></label>
                                    <div class="flex flex-col sm:flex-row gap-2 rounded-xl border border-slate-200 dark:border-slate-600 p-2 sm:p-3 bg-white dark:bg-slate-800/50">
                                        <label class="inline-flex items-center min-h-[44px] flex-1 cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"><input type="radio" name="jantina" value="L" {{ old('jantina') === 'L' ? 'checked' : '' }} required class="h-5 w-5 shrink-0 border-slate-300 text-orange-600 focus:ring-orange-500"> <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Lelaki</span></label>
                                        <label class="inline-flex items-center min-h-[44px] flex-1 cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"><input type="radio" name="jantina" value="P" {{ old('jantina') === 'P' ? 'checked' : '' }} class="h-5 w-5 shrink-0 border-slate-300 text-orange-600 focus:ring-orange-500"> <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Perempuan</span></label>
                                    </div>
                                    @error('jantina')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div class="min-w-0 @error('jabatan_id') semak-select2-invalid @enderror">
                                    <label for="jabatan_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Jabatan <span class="text-red-500">*</span></label>
                                    <select name="jabatan_id" id="jabatan_id" required class="semak-select2 w-full" data-placeholder="Cari dan pilih jabatan...">
                                        <option value=""></option>
                                        @foreach($jabatans as $j)
                                            <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Jabatan tempat berkhidmat.</p>
                                    @error('jabatan_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div class="min-w-0 @error('jawatan_id') semak-select2-invalid @enderror">
                                    <label for="jawatan_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Jawatan <span class="text-red-500">*</span></label>
                                    <select name="jawatan_id" id="jawatan_id" required class="semak-select2 w-full" data-placeholder="Cari dan pilih jawatan...">
                                        <option value=""></option>
                                        @foreach($jawatans as $j)
                                            <option value="{{ $j->id }}" {{ old('jawatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jawatan }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Jawatan semasa.</p>
                                    @error('jawatan_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <button type="button" id="regGoStep2"
                                class="w-full min-h-[48px] flex items-center justify-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-sm shadow-teal-500/20 transition-all">
                                Seterusnya: Perhubungan
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Step 2: Perhubungan --}}
                        <div id="regStep2" class="{{ $regInitialStep === 1 ? '' : 'hidden' }} rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50/50 dark:bg-slate-900/30 p-4 sm:p-6 space-y-4 sm:space-y-5">
                            <div class="flex items-center gap-3 pb-1 border-b border-slate-200/80 dark:border-slate-600/80">
                                <button type="button" id="regBackTo1" aria-label="Kembali ke Maklumat Peribadi"
                                    class="shrink-0 w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                    </svg>
                                </button>
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white shadow-sm">2</span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Maklumat perhubungan</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Email, alamat &amp; nombor telefon</p>
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">E-mel</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                                @error('email')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="alamat1" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Alamat 1</label>
                                <input type="text" name="alamat1" id="alamat1" value="{{ old('alamat1') }}" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                                @error('alamat1')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="alamat2" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Alamat 2</label>
                                <input type="text" name="alamat2" id="alamat2" value="{{ old('alamat2') }}" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="min-w-0">
                                    <label for="poskod" class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                                        Poskod
                                        <span id="semak-poskod-loading" class="hidden text-xs font-normal text-orange-600 dark:text-orange-400 animate-pulse" aria-hidden="true">Mencari…</span>
                                    </label>
                                    <input type="text" name="poskod" id="poskod" value="{{ old('poskod') }}" inputmode="numeric" autocomplete="postal-code" maxlength="5" placeholder="5 digit"
                                        class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 transition-shadow">
                                    <p id="semak-poskod-hint" class="mt-1.5 hidden text-xs text-amber-600 dark:text-amber-400" role="status"></p>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Bandar dan negeri diisi automatik daripada poskod (data Malaysia).</p>
                                </div>
                                <div class="min-w-0">
                                    <label for="bandar" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Bandar</label>
                                    <input type="text" name="bandar" id="bandar" value="{{ old('bandar') }}" placeholder="Auto dari poskod"
                                        class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 transition-shadow">
                                </div>
                                <div class="min-w-0">
                                    <label for="negeri" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Negeri</label>
                                    <select name="negeri" id="negeri" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 @error('negeri') border-red-500 focus:border-red-500 focus:ring-red-500/15 @enderror">
                                        <option value="">— Pilih negeri —</option>
                                        @foreach($semakNegeriList as $n)
                                            <option value="{{ $n }}" @selected(old('negeri') === $n)>{{ $n }}</option>
                                        @endforeach
                                    </select>
                                    @error('negeri')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="no_tel" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">No. telefon</label>
                                    <input type="text" name="no_tel" id="no_tel" value="{{ old('no_tel') }}" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                                </div>
                                <div>
                                    <label for="no_hp" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">No. HP</label>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">
                                </div>
                            </div>

                            <button type="button" id="regGoStep3"
                                class="w-full min-h-[48px] flex items-center justify-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-sm shadow-teal-500/20 transition-all">
                                Seterusnya: Pembayaran
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Step 3: Pembayaran --}}
                        <div id="regStep3" class="{{ $regInitialStep === 2 ? '' : 'hidden' }} rounded-2xl border-2 border-orange-200/90 dark:border-orange-900/50 bg-gradient-to-br from-orange-50/80 to-amber-50/40 dark:from-orange-950/25 dark:to-amber-950/15 p-4 sm:p-6 space-y-5">
                            <div class="flex items-center gap-3 pb-1 border-b border-orange-200/70 dark:border-orange-900/40">
                                <button type="button" id="regBackTo2" aria-label="Kembali ke Maklumat Perhubungan"
                                    class="shrink-0 w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                    </svg>
                                </button>
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white shadow-sm">3</span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Pembayaran</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">Bank in yuran pendaftaran &amp; muat naik bukti bayaran</p>
                                </div>
                            </div>

                            {{-- Payment accounts --}}
                            <div class="space-y-2">
                                @if($semakRegHasAccounts)
                                <div class="space-y-2.5">
                                    <p class="text-xs text-slate-600 dark:text-slate-400">Bank in yuran pendaftaran <strong>RM12.00</strong> ke akaun di bawah. Imbas QR atau salin nombor akaun.</p>

                                @foreach($paymentAccounts as $account)
                                <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white/70 dark:bg-slate-800/40 hover:border-teal-300 dark:hover:border-teal-700 hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-all duration-200 p-3 space-y-3">
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
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">{{ $account->account_number }}</p>
                                            @if(!empty($account->qr_image_url))
                                                <p class="text-[10px] text-teal-600 dark:text-teal-400 mt-0.5">Ketik QR untuk besarkan</p>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="js-copy-account shrink-0 w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-400 dark:text-slate-500 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-all"
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
                                            <span class="font-semibold">Pendaftaran BAKIS</span>
                                        </p>
                                    </div>
                                    <p class="text-[11px] text-amber-700/80 dark:text-amber-400/80">Rujukan ini memudahkan pentadbir mengesahkan bayaran anda dengan lebih pantas.</p>
                                </div>
                                </div>
                                @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">Tiada akaun pembayaran dikonfigurasi buat masa ini. Sila teruskan ke langkah muat naik bukti bayaran.</p>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100">Bukti bayaran <span class="text-red-500">*</span> <span class="font-normal text-xs text-slate-600 dark:text-slate-400">(JPG, PNG, PDF — maks. 5MB)</span></label>
                                <label for="bukti_bayaran" id="reg-dropzone-bukti" class="group flex flex-col items-center justify-center w-full min-h-[8rem] cursor-pointer rounded-2xl border-2 border-dashed border-orange-300/80 dark:border-orange-800/60 bg-white/80 dark:bg-slate-800/60 px-4 py-5 transition-all hover:border-orange-500 hover:bg-orange-50/50 dark:hover:bg-orange-950/20 focus-within:ring-4 focus-within:ring-orange-500/20 @error('bukti_bayaran') border-red-400 @enderror">
                                    <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" required class="sr-only">
                                    <div id="reg-bukti-default" class="flex flex-col items-center text-center gap-2">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                        </div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Seret fail ke sini atau klik untuk pilih</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG atau PDF</p>
                                    </div>
                                    <div id="reg-bukti-preview" class="hidden flex-col items-center text-center gap-1 w-full">
                                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <p id="reg-bukti-filename" class="text-sm font-semibold text-slate-800 dark:text-slate-100 break-all px-2"></p>
                                        <p class="text-xs text-slate-500">Klik untuk tukar fail</p>
                                    </div>
                                </label>
                                @error('bukti_bayaran')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>

                            @if (! empty($turnstileEnabled))
                                <div>
                                    <div style="display:flex; justify-content:center; min-height:65px;">
                                        <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                                    </div>
                                    @error('cf-turnstile-response')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <button type="submit" id="register-submit-btn" class="w-full min-h-[52px] rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 px-4 py-3.5 text-sm sm:text-base font-semibold text-white shadow-lg shadow-teal-600/25 transition hover:from-teal-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-teal-500/30 disabled:opacity-60 disabled:pointer-events-none">
                                Hantar pendaftaran
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex min-h-[280px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-600 bg-gradient-to-b from-slate-50/90 to-white dark:from-slate-900/40 dark:to-slate-800/30 px-6 py-10 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664v.75h-4.5M4.5 15.75h4.5m0-12h4.5m-4.5 0V3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V4.5m-4.5 0h4.5"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Tiada borang pendaftaran lagi</h3>
                        <p class="mt-2 max-w-sm text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Selepas semakan, jika No. KP <strong>tidak</strong> dijumpai, borang daftar ahli baharu akan muncul di ruang ini. Mulakan dengan panel semakan di sebelah kiri.
                        </p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

{{-- Sticky lookup CTA (mobile / tablet) --}}
<div class="xl:hidden fixed bottom-0 left-0 right-0 z-50 border-t border-slate-200/90 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] shadow-[0_-8px_30px_rgba(0,0,0,0.08)] dark:shadow-black/40">
    <button type="submit" form="semak-lookup-form" id="lookup-sticky-submit" class="flex w-full min-h-[52px] items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-orange-600 to-orange-700 px-4 text-base font-semibold text-white shadow-lg shadow-orange-600/30 active:scale-[0.99] transition disabled:opacity-60 disabled:pointer-events-none">
        <span class="lookup-btn-label">Semak status</span>
    </button>
</div>

{{-- QR Preview Modal --}}
<div id="qrPreviewModal" class="fixed inset-0 z-[70] hidden" aria-hidden="true">
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

<style>
    @keyframes semak-form-rise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .semak-form-enter { animation: semak-form-rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) both; }
    .semak-section-stagger > * { opacity: 0; animation: semak-form-rise 0.45s cubic-bezier(0.22, 1, 0.36, 1) both; }
    .semak-section-stagger > *:nth-child(1) { animation-delay: 0.05s; }
    .semak-section-stagger > *:nth-child(2) { animation-delay: 0.12s; }
    .semak-section-stagger > *:nth-child(3) { animation-delay: 0.19s; }
</style>

<script>
    (function () {
        function showToast(message, variant) {
            var root = document.getElementById('toast-root');
            if (!root) return;
            var el = document.createElement('div');
            var bg = variant === 'error'
                ? 'bg-red-600 text-white'
                : 'bg-slate-900 text-white dark:bg-white dark:text-slate-900';
            el.className = 'pointer-events-auto max-w-md rounded-xl px-4 py-3 text-sm font-medium shadow-xl ' + bg + ' semak-form-enter';
            el.setAttribute('role', 'status');
            el.textContent = message;
            root.appendChild(el);
            setTimeout(function () {
                el.style.opacity = '0';
                el.style.transform = 'translateY(8px)';
                el.style.transition = 'opacity 0.25s, transform 0.25s';
                setTimeout(function () { el.remove(); }, 280);
            }, 3200);
        }

        function updateKpUi(input) {
            var digits = input.value.replace(/\D/g, '').slice(0, 12);
            input.value = digits;
            var maskEl = document.getElementById('kp-mask-preview');
            var countEl = document.getElementById('kp-digit-count');
            if (countEl) countEl.textContent = String(digits.length);
            if (maskEl) {
                var padded = digits + '·'.repeat(Math.max(0, 12 - digits.length));
                var a = padded.slice(0, 6);
                var b = padded.slice(6, 8);
                var c = padded.slice(8, 12);
                maskEl.textContent = a + '-' + b + '-' + c;
            }
        }

        var noKpInput = document.getElementById('no_kp');
        if (noKpInput) {
            noKpInput.addEventListener('input', function () { updateKpUi(noKpInput); });
            updateKpUi(noKpInput);
        }

        var regNoKpInput = document.getElementById('reg_no_kp');
        var regKpCountEl = document.getElementById('reg-kp-digit-count');
        function updateRegKpCount() {
            if (!regNoKpInput) return;
            var digits = regNoKpInput.value.replace(/\D/g, '').slice(0, 12);
            regNoKpInput.value = digits;
            if (regKpCountEl) regKpCountEl.textContent = String(digits.length);
        }
        if (regNoKpInput) {
            regNoKpInput.addEventListener('input', updateRegKpCount);
            updateRegKpCount();
        }

        var lookupForm = document.getElementById('semak-lookup-form');
        var lookupBtns = [document.getElementById('lookup-submit-btn'), document.getElementById('lookup-sticky-submit')].filter(Boolean);
        function setLookupLoading(loading) {
            lookupBtns.forEach(function (btn) {
                btn.disabled = loading;
                var label = btn.querySelector('.lookup-btn-label');
                if (!label) return;
                if (loading) {
                    label.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyemak…</span>';
                } else {
                    label.textContent = 'Semak status';
                }
            });
        }
        if (lookupForm) {
            lookupForm.addEventListener('submit', function () { setLookupLoading(true); });
        }

        function validateProofFile(file, input) {
            var maxBytes = 5120 * 1024;
            var allowedExt = /\.(jpe?g|png|pdf)$/i;
            if (!allowedExt.test(file.name)) {
                showToast('Jenis fail tidak sah. Hanya JPG, PNG atau PDF.', 'error');
                input.value = '';
                return false;
            }
            if (file.size > maxBytes) {
                showToast('Fail terlalu besar. Maksimum 5MB.', 'error');
                input.value = '';
                return false;
            }
            return true;
        }

        var buktiInput = document.getElementById('bukti_bayaran');
        var dzBukti = document.getElementById('reg-dropzone-bukti');
        if (buktiInput && dzBukti) {
            var def = document.getElementById('reg-bukti-default');
            var prev = document.getElementById('reg-bukti-preview');
            var fn = document.getElementById('reg-bukti-filename');
            buktiInput.addEventListener('change', function () {
                if (!buktiInput.files || !buktiInput.files[0]) return;
                if (!validateProofFile(buktiInput.files[0], buktiInput)) {
                    def.classList.remove('hidden');
                    prev.classList.add('hidden');
                    prev.classList.remove('flex');
                    return;
                }
                var name = buktiInput.files[0].name;
                fn.textContent = name.length > 40 ? name.substring(0, 40) + '…' : name;
                def.classList.add('hidden');
                prev.classList.remove('hidden');
                prev.classList.add('flex');
            });
            dzBukti.addEventListener('dragover', function (e) {
                e.preventDefault();
                dzBukti.classList.add('ring-4', 'ring-orange-400/50', 'border-orange-500');
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                dzBukti.addEventListener(ev, function (e) {
                    e.preventDefault();
                    dzBukti.classList.remove('ring-4', 'ring-orange-400/50', 'border-orange-500');
                });
            });
            dzBukti.addEventListener('drop', function (e) {
                e.preventDefault();
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    buktiInput.files = e.dataTransfer.files;
                    buktiInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        var poskodEl = document.getElementById('poskod');
        var bandarEl = document.getElementById('bandar');
        var negeriEl = document.getElementById('negeri');
        var poskodLoadingEl = document.getElementById('semak-poskod-loading');
        var poskodHintEl = document.getElementById('semak-poskod-hint');
        if (poskodEl && bandarEl && negeriEl) {
            var poskodLookupTimer = null;
            /** Increments only when a new lookup starts; used to ignore stale responses. */
            var poskodLookupGeneration = 0;
            var poskodAbortController = null;
            /** After a successful API fill, skip duplicate lookups for the same code (stops a 2nd failing fetch from showing "Ralat rangkaian"). */
            var poskodLastSuccessCode = '';
            var poskodInFlightCode = null;
            function semakClearPoskodHint() {
                if (!poskodHintEl) return;
                poskodHintEl.textContent = '';
                poskodHintEl.classList.add('hidden');
            }
            function semakSetPoskodLoading(on) {
                if (poskodLoadingEl) poskodLoadingEl.classList.toggle('hidden', !on);
                if (poskodEl) poskodEl.classList.toggle('bg-orange-50/50', on);
                if (poskodEl) poskodEl.classList.toggle('dark:bg-orange-950/20', on);
            }
            function semakFlashField(el, ok) {
                if (!el) return;
                var cOk = ['ring-2', 'ring-emerald-500/50'];
                var cBad = ['ring-2', 'ring-red-500/50'];
                el.classList.remove.apply(el, cOk.concat(cBad));
                var add = ok ? cOk : cBad;
                add.forEach(function (c) { el.classList.add(c); });
                setTimeout(function () {
                    add.forEach(function (c) { el.classList.remove(c); });
                }, 2000);
            }
            function semakNegeriSetValue(stateName) {
                if (!stateName || negeriEl.tagName !== 'SELECT') return false;
                var i;
                for (i = 0; i < negeriEl.options.length; i++) {
                    if (negeriEl.options[i].value === stateName) {
                        negeriEl.selectedIndex = i;
                        return true;
                    }
                }
                return false;
            }
            function semakParseJsonResponse(res) {
                return res.text().then(function (text) {
                    if (!text || !text.trim()) {
                        return { res: res, data: null };
                    }
                    try {
                        return { res: res, data: JSON.parse(text) };
                    } catch (e) {
                        return { res: res, data: null, parseError: true };
                    }
                });
            }
            function semakIsAbortError(err) {
                if (!err) return false;
                if (err.name === 'AbortError') return true;
                if (typeof err.code === 'number' && err.code === 20) return true;
                return false;
            }
            function semakLookupPostcode(code) {
                if (code.length !== 5) return;
                if (code === poskodLastSuccessCode) return;
                if (poskodInFlightCode === code) return;
                var myGen = ++poskodLookupGeneration;
                if (poskodAbortController) {
                    poskodAbortController.abort();
                }
                poskodAbortController = new AbortController();
                var signal = poskodAbortController.signal;
                poskodInFlightCode = code;
                semakSetPoskodLoading(true);
                semakClearPoskodHint();
                fetch('{{ url('/api/postcode') }}/' + encodeURIComponent(code), {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                    signal: signal,
                })
                    .then(function (res) {
                        return semakParseJsonResponse(res);
                    })
                    .then(function (out) {
                        if (myGen !== poskodLookupGeneration) return;
                        try {
                            if (out.parseError) {
                                if (poskodHintEl) {
                                    poskodHintEl.textContent = 'Respons pelayan tidak sah. Cuba semula.';
                                    poskodHintEl.classList.remove('hidden');
                                }
                                semakFlashField(poskodEl, false);
                                return;
                            }
                            if (out.res.ok && out.data && out.data.success) {
                                var city = out.data.city || out.data.bandar || '';
                                var state = out.data.state || out.data.negeri || '';
                                bandarEl.value = city;
                                if (!semakNegeriSetValue(state)) {
                                    negeriEl.value = '';
                                    if (poskodHintEl) {
                                        poskodHintEl.textContent = 'Negeri daripada data poskod tidak sepadan senarai. Sila pilih negeri secara manual.';
                                        poskodHintEl.classList.remove('hidden');
                                    }
                                }
                                poskodLastSuccessCode = code;
                                semakFlashField(poskodEl, true);
                                semakFlashField(bandarEl, true);
                                semakFlashField(negeriEl, true);
                            } else {
                                var msg = (out.data && out.data.message) ? out.data.message : 'Poskod tidak dijumpai.';
                                if (poskodHintEl) {
                                    poskodHintEl.textContent = msg;
                                    poskodHintEl.classList.remove('hidden');
                                }
                                semakFlashField(poskodEl, false);
                            }
                        } catch (e) {
                            if (poskodHintEl) {
                                poskodHintEl.textContent = 'Ralat paparan. Data mungkin telah diisi — semak bandar/negeri.';
                                poskodHintEl.classList.remove('hidden');
                            }
                        } finally {
                            if (poskodInFlightCode === code) poskodInFlightCode = null;
                            semakSetPoskodLoading(false);
                        }
                    })
                    .catch(function (err) {
                        if (semakIsAbortError(err)) return;
                        if (myGen !== poskodLookupGeneration) return;
                        if (poskodInFlightCode === code) poskodInFlightCode = null;
                        semakSetPoskodLoading(false);
                        if (code === poskodLastSuccessCode) return;
                        if (poskodHintEl) {
                            poskodHintEl.textContent = 'Ralat rangkaian. Cuba semula.';
                            poskodHintEl.classList.remove('hidden');
                        }
                        semakFlashField(poskodEl, false);
                    });
            }
            poskodEl.addEventListener('input', function () {
                var digits = poskodEl.value.replace(/\D/g, '').slice(0, 5);
                poskodEl.value = digits;
                if (digits.length < 5) {
                    poskodLastSuccessCode = '';
                }
                semakClearPoskodHint();
                if (poskodLookupTimer) clearTimeout(poskodLookupTimer);
                if (digits.length === 5) {
                    poskodLookupTimer = setTimeout(function () {
                        semakLookupPostcode(digits);
                    }, 250);
                }
            });
        }

        var registerForm = document.getElementById('register-member-form');
        if (registerForm) {
            var section = document.getElementById('register-form-section');
            if (section) {
                section.classList.add('semak-form-enter');
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // ── Registration wizard: 3-step navigation ──────────────────────────
        var regSteps = [
            document.getElementById('regStep1'),
            document.getElementById('regStep2'),
            document.getElementById('regStep3'),
        ];
        var regCurrentStep = {{ $regInitialStep }};

        function setRegStepState(currentStep) {
            var activeMuted = 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500';
            var activeCurrent = 'bg-teal-600 text-white ring-4 ring-teal-100 dark:ring-teal-900/60 scale-105';
            var doneStyle = 'bg-emerald-600 text-white ring-2 ring-emerald-100 dark:ring-emerald-900/60';
            var labelMuted = 'text-slate-400 dark:text-slate-500';
            var labelActive = 'text-teal-700 dark:text-teal-400';
            var labelDone = 'text-emerald-700 dark:text-emerald-400';

            for (var i = 0; i < 3; i++) {
                var dot = document.getElementById('reg-step-dot-' + i);
                var label = document.getElementById('reg-step-label-' + i);
                if (!dot || !label) continue;
                var state = i < currentStep ? 'done' : (i === currentStep ? 'current' : 'muted');
                dot.textContent = state === 'done' ? '✓' : String(i + 1);
                var dotClass = state === 'done' ? doneStyle : (state === 'current' ? activeCurrent : activeMuted);
                var labelClass = state === 'done' ? labelDone : (state === 'current' ? labelActive : labelMuted);
                dot.className = 'reg-step w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 ' + dotClass;
                label.className = 'text-[10px] mt-2 font-semibold text-center leading-tight w-16 transition-colors ' + labelClass;
            }

            document.querySelectorAll('.reg-step-connector').forEach(function (line, idx) {
                var filled = idx < currentStep;
                line.classList.toggle('bg-teal-400', filled);
                line.classList.toggle('dark:bg-teal-600', filled);
                line.classList.toggle('bg-slate-200', !filled);
                line.classList.toggle('dark:bg-slate-600', !filled);
            });
        }

        function goToRegStep(idx) {
            regCurrentStep = idx;
            regSteps.forEach(function (el, i) { if (el) el.classList.toggle('hidden', i !== idx); });
            setRegStepState(idx);
            if (idx > 0 && regSteps[idx]) regSteps[idx].scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function validateRegStep1() {
            var ok = true;
            var kpInput = document.getElementById('reg_no_kp');
            if (kpInput && kpInput.value.replace(/\D/g, '').length !== 12) {
                ok = false;
                kpInput.classList.add('border-red-500');
                setTimeout(function () { kpInput.classList.remove('border-red-500'); }, 2000);
            }
            var namaInput = document.getElementById('nama');
            if (namaInput && !namaInput.value.trim()) {
                ok = false;
                namaInput.classList.add('border-red-500');
                setTimeout(function () { namaInput.classList.remove('border-red-500'); }, 2000);
            }
            if (registerForm && !registerForm.querySelector('input[name="jantina"]:checked')) {
                ok = false;
            }
            ['jabatan_id', 'jawatan_id'].forEach(function (fieldId) {
                var select = document.getElementById(fieldId);
                var wrap = select ? select.closest('.min-w-0') : null;
                if (select && !select.value) {
                    ok = false;
                    if (wrap) wrap.classList.add('semak-select2-invalid');
                } else if (wrap) {
                    wrap.classList.remove('semak-select2-invalid');
                }
            });
            if (!ok) showToast('Sila lengkapkan semua ruangan wajib di Langkah 1.', 'error');
            return ok;
        }

        var regGoStep2Btn = document.getElementById('regGoStep2');
        if (regGoStep2Btn) {
            regGoStep2Btn.addEventListener('click', function () {
                if (validateRegStep1()) goToRegStep(1);
            });
        }
        var regBackTo1Btn = document.getElementById('regBackTo1');
        if (regBackTo1Btn) regBackTo1Btn.addEventListener('click', function () { goToRegStep(0); });
        var regGoStep3Btn = document.getElementById('regGoStep3');
        if (regGoStep3Btn) regGoStep3Btn.addEventListener('click', function () { goToRegStep(2); });
        var regBackTo2Btn = document.getElementById('regBackTo2');
        if (regBackTo2Btn) regBackTo2Btn.addEventListener('click', function () { goToRegStep(1); });

        ['jabatan_id', 'jawatan_id'].forEach(function (fieldId) {
            var select = document.getElementById(fieldId);
            if (!select) return;
            select.addEventListener('change', function () {
                var wrap = select.closest('.min-w-0');
                if (wrap) wrap.classList.remove('semak-select2-invalid');
            });
        });

        if (registerForm) {
            registerForm.addEventListener('submit', function (e) {
                if (!validateRegStep1()) {
                    e.preventDefault();
                    goToRegStep(0);
                    return;
                }
                var buktiFile = document.getElementById('bukti_bayaran');
                if (buktiFile && (!buktiFile.files || !buktiFile.files[0])) {
                    e.preventDefault();
                    goToRegStep(2);
                    buktiFile.reportValidity();
                    return;
                }
                var btn = document.getElementById('register-submit-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="inline-flex items-center justify-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menghantar…</span>';
                }
            });
        }

        if (document.getElementById('reg-step-dot-0')) setRegStepState(regCurrentStep);

        // ── QR preview modal (shared with payment-account cards) ────────────
        var qrPreviewModal    = document.getElementById('qrPreviewModal');
        var qrPreviewImage    = document.getElementById('qrPreviewImage');
        var qrPreviewClose    = document.getElementById('qrPreviewClose');
        var qrPreviewBackdrop = document.getElementById('qrPreviewBackdrop');
        var qrPreviewSubtitle = document.getElementById('qrPreviewSubtitle');

        function openQrPreview(url, accountName) {
            if (!qrPreviewModal || !qrPreviewImage || !url) return;
            qrPreviewImage.src = url;
            qrPreviewImage.alt = accountName ? ('Kod QR ' + accountName) : 'Preview Kod QR';
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

        document.querySelectorAll('.js-qr-preview').forEach(function (btn) {
            btn.addEventListener('click', function () { openQrPreview(btn.dataset.qrUrl, btn.dataset.qrAccount || ''); });
        });
        if (qrPreviewClose) qrPreviewClose.addEventListener('click', closeQrPreview);
        if (qrPreviewBackdrop) qrPreviewBackdrop.addEventListener('click', closeQrPreview);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && qrPreviewModal && !qrPreviewModal.classList.contains('hidden')) closeQrPreview();
        });

        function fallbackCopy(text, onSuccess) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy');
                onSuccess();
            } catch (e) {
                showToast('Tidak dapat menyalin. Cuba semula.', 'error');
            }
            document.body.removeChild(ta);
        }

        document.querySelectorAll('.js-copy-account').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var number = btn.dataset.accountNumber;
                if (!number) return;
                var original = btn.innerHTML;
                var succeed = function () {
                    showToast('No. akaun disalin ke papan keratan.', 'success');
                    btn.innerHTML = '<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                    setTimeout(function () { btn.innerHTML = original; }, 1800);
                };
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(number).then(succeed).catch(function () { fallbackCopy(number, succeed); });
                } else {
                    fallbackCopy(number, succeed);
                }
            });
        });
    })();
</script>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #register-form-section .select2-container { width: 100% !important; }
    #register-form-section .select2-container--open {
        z-index: 10055 !important;
    }
    #register-form-section .select2-container--default .select2-selection--single {
        min-height: 48px;
        height: auto;
        padding: 0.625rem 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid rgb(203 213 225);
        background-color: #ffffff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    #register-form-section .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: rgb(15 23 42);
    }
    #register-form-section .select2-container--default .select2-selection--single .select2-selection__arrow { height: 46px; right: 10px; }
    #register-form-section .select2-container--default.select2-container--focus .select2-selection--single,
    #register-form-section .select2-container--default.select2-container--open .select2-selection--single {
        border-color: rgb(234 88 12);
        outline: 0;
        box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.12);
    }
    .select2-dropdown.semak-register-select2-dd {
        border-radius: 0.75rem;
        border: 1px solid rgb(203 213 225);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        z-index: 10060 !important;
    }
    .select2-dropdown.semak-register-select2-dd .select2-search--dropdown .select2-search__field {
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid rgb(203 213 225);
    }
    .select2-dropdown.semak-register-select2-dd .select2-search--dropdown .select2-search__field:focus {
        border-color: rgb(234 88 12);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
        outline: none;
    }
    .select2-dropdown.semak-register-select2-dd .select2-results__option { padding: 0.5rem 1rem; }
    .select2-dropdown.semak-register-select2-dd .select2-results__option--highlighted[aria-selected] {
        background-color: rgb(234 88 12);
        color: #fff;
    }
    .semak-select2-invalid .select2-container--default .select2-selection--single {
        border-color: rgb(239 68 68) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
    }
    .dark #register-form-section .select2-container--default .select2-selection--single {
        background-color: rgb(51 65 85) !important;
        border-color: rgb(71 85 105);
    }
    .dark #register-form-section .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: rgb(248 250 252) !important;
    }
    .dark .select2-dropdown.semak-register-select2-dd {
        background-color: rgb(30 41 59);
        border-color: rgb(71 85 105);
    }
    .dark .select2-dropdown.semak-register-select2-dd .select2-search--dropdown .select2-search__field {
        background-color: rgb(15 23 42);
        border-color: rgb(71 85 105);
        color: #fff;
    }
    .dark .select2-dropdown.semak-register-select2-dd .select2-results__option { color: rgb(226 232 240); }
    .dark .select2-dropdown.semak-register-select2-dd .select2-results__option[aria-selected="true"] { background-color: rgb(51 65 85); }
    .dark .semak-select2-invalid .select2-container--default .select2-selection--single {
        border-color: rgb(248 113 113) !important;
    }
</style>
@endpush

@push('scripts')
@if (! empty($turnstileEnabled))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    (function () {
        $(function () {
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }
            var $jab = $('#jabatan_id');
            var $jaw = $('#jawatan_id');
            if (!$jab.length || !$jaw.length) {
                return;
            }
            var lang = {
                noResults: function () { return 'Tiada hasil dijumpai'; },
                searching: function () { return 'Mencari...'; }
            };
            var base = {
                width: '100%',
                allowClear: true,
                dropdownParent: $('body'),
                dropdownCssClass: 'semak-register-select2-dd',
                language: lang
            };
            $jab.select2($.extend({}, base, {
                placeholder: $jab.data('placeholder') || 'Cari dan pilih jabatan...'
            }));
            $jaw.select2($.extend({}, base, {
                placeholder: $jaw.data('placeholder') || 'Cari dan pilih jawatan...'
            }));
        });
    })();
</script>
@endpush
