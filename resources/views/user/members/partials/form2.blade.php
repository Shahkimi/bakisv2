{{-- Step 2: Maklumat Peribadi --}}
<div x-show="currentStep === 2"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 scale-[0.96]"
     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-8 flex-1 min-h-0 overflow-auto"
     style="display: none;"
     x-data="{
         noKp: '{{ old('no_kp') }}',
         formatNoKp(value) {
             let digits = value.replace(/\D/g, '').substring(0, 12);
             if (digits.length > 8) return digits.substring(0,6) + '-' + digits.substring(6,8) + '-' + digits.substring(8);
             if (digits.length > 6) return digits.substring(0,6) + '-' + digits.substring(6);
             return digits;
         },
         avatarPreview: null,
         handleAvatar(e) {
             const file = e.target.files[0];
             if (!file) return;
             const reader = new FileReader();
             reader.onload = (evt) => { this.avatarPreview = evt.target.result; };
             reader.readAsDataURL(file);
         }
     }">

    {{-- Section Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Maklumat Peribadi</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Nama, kad pengenalan, jawatan dan maklumat hubungan.</p>
        </div>
    </div>

    <div class="space-y-6">

        {{-- Avatar + Nama row --}}
        <div class="flex flex-col sm:flex-row items-start gap-6">
            {{-- Avatar Upload --}}
            <div class="shrink-0 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar</label>
                <label class="group relative flex h-24 w-24 cursor-pointer items-center justify-center rounded-2xl
                               border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden
                               hover:border-indigo-400 dark:hover:border-indigo-500 transition-all"
                       for="gambar">
                    <template x-if="!avatarPreview">
                        <div class="flex flex-col items-center gap-1 text-gray-400 group-hover:text-indigo-500 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-[10px] font-medium">Muat naik</span>
                        </div>
                    </template>
                    <template x-if="avatarPreview">
                        <img :src="avatarPreview" class="h-full w-full object-cover" alt="Preview">
                    </template>
                </label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg"
                       class="sr-only" @change="handleAvatar($event)">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 text-center">JPG / PNG</p>
            </div>

            {{-- Nama --}}
            <div class="flex-1 space-y-2">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Penuh <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                       placeholder="Contoh: AHMAD BIN ABU BAKAR"
                       style="text-transform:uppercase"
                       class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                              shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                              @error('nama') border-red-500 ring-2 ring-red-100 @enderror">
                @error('nama')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- No. KP + Jawatan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="no_kp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    No. Kad Pengenalan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_kp" id="no_kp"
                       :value="noKp"
                       @input="noKp = formatNoKp($event.target.value); $event.target.value = noKp"
                       maxlength="14" required
                       placeholder="850123-01-5678"
                       class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 font-mono
                              shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                              @error('no_kp') border-red-500 ring-2 ring-red-100 @enderror">
                <p class="text-xs text-gray-400 dark:text-gray-500">12 digit — format automatik: <code class="font-mono">XXXXXX-XX-XXXX</code></p>
                @error('no_kp')<p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-2">
                <label for="jawatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Jawatan <span class="text-red-500">*</span>
                </label>
                <select name="jawatan_id" id="jawatan_id" required
                        class="member-select2 block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600
                               rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">— Pilih Jawatan —</option>
                    @foreach($jawatans as $j)
                        <option value="{{ $j->id }}" {{ old('jawatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jawatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Jabatan --}}
        <div class="space-y-2">
            <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Jabatan / Unit / Wad <span class="text-red-500">*</span>
            </label>
            <select name="jabatan_id" id="jabatan_id" required
                    class="member-select2 block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600
                           rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                           shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <option value="">— Pilih Jabatan —</option>
                @foreach($jabatans as $j)
                    <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>

        {{-- Jantina + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Jantina <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-3 pt-1" x-data="{ jantina: '{{ old('jantina', 'L') }}' }">
                    <label :class="jantina === 'L'
                        ? 'flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 cursor-pointer transition'
                        : 'flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 cursor-pointer hover:border-indigo-400 transition'">
                        <input type="radio" name="jantina" value="L" x-model="jantina" required class="sr-only">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4" stroke-width="2"/><path d="M12 12v8M8 20h8" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span class="text-sm font-medium">Lelaki</span>
                    </label>
                    <label :class="jantina === 'P'
                        ? 'flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-pink-500 bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300 cursor-pointer transition'
                        : 'flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 cursor-pointer hover:border-pink-400 transition'">
                        <input type="radio" name="jantina" value="P" x-model="jantina" class="sr-only">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4" stroke-width="2"/><path d="M12 12v4M10 18h4M12 16v2" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span class="text-sm font-medium">Perempuan</span>
                    </label>
                </div>
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emel</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           class="block w-full pl-9 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                  bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                  shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
        </div>

        {{-- No. Tel + No. HP --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="no_tel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telefon Pejabat</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input type="text" name="no_tel" id="no_tel" value="{{ old('no_tel') }}"
                           placeholder="04-xxx xxxx"
                           class="block w-full pl-9 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                  bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                  shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
            <div class="space-y-2">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    No. Telefon Bimbit <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                           placeholder="012-3456789"
                           class="block w-full pl-9 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                                  bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                  shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="space-y-2">
            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
            <textarea name="catatan" id="catatan" rows="3"
                      placeholder="Sebarang nota tambahan (pilihan)..."
                      class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl
                             bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                             shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('catatan') }}</textarea>
        </div>
    </div>
</div>