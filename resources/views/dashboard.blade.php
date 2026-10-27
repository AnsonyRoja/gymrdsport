<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Gym RD Sport</title>
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
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.15);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.9rem 1.5rem;
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
            font-size: 1.25rem;
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

        .user-nav {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .user-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: #FFFFFF;
        }

        .user-role-badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--carbon);
            background-color: var(--oliva);
            padding: 0.1rem 0.55rem;
            border-radius: 9999px;
            align-self: flex-end;
            margin-top: 0.2rem;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #FFFFFF;
            padding: 0.5rem 0.95rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #EF4444;
            border-color: #EF4444;
            color: #FFFFFF;
        }

        /* Main Container */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
            width: 100%;
            flex: 1;
        }

        /* Banner de Bienvenida: Sección destacada en Negro Carbono */
        .welcome-banner {
            background-color: var(--carbon);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.25rem;
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(26, 26, 26, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -20px;
            top: -20px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(163, 184, 153, 0.35) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.35rem;
        }

        .welcome-title span {
            color: var(--oliva);
        }

        .welcome-subtitle {
            color: var(--plateado);
            font-size: 0.92rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(163, 184, 153, 0.2);
            border: 1px solid var(--oliva);
            color: var(--oliva);
            padding: 0.4rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--oliva);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--oliva);
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.25); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.25rem;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--plateado);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(26, 26, 26, 0.04);
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--oliva);
            box-shadow: 0 10px 15px -3px rgba(26, 26, 26, 0.08);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background-color: rgba(163, 184, 153, 0.2);
            color: var(--oliva-dark);
        }

        .stat-icon.carbon {
            background-color: var(--carbon);
            color: var(--oliva);
        }

        .stat-label {
            color: var(--texto-muted);
            font-size: 0.825rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--carbon);
            margin-top: 0.15rem;
        }

        /* Section Cards */
        .section-card {
            background: #FFFFFF;
            border: 1px solid var(--plateado);
            border-radius: 1.25rem;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(26, 26, 26, 0.04);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--plateado-light);
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--carbon);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-cta-small {
            background-color: var(--oliva);
            color: var(--carbon);
            font-size: 0.825rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.45rem 0.9rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        .btn-cta-small:hover {
            background-color: var(--oliva-hover);
            transform: translateY(-1px);
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }

        th {
            background: var(--hueso);
            color: var(--carbon);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1.5px solid var(--plateado);
        }

        td {
            padding: 0.95rem 1rem;
            color: var(--carbon);
            border-bottom: 1px solid var(--plateado-light);
        }

        tr:hover td {
            background: rgba(163, 184, 153, 0.06);
        }

        .badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 0.375rem;
        }

        .badge-admin {
            background: var(--carbon);
            color: var(--oliva);
            border: 1px solid var(--carbon);
        }

        .badge-client {
            background: rgba(163, 184, 153, 0.25);
            color: var(--oliva-dark);
            border: 1px solid var(--oliva);
        }

        .badge-gymadmin {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }

        .alert-success {
            background: rgba(163, 184, 153, 0.25);
            border: 1px solid var(--oliva);
            color: var(--oliva-dark);
            padding: 0.85rem 1rem;
            border-radius: 0.65rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Footer */
        .dashboard-footer {
            background-color: var(--carbon);
            color: var(--plateado);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.825rem;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body>
    <!-- Navbar: Negro Carbono con detalles en Verde Oliva -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('dashboard') }}" class="brand-logo">
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

            <div class="user-nav">
                <div class="user-info">
                    <span class="user-name">{{ $user->name }}</span>
                    <span class="user-role-badge">{{ $user->rol->name ?? 'Usuario' }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="Cerrar sesión">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content: Blanco Hueso con Tarjetas Blancas y Bordes Plateados -->
    <main class="main-content">
        @if (session('success'))
            <div class="alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Welcome Banner: Negro Carbono con acentos Verde Oliva -->
        <div class="welcome-banner">
            <div>
                <h1 class="welcome-title">¡Bienvenido(a), <span>{{ $user->name }}</span>!</h1>
                <p class="welcome-subtitle">Panel de gestión deportiva <strong>Gym RD Sport</strong>.</p>
            </div>
            <div>
                <span class="status-pill">
                    <span class="pulse-dot"></span>
                    Sesión Activa: {{ $user->email }}
                </span>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon carbon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Total Usuarios</div>
                    <div class="stat-value">{{ $totalUsers }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Roles Activos</div>
                    <div class="stat-value">{{ $totalRoles }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="5"></circle>
                        <path d="M20 21a8 8 0 1 0-16 0"></path>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Tu Rol Actual</div>
                    <div class="stat-value" style="font-size: 1.2rem;">{{ $user->rol->name ?? 'Usuario' }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(163, 184, 153, 0.35);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="stat-label">Estado Gimnasio</div>
                    <div class="stat-value" style="color: var(--oliva-dark); font-size: 1.2rem;">Operativo</div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Usuarios Registrados
                </h2>
                <a href="{{ route('register') }}" class="btn-cta-small">
                    + Registrar Nuevo
                </a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentUsers as $recentUser)
                            <tr>
                                <td style="font-weight: 700; color: var(--texto-muted);">#{{ $recentUser->id }}</td>
                                <td style="font-weight: 700; color: var(--carbon);">{{ $recentUser->name }}</td>
                                <td>{{ $recentUser->email }}</td>
                                <td>
                                    @php
                                        $rolName = $recentUser->rol->name ?? 'Sin Rol';
                                        $badgeClass = match($rolName) {
                                            'Administrador' => 'badge-admin',
                                            'Administrador gym' => 'badge-gymadmin',
                                            default => 'badge-client',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $rolName }}</span>
                                </td>
                                <td style="color: var(--texto-muted);">{{ $recentUser->created_at ? $recentUser->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--texto-muted); padding: 2rem;">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer: Negro Carbono -->
    <footer class="dashboard-footer">
        &copy; {{ date('Y') }} <strong>Gym RD Sport</strong> — Todos los derechos reservados.
    </footer>
</body>
</html>
