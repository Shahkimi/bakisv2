<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 15% 10%, #ffd79e 0%, transparent 40%),
                radial-gradient(circle at 80% 15%, #f6b98e 0%, transparent 35%),
                linear-gradient(155deg, #f9f4e9 0%, #f2eadc 50%, #efe3d5 100%);
            min-height: 100vh;
        }

        .container {
            width: min(1120px, calc(100% - 2rem));
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 0;
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

        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 1.4rem;
            padding: 2rem 0 1rem;
            align-items: stretch;
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

        .section {
            margin-top: 1.2rem;
            padding-bottom: 2.6rem;
        }

        .section-title {
            font-family: "Sora", sans-serif;
            margin: 0 0 0.8rem;
            font-size: clamp(1.4rem, 2.2vw, 1.9rem);
        }

        .plans {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .plan {
            background: rgba(255, 253, 249, 0.92);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 1rem;
        }

        .plan h4 {
            margin: 0 0 0.5rem;
            font-family: "Sora", sans-serif;
            font-size: 1rem;
        }

        .price {
            margin: 0 0 0.65rem;
            font-size: 1.7rem;
            font-family: "Sora", sans-serif;
        }

        .price small {
            font-family: "Manrope", sans-serif;
            font-size: 0.92rem;
            color: var(--muted);
            font-weight: 600;
        }

        .plan ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 0.4rem;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .plan li::before {
            content: "* ";
            color: var(--accent);
            font-weight: 800;
        }

        .footer {
            border-top: 1px solid rgba(95, 85, 79, 0.2);
            padding: 1.3rem 0 2rem;
            color: var(--muted);
            font-size: 0.88rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
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

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .plans {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 720px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .plans,
            .stats {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                padding: 1.2rem;
            }

            .quick-access-row .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header class="container topbar">
        <div class="brand">
            <div class="brand-badge">BK</div>
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
                <div class="mini-card">
                    <h3>Member Dashboard</h3>
                    <p>Track renewals, update your details, and access your membership card in one place.</p>
                </div>
                <div class="mini-card">
                    <h3>Exclusive Programs</h3>
                    <p>Get early access to selected BAKIS activities, workshops, and collaboration events.</p>
                </div>
                <div class="mini-card">
                    <h3>Priority Announcements</h3>
                    <p>Receive timely updates about membership matters and upcoming opportunities.</p>
                </div>
            </aside>
        </section>

        <section class="section" id="plans">
            <h2 class="section-title">Membership Options</h2>
            <div class="plans">
                <article class="plan">
                    <h4>Basic Member</h4>
                    <p class="price">RM30 <small>/ year</small></p>
                    <ul>
                        <li>Digital member profile</li>
                        <li>Community event notifications</li>
                        <li>Standard support access</li>
                    </ul>
                </article>
                <article class="plan">
                    <h4>Active Member</h4>
                    <p class="price">RM80 <small>/ year</small></p>
                    <ul>
                        <li>All Basic benefits</li>
                        <li>Priority event registration</li>
                        <li>Discounted selected programs</li>
                    </ul>
                </article>
                <article class="plan">
                    <h4>Premium Member</h4>
                    <p class="price">RM150 <small>/ year</small></p>
                    <ul>
                        <li>All Active benefits</li>
                        <li>Exclusive networking sessions</li>
                        <li>Premium member recognition</li>
                    </ul>
                </article>
            </div>
        </section>
    </main>

    <footer class="container footer">
        <span>&copy; {{ date('Y') }} BAKIS. All rights reserved.</span>
        <span>Built for members, programs, and community growth.</span>
    </footer>
</body>

</html>
