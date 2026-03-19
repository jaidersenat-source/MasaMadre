<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.index', ['user' => Auth::user()]);
    }

    public function updateDatos(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return back()->with('success', 'Datos actualizados correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_actual'  => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'password_actual.current_password' => 'La contraseña actual no es correcta.',
            'password.min'                     => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'               => 'Las contraseñas no coinciden.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Contraseña actualizada correctamente.');
    }
}
