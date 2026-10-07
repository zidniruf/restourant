<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — {{ config('app.name') }}</title>
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background dekorasi */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 60% 40%, rgba(200,142,91,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 20% 80%, rgba(200,142,91,0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Huruf dekorasi */
        body::after {
            content: 'A';
            position: absolute;
            right: -5%;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40vw;
            font-family: Georgia, serif;
            color: rgba(255,255,255,0.015);
            pointer-events: none;
            line-height: 1;
            user-select: none;
        }

        .card {
            width: 100%;
            max-width: 400px;
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-shadow: 0 32px 64px rgba(0,0,0,0.6);
        }

        /* Glow di atas card */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(200,142,91,0.4), transparent);
        }

        /* Header */
        .card-header {
            padding: 36px 36px 28px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #c88e5b, #a06e3b);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 22px;
            box-shadow: 0 8px 24px rgba(200,142,91,0.3);
        }

        .brand-name {
            font-family: Georgia, serif;
            font-size: 20px;
            color: #c88e5b;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .brand-sub {
            font-size: 12px;
            color: #4b5563;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Body */
        .card-body {
            padding: 28px 36px 36px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: #f9fafb;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            font-family: inherit;
        }

        input:focus {
            border-color: rgba(200,142,91,0.5);
            background: rgba(200,142,91,0.04);
        }

        input::placeholder { color: #374151; }

        /* Remember me */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #c88e5b;
            cursor: pointer;
        }

        .remember label {
            font-size: 12px;
            color: #6b7280;
            text-transform: none;
            letter-spacing: 0;
            margin: 0;
            cursor: pointer;
        }

        /* Button */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #c88e5b, #a06e3b);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(200,142,91,0.25);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #d9a06e, #c88e5b);
            box-shadow: 0 6px 20px rgba(200,142,91,0.35);
            transform: translateY(-1px);
        }

        .btn-login:active { transform: translateY(0); }

        /* Error */
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #f87171;
            margin-bottom: 16px;
        }

        /* Footer */
        .card-footer {
            padding: 16px 36px;
            border-top: 1px solid rgba(255,255,255,0.04);
            text-align: center;
            font-size: 11px;
            color: #374151;
        }

        .card-footer span { color: #c88e5b; }
    </style>
</head>
<body>

<div class="card">

    {{-- Header --}}
    <div class="card-header">
        <div class="brand-name">{{ config('app.name') }}</div>
        <div class="brand-sub">Admin Panel</div>
    </div>

    {{-- Body --}}
    <div class="card-body">

        {{-- Error --}}
        @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('filament.admin.auth.login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                Masuk ke Dashboard
            </button>

        </form>
    </div>

    {{-- Footer --}}
    <div class="card-footer">
        © {{ date('Y') }} <span>{{ config('app.name') }}</span>. All rights reserved.
    </div>

</div>

</body>
</html>