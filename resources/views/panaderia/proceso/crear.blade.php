@extends('layouts.app')
@section('title', 'Iniciar proceso')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Encabezado con pasos --}}
    <div class="mb-8">
        <a href="{{ route('panaderia.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al inicio
        </a>
        <h1 class="font-display text-3xl font-bold text-corteza">Nuevo proceso</h1>
        <p class="text-corteza/50 text-sm mt-1">Paso 1 de 7 — Datos iniciales y agua de proceso</p>

        {{-- Stepper visual --}}
        <div class="flex items-center gap-2 mt-4">
            <div class="w-8 h-8 rounded-full bg-trigo text-corteza-dark text-sm font-bold flex items-center justify-center">1</div>
            @for($i = 2; $i <= 7; $i++)
                <div class="h-0.5 flex-1 bg-masa-dark"></div>
                <div class="w-8 h-8 rounded-full bg-masa-dark text-corteza/30 text-sm flex items-center justify-center">
                    {{ $i <= 6 ? $i - 1 : '🍞' }}
                </div>
            @endfor
        </div>
        <div class="flex justify-between text-xs text-corteza/40 mt-1.5 px-1">
            <span>Inicio</span>
            <span class="text-right">Pan</span>
        </div>
    </div>

    <form method="POST" action="{{ route('panaderia.proceso.store') }}" class="space-y-6">
        @csrf

        {{-- Card 1: Fecha y hora --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">1</span>
                Fecha y hora de inicio
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio"
                           value="{{ old('fecha_inicio', date('Y-m-d')) }}"
                           class="input @error('fecha_inicio') border-red-400 @enderror">
                    @error('fecha_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Hora de inicio</label>
                    <input type="time" name="hora_inicio"
                           value="{{ old('hora_inicio', date('H:i')) }}"
                           class="input @error('hora_inicio') border-red-400 @enderror">
                    @error('hora_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Card 2: Agua de proceso --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">2</span>
                Agua de proceso inicial
            </h2>
            <p class="text-xs text-corteza/50 mb-4 ml-8">Medición antes de iniciar la mezcla</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">pH del agua (tiras)</label>
                    <input type="number" name="ph_agua" step="0.1" min="6.5" max="9"
                           value="{{ old('ph_agua') }}"
                           placeholder="Ej: 7.0"
                           class="input @error('ph_agua') border-red-400 @enderror">
                    <p class="text-xs text-corteza/40 mt-1">Rango válido: 6.5 – 9</p>
                    @error('ph_agua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Cloro (cinta, ppm)</label>
                    <input type="number" name="cloro_agua" step="0.1" min="0.3" max="2"
                           value="{{ old('cloro_agua') }}"
                           placeholder="Ej: 0.5"
                           class="input @error('cloro_agua') border-red-400 @enderror">
                    <p class="text-xs text-corteza/40 mt-1">Rango válido: 0.3 – 2 ppm</p>
                    @error('cloro_agua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Card 3: Calibración tester --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-1 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">3</span>
                Calibración del tester
            </h2>
            <p class="text-xs text-corteza/50 mb-4 ml-8">Verificar al menos una vez por semana</p>
            <div>
                <label class="label">Fecha de verificación / calibración</label>
                <input type="date" name="fecha_calibracion_tester"
                       value="{{ old('fecha_calibracion_tester') }}"
                       class="input max-w-xs">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-verde">
                Continuar al Día 1
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@endsection