<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePanaderiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            // Datos de la panadería
            'nombre'           => ['required', 'string', 'max:150'],
            'ciudad'           => ['required', 'string', 'max:100'],
            'direccion'        => ['required', 'string', 'max:200'],
            'regional'         => ['required', 'string', 'max:100'],
            'centro_formacion' => ['required', 'string', 'max:200'],
            'extensionista'    => ['required', 'string', 'max:150'],

            // Datos del usuario asociado
            'user_name'        => ['required', 'string', 'max:100'],
            'user_email'       => ['required', 'email', 'unique:users,email'],
            'user_password'    => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_email.unique'    => 'Este correo ya está registrado en el sistema.',
            'user_password.min'    => 'La contraseña debe tener al menos 8 caracteres.',
            'user_password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}