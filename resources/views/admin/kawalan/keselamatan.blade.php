@extends('layouts.app')

@section('title', 'Keselamatan / Turnstile')

@section('content')
<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'Keselamatan / Turnstile'])
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Keselamatan / Turnstile</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lindungi borang log masuk, tetapan semula kata laluan dan semakan keahlian dengan cabaran Cloudflare Turnstile.</p>
            </div>
        </div>
    </div>

    <script>
        window.bakisTurnstilePage = function () {
            return {
                enabled: false,
                hasSecret: false,
                siteKey: '',
                secretKey: '',
                storeUrl: '',
                csrfToken: '',
                saving: false,
                toggling: false,
                init() {
                    const root = this.$root;
                    this.storeUrl = root.dataset.storeUrl || '';
                    this.enabled = root.dataset.enabled === '1';
                    this.hasSecret = root.dataset.hasSecret === '1';
                    this.siteKey = root.dataset.siteKey || '';
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                },
                async toggle() {
                    if (this.toggling || this.saving) return;
                    // Target state we want to switch to; do NOT mutate `enabled` until the
                    // server confirms, so the switch reverts cleanly if the request fails.
                    const target = !this.enabled;
                    this.toggling = true;
                    const fd = new FormData();
                    fd.append('_token', this.csrfToken);
                    fd.append('is_enabled', target ? '1' : '0');
                    // Leave keys blank so stored values are preserved (service keeps them).
                    fd.append('site_key', '');
                    fd.append('secret_key', '');
                    try {
                        const res = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Gagal menukar status Turnstile.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            // Keep `enabled` at its previous value — switch must not appear on.
                            Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                            return;
                        }
                        this.enabled = !!data.enabled;
                        this.hasSecret = !!data.has_secret;
                        this.siteKey = data.site_key || this.siteKey;
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya',
                            text: this.enabled ? 'Turnstile diaktifkan.' : 'Turnstile dinyahaktifkan.',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    } finally {
                        this.toggling = false;
                    }
                },
                async save() {
                    if (this.saving) return;
                    this.saving = true;
                    const fd = new FormData();
                    fd.append('_token', this.csrfToken);
                    fd.append('is_enabled', this.enabled ? '1' : '0');
                    fd.append('site_key', this.siteKey || '');
                    fd.append('secret_key', this.secretKey || '');
                    try {
                        const res = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Gagal menyimpan tetapan.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                            return;
                        }
                        this.enabled = !!data.enabled;
                        this.hasSecret = !!data.has_secret;
                        this.siteKey = data.site_key || this.siteKey;
                        this.secretKey = '';
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Tetapan disimpan.', timer: 2000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    } finally {
                        this.saving = false;
                    }
                }
            };
        };
    </script>

    <div
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-8 mb-6"
        data-store-url="{{ route('admin.kawalan.keselamatan.update') }}"
        data-enabled="{{ $enabled ? '1' : '0' }}"
        data-has-secret="{{ $hasSecret ? '1' : '0' }}"
        data-site-key="{{ $siteKey }}"
        x-data="bakisTurnstilePage()"
    >
        {{-- Enable toggle --}}
        <div class="flex items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div class="min-w-0">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Turnstile</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Apabila diaktifkan, pengguna perlu menyelesaikan cabaran Cloudflare sebelum log masuk, tetapan semula kata laluan, atau semakan keahlian. Suis ini berkuat kuasa serta-merta &mdash; tidak perlu tekan "Simpan tetapan".</p>
            </div>
            <button type="button" role="switch" :aria-checked="enabled ? 'true' : 'false'" @click="toggle()" :disabled="toggling || saving"
                    class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-wait"
                    :class="enabled ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'">
                <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition-transform mt-0.5"
                      :class="enabled ? 'translate-x-5' : 'translate-x-0.5'"></span>
            </button>
        </div>

        {{-- Keys --}}
        <div class="space-y-5 pt-6">
            <div>
                <label for="ts-site-key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kunci Tapak (Site Key)</label>
                <input id="ts-site-key" type="text" x-model="siteKey" autocomplete="off" spellcheck="false"
                       placeholder="0x4AAAAAAA..."
                       class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3.5 py-2.5 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15">
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Kunci awam yang dipaparkan pada borang (boleh dilihat oleh pelawat).</p>
            </div>

            <div>
                <label for="ts-secret-key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kunci Rahsia (Secret Key)</label>
                <input id="ts-secret-key" type="password" x-model="secretKey" autocomplete="new-password" spellcheck="false"
                       :placeholder="hasSecret ? '•••••••• tersimpan — biar kosong untuk kekalkan' : 'Masukkan kunci rahsia'"
                       class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3.5 py-2.5 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15">
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Kunci rahsia untuk pengesahan pelayan. Biarkan kosong untuk mengekalkan kunci sedia ada.</p>
            </div>

            <div class="rounded-xl bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 px-4 py-3 text-xs text-sky-800 dark:text-sky-300">
                Dapatkan kunci dari
                <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" rel="noopener noreferrer" class="font-semibold underline">Cloudflare Dashboard → Turnstile</a>.
                Untuk ujian setempat: site <code class="font-mono">1x00000000000000000000AA</code>, secret <code class="font-mono">1x0000000000000000000000000000000AA</code>.
            </div>

            <div class="flex flex-wrap gap-3 pt-1">
                <button type="button" @click="save()" :disabled="saving"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:shadow-md transition disabled:opacity-60 disabled:cursor-wait">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span x-text="saving ? 'Menyimpan...' : 'Simpan tetapan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25"></script>
@endpush
