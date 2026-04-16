<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use App\Models\RegistroProceso;
use App\Models\RegistroDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentoController extends Controller
{
    // ── Guard: el proceso pertenece a la panadería del usuario ──────────────
    private function verificarProceso(RegistroProceso $proceso): void
    {
        $panaderia = Auth::user()->panaderia;
        abort_unless($proceso->panaderia_id === $panaderia->id, 403);
    }

    // ── Obtener o crear el registro de documentos del proceso ───────────────
    private function obtenerDocumento(RegistroProceso $proceso): RegistroDocumento
    {
        return RegistroDocumento::firstOrCreate(
            ['registro_id' => $proceso->id]
        );
    }

    // Mostrar listado de documentos para la panadería autenticada
    public function index()
    {
        $panaderia = Auth::user()->panaderia;

        $registros = $panaderia->registros()
            ->with('documento')
            ->latest('fecha_inicio')
            ->get();

        // Proceso activo: primero busca registros con estado 'en_proceso', si no hay devuelve el más reciente
        $procesoActivo = $panaderia->registros()->activos()->with('documento')->latest('fecha_inicio')->first()
            ?? $panaderia->registros()->with('documento')->latest('fecha_inicio')->first();

        return view('panaderia.documentos.index', compact('panaderia', 'registros', 'procesoActivo'));
    }

    // ════════════════════════════════════════════════════════════════════════
    //  DESCARGAR ACTA PRE-LLENADA
    //  Genera un .docx con los datos de la panadería ya escritos
    // ════════════════════════════════════════════════════════════════════════
    public function descargarActa(RegistroProceso $proceso, string $tipo)
    {
        $this->verificarProceso($proceso);

        $panaderia = $proceso->panaderia;

        // Ruta al template .docx según tipo
        // Guarda los archivos originales en: storage/app/templates/
        $templateFile = $tipo === 'basica'
            ? storage_path('app/templates/ACTA_INICIO_BASICA.docx')
            : storage_path('app/templates/ACTA_INICIO_ESPECIALIZADA.docx');

        abort_unless(file_exists($templateFile), 404, 'Plantilla no encontrada.');

        // Reemplazar marcadores en el Word
        // Los marcadores en el .docx deben estar escritos como: ${NOMBRE_PANADERIA}
        // Ver instrucciones al final del archivo sobre cómo preparar el template
        $template = new TemplateProcessor($templateFile);

        $template->setValue('NOMBRE_PANADERIA',   $panaderia->nombre);
        $template->setValue('REGIONAL',           $panaderia->regional);
        $template->setValue('CENTRO_FORMACION',   $panaderia->centro_formacion);
        $template->setValue('CIUDAD',             $panaderia->ciudad);
        $template->setValue('DIRECCION',          $panaderia->direccion);
        $template->setValue('EXTENSIONISTA',      $panaderia->extensionista);
        $template->setValue('CEDULA_EXTENSIONISTA', $panaderia->extensionista_cedula ?? '');
        $template->setValue('FECHA_INICIO',       $proceso->fecha_inicio->format('d/m/Y'));
        $template->setValue('FECHA_GENERACION',   now()->format('d/m/Y'));

        // Guardar en archivo temporal y enviar al navegador
        $nombreArchivo = 'Acta_' . ucfirst($tipo) . '_' . str()->slug($panaderia->nombre) . '.docx';
        $tmpPath = sys_get_temp_dir() . '/' . $nombreArchivo;
        $template->saveAs($tmpPath);

        return response()->download($tmpPath, $nombreArchivo)->deleteFileAfterSend(true);
    }

    // ════════════════════════════════════════════════════════════════════════
    //  SUBIR ACTA FIRMADA (PDF escaneado)
    // ════════════════════════════════════════════════════════════════════════
    public function subirActa(Request $request, RegistroProceso $proceso, string $tipo)
    {
        $this->verificarProceso($proceso);

        $request->validate([
            'acta' => 'required|file|mimes:pdf|max:10240', // máx 10MB
        ], [
            'acta.required' => 'Debes seleccionar el archivo del acta.',
            'acta.mimes'    => 'El archivo debe ser un PDF.',
            'acta.max'      => 'El archivo no puede superar 10MB.',
        ]);

        $documento = $this->obtenerDocumento($proceso);

        // Eliminar archivo anterior si existe
        $campoPath  = "acta_{$tipo}_path";
        $campoFecha = "acta_{$tipo}_subida_at";

        if ($documento->$campoPath) {
            Storage::delete($documento->$campoPath);
        }

        // Guardar nuevo archivo
        // Ruta: actas/{proceso_id}/acta_basica.pdf
        $path = $request->file('acta')->storeAs(
            "actas/{$proceso->id}",
            "acta_{$tipo}.pdf",
            'private'  // no accesible públicamente, solo vía Storage::download
        );

        $documento->update([
            $campoPath  => $path,
            $campoFecha => now(),
        ]);

        return back()->with('success', 'Acta subida correctamente.');
    }

    // ════════════════════════════════════════════════════════════════════════
    //  SUBIR FOTO DE MEDICIÓN (pH o cloro)
    // ════════════════════════════════════════════════════════════════════════
    public function subirFotoMedicion(Request $request, RegistroProceso $proceso, string $tipo)
    {
        $this->verificarProceso($proceso);

        $label = $tipo === 'ph' ? 'pH' : 'cloro';

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'foto.required' => "Debes seleccionar la foto de {$label}.",
            'foto.image'    => 'El archivo debe ser una imagen.',
            'foto.mimes'    => 'Solo se aceptan imágenes JPG o PNG.',
            'foto.max'      => 'La imagen no puede superar 5MB.',
        ]);

        $documento = $this->obtenerDocumento($proceso);
        $campo     = "foto_{$tipo}_path";

        // Eliminar foto anterior
        if ($documento->$campo) {
            Storage::disk('public')->delete($documento->$campo);
        }

        // Guardar nueva foto
        // Ruta pública: fotos/{proceso_id}/ph.jpg
        $extension = $request->file('foto')->getClientOriginalExtension();
        $path = $request->file('foto')->storeAs(
            "fotos/{$proceso->id}",
            "{$tipo}.{$extension}",
            'public'
        );

        $documento->update([$campo => $path]);

        return back()->with('success', "Foto de {$label} guardada correctamente.");
    }

    // ════════════════════════════════════════════════════════════════════════
    //  SUBIR FOTOS DEL PROCESO (hasta 5 fotos, días 1/3/5)
    // ════════════════════════════════════════════════════════════════════════
    public function subirFotosProceso(Request $request, RegistroProceso $proceso)
    {
        $this->verificarProceso($proceso);

        $request->validate([
            'fotos'   => 'required|array|min:1|max:5',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'fotos.required'   => 'Debes seleccionar al menos una foto.',
            'fotos.max'        => 'Puedes subir máximo 5 fotos.',
            'fotos.*.image'    => 'Todos los archivos deben ser imágenes.',
            'fotos.*.mimes'    => 'Solo se aceptan imágenes JPG o PNG.',
            'fotos.*.max'      => 'Cada imagen no puede superar 5MB.',
        ]);

        $documento = $this->obtenerDocumento($proceso);

        // Eliminar fotos anteriores del storage
        if (!empty($documento->fotos_proceso)) {
            foreach ($documento->fotos_proceso as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        // Guardar nuevas fotos
        $paths = [];
        foreach ($request->file('fotos') as $index => $foto) {
            $extension = $foto->getClientOriginalExtension();
            $paths[] = $foto->storeAs(
                "fotos/{$proceso->id}/proceso",
                "dia_" . ($index + 1) . ".{$extension}",
                'public'
            );
        }

        $documento->update(['fotos_proceso' => $paths]);

        return back()->with('success', count($paths) . ' foto(s) del proceso guardadas.');
    }
}

