@extends('layouts.app')

@section('title', 'Tambah Ahli Baru')

@section('content')
<div class="h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)] flex flex-col max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 overflow-hidden bg-gray-50 dark:bg-gray-900" x-data="{ currentStep: 1, totalSteps: 4, approvePayment: {{ old('approve_immediately') ? 'true' : 'false' }} }">
    <!-- Page Header (dynamic title per step) -->
    <div class="flex-shrink-0 pt-4 pb-2">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="currentStep === 1 ? 'Maklumat Keanggotaan' : (currentStep === 2 ? 'Maklumat Peribadi' : (currentStep === 3 ? 'Maklumat Alamat' : 'Maklumat Pembayaran'))"></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="currentStep === 1 ? 'Status ahli, no. ahli, tarikh daftar' : (currentStep === 2 ? 'Nama, no. KP, jabatan, jawatan' : (currentStep === 3 ? 'Alamat surat-menyurat' : 'Bayaran yuran keahlian'))"></p>
    </div>

    <!-- Step indicator: horizontal card layout -->
    <div class="flex-shrink-0 py-6 flex flex-col sm:flex-row justify-between items-stretch gap-3 w-full max-w-4xl mx-auto">
        <!-- Step 1: Maklumat Keanggotaan -->
        <div @click="currentStep = 1"
             :class="currentStep === 1 ? 'bg-indigo-600 text-white border-indigo-600 dark:border-indigo-600' : (currentStep > 1 ? 'bg-white dark:bg-gray-800 border-indigo-200 dark:border-indigo-800 text-gray-900 dark:text-white' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300')"
             class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-xl border-2 cursor-pointer transition-all flex-1 min-w-0">
            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center"
                 :class="currentStep === 1 ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-xs sm:text-sm">Keanggotaan</div>
                <div class="text-xs opacity-80 hidden sm:block">Status & No. Ahli</div>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </div>
        <!-- Step 2: Maklumat Peribadi -->
        <div @click="currentStep = 2"
             :class="currentStep === 2 ? 'bg-indigo-600 text-white border-indigo-600 dark:border-indigo-600' : (currentStep > 2 ? 'bg-white dark:bg-gray-800 border-indigo-200 dark:border-indigo-800 text-gray-900 dark:text-white' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300')"
             class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-xl border-2 cursor-pointer transition-all flex-1 min-w-0">
            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center"
                 :class="currentStep === 2 ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-xs sm:text-sm">Peribadi</div>
                <div class="text-xs opacity-80 hidden sm:block">Nama, No. KP, Jabatan</div>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </div>
        <!-- Step 3: Maklumat Alamat -->
        <div @click="currentStep = 3"
             :class="currentStep === 3 ? 'bg-indigo-600 text-white border-indigo-600 dark:border-indigo-600' : (currentStep > 3 ? 'bg-white dark:bg-gray-800 border-indigo-200 dark:border-indigo-800 text-gray-900 dark:text-white' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300')"
             class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-xl border-2 cursor-pointer transition-all flex-1 min-w-0">
            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center"
                 :class="currentStep === 3 ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-xs sm:text-sm">Alamat</div>
                <div class="text-xs opacity-80 hidden sm:block">Alamat surat-menyurat</div>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </div>
        <!-- Step 4: Maklumat Pembayaran -->
        <div @click="currentStep = 4"
             :class="currentStep === 4 ? 'bg-indigo-600 text-white border-indigo-600 dark:border-indigo-600' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300'"
             class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 rounded-xl border-2 cursor-pointer transition-all flex-1 min-w-0">
            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center"
                 :class="currentStep === 4 ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-xs sm:text-sm">Pembayaran</div>
                <div class="text-xs opacity-80 hidden sm:block">Bayaran yuran</div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="flex-shrink-0 mb-3 rounded-xl bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-3 text-xs shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xs font-semibold text-red-800 dark:text-red-200">Sila betulkan ralat berikut</h3>
                    <ul class="mt-0.5 text-xs text-red-700 dark:text-red-300 list-disc list-inside space-y-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" id="memberForm" class="flex-1 min-h-0 flex flex-col items-center">
        @csrf

        <div class="w-full max-w-4xl mx-auto flex-1 min-h-0 flex flex-col">
        <!-- Step 1: Maklumat Keanggotaan only -->
        <div x-show="currentStep === 1"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-x-6 scale-[0.98]"
             class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex-1 min-h-0 overflow-auto">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Maklumat Keanggotaan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Status keahlian, nombor ahli dan tarikh pendaftaran.</p>

            <div class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/80 dark:bg-gray-800/50 p-6 sm:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label for="member_status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Ahli <span class="text-red-500">*</span></label>
                        <select name="member_status_id" id="member_status_id" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}" {{ old('member_status_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="no_ahli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Ahli</label>
                        <input type="text" name="no_ahli" id="no_ahli" value="{{ old('no_ahli') }}" placeholder="Auto jika kosong" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('no_ahli') border-red-500 ring-2 ring-red-200 @enderror">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Biarkan kosong untuk auto-generate oleh sistem.</p>
                        @error('no_ahli')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label for="tarikh_daftar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarikh Daftar <span class="text-red-500">*</span></label>
                        <input type="date" name="tarikh_daftar" id="tarikh_daftar" value="{{ old('tarikh_daftar', date('Y-m-d')) }}" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition [color-scheme:light] dark:[color-scheme:dark]">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Maklumat Peribadi -->
        <div x-show="currentStep === 2"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-x-6 scale-[0.98]"
             class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex-1 min-h-0 overflow-auto"
             style="display: none;">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-6">Maklumat Peribadi</h3>
            <div class="space-y-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Ahli <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required placeholder="Nama penuh" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama') border-red-500 @enderror">
                    @error('nama')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="no_kp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Kad Pengenalan <span class="text-red-500">*</span></label>
                        <input type="text" name="no_kp" id="no_kp" value="{{ old('no_kp') }}" maxlength="12" pattern="[0-9]*" required placeholder="850123015678" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('no_kp') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan 12 digit (contoh: 850123015678) — akan diformatkan secara automatik</p>
                        @error('no_kp')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jawatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jawatan <span class="text-red-500">*</span></label>
                        <select name="jawatan_id" id="jawatan_id" required class="member-select2 block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">Pilih Jawatan</option>
                            @foreach($jawatans as $j)
                                <option value="{{ $j->id }}" {{ old('jawatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jawatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jantina <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 pt-2">
                            <label class="inline-flex items-center cursor-pointer text-sm text-gray-700 dark:text-gray-300"><input type="radio" name="jantina" value="L" {{ old('jantina', 'L') === 'L' ? 'checked' : '' }} required class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 rounded"> <span class="ml-2">Lelaki</span></label>
                            <label class="inline-flex items-center cursor-pointer text-sm text-gray-700 dark:text-gray-300"><input type="radio" name="jantina" value="P" {{ old('jantina') === 'P' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 rounded"> <span class="ml-2">Perempuan</span></label>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="no_tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telefon Pejabat</label>
                        <input type="text" name="no_tel" id="no_tel" value="{{ old('no_tel') }}" placeholder="04-xxx xxxx (pilihan)" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. HP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="012-3456789" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan / Unit / Wad <span class="text-red-500">*</span></label>
                    <select name="jabatan_id" id="jabatan_id" required class="member-select2 block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatans as $j)
                            <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar</label>
                    <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border file:border-gray-300 dark:file:border-gray-600 file:bg-white dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-300 file:font-medium file:cursor-pointer">
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="3" placeholder="Pilihan" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Step 3: Maklumat Alamat -->
        <div x-show="currentStep === 3"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-x-6 scale-[0.98]"
             class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex-1 min-h-0 overflow-auto"
             style="display: none;">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-6">Maklumat Alamat</h3>
            <div class="space-y-6">
                <div>
                    <label for="alamat1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat 1</label>
                    <input type="text" name="alamat1" id="alamat1" value="{{ old('alamat1') }}" placeholder="Jalan, bangunan, no. rumah" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label for="alamat2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat 2</label>
                    <input type="text" name="alamat2" id="alamat2" value="{{ old('alamat2') }}" placeholder="Taman, daerah (pilihan)" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="poskod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poskod</label>
                        <input type="text" name="poskod" id="poskod" value="{{ old('poskod') }}" placeholder="5 digit" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label for="negeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Negeri</label>
                        <input type="text" name="negeri" id="negeri" value="{{ old('negeri') }}" placeholder="Pilih Negeri" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="bandar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bandar</label>
                    <input type="text" name="bandar" id="bandar" value="{{ old('bandar') }}" placeholder="Bandar" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
        </div>

        <!-- Step 4: Maklumat Pembayaran -->
        <div x-show="currentStep === 4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-x-6 scale-[0.98]"
             class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex-1 min-h-0 overflow-auto"
             style="display: none;">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-6">Maklumat Pembayaran</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Isi jika ahli telah bayar. Centang di bawah untuk sahkan & aktifkan ahli serta-merta.</p>
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="tahun_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Bayar <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                        <input type="number" name="tahun_bayar" id="tahun_bayar" value="{{ old('tahun_bayar', date('Y')) }}" min="2000" max="2100" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label for="yuran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Yuran <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                        <select name="yuran_id" id="yuran_id" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Yuran --</option>
                            @foreach(\App\Models\Yuran::where('is_active', true)->orderBy('jenis_yuran')->get() as $yuran)
                                <option value="{{ $yuran->id }}" {{ old('yuran_id', '1') == $yuran->id ? 'selected' : '' }}>
                                    {{ $yuran->jenis_yuran }} - RM {{ number_format($yuran->jumlah, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Liputan tahun (pilihan; untuk bayaran mengejar)</p>
                        <div class="flex items-center gap-2">
                            <input type="number" name="tahun_mula" id="tahun_mula" value="{{ old('tahun_mula') }}" min="2000" max="2100" placeholder="Tahun mula" class="block w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-indigo-500">
                            <span class="text-gray-400">–</span>
                            <input type="number" name="tahun_tamat" id="tahun_tamat" value="{{ old('tahun_tamat') }}" min="2000" max="2100" placeholder="Tahun tamat" class="block w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="no_resit_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Resit / Rujukan <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                        <input type="text" name="no_resit_transfer" id="no_resit_transfer" value="{{ old('no_resit_transfer') }}" placeholder="Rujukan bank / resit" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label for="no_resit_sistem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Resit Sistem</label>
                        <input type="text" name="no_resit_sistem" id="no_resit_sistem" value="{{ old('no_resit_sistem') }}" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div>
                    <label for="bukti_bayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti bayaran</label>
                    <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border file:border-gray-300 dark:file:border-gray-600 file:bg-white dark:file:bg-gray-800 file:text-gray-700 dark:file:text-gray-300 file:font-medium file:cursor-pointer">
                </div>
                <div class="flex items-center pt-2">
                    <label class="inline-flex items-center cursor-pointer gap-2">
                        <input type="checkbox" name="approve_immediately" value="1" x-model="approvePayment" {{ old('approve_immediately') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sahkan pembayaran sekarang & aktifkan ahli</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Step navigation -->
        <div class="flex-shrink-0 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3 w-full">
            <div>
                <button type="button"
                        x-show="currentStep > 1"
                        x-cloak
                        @click="currentStep--"
                        class="inline-flex items-center py-2.5 px-5 text-sm font-medium rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-1 focus:ring-gray-400 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Previous
                </button>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.members.index') }}" class="inline-flex items-center py-2.5 px-5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-1 focus:ring-gray-400 transition">
                    Batal
                </a>
                <button type="button"
                        x-show="currentStep < totalSteps"
                        @click="currentStep++"
                        class="inline-flex items-center py-2.5 px-6 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                <button type="submit"
                        x-show="currentStep === totalSteps"
                        x-cloak
                        class="inline-flex items-center py-2.5 px-6 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Ahli
                </button>
            </div>
        </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single { height: 42px; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid rgb(209 213 219); }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5; padding-left: 0; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
    .select2-container--default.select2-container--focus .select2-selection--single { border-color: rgb(99 102 241); outline: 0; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25); }
    .select2-dropdown { border-radius: 0.5rem; border: 1px solid rgb(209 213 219); }
    .select2-container--default .select2-search--dropdown .select2-search__field { border-radius: 0.375rem; padding: 0.5rem 0.75rem; }
    .dark .select2-container--default .select2-selection--single { background-color: rgba(55, 65, 81, 0.5); border-color: rgb(75 85 99); }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered { color: rgb(243 244 246); }
    .dark .select2-dropdown { background-color: rgb(55 65 81); border-color: rgb(75 85 99); }
    .dark .select2-results__option { color: rgb(243 244 246); }
    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: rgb(99 102 241); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') return;
    var $ = jQuery;
    $('.member-select2').select2({
        width: '100%',
        placeholder: 'Cari...',
        allowClear: true,
        language: {
            noResults: function() { return 'Tiada hasil'; },
            searching: function() { return 'Mencari...'; },
            inputTooShort: function() { return 'Sila taip untuk cari'; }
        }
    });
});
</script>
@endpush
@endsection
