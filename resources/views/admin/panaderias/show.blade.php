@extends('layouts.app')
@section('title', $panaderia->nombre)

@section('content')

{{-- ══ HEADER ════════════════════════════════════════════════════ --}}
<div class="mb-8">
    <a href="{{ route('admin.panaderias.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Panaderías
    </a>

    <button id="btn-caracterizacion" type="button" onclick="toggleCaracterizacion()"
       class="inline-flex items-center gap-1.5 text-sm text-corteza/50 hover:text-corteza mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
        </svg>
        <span id="btn-caracterizacion-label">Panadería · Caracterización</span>
    </button>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Avatar inicial --}}
            <div class="w-14 h-14 rounded-2xl bg-trigo flex items-center justify-center
                        font-display font-bold text-corteza-dark text-2xl shrink-0 shadow-sm">
                {{ mb_substr($panaderia->nombre, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="font-display text-3xl font-bold text-corteza leading-tight">
                        {{ $panaderia->nombre }}
                    </h1>
                    <span class="{{ $panaderia->activa ? 'badge-activo' : 'badge-inactivo' }}">
                        {{ $panaderia->activa ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <p class="text-corteza/50 text-sm mt-0.5">
                    {{ $panaderia->ciudad }}
                    <span class="mx-1.5 text-corteza/20">·</span>
                    {{ $panaderia->regional }}
                </p>
            </div>
        </div>

        <div class="flex gap-2.5 shrink-0">
            <a href="{{ route('admin.panaderias.edit', $panaderia->id) }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <form id="form-estado" method="POST"
                  action="{{ route('admin.panaderias.estado', $panaderia->id) }}">
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
</div>

{{-- ══ STATS ═══════════════════════════════════════════════════════ --}}
<div id="main-content">

<div class="grid grid-cols-3 gap-4 mb-8">
    @foreach([
        [$stats['total_procesos'],     'Total procesos',  'text-corteza',    '📋'],
        [$stats['procesos_activos'],   'En proceso',      'text-amber-700',  '⏳'],
        [$stats['procesos_completos'], 'Completados',     'text-verde',      '✓'],
    ] as [$val, $label, $color, $icon])
    <div class="card p-5 text-center group hover:shadow-md transition-shadow">
        <div class="font-display text-4xl font-bold {{ $color }} mb-1 tabular-nums">{{ $val }}</div>
        <div class="text-xs text-corteza/50 font-medium tracking-wide uppercase">{{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- ══ CUERPO PRINCIPAL ════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Columna lateral ─────────────────────────────────────────── --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Datos generales --}}
        <div class="card p-5">
            <h3 class="text-xs font-semibold text-corteza/40 uppercase tracking-widest mb-4">
                Datos generales
            </h3>
            <dl class="space-y-4">
                @foreach([
                    ['Dirección',           $panaderia->direccion,        'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['Centro de formación', $panaderia->centro_formacion, 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                    ['Extensionista',       $panaderia->extensionista,    'M16 7a4 4 0 11-8 0 4 4 0 018 0z M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ] as [$label, $value, $iconPath])
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-trigo/40 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-corteza/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                        </svg>
                    </div>
                    <div>
                        <dt class="text-[10px] text-corteza/40 uppercase tracking-wide font-medium mb-0.5">
                            {{ $label }}
                        </dt>
                        <dd class="text-sm text-corteza leading-snug">{{ $value }}</dd>
                    </div>
                </div>
                @endforeach
            </dl>
        </div>

        {{-- Usuario de acceso --}}
        <div class="card p-5">
            <h3 class="text-xs font-semibold text-corteza/40 uppercase tracking-widest mb-4">
                Usuario de acceso
            </h3>
            @foreach($panaderia->users as $user)
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-full bg-trigo flex items-center justify-center
                            font-semibold text-corteza-dark text-sm shrink-0">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-medium text-corteza leading-tight">{{ $user->name }}</div>
                    <div class="text-xs text-corteza/50">{{ $user->email }}</div>
                </div>
                <span class="ml-auto {{ $user->activo ? 'badge-activo' : 'badge-inactivo' }}">
                    {{ $user->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- Exportar --}}
        <div class="card p-5">
            <h3 class="text-xs font-semibold text-corteza/40 uppercase tracking-widest mb-3">
                Exportar datos
            </h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.exportar.excel', ['panaderia_id' => $panaderia->id]) }}"
                   class="btn-secondary text-xs w-full justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar Excel
                </a>
                <a href="{{ route('admin.exportar.pdf', ['panaderia_id' => $panaderia->id]) }}"
                   class="btn-secondary text-xs w-full justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Descargar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Columna principal ───────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Procesos registrados --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-trigo-light/50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-corteza">Procesos registrados</h3>
                    <p class="text-xs text-corteza/40 mt-0.5">
                        {{ $panaderia->registros->count() }} proceso{{ $panaderia->registros->count() !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>

            @forelse($panaderia->registros as $registro)
            <div class="px-6 py-4 border-b border-trigo-light/20 last:border-0
                        hover:bg-masa/40 transition-colors group">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
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
                                    · {{ $registro->fecha_inicio->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1.5">
                                {{-- Barra de progreso segmentada (5 días) --}}
                                <div class="flex gap-0.5">
                                    @for($d = 1; $d <= 5; $d++)
                                    <div class="w-5 h-1.5 rounded-full
                                        {{ $d <= $registro->dias_count
                                            ? ($registro->estado === 'completado' ? 'bg-verde' : 'bg-trigo')
                                            : 'bg-masa-dark' }}">
                                    </div>
                                    @endfor
                                </div>
                                <span class="text-xs text-corteza/40">
                                    {{ $registro->dias_count }}/5 días · {{ $registro->panes_count }} pan
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="{{ $registro->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }}">
                            {{ $registro->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                        <a href="{{ route('admin.registros.show', $registro->id) }}"
                           class="w-8 h-8 rounded-lg bg-trigo/30 hover:bg-trigo/60 flex items-center justify-center
                                  transition-colors text-corteza">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-masa mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-corteza/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm text-corteza/40">Aún no hay procesos registrados.</p>
            </div>
            @endforelse
        </div>

        {{-- Documentos y soportes --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-trigo-light/50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-corteza">Documentos y soportes</h3>
                    <p class="text-xs text-corteza/40 mt-0.5">Estado de los soportes por proceso</p>
                </div>
                <div class="hidden sm:flex items-center gap-4 text-xs text-corteza/50">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-verde inline-block"></span> Subido
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-masa-dark inline-block"></span> Pendiente
                    </span>
                </div>
            </div>

            @forelse($panaderia->registros as $registro)
            @php
                $doc     = $registro->documento;
                $tieneCaract = $panaderia->tieneCaracterizacion();
                $resumen = $doc->resumenEstado($tieneCaract);
                $pct     = $doc->porcentajeCompletitud($tieneCaract);
            @endphp

            <div class="border-b border-trigo-light/20 last:border-0">
                {{-- Cabecera proceso --}}
                <div class="px-6 py-3 bg-masa/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <span class="text-sm font-medium text-corteza">
                            Proceso #{{ $registro->id }}
                        </span>
                        <span class="text-corteza/30 text-xs">·</span>
                        <span class="text-xs text-corteza/40">
                            {{ $registro->fecha_inicio->format('d/m/Y') }}
                        </span>
                        <span class="{{ $registro->estado === 'completado' ? 'badge-completo' : 'badge-proceso' }}">
                            {{ $registro->estado === 'completado' ? 'Completado' : 'En proceso' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="w-20 h-1.5 bg-masa-dark rounded-full hidden sm:block overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500
                                {{ $pct === 100 ? 'bg-verde' : ($pct >= 50 ? 'bg-trigo' : 'bg-red-300') }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-semibold tabular-nums
                            {{ $pct === 100 ? 'text-verde' : ($pct >= 50 ? 'text-trigo-dark' : 'text-red-400') }}">
                            {{ $doc->totalCompletados($tieneCaract) }}/6
                        </span>
                    </div>
                </div>

                {{-- Items --}}
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($resumen as $clave => $item)
                        <div class="flex items-start gap-2.5 py-2.5 px-3 rounded-xl transition-colors
                            {{ $item['completo'] ? 'bg-verde-light/40 hover:bg-verde-light/60' : 'bg-masa/50 hover:bg-masa' }}">

                            {{-- Estado --}}
                            <div class="shrink-0 mt-0.5">
                                @if($item['completo'])
                                    <div class="w-5 h-5 rounded-full bg-verde/20 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-verde" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-5 h-5 rounded-full bg-corteza/8 flex items-center justify-center">
                                        <div class="w-1.5 h-1.5 rounded-full bg-corteza/20"></div>
                                    </div>
                                @endif
                            </div>

                            {{-- Contenido --}}
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-medium text-corteza leading-tight">
                                    {{ $item['label'] }}
                                </div>
                                @if($item['completo'] && $item['fecha'])
                                    <div class="text-[10px] text-corteza/40 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y H:i') }}
                                    </div>
                                @elseif(!$item['completo'])
                                    <div class="text-[10px] text-corteza/30 mt-0.5">Pendiente</div>
                                @endif

                                @if($item['completo'])
                                    {{-- Actas --}}
                                    @if(in_array($clave, ['acta_basica', 'acta_especializada']) && $item['url'])
                                        <a href="{{ $item['url'] }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-[10px] text-trigo-dark
                                                  hover:text-corteza transition-colors mt-1.5 font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                            </svg>
                                            Descargar PDF
                                        </a>
                                    @endif
                                    {{-- Foto individual --}}
                                    @if(in_array($clave, ['foto_ph', 'foto_cloro']) && $item['url'])
                                        <button type="button"
                                                onclick="abrirFoto('{{ $item['url'] }}', '{{ $item['label'] }}')"
                                                class="inline-flex items-center gap-1 text-[10px] text-trigo-dark
                                                       hover:text-corteza transition-colors mt-1.5 font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver foto
                                        </button>
                                    @endif
                                    {{-- Galería --}}
                                    @if($clave === 'fotos_proceso' && !empty($item['urls']))
                                        <button type="button"
                                                onclick="abrirGaleria({{ json_encode($item['urls']) }})"
                                                class="inline-flex items-center gap-1 text-[10px] text-trigo-dark
                                                       hover:text-corteza transition-colors mt-1.5 font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Ver {{ count($item['urls']) }} foto{{ count($item['urls']) > 1 ? 's' : '' }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-corteza/40">Sin documentos registrados aún.</p>
            </div>
            @endforelse
        </div>

        

    </div>{{-- /col-span-2 --}}
</div>{{-- /grid --}}

</div><!-- /#main-content -->


{{-- ══ MODAL: foto individual ════════════════════════════════════ --}}
                <div id="caracterizacion-container" class="hidden">
                {{-- ══ CARACTERIZACIÓN ══════════════════════════════════════ --}}
                <?php if($panaderia->caracterizacion->estaCompleta()): ?>
                <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-trigo-light/50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-corteza">Caracterización</h3>
                    <p class="text-xs text-corteza/40 mt-0.5">51 preguntas · Completada</p>
                </div>
                     <a href="{{ route('admin.exportar.caracterizacion', ['panaderia_id' => $panaderia->id]) }}"
                   class="btn-secondary text-xs gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Excel
                </a>
            </div>

            @php $c = $panaderia->caracterizacion; @endphp

            {{-- Paso 1: Identificación --}}
            <div class="px-6 py-4 border-b border-trigo-light/20">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-corteza/40 mb-3">
                    Identificación
                </p>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-sm">
                    @foreach([
                        ['Responsable',    $c->nombres_apellidos],
                        ['Cédula',         $c->cedula],
                        ['Rol',            $c->rol],
                        ['Extensionista',  $c->extensionista],
                        ['Formalización',  $c->formalizacion],
                        ['Tipo doc.',      $c->tipo_documento_panaderia],
                        ['N° documento',   $c->numero_documento_panaderia],
                    ] as [$lbl, $val])
                    <div>
                        <dt class="text-[10px] text-corteza/40 uppercase tracking-wide">{{ $lbl }}</dt>
                        <dd class="font-medium text-corteza mt-0.5">{{ $val ?? '—' }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- Paso 2: Ubicación --}}
            <div class="px-6 py-4 border-b border-trigo-light/20">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-corteza/40 mb-3">
                    Ubicación
                </p>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-sm">
                    @foreach([
                        ['Municipio',  $c->ciudad_municipio],
                        ['Zona',       $c->zona],
                        ['Barrio',     $c->barrio_vereda],
                        ['Dirección',  $c->direccion],
                        ['Celular',    $c->celular_contacto],
                        ['Estrato',    $c->estrato ? 'Estrato ' . $c->estrato : '—'],
                    ] as [$lbl, $val])
                    <div>
                        <dt class="text-[10px] text-corteza/40 uppercase tracking-wide">{{ $lbl }}</dt>
                        <dd class="font-medium text-corteza mt-0.5">{{ $val ?? '—' }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- Paso 3: Empleados --}}
            <div class="px-6 py-4 border-b border-trigo-light/20">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-corteza/40 mb-3">
                    Empleados
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm mb-4">
                    @foreach([
                        ['Total empleados', $c->num_empleados],
                        ['Años funcionando', $c->anos_funcionamiento],
                        ['Mujeres cab. hogar', $c->mujeres_cabeza_hogar],
                        ['Hombres cab. hogar', $c->hombres_cabeza_hogar],
                    ] as [$lbl, $val])
                    <div class="rounded-xl bg-masa/60 px-3 py-2 text-center">
                        <div class="font-display text-xl font-bold text-corteza">{{ $val ?? 0 }}</div>
                        <div class="text-[10px] text-corteza/50 mt-0.5">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
                {{-- Género --}}
                <div class="flex flex-wrap gap-2 text-xs">
                    @foreach([
                        ['Femenino',    $c->empleados_femenino,     'bg-pink-100 text-pink-700'],
                        ['Masculino',   $c->empleados_masculino,    'bg-blue-100 text-blue-700'],
                        ['Otro género', $c->empleados_otro_genero,  'bg-purple-100 text-purple-700'],
                        ['No responde', $c->empleados_no_responde,  'bg-gray-100 text-gray-600'],
                    ] as [$lbl, $n, $cls])
                        @if($n)
                        <span class="px-2 py-1 rounded-full font-medium {{ $cls }}">
                            {{ $n }} {{ $lbl }}
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Paso 6: Masa madre --}}
            <div class="px-6 py-4 border-b border-trigo-light/20">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-corteza/40 mb-3">
                    Masa madre
                </p>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-sm mb-3">
                    @foreach([
                        ['kg harina/día',      $c->kilos_harina_dia],
                        ['Sabe masa madre',    $c->sabe_masa_madre],
                        ['Usa masa madre',     $c->usa_masa_madre],
                        ['Recibió transfer.',  $c->recibio_transferencia],
                    ] as [$lbl, $val])
                    <div>
                        <dt class="text-[10px] text-corteza/40 uppercase tracking-wide">{{ $lbl }}</dt>
                        <dd class="font-medium mt-0.5
                            {{ in_array($val, ['Si','Sí']) ? 'text-verde' : ($val === 'No' ? 'text-red-500' : 'text-corteza') }}">
                            {{ $val ?? '—' }}
                        </dd>
                    </div>
                    @endforeach
                </dl>
                @if(!empty($c->prefermentos))
                <div class="flex flex-wrap gap-1.5">
                    @foreach((array) $c->prefermentos as $pref)
                    <span class="px-2 py-0.5 bg-trigo/30 text-corteza-dark rounded text-xs font-medium">
                        {{ $pref }}
                    </span>
                    @endforeach
                </div>
                @endif
                @if($c->tipos_pan)
                <p class="text-xs text-corteza/60 mt-2">
                    <span class="font-semibold text-corteza/70">Panes que produce:</span>
                    {{ $c->tipos_pan }}
                </p>
                @endif
            </div>

            {{-- Paso 8: Situación económica --}}
            <div class="px-6 py-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-corteza/40 mb-3">
                    Situación económica
                </p>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    @foreach([
                        ['Situación actual',    $c->situacion_economica],
                        ['Cierre/reducción',    $c->cierre_reduccion],
                        ['Nuevas técnicas →  ↑ingresos', $c->nuevas_tecnicas_ingresos],
                    ] as [$lbl, $val])
                    <div>
                        <dt class="text-[10px] text-corteza/40 uppercase tracking-wide">{{ $lbl }}</dt>
                        <dd class="font-medium text-corteza mt-0.5">{{ $val ?? '—' }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
        </div>
        <?php else: ?>
        <div class="card p-6 flex items-center gap-3 text-sm text-corteza/50">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Esta panadería aún no ha completado la caracterización.
        </div>
        <?php endif; ?>
        </div>
<div id="modal-foto"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-corteza/75 backdrop-blur-sm" onclick="cerrarFoto()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-trigo-light/50">
            <span id="modal-foto-titulo" class="font-medium text-corteza text-sm"></span>
            <button onclick="cerrarFoto()"
                    class="w-7 h-7 rounded-lg hover:bg-masa flex items-center justify-center
                           text-corteza/40 hover:text-corteza transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <img id="modal-foto-img" src="" alt="" class="w-full object-contain max-h-[70vh]">
    </div>
</div>

{{-- ══ MODAL: galería ════════════════════════════════════════════ --}}
<div id="modal-galeria"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-corteza/75 backdrop-blur-sm" onclick="cerrarGaleria()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-trigo-light/50">
            <span class="font-medium text-corteza text-sm">
                Fotos del proceso
                <span id="galeria-contador" class="font-normal text-corteza/40 ml-1 tabular-nums"></span>
            </span>
            <button onclick="cerrarGaleria()"
                    class="w-7 h-7 rounded-lg hover:bg-masa flex items-center justify-center
                           text-corteza/40 hover:text-corteza transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="relative bg-corteza/5">
            <img id="galeria-img" src="" alt="" class="w-full object-contain max-h-[55vh]">
            <button onclick="galeriaAnterior()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                           bg-white/90 hover:bg-white shadow-md flex items-center justify-center
                           transition-all hover:scale-105">
                <svg class="w-4 h-4 text-corteza" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="galeriaSiguiente()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                           bg-white/90 hover:bg-white shadow-md flex items-center justify-center
                           transition-all hover:scale-105">
                <svg class="w-4 h-4 text-corteza" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <div id="galeria-thumbs"
             class="flex gap-2 overflow-x-auto px-4 py-3 border-t border-trigo-light/40 scrollbar-hide">
        </div>
    </div>
</div>

{{-- ══ MODAL: confirmar desactivar ══════════════════════════════ --}}
@if($panaderia->activa)
<div id="modal-desactivar"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
    <div class="absolute inset-0 bg-corteza/40 backdrop-blur-sm"
         onclick="cerrarModalDesactivar()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
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
            Los usuarios no podrán iniciar sesión.
        </p>
        <div class="flex gap-3">
            <button type="button" onclick="cerrarModalDesactivar()"
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
@endif

<script>
// ── Modal foto individual ─────────────────────────────────────────
function abrirFoto(url, titulo) {
    document.getElementById('modal-foto-titulo').textContent = titulo;
    document.getElementById('modal-foto-img').src = url;
    const m = document.getElementById('modal-foto');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function cerrarFoto() {
    const m = document.getElementById('modal-foto');
    m.classList.remove('flex'); m.classList.add('hidden');
}

// ── Galería ───────────────────────────────────────────────────────
let _galeriaUrls = [], _galeriaIndex = 0;

function abrirGaleria(urls) {
    _galeriaUrls = urls; _galeriaIndex = 0;
    renderGaleria();
    const m = document.getElementById('modal-galeria');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function cerrarGaleria() {
    const m = document.getElementById('modal-galeria');
    m.classList.remove('flex'); m.classList.add('hidden');
}
function galeriaAnterior() {
    _galeriaIndex = (_galeriaIndex - 1 + _galeriaUrls.length) % _galeriaUrls.length;
    renderGaleria();
}
function galeriaSiguiente() {
    _galeriaIndex = (_galeriaIndex + 1) % _galeriaUrls.length;
    renderGaleria();
}
function renderGaleria() {
    document.getElementById('galeria-img').src = _galeriaUrls[_galeriaIndex];
    document.getElementById('galeria-contador').textContent =
        `(${_galeriaIndex + 1} / ${_galeriaUrls.length})`;
    document.getElementById('galeria-thumbs').innerHTML = _galeriaUrls.map((url, i) => `
        <button onclick="_galeriaIndex=${i};renderGaleria()"
                class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all
                       ${i === _galeriaIndex
                           ? 'border-trigo shadow-sm scale-105'
                           : 'border-transparent opacity-50 hover:opacity-80'}">
            <img src="${url}" class="w-full h-full object-cover">
        </button>
    `).join('');
}

// ── Modal desactivar ──────────────────────────────────────────────
function abrirModalDesactivar() {
    const m = document.getElementById('modal-desactivar');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}
function cerrarModalDesactivar() {
    const m = document.getElementById('modal-desactivar');
    if (m) { m.classList.remove('flex'); m.classList.add('hidden'); }
}

// ── Escape ────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarFoto(); cerrarGaleria(); cerrarModalDesactivar(); }
});

// ── Toggle caracterización ───────────────────────────────────────
function toggleCaracterizacion() {
    const c = document.getElementById('caracterizacion-container');
    const main = document.getElementById('main-content');
    const label = document.getElementById('btn-caracterizacion-label');
    if (!c || !label || !main) return;
    const isHidden = c.classList.contains('hidden');
    if (isHidden) {
        // show only caracterización
        c.classList.remove('hidden'); c.classList.add('block');
        main.classList.add('hidden');
        label.textContent = 'Cerrar caracterización';
        setTimeout(() => c.scrollIntoView({ behavior: 'smooth', block: 'start' }), 50);
    } else {
        // restore main view
        c.classList.remove('block'); c.classList.add('hidden');
        main.classList.remove('hidden');
        label.textContent = 'Panadería · Caracterización';
    }
}
</script>

@endsection