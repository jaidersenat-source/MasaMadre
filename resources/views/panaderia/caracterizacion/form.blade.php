@extends('layouts.app')
@section('title', 'Caracterización de Panadería')

@section('content')

@php
$grupos = [
    'victima_violencia'    => 'Población víctima de la violencia.',
    'discapacidad'         => 'Población con discapacidad.',
    'indigena'             => 'Población indígena.',
    'afrocolombiana'       => 'Población afrocolombiana.',
    'comunidades_negras'   => 'Población comunidades negras.',
    'raizal'               => 'Población raizal.',
    'palenquera'           => 'Población palenquera.',
    'privada_libertad'     => 'Población privada de la libertad.',
    'victima_trata'        => 'Población víctima de trata de personas.',
    'tercera_edad'         => 'Tercera edad.',
    'adolescentes_jovenes' => 'Población adolescentes y jóvenes vulnerables.',
    'adolescentes_ley_penal'=> 'Adolescentes en conflictos por la ley penal.',
    'mujer_cabeza_hogar'   => 'Población mujer cabeza de hogar.',
    'reincorporacion'      => 'Población en proceso de reincorporación.',
    'reintegracion'        => 'Población en proceso de reintegración.',
    'victima_agente_quimico'=> 'Población víctima de ataque con agente químico.',
    'pueblo_rom'           => 'Pueblo Rom.',
    'mujeres_empresarias'  => 'Mujeres empresarias.',
    'ninguna'              => 'Ninguna de las anteriores.',
];
$old = old();
$c   = $caract ?? [];
@endphp

{{-- ══ ESTILOS ══ --}}
<style>
  @supports (view-transition-name: none) {
    ::view-transition-group(*),
    ::view-transition-old(*),
    ::view-transition-new(*) {
      animation-duration: 0.25s;
      animation-timing-function: cubic-bezier(0.19, 1, 0.22, 1);
    }
  }

  /* Contenedor del formulario */
  .caract-wrap {
    max-width: 860px;
    margin: 0 auto;
  }

  /* Stepper */
  .step-bar {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 2rem;
  }
  .step-dot {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    font-family: 'DM Mono', monospace;
    flex-shrink: 0;
    transition: background 0.3s, color 0.3s;
    border: 2px solid transparent;
  }
  .step-dot.done    { background: var(--color-verde); color: white; }
  .step-dot.active  { background: var(--color-trigo); color: var(--color-corteza-dark); border-color: var(--color-trigo-dark); }
  .step-dot.pending { background: var(--color-masa-dark); color: var(--color-corteza); opacity: .5; }
  .step-line {
    flex: 1;
    height: 2px;
    background: var(--color-masa-dark);
    transition: background 0.3s;
  }
  .step-line.done { background: var(--color-verde); }

  /* Secciones de paso */
  .caract-step { display: none; }
  .caract-step.active { display: block; }

  /* Radio / checkbox visual */
  .opt-radio,
  .opt-check {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.9rem;
    border-radius: 10px;
    border: 1.5px solid var(--color-trigo-light);
    background: var(--color-miga);
    cursor: pointer;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--color-corteza);
    transition: border-color 0.15s, background 0.15s;
    user-select: none;
  }
  .opt-radio:has(input:checked),
  .opt-check:has(input:checked) {
    border-color: var(--color-trigo);
    background: rgba(200, 169, 110, 0.12);
    color: var(--color-corteza-dark);
  }
  .opt-radio input,
  .opt-check input { accent-color: var(--color-trigo); }

  /* Grupo especial — tabla */
  .grupo-table { width: 100%; border-collapse: collapse; }
  .grupo-table th {
    padding: 0.4rem 0.3rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--color-corteza);
    text-align: center;
    border-bottom: 2px solid var(--color-trigo-light);
    background: rgba(200, 169, 110, 0.08);
    font-family: 'DM Mono', monospace;
  }
  .grupo-table td {
    padding: 0.4rem 0.3rem;
    text-align: center;
    border-bottom: 1px solid var(--color-masa-dark);
  }
  .grupo-table td:first-child {
    text-align: left;
    padding-left: 0.5rem;
    font-size: 0.78rem;
    color: var(--color-corteza);
  }
  .grupo-table tr:last-child td { border-bottom: none; }
  .grupo-table tr:hover td { background: rgba(200,169,110,.04); }
  .grupo-table input[type="radio"] {
    accent-color: var(--color-trigo);
    width: 1rem;
    height: 1rem;
    cursor: pointer;
  }

  /* Sección título */
  .step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    color: var(--color-corteza-dark);
    font-weight: 700;
    margin-bottom: 0.25rem;
  }
  .step-subtitle {
    font-size: 0.78rem;
    color: var(--color-corteza);
    opacity: .5;
    margin-bottom: 1.5rem;
  }

  /* Botones de navegación */
  .caract-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--color-trigo-light);
  }

  /* Número de pregunta */
  .q-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    background: var(--color-trigo);
    color: var(--color-corteza-dark);
    font-size: 0.65rem;
    font-weight: 700;
    font-family: 'DM Mono', monospace;
    flex-shrink: 0;
    margin-right: 0.4rem;
  }

  .q-label {
    display: flex;
    align-items: flex-start;
    gap: 0;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--color-corteza);
    margin-bottom: 0.5rem;
  }
