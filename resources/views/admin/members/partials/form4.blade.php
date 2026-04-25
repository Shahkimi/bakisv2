{{-- Step 4: Maklumat Pembayaran --}}
<div x-show="currentStep === 4"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 scale-[0.96]"
     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8 flex-1 min-h-0 overflow-auto"
     style="display: none;">

    @php
        $pendaftaranYuran = $yurans?->first(fn ($yuran) => (float) $yuran->jumlah === 12.0);
        $pembaharuan10Yuran = $yurans?->first(fn ($yuran) => (float) $yuran->jumlah === 10.0 && (int) $yuran->tempoh_tahun === 1);
        $pendaftaranId = $pendaftaranYuran?->id ?? 1;
        $pendaftaranJumlah = (float) ($pendaftaranYuran?->jumlah ?? 12.00);
        $advanceJumlah = (float) ($pembaharuan10Yuran?->jumlah ?? 10.00);
        $oldPaymentCombo = old('payment_combo', 'registration_only');
        $advanceTotal = $pendaftaranJumlah + $advanceJumlah;
    @endphp

    {{-- Section Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Maklumat Pembayaran</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Isi jika ahli telah membuat pembayaran. Medan ini adalah pilihan.</p>
        </div>
    </div>

    {{-- Payment Toggle Switch --}}
    <div x-data="{
            hasPayment: {{ old('yuran_id') ? 'true' : 'false' }},
            approvePayment: {{ old('approve_immediately') ? 'true' : 'false' }},
            manualOverride: {{ (!empty(old('tahun_mula')) || !empty(old('tahun_tamat'))) ? 'true' : 'false' }},
            computeYears() {
                if (this.manualOverride) return;
                const combo = document.getElementById('payment_combo')?.value ?? 'registration_only';
                const tahunBayar = parseInt(document.getElementById('tahun_bayar')?.value ?? '{{ date('Y') }}', 10);
                if (isNaN(tahunBayar)) return;
                const mula = document.getElementById('tahun_mula');
                const tamat = document.getElementById('tahun_tamat');
                if (!mula || !tamat) return;
                if (combo === 'registration_advance_next_year') {
                    mula.value = tahunBayar; tamat.value = tahunBayar + 1;
                } else {
                    mula.value = tahunBayar; tamat.value = tahunBayar;
                }
            }
         }">

        {{-- Toggle: Ada Bayaran? --}}
        <label class="flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-600
                       bg-gray-50 dark:bg-gray-800/60 cursor-pointer hover:border-indigo-300 transition mb-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Rekod Pembayaran Sekarang?</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Aktifkan jika ahli telah membuat bayaran.</p>
                </div>
            </div>
            <button type="button" @click="hasPayment = !hasPayment"
                    :class="hasPayment ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700'"
                    class="relative inline-flex h-6 w-11 shrink-0 rounded-full border-2 border-transparent
                           transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span :class="hasPayment ? 'translate-x-5' : 'translate-x-0'"
                      class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
            </button>
        </label>

        {{-- Payment Form Panel --}}
        <div x-show="hasPayment"
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">

            {{-- Section 1: Butiran Bayaran --}}
            <section class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-800/40 p-5">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                                  text-indigo-600 dark:text-indigo-400 text-xs font-bold">1</span>
                    Butiran Bayaran
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label for="tahun_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Bayar <span class="text-red-500" x-show="hasPayment">*</span>
                        </label>
                        <input type="number" name="tahun_bayar" id="tahun_bayar"
                               value="{{ old('tahun_bayar', date('Y')) }}" min="2000" max="2100"
                               :required="hasPayment"
                               @input="computeYears()"
                               class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                      bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                                      shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div class="space-y-2 sm:col-span-1 lg:col-span-2">
                        <label for="yuran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Yuran <span class="text-red-500" x-show="hasPayment">*</span>
                        </label>
                        <input type="hidden" name="payment_combo" id="payment_combo" value="{{ $oldPaymentCombo }}">
                        <select name="yuran_id" id="yuran_id"
                                :required="hasPayment"
                                @change="document.getElementById('payment_combo').value = $event.target.selectedOptions[0]?.dataset?.paymentCombo ?? 'registration_only'; manualOverride = false; computeYears();"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                                       shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">— Pilih Yuran —</option>
                            <option value="{{ $pendaftaranId }}"
                                    data-payment-combo="registration_only"
                                    {{ $oldPaymentCombo === 'registration_only' ? 'selected' : '' }}>
                                Pendaftaran Keahlian — RM {{ number_format($pendaftaranJumlah, 2) }}
                            </option>
                            <option value="{{ $pendaftaranId }}"
                                    data-payment-combo="registration_advance_next_year"
                                    {{ $oldPaymentCombo === 'registration_advance_next_year' ? 'selected' : '' }}>
                                Pendaftaran + 1 Tahun Advance — RM {{ number_format($advanceTotal, 2) }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2 sm:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Liputan Keahlian</label>
                        <div class="flex flex-wrap items-center gap-3">
                            <input type="number" name="tahun_mula" id="tahun_mula"
                                   value="{{ old('tahun_mula') }}" min="2000" max="2100"
                                   placeholder="Tahun mula"
                                   @input="manualOverride = true"
                                   class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                          bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                          focus:ring-2 focus:ring-indigo-500 transition">
                            <span class="text-gray-400 text-sm shrink-0">hingga</span>
                            <input type="number" name="tahun_tamat" id="tahun_tamat"
                                   value="{{ old('tahun_tamat') }}" min="2000" max="2100"
                                   placeholder="Tahun tamat"
                                   @input="manualOverride = true"
                                   class="w-full sm:w-36 px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                          bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                          focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Section 2: Resit & Rujukan --}}
            <section class="rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50/60 dark:bg-gray-800/40 p-5">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                                  text-indigo-600 dark:text-indigo-400 text-xs font-bold">2</span>
                    Resit & Rujukan
                </h4>
                <div class="grid grid-cols-1 gap-5">
                    <div class="space-y-2">
                        <label for="no_resit_sistem" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            No. Resit Sistem
                            <span class="ml-1 text-xs font-normal text-indigo-400">(auto-jana)</span>
                        </label>
                        <input type="text" name="no_resit_sistem" id="no_resit_sistem"
                               value="{{ old('no_resit_sistem') }}"
                               placeholder="Dijana oleh sistem jika kosong"
                               class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-xl
                                      bg-gray-50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500 placeholder-gray-300
                                      focus:ring-2 focus:ring-indigo-400 transition">
                    </div>
                </div>
            </section>

            {{-- Section 3: Aktifkan Serta-merta --}}
            <section class="rounded-xl border-2 border-indigo-200 dark:border-indigo-500/50
                             bg-indigo-50/70 dark:bg-indigo-900/25 p-5">
                <label class="flex cursor-pointer items-start gap-4 rounded-lg transition
                               hover:bg-indigo-100/50 dark:hover:bg-indigo-900/30">
                    <input type="checkbox" name="approve_immediately" value="1"
                           x-model="approvePayment"
                           {{ old('approve_immediately') ? 'checked' : '' }}
                           class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 dark:border-gray-600
                                  text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                            Sahkan pembayaran & aktifkan ahli serta-merta
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Centang jika bayaran telah diterima dan ahli hendak diaktifkan segera.
                        </p>
                    </div>
                </label>
            </section>
        </div>

        {{-- If no payment — skip notice --}}
        <div x-show="!hasPayment"
             class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-gray-700
                    bg-gray-50 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm">Tiada rekod bayaran. Ahli akan disimpan dengan status <strong>Aktif</strong> tanpa pembayaran. Boleh dikemaskini kemudian.</p>
        </div>
    </div>
</div>