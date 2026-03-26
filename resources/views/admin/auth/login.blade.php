<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitai Finance — Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1B3A6B;
            --primary-light: #2D5499;
            --primary-dark: #0D2454;
            --bg: #F0F4F8;
            --surface: #FFFFFF;
            --text-primary: #1B3A6B;
            --text-secondary: #6B7280;
            --border: #E5EAF2;
            --success: #16A34A;
            --danger: #DC2626;
            --accent: #3B82F6;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left Panel */
        .left-panel {
            background: var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-name {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .brand-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
        }

        .hero-text {
            z-index: 1;
        }

        .hero-text h1 {
            font-size: 2.4rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .hero-text h1 span {
            color: rgba(255, 255, 255, 0.45);
            font-weight: 300;
        }

        .hero-text p {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 340px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            z-index: 1;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
        }

        .stat-card .val {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-card .lbl {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Right Panel */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-box h2 {
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.4rem;
            letter-spacing: -0.3px;
        }

        .login-box p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Alerts */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: var(--danger);
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: var(--success);
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 0.9rem;
            color: var(--text-primary);
            background: var(--surface);
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 58, 107, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 15px;
            user-select: none;
        }

        .form-control.has-icon {
            padding-right: 42px;
        }

        /* Remember row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.84rem;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        .forgot-link {
            font-size: 0.84rem;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Submit */
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.2px;
        }

        .btn-login:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
            background: var(--primary-dark);
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 1.5rem 0;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: var(--border);
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .version-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(27, 58, 107, 0.06);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-family: 'JetBrains Mono', monospace;
            margin-top: 1.5rem;
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--success);
            display: inline-block;
        }

        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>

    {{-- Left Brand Panel --}}
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon">💰</div>
            <div>
                <div class="brand-name">Vitai Finance</div>
                <div class="brand-sub">Admin Portal</div>
            </div>
        </div>

        <div class="hero-text">
            <h1>Smart Finance<br><span>Management</span><br>Dashboard</h1>
            <p>Manage users, track transactions, generate reports — all from one powerful admin interface.</p>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="val">2.4K</div>
                <div class="lbl">Users</div>
            </div>
            <div class="stat-card">
                <div class="val">₹84L</div>
                <div class="lbl">Tracked</div>
            </div>
            <div class="stat-card">
                <div class="val">99.9%</div>
                <div class="lbl">Uptime</div>
            </div>
        </div>
    </div>

    {{-- Right Login Panel --}}
    <div class="right-panel">
        <div class="login-box">

            <h2>Welcome back</h2>
            <p>Sign in to your admin account to continue</p>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert-error">
                    ⚠ {{ $errors->first() }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="admin@vitai.com" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password"
                            class="form-control has-icon @error('password') is-invalid @enderror" placeholder="••••••••"
                            autocomplete="current-password" required>
                        <span class="input-icon" onclick="togglePassword()">👁</span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">Sign In to Dashboard</button>
            </form>

            <div style="text-align:center; margin-top: 1rem;">
                <div class="version-badge">
                    <span class="dot"></span>
                    Laravel v13 · Vitai v1.0
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
