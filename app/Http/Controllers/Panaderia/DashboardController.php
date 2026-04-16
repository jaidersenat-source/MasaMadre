<?php

namespace App\Http\Controllers\Panaderia;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $panaderia = Auth::user()->panaderia;

        // Si la panadería aún no ha completado la caracterización, redirigir al formulario
        if ($panaderia && !$panaderia->tieneCaracterizacion()) {
            return redirect()->route('panaderia.caracterizacion.show');
        }

        $procesosActivos = $panaderia?->registros()
            ->where('estado', 'en_proceso')
            ->with('dias')
            ->latest()
            ->get();

        return view('panaderia.dashboard', compact('panaderia', 'procesosActivos'));
    }
}