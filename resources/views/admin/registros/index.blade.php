@extends('layouts.app')
@section('title', 'Todos los registros')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-3xl font-bold text-corteza">Registros</h1>
        <p class="text-corteza/50 text-sm mt-1">{{ $registros->total() }} procesos en total</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.exportar.excel', request()->query()) }}" class="btn-secondary text-xs flex-1 sm:flex-none justify-center">
            Excel
        </a>
        <a href="{{ route('admin.exportar.pdf', request()->query()) }}" class="btn-secondary text-xs flex-1 sm:flex-none justify-center">
            PDF
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('admin.registros.index') }}"
          class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap gap-3 lg:items-end">

        <div class="sm:col-span-2 lg:flex-1 lg:min-w-40">
            <label class="label">Panadería</label>
            <input type="text" name="panaderia" value="{{ request('panaderia') }}"
                   placeholder="Nombre..." class="input">
        </div>

        <div>
            <label class="label">Regional</label>
            <select name="regional" class="input">
                <option value="">Todas</option>
                @foreach($regionales as $r)
                    <option value="{{ $r }}" {{ request('regional') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Estado</label>
            <select name="estado" class="input">
                <option value="">Todos</option>
                <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                <option value="completado" {{ request('estado') === 'completado'  ? 'selected' : '' }}>Completado</option>
            </select>
        </div>

        <div>
            <label class="label">Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="input">
        </div>

        <div>
            <label class="label">Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="input">
        </div>

        <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn-primary flex-1 lg:flex-none justify-center">Filtrar</button>
            @if(request()->hasAny(['panaderia','regional','estado','fecha_desde','fecha_hasta']))
                <a href="{{ route('admin.registros.index') }}" class="btn-secondary flex-1 lg:flex-none justify-center">Limpiar</a>
            @endif
        </div>
    </form>
</div>

{{-- Vista móvil: cards --}}
<div class="md:hidden space-y-3 mb-6">
    @forelse($registros as $r)
    <div class="card p-4">
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="min-w-0">
                <div class="font-semibold text-corteza truncate">{{ $r->panaderia->nombre }}</div>
                <div class="text-xs text-corteza/40 mt-0.5">{{ $r->panaderia->ciudad }} &middot; {{ $r->panaderia->regional }}</div>
            </div>
            <span class="{{ $r->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }} shrink-0">
                {{ $r->estado === 'completado' ? 'Completado' : 'En proceso' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs mb-3">
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Fecha inicio</span>
                <div class="text-corteza">{{ $r->fecha_inicio->format('d/m/Y') }}</div>
            </div>
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">pH agua</span>
                <div class="font-medium {{ $r->ph_agua >= 6.5 && $r->ph_agua <= 9 ? 'text-verde' : 'text-red-500' }}">
                    {{ $r->ph_agua }}
                </div>
            </div>
            <div class="col-span-2">
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Progreso</span>
                <div class="flex items-center gap-2 mt-1">
                    <div class="flex-1 h-1.5 bg-masa-dark rounded-full">
                        <div class="h-full bg-trigo rounded-full" style="width: {{ $r->progreso() }}%"></div>
                    </div>
                    <span class="text-corteza/40">{{ $r->dias_count }}/5 días</span>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-trigo-light/40 flex justify-between items-center">
            <span class="text-[10px] font-mono text-corteza/30">#{{ $r->id }}</span>
            <a href="{{ route('admin.registros.show', $r->id) }}"
               class="btn-secondary text-xs justify-center px-4">
                Ver detalle &rarr;
            </a>
        </div>
    </div>
    @empty
    <div class="card px-6 py-10 text-center text-corteza/40 text-sm">
        No hay registros con esos filtros.
    </div>
    @endforelse
</div>

{{-- Vista escritorio: tabla --}}
<div class="hidden md:block card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-masa border-b border-trigo-light/50">
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">#</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Panadería</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Regional</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Fecha inicio</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Progreso</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">pH agua</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-trigo-light/30">
                @forelse($registros as $r)
                <tr class="hover:bg-masa/50 transition-colors">
                    <td class="px-5 py-3.5 text-corteza/40 font-mono text-xs">{{ $r->id }}</td>
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-corteza">{{ $r->panaderia->nombre }}</div>
                        <div class="text-xs text-corteza/40">{{ $r->panaderia->ciudad }}</div>
                    </td>
                    <td class="px-5 py-3.5 text-corteza/60 text-xs">{{ $r->panaderia->regional }}</td>
                    <td class="px-5 py-3.5 text-corteza/70">{{ $r->fecha_inicio->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-masa-dark rounded-full">
                                <div class="h-full bg-trigo rounded-full"
                                     style="width: {{ $r->progreso() }}%"></div>
                            </div>
                            <span class="text-xs text-corteza/40">{{ $r->dias_count }}/5</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="{{ $r->ph_agua >= 6.5 && $r->ph_agua <= 9 ? 'text-verde' : 'text-red-500' }} font-medium">
                            {{ $r->ph_agua }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="{{ $r->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }}">
                            {{ $r->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.registros.show', $r->id) }}"
                           class="text-xs text-trigo-dark hover:text-corteza transition-colors font-medium">
                            Ver →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-corteza/40">
                        No hay registros con esos filtros.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registros->hasPages())
    <div class="px-6 py-4 border-t border-trigo-light/40">
        {{ $registros->links() }}
    </div>
    @endif
</div>

{{-- Paginación móvil --}}
@if($registros->hasPages())
<div class="md:hidden mt-4">
    {{ $registros->links() }}
</div>
@endif

@endsection