@extends('layouts.app')
@section('title', 'Panaderías')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-3xl font-bold text-corteza">Panaderías</h1>
        <p class="text-corteza/50 text-sm mt-1">{{ $panaderias->total() }} registradas en el sistema</p>
    </div>
    <a href="{{ route('admin.panaderias.create') }}" class="btn-verde w-full sm:w-auto justify-center sm:justify-start">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva panadería
    </a>
</div>

{{-- Filtros --}}
<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('admin.panaderias.index') }}"
          class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:flex-wrap gap-3 lg:items-end">

        <div class="sm:col-span-2 lg:flex-1 lg:min-w-45">
            <label class="label">Buscar por nombre</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Nombre de la panadería..."
                   class="input">
        </div>

        <div>
            <label class="label">Regional</label>
            <select name="regional" class="input">
                <option value="">Todas</option>
                @foreach($regionales as $r)
                    <option value="{{ $r }}" {{ request('regional') === $r ? 'selected' : '' }}>
                        {{ $r }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Estado</label>
            <select name="estado" class="input">
                <option value="">Todos</option>
                <option value="activa"    {{ request('estado') === 'activa'    ? 'selected' : '' }}>Activas</option>
                <option value="inactiva"  {{ request('estado') === 'inactiva'  ? 'selected' : '' }}>Inactivas</option>
            </select>
        </div>

        <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn-primary flex-1 lg:flex-none justify-center">Filtrar</button>
            @if(request()->hasAny(['buscar','regional','estado']))
                <a href="{{ route('admin.panaderias.index') }}" class="btn-secondary flex-1 lg:flex-none justify-center">Limpiar</a>
            @endif
        </div>
    </form>
</div>

{{-- Vista móvil: cards --}}
<div class="md:hidden space-y-3 mb-6">
    @forelse($panaderias as $p)
    <div class="card p-4">
        <div class="flex items-start justify-between gap-2 mb-2">
            <div class="min-w-0">
                <div class="font-semibold text-corteza truncate">{{ $p->nombre }}</div>
                <div class="text-xs text-corteza/40 mt-0.5">{{ $p->users->first()?->email }}</div>
            </div>
            <span class="{{ $p->activa ? 'badge-activo' : 'badge-inactivo' }} shrink-0">
                {{ $p->activa ? 'Activa' : 'Inactiva' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs text-corteza/70 mb-3">
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Ciudad</span>
                <div class="text-corteza">{{ $p->ciudad }}</div>
            </div>
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Regional</span>
                <div class="text-corteza">{{ $p->regional }}</div>
            </div>
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Extensionista</span>
                <div class="text-corteza">{{ $p->extensionista }}</div>
            </div>
            <div>
                <span class="text-corteza/40 uppercase tracking-wide text-[10px]">Procesos</span>
                <div class="flex items-center gap-1.5">
                    <span class="font-medium text-corteza">{{ $p->registros_count }}</span>
                    @if($p->procesos_activos_count > 0)
                        <span class="badge-proceso">{{ $p->procesos_activos_count }} activo</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 pt-3 border-t border-trigo-light/40">
            <a href="{{ route('admin.panaderias.show', $p->id) }}"
               class="btn-secondary text-xs flex-1 justify-center">Ver</a>
            <a href="{{ route('admin.panaderias.edit', $p->id) }}"
               class="btn-secondary text-xs flex-1 justify-center">Editar</a>
            @if($p->activa)
                <button type="button"
                    onclick="abrirModalDesactivar('{{ route('admin.panaderias.estado', $p->id) }}', '{{ addslashes($p->nombre) }}')"
                    class="text-xs text-red-400 hover:text-red-600 transition-colors flex-1 text-center py-1.5">
                    Desactivar
                </button>
            @else
                <form method="POST" action="{{ route('admin.panaderias.estado', $p->id) }}" class="flex-1">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs text-verde hover:text-verde-dark transition-colors w-full text-center py-1.5">
                        Activar
                    </button>
                </form>
            @endif
        </div>
    </div>
    @empty
    <div class="card px-6 py-10 text-center text-corteza/40 text-sm">
        No hay panaderías que coincidan con los filtros.
    </div>
    @endforelse
</div>

{{-- Vista escritorio: tabla --}}
<div class="hidden md:block card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-masa border-b border-trigo-light/50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Panadería</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Ciudad / Regional</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Extensionista</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Procesos</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-corteza/50 uppercase tracking-wide">Estado</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-trigo-light/30">
                @forelse($panaderias as $p)
                <tr class="hover:bg-masa/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-corteza">{{ $p->nombre }}</div>
                        <div class="text-xs text-corteza/40 mt-0.5">{{ $p->users->first()?->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-corteza">{{ $p->ciudad }}</div>
                        <div class="text-xs text-corteza/50 mt-0.5">{{ $p->regional }}</div>
                    </td>
                    <td class="px-6 py-4 text-corteza/70">{{ $p->extensionista }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-corteza">{{ $p->registros_count }}</span>
                            @if($p->procesos_activos_count > 0)
                                <span class="badge-proceso">{{ $p->procesos_activos_count }} activo</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $p->activa ? 'badge-activo' : 'badge-inactivo' }}">
                            {{ $p->activa ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3 justify-end">
                            <a href="{{ route('admin.panaderias.show', $p->id) }}"
                               class="text-xs text-trigo-dark hover:text-corteza transition-colors font-medium">
                                Ver
                            </a>
                            <a href="{{ route('admin.panaderias.edit', $p->id) }}"
                               class="text-xs text-corteza/50 hover:text-corteza transition-colors">
                                Editar
                            </a>
                            @if($p->activa)
                                <button type="button"
                                    onclick="abrirModalDesactivar('{{ route('admin.panaderias.estado', $p->id) }}', '{{ addslashes($p->nombre) }}')"
                                    class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                    Desactivar
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.panaderias.estado', $p->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-verde hover:text-verde-dark transition-colors">
                                        Activar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-corteza/40">
                        No hay panaderías que coincidan con los filtros.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($panaderias->hasPages())
    <div class="px-6 py-4 border-t border-trigo-light/40">
        {{ $panaderias->links() }}
    </div>
    @endif
</div>

{{-- Paginación móvil --}}
@if($panaderias->hasPages())
<div class="md:hidden mt-4">
    {{ $panaderias->links() }}
</div>
@endif

@endsection

{{-- Modal confirmación desactivar --}}
<div id="modal-desactivar"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
    <!-- ... -->

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
            <span id="modal-nombre" class="font-semibold text-corteza"></span>?
            Los usuarios de esta panadería no podrán iniciar sesión.
        </p>

        <div class="flex gap-3">
            <button type="button" onclick="cerrarModalDesactivar()"
                    class="btn-secondary flex-1 justify-center">
                Cancelar
            </button>
            <form id="form-desactivar" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn-danger flex-1 justify-center">
                    Sí, desactivar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
   function abrirModalDesactivar(action, nombre) {
    document.getElementById('modal-nombre').textContent = nombre;
    document.getElementById('form-desactivar').action = action;
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