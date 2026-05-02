@props([
    'current',
    'title' => null,
    'subtitle' => null,
    'icon' => 'users',
    'variant' => 'indigo',
    'parents' => null,
])

@php
    $gradientWrap = $variant === 'emerald'
        ? 'from-emerald-500 to-teal-600 shadow-emerald-500/30'
        : 'from-indigo-500 to-purple-600 shadow-indigo-500/30';
@endphp

<div {{ $attributes->merge(['class' => 'mb-8 flex flex-col gap-3']) }}>
    @include('user.partials.breadcrumb', ['current' => $current, 'parents' => $parents])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex items-center gap-4 group">
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br {{ $gradientWrap }} shadow-lg flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                @if ($icon === 'search')
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                @elseif ($icon === 'payment')
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @elseif ($icon === 'kutipan')
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m-6-4a6 6 0 1112 0 6 6 0 01-12 0z" />
                    </svg>
                @elseif ($icon === 'member-detail')
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 13c0 1.657-1.79 3-4 3s-4-1.343-4-3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z" />
                    </svg>
                @else
                    {{-- users --}}
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                @endif
            </div>
            <div>
                @if (isset($heading) && ! $heading->isEmpty())
                    {{ $heading }}
                @else
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
                    @endif
                @endif
            </div>
        </div>
        @if (isset($action) && ! $action->isEmpty())
            <div class="flex shrink-0 flex-wrap items-center gap-3 sm:justify-end">
                {{ $action }}
            </div>
        @endif
    </div>
</div>
