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
                            Lengkapkan 3 bahagian di bawah. Yuran pendaftaran <strong>RM12.00</strong>.
                        </p>
                    </div>

                    <form id="register-member-form" action="{{ route('semak.register') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <input type="hidden" name="no_kp" value="{{ $prefillNoKp }}">

                        {{-- 1 Peribadi --}}
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50/50 dark:bg-slate-900/30 p-4 sm:p-6 space-y-4 sm:space-y-5 register-section">
                            <div class="flex items-center gap-3 pb-1 border-b border-slate-200/80 dark:border-slate-600/80">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-600 text-sm font-bold text-white shadow-sm">1</span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Maklumat peribadi</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Nama, jantina, jabatan &amp; jawatan</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="md:col-span-2">
                                    <label for="nama" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Nama penuh <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full min-h-[48px] rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15 @error('nama') border-red-500 @enderror">
                                    @error('nama')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
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
                                    @error('jawatan_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- 2 Perhubungan --}}
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50/50 dark:bg-slate-900/30 p-4 sm:p-6 space-y-4 sm:space-y-5 register-section">
                            <div class="flex items-center gap-3 pb-1 border-b border-slate-200/80 dark:border-slate-600/80">
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
                        </div>

                        {{-- 3 Pembayaran & dokumen --}}
                        <div class="rounded-2xl border-2 border-orange-200/90 dark:border-orange-900/50 bg-gradient-to-br from-orange-50/80 to-amber-50/40 dark:from-orange-950/25 dark:to-amber-950/15 p-4 sm:p-6 space-y-5 register-section">
                            <div class="flex items-center gap-3 pb-1 border-b border-orange-200/70 dark:border-orange-900/40">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white shadow-sm">3</span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Pembayaran &amp; dokumen</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">Bukti bayaran wajib — gambar profil pilihan</p>
                                </div>
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
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100">Gambar profil <span class="font-normal text-xs text-slate-600 dark:text-slate-400">(pilihan)</span></label>
                                <label for="gambar" id="reg-dropzone-gambar" class="group flex flex-col sm:flex-row items-center gap-4 w-full min-h-[7rem] cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-white/80 dark:bg-slate-800/60 px-4 py-4 transition-all hover:border-teal-400 hover:bg-teal-50/30 dark:hover:bg-teal-950/20 focus-within:ring-4 focus-within:ring-teal-500/15 @error('gambar') border-red-400 @enderror">
                                    <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg" class="sr-only">
                                    <div id="reg-gambar-placeholder" class="flex flex-1 flex-col items-center justify-center text-center gap-2 py-2">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Muat naik gambar (JPEG/PNG)</p>
                                    </div>
                                    <div id="reg-gambar-preview-wrap" class="hidden shrink-0">
                                        <img id="reg-gambar-preview" src="" alt="Pratonton gambar" class="h-24 w-24 rounded-2xl object-cover border border-slate-200 dark:border-slate-600 shadow-sm">
                                    </div>
                                    <p id="reg-gambar-filename" class="hidden text-xs text-slate-500 dark:text-slate-400 text-center sm:text-left break-all flex-1"></p>
                                </label>
                                @error('gambar')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="catatan" class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="3" class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-3 text-base sm:text-sm text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/15">{{ old('catatan') }}</textarea>
                            </div>
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

        var gambarInput = document.getElementById('gambar');
        var dzGambar = document.getElementById('reg-dropzone-gambar');
        if (gambarInput && dzGambar) {
            var gPh = document.getElementById('reg-gambar-placeholder');
            var gWrap = document.getElementById('reg-gambar-preview-wrap');
            var gImg = document.getElementById('reg-gambar-preview');
            var gFn = document.getElementById('reg-gambar-filename');
            gambarInput.addEventListener('change', function () {
                if (!gambarInput.files || !gambarInput.files[0]) return;
                var file = gambarInput.files[0];
                if (!/^image\/(jpeg|png)$/i.test(file.type)) {
                    showToast('Gambar mesti JPEG atau PNG.', 'error');
                    gambarInput.value = '';
                    return;
                }
                var url = URL.createObjectURL(file);
                if (gImg) gImg.src = url;
                gPh.classList.add('hidden');
                gWrap.classList.remove('hidden');
                if (gFn) {
                    gFn.textContent = file.name;
                    gFn.classList.remove('hidden');
                }
            });
            dzGambar.addEventListener('dragover', function (e) {
                e.preventDefault();
                dzGambar.classList.add('ring-4', 'ring-teal-400/40', 'border-teal-500');
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                dzGambar.addEventListener(ev, function (e) {
                    e.preventDefault();
                    dzGambar.classList.remove('ring-4', 'ring-teal-400/40', 'border-teal-500');
                });
            });
            dzGambar.addEventListener('drop', function (e) {
                e.preventDefault();
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    gambarInput.files = e.dataTransfer.files;
                    gambarInput.dispatchEvent(new Event('change', { bubbles: true }));
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
            registerForm.querySelectorAll('.register-section').forEach(function (sec) {
                sec.classList.add('semak-section-stagger');
            });
            registerForm.addEventListener('submit', function () {
                var btn = document.getElementById('register-submit-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="inline-flex items-center justify-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menghantar…</span>';
                }
            });
            var section = document.getElementById('register-form-section');
            if (section) {
                section.classList.add('semak-form-enter');
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
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
