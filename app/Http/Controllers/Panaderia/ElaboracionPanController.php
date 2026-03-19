<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreElaboracionPanRequest;
use App\Models\ElaboracionPan;
use App\Models\RegistroProceso;
use Illuminate\Support\Facades\Auth;

class ElaboracionPanController extends Controller
{
    // Formulario de elaboración de pan
    public function create(RegistroProceso $proceso)
    {
        $this->autorizarPanaderia($proceso);

        // Solo disponible cuando ya hay 5 días registrados
        if ($proceso->dias()->count() < 5) {
            return redirect()
                ->route('panaderia.proceso.show', $proceso->id)
                ->with('error', 'Debes completar los 5 días de masa madre antes de registrar el pan.');
        }

        // Obtener el último pH registrado (día 5) para mostrar como referencia
        $ultimoDia = $proceso->dias()->orderBy('dia', 'desc')->first();

        return view('panaderia.proceso.pan', compact('proceso', 'ultimoDia'));
    }

    // Guardar elaboración de pan
    public function store(StoreElaboracionPanRequest $request, RegistroProceso $proceso)
    {
        $this->autorizarPanaderia($proceso);

        $pan = ElaboracionPan::create([
            ...$request->validated(),
            'registro_id' => $proceso->id,
        ]);

        // Generar alertas de pH fuera de rango
        $alertas = $this->verificarRangospH($pan);

        $mensaje = 'Elaboración de pan guardada correctamente.';
        if (!empty($alertas)) {
            $mensaje .= ' ⚠️ ' . implode(' | ', $alertas);
        }

        return redirect()
            ->route('panaderia.proceso.show', $proceso->id)
            ->with(empty($alertas) ? 'success' : 'warning', $mensaje);
    }

    // Verificar que los valores de pH estén en los rangos ideales
    private function verificarRangospH(ElaboracionPan $pan): array
    {
        $alertas = [];

        if ($pan->ph_masa_madre >= 4.2) {
            $alertas[] = "pH masa madre ({$pan->ph_masa_madre}) supera el ideal de 4.2";
        }
        if ($pan->ph_masa_antes_coccion >= 4.8) {
            $alertas[] = "pH antes de cocción ({$pan->ph_masa_antes_coccion}) supera el ideal de 4.8";
        }
        if ($pan->ph_pan >= 5.8) {
            $alertas[] = "pH del pan ({$pan->ph_pan}) supera el ideal de 5.8";
        }

        return $alertas;
    }

    private function autorizarPanaderia(RegistroProceso $proceso): void
    {
        if ($proceso->panaderia_id !== Auth::user()->panaderia_id) {
            abort(403);
        }
    }
}