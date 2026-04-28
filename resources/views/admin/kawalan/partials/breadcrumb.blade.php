{{-- Expects: string $current (current Kawalan page label) --}}
<nav class="text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200 hover:underline">Dashboard</a>
    <span class="px-2" aria-hidden="true">/</span>
    <span class="text-gray-700 dark:text-gray-200 font-semibold">{{ $current }}</span>
</nav>
