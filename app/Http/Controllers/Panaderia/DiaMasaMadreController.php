<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiaMasaMadreRequest;
use App\Models\DiaMasaMadre;
use App\Models\RegistroDocumento;
use App\Models\RegistroProceso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        $data = $request->validated();

        // Crear el registro del día sin las fotos todavía
        $dataForCreate = $data;
        if (isset($dataForCreate['fotos_proceso'])) {
            unset($dataForCreate['fotos_proceso']);
        }

        $diaModel = DiaMasaMadre::create([
            ...$dataForCreate,
            'registro_id' => $proceso->id,
            'dia'         => $dia,
        ]);

        // Si se subieron fotos (solo aplicable en días 1,3,5 según la request)
        try {
            if ($request->hasFile('fotos_proceso')) {
                $files = $request->file('fotos_proceso');
                Log::info('Upload attempt: fotos_proceso count=' . count($files), ['proceso_id' => $proceso->id, 'dia' => $dia]);
                $stored = [];
                foreach ($files as $file) {
                    if (!$file->isValid()) {
                        Log::warning('Uploaded file invalid', ['error' => $file->getError(), 'name' => $file->getClientOriginalName()]);
                        continue;
                    }
                    $path = $file->store("panaderias/{$proceso->panaderia_id}/procesos/{$proceso->id}/dias/{$dia}", 'public');
                    $stored[] = $path;
                }
                if (!empty($stored)) {
                    $diaModel->update(['fotos' => $stored]);
                    Log::info('Fotos almacenadas', ['count' => count($stored), 'paths' => $stored]);

                    // ── Sincronizar RegistroDocumento.fotos_proceso ──────────
                    // Recolectar todas las fotos de todos los días del proceso
                    $todasFotos = DiaMasaMadre::where('registro_id', $proceso->id)
                        ->whereNotNull('fotos')
                        ->orderBy('dia')
                        ->get()
                        ->flatMap(fn($d) => $d->fotos ?? [])
                        ->values()
                        ->toArray();

                    RegistroDocumento::updateOrCreate(
                        ['registro_id' => $proceso->id],
                        ['fotos_proceso' => $todasFotos]
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error al procesar subida de fotos', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

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