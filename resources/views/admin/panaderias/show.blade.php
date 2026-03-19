@extends('layouts.app')
@section('title', $panaderia->nombre)

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
    <div>
        <a href="{{ route('admin.panaderias.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Panaderías
        </a>
        <div class="flex items-center gap-3">
            <h1 class="font-display text-3xl font-bold text-corteza">{{ $panaderia->nombre }}</h1>
            <span class="{{ $panaderia->activa ? 'badge-activo' : 'badge-inactivo' }}">
                {{ $panaderia->activa ? 'Activa' : 'Inactiva' }}
            </span>
        </div>
        <p class="text-corteza/50 text-sm mt-1">{{ $panaderia->ciudad }} · {{ $panaderia->regional }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.panaderias.edit', $panaderia->id) }}" class="btn-secondary">
            Editar
        </a>
        <form id="form-estado" method="POST" action="{{ route('admin.panaderias.estado', $panaderia->id) }}">
            @csrf @method('PATCH')
            @if($panaderia->activa)
                <button type="button" onclick="abrirModalDesactivar()" class="btn-danger">
    Desactivar
</button>
            @else
                <button type="submit" class="btn-verde">Activar</button>
            @endif
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    @foreach([
        [$stats['total_procesos'],    'Total procesos',     'text-corteza'],
        [$stats['procesos_activos'],  'En proceso',         'text-amber-700'],
        [$stats['procesos_completos'],'Completados',        'text-verde'],
    ] as [$val, $label, $color])
    <div class="card p-5 text-center">
        <div class="font-display text-3xl font-bold {{ $color }} mb-1">{{ $val }}</div>
        <div class="text-xs text-corteza/50">{{ $label }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info panadería --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="card p-5">
            <h3 class="font-semibold text-corteza mb-4 text-sm">Datos generales</h3>
            <dl class="space-y-3 text-sm">
                @foreach([
                    ['Dirección',        $panaderia->direccion],
                    ['Centro de formación', $panaderia->centro_formacion],
                    ['Extensionista',    $panaderia->extensionista],
                ] as [$label, $value])
                <div>
                    <dt class="text-xs text-corteza/50 uppercase tracking-wide mb-0.5">{{ $label }}</dt>
                    <dd class="text-corteza">{{ $value }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        <div class="card p-5">
            <h3 class="font-semibold text-corteza mb-4 text-sm">Usuario de acceso</h3>
            @foreach($panaderia->users as $user)
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs text-corteza/50 uppercase tracking-wide mb-0.5">Nombre</dt>
                    <dd class="text-corteza">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-corteza/50 uppercase tracking-wide mb-0.5">Correo</dt>
                    <dd class="text-corteza">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-corteza/50 uppercase tracking-wide mb-0.5">Estado</dt>
                    <dd>
                        <span class="{{ $user->activo ? 'badge-activo' : 'badge-inactivo' }}">
                            {{ $user->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                </div>
            </dl>
            @endforeach
        </div>

        {{-- Exportar solo esta panadería --}}
        <div class="card p-5">
            <h3 class="font-semibold text-corteza mb-3 text-sm">Exportar datos</h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.exportar.excel', ['panaderia_id' => $panaderia->id]) }}"
                   class="btn-secondary text-xs w-full justify-center">
                    Descargar Excel
                </a>
                <a href="{{ route('admin.exportar.pdf', ['panaderia_id' => $panaderia->id]) }}"
                   class="btn-secondary text-xs w-full justify-center">
                    Descargar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Listado de procesos --}}
    <div class="lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-trigo-light/50">
                <h3 class="font-semibold text-corteza">Procesos registrados</h3>
            </div>

            @forelse($panaderia->registros as $registro)
            <div class="px-6 py-4 border-b border-trigo-light/30 last:border-0
                        hover:bg-masa/40 transition-colors">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        {{-- Indicador de progreso circular visual --}}
                        <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center
                                    {{ $registro->estado === 'completado' ? 'bg-verde-light' : 'bg-amber-50' }}">
                            @if($registro->estado === 'completado')
                                <svg class="w-5 h-5 text-verde" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-corteza">
                                Proceso #{{ $registro->id }}
                                <span class="font-normal text-corteza/50 ml-1">
                                    — {{ $registro->fecha_inicio->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                <div class="w-24 h-1.5 bg-masa-dark rounded-full">
                                    <div class="h-full bg-trigo rounded-full"
                                         style="width: {{ $registro->progreso() }}%"></div>
                                </div>
                                <span class="text-xs text-corteza/40">
                                    {{ $registro->dias_count }}/5 días
                                    · {{ $registro->panes_count }} pan
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="{{ $registro->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }}">
                            {{ $registro->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                        <a href="{{ route('admin.registros.show', $registro->id) }}"
                           class="text-xs text-trigo-dark hover:text-corteza transition-colors font-medium">
                            Ver →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-corteza/40 text-sm">
                Esta panadería aún no tiene procesos registrados.
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@if($panaderia->activa)
<div id="modal-desactivar"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-titulo">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-corteza/40 backdrop-blur-sm"
         onclick="cerrarModalDesactivar()"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        {{-- Icono --}}
        <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <h3 id="modal-titulo" class="text-center font-display text-lg font-bold text-corteza mb-1">
            Desactivar panadería
        </h3>
        <p class="text-center text-sm text-corteza/60 mb-6">
            ¿Estás seguro de que quieres desactivar
            <span class="font-semibold text-corteza">{{ $panaderia->nombre }}</span>?
            Los usuarios de esta panadería no podrán iniciar sesión.
        </p>
        <div class="flex gap-3">
            <button type="button"
                    onclick="cerrarModalDesactivar()"
                    class="btn-secondary flex-1 justify-center">
                Cancelar
            </button>
            <button type="button"
                    onclick="document.getElementById('form-estado').submit()"
                    class="btn-danger flex-1 justify-center">
                Sí, desactivar
            </button>
        </div>
    </div>
</div>
<script>
function abrirModalDesactivar() {
    const modal = document.getElementById('modal-desactivar');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function cerrarModalDesactivar() {
    const modal = document.getElementById('modal-desactivar');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>

@endif