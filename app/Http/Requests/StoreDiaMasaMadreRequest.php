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
        $rules = [
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

        // Validación condicional para fotos: solo en días 1, 3 y 5
        $dia = (int) $this->route('dia');
        if (in_array($dia, [1, 3, 5], true)) {
            $rules['fotos_proceso'] = ['required', 'array', 'min:3', 'max:6'];
            $rules['fotos_proceso.*'] = ['file', 'image', 'mimes:jpg,jpeg,png', 'max:5120']; // 5 MB max por archivo
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'pct_harina_trigo.max'        => 'El porcentaje de harina no puede superar 100%.',
            'ph_inicial.between'          => 'El pH inicial debe estar entre 1 y 14.',
            'tiempo_maduracion_horas.min' => 'El tiempo de maduración debe ser al menos 1 hora.',
            'fotos_proceso.required'      => 'En este día es obligatorio subir fotos del proceso (mínimo 3).',
            'fotos_proceso.array'         => 'Las fotos deben cargarse como varios archivos.',
            'fotos_proceso.min'           => 'Debes subir al menos :min fotos.',
            'fotos_proceso.max'           => 'Puedes subir como máximo :max fotos.',
            'fotos_proceso.*.image'       => 'Los archivos deben ser imágenes (jpg, png).',
            'fotos_proceso.*.mimes'       => 'Formato inválido. Usa JPG o PNG.',
            'fotos_proceso.*.max'         => 'Cada imagen no puede superar 5 MB.',
        ];
    }
}