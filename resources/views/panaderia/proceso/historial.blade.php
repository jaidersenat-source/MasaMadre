@extends('layouts.app')
@section('title', 'Historial de procesos')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8">
    <div>
        <h1 class="font-display text-3xl font-bold text-corteza">Historial</h1>
        <p class="text-corteza/50 text-sm mt-1">Todos tus procesos registrados</p>
    </div>
</div>

@if($procesos->isEmpty())
<div class="card p-12 text-center">
    <div class="w-14 h-14 rounded-2xl bg-masa-dark flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-corteza/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <p class="text-corteza/50 text-sm">Aún no tienes procesos registrados.</p>
</div>
@else

<div class="space-y-3">
    @foreach($procesos as $p)
    <div class="card p-5 hover:shadow-md transition-shadow">

        {{-- Fila superior: icono + info --}}
        <div class="flex items-start gap-3 mb-3">

            {{-- Estado icono --}}
            <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center mt-0.5
                        {{ $p->estado === 'completado' ? 'bg-verde-light' : 'bg-amber-50' }}">
                @if($p->estado === 'completado')
                    <svg class="w-5 h-5 text-verde" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="font-medium text-corteza text-sm">Proceso #{{ $p->id }}</span>
                    <span class="{{ $p->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }}">
                        {{ $p->estado === 'completado' ? 'Completado' : 'En proceso' }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-0.5 text-xs text-corteza/50">
                    <span>{{ $p->fecha_inicio->format('d/m/Y') }}</span>
                    <span>{{ $p->dias_count }}/5 días</span>
                    <span>{{ $p->panes_count }} elaboración(es)</span>
                </div>
            </div>
        </div>

        {{-- Fila inferior: barra de progreso + botón --}}
        <div class="flex items-center gap-3 pl-13">
            <div class="flex-1 flex items-center gap-2">
                <div class="flex-1 h-1.5 bg-masa-dark rounded-full">
                    <div class="h-full bg-trigo rounded-full" style="width: {{ $p->progreso() }}%"></div>
                </div>
                <span class="text-xs text-corteza/40 shrink-0">{{ $p->progreso() }}%</span>
            </div>
            <a href="{{ route('panaderia.proceso.show', $p->id) }}"
               class="btn-secondary text-xs shrink-0">
                Ver detalle
            </a>
        </div>

    </div>
    @endforeach
</div>

{{-- Paginación --}}
@if($procesos->hasPages())
<div class="mt-6">{{ $procesos->links() }}</div>
@endif

@endif

@endsection