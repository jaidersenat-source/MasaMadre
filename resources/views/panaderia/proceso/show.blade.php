@extends('layouts.app')
@section('title', 'Proceso #' . $proceso->id)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('panaderia.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Inicio
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold text-corteza">Proceso #{{ $proceso->id }}</h1>
                <p class="text-corteza/50 text-sm mt-1">Iniciado el {{ $proceso->fecha_inicio->format('d/m/Y') }}</p>
            </div>
            <span class="{{ $proceso->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }} text-sm">
                {{ $proceso->estado === 'completado' ? 'Completado' : 'En proceso' }}
            </span>
        </div>
    </div>

    {{-- Progreso --}}
    <div class="card p-5 mb-6">
        <div class="flex justify-between text-xs text-corteza/60 mb-2">
            <span>Progreso total</span>
            <span>{{ $proceso->progreso() }}%</span>
        </div>
        <div class="h-2.5 bg-masa-dark rounded-full mb-5">
            <div class="h-full bg-trigo rounded-full transition-all duration-700"
                 style="width: {{ $proceso->progreso() }}%"></div>
        </div>

        {{-- Acción siguiente --}}
        @if($proceso->estado === 'en_proceso')
            @if($siguienteDia && $siguienteDia <= 5)
                <a href="{{ route('panaderia.proceso.dia.create', [$proceso->id, $siguienteDia]) }}"
                   class="btn-verde text-sm">
                    Registrar Día {{ $siguienteDia }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @elseif($puedeAgregarPan && $proceso->panes->isEmpty())
                <a href="{{ route('panaderia.proceso.pan.create', $proceso->id) }}"
                   class="btn-verde text-sm">
                    Registrar elaboración de pan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @elseif($proceso->estaCompleto())
                <form method="POST" action="{{ route('panaderia.proceso.completar', $proceso->id) }}"
                      onsubmit="return confirm('¿Confirmar proceso completo?')">
                    @csrf @method('PATCH')
                    <button class="btn-verde text-sm">Marcar como completado</button>
                </form>
            @endif
        @endif
    </div>

    {{-- Días registrados --}}
    @if($proceso->dias->isNotEmpty())
    <div class="card overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-trigo-light/50">
            <h2 class="font-semibold text-corteza">Días de masa madre</h2>
        </div>
        <div class="divide-y divide-trigo-light/30">
            @foreach($proceso->dias as $dia)
            <div class="px-6 py-4">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-verde text-white text-sm font-bold flex items-center justify-center shrink-0">
                        {{ $dia->dia }}
                    </div>
                    <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div>
                            <div class="text-xs text-corteza/50">pH inicial</div>
                            <div class="font-medium {{ $dia->ph_inicial <= 4.5 ? 'text-verde' : ($dia->ph_inicial <= 5.5 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ $dia->ph_inicial }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-corteza/50">T° mezcla</div>
                            <div class="font-medium text-corteza">{{ $dia->temp_mezcla }}°C</div>
                        </div>
                        <div>
                            <div class="text-xs text-corteza/50">Maduración</div>
                            <div class="font-medium text-corteza">{{ $dia->tiempo_maduracion_horas }}h</div>
                        </div>
                        <div>
                            <div class="text-xs text-corteza/50">Responsable</div>
                            <div class="font-medium text-corteza">{{ $dia->responsable }}</div>
                        </div>
                        @if($dia->observaciones && $dia->observaciones !== 'NA')
                        <div class="col-span-4 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2">
                            {{ $dia->observaciones }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pan --}}
    @foreach($proceso->panes as $pan)
    <div class="card p-6">
        <h2 class="font-semibold text-corteza mb-4">Pan elaborado</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm mb-4">
            @foreach([
                ['Tipo de pan',   $pan->tipo_pan],
                ['Harina',        $pan->tipo_harina],
                ['T° pan',        $pan->temp_pan . '°C'],
            ] as [$l, $v])
            <div>
                <div class="text-xs text-corteza/50 mb-0.5">{{ $l }}</div>
                <div class="font-medium text-corteza">{{ $v }}</div>
            </div>
            @endforeach
        </div>
        <div class="grid grid-cols-3 gap-3 p-4 rounded-xl bg-masa text-center text-sm">
            @foreach([
                ['pH M.M.',     $pan->ph_masa_madre,        4.2],
                ['pH cocción',  $pan->ph_masa_antes_coccion, 4.8],
                ['pH pan',      $pan->ph_pan,                5.8],
            ] as [$l, $v, $lim])
            <div>
                <div class="text-xs text-corteza/50 mb-1">{{ $l }}</div>
                <div class="font-display text-xl font-bold {{ $v < $lim ? 'text-verde' : 'text-red-500' }}">{{ $v }}</div>
                <div class="text-xs {{ $v < $lim ? 'text-verde/70' : 'text-red-400' }}">{{ $v < $lim ? '✓' : '⚠' }} < {{ $lim }}</div>
            </div>
            @endforeach
        </div>
        @if($pan->observaciones && $pan->observaciones !== 'NA')
        <p class="mt-4 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2">{{ $pan->observaciones }}</p>
        @endif
    </div>
    @endforeach

</div>

@endsection