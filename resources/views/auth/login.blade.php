<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.favicon-links')
    <title>Admin Login - BAKIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @endif

    <style>
        :root {
            --ink: #181513;
            --muted: #5f554f;
            --surface: #fffdfa;
            --line: #decfbe;
            --brand: #b75817;
            --brand-deep: #7d3506;
            --shadow: 0 20px 45px rgba(125, 53, 6, 0.15);
            --success-bg: #ecfdf5;
            --success-border: #a7f3d0;
            --success-text: #065f46;
            --auth-panel-dur-enter: 360ms;
            --auth-panel-dur-leave: 300ms;
            --auth-panel-enter-delay: 75ms;
            --auth-panel-ease: cubic-bezier(0.4, 0, 0.2, 1);
            --auth-card-shell-dur: 400ms;
            --auth-tab-dur: 220ms;
        }

        @media (prefers-reduced-motion: reduce) {
            :root {
                --auth-panel-dur-enter: 1ms;
                --auth-panel-dur-leave: 1ms;
                --auth-panel-enter-delay: 0ms;
                --auth-card-shell-dur: 1ms;
                --auth-tab-dur: 1ms;
            }
        }

        * { box-sizing: border-box; }

        [x-cloak] { display: none !important; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            display: grid;
            place-items: center;
            padding: 1rem;
            background:
                radial-gradient(circle at 14% 12%, #ffd79e 0%, transparent 40%),
                radial-gradient(circle at 85% 15%, #f6b98e 0%, transparent 32%),
                linear-gradient(155deg, #faf4e8 0%, #f2e9da 54%, #efdfce 100%);
        }

        .card {
            width: min(440px, 100%);
            background: var(--surface);
            border: 1px solid rgba(125, 53, 6, 0.15);
            border-radius: 20px;
            box-shadow: var(--shadow);
            padding: 1.3rem;
        }

        .card-inner {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1.2rem;
            background: #fff;
        }

        .badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-family: "Sora", sans-serif;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            margin-bottom: 0.8rem;
        }

        h1 {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 1.3rem;
        }

        .subtitle {
            margin: 0.35rem 0 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .notice-success {
            margin-top: 1rem;
            border-radius: 12px;
            border: 1px solid var(--success-border);
            background: var(--success-bg);
            color: var(--success-text);
            padding: 0.8rem 0.9rem;
            font-size: 0.9rem;
        }

        .error-box {
            margin-top: 1rem;
            border-radius: 12px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #7f1d1d;
            padding: 0.8rem 0.9rem;
            font-size: 0.9rem;
        }

        .tabs {
            margin-top: 1rem;
            display: flex;
            padding: 4px;
            border-radius: 12px;
            background: rgba(125, 53, 6, 0.08);
            gap: 4px;
        }

        .tab-btn {
            flex: 1;
            border: none;
            border-radius: 10px;
            padding: 0.55rem 0.65rem;
            font: inherit;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            color: #6b5d54;
            background: transparent;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .tab-btn:hover {
            color: var(--brand-deep);
        }

        .tab-btn.is-active {
            background: #fff;
            color: var(--brand-deep);
            box-shadow: 0 2px 8px rgba(125, 53, 6, 0.12);
        }

        .field { margin-top: 0.85rem; }

        label {
            display: block;
            margin-bottom: 0.45rem;
            font-size: 0.86rem;
            font-weight: 700;
            color: #3e3530;
        }

        .hint {
            margin: 0.25rem 0 0;
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.35;
        }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            border: 1px solid #d8ccc2;
            border-radius: 12px;
            background: #fff;
            padding: 0.75rem 0.85rem;
            font: inherit;
            color: var(--ink);
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        input:focus {
            outline: none;
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.18);
        }

        .field-error {
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: #b91c1c;
        }

        .remember {
            margin-top: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.86rem;
            color: #544b45;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #b75817;
        }

        .submit {
            margin-top: 1rem;
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 0.82rem;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-deep));
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: filter .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit:hover { filter: brightness(1.03); }
        .submit:disabled { opacity: 0.75; cursor: wait; }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        .submit.is-loading .spinner { display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .auth-panels-shell {
            margin-top: 1rem;
            overflow: hidden;
            min-height: 17.5rem;
            transition: height var(--auth-card-shell-dur) var(--auth-panel-ease);
        }

        .auth-panels-shell.is-shell-measured {
            min-height: 0;
        }

        .auth-panels {
            position: relative;
            margin-top: 0;
            min-height: 0;
        }

        .auth-panel {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            width: 100%;
            min-width: 0;
            z-index: 1;
        }

        .auth-panel.auth-panel--stack {
            z-index: 2;
        }

        .auth-t-enter {
            transition-property: opacity, transform;
            transition-duration: var(--auth-panel-dur-enter);
            transition-timing-function: var(--auth-panel-ease);
            transition-delay: var(--auth-panel-enter-delay);
        }

        .auth-t-enter-start {
            opacity: 0;
            transform: translateY(4px) scale(0.995);
        }

        .auth-t-enter-end {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .auth-t-leave {
            transition-property: opacity, transform;
            transition-duration: var(--auth-panel-dur-leave);
            transition-timing-function: var(--auth-panel-ease);
            transition-delay: 0ms;
        }

        .auth-t-leave-start {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .auth-t-leave-end {
            opacity: 0;
            transform: translateY(-4px) scale(0.995);
        }
    </style>
</head>
<body>
    <section class="card" x-data="{
        tab: '{{ old('no_kp') ? 'forgot' : 'login' }}',
        authPanelShellHeight: 0,
        shellReflowTimer: null,
        init() {
            this.$watch('tab', () => this.queueAuthShellReflow());
            this.$nextTick(() => {
                this.syncAuthShellHeight();
                if (this.tab === 'forgot') {
                    this.$refs.noKpInput?.focus();
                }
            });
        },
        queueAuthShellReflow() {
            this.syncAuthShellHeight();
            clearTimeout(this.shellReflowTimer);
            this.shellReflowTimer = setTimeout(() => this.syncAuthShellHeight(), 420);
        },
        syncAuthShellHeight() {
            this.$nextTick(() => {
                const login = this.$refs.panelLogin;
                const forgot = this.$refs.panelForgot;
                const h = Math.max(login?.offsetHeight ?? 0, forgot?.offsetHeight ?? 0);
                this.authPanelShellHeight = h > 0 ? Math.ceil(h) : 0;
            });
        }
    }">
        <div class="card-inner">
            <div class="badge">BK</div>
            <h1>Admin Login</h1>
            <p class="subtitle">Halaman ini untuk pentadbir BAKIS sahaja.</p>

            @if (session('status'))
                <div class="notice-success" role="status">{{ session('status') }}</div>
            @endif

            @if (session('reset_link_notice'))
                <div class="notice-success" role="status">{{ session('reset_link_notice') }}</div>
            @endif

            @if ($errors->any() && ! $errors->has('no_kp') && ! $errors->has('email') && ! $errors->has('password'))
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <div class="tabs" role="tablist" aria-label="Pilih mod">
                <button type="button" id="tab-login" role="tab" :aria-selected="tab === 'login'" aria-controls="panel-login"
                    class="tab-btn"
                    :class="{ 'is-active': tab === 'login' }"
                    @click="tab = 'login'; $nextTick(() => $refs.emailInput?.focus())">Log masuk</button>
                <button type="button" id="tab-forgot" role="tab" :aria-selected="tab === 'forgot'" aria-controls="panel-forgot"
                    class="tab-btn"
                    :class="{ 'is-active': tab === 'forgot' }"
                    @click="tab = 'forgot'; $nextTick(() => $refs.noKpInput?.focus())">Lupa kata laluan</button>
            </div>

            <div
                class="auth-panels-shell"
                :class="{ 'is-shell-measured': authPanelShellHeight > 0 }"
                :style="authPanelShellHeight > 0 ? { height: authPanelShellHeight + 'px' } : {}">
            <div class="auth-panels">
            <div id="panel-login" role="tabpanel" aria-labelledby="tab-login" class="auth-panel"
                x-ref="panelLogin"
                :class="{ 'auth-panel--stack': tab === 'login' }"
                x-show="tab === 'login'"
                x-transition:enter="auth-t-enter"
                x-transition:enter-start="auth-t-enter-start"
                x-transition:enter-end="auth-t-enter-end"
                x-transition:leave="auth-t-leave"
                x-transition:leave-start="auth-t-leave-start"
                x-transition:leave-end="auth-t-leave-end"
                x-cloak>
                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input x-ref="emailInput" id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}">
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="remember" for="remember">
                        <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>

                    <button type="submit" class="submit" id="submitBtn">
                        <span id="buttonText">Log Masuk</span>
                        <span class="spinner" id="loadingSpinner"></span>
                    </button>
                </form>
            </div>

            <div id="panel-forgot" role="tabpanel" aria-labelledby="tab-forgot" class="auth-panel"
                x-ref="panelForgot"
                :class="{ 'auth-panel--stack': tab === 'forgot' }"
                x-show="tab === 'forgot'"
                x-transition:enter="auth-t-enter"
                x-transition:enter-start="auth-t-enter-start"
                x-transition:enter-end="auth-t-enter-end"
                x-transition:leave="auth-t-leave"
                x-transition:leave-start="auth-t-leave-start"
                x-transition:leave-end="auth-t-leave-end"
                x-cloak>
                <form action="{{ route('password.forgot-by-kp') }}" method="POST" id="forgotForm">
                    @csrf
                    <div class="field">
                        <label for="no_kp">No. Kad Pengenalan (12 digit)</label>
                        <input x-ref="noKpInput" id="no_kp" name="no_kp" type="text" inputmode="numeric" pattern="\d{12}" maxlength="12" autocomplete="off"
                            placeholder="Contoh: 900101011234"
                            value="{{ old('no_kp') }}">
                        <p class="hint">Masukkan 12 digit tanpa sempang. Kami akan hantar pautan tetapan semula ke e-mel berdaftar.</p>
                        @error('no_kp')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="submit" id="forgotSubmitBtn">
                        <span id="forgotButtonText">Hantar pautan reset</span>
                        <span class="spinner" id="forgotSpinner"></span>
                    </button>
                </form>
            </div>
            </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('loginForm')?.addEventListener('submit', function () {
            const button = document.getElementById('submitBtn');
            const text = document.getElementById('buttonText');
            button.disabled = true;
            button.classList.add('is-loading');
            text.textContent = 'Sedang Log Masuk...';
        });

        document.getElementById('forgotForm')?.addEventListener('submit', function () {
            const button = document.getElementById('forgotSubmitBtn');
            const text = document.getElementById('forgotButtonText');
            button.disabled = true;
            button.classList.add('is-loading');
            text.textContent = 'Menghantar...';
        });
    </script>
</body>
</html>
