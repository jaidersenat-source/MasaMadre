@extends('layouts.app')
@section('title', 'Mis documentos')
@section('breadcrumb', 'Mis documentos')

@section('content')

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
  .pan-btn:hover       { transform: translateY(-1px); }
  .pan-btn svg         { flex-shrink: 0; }

  .pan-btn-ghost       { background: white; color: var(--color-corteza); border: 1px solid rgba(107, 66, 38, 0.15); opacity: 0.7; }
  .pan-btn-ghost:hover { opacity: 1; box-shadow: 0 2px 8px rgba(107, 66, 38, 0.1); }

  .pan-btn-primary       { background: var(--color-verde); color: white; box-shadow: 0 2px 10px rgba(44, 95, 46, 0.22); }
  .pan-btn-primary:hover { opacity: 0.92; box-shadow: 0 4px 16px rgba(44, 95, 46, 0.3); }

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
  .pan-badge-amber { background: rgba(217, 119, 6, 0.1); color: #b45309; }
  .pan-badge-green { background: rgba(44, 95, 46, 0.1);  color: var(--color-verde); }

  /* ── Tipografía ── */
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
        Proceso activo
      </span>
    </div>
    <h1 class="font-display font-bold leading-tight"
        style="font-size: clamp(1.5rem, 5vw, 1.9rem); color: var(--color-corteza)">
      Documentos y soportes
    </h1>
    <p class="text-sm mt-0.5" style="color: var(--color-corteza); opacity: 0.4">
      Gestiona las actas y fotos del proceso de fermentación
    </p>
  </div>
</div>

{{-- ══════════════════════════════════════
     ESTADO: SIN PROCESO ACTIVO
══════════════════════════════════════ --}}
@if (!$procesoActivo)
  <div class="pan-card">
    <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
           style="background: rgba(200, 169, 110, 0.1)">
        <svg width="28" height="28" fill="none" stroke="var(--color-trigo)" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                   a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <h3 class="font-display font-semibold mb-2"
          style="font-size: 1.15rem; color: var(--color-corteza)">
        Sin proceso activo
      </h3>
      <p class="text-sm max-w-xs" style="color: var(--color-corteza); opacity: 0.4; line-height: 1.65">
        Los documentos estarán disponibles una vez que inicies un proceso de fermentación.
      </p>
    </div>
  </div>

{{-- ══════════════════════════════════════
     DOCUMENTOS DEL PROCESO ACTIVO
══════════════════════════════════════ --}}
@else
  @php $doc = $procesoActivo->documento; @endphp

  <div class="pan-card overflow-hidden">

    {{-- Encabezado con progreso --}}
    <div class="px-5 py-4 border-b flex items-center justify-between"
         style="border-color: rgba(107, 66, 38, 0.1)">
      <div class="flex items-center gap-2">
        <div class="w-1 h-4 rounded-full" style="background: var(--color-trigo)"></div>
        <h3 class="text-sm font-semibold" style="color: var(--color-corteza)">
          Proceso #{{ $procesoActivo->id }}
        </h3>
      </div>

      <div class="flex items-center gap-2">
        <div class="w-20 h-1.5 rounded-full overflow-hidden"
             style="background: rgba(107, 66, 38, 0.1)">
          <div class="h-full rounded-full transition-all"
               style="width: {{ $doc->porcentajeCompletitud() }}%;
                      background: {{ $doc->porcentajeCompletitud() === 100
                          ? 'var(--color-verde)'
                          : 'var(--color-trigo)' }}">
          </div>
        </div>
        <span class="font-mono text-xs" style="color: var(--color-corteza); opacity: 0.5">
          {{ $doc->totalCompletados() }}/6
        </span>
      </div>
    </div>

    <div class="p-5 space-y-6">

      {{-- ── ACTAS ── --}}
      <section>
        <p class="pan-dt mb-3">Actas de inicio</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

          @foreach ([
            ['basica',        'Acta básica',       $doc->acta_basica_path,       $doc->acta_basica_subida_at],
            ['especializada', 'Acta especializada', $doc->acta_especializada_path, $doc->acta_especializada_subida_at],
          ] as [$tipo, $label, $path, $fecha])

            <div class="rounded-xl p-4 border"
                 style="border-color: rgba(107, 66, 38, 0.1);
                        background: {{ $path ? 'rgba(44,95,46,.04)' : 'rgba(107,66,38,.02)' }}">

              {{-- Estado --}}
              <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                  <div class="text-sm font-medium" style="color: var(--color-corteza)">
                    {{ $label }}
                  </div>
                  <div class="text-xs mt-0.5" style="color: var(--color-corteza); opacity: 0.35">
                    @if ($path && $fecha)
                      Subida {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                    @else
                      Pendiente
                    @endif
                  </div>
                </div>

                @if ($path)
                  <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor"
                       style="color: var(--color-verde)" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                             00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
                             1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                  </svg>
                @else
                  <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                       style="color: var(--color-corteza); opacity: 0.25" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
                    <path stroke-linecap="round" stroke-width="1.5" d="M12 8v4m0 4h.01"/>
                  </svg>
                @endif
              </div>

              {{-- Paso 1: Descargar --}}
              <a href="{{ route('panaderia.proceso.documentos.acta.descargar', [$procesoActivo->id, $tipo]) }}"
                 class="pan-btn pan-btn-ghost w-full justify-center text-xs mb-2">
                <svg width="12" height="12" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                  <path d="M12 3v13M5 16l7 7 7-7M3 21h18"/>
                </svg>
                Descargar, imprimir y firmar
              </a>

              {{-- Paso 2: Subir PDF firmado --}}
              <form method="POST"
                    action="{{ route('panaderia.proceso.documentos.acta.subir', [$procesoActivo->id, $tipo]) }}"
                    enctype="multipart/form-data">
                @csrf

                <label for="acta_{{ $tipo }}"
                       class="flex items-center gap-2 w-full cursor-pointer rounded-lg
                              px-3 py-2 border border-dashed transition-colors hover:border-current"
                       style="border-color: rgba(107, 66, 38, 0.2);
                              font-size: 0.75rem;
                              color: var(--color-corteza);
                              opacity: 0.6">
                  <svg width="12" height="12" fill="none" stroke="currentColor"
                       stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v13"/>
                  </svg>
                  <span id="label_acta_{{ $tipo }}">
                    {{ $path ? 'Reemplazar PDF firmado' : 'Subir PDF firmado (escaneado)' }}
                  </span>
                </label>
                <input type="file" id="acta_{{ $tipo }}" name="acta"
                       accept=".pdf" class="hidden"
                       onchange="actualizarLabel(this, 'label_acta_{{ $tipo }}')">

                @error('acta')
                  @if (request()->route('tipo') === $tipo)
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @endif
                @enderror

                <button type="submit"
                        class="pan-btn pan-btn-primary w-full justify-center mt-2 text-xs">
                  Guardar acta firmada
                </button>
              </form>

            </div>
          @endforeach

        </div>
      </section>

      {{-- ── FOTOS DE MEDICIÓN ── --}}
      <section>
        <p class="pan-dt mb-3">Fotos de medición inicial (tiras de pH y cloro)</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

          @foreach ([
            ['ph',    'Foto pH',    $doc->foto_ph_path,    $doc->urlFotoPh()],
            ['cloro', 'Foto cloro', $doc->foto_cloro_path, $doc->urlFotoCloro()],
          ] as [$tipo, $label, $path, $url])

            <div class="rounded-xl border overflow-hidden"
                 style="border-color: rgba(107, 66, 38, 0.1)">

              {{-- Preview o placeholder --}}
              @if ($path && $url)
                <div class="relative h-28 overflow-hidden" style="background: var(--color-masa)">
                  <img src="{{ $url }}" alt="{{ $label }}" class="w-full h-full object-cover">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                  <div class="absolute bottom-2 left-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                               00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
                               1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"/>
                    </svg>
                    <span class="text-white text-xs font-medium">Subida</span>
                  </div>
                </div>
              @else
                <div class="h-28 flex items-center justify-center"
                     style="background: rgba(107, 66, 38, 0.03)">
                  <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor"
                         stroke-width="1.2" style="color: var(--color-corteza); opacity: 0.2"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0
                               0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0
                               0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                      <path stroke-linecap="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div class="text-xs" style="color: var(--color-corteza); opacity: 0.3">Sin foto</div>
                  </div>
                </div>
              @endif

              {{-- Formulario subida --}}
              <form method="POST"
                    action="{{ route('panaderia.proceso.documentos.foto.subir', [$procesoActivo->id, $tipo]) }}"
                    enctype="multipart/form-data"
                    class="p-3">
                @csrf
                <div class="text-xs font-medium mb-2" style="color: var(--color-corteza)">
                  {{ $label }}
                </div>

                <label for="foto_{{ $tipo }}"
                       class="flex items-center gap-2 w-full cursor-pointer rounded-lg
                              px-3 py-2 border border-dashed transition-colors hover:border-current"
                       style="border-color: rgba(107, 66, 38, 0.2);
                              font-size: 0.7rem;
                              color: var(--color-corteza);
                              opacity: 0.55">
                  <svg width="11" height="11" fill="none" stroke="currentColor"
                       stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v13"/>
                  </svg>
                  <span id="label_foto_{{ $tipo }}">
                    {{ $path ? 'Cambiar foto' : 'Seleccionar JPG/PNG' }}
                  </span>
                </label>
                <input type="file" id="foto_{{ $tipo }}" name="foto"
                       accept="image/jpeg,image/png" class="hidden"
                       onchange="actualizarLabel(this, 'label_foto_{{ $tipo }}')">

                <button type="submit"
                        class="pan-btn pan-btn-primary w-full justify-center mt-2 text-xs">
                  Guardar foto
                </button>
              </form>

            </div>
          @endforeach

        </div>
      </section>

      {{-- ── FOTOS DEL PROCESO ── --}}
      <section>
        <p class="pan-dt mb-3">Fotos del proceso (días 1, 3 y 5)</p>
        @php $urlsFotoProceso = $doc->urlsFotoProceso(); @endphp

        {{-- Galería de fotos existentes --}}
        @if (!empty($urlsFotoProceso))
          <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3">
            @foreach ($urlsFotoProceso as $i => $url)
              <div class="relative rounded-xl overflow-hidden" style="aspect-ratio:1">
                <img src="{{ $url }}" alt="Foto proceso {{ $i + 1 }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                <span class="absolute bottom-1 left-2 text-white font-mono"
                      style="font-size:0.6rem">{{ $i + 1 }}</span>
              </div>
            @endforeach
          </div>
          <div class="flex items-center gap-1.5 mb-3 px-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor"
                 style="color: var(--color-verde)" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                       00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
                       1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"/>
            </svg>
            <span class="text-xs" style="color: var(--color-verde)">
              {{ count($urlsFotoProceso) }} foto(s) subida(s) — puedes reemplazarlas
            </span>
          </div>
        @else
          <div class="h-24 flex items-center justify-center rounded-xl mb-3"
               style="background: rgba(107, 66, 38, 0.03); border: 1px dashed rgba(107, 66, 38, 0.15)">
            <div class="text-center">
              <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor"
                   stroke-width="1.2" style="color: var(--color-corteza); opacity: 0.2"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0
                         0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0
                         0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <div class="text-xs" style="color: var(--color-corteza); opacity: 0.3">Sin fotos aún</div>
            </div>
          </div>
        @endif

        {{-- Formulario de subida --}}
        <form method="POST"
              action="{{ route('panaderia.proceso.documentos.fotos_proceso.subir', $procesoActivo->id) }}"
              enctype="multipart/form-data">
          @csrf

          <label for="fotos_proceso_input"
                 class="flex items-center gap-2 w-full cursor-pointer rounded-lg
                        px-3 py-2 border border-dashed transition-colors hover:border-current"
                 style="border-color: rgba(107, 66, 38, 0.2);
                        font-size: 0.72rem;
                        color: var(--color-corteza);
                        opacity: 0.6">
            <svg width="12" height="12" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
              <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v13"/>
            </svg>
            <span id="label_fotos_proceso">
              {{ empty($urlsFotoProceso) ? 'Seleccionar fotos (mín. 1, máx. 5 — JPG/PNG)' : 'Reemplazar fotos' }}
            </span>
          </label>
          <input type="file" id="fotos_proceso_input" name="fotos[]"
                 accept="image/jpeg,image/png" multiple class="hidden"
                 onchange="actualizarLabelMultiple(this, 'label_fotos_proceso')">

          @error('fotos')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
          @error('fotos.*')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror

          <button type="submit"
                  class="pan-btn pan-btn-primary w-full justify-center mt-2 text-xs">
            Guardar fotos del proceso
          </button>
        </form>
      </section>

    </div>
  </div>

@endif {{-- @if($procesoActivo) --}}

{{-- ── Flash de éxito ── --}}
@if (session('success'))
  <div id="flash-success"
       class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg"
       style="background: var(--color-verde); color: white; font-size: 0.8rem; max-width: 320px">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd"
            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
               00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
               1.414l2 2a1 1 0 001.414 0l4-4z"
            clip-rule="evenodd"/>
    </svg>
    {{ session('success') }}
  </div>

  <script>
    setTimeout(() => {
      const el = document.getElementById('flash-success');
      if (!el) return;
      el.style.transition = 'opacity 0.4s';
      el.style.opacity    = '0';
      setTimeout(() => el.remove(), 400);
    }, 3500);
  </script>
@endif

{{-- ══ SCRIPTS ══ --}}
<script>
  /** Muestra el nombre del archivo seleccionado en el label. */
  function actualizarLabel(input, labelId) {
    const label = document.getElementById(labelId);
    if (label && input.files?.length) {
      label.textContent = input.files[0].name;
    }
  }

  /** Para inputs múltiples: nombre si es uno, cantidad si son varios. */
  function actualizarLabelMultiple(input, labelId) {
    const label = document.getElementById(labelId);
    if (!label || !input.files?.length) return;

    label.textContent = input.files.length === 1
      ? input.files[0].name
      : `${input.files.length} fotos seleccionadas`;
  }
</script>

@endsection