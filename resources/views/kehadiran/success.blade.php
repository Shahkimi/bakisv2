<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon-links')
    <title>Kehadiran Direkod — {{ $acara->nama_acara }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes pop { 0% { transform: scale(0.4); opacity: 0; } 60% { transform: scale(1.1); } 100% { transform: scale(1); opacity: 1; } }
        .animate-pop { animation: pop 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28) both; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-green-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(16,185,129,0.18)] border border-gray-100 dark:border-gray-700 p-8 text-center">
            @if ($alreadyRecorded)
                <div class="animate-pop inline-flex h-20 w-20 rounded-full bg-amber-100 dark:bg-amber-900/30 items-center justify-center mb-5">
                    <svg class="w-11 h-11 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sudah Direkod</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kehadiran anda untuk acara ini telah pun direkodkan sebelum ini.</p>
            @else
                <div class="animate-pop inline-flex h-20 w-20 rounded-full bg-emerald-100 dark:bg-emerald-900/30 items-center justify-center mb-5">
                    <svg class="w-11 h-11 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kehadiran Berjaya!</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Terima kasih. Kehadiran anda telah direkodkan.</p>
            @endif

            <div class="mt-6 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 p-5 text-left space-y-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Nama</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kehadiran->nama }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Acara</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $acara->nama_acara }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">Waktu Direkod</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $kehadiran->attended_at->translatedFormat('d M Y, g:i A') }}</p>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-5">Dikuasakan oleh <span class="font-semibold text-gray-500 dark:text-gray-400">BAKIS</span></p>
    </div>
</body>
</html>
