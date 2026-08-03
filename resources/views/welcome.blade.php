<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
            --brass-deep: #9C7322;
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

<body class="m-0 min-h-screen overflow-auto flex flex-col text-[var(--ink)] lg:h-screen lg:overflow-hidden">
    @php
        $brandLogoUrl = app(\App\Services\SiteSettingService::class)->logoPublicUrl();
    @endphp

    <header class="w-[min(1180px,calc(100%-2rem))] mx-auto flex flex-wrap items-center justify-between gap-x-4 gap-y-2 py-4 shrink-0">
        <div class="flex items-center gap-3">
            @if ($brandLogoUrl)
                <img src="{{ $brandLogoUrl }}" alt="BAKIS"
                    class="w-11 h-11 rounded-xl object-cover ring-1 ring-[var(--line)] shadow-sm">
            @else
                <div class="w-11 h-11 rounded-xl grid place-items-center text-white font-display font-semibold text-sm shadow-md"
                    style="background: linear-gradient(135deg, var(--emerald), var(--moss));">BK</div>
            @endif
            <div class="leading-tight">
                <div class="font-display font-700 text-lg tracking-tight text-[var(--moss)]">BAKIS</div>
                <div class="text-[0.68rem] text-[var(--muted)] font-medium">Badan Kebajikan Islam · Hospital Sultanah Bahiyah</div>
            </div>
        </div>
        <nav class="flex items-center gap-2.5">
            <a href="/login"
                class="inline-flex items-center whitespace-nowrap rounded-full px-4 py-2.5 text-sm font-semibold text-[var(--ink)] bg-white/80 border border-[var(--line)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]">Log Masuk</a>
            <a href="/semak"
                class="inline-flex items-center whitespace-nowrap rounded-full px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brass)]"
                style="background: linear-gradient(135deg, var(--brass), var(--brass-deep));">Semak Status Ahli</a>
        </nav>
    </header>

    <main class="w-[min(1180px,calc(100%-2rem))] mx-auto flex flex-col lg:flex-1 lg:min-h-0 lg:overflow-hidden">
        <section class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-5 pt-5 items-stretch lg:flex-1 lg:min-h-0">

            <article class="rise self-start relative rounded-[26px] bg-[var(--surface)] border border-[var(--line)] shadow-[0_20px_45px_rgba(11,61,51,0.12)] p-8 lg:max-h-full lg:overflow-hidden lg:overflow-y-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-[var(--line)] bg-[var(--paper)] px-3 py-1.5 text-[0.78rem] font-bold text-[var(--emerald)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--brass)]"></span>Portal Rasmi Keahlian
                </span>

                <h1 class="font-display font-700 mt-4 mb-3 leading-[1.08] tracking-tight text-[var(--moss)] text-[clamp(1.9rem,3.6vw,3rem)]">
                    Menyatukan warga hospital melalui semangat <span class="relative whitespace-nowrap text-[var(--brass-deep)]">kebajikan<span class="absolute left-0 -bottom-0.5 h-[0.18em] w-full rounded-full bg-[rgba(196,154,74,0.4)]" aria-hidden="true"></span></span>
                </h1>

                <p class="m-0 max-w-[56ch] leading-relaxed text-[var(--muted)]">
                    Daftar dan perbaharui keahlian, semak status anda, dan ikuti program kebajikan
                    BAKIS untuk warga Hospital Sultanah Bahiyah — semuanya di satu portal.
                </p>

                {{-- Signature: heartbeat / ECG pulse line --}}
                <svg class="mt-5 w-full h-12" viewBox="0 0 620 60" preserveAspectRatio="none" aria-hidden="true">
                    <path class="pulse-line"
                        d="M0 30 H150 L168 30 L182 12 L200 48 L216 22 L230 30 H360 L378 30 L392 14 L410 46 L426 24 L440 30 H620" />
                    <circle class="pulse-node" cx="392" cy="14" r="3" />
                </svg>
                <p class="mt-1.5 flex items-center gap-2 text-[0.66rem] font-700 uppercase tracking-[0.2em] text-[var(--muted)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--pulse)]"></span>Satu denyut, satu kebajikan
                </p>

                <div class="mt-5 flex flex-wrap gap-2.5">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center rounded-full px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]"
                            style="background: linear-gradient(135deg, var(--emerald), var(--moss));">Daftar Keahlian</a>
                    @endif
                    <a href="/semak"
                        class="inline-flex items-center rounded-full px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brass)]"
                        style="background: linear-gradient(135deg, var(--brass), var(--brass-deep));">Semak Status Ahli</a>
                    <a href="/login"
                        class="inline-flex items-center rounded-full px-5 py-3 text-sm font-bold text-[var(--ink)] bg-white border border-[var(--line)] transition hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--emerald)]">Log Masuk</a>
                </div>

                {{-- Honest value cards (no fabricated metrics) --}}
                <div class="mt-7 grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Kebajikan</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Sokongan untuk warga hospital</span>
                    </div>
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Keahlian</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Daftar &amp; perbaharui dalam talian</span>
                    </div>
                    <div class="group rounded-2xl border border-[var(--line)] bg-[var(--paper)] p-3.5 transition hover:-translate-y-0.5 hover:border-[rgba(14,122,102,0.3)] hover:shadow-[0_10px_24px_rgba(11,61,51,0.09)]">
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-lg mb-2.5 text-[var(--emerald)] bg-white border border-[var(--line)] transition group-hover:border-[rgba(14,122,102,0.3)]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </span>
                        <strong class="block font-display font-600 text-[var(--moss)] text-[1.02rem]">Komuniti</strong>
                        <span class="text-[0.84rem] text-[var(--muted)] leading-snug">Program sepanjang tahun</span>
                    </div>
                </div>
            </article>

            <aside class="rise-delay grid gap-3 content-start lg:overflow-y-auto lg:min-h-0">
                @if ($committee->isNotEmpty())
                    <div class="rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]"
                        id="ajk-carousel" data-count="{{ $committee->count() }}">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--moss) 0%, var(--emerald) 60%, #129b82 100%);">
                            <h3 class="m-0 font-display font-700 text-[0.95rem]">Ahli Jawatankuasa</h3>
                            <span class="text-[0.62rem] uppercase tracking-[0.13em] font-bold text-white/60 whitespace-nowrap">Barisan Kepimpinan</span>
                        </div>
                        <div class="overflow-hidden">
                            <div class="ajk-track" id="ajk-track">
                                @foreach ($committee as $member)
                                    @php($photo = $member->photoUrl())
                                    <div class="ajk-slide grid grid-cols-[92px_1fr]">
                                        <div class="flex items-center justify-center py-4 border-r border-[var(--line)]"
                                            style="background: linear-gradient(170deg, rgba(14,122,102,0.07), rgba(11,61,51,0.03));">
                                            @if ($photo)
                                                <img src="{{ $photo }}" alt="{{ $member->name }}" loading="lazy"
                                                    class="w-[66px] h-[66px] rounded-full object-cover border-[2.5px] border-white shadow-[0_0_0_4px_rgba(14,122,102,0.12),0_5px_18px_rgba(11,61,51,0.25)]">
                                            @else
                                                <div class="w-[66px] h-[66px] rounded-full flex items-center justify-center font-display font-700 text-2xl border-[2.5px] border-white text-[var(--emerald)] bg-[var(--paper)] shadow-[0_0_0_4px_rgba(14,122,102,0.12)]">
                                                    {{ \Illuminate\Support\Str::of($member->name)->substr(0, 1)->upper() }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col justify-center gap-1.5 p-4 min-w-0">
                                            <h4 class="m-0 font-display font-700 text-[0.92rem] leading-snug text-[var(--ink)]">{{ $member->name }}</h4>
                                            <span class="inline-flex self-start max-w-full items-center rounded-full px-2.5 py-1 text-[0.72rem] font-bold whitespace-nowrap overflow-hidden text-ellipsis border border-[var(--line)] text-[var(--brass-deep)] bg-[rgba(196,154,74,0.1)]">{{ $member->jawatan }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($committee->count() > 1)
                            <div class="flex items-center gap-2.5 px-3.5 py-2.5 border-t border-[var(--line)] bg-[rgba(11,61,51,0.02)]">
                                <button type="button" id="ajk-prev" aria-label="Sebelumnya"
                                    class="w-[26px] h-[26px] rounded-full border border-[var(--line)] bg-white text-[var(--emerald)] inline-flex items-center justify-center shrink-0 transition hover:bg-[var(--emerald)] hover:text-white hover:border-[var(--emerald)] hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[var(--emerald)]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <div class="flex-1 h-[3px] rounded-full overflow-hidden bg-[rgba(14,122,102,0.13)]">
                                    <div class="ajk-progress-fill" id="ajk-progress"></div>
                                </div>
                                <span class="text-[0.71rem] font-bold text-[var(--muted)] font-display tracking-wider whitespace-nowrap shrink-0" id="ajk-counter">01&thinsp;/&thinsp;{{ str_pad($committee->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                <button type="button" id="ajk-next" aria-label="Seterusnya"
                                    class="w-[26px] h-[26px] rounded-full border border-[var(--line)] bg-white text-[var(--emerald)] inline-flex items-center justify-center shrink-0 transition hover:bg-[var(--emerald)] hover:text-white hover:border-[var(--emerald)] hover:scale-110 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-[var(--emerald)]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-2xl p-4 border border-[var(--line)] bg-[var(--surface)]">
                        <h3 class="m-0 mb-1 font-display font-700 text-base text-[var(--moss)]">Ahli Jawatankuasa</h3>
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
                    <div id="program-bakis" class="rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--brass-deep) 0%, var(--brass) 70%, #d6ad5e 100%);">
                            <h3 class="m-0 font-display font-700 text-[0.95rem]">Program BAKIS</h3>
                            <span class="text-[0.62rem] uppercase tracking-[0.13em] font-bold text-white/70 whitespace-nowrap">Aktiviti &amp; Acara</span>
                        </div>
                        <div class="flex flex-col">
                            @foreach ($programs as $program)
                                @php($past = $program->isPast())
                                @php($range = trim(($fmtTime($program->waktu_mula) ?: '').(($program->waktu_mula && $program->waktu_tamat) ? ' – ' : '').($fmtTime($program->waktu_tamat) ?: '')))
                                <div class="flex items-center gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.035)] {{ $past ? 'opacity-60' : '' }}">
                                    <div class="shrink-0 w-[46px] h-[46px] rounded-xl flex flex-col items-center justify-center leading-none border {{ $past ? 'border-[var(--line)] text-[var(--muted)]' : 'border-[rgba(14,122,102,0.16)] text-[var(--emerald)]' }}"
                                        style="background: {{ $past ? 'linear-gradient(160deg,#f1efe9,#e7e1d6)' : 'linear-gradient(160deg,#e7f3f1,#d2ebe7)' }};">
                                        <span class="font-display font-700 text-[1.1rem]">{{ $program->tarikh->format('d') }}</span>
                                        <span class="text-[0.58rem] font-bold uppercase tracking-wider mt-0.5">{{ $monthsMy[(int) $program->tarikh->format('n')] }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] whitespace-nowrap overflow-hidden text-ellipsis">{{ $program->nama_program }}</p>
                                        @if ($range !== '')
                                            <p class="mt-0.5 mb-0 text-[0.74rem] text-[var(--muted)] flex items-center gap-1">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $range }}
                                            </p>
                                        @endif
                                    </div>
                                    @if ($past)
                                        <span class="shrink-0 inline-flex items-center gap-1 rounded-full px-2 py-1 text-[0.64rem] font-bold border border-[var(--line)] text-[var(--muted)] bg-[rgba(92,107,100,0.1)] whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Telah Dianjurkan</span>
                                    @else
                                        <span class="shrink-0 inline-flex items-center gap-1 rounded-full px-2 py-1 text-[0.64rem] font-bold border border-[rgba(14,122,102,0.18)] text-[var(--emerald)] bg-[rgba(14,122,102,0.12)] whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Akan Datang</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div id="program-bakis" class="rounded-2xl p-4 border border-[var(--line)] bg-[var(--surface)]">
                        <h3 class="m-0 mb-1 font-display font-700 text-base text-[var(--moss)]">Program BAKIS</h3>
                        <p class="m-0 text-[0.91rem] leading-relaxed text-[var(--muted)]">Aktiviti, bengkel, dan acara komuniti BAKIS akan dipaparkan di sini.</p>
                    </div>
                @endif

                @if ($perlembagaans->isNotEmpty())
                    <div class="rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                        <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                            style="background: linear-gradient(135deg, var(--moss) 0%, var(--emerald) 70%, #129b82 100%);">
                            <h3 class="m-0 font-display font-700 text-[0.95rem]">Perlembagaan</h3>
                            <span class="text-[0.62rem] uppercase tracking-[0.13em] font-bold text-white/70 whitespace-nowrap">Info Persatuan</span>
                        </div>
                        <div class="flex flex-col">
                            @foreach ($perlembagaans as $doc)
                                @php($url = $doc->publicUrl())
                                <a href="{{ $url ?? '#' }}" @if ($url) target="_blank" rel="noopener" @endif
                                    class="group flex items-center gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.04)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[var(--emerald)] {{ $url ? '' : 'pointer-events-none opacity-60' }}">
                                    <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(14,122,102,0.16)] text-[var(--emerald)]"
                                        style="background: linear-gradient(160deg,#e7f3f1,#d2ebe7);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] whitespace-nowrap overflow-hidden text-ellipsis">{{ $doc->tajuk }}</p>
                                        <p class="mt-0.5 mb-0 text-[0.72rem] text-[var(--muted)] flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-2-2m2 2l2-2M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" /></svg>
                                            PDF{{ $doc->humanFileSize() !== '' ? ' · '.$doc->humanFileSize() : '' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[0.68rem] font-bold border border-[rgba(14,122,102,0.18)] text-[var(--emerald)] bg-[rgba(14,122,102,0.1)] transition group-hover:bg-[rgba(14,122,102,0.16)] whitespace-nowrap">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                                        Muat Turun
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Hebahan Penting --}}
                <div id="hebahan-penting" class="rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]">
                    <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                        style="background: linear-gradient(135deg, var(--brass-deep) 0%, var(--brass) 70%, #d6ad5e 100%);">
                        <h3 class="m-0 font-display font-700 text-[0.95rem]">Hebahan Penting</h3>
                        <span class="text-[0.62rem] uppercase tracking-[0.13em] font-bold text-white/70 whitespace-nowrap">Makluman Terkini</span>
                    </div>
                    <div class="flex items-start gap-3 px-3.5 py-3.5">
                        <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(196,154,74,0.25)] text-[var(--brass-deep)]"
                            style="background: linear-gradient(160deg,#fbf5e8,#f3e6c9);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </span>
                        <p class="m-0 text-[0.85rem] leading-relaxed text-[var(--muted)]">Maklumat terkini berkaitan keahlian dan peluang kebajikan akan disampaikan di sini.</p>
                    </div>
                </div>

                {{-- Pilihan Keahlian (yuran & manfaat) --}}
                <div class="rounded-2xl border border-[var(--line)] bg-[var(--surface)] overflow-hidden shadow-[0_2px_20px_rgba(11,61,51,0.08)]" id="plans">
                    <div class="relative overflow-hidden flex items-center justify-between gap-2 px-4 py-3 text-white"
                        style="background: linear-gradient(135deg, var(--emerald) 0%, var(--pulse) 70%, #1fcaa9 100%);">
                        <h3 class="m-0 font-display font-700 text-[0.95rem]">Pilihan Keahlian</h3>
                        <span class="text-[0.62rem] uppercase tracking-[0.13em] font-bold text-white/70 whitespace-nowrap">Yuran &amp; Manfaat</span>
                    </div>
                    <div class="flex flex-col">
                        @forelse($yurans as $yuran)
                            <div class="group flex items-center gap-3 px-3.5 py-3 border-b border-[var(--line)] last:border-b-0 transition hover:bg-[rgba(14,122,102,0.035)]">
                                <span class="shrink-0 w-[42px] h-[42px] rounded-xl flex items-center justify-center border border-[rgba(14,122,102,0.16)] text-[var(--emerald)]"
                                    style="background: linear-gradient(160deg,#e7f3f1,#d2ebe7);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="m-0 font-display font-600 text-[0.86rem] leading-snug text-[var(--ink)] whitespace-nowrap overflow-hidden text-ellipsis">{{ $yuran->jenis_yuran }}</p>
                                    <p class="mt-0.5 mb-0 text-[0.72rem] text-[var(--muted)] flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Tempoh {{ (int) $yuran->tempoh_tahun }} tahun
                                    </p>
                                </div>
                                <div class="shrink-0 text-right leading-none">
                                    <p class="m-0 font-display font-700 text-[1rem] text-[var(--moss)] whitespace-nowrap">RM {{ number_format((float) $yuran->jumlah, 2) }}</p>
                                    <p class="mt-1 mb-0 text-[0.62rem] font-semibold uppercase tracking-wider text-[var(--muted)]">/ {{ (int) $yuran->tempoh_tahun }} thn</p>
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
                            class="flex items-center justify-center gap-1.5 px-3.5 py-3 border-t border-[var(--line)] bg-[rgba(14,122,102,0.04)] text-[0.82rem] font-bold text-[var(--emerald)] transition hover:bg-[rgba(14,122,102,0.09)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[var(--emerald)]">
                            Daftar Keahlian
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @endif
                </div>
            </aside>
        </section>
    </main>

    <footer class="w-[min(1180px,calc(100%-2rem))] mx-auto border-t border-[var(--line)] pt-2.5 pb-8 lg:pb-3 text-[0.86rem] text-[var(--muted)] flex justify-between gap-4 flex-wrap shrink-0">
        <span>&copy; {{ date('Y') }} BAKIS — Badan Kebajikan Islam, Hospital Sultanah Bahiyah.</span>
        <span>Dibina untuk warga, program, dan kebajikan komuniti.</span>
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