</style>

<div class="caract-wrap">

  {{-- Encabezado --}}
  <div class="mb-6">
    <div class="flex items-center gap-3 mb-1">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-verde-light text-verde text-xs font-semibold tracking-wide uppercase">
        SENA · Huila
      </span>
      <span class="text-corteza/40 text-xs">Formulario obligatorio · Primera vez</span>
    </div>
    <h1 class="font-display text-2xl font-bold text-corteza-dark mt-1">Caracterización de panadería</h1>
    <p class="text-sm text-corteza/50 mt-0.5">Complete los 8 pasos para acceder al sistema.</p>
  </div>

  {{-- Barra de progreso --}}
  <div class="step-bar" id="stepBar" aria-label="Progreso del formulario"></div>
  <div class="flex justify-between text-xs text-corteza/40 -mt-5 mb-8 px-0.5" id="stepLabels"></div>

  {{-- ERRORES DE VALIDACIÓN --}}
  @if($errors->any())
  <div class="alert-error mb-6">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
    </svg>
    <div>
      <p class="font-semibold">Por favor corrija los siguientes errores:</p>
      <ul class="mt-1 list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  </div>
  @endif

  {{-- ══ FORMULARIO ══ --}}
  <form method="POST" action="{{ route('panaderia.caracterizacion.store') }}" id="caractForm">
    @csrf

    {{-- ════════════════════════════════════
         PASO 1 · Identificación (P1–P10)
    ════════════════════════════════════ --}}
    <div class="caract-step {{ $errors->any() && old('_paso','1') == '1' ? 'active' : '' }}" data-step="1">
      <p class="step-title">Identificación</p>
      <p class="step-subtitle">Preguntas 1 – 10 · Datos del responsable y la panadería</p>

      <div class="space-y-5">

        {{-- P1 Regional (estático) --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">1</span>Regional</p>
          <p class="px-4 py-2 rounded-xl bg-masa-dark text-corteza font-medium text-sm w-max">HUILA</p>
          <input type="hidden" name="regional" value="HUILA">
        </div>

        {{-- P2 Centro de formación (estático) --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">2</span>Centro de formación</p>
          <p class="px-4 py-2 rounded-xl bg-masa-dark text-corteza font-medium text-sm">CENTRO DE FORMACIÓN AGROINDUSTRIAL LA ANGOSTURA</p>
          <input type="hidden" name="centro_formacion" value="CENTRO DE FORMACIÓN AGROINDUSTRIAL LA ANGOSTURA">
        </div>

        {{-- P3 Nombres y apellidos --}}
        <div class="card p-4">
          <label class="q-label" for="nombres_apellidos"><span class="q-num">3</span>Nombres y apellidos completos</label>
          <input type="text" id="nombres_apellidos" name="nombres_apellidos"
                 value="{{ old('nombres_apellidos') }}"
                 placeholder="Ej: Juan Carlos Pérez Gómez"
                 maxlength="200"
                 class="input @error('nombres_apellidos') border-red-400 @enderror">
          @error('nombres_apellidos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P4 Cédula --}}
        <div class="card p-4">
          <label class="q-label" for="cedula"><span class="q-num">4</span>Número de cédula de ciudadanía</label>
          <input type="text" id="cedula" name="cedula"
                 value="{{ old('cedula') }}"
                 placeholder="Ej: 1234567890"
                 maxlength="20"
                 class="input @error('cedula') border-red-400 @enderror">
          @error('cedula') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P5 Rol --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">5</span>Rol que ejerce en la panadería</p>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['Propietario','Administrador','Panadero','Vendedor'] as $rol)
              <label class="opt-radio">
                <input type="radio" name="rol" value="{{ $rol }}" {{ old('rol') == $rol ? 'checked' : '' }}>
                {{ $rol }}
              </label>
            @endforeach
          </div>
          @error('rol') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P6 Extensionista --}}
        <div class="card p-4">
          <label class="q-label" for="extensionista"><span class="q-num">6</span>Nombres y apellidos del profesional extensionista responsable</label>
          <input type="text" id="extensionista" name="extensionista"
                 value="{{ old('extensionista', $panaderia?->extensionista) }}"
                 placeholder="Ej: María Fernanda López"
                 maxlength="200"
                 class="input @error('extensionista') border-red-400 @enderror">
          @error('extensionista') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P7 Nombre panadería (estático) --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">7</span>Nombre de la panadería</p>
          <p class="px-4 py-2 rounded-xl bg-masa-dark text-corteza font-medium text-sm w-max">{{ $panaderia?->nombre ?? '—' }}</p>
        </div>

        {{-- P8 Formalización --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">8</span>¿La panadería cuenta con formalización?</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Sí','No'] as $op)
              <label class="opt-radio">
                <input type="radio" name="formalizacion" value="{{ $op }}" {{ old('formalizacion') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('formalizacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P9 Tipo documento panadería --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">9</span>Tipo de documento de identificación de la panadería</p>
          <div class="flex gap-2 mt-1">
            @foreach(['NIT','Cédula de ciudadanía'] as $op)
              <label class="opt-radio">
                <input type="radio" name="tipo_documento_panaderia" value="{{ $op }}" {{ old('tipo_documento_panaderia') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('tipo_documento_panaderia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P10 Número documento --}}
        <div class="card p-4">
          <label class="q-label" for="numero_documento_panaderia"><span class="q-num">10</span>Número del documento de identificación de la panadería</label>
          <input type="text" id="numero_documento_panaderia" name="numero_documento_panaderia"
                 value="{{ old('numero_documento_panaderia') }}"
                 placeholder="Ej: 900123456-1"
                 maxlength="20"
                 class="input @error('numero_documento_panaderia') border-red-400 @enderror">
          @error('numero_documento_panaderia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div><!-- /space-y-5 -->
      <div class="caract-nav">
        <span></span>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 1 -->


    {{-- ════════════════════════════════════
         PASO 2 · Ubicación (P11–P16)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="2">
      <p class="step-title">Ubicación</p>
      <p class="step-subtitle">Preguntas 11 – 16 · Localización de la panadería</p>

      <div class="space-y-5">

        {{-- P11 Ciudad --}}
        <div class="card p-4">
          <label class="q-label" for="ciudad_municipio"><span class="q-num">11</span>Ciudad o municipio donde se encuentra ubicada la panadería</label>
          <input type="text" id="ciudad_municipio" name="ciudad_municipio"
                 value="{{ old('ciudad_municipio', $panaderia?->ciudad) }}"
                 placeholder="Ej: Pitalito"
                 maxlength="100"
                 class="input @error('ciudad_municipio') border-red-400 @enderror">
          @error('ciudad_municipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P12 Zona --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">12</span>La panadería se ubica en zona</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Urbana','Rural'] as $op)
              <label class="opt-radio">
                <input type="radio" name="zona" value="{{ $op }}" {{ old('zona') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('zona') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P13 Barrio / vereda --}}
        <div class="card p-4">
          <label class="q-label" for="barrio_vereda"><span class="q-num">13</span>Nombre del barrio o vereda</label>
          <input type="text" id="barrio_vereda" name="barrio_vereda"
                 value="{{ old('barrio_vereda') }}"
                 placeholder="Ej: Barrio El Centro"
                 maxlength="100"
                 class="input @error('barrio_vereda') border-red-400 @enderror">
          @error('barrio_vereda') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P14 Dirección --}}
        <div class="card p-4">
          <label class="q-label" for="direccion"><span class="q-num">14</span>Dirección completa o datos orientadores</label>
          <input type="text" id="direccion" name="direccion"
                 value="{{ old('direccion', $panaderia?->direccion) }}"
                 placeholder="Ej: Carrera 5 # 10-23"
                 maxlength="250"
                 class="input @error('direccion') border-red-400 @enderror">
          @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P15 Celular --}}
        <div class="card p-4">
          <label class="q-label" for="celular_contacto"><span class="q-num">15</span>Celular de contacto de la panadería</label>
          <input type="tel" id="celular_contacto" name="celular_contacto"
                 value="{{ old('celular_contacto') }}"
                 placeholder="Ej: 3001234567"
                 maxlength="20"
                 class="input @error('celular_contacto') border-red-400 @enderror">
          @error('celular_contacto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P16 Estrato --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">16</span>Estrato socioeconómico donde está localizada la panadería</p>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['1','2','3','4','5','6'] as $e)
              <label class="opt-radio">
                <input type="radio" name="estrato" value="{{ $e }}" {{ old('estrato') == $e ? 'checked' : '' }}>
                Estrato {{ $e }}
              </label>
            @endforeach
          </div>
          @error('estrato') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div>
      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 2 -->


    {{-- ════════════════════════════════════
         PASO 3 · Tiempo, edades y género (P17–P28)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="3">
      <p class="step-title">Tiempo, edades y género</p>
      <p class="step-subtitle">Preguntas 17 – 28 · Información sobre los empleados</p>

      <div class="space-y-5">

        {{-- P17 Años funcionamiento --}}
        <div class="card p-4">
          <label class="q-label" for="anos_funcionamiento"><span class="q-num">17</span>¿Cuántos años de funcionamiento tiene la panadería?</label>
          <input type="number" id="anos_funcionamiento" name="anos_funcionamiento"
                 value="{{ old('anos_funcionamiento') }}"
                 min="0" max="200" placeholder="Ej: 5"
                 class="input @error('anos_funcionamiento') border-red-400 @enderror" style="max-width:160px">
          @error('anos_funcionamiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P18 Número empleados --}}
        <div class="card p-4">
          <label class="q-label" for="num_empleados"><span class="q-num">18</span>Indique el número de empleados que tiene la panadería</label>
          <input type="number" id="num_empleados" name="num_empleados"
                 value="{{ old('num_empleados') }}"
                 min="0" max="9999" placeholder="Ej: 4"
                 class="input @error('num_empleados') border-red-400 @enderror" style="max-width:160px">
          @error('num_empleados') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P19–P22 Rangos de edad --}}
        <div class="card p-4">
          <p class="font-semibold text-sm text-corteza mb-3">Rangos de edad de los empleados</p>
          <div class="grid grid-cols-2 gap-4">
            @php
            $edades = [
              19 => ['empleados_18_28', '18 a 28 años'],
              20 => ['empleados_29_40', '29 a 40 años'],
              21 => ['empleados_41_55', '41 a 55 años'],
              22 => ['empleados_55_mas', '55 años o más'],
            ];
            @endphp
            @foreach($edades as $num => [$field, $label])
              <div>
                <label class="q-label" for="{{ $field }}"><span class="q-num">{{ $num }}</span>{{ $label }}</label>
                <input type="number" id="{{ $field }}" name="{{ $field }}"
                       value="{{ old($field, 0) }}"
                       min="0" placeholder="0"
                       class="input @error($field) border-red-400 @enderror">
                @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            @endforeach
          </div>
        </div>

        {{-- P23–P26 Género --}}
        <div class="card p-4">
          <p class="font-semibold text-sm text-corteza mb-3">Género de los empleados</p>
          <div class="grid grid-cols-2 gap-4">
            @php
            $generos = [
              23 => ['empleados_femenino',    'Género FEMENINO'],
              24 => ['empleados_masculino',   'Género MASCULINO'],
              25 => ['empleados_otro_genero', 'Otro género'],
              26 => ['empleados_no_responde', 'Prefieren no responder'],
            ];
            @endphp
            @foreach($generos as $num => [$field, $label])
              <div>
                <label class="q-label" for="{{ $field }}"><span class="q-num">{{ $num }}</span>{{ $label }}</label>
                <input type="number" id="{{ $field }}" name="{{ $field }}"
                       value="{{ old($field, 0) }}"
                       min="0" placeholder="0"
                       class="input @error($field) border-red-400 @enderror">
                @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            @endforeach
          </div>
        </div>

        {{-- P27–P28 Cabeza de hogar --}}
        <div class="card p-4">
          <p class="font-semibold text-sm text-corteza mb-3">Cabeza de hogar</p>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="q-label" for="mujeres_cabeza_hogar"><span class="q-num">27</span>Mujeres cabeza de hogar</label>
              <input type="number" id="mujeres_cabeza_hogar" name="mujeres_cabeza_hogar"
                     value="{{ old('mujeres_cabeza_hogar', 0) }}"
                     min="0" placeholder="0"
                     class="input @error('mujeres_cabeza_hogar') border-red-400 @enderror">
              @error('mujeres_cabeza_hogar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="q-label" for="hombres_cabeza_hogar"><span class="q-num">28</span>Hombres cabeza de hogar</label>
              <input type="number" id="hombres_cabeza_hogar" name="hombres_cabeza_hogar"
                     value="{{ old('hombres_cabeza_hogar', 0) }}"
                     min="0" placeholder="0"
                     class="input @error('hombres_cabeza_hogar') border-red-400 @enderror">
              @error('hombres_cabeza_hogar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>

      </div>
      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 3 -->


    {{-- ════════════════════════════════════
         PASO 4 · Grupos especiales (P29)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="4">
      <p class="step-title">Grupos especiales</p>
      <p class="step-subtitle">Pregunta 29 · Indique cuántas personas de la panadería pertenecen a cada grupo</p>

      <div class="card p-4 overflow-x-auto">
        <p class="q-label mb-3"><span class="q-num">29</span>¿Alguna persona de la panadería pertenece o se identifica con alguno de los siguientes grupos?</p>
        <table class="grupo-table">
          <thead>
            <tr>
              <th class="text-left pl-2" style="min-width:200px">Grupo</th>
              @foreach(['0','1','2','3','4','5','6 o Más'] as $col)
                <th>{{ $col }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($grupos as $key => $label)
              @php $vals = ['0','1','2','3','4','5','6_o_mas']; @endphp
              <tr>
                <td>{{ $label }}</td>
                @foreach($vals as $val)
                  <td>
                    <input type="radio"
                           name="grupos_especiales[{{ $key }}]"
                           value="{{ $val }}"
                           {{ old("grupos_especiales.$key", '0') === $val ? 'checked' : '' }}>
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
        @error('grupos_especiales') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
      </div>

      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 4 -->


    {{-- ════════════════════════════════════
         PASO 5 · Nivel educativo (P30–P37)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="5">
      <p class="step-title">Nivel educativo</p>
      <p class="step-subtitle">Preguntas 30 – 37 · Número de empleados por nivel de estudio</p>

      <div class="card p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          @php
          $educacion = [
            30 => ['edu_sin_estudios', 'Sin nivel educativo / sin estudios'],
            31 => ['edu_primaria',     'Básica primaria (Grados 1° a 5°)'],
            32 => ['edu_secundaria',   'Básica secundaria (Grados 6° a 9°)'],
            33 => ['edu_media',        'Educación media (Grados 10° a 11°)'],
            34 => ['edu_tecnico',      'Técnico'],
            35 => ['edu_tecnologo',    'Tecnólogo'],
            36 => ['edu_pregrado',     'Universitario / pregrado'],
            37 => ['edu_posgrado',     'Posgrado'],
          ];
          @endphp
          @foreach($educacion as $num => [$field, $label])
            <div>
              <label class="q-label" for="{{ $field }}"><span class="q-num">{{ $num }}</span>{{ $label }}</label>
              <input type="number" id="{{ $field }}" name="{{ $field }}"
                     value="{{ old($field, 0) }}"
                     min="0" placeholder="0"
                     class="input @error($field) border-red-400 @enderror">
              @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
          @endforeach
        </div>
      </div>

      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 5 -->


    {{-- ════════════════════════════════════
         PASO 6 · Masa madre (P38–P44)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="6">
      <p class="step-title">Masa madre</p>
      <p class="step-subtitle">Preguntas 38 – 44 · Conocimiento y uso de masa madre</p>

      <div class="space-y-5">

        {{-- P38 Kilos harina --}}
        <div class="card p-4">
          <label class="q-label" for="kilos_harina_dia"><span class="q-num">38</span>¿Cuántos kilos de harinas gasta al día en la panadería?</label>
          <input type="number" id="kilos_harina_dia" name="kilos_harina_dia"
                 value="{{ old('kilos_harina_dia') }}"
                 step="0.5" min="0" placeholder="Ej: 25"
                 class="input @error('kilos_harina_dia') border-red-400 @enderror" style="max-width:200px">
          @error('kilos_harina_dia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P39 Tipos de pan --}}
        <div class="card p-4">
          <label class="q-label" for="tipos_pan"><span class="q-num">39</span>¿Cuáles son los tipos de panes que más produce?</label>
          <textarea id="tipos_pan" name="tipos_pan" rows="3" maxlength="500"
                    placeholder="Ej: Pan francés, almojábanas, roscones…"
                    class="input @error('tipos_pan') border-red-400 @enderror">{{ old('tipos_pan') }}</textarea>
          @error('tipos_pan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P40 Sabe masa madre --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">40</span>¿Sabe hacer panes con masa madre?</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Si','No'] as $op)
              <label class="opt-radio">
                <input type="radio" name="sabe_masa_madre" value="{{ $op }}" {{ old('sabe_masa_madre') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('sabe_masa_madre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P41 Usa masa madre --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">41</span>¿En la panadería utiliza masa madre?</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Si','No'] as $op)
              <label class="opt-radio">
                <input type="radio" name="usa_masa_madre" value="{{ $op }}" {{ old('usa_masa_madre') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('usa_masa_madre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P42 Prefermentos --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">42</span>¿Utiliza prefermentos en el proceso de producción? Seleccione los que emplea:</p>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['Biga','Poolish','Esponja','No uso prefermentos'] as $pref)
              <label class="opt-check">
                <input type="checkbox" name="prefermentos[]" value="{{ $pref }}"
                       {{ in_array($pref, old('prefermentos', [])) ? 'checked' : '' }}>
                {{ $pref }}
              </label>
            @endforeach
          </div>
          @error('prefermentos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P43 Recibió transferencia --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">43</span>¿Ha recibido directamente en su panadería una transferencia tecnológica en temas de panificación para usar masa madre?</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Si','No'] as $op)
              <label class="opt-radio">
                <input type="radio" name="recibio_transferencia" value="{{ $op }}" {{ old('recibio_transferencia') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('recibio_transferencia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P44 Pan deseado --}}
        <div class="card p-4">
          <label class="q-label" for="pan_masa_madre_deseado"><span class="q-num">44</span>¿Con qué tipo de pan le gustaría aplicar el uso de masa madre?</label>
          <textarea id="pan_masa_madre_deseado" name="pan_masa_madre_deseado" rows="2" maxlength="500"
                    placeholder="Ej: Pan campesino, baguette…"
                    class="input @error('pan_masa_madre_deseado') border-red-400 @enderror">{{ old('pan_masa_madre_deseado') }}</textarea>
          @error('pan_masa_madre_deseado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div>
      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 6 -->


    {{-- ════════════════════════════════════
         PASO 7 · Expectativas (P45–P47)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="7">
      <p class="step-title">Expectativas sobre el proyecto</p>
      <p class="step-subtitle">Preguntas 45 – 47 · Sus metas con la masa madre</p>

      <div class="space-y-5">

        <div class="card p-4">
          <label class="q-label" for="expectativa_aprendizaje"><span class="q-num">45</span>¿Qué espera aprender con el uso de la masa madre?</label>
          <textarea id="expectativa_aprendizaje" name="expectativa_aprendizaje" rows="3" maxlength="1000"
                    placeholder="Comparta sus expectativas…"
                    class="input @error('expectativa_aprendizaje') border-red-400 @enderror">{{ old('expectativa_aprendizaje') }}</textarea>
          @error('expectativa_aprendizaje') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="card p-4">
          <label class="q-label" for="preocupacion_masa_madre"><span class="q-num">46</span>¿Qué le preocupa del uso de la masa madre?</label>
          <textarea id="preocupacion_masa_madre" name="preocupacion_masa_madre" rows="3" maxlength="1000"
                    placeholder="Comparta sus inquietudes…"
                    class="input @error('preocupacion_masa_madre') border-red-400 @enderror">{{ old('preocupacion_masa_madre') }}</textarea>
          @error('preocupacion_masa_madre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="card p-4">
          <label class="q-label" for="expectativa_proyecto"><span class="q-num">47</span>¿Qué le gustaría que este proyecto dejara en su panadería?</label>
          <textarea id="expectativa_proyecto" name="expectativa_proyecto" rows="3" maxlength="1000"
                    placeholder="Ej: mejor calidad del pan, más clientes…"
                    class="input @error('expectativa_proyecto') border-red-400 @enderror">{{ old('expectativa_proyecto') }}</textarea>
          @error('expectativa_proyecto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div>
      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="button" onclick="goNext()" class="btn-primary">
          Siguiente
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div><!-- /step 7 -->


    {{-- ════════════════════════════════════
         PASO 8 · Condiciones económicas (P48–P51)
    ════════════════════════════════════ --}}
    <div class="caract-step" data-step="8">
      <p class="step-title">Condiciones económicas y laborales</p>
      <p class="step-subtitle">Preguntas 48 – 51 · Situación actual de la panadería</p>

      <div class="space-y-5">

        {{-- P48 Situación económica --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">48</span>¿Cómo describiría la situación económica actual de la panadería?</p>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['Muy difícil','Difícil','Estable','Buena'] as $op)
              <label class="opt-radio">
                <input type="radio" name="situacion_economica" value="{{ $op }}" {{ old('situacion_economica') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('situacion_economica') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P49 Cierre o reducción --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">49</span>¿Ha tenido que cerrar o reducir producción en los últimos años?</p>
          <div class="flex gap-2 mt-1">
            @foreach(['Sí','No'] as $op)
              <label class="opt-radio">
                <input type="radio" name="cierre_reduccion" value="{{ $op }}" {{ old('cierre_reduccion') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('cierre_reduccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P50 Dificultad sostener --}}
        <div class="card p-4">
          <label class="q-label" for="dificultad_sostener"><span class="q-num">50</span>¿Qué es lo más difícil de sostener la panadería hoy?</label>
          <textarea id="dificultad_sostener" name="dificultad_sostener" rows="3" maxlength="1000"
                    placeholder="Ej: Los costos de insumos, la competencia…"
                    class="input @error('dificultad_sostener') border-red-400 @enderror">{{ old('dificultad_sostener') }}</textarea>
          @error('dificultad_sostener') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- P51 Nuevas técnicas --}}
        <div class="card p-4">
          <p class="q-label"><span class="q-num">51</span>¿Considera que aprender nuevas técnicas puede mejorar sus ingresos?</p>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach(['Sí','No','No sabe'] as $op)
              <label class="opt-radio">
                <input type="radio" name="nuevas_tecnicas_ingresos" value="{{ $op }}" {{ old('nuevas_tecnicas_ingresos') == $op ? 'checked' : '' }}>
                {{ $op }}
              </label>
            @endforeach
          </div>
          @error('nuevas_tecnicas_ingresos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

      </div>

      <div class="caract-nav">
        <button type="button" onclick="goPrev()" class="btn-secondary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Atrás
        </button>
        <button type="submit" class="btn-verde gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Enviar caracterización
        </button>
      </div>
    </div><!-- /step 8 -->

  </form><!-- /caractForm -->

</div><!-- /caract-wrap -->

{{-- ══ SCRIPT ══ --}}
<script>
(function () {
  const TOTAL = 8;
  const LABELS = [
    'Identificación', 'Ubicación', 'Empleados',
    'Grupos esp.', 'Educación', 'Masa madre',
    'Expectativas', 'Económico'
  ];

  let current = 1;

  /* ── Stepper ── */
  function buildStepper() {
    const bar    = document.getElementById('stepBar');
    const labels = document.getElementById('stepLabels');
    bar.innerHTML = '';
    labels.innerHTML = '';

    for (let i = 1; i <= TOTAL; i++) {
      const dot = document.createElement('div');
      dot.className = 'step-dot ' + (i < current ? 'done' : i === current ? 'active' : 'pending');
      dot.id = 'dot-' + i;
      dot.textContent = i < current ? '✓' : i;
      dot.title = LABELS[i - 1];
      dot.style.cursor = i < current ? 'pointer' : 'default';
      if (i < current) dot.addEventListener('click', () => jumpTo(i));
      bar.appendChild(dot);

      if (i < TOTAL) {
        const line = document.createElement('div');
        line.className = 'step-line ' + (i < current ? 'done' : '');
        line.id = 'line-' + i;
        bar.appendChild(line);
      }
    }

    // Only first and last label
    const s1 = document.createElement('span');
    s1.textContent = LABELS[0];
    labels.appendChild(s1);
    const s2 = document.createElement('span');
    s2.textContent = LABELS[TOTAL - 1];
    labels.appendChild(s2);
  }

  function showStep(n) {
    document.querySelectorAll('.caract-step').forEach(el => {
      el.classList.remove('active');
    });
    const next = document.querySelector(`[data-step="${n}"]`);
    if (next) next.classList.add('active');
    current = n;
    buildStepper();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function transition(n) {
    if (document.startViewTransition) {
      document.startViewTransition(() => showStep(n));
    } else {
      showStep(n);
    }
  }

  window.goNext = function () { if (current < TOTAL) transition(current + 1); };
  window.goPrev = function () { if (current > 1)    transition(current - 1); };
  window.jumpTo = function (n) { if (n < current)   transition(n); };

  /* ── Inicialización ── */
  // Si hay errores de validación, volver al paso 1
  const hasErrors = document.querySelector('.alert-error') !== null;
  showStep(hasErrors ? 1 : 1);
})();
</script>

@endsection
