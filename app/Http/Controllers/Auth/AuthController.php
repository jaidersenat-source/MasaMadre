<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Las credenciales no son correctas.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if (!$user->activo) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Redirigir según rol
    private function redirectByRole()
    {
        return match(Auth::user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'panaderia' => redirect()->route('panaderia.dashboard'),
            default     => redirect()->route('login'),
        };
    }
}