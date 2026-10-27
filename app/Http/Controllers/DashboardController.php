<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal de Gym RD Sport para usuarios autenticados.
     */
    public function index(): View
    {
        $user = Auth::user()->load('rol');
        $totalUsers = User::count();
        $totalRoles = Rol::count();
        $recentUsers = User::with('rol')->latest()->take(5)->get();

        return view('dashboard', compact('user', 'totalUsers', 'totalRoles', 'recentUsers'));
    }
}
