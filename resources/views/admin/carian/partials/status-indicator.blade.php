@php
    $code = $code ?? 'default';
    $slug = str_replace([' ', '-'], '_', strtolower((string) $code));
    if ($slug === '') {
        $slug = 'default';
    }
@endphp
<span class="status-indicator status-{{ $slug }}">
    <span class="status-dot" aria-hidden="true"></span>
    <span class="status-label">{{ $name }}</span>
</span>
