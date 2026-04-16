@extends('layouts.app')
@section('title', 'Mi Panadería')
@section('breadcrumb', 'Inicio')

@section('content')

@php $procesoActivo = $procesosActivos->first(); @endphp

{{-- ══ ESTILOS ══ --}}
<style>
  /* ── Tarjetas ── */
  .pan-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(107, 66, 38, 0.1);
    box-shadow: 0 1px 4px rgba(107, 66, 38, 0.05);
  }

  /* ── Botones ── */
  .pan-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.8rem;
    font-weight: 500;
    font-family: inherit;
    padding: 0.55rem 1rem;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.15s, box-shadow 0.15s, transform 0.15s;
    white-space: nowrap;
  }
  .pan-btn:hover         { transform: translateY(-1px); }
  .pan-btn svg           { flex-shrink: 0; }

  .pan-btn-ghost         { background: white; color: var(--color-corteza); border: 1px solid rgba(107, 66, 38, 0.15); opacity: 0.7; }
  .pan-btn-ghost:hover   { opacity: 1; box-shadow: 0 2px 8px rgba(107, 66, 38, 0.1); }

  .pan-btn-primary       { background: var(--color-verde); color: white; box-shadow: 0 2px 10px rgba(44, 95, 46, 0.22); }
  .pan-btn-primary:hover { opacity: 0.92; box-shadow: 0 4px 16px rgba(44, 95, 46, 0.3); }

  .pan-btn-verde         { background: var(--color-verde-dark); color: white; box-shadow: 0 2px 10px rgba(30, 69, 32, 0.2); }
  .pan-btn-verde:hover   { opacity: 0.92; }

  /* ── Badges ── */
  .pan-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.18rem 0.6rem;
    border-radius: 100px;
    font-size: 0.62rem;
    font-weight: 500;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.04em;
  }
  .pan-badge-amber { background: rgba(217, 119, 6, 0.1);  color: #b45309; }
  .pan-badge-green { background: rgba(44, 95, 46, 0.1);   color: var(--color-verde); }

  /* ── Tipografía de formulario ── */
  .pan-dt {
    display: block;
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--color-corteza);
    opacity: 0.38;
    margin-bottom: 0.2rem;
  }
  .pan-dd {
    font-size: 0.85rem;
    color: var(--color-corteza);
    opacity: 0.78;
    line-height: 1.4;
  }

  /* ── Divisor de sección ── */
  .pan-divider {
    border: none;
    border-top: 1px solid rgba(107, 66, 38, 0.08);
    margin: 1.25rem 0;
  }

  /* ── Animación ping ── */
  @keyframes ping {
    75%, 100% { transform: scale(2); opacity: 0; }
  }
  .animate-ping { animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite; }
</style>

{{-- ══════════════════════════════════════
     HEADER
══════════════════════════════════════ --}}
<div class="flex flex-wrap items-start justify-between gap-4 mb-6">

  <div>
    <div class="flex items-center gap-2 mb-1.5">
      <div class="w-1 h-5 rounded-full" style="background: var(--color-trigo)"></div>
      <span class="font-mono text-xs uppercase tracking-widest"
            style="color: var(--color-trigo); opacity: 0.7">
        Mi panadería
      </span>
    </div>

    <h1 class="font-display font-bold leading-tight"
        style="font-size: clamp(1.5rem, 5vw, 1.9rem); color: var(--color-corteza)">
      {{ $panaderia->nombre }}
    </h1>

    <p class="text-sm mt-0.5" style="color: var(--color-corteza); opacity: 0.4">
      {{ $panaderia->ciudad }} &nbsp;·&nbsp; {{ $panaderia->regional }}
    </p>
  </div>

  @if (!$procesoActivo)
    <a href="{{ route('panaderia.proceso.create') }}"
       class="pan-btn pan-btn-primary w-full sm:w-auto justify-center">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
           stroke-linecap="round" viewBox="0 0 24 24">
        <line x1="12" y1="5"  x2="12" y2="19"/>
        <line x1="5"  y1="12" x2="19" y2="12"/>
      </svg>
      Iniciar nuevo proceso
    </a>
  @endif

</div>

