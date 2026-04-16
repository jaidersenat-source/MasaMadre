<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caracterizacion;
use App\Models\DiaMasaMadre;
use App\Models\ElaboracionPan;
use App\Models\Panaderia;
use App\Models\RegistroProceso;

class ReportesController extends Controller
{
    public function index()
    {
        $stats = [
            'panaderias'          => Panaderia::count(),
            'procesos'            => RegistroProceso::count(),
            'procesos_completados'=> RegistroProceso::where('estado', 'completado')->count(),
            'dias_masa_madre'     => DiaMasaMadre::count(),
            'elaboraciones_pan'   => ElaboracionPan::count(),
            'caracterizaciones'   => Caracterizacion::where('paso_completado', 8)->count(),
        ];

        return view('admin.reportes.index', compact('stats'));
    }
}
