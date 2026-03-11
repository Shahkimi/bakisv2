{{-- Step 3: Maklumat Alamat --}}
<div x-show="currentStep === 3"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 sm:translate-y-10 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 sm:-translate-y-10 scale-[0.96]"
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
