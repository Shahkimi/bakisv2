<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon-links')
    <title>BAKIS — Portal Keahlian Warga Hospital Sultanah Bahiyah</title>
    @include('partials.landing-seo-meta')
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --moss: #0B3D33;
            --emerald: #0E7A66;
            --pulse: #16B89A;
            --brass: #C49A4A;
            --brass-deep: #8A6218;
            --brass-dark: #6B4A10;
            --paper: #F2F6F3;
            --surface: #FFFFFF;
            --ink: #16221E;
            --muted: #5C6B64;
            --line: #DCE6E1;
            --font-display: 'Fraunces', Georgia, serif;
            --font-body: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        body {
            font-family: var(--font-body);
            background:
                radial-gradient(circle at 12% 8%, rgba(22, 184, 154, 0.10) 0%, transparent 42%),
                radial-gradient(circle at 88% 12%, rgba(196, 154, 74, 0.10) 0%, transparent 38%),
                linear-gradient(160deg, #f6faf8 0%, var(--paper) 55%, #eef4f0 100%);
        }

        .font-display { font-family: var(--font-display); }
        .font-body { font-family: var(--font-body); }
        .font-500 { font-weight: 500; }
        .font-600 { font-weight: 600; }
        .font-700 { font-weight: 700; }
        .font-800 { font-weight: 800; }

        /* ── Hero heading: scales with viewport, not just vw, so phones aren't stuck at the clamp floor ── */
        .hero-title {
            font-size: clamp(1.5rem, calc(4.2vw + 0.5rem), 3rem);
        }

        /* ── Signature: heartbeat / ECG pulse line ── */
        .pulse-line {
            stroke: var(--pulse);
            stroke-width: 2.4;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 620;
            stroke-dashoffset: 620;
            animation: ecg 3.6s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        }

        .pulse-node {
            fill: var(--brass);
            opacity: 0;
            animation: node-glow 3.6s ease-in-out infinite;
        }

        @keyframes ecg {
            0%   { stroke-dashoffset: 620; }
            55%  { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: 0; }
        }

        @keyframes node-glow {
            0%, 40% { opacity: 0; r: 3; }
            58%     { opacity: 1; r: 5.5; }
            80%     { opacity: 0.9; r: 4; }
            100%    { opacity: 0; r: 3; }
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .rise { animation: rise .6s ease-out both; }
        .rise-delay { animation: rise .6s ease-out .12s both; }

        /* ── AJK carousel track ── */
        .ajk-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }
        .ajk-slide { flex: 0 0 100%; min-width: 100%; }
        .ajk-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--brass-deep), var(--brass));
            border-radius: 999px;
        }

        @media (prefers-reduced-motion: reduce) {
            .pulse-line { animation: none; stroke-dashoffset: 0; }
            .pulse-node { animation: none; opacity: 1; }
            .ajk-track { transition: none; }
            .ajk-progress-fill { transition: none !important; }
            .rise, .rise-delay { animation: none; }
        }
    </style>
</head>

