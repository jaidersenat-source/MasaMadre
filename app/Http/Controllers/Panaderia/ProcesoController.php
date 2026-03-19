<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcesoRequest;
use App\Models\RegistroProceso;
use Illuminate\Support\Facades\Auth;

class ProcesoController extends Controller
{
    // Formulario para iniciar un nuevo proceso
    public function create()
    {
        $panaderia = Auth::user()->panaderia;

        // Solo puede tener un proceso activo a la vez
        $procesoActivo = RegistroProceso::where('panaderia_id', $panaderia->id)
            ->where('estado', 'en_proceso')
            ->first();

        if ($procesoActivo) {
            return redirect()
                ->route('panaderia.proceso.show', $procesoActivo->id)
                ->with('info', 'Ya tienes un proceso en curso. Continúa registrando los días.');
        }

        return view('panaderia.proceso.crear', compact('panaderia'));
    }

    // Guardar el proceso (agua inicial)
    public function store(StoreProcesoRequest $request)
    {
        $panaderia = Auth::user()->panaderia;

        $proceso = RegistroProceso::create([
            ...$request->validated(),
            'panaderia_id' => $panaderia->id,
            'estado'       => 'en_proceso',
        ]);

        return redirect()
            ->route('panaderia.proceso.dia.create', ['proceso' => $proceso->id, 'dia' => 1])
            ->with('success', 'Proceso iniciado. Ahora registra el Día 1 de la masa madre.');
    }

    // Ver un proceso completo
    public function show(RegistroProceso $proceso)
    {
        $this->autorizarPanaderia($proceso);

        $proceso->load(['dias' => fn($q) => $q->orderBy('dia'), 'panes']);
        $siguienteDia = $proceso->dias->count() + 1;
        $puedeAgregarPan = $proceso->dias->count() >= 5;

        return view('panaderia.proceso.show', compact('proceso', 'siguienteDia', 'puedeAgregarPan'));
    }

    // Historial de procesos completados
    public function historial()
    {
        $panaderia = Auth::user()->panaderia;

        $procesos = RegistroProceso::where('panaderia_id', $panaderia->id)
            ->withCount(['dias', 'panes'])
            ->latest('fecha_inicio')
            ->paginate(10);

        return view('panaderia.proceso.historial', compact('procesos'));
    }

    // Marcar proceso como completado
    public function completar(RegistroProceso $proceso)
    {
        $this->autorizarPanaderia($proceso);

        if (!$proceso->estaCompleto()) {
            return back()->with('error', 'El proceso necesita 5 días de masa madre y al menos 1 elaboración de pan para completarse.');
        }

        $proceso->update(['estado' => 'completado']);

        return redirect()
            ->route('panaderia.dashboard')
            ->with('success', 'Proceso completado exitosamente.');
    }

    // Verificar que el proceso pertenece a la panadería autenticada
    private function autorizarPanaderia(RegistroProceso $proceso): void
    {
        if ($proceso->panaderia_id !== Auth::user()->panaderia_id) {
            abort(403);
        }
    }
}