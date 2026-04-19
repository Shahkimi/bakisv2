{{-- Step 1: Maklumat Keanggotaan --}}
<div x-show="currentStep === 1"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 sm:translate-y-10 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 sm:-translate-y-10 scale-[0.96]"
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
