@extends('layouts.app')
@section('title', 'Panel Administrador')
@section('breadcrumb', 'Panel general')

@section('content')

{{-- ── ENCABEZADO ── --}}
<div class="flex flex-wrap items-start justify-between gap-4 mb-7">
  <div>
    <div class="flex items-center gap-2 mb-1.5">
      <div class="w-1 h-5 rounded-full enc-acento"></div>
      <span class="font-mono text-xs uppercase tracking-widest enc-subtitulo">
        Coordinador · SENA
      </span>
    </div>
    <h1 class="font-display font-bold leading-tight enc-titulo">
      Panel de control
    </h1>
    <p class="text-sm mt-0.5 enc-fecha">
      Resumen global del programa · {{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}
    </p>
  </div>

  {{-- Acciones --}}
  <div class="flex flex-wrap items-center gap-2">
   
    <a href="{{ route('admin.panaderias.create') }}" class="adm-btn adm-btn-primary">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nueva panadería
    </a>
  </div>
</div>

{{-- ── TARJETAS MÉTRICAS ── --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-6">

  {{-- Total panaderías --}}
  <div class="adm-card adm-metric-card">
    <div class="adm-metric-icon metric-icon-corteza">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
           class="icon-corteza-mid">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
    <div>
      <div class="adm-metric-label">Total panaderías</div>
      <div class="adm-metric-value metric-value-corteza">{{ $stats['total_panaderias'] }}</div>
      @if($sinActividad > 0)
        <div class="adm-metric-sub metric-sub-amber">{{ $sinActividad }} sin actividad reciente</div>
      @else
        <div class="adm-metric-sub metric-sub-verde">Todas con actividad</div>
      @endif
    </div>
  </div>

  {{-- Activas --}}
  <div class="adm-card adm-metric-card">
    <div class="adm-metric-icon metric-icon-verde">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
           class="icon-verde">
        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
      </svg>
    </div>
    <div>
      <div class="adm-metric-label">Panaderías activas</div>
      <div class="adm-metric-value metric-value-verde">{{ $stats['panaderias_activas'] }}</div>
      <div class="adm-metric-sub metric-sub-verde-dim">En proceso ahora</div>
    </div>
  </div>

  {{-- Procesos en curso --}}
  <div class="adm-card adm-metric-card">
    <div class="adm-metric-icon metric-icon-amber">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
           class="icon-amber">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
    </div>
    <div>
      <div class="adm-metric-label">Procesos en curso</div>
      <div class="adm-metric-value metric-value-amber">{{ $stats['procesos_activos'] }}</div>
      <div class="adm-metric-sub metric-sub-amber-dim">Fermentando hoy</div>
    </div>
  </div>

  {{-- Completos --}}
  <div class="adm-card adm-metric-card">
    <div class="adm-metric-icon metric-icon-blue">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
           class="icon-blue">
        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
    </div>
    <div>
      <div class="adm-metric-label">Procesos completos</div>
      <div class="adm-metric-value metric-value-blue">{{ $stats['procesos_completos'] }}</div>
      <div class="adm-metric-sub metric-sub-blue-dim">Ciclos terminados</div>
    </div>
  </div>

</div>

{{-- ── FILA: tabla + mini panel derecho ── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

  {{-- TABLA DE PROCESOS (2/3) --}}
  <div class="adm-card overflow-hidden xl:col-span-2">
    <div class="px-5 py-4 border-b flex items-center justify-between tabla-header-border">
      <div>
        <h2 class="font-semibold text-sm tabla-titulo">Últimos registros</h2>
        <p class="text-xs mt-0.5 tabla-subtitulo">Actividad reciente de panaderías</p>
      </div>
      <a href="{{ route('admin.registros.index') }}" class="tabla-ver-todos">
        Ver todos →
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="tabla-thead-row">
            <th class="adm-th">Panadería</th>
            <th class="adm-th">Regional</th>
            <th class="adm-th">Inicio</th>
            <th class="adm-th">Progreso</th>
            <th class="adm-th">Estado</th>
            <th class="adm-th w-8"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($ultimosProcesos as $p)
          <tr class="adm-tr">
            <td class="adm-td">
              <div class="flex items-center gap-2.5">
                <div class="tabla-avatar">
                  {{ strtoupper(substr($p->panaderia->nombre, 0, 1)) }}
                </div>
                <span class="font-medium text-sm tabla-nombre">
                  {{ $p->panaderia->nombre }}
                </span>
              </div>
            </td>
            <td class="adm-td adm-td-muted">{{ $p->panaderia->regional }}</td>
            <td class="adm-td adm-td-mono">{{ $p->fecha_inicio->format('d/m/Y') }}</td>
            <td class="adm-td">
              <div class="flex items-center gap-2.5">
                <div class="progreso-track">
                  <div class="progreso-fill"
                       style="width:{{ $p->progreso() }}%;
                              background:{{ $p->progreso() >= 100 ? '#2563eb' : ($p->progreso() >= 60 ? 'var(--color-verde)' : 'var(--color-trigo)') }}">
                  </div>
                </div>
                <span class="adm-td-mono text-xs progreso-pct">
                  {{ $p->progreso() }}%
                </span>
              </div>
            </td>
            <td class="adm-td">
              @if($p->estado === 'completado')
                <span class="adm-badge adm-badge-blue">Completado</span>
              @elseif($p->estado === 'activo')
                <span class="adm-badge adm-badge-green">En proceso</span>
              @else
                <span class="adm-badge adm-badge-amber">Pendiente</span>
              @endif
            </td>
            <td class="adm-td">
              <a href="{{ route('admin.registros.show', $p->id) }}" class="tabla-arrow">
                →
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-5 py-10 text-center text-sm tabla-empty">
              No hay registros aún.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- PANEL DERECHO (1/3) --}}
  <div class="flex flex-col gap-4">

    {{-- Alertas de pH --}}
    <div class="adm-card overflow-hidden">
      <div class="px-5 py-4 border-b flex items-center justify-between alertas-header-border">
        <h2 class="font-semibold text-sm alertas-titulo">Alertas activas</h2>
        @php $alertasPH = $ultimosProcesos->flatMap(fn($p) => $p->registros)->filter(fn($r) => isset($r->ph) && ($r->ph < 3.5 || $r->ph > 5.0)); @endphp
        @if($alertasPH->count() > 0)
          <span class="adm-badge adm-badge-amber">{{ $alertasPH->count() }}</span>
        @else
          <span class="adm-badge adm-badge-green">Sin alertas</span>
        @endif
      </div>
      <div class="p-5">
        @if($alertasPH->count() > 0)
          <div class="space-y-2">
            @foreach($alertasPH->take(4) as $r)
            <div class="flex items-center justify-between py-2 border-b last:border-0 alerta-item-border">
              <div>
                <div class="text-xs font-medium alerta-nombre">
                  {{ $r->proceso->panaderia->nombre ?? '—' }}
                </div>
                <div class="text-xs font-mono mt-0.5 alerta-dia">
                  Día {{ $r->dia }} · {{ $r->created_at->format('d/m') }}
                </div>
              </div>
              <div class="text-right">
                <div class="font-mono text-sm font-medium alerta-ph">
                  pH {{ number_format($r->ph, 1) }}
                </div>
                <div class="text-xs alerta-rango">Fuera de rango</div>
              </div>
            </div>
            @endforeach
          </div>
        @else
          <div class="flex flex-col items-center justify-center py-6 text-center">
            <div class="alerta-ok-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                   class="icon-verde">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
              </svg>
            </div>
            <p class="text-xs alerta-ok-texto">
              Todos los valores de pH dentro del rango ideal
            </p>
          </div>
        @endif
      </div>
    </div>

    {{-- Progreso del programa --}}
    <div class="adm-card p-5">
      <h2 class="font-semibold text-sm mb-4 progreso-titulo">
        Progreso del programa
      </h2>
      @php
        $total   = max($stats['total_panaderias'], 1);
        $pct     = round(($stats['procesos_completos'] / $total) * 100);
        $pctAct  = round(($stats['procesos_activos']  / $total) * 100);
      @endphp

      {{-- Ring visual --}}
      <div class="flex items-center gap-5 mb-5">
        <div class="relative shrink-0 progreso-ring-wrap">
          <svg width="72" height="72" viewBox="0 0 72 72">
            <circle cx="36" cy="36" r="30" fill="none" stroke="rgba(107,66,38,.12)" stroke-width="6"/>
            <circle cx="36" cy="36" r="30" fill="none"
                    stroke="var(--color-verde)" stroke-width="6"
                    stroke-linecap="round"
                    stroke-dasharray="{{ round(188.5 * $pct / 100) }} 188.5"
                    transform="rotate(-90 36 36)"/>
          </svg>
          <div class="progreso-ring-label">
            <span class="font-display font-bold progreso-ring-num">{{ $pct }}</span>
            <span class="font-mono progreso-ring-pct">%</span>
          </div>
        </div>
        <div class="space-y-1.5">
          <div class="flex items-center gap-2 text-xs progreso-leyenda">
            <div class="progreso-dot dot-verde"></div>
            <span>{{ $stats['procesos_completos'] }} completados</span>
          </div>
          <div class="flex items-center gap-2 text-xs progreso-leyenda">
            <div class="progreso-dot dot-trigo"></div>
            <span>{{ $stats['procesos_activos'] }} en proceso</span>
          </div>
          <div class="flex items-center gap-2 text-xs progreso-leyenda">
            <div class="progreso-dot dot-muted"></div>
            <span>{{ $stats['total_panaderias'] - $stats['procesos_completos'] - $stats['procesos_activos'] }} sin iniciar</span>
          </div>
        </div>
      </div>

      {{-- Barra apilada --}}
      <div class="progreso-barra-track">
        <div class="h-full transition-all progreso-barra-completos" style="width:{{ $pct }}%"></div>
        <div class="h-full transition-all progreso-barra-activos"   style="width:{{ $pctAct }}%"></div>
      </div>
      <div class="flex justify-between mt-1.5">
        <span class="font-mono text-xs progreso-barra-label">0</span>
        <span class="font-mono text-xs progreso-barra-label">{{ $stats['total_panaderias'] }} panaderías</span>
      </div>
    </div>

  </div>
</div>

{{-- ── SECCIÓN CARACTERIZACIÓN ── --}}
<div class="mt-6">
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="font-semibold text-sm tabla-titulo">Caracterización de panaderías</h2>
      <p class="text-xs mt-0.5 tabla-subtitulo">
        {{ $statsCaract['total'] }} panaderías con caracterización completada
      </p>
    </div>
    <a href="{{ route('admin.exportar.caracterizaciones') }}" class="adm-btn adm-btn-ghost">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="12" y1="18" x2="12" y2="12"/>
        <line x1="9" y1="15" x2="15" y2="15"/>
      </svg>
      Exportar Excel
    </a>
  </div>

  {{-- Tarjetas clave --}}
  <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-4">
    @php
    $caracTarjetas = [
      ['Caracterizadas',          $statsCaract['total'],           'metric-value-corteza'],
      ['Usan masa madre',         $statsCaract['usan_masa_madre'],  'metric-value-verde'],
      ['Formalizadas',            $statsCaract['formalizadas'],     'metric-value-amber'],
      ['Recibieron transferencia',$statsCaract['recibio_transfer'], 'metric-value-blue'],
    ];
    @endphp
    @foreach($caracTarjetas as [$lbl, $val, $cls])
    <div class="adm-card p-4 flex items-center gap-3">
      <div>
        <div class="adm-metric-label">{{ $lbl }}</div>
        <div class="adm-metric-value {{ $cls }}">{{ $val }}</div>
        @if($statsCaract['total'] > 0)
          <div class="adm-metric-sub metric-sub-verde-dim">
            {{ round($val / max($statsCaract['total'], 1) * 100) }}%
          </div>
        @endif
      </div>
    </div>
    @endforeach
  </div>

  {{-- Situación económica + zona --}}
  <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

    {{-- Situación económica --}}
    <div class="adm-card p-5">
      <h3 class="font-semibold text-sm tabla-titulo mb-4">Situación económica</h3>
      @php
      $coloresSit = ['Muy difícil'=>'bg-red-400','Difícil'=>'bg-amber-400','Estable'=>'bg-trigo','Buena'=>'bg-verde'];
      $maxSit = max(1, $statsCaract['situacion_economica']->max() ?? 1);
      @endphp
      <div class="space-y-2.5">
        @foreach($coloresSit as $etiqueta => $color)
          @php $n = $statsCaract['situacion_economica'][$etiqueta] ?? 0; @endphp
          <div class="flex items-center gap-3 text-xs">
            <span class="w-20 text-right text-corteza/60 shrink-0">{{ $etiqueta }}</span>
            <div class="flex-1 h-5 bg-masa-dark rounded-full overflow-hidden">
              <div class="{{ $color }} h-full rounded-full transition-all"
                   style="width:{{ round($n / $maxSit * 100) }}%"></div>
            </div>
            <span class="w-6 text-left font-mono font-semibold text-corteza">{{ $n }}</span>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Zona + promedios --}}
    <div class="adm-card p-5">
      <h3 class="font-semibold text-sm tabla-titulo mb-4">Zona y promedios</h3>

      {{-- Zona --}}
      @php $totalZona = max(1, ($statsCaract['zona']['Urbana'] ?? 0) + ($statsCaract['zona']['Rural'] ?? 0)); @endphp
      <div class="flex items-center gap-2 mb-2">
        <span class="text-xs text-corteza/50 w-12 shrink-0">Urbana</span>
        <div class="flex-1 h-4 bg-masa-dark rounded-full overflow-hidden flex">
          <div class="bg-trigo h-full transition-all"
               style="width:{{ round(($statsCaract['zona']['Urbana'] ?? 0) / $totalZona * 100) }}%"></div>
        </div>
        <span class="text-xs font-mono text-corteza w-6">{{ $statsCaract['zona']['Urbana'] ?? 0 }}</span>
      </div>
      <div class="flex items-center gap-2 mb-5">
        <span class="text-xs text-corteza/50 w-12 shrink-0">Rural</span>
        <div class="flex-1 h-4 bg-masa-dark rounded-full overflow-hidden flex">
          <div class="bg-verde h-full transition-all"
               style="width:{{ round(($statsCaract['zona']['Rural'] ?? 0) / $totalZona * 100) }}%"></div>
        </div>
        <span class="text-xs font-mono text-corteza w-6">{{ $statsCaract['zona']['Rural'] ?? 0 }}</span>
      </div>

      {{-- Promedios --}}
      <div class="grid grid-cols-2 gap-3 pt-4 border-t border-trigo-light/40">
        <div class="text-center">
          <div class="font-display text-2xl font-bold text-corteza tabular-nums">
            {{ $statsCaract['avg_empleados'] }}
          </div>
          <div class="text-[10px] text-corteza/40 uppercase tracking-wide mt-0.5">
            Prom. empleados
          </div>
        </div>
        <div class="text-center">
          <div class="font-display text-2xl font-bold text-corteza tabular-nums">
            {{ $statsCaract['avg_kilos_harina'] }}
          </div>
          <div class="text-[10px] text-corteza/40 uppercase tracking-wide mt-0.5">
            Prom. kg harina/día
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection