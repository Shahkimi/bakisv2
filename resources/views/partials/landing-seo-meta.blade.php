{{--
    SEO + social share (Open Graph + Twitter Card) + JSON-LD structured data
    for the public landing page (welcome.blade.php).

    URLs are built from config('app.url') — set APP_URL to the production domain
    (e.g. https://bakishsb.kdh.moh.gov.my) in the production .env.
--}}
@php
    $siteName = config('app.name', 'BAKIS');
    $base = rtrim(config('app.url', url('/')), '/');

    $shareTitle = 'BAKIS — Portal Keahlian Warga Hospital Sultanah Bahiyah';
    $shareDesc = 'Portal rasmi keahlian BAKIS — Badan Kebajikan Islam Hospital Sultanah Bahiyah. Daftar & perbaharui keahlian, semak status ahli, dan ikuti program kebajikan komuniti.';

    $logoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();

    // Prefer the dedicated 1200×630 banner; fall back to the uploaded logo.
    $bannerPath = public_path('images/og-banner.png');
    $shareImage = file_exists($bannerPath) ? $base.'/images/og-banner.png' : $logoUrl;
    $hasBanner = file_exists($bannerPath);

    $ld = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'alternateName' => 'Badan Kebajikan Islam Hospital Sultanah Bahiyah',
            'url' => $base,
            'logo' => $logoUrl ?: ($hasBanner ? $base.'/images/og-banner.png' : null),
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $base,
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => [
                ['@type' => 'SiteNavigationElement', 'position' => 1, 'name' => 'Semak Status Ahli', 'url' => $base.'/semak'],
                ['@type' => 'SiteNavigationElement', 'position' => 2, 'name' => 'Program BAKIS', 'url' => $base.'/#program-bakis'],
                ['@type' => 'SiteNavigationElement', 'position' => 3, 'name' => 'Hebahan Penting', 'url' => $base.'/#hebahan-penting'],
            ],
        ],
    ];
@endphp

<meta name="description" content="{{ $shareDesc }}">
<meta name="robots" content="index,follow">
<link rel="canonical" href="{{ $base }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $shareTitle }}">
<meta property="og:description" content="{{ $shareDesc }}">
<meta property="og:url" content="{{ $base }}">
<meta property="og:locale" content="ms_MY">
@if ($shareImage)
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:alt" content="{{ $shareTitle }}">
    @if ($hasBanner)
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $hasBanner ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $shareTitle }}">
<meta name="twitter:description" content="{{ $shareDesc }}">
@if ($shareImage)
    <meta name="twitter:image" content="{{ $shareImage }}">
@endif

{{-- JSON-LD structured data --}}
<script type="application/ld+json">
{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
