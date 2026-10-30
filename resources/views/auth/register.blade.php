<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Gym RD Sport</title>
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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-slate-900 selection:text-white">

    <!-- Navbar superior minimalista -->
    <header class="w-full px-6 py-4 flex items-center justify-between border-b border-slate-200/70 bg-white/80 backdrop-blur-sm">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white font-bold text-sm shadow-sm group-hover:bg-slate-800 transition-colors">
                RD
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-tight text-slate-900">Gym RDSport</span>
                <span class="text-[10px] font-medium tracking-wider text-slate-400 uppercase">Registro de Usuarios</span>
            </div>
        </a>

        <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al inicio
        </a>
    </header>

    <!-- Contenedor del Formulario -->
    <main class="flex-1 flex items-center justify-center p-6 sm:p-8">
        <div class="w-full max-w-md">
            
            <!-- Tarjeta Principal Impecable -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-8 sm:p-10">
                
                <!-- Encabezado de la Tarjeta -->
                <div class="mb-8 text-center">
                    <div class="inline-flex w-12 h-12 rounded-xl bg-slate-100 border border-slate-200/60 items-center justify-center text-slate-800 mb-4 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Crear Cuenta</h1>
                    <p class="text-xs text-slate-500 mt-1.5">Regístrate para comenzar a entrenar con nosotros</p>
                </div>

                <!-- Notificaciones de Error -->
                @if ($errors->any())
                    <div class="mb-6 p-3.5 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-medium flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Formulario -->
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nombre Completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                   placeholder="Juan Pérez"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="correo@ejemplo.com"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Contraseña (mínimo 8 caracteres)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 transition-colors placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Botón Elegante Corporativo -->
                    <button type="submit"
                            class="w-full mt-3 py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold tracking-wide transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
                        <span>Completar Registro</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Pie de Tarjeta -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:underline ml-1">
                            Iniciar sesión
                        </a>
                    </p>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer Discreto -->
    <footer class="w-full py-4 text-center text-[11px] text-slate-400 border-t border-slate-200/50">
        &copy; {{ date('Y') }} Gym RDSport. Sistema de Gestión y Alto Rendimiento.
    </footer>

</body>
</html>
