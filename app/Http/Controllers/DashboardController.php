<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra la vista de bienvenida al panel principal para usuarios autenticados.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard', compact('user'));
    }
}
