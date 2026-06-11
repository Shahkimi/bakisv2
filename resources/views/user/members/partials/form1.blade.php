{{-- Step 1: Maklumat Keanggotaan --}}
<div x-show="currentStep === 1"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 scale-[0.96]"
     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8 flex-1 min-h-0 overflow-auto">

    {{-- Section Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Maklumat Keanggotaan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Status keahlian, nombor ahli dan tarikh pendaftaran.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        {{-- ① STATUS AHLI — Auto Aktif Badge (hidden input) --}}
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Status Ahli
                <span class="ml-1 text-xs font-normal text-indigo-500 dark:text-indigo-400">(auto)</span>
            </label>
            {{-- Hidden input sends the aktif status ID --}}
            @php $aktifStatus = $statuses->firstWhere('code', 'aktif') ?? $statuses->first(); @endphp
            <input type="hidden" name="member_status_id" value="{{ $aktifStatus?->id }}">
            {{-- Visual badge — not an input --}}
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 shadow-sm">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                    {{ $aktifStatus?->name ?? 'Aktif' }}
                </span>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                <svg class="inline w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ditetapkan secara automatik oleh sistem.
            </p>
        </div>

        {{-- ② NO. AHLI — Auto-generate preview (read-only) --}}
        <div class="space-y-2">
            <label for="no_ahli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                No. Ahli
                <span class="ml-1 text-xs font-normal text-indigo-500 dark:text-indigo-400">(auto-jana)</span>
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                </div>
                <input type="text"
                       name="no_ahli"
                       id="no_ahli"
                       value="{{ old('no_ahli') }}"
                       placeholder="BKS-{{ date('y') }}????"
                       class="block w-full pl-9 pr-10 py-3 text-sm border border-gray-200 dark:border-gray-700
                              rounded-xl bg-gray-50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500
                              placeholder-gray-300 dark:placeholder-gray-600
                              focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                              transition cursor-not-allowed select-none
                              @error('no_ahli') border-red-500 ring-2 ring-red-200 @enderror"
                       readonly
                       tabindex="-1"
                       title="No. ahli akan dijana secara automatik setelah ahli disimpan.">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500">Dijana automatik: <code class="font-mono bg-gray-100 dark:bg-gray-700 px-1 rounded">BKS-YYXXXX</code></p>
            @error('no_ahli')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>

        {{-- ③ TARIKH DAFTAR — Pre-filled with today --}}
        <div class="space-y-2">
            <label for="tarikh_daftar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Tarikh Daftar
                <span class="text-red-500 ml-0.5">*</span>
                <span class="ml-1 text-xs font-normal text-indigo-500 dark:text-indigo-400">(auto-isi)</span>
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="date"
                       name="tarikh_daftar"
                       id="tarikh_daftar"
                       value="{{ old('tarikh_daftar', now()->toDateString()) }}"
                       required
                       class="block w-full pl-9 py-3 text-sm border border-gray-300 dark:border-gray-600
                              rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                              transition [color-scheme:light] dark:[color-scheme:dark]">
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Hari ini: <span class="font-medium text-gray-600 dark:text-gray-400">{{ now()->translatedFormat('d M Y') }}</span>
            </p>
        </div>

    </div>

    {{-- Info callout --}}
    <div class="mt-6 flex items-start gap-3 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-indigo-700 dark:text-indigo-300 leading-relaxed">
            <strong>Nota:</strong> Status ahli ditetapkan kepada <strong>Aktif</strong> secara automatik.
            No. Ahli akan dijana selepas rekod disimpan dalam format <code class="font-mono">BKS-YYXXXX</code>.
            Tarikh daftar telah diisi dengan tarikh hari ini — boleh diubah jika perlu.
        </p>
    </div>
</div>