<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePanaderiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $panaderia = $this->route('panaderia');

        return [
            'nombre'           => ['required', 'string', 'max:150'],
            'ciudad'           => ['required', 'string', 'max:100'],
            'direccion'        => ['required', 'string', 'max:200'],
            'regional'         => ['required', 'string', 'max:100'],
            'centro_formacion' => ['required', 'string', 'max:200'],
            'extensionista'    => ['required', 'string', 'max:150'],
            'activa'           => ['boolean'],

            // Email del usuario: ignorar el propio registro al validar unique
            'user_email' => [
                'required',
                'email',
                "unique:users,email,{$panaderia->users()->first()?->id}",
            ],
        ];
    }
}