{{-- ══════════════════════════════════════
     PROCESO ACTIVO
══════════════════════════════════════ --}}
@if ($procesoActivo)
  @php
    $progreso  = $procesoActivo->progreso();
    $tienePan  = $procesoActivo->panes->isNotEmpty();
    $panActivo = !$tienePan && $procesoActivo->dias->count() >= 5;
  @endphp

  <div class="pan-card mb-5" style="border-left: 3px solid var(--color-trigo)">

    {{-- Encabezado de tarjeta --}}
    <div class="px-5 py-4 border-b flex flex-wrap items-center justify-between gap-3"
         style="border-color: rgba(107, 66, 38, 0.1)">

      <div class="flex items-center gap-3">
        {{-- Indicador pulsante --}}
        <div class="relative shrink-0">
          <div class="w-2.5 h-2.5 rounded-full" style="background: var(--color-trigo)"></div>
          <div class="absolute inset-0 rounded-full animate-ping"
               style="background: var(--color-trigo); opacity: 0.4"></div>
        </div>

        <div>
          <div class="flex items-center gap-2">
            <span class="pan-badge pan-badge-amber">En proceso</span>
            <span class="font-mono text-xs" style="color: var(--color-corteza); opacity: 0.35">
              #{{ $procesoActivo->id }}
            </span>
          </div>
          <h2 class="font-display font-semibold mt-0.5"
              style="font-size: 1.05rem; color: var(--color-corteza)">
            Proceso activo
            <span class="font-sans font-normal text-sm"
                  style="color: var(--color-corteza); opacity: 0.4">
              — iniciado {{ $procesoActivo->fecha_inicio->format('d/m/Y') }}
            </span>
          </h2>
        </div>
      </div>

      <a href="{{ route('panaderia.proceso.show', $procesoActivo->id) }}"
         class="pan-btn pan-btn-ghost w-full sm:w-auto justify-center">
        Ver detalle
      </a>
    </div>

    {{-- Cuerpo --}}
    <div class="p-5">

      {{-- Barra de progreso --}}
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs" style="color: var(--color-corteza); opacity: 0.45">
          Progreso del proceso
        </span>
        <span class="font-mono text-xs font-medium" style="color: var(--color-trigo)">
          {{ $progreso }}%
        </span>
      </div>
      <div class="h-1.5 rounded-full overflow-hidden mb-6"
           style="background: rgba(107, 66, 38, 0.1)">
        <div class="h-full rounded-full transition-all duration-700"
             style="width: {{ $progreso }}%;
                    background: linear-gradient(to right, var(--color-trigo-dark), var(--color-trigo))">
        </div>
      </div>

      {{-- Timeline: 5 días + Pan --}}
      <div class="-mx-1 overflow-x-auto pb-1">
        <div class="flex items-start gap-2 min-w-max px-1">

          @for ($d = 1; $d <= 5; $d++)
            @php
              $diaReg     = $procesoActivo->dias->firstWhere('dia', $d);
              $esSiguiente = $d === $procesoActivo->proximoDia();
            @endphp

            <div class="flex flex-col items-center gap-1.5 flex-1">
              <div class="w-full">

                {{-- Círculo del día --}}
                <div class="w-10 h-10 rounded-xl mx-auto flex items-center justify-center
                            text-sm font-medium mb-1.5 transition-all"
                     style="
                       @if ($diaReg)
                         background: var(--color-verde); color: white;
                         box-shadow: 0 2px 8px rgba(44, 95, 46, 0.25);
                       @elseif ($esSiguiente)
                         background: var(--color-trigo); color: var(--color-corteza);
                         box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.25),
                                     0 2px 8px rgba(200, 169, 110, 0.2);
                       @else
                         background: rgba(107, 66, 38, 0.08); color: var(--color-corteza);
                         opacity: 0.4;
                       @endif
                     ">
                  @if ($diaReg)
                    <svg width="15" height="15" fill="none" stroke="white" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                      <path d="M20 6L9 17l-5-5"/>
                    </svg>
                  @else
                    {{ $d }}
                  @endif
                </div>

                {{-- Etiqueta del día --}}
                <div class="text-center">
                  <div class="text-xs font-medium"
                       style="color: var(--color-corteza);
                              opacity: {{ $diaReg ? '0.7' : ($esSiguiente ? '0.9' : '0.3') }}">
                    Día {{ $d }}
                  </div>

                  @if ($diaReg)
                    <div class="font-mono text-xs mt-0.5"
                         style="color: var(--color-verde); opacity: 0.7">
                      pH {{ number_format($diaReg->ph ?? 0, 1) }}
                    </div>
                  @elseif ($esSiguiente)
                    <div class="text-xs mt-0.5"
                         style="color: var(--color-trigo); opacity: 0.8">
                      Pendiente
                    </div>
                  @endif
                </div>

              </div>
            </div>

            {{-- Conector entre días --}}
            @if ($d < 5)
              <div class="shrink-0 mt-5"
                   style="width: 16px; height: 1px;
                          background: {{ $diaReg ? 'rgba(44,95,46,.3)' : 'rgba(107,66,38,.1)' }}">
              </div>
            @endif
          @endfor

          {{-- Conector antes del Pan --}}
          <div class="shrink-0 mt-5"
               style="width: 16px; height: 1px;
                      background: {{ $procesoActivo->dias->count() >= 5 ? 'rgba(44,95,46,.3)' : 'rgba(107,66,38,.1)' }}">
          </div>

          {{-- Paso: Pan --}}
          <div class="flex flex-col items-center gap-1.5" style="width: 72px; flex-shrink: 0">
            <div class="w-full h-10 rounded-xl flex items-center justify-center gap-1
                        text-xs font-medium transition-all"
                 style="
                   @if ($tienePan)
                     background: var(--color-verde); color: white;
                     box-shadow: 0 2px 8px rgba(44, 95, 46, 0.25);
                   @elseif ($panActivo)
                     background: var(--color-trigo); color: var(--color-corteza);
                     box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.25);
                   @else
                     background: rgba(107, 66, 38, 0.08); color: var(--color-corteza);
                     opacity: 0.35;
                   @endif
                 ">
              @if ($tienePan)
                <svg width="14" height="14" fill="none" stroke="white" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
              @else
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C8 2 4 5.5 4 9c0 2.4 1.2 4.5 3 5.7V17h10v-2.3
                           c1.8-1.2 3-3.3 3-5.7 0-3.5-4-7-8-7zm-2 13h4v1a2 2 0 01-4 0v-1z"/>
                </svg>
              @endif
              Pan
            </div>

            <div class="text-xs text-center"
                 style="color: var(--color-corteza);
                        opacity: {{ $tienePan ? '0.7' : ($panActivo ? '0.9' : '0.3') }}">
              Elaboración
            </div>
          </div>

        </div>
      </div>

      {{-- ── Siguiente acción ── --}}
      <div class="mt-6 pt-5 border-t flex flex-col sm:flex-row sm:flex-wrap sm:items-center
                  sm:justify-between gap-3"
           style="border-color: rgba(107, 66, 38, 0.1)">

        <div>
          <div class="text-xs font-mono uppercase tracking-widest mb-0.5"
               style="color: var(--color-corteza); opacity: 0.35">
            Siguiente paso
          </div>
          <div class="text-sm font-medium" style="color: var(--color-corteza)">
            @if ($procesoActivo->proximoDia())
              Registrar los datos del Día {{ $procesoActivo->proximoDia() }}
            @elseif (!$tienePan)
              Registrar la elaboración del pan final
            @else
              Todo listo — puedes cerrar el proceso
            @endif
          </div>
        </div>

        @if ($procesoActivo->proximoDia())
          <a href="{{ route('panaderia.proceso.dia.create', [$procesoActivo->id, $procesoActivo->proximoDia()]) }}"
             class="pan-btn pan-btn-primary w-full sm:w-auto justify-center">
            Registrar Día {{ $procesoActivo->proximoDia() }}
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>

        @elseif (!$tienePan)
          <a href="{{ route('panaderia.proceso.pan.create', $procesoActivo->id) }}"
             class="pan-btn pan-btn-primary w-full sm:w-auto justify-center">
            Registrar elaboración de pan
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>

        @else
          <form method="POST"
                action="{{ route('panaderia.proceso.completar', $procesoActivo->id) }}"
                onsubmit="return confirm('¿Confirmas que el proceso está completo?')"
                class="w-full sm:w-auto">
            @csrf
            @method('PATCH')
            <button type="submit" class="pan-btn pan-btn-verde w-full justify-center">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                   stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
              Marcar como completado
            </button>
          </form>
        @endif

      </div>
    </div>
  </div>

