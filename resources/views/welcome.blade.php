<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon-links')
    <title>BAKIS Membership</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #f5f1e6;
            --surface: #fffdfa;
            --ink: #181513;
            --muted: #5f554f;
            --brand: #b75817;
            --brand-deep: #7d3506;
            --accent: #0f5f57;
            --line: #d8cfc4;
            --shadow: 0 20px 45px rgba(125, 53, 6, 0.15);
            --radius-xl: 26px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
        }

        /* ── Viewport fit ── */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 15% 10%, #ffd79e 0%, transparent 40%),
                radial-gradient(circle at 80% 15%, #f6b98e 0%, transparent 35%),
                linear-gradient(155deg, #f9f4e9 0%, #f2eadc 50%, #efe3d5 100%);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            min-height: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: min(1120px, calc(100% - 2rem));
            margin: 0 auto;
        }

        /* ── Header ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 0;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-family: "Sora", sans-serif;
            letter-spacing: 0.02em;
            font-weight: 700;
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            box-shadow: var(--shadow);
            font-size: 0.95rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.72rem 1.2rem;
            font-weight: 700;
            font-size: 0.95rem;
            transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
            border: 1px solid transparent;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-outline {
            border-color: var(--line);
            color: var(--ink);
            background: rgba(255, 253, 250, 0.8);
        }

        .btn-solid {
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            color: #fff;
            box-shadow: var(--shadow);
        }

        .btn-accent {
            background: linear-gradient(135deg, #0f5f57, #0b463f);
            color: #fff;
            box-shadow: 0 14px 30px rgba(15, 95, 87, 0.22);
        }

        /* ── Hero grid — fills all remaining space ── */
        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            grid-template-rows: 1fr;
            gap: 1.4rem;
            padding: 1.4rem 0 0;
            align-items: stretch;
            flex: 1;
            min-height: 0;
        }

        .hero-panel,
        .hero-side {
            background: var(--surface);
            border: 1px solid rgba(125, 53, 6, 0.15);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
        }

        .hero-panel {
            padding: 2rem;
            position: relative;
            overflow: hidden;
            overflow-y: auto;
            animation: rise .6s ease-out both;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            right: -65px;
            top: -55px;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(183, 88, 23, 0.3), rgba(183, 88, 23, 0));
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border: 1px solid #e6d5c5;
            background: #fff8f1;
            border-radius: 999px;
            padding: 0.4rem 0.7rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand-deep);
        }

        h1 {
            margin: 1rem 0 0.85rem;
            font-family: "Sora", sans-serif;
            font-size: clamp(1.9rem, 3.8vw, 3.05rem);
            line-height: 1.12;
            letter-spacing: -0.02em;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            max-width: 58ch;
            line-height: 1.6;
        }

        .hero-actions {
            margin-top: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
        }

        .quick-access {
            margin-top: 1.15rem;
            padding: 0.9rem;
            border-radius: var(--radius-lg);
            border: 1px solid #eadccf;
            background: #fff8f0;
            display: grid;
            gap: 0.7rem;
        }

        .quick-access strong {
            font-family: "Sora", sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            color: var(--brand-deep);
        }

        .quick-access-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .stats {
            margin-top: 1.6rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .stat {
            border: 1px dashed #e4d7c8;
            background: #fffcf7;
            border-radius: 14px;
            padding: 0.8rem;
        }

        .stat strong {
            display: block;
            font-size: 1.2rem;
            font-family: "Sora", sans-serif;
        }

        .stat span {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .hero-side {
            padding: 1rem;
            display: grid;
            gap: 0.8rem;
            animation: rise .6s ease-out .12s both;
            overflow-y: auto;
            min-height: 0;
            align-content: start;
        }

        .mini-card {
            border-radius: var(--radius-lg);
            padding: 1rem;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, #fff, #fef9f4);
        }

        .mini-card h3 {
            margin: 0 0 0.35rem;
            font-family: "Sora", sans-serif;
            font-size: 1rem;
        }

        .mini-card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.91rem;
            line-height: 1.5;
        }

        /* ── Membership plans — compact horizontal strip ── */
        .section {
            margin-top: 0.85rem;
            padding-bottom: 0;
            flex-shrink: 0;
        }

        .section-title {
            font-family: "Sora", sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin: 0 0 0.45rem;
        }

        .plans {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.55rem;
        }

        .plan {
            background: rgba(255, 253, 249, 0.92);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 0.6rem 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .plan h4 {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--muted);
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .price {
            margin: 0;
            font-size: 1.1rem;
            font-family: "Sora", sans-serif;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .price small {
            font-family: "Manrope", sans-serif;
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 600;
        }

        /* bullet list hidden in compact strip mode */
        .plan ul {
            display: none;
        }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid rgba(95, 85, 79, 0.2);
            padding: 0.6rem 0 0.75rem;
            color: var(--muted);
            font-size: 0.88rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            flex-shrink: 0;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Breakpoints ── */
        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .plans {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 720px) {
            /* restore normal scroll on small screens */
            html, body { overflow: auto; height: auto; }
            main { overflow: visible; flex: none; }
            .hero { flex: none; grid-template-rows: auto; min-height: auto; padding: 1.5rem 0 0; }
            .hero-panel, .hero-side { overflow-y: visible; min-height: auto; }
            .topbar { flex-direction: column; align-items: flex-start; }
            .plans { grid-template-columns: 1fr; gap: 0.85rem; }
            .stats { grid-template-columns: 1fr; }
            .hero-panel { padding: 1.4rem; }
            .quick-access-row .btn { width: 100%; }
            /* restore full plan cards on mobile */
            .section { margin-top: 1.2rem; padding-bottom: 2rem; }
            .section-title { font-size: clamp(1.4rem, 2.2vw, 1.9rem); text-transform: none; letter-spacing: 0; color: var(--ink); margin-bottom: 0.8rem; }
            .plan { flex-direction: column; align-items: flex-start; gap: 0.3rem; padding: 1rem; }
            .plan h4 { font-size: 1rem; color: var(--ink); white-space: normal; overflow: visible; }
            .price { font-size: 1.7rem; }
            .plan ul { display: grid; gap: 0.4rem; font-size: 0.9rem; color: var(--muted); list-style: none; padding: 0; margin: 0; }
            .plan li::before { content: "* "; color: var(--accent); font-weight: 800; }
            .footer { padding: 1.3rem 0 2rem; }
        }

        /* ===== Ahli Jawatankuasa carousel (hero-side) ===== */
        .ajk-card {
            border-radius: var(--radius-lg);
            border: 1px solid rgba(125, 53, 6, 0.12);
            background: var(--surface);
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(125, 53, 6, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .ajk-header {
            padding: 0.7rem 1rem 0.72rem;
            background: linear-gradient(135deg, #7d3506 0%, #b75817 55%, #c9661f 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
        }

        .ajk-header::after {
            content: "";
            position: absolute;
            right: -18px;
            top: -18px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }

        .ajk-title {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .ajk-eyebrow {
            font-size: 0.62rem;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.58);
            white-space: nowrap;
        }

        .ajk-viewport {
            overflow: hidden;
        }

        .ajk-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }

        .ajk-slide {
            flex: 0 0 100%;
            min-width: 100%;
            display: grid;
            grid-template-columns: 92px 1fr;
        }

        .ajk-photo-zone {
            background: linear-gradient(170deg, rgba(183, 88, 23, 0.07) 0%, rgba(125, 53, 6, 0.03) 100%);
            border-right: 1px solid rgba(183, 88, 23, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
        }

        .ajk-photo {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid #fff;
            box-shadow: 0 0 0 4px rgba(183, 88, 23, 0.12), 0 5px 18px rgba(125, 53, 6, 0.28);
            background: var(--bg);
        }

        .ajk-photo.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(183, 88, 23, 0.09);
            color: var(--brand);
            font-family: "Sora", sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
        }

        .ajk-info {
            padding: 1rem 1rem 1rem 0.9rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.45rem;
            min-width: 0;
        }

        .ajk-name {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
        }

        .ajk-badge {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            padding: 0.22rem 0.6rem;
            background: rgba(183, 88, 23, 0.1);
            color: var(--brand-deep);
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            border: 1px solid rgba(183, 88, 23, 0.16);
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ajk-controls {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.55rem 0.85rem;
            border-top: 1px solid rgba(183, 88, 23, 0.07);
            background: rgba(125, 53, 6, 0.018);
        }

        .ajk-arrow {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1px solid rgba(183, 88, 23, 0.2);
            background: #fff;
            color: var(--brand-deep);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.18s, color 0.18s, border-color 0.18s, transform 0.18s;
        }

        .ajk-arrow:hover {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            transform: scale(1.1);
        }

        .ajk-arrow svg {
            width: 13px;
            height: 13px;
        }

        .ajk-progress-wrap {
            flex: 1;
            height: 3px;
            background: rgba(183, 88, 23, 0.13);
            border-radius: 999px;
            overflow: hidden;
        }

        .ajk-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--brand-deep), var(--brand));
            border-radius: 999px;
        }

        .ajk-counter {
            font-size: 0.71rem;
            font-weight: 700;
            color: var(--muted);
            font-family: "Sora", sans-serif;
            letter-spacing: 0.04em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        @media (prefers-reduced-motion: reduce) {
            .ajk-track { transition: none; }
            .ajk-progress-fill { transition: none !important; }
        }

        /* ===== Program BAKIS card (hero-side) ===== */
        .prog-card {
            border-radius: var(--radius-lg);
            border: 1px solid rgba(125, 53, 6, 0.12);
            background: var(--surface);
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(125, 53, 6, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .prog-header {
            padding: 0.7rem 1rem 0.72rem;
            background: linear-gradient(135deg, #0f5f57 0%, #11756b 55%, #138a7d 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
        }

        .prog-header::after {
            content: "";
            position: absolute;
            right: -18px;
            top: -18px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .prog-title {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .prog-eyebrow {
            font-size: 0.62rem;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
        }

        .prog-list {
            display: flex;
            flex-direction: column;
        }

        .prog-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.9rem;
            border-bottom: 1px solid rgba(183, 88, 23, 0.08);
            transition: background 0.2s;
        }

        .prog-row:last-child {
            border-bottom: 0;
        }

        .prog-row:hover {
            background: rgba(15, 95, 87, 0.035);
        }

        .prog-row.is-past {
            opacity: 0.62;
        }

        .prog-date-chip {
            flex-shrink: 0;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1;
            background: linear-gradient(160deg, #e7f3f1, #d2ebe7);
            color: var(--accent);
            border: 1px solid rgba(15, 95, 87, 0.14);
        }

        .prog-row.is-past .prog-date-chip {
            background: linear-gradient(160deg, #f1efe9, #e7e1d6);
            color: var(--muted);
            border-color: var(--line);
        }

        .prog-date-chip .d {
            font-family: "Sora", sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .prog-date-chip .m {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 0.12rem;
        }

        .prog-body {
            min-width: 0;
            flex: 1;
        }

        .prog-name {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prog-time {
            margin: 0.2rem 0 0;
            font-size: 0.74rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .prog-time svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        .prog-badge {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.28rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.64rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            background: rgba(15, 95, 87, 0.12);
            color: var(--accent);
            border: 1px solid rgba(15, 95, 87, 0.18);
            white-space: nowrap;
        }

        .prog-badge .dot {
            width: 0.38rem;
            height: 0.38rem;
            border-radius: 999px;
            background: currentColor;
        }

        .prog-badge.is-past {
            background: rgba(95, 85, 79, 0.1);
            color: var(--muted);
            border-color: var(--line);
        }
    </style>
</head>

<body>
    @php
        $brandLogoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    @endphp
    <header class="container topbar">
        <div class="brand">
            @if ($brandLogoUrl)
                <img src="{{ $brandLogoUrl }}" alt="BAKIS Membership" class="brand-badge" style="object-fit: cover;">
            @else
                <div class="brand-badge">BK</div>
            @endif
            <span>BAKIS Membership</span>
        </div>
        <nav class="nav-links">
            <a class="btn btn-outline" href="/login">Login</a>
            <a class="btn btn-accent" href="/semak">Semak Status Ahli</a>
        </nav>
    </header>

    <main class="container">
        <section class="hero">
            <article class="hero-panel">
                <span class="eyebrow">Official Portal</span>
                <h1>Welcome to BAKIS Membership</h1>
                <p>
                    Manage your profile, enjoy exclusive member privileges, and stay updated with upcoming community
                    programs through one membership hub.
                </p>
                <div class="quick-access">
                    <strong>Quick Access</strong>
                    <div class="quick-access-row">
                        <a class="btn btn-solid" href="/login">Login</a>
                        <a class="btn btn-accent" href="/semak">Semak Status Ahli</a>
                    </div>
                </div>
                <div class="hero-actions">
                    @if (Route::has('register'))
                        <a class="btn btn-solid" href="{{ route('register') }}">Create Membership</a>
                    @endif
                    <a class="btn btn-outline" href="#plans">View Membership Plans</a>
                </div>
                <div class="stats">
                    <div class="stat">
                        <strong>24/7</strong>
                        <span>Portal Access</span>
                    </div>
                    <div class="stat">
                        <strong>Fast</strong>
                        <span>Membership Setup</span>
                    </div>
                    <div class="stat">
                        <strong>Secure</strong>
                        <span>Account Protection</span>
                    </div>
                </div>
            </article>

            <aside class="hero-side">
                @if ($committee->isNotEmpty())
                    <div class="ajk-card" id="ajk-carousel" data-count="{{ $committee->count() }}">
                        <div class="ajk-header">
                            <h3 class="ajk-title">Ahli Jawatankuasa</h3>
                            <span class="ajk-eyebrow">Barisan Kepimpinan</span>
                        </div>
                        <div class="ajk-viewport">
                            <div class="ajk-track" id="ajk-track">
                                @foreach ($committee as $member)
                                    @php($photo = $member->photoUrl())
                                    <div class="ajk-slide">
                                        <div class="ajk-photo-zone">
                                            @if ($photo)
                                                <img class="ajk-photo" src="{{ $photo }}" alt="{{ $member->name }}" loading="lazy">
                                            @else
                                                <div class="ajk-photo placeholder">{{ \Illuminate\Support\Str::of($member->name)->substr(0, 1)->upper() }}</div>
                                            @endif
                                        </div>
                                        <div class="ajk-info">
                                            <h4 class="ajk-name">{{ $member->name }}</h4>
                                            <span class="ajk-badge">{{ $member->jawatan }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($committee->count() > 1)
                            <div class="ajk-controls">
                                <button type="button" class="ajk-arrow" id="ajk-prev" aria-label="Sebelumnya">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <div class="ajk-progress-wrap">
                                    <div class="ajk-progress-fill" id="ajk-progress"></div>
                                </div>
                                <span class="ajk-counter" id="ajk-counter">01&thinsp;/&thinsp;{{ str_pad($committee->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                <button type="button" class="ajk-arrow" id="ajk-next" aria-label="Seterusnya">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mini-card">
                        <h3>Ahli Jawatankuasa</h3>
                        <p>Track renewals, update your details, and access your membership card in one place.</p>
                    </div>
                @endif
                @if ($programs->isNotEmpty())
                    @php($monthsMy = [1 => 'Jan', 2 => 'Feb', 3 => 'Mac', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ogo', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Dis'])
                    @php($fmtTime = function ($t) {
                        if (! $t) { return ''; }
                        if (! preg_match('/^(\d{1,2}):(\d{2})/', (string) $t, $m)) { return $t; }
                        $h = (int) $m[1]; $ap = $h >= 12 ? 'PM' : 'AM'; $h = $h % 12 ?: 12;
                        return $h.':'.$m[2].' '.$ap;
                    })
                    <div class="prog-card">
                        <div class="prog-header">
                            <h3 class="prog-title">Program BAKIS</h3>
                            <span class="prog-eyebrow">Aktiviti &amp; Acara</span>
                        </div>
                        <div class="prog-list">
                            @foreach ($programs as $program)
                                @php($past = $program->isPast())
                                @php($range = trim(($fmtTime($program->waktu_mula) ?: '').(($program->waktu_mula && $program->waktu_tamat) ? ' – ' : '').($fmtTime($program->waktu_tamat) ?: '')))
                                <div class="prog-row {{ $past ? 'is-past' : '' }}">
                                    <div class="prog-date-chip">
                                        <span class="d">{{ $program->tarikh->format('d') }}</span>
                                        <span class="m">{{ $monthsMy[(int) $program->tarikh->format('n')] }}</span>
                                    </div>
                                    <div class="prog-body">
                                        <p class="prog-name">{{ $program->nama_program }}</p>
                                        @if ($range !== '')
                                            <p class="prog-time">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $range }}
                                            </p>
                                        @endif
                                    </div>
                                    @if ($past)
                                        <span class="prog-badge is-past"><span class="dot"></span>Telah Dianjurkan</span>
                                    @else
                                        <span class="prog-badge"><span class="dot"></span>Akan Datang</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mini-card">
                        <h3>Program BAKIS</h3>
                        <p>Aktiviti, bengkel, dan acara komuniti BAKIS akan dipaparkan di sini.</p>
                    </div>
                @endif
                <div class="mini-card">
                    <h3>Priority Announcements</h3>
                    <p>Receive timely updates about membership matters and upcoming opportunities.</p>
                </div>
            </aside>
        </section>

        <section class="section" id="plans">
            <h2 class="section-title">Membership Options</h2>
            <div class="plans">
                @forelse($yurans as $yuran)
                    <article class="plan">
                        <h4>{{ $yuran->jenis_yuran }}</h4>
                        <p class="price">
                            RM {{ number_format((float) $yuran->jumlah, 2) }}
                            <small>/ {{ (int) $yuran->tempoh_tahun }} year{{ (int) $yuran->tempoh_tahun === 1 ? '' : 's' }}</small>
                        </p>
                        <ul>
                            @switch($yuran->jenis_yuran)
                                @case('Pendaftaran Keahlian')
                                    <li>Digital member profile</li>
                                    <li>Community event notifications</li>
                                    <li>Standard support access</li>
                                    @break
                                @case('Pembaharuan Keahlian')
                                    <li>All Basic benefits</li>
                                    <li>Priority event registration</li>
                                    <li>Discounted selected programs</li>
                                    @break
                                @case('Pembaharuan 2 Tahun')
                                    <li>All Active benefits</li>
                                    <li>Exclusive networking sessions</li>
                                    <li>Premium member recognition</li>
                                    @break
                                @default
                                    <li>Membership benefits</li>
                                    <li>Access to community updates</li>
                                    <li>Priority support access</li>
                            @endswitch
                        </ul>
                    </article>
                @empty
                    <article class="plan">
                        <h4>Membership Options</h4>
                        <p class="price">RM0 <small>/ year</small></p>
                        <ul>
                            <li>No active membership options found.</li>
                        </ul>
                    </article>
                @endforelse
            </div>
        </section>

    </main>

    <footer class="container footer">
        <span>&copy; {{ date('Y') }} BAKIS. All rights reserved.</span>
        <span>Built for members, programs, and community growth.</span>
    </footer>

    <script>
        (function () {
            var root = document.getElementById('ajk-carousel');
            if (!root) return;
            var track = document.getElementById('ajk-track');
            var count = parseInt(root.getAttribute('data-count'), 10) || 0;
            if (!track || count < 2) return;

            var prev = document.getElementById('ajk-prev');
            var next = document.getElementById('ajk-next');
            var progressEl = document.getElementById('ajk-progress');
            var counterEl = document.getElementById('ajk-counter');
            var index = 0;
            var timer = null;
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var AUTO_MS = 4000;

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function startProgress() {
                if (!progressEl || reduceMotion) return;
                progressEl.style.transition = 'none';
                progressEl.style.width = '0%';
                void progressEl.offsetWidth;
                progressEl.style.transition = 'width ' + AUTO_MS + 'ms linear';
                progressEl.style.width = '100%';
            }

            function stopProgress() {
                if (!progressEl) return;
                var w = progressEl.getBoundingClientRect().width;
                var pw = progressEl.parentElement ? progressEl.parentElement.getBoundingClientRect().width : 0;
                progressEl.style.transition = 'none';
                progressEl.style.width = (pw > 0 ? Math.round(w / pw * 100) : 0) + '%';
            }

            function render() {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                if (counterEl) counterEl.innerHTML = pad(index + 1) + '&thinsp;/&thinsp;' + pad(count);
                startProgress();
            }

            function go(i) {
                index = (i + count) % count;
                render();
            }

            function start() {
                if (reduceMotion || timer) return;
                timer = setInterval(function () { go(index + 1); }, AUTO_MS);
                startProgress();
            }

            function stop() {
                if (timer) { clearInterval(timer); timer = null; }
                stopProgress();
            }

            function restart() { stop(); start(); }

            if (prev) prev.addEventListener('click', function () { go(index - 1); restart(); });
            if (next) next.addEventListener('click', function () { go(index + 1); restart(); });

            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);
            root.addEventListener('focusin', stop);
            root.addEventListener('focusout', start);

            render();
            start();
        })();
    </script>
</body>

</html>
