@extends('layouts.admin')

@section('title', 'Inicio')
@section('page-title', 'Bienvenido')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Acceso Exclusivo para Administrador -->
    @if(auth()->check() && auth()->user()->rol === 'admin')
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-700/60">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/10">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-tight">Acceso Administrativo Habilitado</h2>
                    <p class="text-xs text-slate-300 mt-0.5">Tienes privilegios de administrador para gestionar métricas globales, ingresos y usuarios.</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white text-slate-900 text-xs font-bold hover:bg-slate-100 transition-all shadow-sm shrink-0">
                <span>Ir al Panel de Administración</span>
                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    @endif

    <!-- Tarjeta Principal de Bienvenida Limpia y Minimalista -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-8 sm:p-12 text-center sm:text-left">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Icono / Avatar de bienvenida -->
            <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-xl font-bold shadow-sm shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <!-- Textos de Bienvenida -->
            <div class="flex-1 space-y-3">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                        ¡Bienvenido(a), {{ $user->name }}!
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Rol: {{ $user->rol ?? 'Cliente' }}
                    </span>
                </div>

                <p class="text-sm text-slate-500 leading-relaxed max-w-2xl">
                    Te damos la bienvenida al sistema integral de <strong class="text-slate-800">Gym RD Sport</strong>. Tu cuenta se encuentra activa y lista para el seguimiento de tus entrenamientos, membresías y actividades dentro de nuestras instalaciones.
                </p>

                <div class="pt-4 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-500 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Cuenta en estado <strong class="text-emerald-600 capitalize font-medium">{{ $user->estado ?? 'activo' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
