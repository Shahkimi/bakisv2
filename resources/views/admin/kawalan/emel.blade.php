@extends('layouts.app')

@section('title', 'E-mel / SMTP Relay')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-6">
    {{-- Page Header --}}
    <div class="mb-5 flex flex-col gap-3">
        @include('admin.kawalan.partials.breadcrumb', ['current' => 'E-mel'])
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 shadow-sm flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <svg class="w-7 h-7 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">E-mel / SMTP Relay</h1>
                    <span class="inline-flex items-center rounded-md border border-indigo-100 dark:border-indigo-900/50 bg-indigo-50 dark:bg-indigo-900/30 px-1.5 py-0.5 text-[10px] font-mono font-semibold uppercase tracking-wider text-indigo-500 dark:text-indigo-300">Konfigurasi Pelayan</span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Konfigurasikan geganti (relay) SMTP untuk penghantaran resit, jemputan akaun dan pemberitahuan sistem — tanpa perlu ubah fail <code class="font-mono text-xs px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">.env</code>.</p>
            </div>
        </div>
    </div>

    <script>
        window.bakisMailSettingPage = function () {
            return {
                enabled: false,
                host: '',
                port: '',
                scheme: 'auto',
                username: '',
                password: '',
                showPassword: false,
                hasPassword: false,
                fromAddress: '',
                fromName: '',
                testEmail: '',
                storeUrl: '',
                testUrl: '',
                csrfToken: '',
                saving: false,
                toggling: false,
                testing: false,
                init() {
                    const root = this.$root;
                    this.storeUrl = root.dataset.storeUrl || '';
                    this.testUrl = root.dataset.testUrl || '';
                    this.enabled = root.dataset.enabled === '1';
                    this.host = root.dataset.host || '';
                    this.port = root.dataset.port || '';
                    this.scheme = root.dataset.scheme || 'auto';
                    this.username = root.dataset.username || '';
                    this.hasPassword = root.dataset.hasPassword === '1';
                    this.fromAddress = root.dataset.fromAddress || '';
                    this.fromName = root.dataset.fromName || '';
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                },
                async toggle() {
                    if (this.toggling || this.saving) return;
                    const target = !this.enabled;
                    this.toggling = true;
                    const fd = new FormData();
                    fd.append('_token', this.csrfToken);
                    fd.append('is_enabled', target ? '1' : '0');
                    try {
                        const res = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Gagal menukar status e-mel.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            Swal.fire({ icon: 'error', title: 'Ralat', text: msg });
                            return;
                        }
                        this.applyResponse(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berjaya',
                            text: this.enabled ? 'Fungsi e-mel diaktifkan.' : 'Fungsi e-mel dinyahaktifkan — e-mel keluar akan dilangkau.',
                            timer: 2200,
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
                    fd.append('host', this.host || '');
                    fd.append('port', this.port || '');
                    fd.append('scheme', this.scheme || 'auto');
                    fd.append('username', this.username || '');
                    fd.append('password', this.password || '');
                    fd.append('from_address', this.fromAddress || '');
                    fd.append('from_name', this.fromName || '');
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
                        this.applyResponse(data);
                        this.password = '';
                        this.showPassword = false;
                        Swal.fire({ icon: 'success', title: 'Berjaya', text: data.message || 'Tetapan disimpan.', timer: 2200, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    } finally {
                        this.saving = false;
                    }
                },
                applyResponse(data) {
                    this.enabled = !!data.enabled;
                    this.hasPassword = !!data.has_password;
                    if (typeof data.host === 'string') this.host = data.host;
                    if (data.port !== null && data.port !== undefined) this.port = String(data.port);
                    if (typeof data.scheme === 'string') this.scheme = data.scheme;
                    if (typeof data.username === 'string') this.username = data.username;
                    if (typeof data.from_address === 'string') this.fromAddress = data.from_address;
                    if (typeof data.from_name === 'string') this.fromName = data.from_name;
                },
                async sendTest() {
                    if (this.testing || !this.testEmail) return;
                    this.testing = true;
                    const fd = new FormData();
                    fd.append('_token', this.csrfToken);
                    fd.append('test_email', this.testEmail);
                    try {
                        const res = await fetch(this.testUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || !data.success) {
                            let msg = data.message || 'Gagal menghantar e-mel ujian.';
                            if (res.status === 422 && data.errors) {
                                const flat = Object.values(data.errors).flat();
                                if (flat.length) msg = flat.join(' ');
                            }
                            Swal.fire({ icon: 'error', title: 'E-mel ujian gagal', text: msg });
                            return;
                        }
                        Swal.fire({ icon: 'success', title: 'E-mel ujian dihantar', text: data.message, timer: 3000, showConfirmButton: false });
                    } catch (err) {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Ralat rangkaian.' });
                    } finally {
                        this.testing = false;
                    }
                }
            };
        };
    </script>

    <div
        x-data="bakisMailSettingPage()"
        data-store-url="{{ route('admin.kawalan.emel.update') }}"
        data-test-url="{{ route('admin.kawalan.emel.test') }}"
        data-enabled="{{ $enabled ? '1' : '0' }}"
        data-host="{{ $host }}"
        data-port="{{ $port }}"
        data-scheme="{{ $scheme }}"
        data-username="{{ $username }}"
        data-has-password="{{ $hasPassword ? '1' : '0' }}"
        data-from-address="{{ $fromAddress }}"
        data-from-name="{{ $fromName }}"
        class="flex flex-col gap-4"
    >
        {{-- Live connection summary strip --}}
        <div class="rounded-2xl border border-indigo-100 dark:border-indigo-900/40 bg-gradient-to-r from-indigo-50 via-sky-50 to-purple-50 dark:from-indigo-950/30 dark:via-sky-950/20 dark:to-purple-950/30 shadow-sm px-4 py-3.5 sm:px-5">
            <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
                <div class="flex items-center gap-3">
                    <button type="button" role="switch" :aria-checked="enabled ? 'true' : 'false'" @click="toggle()" :disabled="toggling || saving"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border border-black/5 dark:border-white/10 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-50 dark:focus-visible:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-wait"
                            :class="enabled ? 'bg-emerald-300' : 'bg-gray-300 dark:bg-gray-600'">
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform mt-0.5 ml-0.5" :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <div class="flex flex-col leading-tight">
                        <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Status</span>
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold" :class="enabled ? 'text-emerald-600 dark:text-emerald-300' : 'text-gray-500 dark:text-gray-400'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="enabled ? 'bg-emerald-400 animate-pulse' : 'bg-gray-400 dark:bg-gray-500'"></span>
                            <span x-text="enabled ? 'E-mel Aktif' : 'E-mel Dimatikan'"></span>
                        </span>
                    </div>
                </div>

                <div class="hidden sm:block h-8 w-px bg-indigo-100 dark:bg-indigo-900/50"></div>

                <div class="flex flex-col leading-tight min-w-0">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Sambungan</span>
                    <span class="inline-flex items-center gap-2 font-mono text-sm text-gray-600 dark:text-gray-300 min-w-0">
                        <span class="break-all" x-text="(host || '—') + ':' + (port || '—')"></span>
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide shrink-0"
                              :class="scheme === 'smtps' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300' : 'bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-300'"
                              x-text="scheme === 'smtps' ? 'SMTPS' : 'STARTTLS'"></span>
                    </span>
                </div>

                <div class="hidden sm:block h-8 w-px bg-indigo-100 dark:bg-indigo-900/50"></div>

                <div class="flex flex-col leading-tight min-w-0 flex-1">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Pengirim</span>
                    <span class="font-mono text-sm text-gray-600 dark:text-gray-300 truncate" x-text="fromAddress || '—'"></span>
                </div>
            </div>
        </div>

        {{-- Bento grid --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- SMTP connection tile --}}
            <div class="md:col-span-7 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4 sm:p-5 flex flex-col">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100 dark:border-gray-700/70">
                    <div class="w-7 h-7 rounded-lg bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-sky-500 dark:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h.01M7 16h.01" />
                        </svg>
                    </div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sambungan Pelayan</h2>
                </div>

                <div class="pt-3.5 grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label for="mail-host" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Pelayan (Host)</label>
                        <input id="mail-host" type="text" x-model="host" autocomplete="off" spellcheck="false"
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                    </div>

                    <div>
                        <label for="mail-port" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Port</label>
                        <input id="mail-port" type="number" x-model="port" min="1" max="65535"
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                        <div class="mt-1.5 flex gap-1.5">
                            <button type="button" @click="port = '25'" class="rounded px-2 py-0.5 text-[11px] font-mono font-medium bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-300 hover:bg-sky-100 dark:hover:bg-sky-900/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-300 transition">25</button>
                            <button type="button" @click="port = '587'" class="rounded px-2 py-0.5 text-[11px] font-mono font-medium bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-300 hover:bg-sky-100 dark:hover:bg-sky-900/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-300 transition">587</button>
                        </div>
                    </div>

                    <div>
                        <label for="mail-scheme" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Kaedah Sambungan</label>
                        <select id="mail-scheme" x-model="scheme"
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                            <option value="auto">Automatik (STARTTLS)</option>
                            <option value="smtps">SMTPS (TLS terus — 465)</option>
                        </select>
                    </div>
                </div>

                <p class="mt-4 text-[11px] text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700/70 pt-3">Geganti mesti menyokong pengesahan SMTP (Username + Password). Kaedah TLS dipadankan secara automatik mengikut port yang dipilih.</p>
            </div>

            {{-- Authentication tile --}}
            <div class="md:col-span-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4 sm:p-5 flex flex-col">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100 dark:border-gray-700/70">
                    <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-violet-500 dark:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pengesahan</h2>
                </div>

                <div class="pt-3.5 flex flex-col gap-3.5">
                    <div>
                        <label for="mail-username" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Username</label>
                        <input id="mail-username" type="text" x-model="username" autocomplete="off" spellcheck="false"
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                    </div>

                    <div>
                        <label for="mail-password" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Password</label>
                        <div class="relative">
                            <input id="mail-password" :type="showPassword ? 'text' : 'password'" x-model="password" autocomplete="new-password" spellcheck="false"
                                   class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 pr-10 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                            <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                    :aria-label="showPassword ? 'Sembunyikan password' : 'Papar password'"
                                    class="absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 rounded-r-lg">
                                <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 012.132-3.336m3.257-2.062A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.957 9.957 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/></svg>
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] font-mono text-gray-400 dark:text-gray-500" x-text="hasPassword ? '•••••••• tersimpan — kosongkan untuk kekalkan' : 'Tiada kata laluan disimpan lagi'"></p>
                    </div>
                </div>
            </div>

            {{-- Sender identity tile --}}
            <div class="md:col-span-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4 sm:p-5 flex flex-col">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100 dark:border-gray-700/70">
                    <div class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-rose-500 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9 8.96 8.96 0 005.657-2" />
                        </svg>
                    </div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Identiti Pengirim</h2>
                </div>

                <div class="pt-3.5 flex flex-col gap-3.5">
                    <div>
                        <label for="mail-from-address" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Alamat Pengirim</label>
                        <input id="mail-from-address" type="email" x-model="fromAddress" autocomplete="off" spellcheck="false"
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm font-mono text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                    </div>

                    <div>
                        <label for="mail-from-name" class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Pengirim</label>
                        <input id="mail-from-name" type="text" x-model="fromName" autocomplete="off"
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Actions tile: save + test, split --}}
            <div class="md:col-span-7 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 dark:divide-gray-700/70">
                    <div class="p-4 sm:p-5 flex flex-col">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100 dark:border-gray-700/70">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </div>
                            <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Simpan Tetapan</h2>
                        </div>
                        <p class="pt-3.5 text-xs text-gray-500 dark:text-gray-400 flex-1">Simpan sambungan, pengesahan dan identiti pengirim di atas. Kosongkan Password untuk kekalkan nilai tersimpan.</p>
                        <button type="button" @click="save()" :disabled="saving"
                                class="mt-3.5 inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-200 hover:bg-indigo-300 dark:bg-indigo-900/50 dark:hover:bg-indigo-900/70 px-4 py-2 text-sm font-semibold text-indigo-900 dark:text-indigo-200 shadow-sm hover:shadow-md transition disabled:opacity-60 disabled:cursor-wait">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan tetapan'"></span>
                        </button>
                    </div>

                    <div class="p-4 sm:p-5 flex flex-col">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100 dark:border-gray-700/70">
                            <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-amber-500 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">E-mel Ujian</h2>
                        </div>
                        <div class="pt-3.5 flex flex-col gap-2.5 flex-1">
                            <label for="mail-test-email" class="sr-only">Alamat e-mel ujian</label>
                            <input id="mail-test-email" type="email" x-model="testEmail" autocomplete="off"
                                   class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/40 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-amber-300 focus:ring-4 focus:ring-amber-200/50 focus:bg-white dark:focus:bg-gray-700 transition-colors">
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">Berfungsi walaupun status di atas dimatikan.</p>
                        </div>
                        <button type="button" @click="sendTest()" :disabled="testing || !testEmail"
                                class="mt-2.5 inline-flex items-center justify-center gap-2 rounded-lg border border-amber-200 dark:border-amber-900/50 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 px-4 py-2 text-sm font-semibold text-amber-800 dark:text-amber-200 transition disabled:opacity-60 disabled:cursor-wait">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span x-text="testing ? 'Menghantar...' : 'Hantar e-mel ujian'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25"></script>
@endpush
