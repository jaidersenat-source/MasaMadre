<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Models\Caracterizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaracterizacionController extends Controller
{
    public function show()
    {
        $panaderia = Auth::user()->panaderia;

        if ($panaderia && $panaderia->tieneCaracterizacion()) {
            return redirect()->route('panaderia.dashboard');
        }

        $caract = $panaderia?->caracterizacion;

        return view('panaderia.caracterizacion.form', compact('panaderia', 'caract'));
    }

    public function store(Request $request)
    {
        $user      = Auth::user();
        $panaderia = $user->panaderia;

        $data = $request->validate([
            // Paso 1 — Identificación
            'nombres_apellidos'          => 'required|string|max:200',
            'cedula'                     => 'required|string|max:20',
            'rol'                        => 'required|in:Propietario,Administrador,Panadero,Vendedor',
            'extensionista'              => 'required|string|max:200',
            'formalizacion'              => 'required|in:Sí,No',
            'tipo_documento_panaderia'   => 'required|in:NIT,Cédula de ciudadanía',
            'numero_documento_panaderia' => 'required|string|max:20',

            // Paso 2 — Ubicación
            'ciudad_municipio' => 'required|string|max:100',
            'zona'             => 'required|in:Urbana,Rural',
            'barrio_vereda'    => 'required|string|max:100',
            'direccion'        => 'required|string|max:250',
            'celular_contacto' => 'required|string|max:20',
            'estrato'          => 'required|in:1,2,3,4,5,6',

            // Paso 3 — Tiempo, edades y género
            'anos_funcionamiento'   => 'required|integer|min:0|max:200',
            'num_empleados'         => 'required|integer|min:0|max:9999',
            'empleados_18_28'       => 'required|integer|min:0',
            'empleados_29_40'       => 'required|integer|min:0',
            'empleados_41_55'       => 'required|integer|min:0',
            'empleados_55_mas'      => 'required|integer|min:0',
            'empleados_femenino'    => 'required|integer|min:0',
            'empleados_masculino'   => 'required|integer|min:0',
            'empleados_otro_genero' => 'required|integer|min:0',
            'empleados_no_responde' => 'required|integer|min:0',
            'mujeres_cabeza_hogar'  => 'required|integer|min:0',
            'hombres_cabeza_hogar'  => 'required|integer|min:0',

            // Paso 4 — Grupos especiales (JSON)
            'grupos_especiales'    => 'required|array',
            'grupos_especiales.*'  => 'required|in:0,1,2,3,4,5,6_o_mas',

            // Paso 5 — Nivel educativo
            'edu_sin_estudios' => 'required|integer|min:0',
            'edu_primaria'     => 'required|integer|min:0',
            'edu_secundaria'   => 'required|integer|min:0',
            'edu_media'        => 'required|integer|min:0',
            'edu_tecnico'      => 'required|integer|min:0',
            'edu_tecnologo'    => 'required|integer|min:0',
            'edu_pregrado'     => 'required|integer|min:0',
            'edu_posgrado'     => 'required|integer|min:0',

            // Paso 6 — Masa madre
            'kilos_harina_dia'       => 'required|numeric|min:0|max:99999',
            'tipos_pan'              => 'required|string|max:500',
            'sabe_masa_madre'        => 'required|in:Si,No',
            'usa_masa_madre'         => 'required|in:Si,No',
            'prefermentos'           => 'required|array|min:1',
            'prefermentos.*'         => 'required|in:Biga,Poolish,Esponja,No uso prefermentos',
            'recibio_transferencia'  => 'required|in:Si,No',
            'pan_masa_madre_deseado' => 'required|string|max:500',

            // Paso 7 — Expectativas
            'expectativa_aprendizaje' => 'required|string|max:1000',
            'preocupacion_masa_madre' => 'required|string|max:1000',
            'expectativa_proyecto'    => 'required|string|max:1000',

            // Paso 8 — Condiciones económicas
            'situacion_economica'      => 'required|in:Muy difícil,Difícil,Estable,Buena',
            'cierre_reduccion'         => 'required|in:Sí,No',
            'dificultad_sostener'      => 'required|string|max:1000',
            'nuevas_tecnicas_ingresos' => 'required|in:Sí,No,No sabe',
        ]);

        $data['panaderia_id']    = $panaderia->id;
        $data['user_id']         = $user->id;
        $data['paso_completado'] = 8;

        Caracterizacion::updateOrCreate(
            ['panaderia_id' => $panaderia->id],
            $data
        );

        // Sincronizar flag en todos los registro_documentos de esta panadería
        \App\Models\RegistroDocumento::whereIn(
            'registro_id',
            $panaderia->registros()->pluck('id')
        )->update([
            'caracterizacion_completada'    => true,
            'caracterizacion_completada_at' => now(),
        ]);

        return redirect()->route('panaderia.dashboard')
            ->with('success', '¡Caracterización completada exitosamente! Bienvenido al sistema.');
    }
}
