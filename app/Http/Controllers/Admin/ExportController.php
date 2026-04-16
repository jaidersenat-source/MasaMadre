<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrosExport;
use App\Exports\CaracterizacionesExport;
use App\Exports\ProcesoExcelExport;
use App\Http\Controllers\Controller;
use App\Models\RegistroProceso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // Exportar a Excel (.xlsx)
    public function excel(Request $request)
    {
        $filtros = $request->only(['regional', 'estado', 'fecha_desde', 'fecha_hasta']);
        $nombre  = 'registros_masa_madre_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new RegistrosExport($filtros), $nombre);
    }

    // Exportar a PDF
    public function pdf(Request $request)
    {
        $filtros = $request->only(['regional', 'estado', 'fecha_desde', 'fecha_hasta']);

        $query = RegistroProceso::with([
            'panaderia',
            'dias'  => fn($q) => $q->orderBy('dia'),
            'panes',
        ]);

        if (!empty($filtros['regional'])) {
            $query->whereHas('panaderia', fn($q) => $q->where('regional', $filtros['regional']));
        }
        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }
        if (!empty($filtros['fecha_desde'])) {
            $query->where('fecha_inicio', '>=', $filtros['fecha_desde']);
        }
        if (!empty($filtros['fecha_hasta'])) {
            $query->where('fecha_inicio', '<=', $filtros['fecha_hasta']);
        }

        $registros = $query->latest('fecha_inicio')->get();
        $nombre    = 'reporte_masa_madre_' . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('exports.reporte_pdf', compact('registros', 'filtros'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($nombre);
    }

    // Exportar un proceso individual a XLSX usando la vista del PDF
    public function procesoExcel(Request $request)
    {
        $procesoId = $request->query('proceso_id') ?? $request->query('proceso');
        $registro = RegistroProceso::with([
            'panaderia',
            'dias'  => fn($q) => $q->orderBy('dia'),
            'panes',
        ])->findOrFail($procesoId);

        $nombre = 'reporte_proceso_' . $registro->id . '_' . now()->format('Ymd_His') . '.xlsx';

        return (new ProcesoExcelExport($registro))->download($nombre);
    }

    
// Exportar solo caracterizaciones
public function caracterizaciones(Request $request)
{
    $filtros = $request->only(['panaderia_id']);
    $nombre  = 'caracterizaciones_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(new RegistrosExport($filtros), $nombre);
}

// Exportar caracterización en archivo separado (solo hoja de caracterizaciones)
public function caracterizacionUnica(Request $request)
{
    $filtros = $request->only(['panaderia_id']);
    $nombre  = 'caracterizacion_' . now()->format('Ymd_His') . '.xlsx';

    return (new CaracterizacionesExport($filtros))->download($nombre);
}
}