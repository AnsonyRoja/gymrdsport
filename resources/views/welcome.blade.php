<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym RDSport - Centro de Alto Rendimiento</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-slate-900 selection:text-white">

    <!-- Navbar Corporativo Minimalista -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand / Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-slate-900 group-hover:bg-slate-800 flex items-center justify-center text-white font-extrabold text-sm shadow-sm transition-all">
                    RD
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-slate-900">Gym RDSport</span>
                    <span class="hidden sm:block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Plataforma Atlética</span>
                </div>
            </a>

            <!-- Acciones de Navegación -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
                        <span>Mi Panel de Control</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
                        <span>Registrarse</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1">
        
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-12 pb-20 sm:pt-20 sm:pb-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                
                <!-- Badge Superior -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold mb-6 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Gestión de Rendimiento & Centro Deportivo</span>
                </div>

                <!-- Título Principal -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] max-w-4xl mx-auto">
                    Eleva tu disciplina y alcanza tu <span class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-900 bg-clip-text text-transparent">máximo potencial</span>
                </h1>

                <!-- Descripción -->
                <p class="mt-6 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Instalaciones de alto nivel, entrenadores profesionales certificados y un sistema digital integral para supervisar tus entrenamientos, membresías y progreso.
                </p>

                <!-- Botones de Acción Hero -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold transition-all shadow-md">
                            <span>Ir al Panel de Control</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold transition-all shadow-md">
                            <span>Comenzar Ahora</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-bold border border-slate-200 transition-all shadow-2xs">
                            <span>Acceso Miembros</span>
                        </a>
                    @endauth
                </div>

                <!-- Estadísticas / Tarjetas Resumidas -->
                <div class="mt-14 max-w-3xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-left">
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                        <div class="text-2xl font-black text-slate-900">100%</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">Entrenadores Certificados</div>
                    </div>
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                        <div class="text-2xl font-black text-slate-900">Multi</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">Multi Disciplinas</div>
                    </div>
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                        <div class="text-2xl font-black text-slate-900">24/7</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">Control Digital</div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Sección de Características y Pilares -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Infraestructura & Servicios</h2>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Diseñado para maximizar tu disciplina</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjeta 1: Entrenadores Dedicados -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-800 mb-5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Entrenadores y Asesoría Técnica</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Personal capacitado asignado a tu plan de desarrollo para acompañarte en tu técnica, metas y acondicionamiento.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-semibold text-slate-700">
                        <span>Seguimiento personalizado</span>
                    </div>
                </div>

                <!-- Tarjeta 2: Planificación de Clases y Servicios -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-800 mb-5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Horarios y Clases Grupales</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Programación dinámica de disciplinas como Crossfit, Spinning y Musculación libre con cupos administrados en tiempo real.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-semibold text-slate-700">
                        <span>Horarios organizados</span>
                    </div>
                </div>

                <!-- Tarjeta 3: Control Administrativo y Asistencia -->
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-800 mb-5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Control & Membresías</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Acceso seguro, registro puntual de asistencia y supervisión transparente de tus mensualidades e inscripciones.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-semibold text-slate-700">
                        <span>Gestión sin fricciones</span>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer Corporativo Minimalista -->
    <footer class="bg-white border-t border-slate-200/80 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800">Gym RDSport</span>
                <span>—</span>
                <span>Centro Deportivo & Administrativo</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
