@extends('layouts.app')

@section('title', 'Edit Ahli')

@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8" x-data="{ openSection: 'membership' }">
    <div class="w-full max-w-6xl flex flex-col md:flex-row gap-6 my-auto">
        {{-- Left Sidebar: Member Summary (Read-Only Display) --}}
        <div class="w-full md:w-80 lg:w-96 flex-shrink-0">
            <div class="md:sticky md:top-6 space-y-4">
                {{-- Member Name Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white text-center uppercase">{{ $member->nama }}</h2>

                    {{-- No. KP Badge --}}
                    <div class="flex justify-center mt-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-full text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            {{ $member->no_kp }}
                        </span>
                    </div>

                    {{-- Status & No. Ahli Badges --}}
                    <div class="flex flex-wrap justify-center gap-2 mt-3">
                        @if($member->memberStatus)
                            @php
                                $statusColors = [
                                    'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'tidak_aktif' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                    'meninggal' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $statusClass = $statusColors[$member->memberStatus->code] ?? $statusColors['tidak_aktif'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $member->memberStatus->name }}
                            </span>
                        @endif
                        @if($member->no_ahli)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-white dark:bg-gray-600">
                                {{ $member->no_ahli }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Jabatan Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Jabatan</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $member->jabatan?->nama_jabatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Unit Info Card --}}
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">UNIT</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $member->jawatan?->nama_jawatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tarikh Daftar Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wide">TARIKH DAFTAR</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $member->tarikh_daftar?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Content: Form Sections (Editable) --}}
        <div class="flex-1 min-w-0">
            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-400 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Sila betulkan ralat berikut</h3>
                            <ul class="mt-1 text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form id="member-edit-form" action="{{ route('admin.members.update', $member) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Section 1: Maklumat Keanggotaan --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300"
                     :class="openSection === 'membership' ? 'ring-2 ring-blue-500/20 shadow-lg' : 'shadow-sm'">
                    <button type="button"
                            @click="openSection = openSection === 'membership' ? '' : 'membership'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left transition-all duration-300 ease-out"
                            :class="openSection === 'membership' 
                                ? 'bg-blue-600 text-white shadow-md' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                        <h3 class="text-sm font-semibold uppercase tracking-wide">MAKLUMAT KEANGGOTAAN</h3>
                        <svg class="w-5 h-5 transition-transform duration-300 ease-out" :class="openSection === 'membership' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openSection === 'membership'" x-collapse.duration.300ms>
                        <div class="p-5 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                <div>
                                    <label for="member_status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Ahli <span class="text-red-500">*</span></label>
                                    <select name="member_status_id" id="member_status_id" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        @foreach($statuses as $s)
                                            <option value="{{ $s->id }}" {{ old('member_status_id', $member->member_status_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="no_ahli" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Ahli</label>
                                    <input type="text" name="no_ahli" id="no_ahli" value="{{ old('no_ahli', $member->no_ahli) }}" placeholder="Auto jika kosong" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                                <div>
                                    <label for="tarikh_daftar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tarikh Daftar <span class="text-red-500">*</span></label>
                                    <input type="date" name="tarikh_daftar" id="tarikh_daftar" value="{{ old('tarikh_daftar', $member->tarikh_daftar?->format('Y-m-d')) }}" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>

                            {{-- Jabatan with Select2 Search --}}
                            <div>
                                <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan <span class="text-red-500">*</span></label>
                                <select name="jabatan_id" id="jabatan_id" required class="select2-field w-full">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach($jabatans as $j)
                                        <option value="{{ $j->id }}" {{ old('jabatan_id', $member->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                                @if($member->jabatan)
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Jabatan semasa: <span class="font-medium text-blue-600 dark:text-blue-400">{{ $member->jabatan->nama_jabatan }}</span>
                                    </p>
                                @endif
                            </div>

                            {{-- Jawatan with Select2 Search --}}
                            <div>
                                <label for="jawatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jawatan <span class="text-red-500">*</span></label>
                                <select name="jawatan_id" id="jawatan_id" required class="select2-field w-full">
                                    <option value="">Pilih Jawatan</option>
                                    @foreach($jawatans as $j)
                                        <option value="{{ $j->id }}" {{ old('jawatan_id', $member->jawatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jawatan }}</option>
                                    @endforeach
                                </select>
                                @if($member->jawatan)
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Jawatan semasa: <span class="font-medium text-green-600 dark:text-green-400">{{ $member->jawatan->nama_jawatan }}</span>
                                    </p>
                                @endif
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Maklumat Peribadi --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300"
                     :class="openSection === 'personal' ? 'ring-2 ring-indigo-500/20 shadow-lg' : 'shadow-sm'">
                    <button type="button"
                            @click="openSection = openSection === 'personal' ? '' : 'personal'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left transition-all duration-300 ease-out"
                            :class="openSection === 'personal' 
                                ? 'bg-indigo-600 text-white shadow-md' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                        <h3 class="text-sm font-semibold uppercase tracking-wide" :class="openSection === 'personal' ? 'text-white' : 'text-indigo-600 dark:text-indigo-400'">MAKLUMAT PERIBADI</h3>
                        <svg class="w-5 h-5 transition-transform duration-300 ease-out" :class="openSection === 'personal' ? 'rotate-180 text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openSection === 'personal'" x-collapse.duration.300ms>
                        <div class="p-5 border-t border-gray-100 dark:border-gray-700 space-y-5">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Penuh <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $member->nama) }}" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="no_kp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. KP <span class="text-red-500">*</span></label>
                                    <input type="text" name="no_kp" id="no_kp" value="{{ old('no_kp', $member->no_kp) }}" maxlength="12" required placeholder="850123015678" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan 12 digit (contoh: 850123015678)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jantina <span class="text-red-500">*</span></label>
                                    <div class="flex gap-6 pt-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="jantina" value="L" {{ old('jantina', $member->jantina) === 'L' ? 'checked' : '' }} required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Lelaki</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="jantina" value="P" {{ old('jantina', $member->jantina) === 'P' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" placeholder="contoh@email.com" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                                <div>
                                    <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. H/P <span class="text-red-500">*</span></label>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $member->no_hp) }}" placeholder="012-3456789" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>

                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="3" placeholder="Catatan tambahan (opsional)" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none">{{ old('catatan', $member->catatan) }}</textarea>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Maklumat Alamat --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300"
                     :class="openSection === 'address' ? 'ring-2 ring-emerald-500/20 shadow-lg' : 'shadow-sm'">
                    <button type="button"
                            @click="openSection = openSection === 'address' ? '' : 'address'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left transition-all duration-300 ease-out"
                            :class="openSection === 'address' 
                                ? 'bg-emerald-600 text-white shadow-md' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                        <h3 class="text-sm font-semibold uppercase tracking-wide" :class="openSection === 'address' ? 'text-white' : 'text-emerald-600 dark:text-emerald-400'">MAKLUMAT ALAMAT</h3>
                        <svg class="w-5 h-5 transition-transform duration-300 ease-out" :class="openSection === 'address' ? 'rotate-180 text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openSection === 'address'" x-collapse.duration.300ms>
                        <div class="p-5 border-t border-gray-100 dark:border-gray-700 space-y-5">
                            <div>
                                <label for="alamat1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat <span class="text-red-500">*</span></label>
                                <input type="text" name="alamat1" id="alamat1" value="{{ old('alamat1', $member->alamat1) }}" placeholder="No. rumah, jalan" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition mb-3">
                                <input type="text" name="alamat2" id="alamat2" value="{{ old('alamat2', $member->alamat2) }}" placeholder="Taman, kampung (opsional)" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="poskod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poskod <span class="text-red-500">*</span></label>
                                    <input type="text" name="poskod" id="poskod" value="{{ old('poskod', $member->poskod) }}" maxlength="5" placeholder="Masukkan 5 digit poskod" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Bandar dan negeri akan diisi secara automatik
                                    </p>
                                </div>
                                <div>
                                    <label for="bandar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bandar</label>
                                    <input type="text" name="bandar" id="bandar" value="{{ old('bandar', $member->bandar) }}" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="negeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Negeri <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="negeri" id="negeri" required class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none pr-10">
                                            <option value="">Pilih Negeri</option>
                                            @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 'Wilayah Persekutuan Putrajaya'] as $negeri)
                                                <option value="{{ $negeri }}" {{ old('negeri', $member->negeri) == $negeri ? 'selected' : '' }}>{{ $negeri }}</option>
                                            @endforeach
                                        </select>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label for="no_tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telefon</label>
                                    <input type="text" name="no_tel" id="no_tel" value="{{ old('no_tel', $member->no_tel) }}" placeholder="Opsional" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Sejarah Pembayaran --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300"
                     :class="openSection === 'payment' ? 'ring-2 ring-purple-500/20 shadow-lg' : 'shadow-sm'">
                    <button type="button"
                            @click="openSection = openSection === 'payment' ? '' : 'payment'"
                            class="sticky top-0 z-10 w-full flex items-center justify-between px-5 py-4 text-left transition-all duration-300 ease-out rounded-t-xl"
                            :class="openSection === 'payment' 
                                ? 'bg-purple-600 text-white shadow-md' 
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                        <h3 class="text-sm font-semibold uppercase tracking-wide flex-1 min-w-0"
                            :class="openSection === 'payment' ? '!text-white' : 'text-purple-600 dark:text-purple-400'">SEJARAH PEMBAYARAN</h3>
                        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-out" :class="openSection === 'payment' ? 'rotate-180' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openSection === 'payment'" x-collapse.duration.300ms>
                        <div class="p-5 border-t border-gray-100 dark:border-gray-700 space-y-6">
                            {{-- Sejarah Yuran Mengikut Tahun --}}
                            <div>
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Sejarah Yuran Mengikut Tahun</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tahun</th>
                                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Butiran</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach($paymentHistoryByYear as $row)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white">{{ $row['year'] }}</td>
                                                    <td class="px-4 py-2.5">
                                                        @if($row['status'] === 'paid')
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Bayar</span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Tidak bayar</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">{{ $row['coverage_label'] ?: '–' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($member->payments->isEmpty())
                                {{-- Empty State --}}
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Tiada Rekod Pembayaran</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ahli ini belum mempunyai sebarang rekod pembayaran.</p>
                                </div>
                            @else
                                {{-- Payment History Table --}}
                                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-purple-50 dark:bg-purple-900/20">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wider">Tahun</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wider">Jenis Pembayaran</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach($member->payments as $payment)
                                                @php
                                                    $start = $payment->tahun_mula ?? $payment->tahun_bayar;
                                                    $end = $payment->tahun_tamat ?? $payment->tahun_bayar;
                                                    $yearLabel = $start === $end ? (string) $payment->tahun_bayar : $payment->tahun_bayar . ' (liputan ' . $start . '–' . $end . ')';
                                                @endphp
                                                <tr class="hover:bg-purple-50/50 dark:hover:bg-purple-900/10 transition-colors duration-150">
                                                    <td class="px-4 py-3.5 text-sm font-semibold text-gray-900 dark:text-white">{{ $yearLabel }}</td>
                                                    <td class="px-4 py-3.5 text-sm font-medium text-gray-900 dark:text-white">
                                                        <span class="inline-flex items-center gap-1">
                                                            <span class="text-gray-500 dark:text-gray-400 text-xs">RM</span>
                                                            {{ number_format((float) ($payment->jumlah ?? 0), 2) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3.5">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ match ($payment->jenis ?? '') { 'pendaftaran_baru' => 'Pendaftaran Baru', 'pembaharuan' => 'Pembaharuan', default => 'N/A' } }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3.5">
                                                        @if($payment->status === 'approved')
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                                Disahkan
                                                            </span>
                                                        @elseif($payment->status === 'pending')
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                                Menunggu
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                                Ditolak
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                {{-- Summary Footer --}}
                                <div class="mt-4 flex items-center justify-between px-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Jumlah rekod: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $member->payments->count() }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Jumlah keseluruhan: <span class="font-semibold text-purple-600 dark:text-purple-400">RM {{ number_format($member->payments->sum('jumlah'), 2) }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3 justify-end pt-2">
                    <a href="{{ route('admin.members.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Kemaskini
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
[x-cloak] { display: none !important; }

/* Select2 Custom Styling to match Tailwind */
.select2-container--default .select2-selection--single {
    height: 48px;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background-color: #fff;
    font-size: 0.875rem;
}
.dark .select2-container--default .select2-selection--single {
    background-color: #1f2937;
    border-color: #4b5563;
    color: #fff;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 30px;
    color: #111827;
    padding-left: 0;
}
.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #fff;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px;
    right: 8px;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #9ca3af;
}
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    outline: none;
}
.select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
.dark .select2-dropdown {
    background-color: #1f2937;
    border-color: #4b5563;
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 8px 12px;
    font-size: 0.875rem;
}
.dark .select2-container--default .select2-search--dropdown .select2-search__field {
    background-color: #374151;
    border-color: #4b5563;
    color: #fff;
}
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #3b82f6;
    color: #fff;
}
.select2-container--default .select2-results__option--selected {
    background-color: #dbeafe;
}
.dark .select2-container--default .select2-results__option--selected {
    background-color: #1e3a5f;
}
.dark .select2-results__option {
    color: #d1d5db;
}
.select2-results__option {
    padding: 10px 12px;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // AJAX form submit with SweetAlert success animation
    const form = document.getElementById('member-edit-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...</span>';
            }
            const formData = new FormData(form);
            const url = form.getAttribute('action');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            })
            .then(function(res) {
                return res.json().then(function(data) {
                    return { ok: res.ok, status: res.status, data: data };
                }).catch(function() {
                    return { ok: res.ok, status: res.status, data: {} };
                });
            })
            .then(function(result) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                if (result.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya',
                        text: result.data.message || 'Ahli berjaya dikemaskini.',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(function() {
                        if (result.data.redirect) {
                            window.location.href = result.data.redirect;
                        }
                    });
                } else {
                    const msg = (result.data && result.data.message) || (result.data && result.data.errors && Object.values(result.data.errors).flat().join(' ')) || 'Ralat berlaku. Sila cuba lagi.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat',
                        text: msg,
                    });
                }
            })
            .catch(function() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: 'Ralat rangkaian. Sila cuba lagi.',
                });
            });
            return false;
        });
    }

    // Initialize Select2 for Jabatan
    $('#jabatan_id').select2({
        placeholder: 'Cari dan pilih Jabatan...',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: false,
        language: {
            noResults: function() {
                return "Tiada hasil dijumpai";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Initialize Select2 for Jawatan
    $('#jawatan_id').select2({
        placeholder: 'Cari dan pilih Jawatan...',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: false,
        language: {
            noResults: function() {
                return "Tiada hasil dijumpai";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Handle Alpine.js accordion - reinitialize Select2 when section opens
    document.addEventListener('alpine:initialized', () => {
        // Watch for section changes and reinitialize Select2 if needed
    });

    // Postcode autocomplete functionality
    $('#poskod').on('input', function() {
        const postcode = $(this).val().trim();
        
        // Only trigger when exactly 5 digits are entered
        if (postcode.length === 5 && /^\d{5}$/.test(postcode)) {
            // Add loading indicator
            $(this).addClass('bg-blue-50 dark:bg-blue-900/20');
            
            // Fetch postcode data
            $.ajax({
                url: `/api/postcode/${postcode}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Update Bandar field
                        $('#bandar').val(response.city);
                        $('#bandar').addClass('ring-2 ring-green-500/50');
                        
                        // Update Negeri dropdown
                        $('#negeri').val(response.state);
                        $('#negeri').addClass('ring-2 ring-green-500/50');
                        
                        // Show success feedback
                        $('#poskod').removeClass('bg-blue-50 dark:bg-blue-900/20')
                                   .addClass('ring-2 ring-green-500/50');
                        
                        // Remove success highlight after 2 seconds
                        setTimeout(() => {
                            $('#bandar').removeClass('ring-2 ring-green-500/50');
                            $('#negeri').removeClass('ring-2 ring-green-500/50');
                            $('#poskod').removeClass('ring-2 ring-green-500/50');
                        }, 2000);
                    } else {
                        $('#poskod').removeClass('bg-blue-50 dark:bg-blue-900/20')
                                   .addClass('ring-2 ring-red-500/50');
                        setTimeout(() => {
                            $('#poskod').removeClass('ring-2 ring-red-500/50');
                        }, 2000);
                    }
                },
                error: function() {
                    $('#poskod').removeClass('bg-blue-50 dark:bg-blue-900/20')
                               .addClass('ring-2 ring-red-500/50');
                    setTimeout(() => {
                        $('#poskod').removeClass('ring-2 ring-red-500/50');
                    }, 2000);
                }
            });
        }
    });

    // Clear highlights when user manually changes fields
    $('#bandar, #negeri').on('change', function() {
        $(this).removeClass('ring-2 ring-green-500/50');
    });
});
</script>
@endpush
