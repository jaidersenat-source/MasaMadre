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
use App\Http\Controllers\PerfilController;

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
        Route::patch('/panaderias/{panaderia}/estado', [PanaderiaController::class, 'toggleEstado'])
            ->name('panaderias.estado');

                route::get('/panaderias/{panaderia}/editar', [PanaderiaController::class, 'edit'])->name('panaderias.edit');
                route::patch('/panaderias/{panaderia}', [PanaderiaController::class, 'update'])->name('panaderias.update'); 

        // Vista global de registros
        Route::get('/registros',          [RegistroController::class, 'index'])->name('registros.index');
        Route::get('/registros/{registro}', [RegistroController::class, 'show'])->name('registros.show');

        // Exportaciones
        Route::get('/exportar/excel', [ExportController::class, 'excel'])->name('exportar.excel');
        Route::get('/exportar/pdf',   [ExportController::class, 'pdf'])->name('exportar.pdf');
    });
