<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiaMasaMadreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && method_exists($user, 'isPanaderia') && $user->isPanaderia();
    }

    public function rules(): array
    {
        return [
            'pct_harina_trigo'       => ['required', 'numeric', 'min:0', 'max:100'],
            'otras_harinas'          => ['nullable', 'string', 'max:255'],
            'pct_agua'               => ['required', 'numeric', 'min:0', 'max:200'],
            'temp_agua'              => ['required', 'numeric', 'between:0,100'],
            'temp_ambiente'          => ['required', 'numeric', 'between:0,60'],
            'temp_mezcla'            => ['required', 'numeric', 'between:0,100'],
            'ph_inicial'             => ['required', 'numeric', 'between:1,14'],
            'tiempo_maduracion_horas'=> ['required', 'integer', 'min:1', 'max:72'],
            'observaciones'          => ['nullable', 'string', 'max:500'],
            'responsable'            => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'pct_harina_trigo.max'        => 'El porcentaje de harina no puede superar 100%.',
            'ph_inicial.between'          => 'El pH inicial debe estar entre 1 y 14.',
            'tiempo_maduracion_horas.min' => 'El tiempo de maduración debe ser al menos 1 hora.',
        ];
    }
}