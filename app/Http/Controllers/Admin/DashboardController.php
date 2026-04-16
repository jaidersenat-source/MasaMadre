<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caracterizacion;
use App\Models\DiaMasaMadre;
use App\Models\ElaboracionPan;
use App\Models\Panaderia;
use App\Models\RegistroProceso;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_panaderias'   => Panaderia::count(),
            'panaderias_activas' => Panaderia::where('activa', true)->count(),
            'procesos_activos'   => RegistroProceso::where('estado', 'en_proceso')->count(),
            'procesos_completos' => RegistroProceso::where('estado', 'completado')->count(),
        ];

        $ultimosProcesos = RegistroProceso::with('panaderia')
            ->withCount(['dias', 'panes'])
            ->latest()
            ->take(10)
            ->get();

        $sinActividad = Panaderia::where('activa', true)
            ->whereDoesntHave('registros', fn($q) =>
                $q->where('created_at', '>=', now()->subDays(30))
            )
            ->count();

        $porRegional = RegistroProceso::selectRaw('panaderias.regional, COUNT(*) as total')
            ->join('panaderias', 'panaderias.id', '=', 'registros_proceso.panaderia_id')
            ->groupBy('panaderias.regional')
            ->orderByDesc('total')
            ->get();

        // ── Estadísticas de caracterización ───────────────────────────────
        $totalCaract = Caracterizacion::where('paso_completado', 8)->count();
        $statsCaract = [
            'total'              => $totalCaract,
            'formalizadas'       => Caracterizacion::where('formalizacion', 'Sí')->count(),
            'usan_masa_madre'    => Caracterizacion::where('usa_masa_madre', 'Si')->count(),
            'saben_masa_madre'   => Caracterizacion::where('sabe_masa_madre', 'Si')->count(),
            'recibio_transfer'   => Caracterizacion::where('recibio_transferencia', 'Si')->count(),
            'situacion_economica'=> Caracterizacion::selectRaw('situacion_economica, COUNT(*) as total')
                ->whereNotNull('situacion_economica')
                ->groupBy('situacion_economica')
                ->orderByRaw("FIELD(situacion_economica,'Muy difícil','Difícil','Estable','Buena')")
                ->pluck('total', 'situacion_economica'),
            'zona'               => Caracterizacion::selectRaw('zona, COUNT(*) as total')
                ->whereNotNull('zona')
                ->groupBy('zona')
                ->pluck('total', 'zona'),
            'avg_empleados'      => round((float)(Caracterizacion::avg('num_empleados') ?? 0), 1),
            'avg_kilos_harina'   => round((float)(Caracterizacion::avg('kilos_harina_dia') ?? 0), 1),
        ];

        return view('admin.dashboard', compact(
            'stats', 'ultimosProcesos', 'sinActividad', 'porRegional', 'statsCaract'
        ));
    }
}