<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym RD Sport - Tu Gimnasio, Tu Rendimiento</title>
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
            flex-direction: column;
        }

        /* Navbar: Negro Carbono con detalles en Verde Oliva */
        .navbar {
            background-color: var(--carbon);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 4px 15px rgba(26, 26, 26, 0.15);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: #FFFFFF;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
        }

        .brand-icon-box {
            width: 2.25rem;
            height: 2.25rem;
            background-color: var(--oliva);
            color: var(--carbon);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-nav-link {
            color: var(--plateado);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 0.95rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-nav-link:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-nav-cta {
            background-color: var(--oliva);
            color: var(--carbon);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 700;
            padding: 0.5rem 1.1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-nav-cta:hover {
            background-color: var(--oliva-hover);
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4.5rem 1.5rem 3rem;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(163, 184, 153, 0.22);
            border: 1px solid var(--oliva);
            color: var(--oliva-dark);
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 3rem;
            line-height: 1.15;
            font-weight: 800;
            color: var(--carbon);
            margin-bottom: 1.25rem;
            letter-spacing: -0.03em;
        }

        .hero-title span {
            color: var(--oliva-dark);
            border-bottom: 4px solid var(--oliva);
            padding-bottom: 4px;
        }

        .hero-desc {
            max-width: 620px;
            margin: 0 auto 2.25rem;
            font-size: 1.1rem;
            line-height: 1.6;
            color: var(--texto-muted);
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background-color: var(--oliva);
            color: var(--carbon);
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 1.85rem;
            border-radius: 0.65rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 14px rgba(163, 184, 153, 0.45);
            transition: all 0.2s ease;
        }

        .btn-hero-primary:hover {
            background-color: var(--oliva-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(163, 184, 153, 0.55);
        }

        .btn-hero-secondary {
            background-color: #FFFFFF;
            color: var(--carbon);
            border: 1.5px solid var(--plateado);
            font-weight: 600;
            font-size: 1rem;
            padding: 0.85rem 1.85rem;
            border-radius: 0.65rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-hero-secondary:hover {
            border-color: var(--carbon);
            background-color: #FAFAFA;
        }

        /* Features Section */
        .features-section {
            max-width: 1200px;
            margin: 2rem auto 4rem;
            padding: 0 1.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: #FFFFFF;
            border: 1px solid var(--plateado);
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(26, 26, 26, 0.04);
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            border-color: var(--oliva);
            box-shadow: 0 12px 20px -5px rgba(26, 26, 26, 0.08);
        }

        .feature-icon-box {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 0.75rem;
            background-color: rgba(163, 184, 153, 0.2);
            color: var(--oliva-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--carbon);
            margin-bottom: 0.5rem;
        }

        .feature-text {
            color: var(--texto-muted);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        /* Footer: Negro Carbono */
        .footer {
            background-color: var(--carbon);
            color: var(--plateado);
            padding: 2.5rem 1.5rem;
            text-align: center;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.875rem;
        }

        .footer strong {
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <!-- Navbar: Negro Carbono con acentos Verde Oliva -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="brand-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6.5 6.5 11 11"></path>
                        <path d="m21 21-1-1"></path>
                        <path d="m3 3 1 1"></path>
                        <path d="m18 22 4-4"></path>
                        <path d="m2 6 4-4"></path>
                        <path d="m3 10 7-7"></path>
                        <path d="m14 21 7-7"></path>
                    </svg>
                </div>
                <span>GYM RD SPORT</span>
            </a>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-nav-cta">
                        Panel de Control
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-link">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn-nav-cta">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            Gimnasio & Centro de Entrenamiento
        </div>
        <h1 class="hero-title">
            Eleva tu potencial con<br>
            <span>GYM RD SPORT</span>
        </h1>
        <p class="hero-desc">
            Instalaciones deportivas de alto nivel, entrenadores dedicados y un sistema moderno para gestionar tu progreso físico día a día.
        </p>

        <div class="hero-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                    Ir al Panel de Control
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-hero-primary">
                    Iniciar Sesión
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="btn-hero-secondary">
                    Crear Cuenta
                </a>
            @endauth
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Gestión de Miembros</h3>
                <p class="feature-text">Control completo de usuarios, roles de administrador y membresías activas para una administración eficiente.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6.5 6.5 11 11"></path>
                        <path d="m21 21-1-1"></path>
                        <path d="m3 3 1 1"></path>
                        <path d="m18 22 4-4"></path>
                        <path d="m2 6 4-4"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Equipamiento Deportivo</h3>
                <p class="feature-text">Áreas acondicionadas con maquinaria especializada para fuerza, resistencia y entrenamiento funcional.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Seguridad y Acceso</h3>
                <p class="feature-text">Autenticación segura con protección contra fuerza bruta y sesiones optimizadas.</p>
            </div>
        </div>
    </section>

    <!-- Footer: Negro Carbono -->
    <footer class="footer">
        &copy; {{ date('Y') }} <strong>Gym RD Sport</strong> — Todos los derechos reservados.
    </footer>
</body>
</html>
