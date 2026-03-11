{{-- Step 4: Maklumat Pembayaran --}}
<div x-show="currentStep === 4"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 sm:translate-y-10 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 sm:-translate-y-10 scale-[0.96]"
     class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex-1 min-h-0 overflow-auto"
     style="display: none;">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Maklumat Pembayaran</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Isi jika ahli telah bayar. Centang di bawah untuk sahkan & aktifkan ahli serta-merta.</p>

    <div class="space-y-2">
        {{-- Section: Butiran bayaran --}}
        <section class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-800/40 p-6" aria-labelledby="section-bayaran">
            <h4 id="section-bayaran" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                Butiran bayaran
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label for="tahun_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Bayar <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                    <input type="number" name="tahun_bayar" id="tahun_bayar" value="{{ old('tahun_bayar', date('Y')) }}" min="2000" max="2100" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="space-y-2 lg:col-span-2">
                    <label for="yuran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Yuran <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                    <select name="yuran_id" id="yuran_id" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">-- Pilih Yuran --</option>
                        @foreach(\App\Models\Yuran::where('is_active', true)->orderBy('jenis_yuran')->get() as $yuran)
                            <option value="{{ $yuran->id }}" {{ old('yuran_id', '1') == $yuran->id ? 'selected' : '' }}>
                                {{ $yuran->jenis_yuran }} - RM {{ number_format($yuran->jumlah, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 sm:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Liputan tahun</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Pilihan; untuk bayaran mengejar (contoh: 2022–2024)</p>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" name="tahun_mula" id="tahun_mula" value="{{ old('tahun_mula') }}" min="2000" max="2100" placeholder="Tahun mula" aria-label="Tahun mula" class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 transition">
                        <span class="text-gray-400 shrink-0">hingga</span>
                        <input type="number" name="tahun_tamat" id="tahun_tamat" value="{{ old('tahun_tamat') }}" min="2000" max="2100" placeholder="Tahun tamat" aria-label="Tahun tamat" class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>
        </section>

        {{-- Section: Resit & rujukan --}}
        <section class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-800/40 p-6" aria-labelledby="section-resit">
            <h4 id="section-resit" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">2</span>
                Resit & rujukan
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="no_resit_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Resit / Rujukan <span class="text-red-500" x-show="approvePayment" x-cloak>*</span></label>
                    <input type="text" name="no_resit_transfer" id="no_resit_transfer" value="{{ old('no_resit_transfer') }}" placeholder="Rujukan bank atau no. resit" :required="approvePayment" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="space-y-2">
                    <label for="no_resit_sistem" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Resit Sistem</label>
                    <input type="text" name="no_resit_sistem" id="no_resit_sistem" value="{{ old('no_resit_sistem') }}" placeholder="Dijana oleh sistem jika kosong" class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
        </section>

        {{-- Section: Bukti bayaran --}}
        <section class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-800/40 p-6" aria-labelledby="section-bukti">
            <h4 id="section-bukti" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold">3</span>
                Bukti bayaran
            </h4>
            <div class="space-y-2">
                <label for="bukti_bayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Muat naik fail</label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">JPG, PNG atau PDF. Pilihan.</p>
                <div class="relative rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800/50 p-4 transition hover:border-indigo-400 dark:hover:border-indigo-500 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20">
                    <input type="file" name="bukti_bayaran" id="bukti_bayaran" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 file:font-medium file:cursor-pointer focus:outline-none">
                </div>
            </div>
        </section>

        {{-- Section: Tindakan (CTA) --}}
        <section class="rounded-xl border-2 border-indigo-200 dark:border-indigo-500/50 bg-indigo-50/70 dark:bg-indigo-900/25 p-6" aria-labelledby="section-tindakan">
            <h4 id="section-tindakan" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-200 dark:bg-indigo-800/60 text-indigo-700 dark:text-indigo-300 text-xs font-bold">✓</span>
                Tindakan
            </h4>
            <label class="flex cursor-pointer items-start gap-4 rounded-lg p-4 -m-2 transition hover:bg-indigo-100/50 dark:hover:bg-indigo-900/30 focus-within:bg-indigo-100/50 dark:focus-within:bg-indigo-900/30">
                <input type="checkbox" name="approve_immediately" value="1" x-model="approvePayment" {{ old('approve_immediately') ? 'checked' : '' }} class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0">
                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    Sahkan pembayaran sekarang & aktifkan ahli
                </span>
            </label>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2 ml-9">Centang jika bayaran telah diterima dan ahli hendak diaktifkan serta-merta.</p>
        </section>
    </div>
</div>
