<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Panaderia\DashboardController as PanaderiaDashboard;
use App\Http\Controllers\Panaderia\ProcesoController;
use App\Http\Controllers\Panaderia\DiaMasaMadreController;
use App\Http\Controllers\Panaderia\ElaboracionPanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PanaderiaController;
use App\Http\Controllers\Admin\RegistroController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ReportesController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Panaderia\DocumentoController;
use App\Http\Controllers\Panaderia\CaracterizacionController;

// Pública
Route::get('/', fn() => view('welcome'))->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Perfil (cualquier usuario autenticado)
Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/',           [PerfilController::class, 'show'])->name('show');
    Route::patch('/datos',    [PerfilController::class, 'updateDatos'])->name('datos');
    Route::patch('/password', [PerfilController::class, 'updatePassword'])->name('password');
});



// ─── Panel panadería ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:panaderia'])
    ->prefix('panaderia')
    ->name('panaderia.')
    ->group(function () {

        // Caracterización inicial (nuevo ingreso)
        Route::get('/caracterizacion',  [CaracterizacionController::class, 'show'])->name('caracterizacion.show');
        Route::post('/caracterizacion', [CaracterizacionController::class, 'store'])->name('caracterizacion.store');

        // Dashboard
        Route::get('/dashboard', [PanaderiaDashboard::class, 'index'])->name('dashboard');

        // Historial de procesos
        Route::get('/historial', [ProcesoController::class, 'historial'])->name('historial');

        // Procesos
        Route::prefix('proceso')->name('proceso.')->group(function () {
            Route::get('/crear',           [ProcesoController::class, 'create'])->name('create');
            Route::post('/',               [ProcesoController::class, 'store'])->name('store');
            Route::get('/{proceso}',       [ProcesoController::class, 'show'])->name('show');
            Route::patch('/{proceso}/completar', [ProcesoController::class, 'completar'])->name('completar');

            // Días masa madre
            Route::get('/{proceso}/dia/{dia}',  [DiaMasaMadreController::class, 'create'])->name('dia.create');
            Route::post('/{proceso}/dia/{dia}', [DiaMasaMadreController::class, 'store'])->name('dia.store');

            // Elaboración de pan
            Route::get('/{proceso}/pan',   [ElaboracionPanController::class, 'create'])->name('pan.create');
            Route::post('/{proceso}/pan',  [ElaboracionPanController::class, 'store'])->name('pan.store');
        });
    });

    Route::middleware(['auth','role:panaderia'])
    ->prefix('panaderia/proceso/{proceso}/documentos')
    ->name('panaderia.proceso.documentos.')
    ->group(function () {
 
        
        Route::get('/acta/{tipo}/descargar', [DocumentoController::class, 'descargarActa'])
            ->name('acta.descargar')
            ->where('tipo', 'basica|especializada');
 
        Route::post('/acta/{tipo}', [DocumentoController::class, 'subirActa'])
            ->name('acta.subir')
            ->where('tipo', 'basica|especializada');
 
        
        Route::post('/foto/{tipo}', [DocumentoController::class, 'subirFotoMedicion'])
            ->name('foto.subir')
            ->where('tipo', 'ph|cloro');
 
        
        Route::post('/fotos-proceso', [DocumentoController::class, 'subirFotosProceso'])
            ->name('fotos_proceso.subir');
    });
 

// ─── Panel admin ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Gestión de panaderías
        Route::resource('panaderias', PanaderiaController::class)
            ->except(['destroy']);
        Route::get('/centros', [PanaderiaController::class, 'centros'])->name('panaderias.centros');
        Route::patch('/panaderias/{panaderia}/estado', [PanaderiaController::class, 'toggleEstado'])
            ->name('panaderias.estado');

                route::get('/panaderias/{panaderia}/editar', [PanaderiaController::class, 'edit'])->name('panaderias.edit');
                route::patch('/panaderias/{panaderia}', [PanaderiaController::class, 'update'])->name('panaderias.update'); 

        // Vista global de registros
        Route::get('/registros',          [RegistroController::class, 'index'])->name('registros.index');
        Route::get('/registros/{registro}', [RegistroController::class, 'show'])->name('registros.show');

        // Reportes
        Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');

        // Exportaciones
        Route::get('/exportar/excel', [ExportController::class, 'excel'])->name('exportar.excel');
        Route::get('/exportar/pdf',   [ExportController::class, 'pdf'])->name('exportar.pdf');

        Route::get('/exportar/caracterizaciones', [ExportController::class, 'caracterizaciones'])
    ->name('exportar.caracterizaciones');
        Route::get('/exportar/proceso/excel', [ExportController::class, 'procesoExcel'])
            ->name('exportar.proceso.excel');
        // Exportar caracterización en un archivo único (solo P1–P51)
        Route::get('/exportar/caracterizacion', [ExportController::class, 'caracterizacionUnica'])
            ->name('exportar.caracterizacion');
    });

    Route::middleware(['auth', 'role:panaderia'])
    ->prefix('panaderia/documentos')
    ->name('panaderia.documentos.')
    ->group(function () {
        Route::get('/', [DocumentoController::class, 'index'])->name('index');
    });
