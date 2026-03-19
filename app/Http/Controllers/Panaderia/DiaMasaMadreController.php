<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiaMasaMadreRequest;
use App\Models\DiaMasaMadre;
use App\Models\RegistroProceso;
use Illuminate\Support\Facades\Auth;

class DiaMasaMadreController extends Controller
{
    // Formulario para registrar un día
    public function create(RegistroProceso $proceso, int $dia)
    {
        $this->autorizarPanaderia($proceso);
        $this->validarDia($proceso, $dia);

        return view('panaderia.proceso.dia', compact('proceso', 'dia'));
    }

    // Guardar el día
    public function store(StoreDiaMasaMadreRequest $request, RegistroProceso $proceso, int $dia)
    {
        $this->autorizarPanaderia($proceso);
        $this->validarDia($proceso, $dia);

        DiaMasaMadre::create([
            ...$request->validated(),
            'registro_id' => $proceso->id,
            'dia'         => $dia,
        ]);

        // Si ya registró los 5 días → invitar a registrar el pan
        if ($dia >= 5) {
            return redirect()
                ->route('panaderia.proceso.show', $proceso->id)
                ->with('success', '¡Día 5 registrado! Ya puedes registrar la elaboración del pan.');
        }

        // Siguiente día
        return redirect()
            ->route('panaderia.proceso.dia.create', ['proceso' => $proceso->id, 'dia' => $dia + 1])
            ->with('success', "Día {$dia} guardado. Continúa con el día " . ($dia + 1) . ".");
    }

    private function autorizarPanaderia(RegistroProceso $proceso): void
    {
        if ($proceso->panaderia_id !== Auth::user()->panaderia_id) {
            abort(403);
        }
    }

    private function validarDia(RegistroProceso $proceso, int $dia): void
    {
        // El día debe estar en el rango 1–5
        if ($dia < 1 || $dia > 5) {
            abort(404);
        }

        // No se puede registrar un día que ya existe
        $yaRegistrado = DiaMasaMadre::where('registro_id', $proceso->id)
            ->where('dia', $dia)
            ->exists();

        if ($yaRegistrado) {
            abort(409, "El día {$dia} ya fue registrado.");
        }

        // No se puede saltar días (el día a registrar debe ser el siguiente en secuencia)
        $diasRegistrados = DiaMasaMadre::where('registro_id', $proceso->id)->count();
        if ($dia !== $diasRegistrados + 1) {
            abort(422, "Debes registrar los días en orden. El siguiente es el día " . ($diasRegistrados + 1) . ".");
        }
    }
}