{{-- ══════════════════════════════════════
     SIN PROCESO ACTIVO
══════════════════════════════════════ --}}
@else
  <div class="pan-card mb-5">
    <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
           style="background: rgba(200, 169, 110, 0.1)">
        <svg width="28" height="28" fill="none" stroke="var(--color-trigo)" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M12 2C8 2 4 5.5 4 9c0 2.4 1.2 4.5 3 5.7V17h10v-2.3
                   c1.8-1.2 3-3.3 3-5.7 0-3.5-4-7-8-7zm-2 13h4v1a2 2 0 01-4 0v-1z"/>
        </svg>
      </div>

      <h3 class="font-display font-semibold mb-2"
          style="font-size: 1.15rem; color: var(--color-corteza)">
        Sin proceso activo
      </h3>
      <p class="text-sm mb-6 max-w-xs"
         style="color: var(--color-corteza); opacity: 0.4; line-height: 1.65">
        Inicia un nuevo ciclo de fermentación para comenzar a registrar los datos diarios.
      </p>

      <a href="{{ route('panaderia.proceso.create') }}" class="pan-btn pan-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" viewBox="0 0 24 24">
          <line x1="12" y1="5"  x2="12" y2="19"/>
          <line x1="5"  y1="12" x2="19" y2="12"/>
        </svg>
        Iniciar nuevo proceso
      </a>
    </div>
  </div>
@endif

{{-- ══════════════════════════════════════
     DATOS DE LA PANADERÍA
══════════════════════════════════════ --}}
<div class="pan-card p-5">
  <div class="flex items-center gap-2 mb-4">
    <div class="w-1 h-4 rounded-full" style="background: var(--color-trigo)"></div>
    <h3 class="text-sm font-semibold" style="color: var(--color-corteza)">
      Datos de la panadería
    </h3>
  </div>

  <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
    @foreach ([
      ['Dirección',           $panaderia->direccion],
      ['Centro de formación', $panaderia->centro_formacion],
      ['Regional',            $panaderia->regional],
      ['Extensionista',       $panaderia->extensionista],
    ] as [$label, $value])
      <div>
        <dt class="pan-dt">{{ $label }}</dt>
        <dd class="pan-dd">{{ $value ?? '—' }}</dd>
      </div>
    @endforeach
  </dl>
</div>



@endsection