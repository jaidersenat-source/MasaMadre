<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaMasaMadre;
use App\Models\ElaboracionPan;
use App\Models\RegistroProceso;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    // Vista global: todos los registros de todas las panaderías
    public function index(Request $request)
    {
        $query = RegistroProceso::with(['panaderia', 'dias', 'panes'])
            ->withCount(['dias', 'panes']);

        // Filtros
        if ($request->filled('regional')) {
            $query->whereHas('panaderia', fn($q) => $q->where('regional', $request->regional));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_inicio', '<=', $request->fecha_hasta);
        }
        if ($request->filled('panaderia')) {
            $query->whereHas('panaderia', fn($q) => $q->where('nombre', 'like', '%' . $request->panaderia . '%'));
        }

        $registros = $query->latest('fecha_inicio')->paginate(20)->withQueryString();

        $regionales = \App\Models\Panaderia::distinct()->pluck('regional')->sort()->values();

        return view('admin.registros.index', compact('registros', 'regionales'));
    }

    // Detalle de un proceso completo (lectura para el admin)
    public function show(RegistroProceso $registro)
    {
        $registro->load([
            'panaderia',
            'dias'    => fn($q) => $q->orderBy('dia'),
            'panes',
            'documento',
        ]);

        return view('admin.registros.show', compact('registro'));
    }
}