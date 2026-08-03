<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon-links')
    <title>Pautan Tamat Tempoh — {{ $acara->nama_acara }}</title>
    @include('partials.acara-social-meta', ['acara' => $acara, 'indexable' => false])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 to-slate-200 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center">
            <div class="inline-flex h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-700 items-center justify-center mb-5">
                <svg class="w-11 h-11 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pautan Tamat Tempoh</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Maaf, kehadiran untuk <span class="font-semibold text-gray-900 dark:text-white">{{ $acara->nama_acara }}</span> tidak lagi dibuka. Pautan ini telah tamat tempoh.</p>
            <p class="mt-4 text-xs text-gray-400">Sila hubungi pihak penganjur jika anda memerlukan bantuan.</p>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-5">Dikuasakan oleh <span class="font-semibold text-gray-500 dark:text-gray-400">BAKIS</span></p>
    </div>
</body>
</html>
