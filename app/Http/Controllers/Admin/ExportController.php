<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrosExport;
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
}