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
        <a href="{{ route('admin.exportar.proceso.excel', ['proceso_id' => $registro->id]) }}"
           class="btn-secondary text-xs w-full sm:w-auto justify-center">
            Exportar Excel (formato informe)
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

{{-- ══ Documentos y fotos ══════════════════════════════════════ --}}
@php $doc = $registro->documento; @endphp
@if($doc)
@php
    $tieneCaract = $registro->panaderia->tieneCaracterizacion();
    $resumen     = $doc->resumenEstado($tieneCaract);
    $pct         = $doc->porcentajeCompletitud($tieneCaract);
@endphp
<div class="card overflow-hidden mb-6 mt-6">
    <div class="px-6 py-4 border-b border-trigo-light/50 flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-corteza">Documentos y soportes</h2>
            <p class="text-xs text-corteza/40 mt-0.5">{{ $doc->totalCompletados($tieneCaract) }}/6 elementos completados</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-24 h-2 bg-masa-dark rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ $pct === 100 ? 'bg-verde' : ($pct >= 50 ? 'bg-trigo' : 'bg-red-300') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
            <span class="text-xs font-semibold tabular-nums {{ $pct === 100 ? 'text-verde' : ($pct >= 50 ? 'text-trigo-dark' : 'text-red-400') }}">
                {{ $pct }}%
            </span>
        </div>
    </div>

    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($resumen as $clave => $item)
        <div class="flex items-start gap-3 p-3 rounded-xl
            {{ $item['completo'] ? 'bg-verde-light/40' : 'bg-masa/50' }}">

            {{-- Indicador --}}
            <div class="shrink-0 mt-0.5">
                @if($item['completo'])
                    <div class="w-6 h-6 rounded-full bg-verde/20 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-verde" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @else
                    <div class="w-6 h-6 rounded-full bg-corteza/8 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-corteza/20"></div>
                    </div>
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <div class="text-xs font-medium text-corteza leading-tight">{{ $item['label'] }}</div>
                @if($item['completo'] && !empty($item['fecha']))
                    <div class="text-[10px] text-corteza/40 mt-0.5">
                        {{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y H:i') }}
                    </div>
                @elseif(!$item['completo'])
                    <div class="text-[10px] text-corteza/30 mt-0.5">Pendiente</div>
                @endif

                @if($item['completo'])
                    {{-- Actas: descargar PDF --}}
                    @if(in_array($clave, ['acta_basica', 'acta_especializada']) && !empty($item['url']))
                        <a href="{{ $item['url'] }}" target="_blank"
                           class="inline-flex items-center gap-1 text-[11px] text-trigo-dark hover:text-corteza
                                  transition-colors mt-1.5 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Descargar PDF
                        </a>
                    @endif
                    {{-- Foto individual: pH / cloro --}}
                    @if(in_array($clave, ['foto_ph', 'foto_cloro']) && !empty($item['url']))
                        <button type="button"
                                onclick="abrirFotoReg('{{ $item['url'] }}', '{{ $item['label'] }}')"
                                class="inline-flex items-center gap-1 text-[11px] text-trigo-dark hover:text-corteza
                                       transition-colors mt-1.5 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver foto
                        </button>
                    @endif
                    {{-- Galería de fotos del proceso --}}
                    @if($clave === 'fotos_proceso' && !empty($item['urls']))
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($item['urls'] as $i => $url)
                            <button type="button"
                                    onclick="abrirGaleriaReg({{ json_encode($item['urls']) }}, {{ $i }})"
                                    class="relative w-14 h-14 rounded-lg overflow-hidden border-2 border-white
                                           shadow-sm hover:scale-105 transition-transform">
                                <img src="{{ $url }}" alt="Foto proceso {{ $i+1 }}"
                                     class="w-full h-full object-cover">
                            </button>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ MODAL foto individual ══════════════════════════════════════ --}}
