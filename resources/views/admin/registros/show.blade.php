@extends('layouts.app')
@section('title', 'Proceso #' . $registro->id)

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <a href="{{ route('admin.panaderias.show', $registro->panaderia->id) }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ $registro->panaderia->nombre }}
        </a>
        <h1 class="font-display text-3xl font-bold text-corteza">Proceso #{{ $registro->id }}</h1>
        <p class="text-corteza/50 text-sm mt-1">
            Iniciado el {{ $registro->fecha_inicio->format('d \d\e F \d\e Y') }}
            &nbsp;·&nbsp;
            <span class="{{ $registro->estado === 'completado' ? 'text-verde' : 'text-amber-700' }} font-medium">
                {{ $registro->estado === 'completado' ? 'Completado' : 'En proceso' }}
            </span>
        </p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.exportar.pdf', ['proceso_id' => $registro->id]) }}"
           class="btn-secondary text-xs w-full sm:w-auto justify-center">
            Exportar PDF
        </a>
    </div>
</div>

{{-- Datos iniciales --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="card p-6">
        <h2 class="font-semibold text-corteza mb-4 text-sm uppercase tracking-wide">Agua de proceso inicial</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <div class="text-xs text-corteza/50 mb-1">pH del agua</div>
                <div class="font-display text-2xl font-bold
                    {{ $registro->ph_agua >= 6.5 && $registro->ph_agua <= 9 ? 'text-verde' : 'text-red-600' }}">
                    {{ $registro->ph_agua }}
                </div>
                <div class="text-xs text-corteza/40">Rango: 6.5 – 9</div>
            </div>
            <div>
                <div class="text-xs text-corteza/50 mb-1">Cloro (ppm)</div>
                <div class="font-display text-2xl font-bold
                    {{ $registro->cloro_agua >= 0.3 && $registro->cloro_agua <= 2 ? 'text-verde' : 'text-red-600' }}">
                    {{ $registro->cloro_agua }}
                </div>
                <div class="text-xs text-corteza/40">Rango: 0.3 – 2 ppm</div>
            </div>
        </div>
        @if($registro->fecha_calibracion_tester)
        <div class="mt-4 pt-4 border-t border-trigo-light/50 text-xs text-corteza/50">
            Calibración tester: {{ $registro->fecha_calibracion_tester->format('d/m/Y') }}
        </div>
        @endif
    </div>

    <div class="card p-6">
        <h2 class="font-semibold text-corteza mb-4 text-sm uppercase tracking-wide">Panadería</h2>
        <dl class="space-y-2 text-sm">
            @foreach([
                ['Nombre',       $registro->panaderia->nombre],
                ['Ciudad',       $registro->panaderia->ciudad],
                ['Regional',     $registro->panaderia->regional],
                ['Extensionista',$registro->panaderia->extensionista],
            ] as [$l, $v])
            <div class="flex gap-2">
                <dt class="text-corteza/50 w-28 shrink-0">{{ $l }}</dt>
                <dd class="text-corteza font-medium">{{ $v }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
</div>

{{-- Días masa madre --}}
@if($registro->dias->isNotEmpty())
<div class="mb-6">
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-trigo-light/50">
            <h2 class="font-semibold text-corteza">Elaboración de masa madre</h2>
        </div>

        {{-- Vista móvil: cards por día --}}
        <div class="md:hidden divide-y divide-trigo-light/30">
            @foreach($registro->dias as $dia)
            <div class="p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-trigo text-corteza-dark text-sm font-bold flex items-center justify-center shrink-0">
                        {{ $dia->dia }}
                    </div>
                    <div class="text-sm font-semibold text-corteza">Día {{ $dia->dia }}</div>
                    <span class="ml-auto text-xs font-medium
                        {{ $dia->ph_inicial <= 4.5 ? 'text-verde' : ($dia->ph_inicial <= 5.5 ? 'text-amber-600' : 'text-red-500') }}">
                        pH {{ $dia->ph_inicial }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Harina T (%)</span>
                        <div class="text-corteza font-medium">{{ $dia->pct_harina_trigo }}%</div>
                    </div>
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Agua (%)</span>
                        <div class="text-corteza font-medium">{{ $dia->pct_agua }}%</div>
                    </div>
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">T° agua</span>
                        <div class="text-corteza">{{ $dia->temp_agua }}°C</div>
                    </div>
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">T° ambiente</span>
                        <div class="text-corteza">{{ $dia->temp_ambiente }}°C</div>
                    </div>
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">T° mezcla</span>
                        <div class="text-corteza">{{ $dia->temp_mezcla }}°C</div>
                    </div>
                    <div>
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Maduración</span>
                        <div class="text-corteza">{{ $dia->tiempo_maduracion_horas }}h</div>
                    </div>
                    @if($dia->otras_harinas)
                    <div class="col-span-2">
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Otras harinas</span>
                        <div class="text-corteza">{{ $dia->otras_harinas }}</div>
                    </div>
                    @endif
                    @if($dia->observaciones)
                    <div class="col-span-2">
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Observaciones</span>
                        <div class="text-corteza/70">{{ $dia->observaciones }}</div>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Responsable</span>
                        <div class="text-corteza font-medium">{{ $dia->responsable }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Vista escritorio: tabla --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-masa text-xs text-corteza/50 uppercase tracking-wide">
                        <th class="px-4 py-3 text-left font-medium">Día</th>
                        <th class="px-4 py-3 text-left font-medium">Harina T (%)</th>
                        <th class="px-4 py-3 text-left font-medium">Otras harinas</th>
                        <th class="px-4 py-3 text-left font-medium">Agua (%)</th>
                        <th class="px-4 py-3 text-left font-medium">T° agua</th>
                        <th class="px-4 py-3 text-left font-medium">T° amb.</th>
                        <th class="px-4 py-3 text-left font-medium">T° mezcla</th>
                        <th class="px-4 py-3 text-left font-medium">pH ini.</th>
                        <th class="px-4 py-3 text-left font-medium">Maduración</th>
                        <th class="px-4 py-3 text-left font-medium">Observaciones</th>
                        <th class="px-4 py-3 text-left font-medium">Resp.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-trigo-light/30">
                    @foreach($registro->dias as $dia)
                    <tr class="hover:bg-masa/40">
                        <td class="px-4 py-3">
                            <div class="w-7 h-7 rounded-lg bg-trigo text-corteza-dark text-xs font-bold flex items-center justify-center">
                                {{ $dia->dia }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->pct_harina_trigo }}%</td>
                        <td class="px-4 py-3 text-corteza/60">{{ $dia->otras_harinas ?? 'NA' }}</td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->pct_agua }}%</td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->temp_agua }}°C</td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->temp_ambiente }}°C</td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->temp_mezcla }}°C</td>
                        <td class="px-4 py-3">
                            <span class="font-medium
                                {{ $dia->ph_inicial <= 4.5 ? 'text-verde' : ($dia->ph_inicial <= 5.5 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ $dia->ph_inicial }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-corteza">{{ $dia->tiempo_maduracion_horas }}h</td>
                        <td class="px-4 py-3 text-corteza/60 max-w-xs truncate"
                            title="{{ $dia->observaciones }}">
                            {{ $dia->observaciones ?? 'NA' }}
                        </td>
                        <td class="px-4 py-3 text-corteza font-medium">{{ $dia->responsable }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Elaboración de pan --}}
@foreach($registro->panes as $pan)
<div class="card overflow-hidden mb-4">
    <div class="px-6 py-4 border-b border-trigo-light/50 flex items-center justify-between">
        <h2 class="font-semibold text-corteza">Elaboración de pan</h2>
        <span class="text-xs text-corteza/50">
            {{ $pan->fecha_elaboracion->format('d/m/Y') }} · {{ $pan->hora_elaboracion }}
        </span>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 text-sm mb-5">
            @foreach([
                ['Tipo de pan',   $pan->tipo_pan,             null],
                ['Tipo de harina',$pan->tipo_harina,           null],
                ['T° agua',       $pan->temp_agua . '°C',      null],
                ['T° ambiente',   $pan->temp_ambiente . '°C',  null],
                ['T° masa madre', $pan->temp_masa_madre . '°C',null],
                ['T° pan',        $pan->temp_pan . '°C',       null],
            ] as [$l, $v, $_])
            <div>
                <div class="text-xs text-corteza/50 mb-0.5">{{ $l }}</div>
                <div class="font-medium text-corteza">{{ $v }}</div>
            </div>
            @endforeach
        </div>

        {{-- pH con semáforo --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-masa border border-trigo-light/50">
            @foreach([
                ['pH masa madre',        $pan->ph_masa_madre,          4.2],
                ['pH antes de cocción',  $pan->ph_masa_antes_coccion,  4.8],
                ['pH del pan',           $pan->ph_pan,                  5.8],
            ] as [$label, $valor, $limite])
            <div class="text-center">
                <div class="text-xs text-corteza/50 mb-1">{{ $label }}</div>
                <div class="font-display text-2xl font-bold
                    {{ $valor < $limite ? 'text-verde' : 'text-red-500' }}">
                    {{ $valor }}
                </div>
                <div class="text-xs {{ $valor < $limite ? 'text-verde/70' : 'text-red-400' }} mt-0.5">
                    {{ $valor < $limite ? '✓ Ok' : '⚠ Supera ' . $limite }}
                </div>
            </div>
            @endforeach
        </div>

        @if($pan->observaciones && $pan->observaciones !== 'NA')
        <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-100 text-xs text-amber-800">
            <span class="font-medium">Observaciones:</span> {{ $pan->observaciones }}
        </div>
        @endif
        <div class="mt-3 text-xs text-corteza/50">Responsable: <span class="font-medium text-corteza">{{ $pan->responsable }}</span></div>
    </div>
</div>
@endforeach

@endsection