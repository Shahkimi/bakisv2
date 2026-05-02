{{-- Expects: string $current (current Kawalan page label, last breadcrumb segment) --}}
<nav aria-label="Breadcrumb" class="text-sm text-gray-500 dark:text-gray-400">
    <ol class="flex flex-wrap items-center gap-2">
        <li>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-emerald-600 dark:hover:text-emerald-300 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1V10.5z" />
                </svg>
                Utama
            </a>
        </li>
        <li aria-hidden="true" class="text-gray-300 dark:text-gray-600">/</li>
        <li class="text-gray-700 dark:text-gray-200 font-medium">{{ $current }}</li>
    </ol>
</nav>
