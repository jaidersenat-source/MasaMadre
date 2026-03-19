<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreElaboracionPanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isPanaderia();
    }

    public function rules(): array
    {
        return [
            'fecha_elaboracion'       => ['required', 'date'],
            'hora_elaboracion'        => ['required', 'date_format:H:i'],
            'tipo_pan'                => ['required', 'string', 'max:100'],
            'tipo_harina'             => ['required', 'string', 'max:100'],
            'temp_agua'               => ['required', 'numeric', 'between:0,100'],
            'temp_ambiente'           => ['required', 'numeric', 'between:0,60'],
            'temp_masa_madre'         => ['required', 'numeric', 'between:0,60'],
            'ph_masa_madre'           => ['required', 'numeric', 'between:1,14'],
            'ph_masa_antes_coccion'   => ['required', 'numeric', 'between:1,14'],
            'ph_pan'                  => ['required', 'numeric', 'between:1,14'],
            'temp_pan'                => ['required', 'numeric', 'between:0,300'],
            'observaciones'           => ['nullable', 'string', 'max:500'],
            'responsable'             => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'ph_masa_madre.between'         => 'El pH de la masa madre debe estar entre 1 y 14. Recuerda que el valor ideal es < 4.2.',
            'ph_masa_antes_coccion.between' => 'El pH antes de cocción debe estar entre 1 y 14. Valor ideal: < 4.8.',
            'ph_pan.between'                => 'El pH del pan debe estar entre 1 y 14. Valor ideal: < 5.8.',
        ];
    }
}