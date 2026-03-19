@extends('layouts.app')
@section('title', "Día $dia — Masa Madre")

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

        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-2xl bg-trigo flex items-center justify-center font-display font-bold text-corteza-dark text-lg">
                {{ $dia }}
            </div>
            <div>
                <h1 class="font-display text-3xl font-bold text-corteza">Día {{ $dia }}</h1>
                <p class="text-corteza/50 text-sm">Masa madre — Proceso #{{ $proceso->id }}</p>
            </div>
        </div>
    </div>

    <form method="POST"
          action="{{ route('panaderia.proceso.dia.store', [$proceso->id, $dia]) }}"
          class="space-y-6">
        @csrf

        {{-- Harinas --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Harinas</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Harina de trigo (%)</label>
                    <input type="number" name="pct_harina_trigo" step="1" min="0" max="100"
                           value="{{ old('pct_harina_trigo', 100) }}"
                           class="input @error('pct_harina_trigo') border-red-400 @enderror">
                    @error('pct_harina_trigo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Otras harinas (si aplica)</label>
                    <input type="text" name="otras_harinas"
                           value="{{ old('otras_harinas', 'NA') }}"
                           placeholder="Ej: CENTENO 30, INTEGRAL 20"
                           class="input @error('otras_harinas') border-red-400 @enderror">
                    <p class="text-xs text-corteza/40 mt-1">Escribe NA si no aplica</p>
                </div>
            </div>
            <div class="mt-4">
                <label class="label">Porcentaje de agua (%)</label>
                <input type="number" name="pct_agua" step="1" min="0" max="200"
                       value="{{ old('pct_agua', 100) }}"
                       class="input max-w-xs @error('pct_agua') border-red-400 @enderror">
                @error('pct_agua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Temperaturas --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Temperaturas</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['temp_agua',     'Agua (°C)',     '28'],
                    ['temp_ambiente', 'Ambiente (°C)', '25'],
                    ['temp_mezcla',   'Mezcla (°C)',   '27'],
                ] as [$name, $label, $placeholder])
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="number" name="{{ $name }}" step="0.1"
                           value="{{ old($name) }}"
                           placeholder="{{ $placeholder }}"
                           class="input @error($name) border-red-400 @enderror">
                    @error($name) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>
        </div>

        {{-- pH y maduración --}}
        <div class="card p-6">
            <h2 class="font-semibold text-corteza mb-4">Control de fermentación</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">pH inicial (después de alimentar)</label>
                    <input type="number" name="ph_inicial" step="0.01" min="1" max="14"
                           value="{{ old('ph_inicial') }}"
                           placeholder="Ej: 5.9"
                           class="input @error('ph_inicial') border-red-400 @enderror">
                    @if($dia >= 3)
                        <p class="text-xs text-amber-600 mt-1">Día {{ $dia }}: el pH debería ir bajando (ideal &lt; 4.5)</p>
                    @endif
                    @error('ph_inicial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Tiempo de maduración (horas)</label>
                    <input type="number" name="tiempo_maduracion_horas" min="1" max="72"
                           value="{{ old('tiempo_maduracion_horas', 24) }}"
                           class="input @error('tiempo_maduracion_horas') border-red-400 @enderror">
                    @error('tiempo_maduracion_horas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Observaciones y responsable --}}
        <div class="card p-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label">Observaciones</label>
                    <textarea name="observaciones" rows="3"
                              placeholder="Ej: BURBUJAS, AROMA LÁCTICO — escribe NA si no hay observaciones"
                              class="input resize-none">{{ old('observaciones') }}</textarea>
                </div>
                <div>
                    <label class="label">Responsable</label>
                    <input type="text" name="responsable"
                           value="{{ old('responsable') }}"
                           placeholder="Iniciales o nombre del responsable"
                           class="input @error('responsable') border-red-400 @enderror">
                    @error('responsable') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-verde">
                {{ $dia < 5 ? "Guardar y continuar al Día " . ($dia + 1) : 'Guardar Día 5' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@endsection