<body class="m-0 min-h-screen flex flex-col text-[var(--ink)] pb-24 lg:pb-0">
    @php
        $brandLogoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    @endphp

    <header class="mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-3 py-3 sm:py-4 shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            @if ($brandLogoUrl)
                <img src="{{ $brandLogoUrl }}" alt="BAKIS"
                    class="w-11 h-11 rounded-xl object-cover ring-1 ring-[var(--line)] shadow-sm shrink-0">
            @else
                <div class="w-11 h-11 rounded-xl grid place-items-center text-white font-display font-semibold text-sm shadow-md shrink-0"
                    style="background: linear-gradient(135deg, var(--emerald), var(--moss));">BK</div>
            @endif
            <div class="leading-tight min-w-0">
                <div class="font-display font-700 text-lg tracking-tight text-[var(--moss)]">BAKIS</div>
                <div class="text-[0.7rem] text-[var(--muted)] font-medium truncate">
                    <span class="sm:hidden">Hospital Sultanah Bahiyah</span>
                    <span class="hidden sm:inline">Badan Kebajikan Islam · Hospital Sultanah Bahiyah</span>
                </div>
            </div>
        </div>
        <nav class="flex items-center gap-2.5 shrink-0">
            <a href="/login"
                class="inline-flex items-center justify-center min-h-[44px] whitespace-nowrap rounded-full px-4 text-sm font-semibold text-[var(--ink)] bg-white/80 border border-[var(--line)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]">Log Masuk</a>
            <a href="/semak"
                class="hidden sm:inline-flex items-center justify-center min-h-[44px] whitespace-nowrap rounded-full px-4 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brass-dark)]"
                style="background: linear-gradient(135deg, var(--brass-deep), var(--brass-dark));">Semak Status Ahli</a>
        </nav>
    </header>

    <main class="mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8 flex flex-col">
        <section class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-5 pt-5">

            <article class="rise relative rounded-[26px] bg-[var(--surface)] border border-[var(--line)] shadow-[0_20px_45px_rgba(11,61,51,0.12)] p-5 sm:p-7 lg:p-8">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-[var(--line)] bg-[var(--paper)] px-3 py-1.5 text-[0.78rem] font-bold text-[var(--emerald)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--brass)]"></span>Portal Rasmi Keahlian
                </span>

                <h1 class="hero-title font-display font-700 mt-4 mb-3 leading-[1.08] tracking-tight text-[var(--moss)]">
                    Menyatukan warga hospital melalui semangat <span class="relative sm:whitespace-nowrap text-[var(--brass-deep)]">kebajikan<span class="absolute left-0 -bottom-0.5 h-[0.18em] w-full rounded-full bg-[rgba(196,154,74,0.4)]" aria-hidden="true"></span></span>
                </h1>

                <p class="m-0 max-w-[56ch] leading-relaxed text-[var(--muted)]">
                    Daftar dan perbaharui keahlian, semak status anda, dan ikuti program kebajikan
                    BAKIS untuk warga Hospital Sultanah Bahiyah — semuanya di satu portal.
                </p>

                {{-- Signature: heartbeat / ECG pulse line --}}
                <svg class="mt-5 w-full h-12" viewBox="0 0 620 60" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
                    <path class="pulse-line"
                        d="M0 30 H150 L168 30 L182 12 L200 48 L216 22 L230 30 H360 L378 30 L392 14 L410 46 L426 24 L440 30 H620" />
                    <circle class="pulse-node" cx="392" cy="14" r="3" />
                </svg>
                <p class="mt-1.5 flex items-start gap-2 text-[0.7rem] font-700 uppercase tracking-[0.14em] text-[var(--muted)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--pulse)] mt-1 shrink-0"></span>Satu denyut, satu kebajikan
                </p>

                <div class="mt-5 grid grid-cols-1 sm:flex sm:flex-wrap gap-2.5">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center min-h-[48px] rounded-full px-5 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]"
                            style="background: linear-gradient(135deg, var(--emerald), var(--moss));">Daftar Keahlian</a>
                    @endif
                    <a href="/semak"
                        class="inline-flex items-center justify-center min-h-[48px] rounded-full px-5 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brass-dark)]"
                        style="background: linear-gradient(135deg, var(--brass-deep), var(--brass-dark));">Semak Status Ahli</a>
                    <a href="/login"
                        class="inline-flex items-center justify-center min-h-[48px] rounded-full px-5 text-sm font-bold text-[var(--emerald)] bg-transparent transition hover:bg-[rgba(14,122,102,0.08)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]">Log Masuk</a>
                </div>

                {{-- Honest value cards (no fabricated metrics) --}}
                <div class="mt-7 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Kebajikan</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Sokongan untuk warga hospital</span>
                    </div>
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Keahlian</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Daftar &amp; perbaharui dalam talian</span>
                    </div>
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Komuniti</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Program sepanjang tahun</span>
                    </div>
                </div>
            </article>

            <aside class="rise-delay grid gap-3 content-start">
                @if ($committee->isNotEmpty())
                    <div class="order-3 lg:order-1 rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]"
                        id="ajk-carousel" data-count="{{ $committee->count() }}"
                        role="region" aria-roledescription="carousel" aria-label="Ahli Jawatankuasa">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--moss) 0%, var(--emerald) 60%, #129b82 100%);">
                            <h2 class="m-0 font-display font-700 text-[1rem]">Ahli Jawatankuasa</h2>
                            <span class="hidden sm:inline text-[0.7rem] uppercase tracking-[0.13em] font-bold text-white/90 whitespace-nowrap">Barisan Kepimpinan</span>
                        </div>
                        <div class="overflow-hidden">
                            <div class="ajk-track" id="ajk-track">
                                @foreach ($committee as $member)
                                    @php($photo = $member->photoUrl())
                                    <div class="ajk-slide grid grid-cols-[92px_1fr]" role="group"
                                        aria-roledescription="slide"
                                        aria-label="{{ $loop->iteration }} daripada {{ $committee->count() }}"
                                        aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                                        <div class="flex items-center justify-center py-4 border-r border-[var(--line)]"
                                            style="background: linear-gradient(170deg, rgba(14,122,102,0.07), rgba(11,61,51,0.03));">
                                            @if ($photo)
                                                <img src="{{ $photo }}" alt="{{ $member->name }}"
                                                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                    @if ($loop->first) fetchpriority="high" @endif
                                                    class="w-[66px] h-[66px] rounded-full object-cover border-[2.5px] border-white shadow-[0_0_0_4px_rgba(14,122,102,0.12),0_5px_18px_rgba(11,61,51,0.25)]">
                                            @else
                                                <div class="w-[66px] h-[66px] rounded-full flex items-center justify-center font-display font-700 text-2xl border-[2.5px] border-white text-[var(--emerald)] bg-[var(--paper)] shadow-[0_0_0_4px_rgba(14,122,102,0.12)]">
                                                    {{ \Illuminate\Support\Str::of($member->name)->substr(0, 1)->upper() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col justify-center gap-1.5 p-4 min-w-0">
                                            <h3 class="m-0 font-display font-700 text-[0.92rem] leading-snug text-[var(--ink)]">{{ $member->name }}</h3>
                                            <span class="inline-block self-start max-w-full truncate rounded-full px-2.5 py-1 text-[0.72rem] font-bold border border-[var(--line)] text-[var(--brass-deep)] bg-[rgba(196,154,74,0.1)]">{{ $member->jawatan }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($committee->count() > 1)
                            <div class="flex items-center gap-1.5 px-3 py-2.5 border-t border-[var(--line)] bg-[rgba(11,61,51,0.02)]">
                                <button type="button" id="ajk-prev" aria-label="Sebelumnya"
                                    class="h-11 w-11 rounded-full border border-[var(--line)] bg-white text-[var(--emerald)] inline-flex items-center justify-center shrink-0 transition hover:bg-[var(--emerald)] hover:text-white hover:border-[var(--emerald)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[var(--emerald)]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <button type="button" id="ajk-toggle" aria-label="Jeda main automatik" aria-pressed="false"
                                    class="h-11 w-11 rounded-full border border-[var(--line)] bg-white text-[var(--emerald)] inline-flex items-center justify-center shrink-0 transition hover:bg-[var(--emerald)] hover:text-white hover:border-[var(--emerald)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[var(--emerald)]">
                                    <svg id="ajk-icon-pause" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 5h4v14H6zM14 5h4v14h-4z" /></svg>
                                    <svg id="ajk-icon-play" class="hidden w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z" /></svg>
                                </button>
                                <div class="flex-1 h-[3px] rounded-full overflow-hidden bg-[rgba(14,122,102,0.13)]">
                                    <div class="ajk-progress-fill" id="ajk-progress"></div>
                                </div>
                                <span class="text-[0.71rem] font-bold text-[var(--muted)] font-display tracking-wider whitespace-nowrap shrink-0" id="ajk-counter" aria-live="off">01&thinsp;/&thinsp;{{ str_pad($committee->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                <button type="button" id="ajk-next" aria-label="Seterusnya"
                                    class="h-11 w-11 rounded-full border border-[var(--line)] bg-white text-[var(--emerald)] inline-flex items-center justify-center shrink-0 transition hover:bg-[var(--emerald)] hover:text-white hover:border-[var(--emerald)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[var(--emerald)]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="order-3 lg:order-1 rounded-2xl p-4 border border-[var(--line)] bg-[var(--surface)]">
                        <h2 class="m-0 mb-1 font-display font-700 text-base text-[var(--moss)]">Ahli Jawatankuasa</h2>
                        <p class="m-0 text-[0.91rem] leading-relaxed text-[var(--muted)]">Senarai barisan kepimpinan BAKIS akan dipaparkan di sini.</p>
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
                    <div id="program-bakis" class="order-2 lg:order-2 scroll-mt-20 rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--brass-dark) 0%, var(--brass-deep) 100%);">
                            <h2 class="m-0 font-display font-700 text-[1rem]">Program BAKIS</h2>
                            <span class="hidden sm:inline text-[0.7rem] uppercase tracking-[0.13em] font-bold text-white/90 whitespace-nowrap">Aktiviti &amp; Acara</span>
                        </div>
                        <div class="flex flex-col">
                            @foreach ($programs as $program)
                                @php($past = $program->isPast())
                                @php($range = trim(($fmtTime($program->waktu_mula) ?: '').(($program->waktu_mula && $program->waktu_tamat) ? ' – ' : '').($fmtTime($program->waktu_tamat) ?: '')))
                                <div class="flex items-start gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.035)] {{ $past ? 'opacity-60' : '' }}">
                                    <div class="shrink-0 w-[46px] h-[46px] rounded-xl flex flex-col items-center justify-center leading-none border {{ $past ? 'border-[var(--line)] text-[var(--muted)]' : 'border-[rgba(14,122,102,0.16)] text-[var(--emerald)]' }}"
                                        style="background: {{ $past ? 'linear-gradient(160deg,#f1efe9,#e7e1d6)' : 'linear-gradient(160deg,#e7f3f1,#d2ebe7)' }};">
                                        <span class="font-display font-700 text-[1.1rem]">{{ $program->tarikh->format('d') }}</span>
                                        <span class="text-[0.7rem] font-bold uppercase tracking-wider mt-0.5">{{ $monthsMy[(int) $program->tarikh->format('n')] }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] line-clamp-2">{{ $program->nama_program }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                            @if ($range !== '')
                                                <p class="m-0 text-[0.74rem] text-[var(--muted)] flex items-center gap-1 whitespace-nowrap">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $range }}
                                                </p>
                                            @endif
                                            @if ($past)
                                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[0.7rem] font-bold border border-[var(--line)] text-[var(--muted)] bg-[rgba(92,107,100,0.1)] whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Telah Dianjurkan</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-[0.7rem] font-bold border border-[rgba(14,122,102,0.18)] text-[var(--emerald)] bg-[rgba(14,122,102,0.12)] whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Akan Datang</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div id="program-bakis" class="order-2 lg:order-2 scroll-mt-20 rounded-2xl p-4 border border-[var(--line)] bg-[var(--surface)]">
                        <h2 class="m-0 mb-1 font-display font-700 text-base text-[var(--moss)]">Program BAKIS</h2>
                        <p class="m-0 text-[0.91rem] leading-relaxed text-[var(--muted)]">Aktiviti, bengkel, dan acara komuniti BAKIS akan dipaparkan di sini.</p>
                    </div>
                @endif

                @if ($perlembagaans->isNotEmpty())
                    <div class="order-4 lg:order-3 rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--moss) 0%, var(--emerald) 70%, #129b82 100%);">
                            <h2 class="m-0 font-display font-700 text-[1rem]">Perlembagaan</h2>
                            <span class="hidden sm:inline text-[0.7rem] uppercase tracking-[0.13em] font-bold text-white/90 whitespace-nowrap">Info Persatuan</span>
                        </div>
                        <div class="flex flex-col">
                            @foreach ($perlembagaans as $doc)
                                @php($url = $doc->publicUrl())
                                <a href="{{ $url ?? '#' }}" @if ($url) target="_blank" rel="noopener" @endif
                                    class="group flex items-center gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.04)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[var(--emerald)] {{ $url ? '' : 'pointer-events-none opacity-60' }}">
                                    <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(14,122,102,0.16)] text-[var(--emerald)]"
                                        style="background: linear-gradient(160deg,#e7f3f1,#d2ebe7);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] line-clamp-2">{{ $doc->tajuk }}</p>
                                        <p class="mt-0.5 mb-0 text-[0.72rem] text-[var(--muted)] flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-2-2m2 2l2-2M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" /></svg>
                                            PDF{{ $doc->humanFileSize() !== '' ? ' · '.$doc->humanFileSize() : '' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[0.7rem] font-bold border border-[rgba(14,122,102,0.18)] text-[var(--emerald)] bg-[rgba(14,122,102,0.1)] transition group-hover:bg-[rgba(14,122,102,0.16)] whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                        <span class="hidden sm:inline">Muat Turun</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Hebahan Penting --}}
                <div id="hebahan-penting" class="order-5 lg:order-4 scroll-mt-20 rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                    <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                        style="background: linear-gradient(135deg, var(--brass-dark) 0%, var(--brass-deep) 100%);">
                        <h2 class="m-0 font-display font-700 text-[1rem]">Hebahan Penting</h2>
                        <span class="hidden sm:inline text-[0.7rem] uppercase tracking-[0.13em] font-bold text-white/90 whitespace-nowrap">Makluman Terkini</span>
                    </div>
                    <div class="flex items-start gap-3 px-3.5 py-3.5">
                        <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(196,154,74,0.25)] text-[var(--brass-deep)]"
                            style="background: linear-gradient(160deg,#fbf5e8,#f3e6c9);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </span>
                        <p class="m-0 text-[0.85rem] leading-relaxed text-[var(--muted)]">Maklumat terkini berkaitan keahlian dan peluang kebajikan akan disampaikan di sini.</p>
                    </div>
                </div>

                {{-- Pilihan Keahlian (yuran & manfaat) --}}
                <div class="order-1 lg:order-5 scroll-mt-20 rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]" id="plans">
                    <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                        style="background: linear-gradient(135deg, var(--emerald) 0%, var(--pulse) 70%, #1fcaa9 100%);">
                        <h2 class="m-0 font-display font-700 text-[1rem]">Pilihan Keahlian</h2>
                        <span class="hidden sm:inline text-[0.7rem] uppercase tracking-[0.13em] font-bold text-white/90 whitespace-nowrap">Yuran &amp; Manfaat</span>
                    </div>
                    <div class="flex flex-col">
                        @forelse($yurans as $yuran)
                            <div class="group flex items-center gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.035)]">
                                <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(14,122,102,0.16)] text-[var(--emerald)]"
                                    style="background: linear-gradient(160deg,#e7f3f1,#d2ebe7);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] line-clamp-2">{{ $yuran->jenis_yuran }}</p>
                                    <p class="mt-0.5 mb-0 text-[0.72rem] text-[var(--muted)] flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Tempoh {{ (int) $yuran->tempoh_tahun }} tahun
                                    </p>
                                </div>
                                <div class="shrink-0 text-right leading-none">
                                    <p class="m-0 font-display font-700 text-[1rem] text-[var(--moss)] whitespace-nowrap">RM {{ number_format((float) $yuran->jumlah, 2) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-3.5 py-4">
                                <p class="m-0 text-[0.85rem] leading-relaxed text-[var(--muted)]">Tiada pilihan keahlian aktif buat masa ini.</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($yurans->isNotEmpty() && Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center gap-1.5 px-3.5 min-h-[48px] py-3 border-t border-[var(--line)] bg-[rgba(14,122,102,0.04)] text-[0.82rem] font-bold text-[var(--emerald)] transition hover:bg-[rgba(14,122,102,0.09)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[var(--emerald)]">
                            Daftar Keahlian
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endif
                </div>
            </aside>
        </section>
    </main>

    <footer class="mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8 border-t border-[var(--line)] pt-2.5 pb-6 lg:pb-4 text-[0.86rem] text-[var(--muted)] flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4 shrink-0">
        <span>&copy; {{ date('Y') }} BAKIS — Badan Kebajikan Islam, Hospital Sultanah Bahiyah.</span>
        <span>Dibina untuk warga, program, dan kebajikan komuniti.</span>
    </footer>

    {{-- Sticky mobile / tablet CTA bar --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 border-t border-[var(--line)] bg-white/95 backdrop-blur-md px-4 py-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] shadow-[0_-8px_30px_rgba(11,61,51,0.12)] flex gap-2.5">
        @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="flex-1 inline-flex items-center justify-center min-h-[52px] rounded-xl text-sm font-bold text-white shadow-md transition active:scale-[0.99] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]"
                style="background: linear-gradient(135deg, var(--emerald), var(--moss));">Daftar Keahlian</a>
        @endif
        <a href="/semak"
            class="flex-1 inline-flex items-center justify-center min-h-[52px] rounded-xl text-sm font-bold text-white shadow-md transition active:scale-[0.99] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brass-dark)]"
            style="background: linear-gradient(135deg, var(--brass-deep), var(--brass-dark));">Semak Status</a>
    </div>

    <script>
        (function () {
            var root = document.getElementById('ajk-carousel');
            if (!root) return;
            var track = document.getElementById('ajk-track');
            var count = parseInt(root.getAttribute('data-count'), 10) || 0;
            if (!track || count < 2) return;

            var slides = track.querySelectorAll('.ajk-slide');
            var prev = document.getElementById('ajk-prev');
            var next = document.getElementById('ajk-next');
            var toggle = document.getElementById('ajk-toggle');
            var iconPause = document.getElementById('ajk-icon-pause');
            var iconPlay = document.getElementById('ajk-icon-play');
            var progressEl = document.getElementById('ajk-progress');
            var counterEl = document.getElementById('ajk-counter');
            var index = 0;
            var timer = null;
            var userPaused = false;
            var mql = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
            var reduceMotion = mql ? mql.matches : false;
            var AUTO_MS = 4000;

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function setProgressBarVisible(visible) {
                if (!progressEl || !progressEl.parentElement) return;
                progressEl.parentElement.style.display = visible ? '' : 'none';
            }

            function onReduceMotionChange(matches) {
                reduceMotion = matches;
                if (reduceMotion) {
                    stop();
                    setProgressBarVisible(false);
                } else {
                    setProgressBarVisible(true);
                    if (!userPaused) start();
                }
            }

            if (mql) {
                if (reduceMotion) setProgressBarVisible(false);
                if (mql.addEventListener) {
                    mql.addEventListener('change', function (e) { onReduceMotionChange(e.matches); });
                }
            }

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

            function updateSlideVisibility() {
                for (var i = 0; i < slides.length; i++) {
                    slides[i].setAttribute('aria-hidden', i === index ? 'false' : 'true');
                }
            }

            function render() {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                if (counterEl) counterEl.innerHTML = pad(index + 1) + '&thinsp;/&thinsp;' + pad(count);
                updateSlideVisibility();
                startProgress();
            }

            function go(i) {
                index = (i + count) % count;
                render();
            }

            function setToggleState(playing) {
                if (!toggle) return;
                toggle.setAttribute('aria-pressed', playing ? 'false' : 'true');
                toggle.setAttribute('aria-label', playing ? 'Jeda main automatik' : 'Main automatik');
                if (iconPause) iconPause.classList.toggle('hidden', !playing);
                if (iconPlay) iconPlay.classList.toggle('hidden', playing);
            }

            function start() {
                if (reduceMotion || timer) return;
                timer = setInterval(function () { go(index + 1); }, AUTO_MS);
                startProgress();
                if (counterEl) counterEl.setAttribute('aria-live', 'off');
                setToggleState(true);
            }

            function stop() {
                if (timer) { clearInterval(timer); timer = null; }
                stopProgress();
                if (counterEl) counterEl.setAttribute('aria-live', 'polite');
                setToggleState(false);
            }

            function restartUnlessPaused() {
                stop();
                if (!userPaused) start();
            }

            if (prev) prev.addEventListener('click', function () { go(index - 1); restartUnlessPaused(); });
            if (next) next.addEventListener('click', function () { go(index + 1); restartUnlessPaused(); });

            if (toggle) {
                toggle.addEventListener('click', function () {
                    userPaused = !userPaused;
                    if (userPaused) { stop(); } else { start(); }
                });
            }

            root.addEventListener('mouseenter', function () { if (!userPaused) stop(); });
            root.addEventListener('mouseleave', function () { if (!userPaused) start(); });
            root.addEventListener('focusin', function () { if (!userPaused) stop(); });
            root.addEventListener('focusout', function () { if (!userPaused) start(); });

            var touchStartX = 0;
            var touchStartY = 0;
            var touching = false;

            track.addEventListener('touchstart', function (e) {
                if (!e.touches || e.touches.length !== 1) return;
                touching = true;
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                if (!userPaused) stop();
            }, { passive: true });

            track.addEventListener('touchend', function (e) {
                if (!touching) return;
                touching = false;
                var touch = e.changedTouches && e.changedTouches[0];
                if (touch) {
                    var dx = touch.clientX - touchStartX;
                    var dy = touch.clientY - touchStartY;
                    if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
                        go(dx < 0 ? index + 1 : index - 1);
                    }
                }
                if (!userPaused) start();
            }, { passive: true });

            render();
            start();
        })();
    </script>
</body>

</html>
