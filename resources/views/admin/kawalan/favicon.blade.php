@extends('layouts.app')

@section('title', 'Logo & Favicon')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Logo & Favicon'])
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Logo & Favicon</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Urus logo organisasi dan favicon tapak web. Perubahan digunakan pada semua halaman selepas disimpan.</p>
            </div>
        </div>
    </div>

    {{-- URLs must not use @json() inside x-data="..." — double quotes break the HTML attribute and leak JS as text. --}}
    <script>
        window.bakisFaviconPage = function () {
            return {
                previewUrl: null,
                hasServerFavicon: false,
                storeUrl: '',
                destroyUrl: '',
                csrfToken: '',
                init() {
                    const root = this.$root;
                    this.storeUrl = root.dataset.storeUrl || '';
                    this.destroyUrl = root.dataset.destroyUrl || '';
                    this.hasServerFavicon = root.dataset.hasFavicon === '1';
                    const p = root.dataset.previewUrl;
                    this.previewUrl = (p && p.length) ? p : null;
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                },
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (evt) => { this.previewUrl = evt.target.result; };
                    reader.readAsDataURL(file);
                },
                async save() {
                    const input = document.getElementById('favicon_file');
                    if (!input || !input.files || !input.files[0]) {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Sila pilih fail favicon.' });
                        return;
                    }
                    const fd = new FormData();
                    fd.append('favicon', input.files[0]);
                    fd.append('_token', this.csrfToken);
                    try {
                        const res = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Muat naik gagal.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                            return;
                        }
                        this.hasServerFavicon = true;
                        if (data.url) this.previewUrl = data.url;
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Favicon dikemas kini.', timer: 2000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    }
                },
                async remove() {
                    const ok = await Swal.fire({
                        icon: 'warning',
                        title: 'Buang favicon?',
                        text: 'Ikon lalai pelayar akan digunakan semula.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, buang',
                        cancelButtonText: 'Batal'
                    });
                    if (!ok.isConfirmed) return;
                    try {
                        const res = await fetch(this.destroyUrl, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam.' });
                            return;
                        }
                        this.previewUrl = null;
                        this.hasServerFavicon = false;
                        const input = document.getElementById('favicon_file');
                        if (input) input.value = '';
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Favicon dibuang.', timer: 2000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    }
                }
            };
        };

        window.bakisLogoSection = function () {
            return {
                previewUrl: null,
                hasServerLogo: false,
                storeUrl: '',
                destroyUrl: '',
                csrfToken: '',
                init() {
                    const root = this.$root;
                    this.storeUrl = root.dataset.storeUrl || '';
                    this.destroyUrl = root.dataset.destroyUrl || '';
                    this.hasServerLogo = root.dataset.hasLogo === '1';
                    const p = root.dataset.previewUrl;
                    this.previewUrl = (p && p.length) ? p : null;
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                },
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (evt) => { this.previewUrl = evt.target.result; };
                    reader.readAsDataURL(file);
                },
                async save() {
                    const input = document.getElementById('logo_file');
                    if (!input || !input.files || !input.files[0]) {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Sila pilih fail logo.' });
                        return;
                    }
                    const fd = new FormData();
                    fd.append('logo', input.files[0]);
                    fd.append('_token', this.csrfToken);
                    try {
                        const res = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Muat naik gagal.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                            return;
                        }
                        this.hasServerLogo = true;
                        if (data.url) this.previewUrl = data.url;
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Logo organisasi dikemas kini.', timer: 2000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    }
                },
                async remove() {
                    const ok = await Swal.fire({
                        icon: 'warning',
                        title: 'Buang logo organisasi?',
                        text: 'Logo lalai akan digunakan semula pada bar sisi dan halaman utama.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, buang',
                        cancelButtonText: 'Batal'
                    });
                    if (!ok.isConfirmed) return;
                    try {
                        const res = await fetch(this.destroyUrl, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            Swal.fire({ icon: 'error', title: 'Ralat', text: data.message || 'Gagal memadam.' });
                            return;
                        }
                        this.previewUrl = null;
                        this.hasServerLogo = false;
                        const input = document.getElementById('logo_file');
                        if (input) input.value = '';
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Logo organisasi dibuang.', timer: 2000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    }
                }
            };
        };
    </script>
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8 mb-6"
        data-store-url="{{ route('admin.kawalan.favicon.store') }}"
        data-destroy-url="{{ route('admin.kawalan.favicon.destroy') }}"
        data-has-favicon="{{ $faviconUrl ? '1' : '0' }}"
        @if ($faviconUrl) data-preview-url="{{ $faviconUrl }}" @endif
        x-data="bakisFaviconPage()"
    >
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Imej favicon</h2>

        <div class="flex flex-col sm:flex-row gap-8 items-start">
            <div class="shrink-0 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pratonton</label>
                <label class="group relative flex h-24 w-24 cursor-pointer items-center justify-center rounded-2xl
                               border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden
                               hover:border-indigo-400 dark:hover:border-indigo-500 transition-all"
                       for="favicon_file">
                    <template x-if="!previewUrl">
                        <div class="flex flex-col items-center gap-1 text-gray-400 group-hover:text-indigo-500 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-[10px] font-medium">Muat naik</span>
                        </div>
                    </template>
                    <template x-if="previewUrl">
                        <img :src="previewUrl" class="h-full w-full object-cover" alt="Pratonton favicon">
                    </template>
                </label>
                <input type="file" name="favicon" id="favicon_file" accept="image/jpeg,image/png,image/jpg,image/gif,.ico,image/x-icon"
                       class="sr-only" @change="handleFile($event)">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 text-center max-w-[6rem]">JPG, PNG, GIF, ICO — max 512 KB</p>
            </div>

            <div class="flex-1 space-y-4 min-w-0">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Pilih imej persegi (disyorkan 32×32 atau 64×64 px). Fail disimpan dengan selamat dan dipaparkan pada tab pelayar.
                </p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" @click="save()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow-md transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Simpan favicon
                    </button>
                    <button type="button" x-show="hasServerFavicon" x-cloak @click="remove()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-5 py-2.5 text-sm font-semibold text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                        Buang favicon
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8"
        data-store-url="{{ route('admin.kawalan.favicon.logo.store') }}"
        data-destroy-url="{{ route('admin.kawalan.favicon.logo.destroy') }}"
        data-has-logo="{{ $logoUrl ? '1' : '0' }}"
        @if ($logoUrl) data-preview-url="{{ $logoUrl }}" @endif
        x-data="bakisLogoSection()"
    >
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Logo Organisasi</h2>

        <div class="flex flex-col sm:flex-row gap-8 items-start">
            <div class="shrink-0 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pratonton</label>
                <label class="group relative flex h-24 w-24 cursor-pointer items-center justify-center rounded-2xl
                               border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden
                               hover:border-amber-400 dark:hover:border-amber-500 transition-all"
                       for="logo_file">
                    <template x-if="!previewUrl">
                        <div class="flex flex-col items-center gap-1 text-gray-400 group-hover:text-amber-500 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-[10px] font-medium">Muat naik</span>
                        </div>
                    </template>
                    <template x-if="previewUrl">
                        <img :src="previewUrl" class="h-full w-full object-cover" alt="Pratonton logo organisasi">
                    </template>
                </label>
                <input type="file" name="logo" id="logo_file" accept="image/jpeg,image/png,image/jpg,image/gif"
                       class="sr-only" @change="handleFile($event)">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 text-center max-w-[6rem]">PNG, JPG, GIF — max 2 MB</p>
            </div>

            <div class="flex-1 space-y-4 min-w-0">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Pilih imej persegi (disyorkan 256×256 px). Logo dipaparkan pada bar sisi dan halaman utama.
                </p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" @click="save()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow-md transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Simpan logo
                    </button>
                    <button type="button" x-show="hasServerLogo" x-cloak @click="remove()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-5 py-2.5 text-sm font-semibold text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                        Buang logo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25"></script>
@endpush
