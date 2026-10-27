<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Gym RD Sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --carbon: #1A1A1A;
            --oliva: #A3B899;
            --oliva-hover: #8FA784;
            --oliva-dark: #374631;
            --hueso: #F5F5F5;
            --plateado: #D1D5DB;
            --plateado-light: #E5E7EB;
            --texto-muted: #6B7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--hueso);
            color: var(--carbon);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 1.5rem;
        }

        /* Ambient subtle shapes */
        .ambient-shape-1 {
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(163, 184, 153, 0.22) 0%, rgba(245, 245, 245, 0) 70%);
            top: -120px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-shape-2 {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 26, 26, 0.05) 0%, rgba(245, 245, 245, 0) 70%);
            bottom: -100px;
            left: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        /* Card Container */
        .card {
            background: #FFFFFF;
            border: 1px solid var(--plateado);
            border-radius: 1.25rem;
            padding: 2.25rem 2rem;
            box-shadow: 0 10px 25px -5px rgba(26, 26, 26, 0.06), 0 8px 10px -6px rgba(26, 26, 26, 0.04);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card:hover {
            border-color: var(--oliva);
            box-shadow: 0 20px 30px -10px rgba(26, 26, 26, 0.08), 0 0 0 1px var(--oliva);
        }

        /* Brand Header */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(163, 184, 153, 0.18);
            border: 1px solid var(--oliva);
            color: var(--oliva-dark);
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.85rem;
        }

        .brand-logo-icon {
            width: 2.75rem;
            height: 2.75rem;
            background-color: var(--carbon);
            color: var(--oliva);
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 10px rgba(26, 26, 26, 0.15);
        }

        .brand-title {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--carbon);
        }

        .brand-title span {
            color: var(--oliva-dark);
            border-bottom: 3px solid var(--oliva);
            padding-bottom: 2px;
        }

        .brand-subtitle {
            color: var(--texto-muted);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--carbon);
            margin-bottom: 0.45rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--texto-muted);
            width: 1.25rem;
            height: 1.25rem;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-input {
            width: 100%;
            background-color: #FFFFFF;
            border: 1.5px solid var(--plateado);
            color: var(--carbon);
            font-size: 0.9375rem;
            padding: 0.75rem 1rem 0.75rem 2.85rem;
            border-radius: 0.65rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .form-input:focus {
            border-color: var(--oliva);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(163, 184, 153, 0.35);
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--oliva-dark);
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: var(--texto-muted);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--carbon);
        }

        /* Remember & Options */
        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -0.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.8125rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--carbon);
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox {
            appearance: none;
            width: 1.15rem;
            height: 1.15rem;
            background-color: #FFFFFF;
            border: 1.5px solid var(--plateado);
            border-radius: 0.3rem;
            cursor: pointer;
            position: relative;
            outline: none;
            transition: all 0.2s ease;
        }

        .remember-checkbox:checked {
            background-color: var(--oliva);
            border-color: var(--oliva);
        }

        .remember-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid var(--carbon);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Action CTA Button */
        .btn-submit {
            width: 100%;
            background-color: var(--oliva);
            color: var(--carbon);
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            padding: 0.85rem 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 0.65rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(163, 184, 153, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background-color: var(--oliva-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(163, 184, 153, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 0.65rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }

        .alert-error {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
        }

        .alert-success {
            background: rgba(163, 184, 153, 0.25);
            border: 1px solid var(--oliva);
            color: var(--oliva-dark);
        }

        /* Demo credentials box */
        .demo-box {
            margin-top: 1.75rem;
            padding: 0.85rem;
            background: var(--hueso);
            border: 1px solid var(--plateado);
            border-radius: 0.65rem;
            font-size: 0.75rem;
            color: var(--carbon);
        }

        .demo-header {
            font-weight: 700;
            color: var(--carbon);
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .demo-items {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .demo-pill {
            cursor: pointer;
            padding: 0.45rem 0.65rem;
            background: #FFFFFF;
            border: 1px solid var(--plateado);
            border-radius: 0.45rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .demo-pill:hover {
            border-color: var(--oliva);
            background: rgba(163, 184, 153, 0.12);
        }

        .demo-pill code {
            background: var(--hueso);
            padding: 0.1rem 0.35rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            border: 1px solid var(--plateado);
        }

        /* Footer */
        .card-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--plateado-light);
            font-size: 0.85rem;
            color: var(--texto-muted);
        }

        .card-footer a {
            color: var(--carbon);
            font-weight: 700;
            text-decoration: underline;
            text-decoration-color: var(--oliva);
            text-underline-offset: 3px;
            margin-left: 0.25rem;
            transition: color 0.2s ease;
        }

        .card-footer a:hover {
            color: var(--oliva-dark);
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 1.25rem;
            color: var(--texto-muted);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-home:hover {
            color: var(--carbon);
        }
    </style>
</head>
<body>
    <div class="ambient-shape-1"></div>
    <div class="ambient-shape-2"></div>

    <div class="container">
        <div class="card">
            <!-- Brand Header -->
            <div class="brand-header">
                <div class="brand-logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6.5 6.5 11 11"></path>
                        <path d="m21 21-1-1"></path>
                        <path d="m3 3 1 1"></path>
                        <path d="m18 22 4-4"></path>
                        <path d="m2 6 4-4"></path>
                        <path d="m3 10 7-7"></path>
                        <path d="m14 21 7-7"></path>
                    </svg>
                </div>
                <div style="margin-bottom: 0.35rem;">
                    <div class="brand-badge">Sistema de Acceso</div>
                </div>
                <h1 class="brand-title">GYM <span>RD SPORT</span></h1>
                <p class="brand-subtitle">Ingresa tus credenciales para acceder a la plataforma</p>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulario de Login -->
            <form action="{{ route('login') }}" method="POST" autocomplete="on">
                @csrf

                <!-- Campo Usuario / Correo -->
                <div class="form-group">
                    <label for="login" class="form-label">Usuario o Correo Electrónico</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            class="form-input"
                            placeholder="ej. admin@example.com o Ansony"
                            value="{{ old('login') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Recordarme -->
                <div class="form-meta">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <span>Recordarme en este equipo</span>
                    </label>
                </div>

                <!-- Botón de Envío CTA -->
                <button type="submit" class="btn-submit">
                    <span>Entrar a mi Cuenta</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <!-- Credenciales de Prueba Rápidas -->
            <div class="demo-box">
                <div class="demo-header">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Usuarios de prueba (clic para autocompletar):
                </div>
                <div class="demo-items">
                    <div class="demo-pill" onclick="fillCredentials('admin@example.com', '12345678')">
                        <span><strong>Admin:</strong> admin@example.com</span>
                        <span>Pass: <code>12345678</code></span>
                    </div>
                    <div class="demo-pill" onclick="fillCredentials('test@example.com', '12345678')">
                        <span><strong>Cliente:</strong> test@example.com</span>
                        <span>Pass: <code>12345678</code></span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                ¿No tienes una cuenta aún?
                <a href="{{ route('register') }}">Registrarse</a>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('home') }}" class="back-home">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            if (isPassword) {
                eyeIcon.innerHTML = `
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                    <line x1="2" y1="2" x2="22" y2="22"></line>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        });

        function fillCredentials(user, pass) {
            document.getElementById('login').value = user;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
