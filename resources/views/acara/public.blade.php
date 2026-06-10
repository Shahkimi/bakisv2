<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.favicon-links')
    <title>Kehadiran — {{ $acara->nama_acara }}</title>
    @include('partials.acara-social-meta', ['acara' => $acara, 'indexable' => true])
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-violet-50 to-purple-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(79,70,229,0.15)] border border-gray-100 dark:border-gray-700 overflow-hidden">
            {{-- Header band --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 px-6 py-8 text-center">
                <div class="inline-flex h-16 w-16 rounded-2xl bg-white/15 backdrop-blur items-center justify-center mb-4 ring-1 ring-white/30">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-100">Rekod Kehadiran</p>
                <h1 class="mt-1 text-2xl font-bold text-white leading-tight">{{ $acara->nama_acara }}</h1>
            </div>

            <div class="p-6 sm:p-8">
                {{-- Event details --}}
                <div class="space-y-3 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Lokasi</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $acara->lokasi }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Tarikh &amp; Waktu</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $acara->tarikh?->translatedFormat('d M Y') ?? '-' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $acara->waktu_mula }} – {{ $acara->waktu_tamat }}</p>
                        </div>
                    </div>
                </div>

                @if (session('lookup_error'))
                    <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200 flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('lookup_error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('acara.attend', ['code' => $acara->code]) }}" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5">
                    @csrf
                    <div>
                        <label for="no_kp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">No. Kad Pengenalan</label>
                        <input type="text" name="no_kp" id="no_kp" inputmode="numeric" autocomplete="off" required
                            value="{{ old('no_kp') }}"
                            maxlength="12" placeholder="cth: 901231015432"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 text-base tracking-wide focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            oninput="this.value = this.value.replace(/\D/g, '')" />
                        <p class="mt-1.5 text-xs text-gray-400">Masukkan 12 digit No. KP tanpa simbol.</p>
                    </div>

                    <button type="submit" :disabled="submitting"
                        class="group relative w-full inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-indigo-500/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-70 disabled:hover:scale-100 dark:focus:ring-offset-gray-900">
                        <svg x-show="!submitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg x-show="submitting" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="submitting ? 'Memproses…' : 'Sahkan Kehadiran'">Sahkan Kehadiran</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-5">Dikuasakan oleh <span class="font-semibold text-gray-500 dark:text-gray-400">BAKIS</span></p>
    </div>
</body>
</html>
