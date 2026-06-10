{{--
    Social share (Open Graph + Twitter Card) and SEO meta for the public event link.

    Required: $acara
    Optional: $indexable (bool, default true) — false renders noindex for expired links.
--}}
@php
    $indexable = $indexable ?? true;

    $shareTitle = $acara->nama_acara;

    $waktu = trim(($acara->waktu_mula ?? '').' – '.($acara->waktu_tamat ?? ''), " –");

    $shareDesc = collect([
        $acara->lokasi,
        $acara->tarikh?->translatedFormat('d M Y'),
        $waktu !== '' ? $waktu : null,
    ])->filter()->implode(' · ');

    $shareDesc = \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', $shareDesc), 200);

    $shareUrl   = $acara->publicUrl();
    $shareImage = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    $siteName   = config('app.name', 'BAKIS');
@endphp

<meta name="description" content="{{ $shareDesc }}">
<meta name="robots" content="{{ $indexable ? 'index,follow' : 'noindex,follow' }}">
<link rel="canonical" href="{{ $shareUrl }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $shareTitle }}">
<meta property="og:description" content="{{ $shareDesc }}">
<meta property="og:url" content="{{ $shareUrl }}">
<meta property="og:locale" content="ms_MY">
@if ($shareImage)
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:alt" content="{{ $shareTitle }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $shareTitle }}">
<meta name="twitter:description" content="{{ $shareDesc }}">
@if ($shareImage)
    <meta name="twitter:image" content="{{ $shareImage }}">
@endif
