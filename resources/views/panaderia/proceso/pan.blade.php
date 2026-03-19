@extends('layouts.app')
@section('title', 'Elaboración de pan')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('panaderia.proceso.show', $proceso->id) }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Ver proceso
        </a>
        <h1 class="font-display text-3xl font-bold text-corteza">Elaboración de pan</h1>
        <p class="text-corteza/50 text-sm mt-1">Con masa madre — Proceso #{{ $proceso->id }}</p>

        {{-- Referencia pH último día --}}
        @if($ultimoDia)
        <div class="mt-4 p-4 rounded-xl bg-trigo/10 border border-trigo/20 text-sm text-corteza/70">
            <span class="font-medium text-corteza">Referencia día 5:</span>
            pH inicial {{ $ultimoDia->ph_inicial }} · T° mezcla {{ $ultimoDia->temp_mezcla }}°C
        </div>
        @endif
    </div>

    <form method="POST"
          action="{{ route('panaderia.proceso.pan.store', $proceso->id) }}"
          class="space-y-6">
        @csrf

        {{-- Fecha, hora y tipo --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Información general</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label">Fecha de elaboración</label>
                    <input type="date" name="fecha_elaboracion"
                           value="{{ old('fecha_elaboracion', date('Y-m-d')) }}"
                           class="input">
                </div>
                <div>
                    <label class="label">Hora</label>
                    <input type="time" name="hora_elaboracion"
                           value="{{ old('hora_elaboracion', date('H:i')) }}"
                           class="input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Tipo de pan</label>
                    <input type="text" name="tipo_pan"
                           value="{{ old('tipo_pan', 'SALUDABLE') }}"
                           placeholder="Ej: SALUDABLE"
                           class="input @error('tipo_pan') border-red-400 @enderror">
                    @error('tipo_pan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Tipo de harina</label>
                    <input type="text" name="tipo_harina"
                           value="{{ old('tipo_harina') }}"
                           placeholder="Ej: BLANCA, CENTENO, INTEGRAL"
                           class="input @error('tipo_harina') border-red-400 @enderror">
                    @error('tipo_harina') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Temperaturas --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Temperaturas</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['temp_agua',        'Agua (°C)',       '28'],
                    ['temp_ambiente',     'Ambiente (°C)',   '25'],
                    ['temp_masa_madre',   'Masa madre (°C)', '27'],
                ] as [$name, $label, $ph])
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="number" name="{{ $name }}" step="0.1"
                           value="{{ old($name) }}"
                           placeholder="{{ $ph }}"
                           class="input @error($name) border-red-400 @enderror">
                    @error($name) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>
        </div>

        {{-- pH --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Control de pH</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label">pH masa madre</label>
                    <input type="number" name="ph_masa_madre" step="0.01"
                           value="{{ old('ph_masa_madre') }}"
                           placeholder="Ej: 4.1"
                           class="input @error('ph_masa_madre') border-red-400 @enderror">
                    <p class="text-xs text-amber-600 mt-1">Ideal: &lt; 4.2</p>
                    @error('ph_masa_madre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">pH masa antes de cocción</label>
                    <input type="number" name="ph_masa_antes_coccion" step="0.01"
                           value="{{ old('ph_masa_antes_coccion') }}"
                           placeholder="Ej: 4.5"
                           class="input @error('ph_masa_antes_coccion') border-red-400 @enderror">
                    <p class="text-xs text-amber-600 mt-1">Ideal: &lt; 4.8</p>
                    @error('ph_masa_antes_coccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">pH del pan</label>
                    <input type="number" name="ph_pan" step="0.01"
                           value="{{ old('ph_pan') }}"
                           placeholder="Ej: 5.1"
                           class="input @error('ph_pan') border-red-400 @enderror">
                    <p class="text-xs text-amber-600 mt-1">Ideal: &lt; 5.8</p>
                    @error('ph_pan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Temperatura del pan (°C)</label>
                    <input type="number" name="temp_pan" step="0.1"
                           value="{{ old('temp_pan') }}"
                           placeholder="Ej: 95"
                           class="input @error('temp_pan') border-red-400 @enderror">
                    @error('temp_pan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Observaciones --}}
        <div class="card p-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label">Observaciones</label>
                    <textarea name="observaciones" rows="3"
                              placeholder="Ej: MASA LIGERAMENTE SOBREFERMENTADA — escribe NA si no hay novedad"
                              class="input resize-none">{{ old('observaciones') }}</textarea>
                </div>
                <div>
                    <label class="label">Responsable</label>
                    <input type="text" name="responsable"
                           value="{{ old('responsable') }}"
                           placeholder="Nombre o iniciales"
                           class="input @error('responsable') border-red-400 @enderror">
                    @error('responsable') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-verde">
                Guardar elaboración de pan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@endsection 

{{-- Si no hay proceso activo --}}