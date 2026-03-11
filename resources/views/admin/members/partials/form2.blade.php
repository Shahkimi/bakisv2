{{-- Step 2: Maklumat Peribadi --}}
<div x-show="currentStep === 2"
     x-transition:enter="transition-all ease-out duration-500 delay-[150ms]"
     x-transition:enter-start="opacity-0 transform translate-y-6 sm:translate-y-10 scale-[0.96]"
     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave="transition-all ease-in duration-300 absolute w-full h-full left-0 top-0 z-0"
     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 transform -translate-y-6 sm:-translate-y-10 scale-[0.96]"
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
