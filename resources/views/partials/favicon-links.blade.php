@php
    $faviconUrl = app(\App\Services\SiteSettingService::class)->faviconPublicUrl();
@endphp
@if ($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
@endif
