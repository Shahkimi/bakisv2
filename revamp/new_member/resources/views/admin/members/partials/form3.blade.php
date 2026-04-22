{{-- Step 3: Maklumat Alamat --}}
<div x-show="currentStep === 3"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 scale-[0.96]"
     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8 flex-1 min-h-0 overflow-auto"
     style="display: none;"
     x-data="{
         poskod: '{{ old('poskod') }}',
         bandar: '{{ old('bandar') }}',
         negeri: '{{ old('negeri') }}',
         poskodLoading: false,
         poskodError: '',
         lookupPostcode() {
             const code = this.poskod.replace(/\D/g,'');
             if (code.length !== 5) return;
             this.poskodLoading = true;
             this.poskodError = '';
             fetch('/api/postcode/' + code)
                 .then(r => r.ok ? r.json() : Promise.reject())
                 .then(data => {
                     if (data.bandar) this.bandar = data.bandar;
                     if (data.negeri) this.negeri = data.negeri;
                 })
                 .catch(() => { this.poskodError = 'Poskod tidak dijumpai.'; })
                 .finally(() => { this.poskodLoading = false; });
         }
     }">

    {{-- Section Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Maklumat Alamat</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Alamat kediaman ahli. Semua medan adalah pilihan.</p>
        </div>
    </div>

    <div class="space-y-5">
        {{-- Alamat 1 --}}
        <div class="space-y-2">
            <label for="alamat1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat 1</label>
            <input type="text" name="alamat1" id="alamat1" value="{{ old('alamat1') }}"
                   placeholder="No. rumah, jalan, bangunan"
                   class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                          bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                          shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        </div>

        {{-- Alamat 2 --}}
        <div class="space-y-2">
            <label for="alamat2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat 2</label>
            <input type="text" name="alamat2" id="alamat2" value="{{ old('alamat2') }}"
                   placeholder="Taman, kawasan (pilihan)"
                   class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                          bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                          shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        </div>

        {{-- Poskod + Bandar --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label for="poskod" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Poskod
                    <span x-show="poskodLoading" class="ml-1 text-xs text-indigo-400 animate-pulse">Mencari...</span>
                </label>
                <div class="relative">
                    <input type="text" name="poskod" id="poskod"
                           x-model="poskod"
                           @blur="lookupPostcode()"
                           @input="if(poskod.replace(/\D/g,'').length===5) lookupPostcode()"
                           maxlength="5"
                           placeholder="Cth: 05400"
                           class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                  bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                  shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-mono">
                    <div x-show="poskodLoading" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="animate-spin h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                <p x-show="poskodError" x-text="poskodError" class="text-xs text-amber-500"></p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Masukkan 5 digit — bandar & negeri diisi automatik.</p>
            </div>
            <div class="space-y-2">
                <label for="bandar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bandar</label>
                <input type="text" name="bandar" id="bandar"
                       x-model="bandar"
                       placeholder="Auto-isi dari poskod"
                       class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                              shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
        </div>

        {{-- Negeri --}}
        <div class="space-y-2">
            <label for="negeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Negeri</label>
            <select name="negeri" id="negeri"
                    x-model="negeri"
                    class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                           bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                           shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <option value="">— Pilih Negeri —</option>
                @foreach(['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu','Kuala Lumpur','Labuan','Putrajaya'] as $state)
                    <option value="{{ $state }}" {{ old('negeri') === $state ? 'selected' : '' }}>{{ $state }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>