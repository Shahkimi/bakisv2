<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - BAKIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        }

        * { box-sizing: border-box; }

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

        .error-box {
            margin-top: 1rem;
            border-radius: 12px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #7f1d1d;
            padding: 0.8rem 0.9rem;
            font-size: 0.9rem;
        }

        .field { margin-top: 0.85rem; }

        label {
            display: block;
            margin-bottom: 0.45rem;
            font-size: 0.86rem;
            font-weight: 700;
            color: #3e3530;
        }

        input[type="email"], input[type="password"] {
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
    </style>
</head>
<body>
    <section class="card">
        <div class="card-inner">
            <div class="badge">BK</div>
            <h1>Admin Login</h1>
            <p class="subtitle">Halaman ini untuk pentadbir BAKIS sahaja.</p>

            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}">
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
    </section>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function () {
            const button = document.getElementById('submitBtn');
            const text = document.getElementById('buttonText');
            button.disabled = true;
            button.classList.add('is-loading');
            text.textContent = 'Sedang Log Masuk...';
        });
    </script>
</body>
</html>
