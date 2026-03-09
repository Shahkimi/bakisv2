@extends('layouts.app')

@section('title', 'Semak Status Ahli')

@section('content')
@php
    $prefillNoKp = old('no_kp', $prefillNoKp ?? session('no_kp', ''));
    $showRegisterForm = (bool) (($showRegisterForm ?? false) || old('nama') || old('no_resit_transfer'));
    $lookupAlert = session('lookup_alert');
@endphp
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-stone-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6">
        <section class="relative overflow-hidden rounded-3xl border border-orange-200/70 dark:border-gray-700 bg-white/95 dark:bg-gray-800/90 p-6 sm:p-8 shadow-xl">
            <div class="absolute -top-24 -right-16 h-48 w-48 rounded-full bg-orange-200/60 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-10 h-44 w-44 rounded-full bg-teal-200/50 blur-3xl"></div>
            <div class="relative">
                <p class="inline-flex items-center rounded-full border border-orange-200 bg-orange-50 px-3 py-1 text-xs font-semibold tracking-wide text-orange-700">
                    Portal Semakan BAKIS
                </p>
                <h1 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Semak Status Keahlian Anda</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-300 max-w-3xl">
                    Gunakan No. KP 12 digit untuk semakan segera. Jika rekod tidak ditemui, anda boleh terus daftar ahli baharu pada halaman ini.
                </p>
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-900/50 p-3">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Langkah 1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">Masukkan No. KP</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-900/50 p-3">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Langkah 2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">Semak Status</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-900/50 p-3">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Langkah 3</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">Daftar Jika Tiada Rekod</p>
                    </div>
                </div>
            </div>
        </section>

        @if($lookupAlert)
            @php
                $alertType = $lookupAlert['type'] ?? 'info';
                $alertClass = match($alertType) {
                    'warning' => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-100',
                    'error' => 'border-red-200 bg-red-50 text-red-900 dark:border-red-800 dark:bg-red-900/20 dark:text-red-100',
                    default => 'border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-100',
                };
            @endphp
            <section class="rounded-2xl border {{ $alertClass }} p-4 sm:p-5 shadow-sm">
                <p class="font-semibold">{{ $lookupAlert['title'] ?? 'Makluman' }}</p>
                @if(!empty($lookupAlert['message']))
                    <p class="mt-1 text-sm opacity-90">{{ $lookupAlert['message'] }}</p>
                @endif
            </section>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <section class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-lg">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Semak No. KP</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pastikan No. KP dimasukkan tanpa simbol dan ruang.</p>

                <form action="{{ route('semak.check') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="no_kp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. KP (12 digit)</label>
                        <input
                            type="text"
                            name="no_kp"
                            id="no_kp"
                            value="{{ $prefillNoKp }}"
                            maxlength="12"
                            pattern="[0-9]{12}"
                            inputmode="numeric"
                            autocomplete="off"
                            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('no_kp') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                            placeholder="Contoh: 900101011234"
                            required
                            autofocus
                        >
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tip: Sistem hanya menerima nombor (0-9).</p>
                        @error('no_kp')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500/30">
                        Semak Status
                    </button>
                </form>

                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="/login" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-600 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Login
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-600 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Kembali ke Laman Utama
                    </a>
                </div>
            </section>

            <section id="register-form-section" class="lg:col-span-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-lg">
                @if($showRegisterForm)
                    <div class="mb-5">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Ahli Baharu</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            No. KP <span class="font-semibold text-orange-700 dark:text-orange-300">{{ $prefillNoKp ?: '-' }}</span> tidak ditemui.
                            Lengkapkan borang di bawah untuk pendaftaran (yuran RM12.00).
                        </p>
                    </div>

                    <form id="register-member-form" action="{{ route('semak.register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="no_kp" value="{{ $prefillNoKp }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Penuh *</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('nama') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                @error('nama')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jantina *</label>
                                <div class="flex gap-4 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                    <label class="inline-flex items-center"><input type="radio" name="jantina" value="L" {{ old('jantina') === 'L' ? 'checked' : '' }} required class="h-4 w-4 border-gray-300 text-orange-600 focus:ring-orange-500"> <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Lelaki</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="jantina" value="P" {{ old('jantina') === 'P' ? 'checked' : '' }} class="h-4 w-4 border-gray-300 text-orange-600 focus:ring-orange-500"> <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perempuan</span></label>
                                </div>
                                @error('jantina')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                @error('email')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan *</label>
                                <select name="jabatan_id" id="jabatan_id" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('jabatan_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($jabatans as $j)
                                        <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="jawatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jawatan *</label>
                                <select name="jawatan_id" id="jawatan_id" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('jawatan_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Jawatan --</option>
                                    @foreach($jawatans as $j)
                                        <option value="{{ $j->id }}" {{ old('jawatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jawatan }}</option>
                                    @endforeach
                                </select>
                                @error('jawatan_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Maklumat Perhubungan</h3>
                            <div>
                                <label for="alamat1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat 1</label>
                                <input type="text" name="alamat1" id="alamat1" value="{{ old('alamat1') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                @error('alamat1')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="alamat2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat 2</label>
                                <input type="text" name="alamat2" id="alamat2" value="{{ old('alamat2') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="poskod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poskod</label>
                                    <input type="text" name="poskod" id="poskod" value="{{ old('poskod') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                                <div>
                                    <label for="bandar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bandar</label>
                                    <input type="text" name="bandar" id="bandar" value="{{ old('bandar') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                                <div>
                                    <label for="negeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Negeri</label>
                                    <input type="text" name="negeri" id="negeri" value="{{ old('negeri') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="no_tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telefon</label>
                                    <input type="text" name="no_tel" id="no_tel" value="{{ old('no_tel') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                                <div>
                                    <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. HP</label>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-orange-200 dark:border-orange-800/50 bg-orange-50/70 dark:bg-orange-900/10 p-4 space-y-4">
                            <h3 class="text-sm font-semibold text-orange-900 dark:text-orange-200">Pembayaran Pendaftaran (RM12.00)</h3>
                            <p class="text-xs text-orange-800/90 dark:text-orange-300">Sila masukkan nombor rujukan pembayaran dan muat naik bukti bayaran sebelum hantar.</p>
                            <div>
                                <label for="no_resit_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Rujukan / Resit Bank *</label>
                                <input type="text" name="no_resit_transfer" id="no_resit_transfer" value="{{ old('no_resit_transfer') }}" required class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 @error('no_resit_transfer') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                @error('no_resit_transfer')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="bukti_bayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Bayaran (JPG, PNG, PDF) *</label>
                                <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-orange-100 dark:file:bg-orange-900/30 file:px-4 file:py-2.5 file:font-medium file:text-orange-700 dark:file:text-orange-300">
                                @error('bukti_bayaran')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar (Pilihan)</label>
                                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 dark:file:bg-gray-700 file:px-4 file:py-2.5 file:font-medium file:text-gray-700 dark:file:text-gray-200">
                                @error('gambar')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="2" class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-3 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-teal-600 to-teal-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 transition hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                            Hantar Pendaftaran
                        </button>
                    </form>
                @else
                    <div class="h-full min-h-[240px] flex items-center justify-center rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/70 dark:bg-gray-900/30 p-6 text-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Borang pendaftaran akan dipaparkan jika No. KP tidak ditemui.</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Mulakan semakan di panel kiri terlebih dahulu.</p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<style>
    @keyframes formRise {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-enter {
        animation: formRise 0.45s ease-out both;
    }

    .focus-pop {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .focus-pop:focus {
        transform: translateY(-1px);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
    }

    .section-pulse {
        animation: formRise 0.5s ease-out both;
    }
</style>

<script>
    (function () {
        const noKpInput = document.getElementById('no_kp');
        if (noKpInput) {
            noKpInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 12);
            });
        }

        const receiptInput = document.getElementById('no_resit_transfer');
        if (receiptInput) {
            receiptInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        }

        const registerForm = document.getElementById('register-member-form');
        if (registerForm) {
            const formElements = registerForm.querySelectorAll('input, select, textarea, button');
            formElements.forEach(function (el, index) {
                el.classList.add('focus-pop', 'form-enter');
                el.style.animationDelay = (index * 35) + 'ms';
            });

            const section = document.getElementById('register-form-section');
            if (section) {
                section.classList.add('section-pulse');
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    })();
</script>
@endsection
