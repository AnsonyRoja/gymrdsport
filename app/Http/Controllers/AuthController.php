<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra la vista de formulario de inicio de sesión.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa la solicitud de autenticación del usuario.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'El campo de usuario o correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['login']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput($request->only('login', 'remember'))->withErrors([
                'login' => "Demasiados intentos fallidos. Por favor inténtalo de nuevo en {$seconds} segundos.",
            ]);
        }

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $authAttempt = [
            $fieldType => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authAttempt, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectRoute = route('dashboard');
            if ($user->isAdmin()) {
                $redirectRoute = route('admin.dashboard');
            } elseif ($user->isEntrenador()) {
                $redirectRoute = route('trainer.dashboard');
            }

            return redirect()->intended($redirectRoute)->with('success', '¡Bienvenido(a) a Gym RD Sport, '.$user->name.'!');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withInput($request->only('login', 'remember'))->withErrors([
            'login' => 'Las credenciales proporcionadas no son correctas.',
        ]);
    }

    /**
     * Cierra la sesión activa del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Muestra la vista de registro de usuario.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Registra un nuevo usuario en la base de datos con el rol 'cliente'.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre completo es requerido.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Cuenta creada y sesión iniciada con éxito!');
    }
}
