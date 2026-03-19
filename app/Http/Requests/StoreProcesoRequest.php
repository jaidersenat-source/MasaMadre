<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcesoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isPanaderia();
    }

    public function rules(): array
    {
        return [
            'fecha_inicio'               => ['required', 'date'],
            'hora_inicio'                => ['required', 'date_format:H:i'],
            'ph_agua'                    => ['required', 'numeric', 'between:6.5,9'],
            'cloro_agua'                 => ['required', 'numeric', 'between:0.3,2'],
            'fecha_calibracion_tester'   => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'ph_agua.between'    => 'El pH del agua debe estar entre 6.5 y 9.',
            'cloro_agua.between' => 'El nivel de cloro debe estar entre 0.3 y 2 ppm.',
        ];
    }
}