<div id="modal-foto-reg"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-corteza/75 backdrop-blur-sm" onclick="cerrarFotoReg()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-trigo-light/50">
            <span id="modal-foto-reg-titulo" class="font-medium text-corteza text-sm"></span>
            <button onclick="cerrarFotoReg()"
                    class="w-7 h-7 rounded-lg hover:bg-masa flex items-center justify-center
                           text-corteza/40 hover:text-corteza transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <img id="modal-foto-reg-img" src="" alt="" class="w-full object-contain max-h-[70vh]">
    </div>
</div>

{{-- ══ MODAL galería proceso ══════════════════════════════════════ --}}
<div id="modal-galeria-reg"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-corteza/75 backdrop-blur-sm" onclick="cerrarGaleriaReg()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-trigo-light/50">
            <span class="font-medium text-corteza text-sm">
                Fotos del proceso
                <span id="galeria-reg-contador" class="font-normal text-corteza/40 ml-1 tabular-nums"></span>
            </span>
            <button onclick="cerrarGaleriaReg()"
                    class="w-7 h-7 rounded-lg hover:bg-masa flex items-center justify-center
                           text-corteza/40 hover:text-corteza transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="relative bg-corteza/5">
            <img id="galeria-reg-img" src="" alt="" class="w-full object-contain max-h-[55vh]">
            <button onclick="galeriaRegAnterior()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                           bg-white/90 hover:bg-white shadow-md flex items-center justify-center
                           transition-all hover:scale-105">
                <svg class="w-4 h-4 text-corteza" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="galeriaRegSiguiente()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full
                           bg-white/90 hover:bg-white shadow-md flex items-center justify-center
                           transition-all hover:scale-105">
                <svg class="w-4 h-4 text-corteza" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <div id="galeria-reg-thumbs"
             class="flex gap-2 overflow-x-auto px-4 py-3 border-t border-trigo-light/40">
        </div>
    </div>
</div>

@section('scripts')
<script>
// ── Modal foto individual ─────────────────────────────────────────
function abrirFotoReg(url, titulo) {
    document.getElementById('modal-foto-reg-titulo').textContent = titulo;
    document.getElementById('modal-foto-reg-img').src = url;
    const m = document.getElementById('modal-foto-reg');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function cerrarFotoReg() {
    const m = document.getElementById('modal-foto-reg');
    m.classList.remove('flex'); m.classList.add('hidden');
}

// ── Galería de fotos del proceso ──────────────────────────────────
let _regUrls = [], _regIdx = 0;

function abrirGaleriaReg(urls, inicio) {
    _regUrls = urls; _regIdx = inicio || 0;
    renderGaleriaReg();
    const m = document.getElementById('modal-galeria-reg');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function cerrarGaleriaReg() {
    const m = document.getElementById('modal-galeria-reg');
    m.classList.remove('flex'); m.classList.add('hidden');
}
function galeriaRegAnterior() {
    _regIdx = (_regIdx - 1 + _regUrls.length) % _regUrls.length;
    renderGaleriaReg();
}
function galeriaRegSiguiente() {
    _regIdx = (_regIdx + 1) % _regUrls.length;
    renderGaleriaReg();
}
function renderGaleriaReg() {
    document.getElementById('galeria-reg-img').src = _regUrls[_regIdx];
    document.getElementById('galeria-reg-contador').textContent =
        (_regIdx + 1) + ' / ' + _regUrls.length;
    const thumbs = document.getElementById('galeria-reg-thumbs');
    thumbs.innerHTML = '';
    _regUrls.forEach((u, i) => {
        const btn = document.createElement('button');
        btn.className = 'shrink-0 w-12 h-12 rounded-lg overflow-hidden border-2 transition-all '
            + (i === _regIdx ? 'border-corteza scale-110' : 'border-transparent opacity-60 hover:opacity-100');
        btn.onclick = () => { _regIdx = i; renderGaleriaReg(); };
        btn.innerHTML = '<img src="' + u + '" class="w-full h-full object-cover">';
        thumbs.appendChild(btn);
    });
    thumbs.children[_regIdx]?.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
}
</script>
@endsection

@endsection