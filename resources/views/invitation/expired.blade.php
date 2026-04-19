<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jemputan tamat — {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">
    <div class="max-w-md text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
        <p class="text-amber-600 dark:text-amber-400 font-semibold mb-2">Jemputan tamat tempoh</p>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Sila minta pentadbir menghantar semula jemputan baharu.</p>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-medium text-white bg-violet-600 hover:bg-violet-500 transition">Ke log masuk</a>
    </div>
</body>
</html>
