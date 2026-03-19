<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaMasaMadre;
use App\Models\ElaboracionPan;
use App\Models\Panaderia;
use App\Models\RegistroProceso;

class DashboardController extends Controller
{
    public function index()
    {
        // Tarjetas de resumen
        $stats = [
            'total_panaderias'    => Panaderia::count(),
            'panaderias_activas'  => Panaderia::where('activa', true)->count(),
            'procesos_activos'    => RegistroProceso::where('estado', 'en_proceso')->count(),
            'procesos_completos'  => RegistroProceso::where('estado', 'completado')->count(),
        ];

        // Últimos 10 procesos registrados
        $ultimosProcesos = RegistroProceso::with('panaderia')
            ->withCount(['dias', 'panes'])
            ->latest()
            ->take(10)
            ->get();

        // Panaderías sin actividad en los últimos 30 días
        $sinActividad = Panaderia::where('activa', true)
            ->whereDoesntHave('registros', fn($q) =>
                $q->where('created_at', '>=', now()->subDays(30))
            )
            ->count();

        // Procesos por regional (para gráfica)
        $porRegional = RegistroProceso::selectRaw('panaderias.regional, COUNT(*) as total')
            ->join('panaderias', 'panaderias.id', '=', 'registros_proceso.panaderia_id')
            ->groupBy('panaderias.regional')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'ultimosProcesos', 'sinActividad', 'porRegional'
        ));
    }
}