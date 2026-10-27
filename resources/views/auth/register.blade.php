<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Gym RD Sport</title>
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
            padding: 2rem 1.5rem;
        }

        .ambient-shape-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(163, 184, 153, 0.22) 0%, rgba(245, 245, 245, 0) 70%);
            top: -120px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-shape-2 {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 26, 26, 0.05) 0%, rgba(245, 245, 245, 0) 70%);
            bottom: -100px;
            left: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
        }

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

        .brand-header {
            text-align: center;
            margin-bottom: 1.75rem;
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
            margin-bottom: 0.75rem;
        }

        .brand-title {
            font-size: 1.75rem;
            font-weight: 800;
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
            margin-top: 0.4rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
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
            width: 1.2rem;
            height: 1.2rem;
            pointer-events: none;
        }

        .form-input, .form-select {
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

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.15rem;
            padding-right: 2.5rem;
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--oliva);
            box-shadow: 0 0 0 3px rgba(163, 184, 153, 0.35);
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--oliva-dark);
        }

        .btn-submit {
            width: 100%;
            background-color: var(--oliva);
            color: var(--carbon);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 0.65rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(163, 184, 153, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background-color: var(--oliva-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(163, 184, 153, 0.5);
        }

        .alert-error {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
            padding: 0.85rem 1rem;
            border-radius: 0.65rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

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
            <div class="brand-header">
                <div class="brand-badge">Nueva Membresía</div>
                <h1 class="brand-title">Crear Cuenta en <span>Gym RD</span></h1>
                <p class="brand-subtitle">Únete y empieza a entrenar con nosotros</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Nombre -->
                <div class="form-group">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Juan Pérez" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <!-- Correo -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <input type="email" id="email" name="email" class="form-input" placeholder="juan@ejemplo.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Rol -->
                <div class="form-group">
                    <label for="roles_id" class="form-label">Rol / Tipo de Usuario</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <select id="roles_id" name="roles_id" class="form-select" required>
                            <option value="">Selecciona un rol...</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('roles_id') == $rol->id ? 'selected' : '' }}>
                                    {{ $rol->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña (mínimo 8 caracteres)</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 11 3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Registrar Cuenta
                </button>
            </form>

            <div class="card-footer">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}">Inicia Sesión</a>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('home') }}" class="back-home">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
