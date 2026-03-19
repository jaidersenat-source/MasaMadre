<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; }
        h1   { font-size: 14px; margin-bottom: 2px; }
        h2   { font-size: 11px; margin: 16px 0 6px; color: #444; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .meta { font-size: 9px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th   { background: #2c5f2e; color: #fff; padding: 4px 6px; text-align: left; font-size: 9px; }
        td   { padding: 3px 6px; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
        tr:nth-child(even) td { background: #f7f7f7; }
        .badge-ok      { color: #2c5f2e; font-weight: bold; }
        .badge-alerta  { color: #b45309; font-weight: bold; }
        .badge-error   { color: #b91c1c; font-weight: bold; }
        .footer { font-size: 8px; color: #999; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<h1>Reporte de Control de Masa Madre y Pan</h1>
<div class="meta">
    Generado: {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp;
    @if(!empty($filtros['regional'])) Regional: {{ $filtros['regional'] }} &nbsp;|&nbsp; @endif
    @if(!empty($filtros['estado'])) Estado: {{ $filtros['estado'] }} &nbsp;|&nbsp; @endif
    Total registros: {{ $registros->count() }}
</div>

@foreach ($registros as $registro)
<h2>
    {{ $registro->panaderia->nombre }} — {{ $registro->panaderia->ciudad }}
    | Proceso #{{ $registro->id }} | {{ $registro->fecha_inicio?->format('d/m/Y') }}
</h2>

<table>
    <tr>
        <th>Regional</th><th>Extensionista</th>
        <th>pH agua</th><th>Cloro (ppm)</th>
        <th>Cal. tester</th><th>Estado</th>
    </tr>
    <tr>
        <td>{{ $registro->panaderia->regional }}</td>
        <td>{{ $registro->panaderia->extensionista }}</td>
        <td>{{ $registro->ph_agua }}</td>
        <td>{{ $registro->cloro_agua }}</td>
        <td>{{ $registro->fecha_calibracion_tester?->format('d/m/Y') ?? '—' }}</td>
        <td>{{ ucfirst(str_replace('_', ' ', $registro->estado)) }}</td>
    </tr>
</table>

@if($registro->dias->count())
<table>
    <tr>
        <th>Día</th><th>Harina T (%)</th><th>Otras harinas</th>
        <th>Agua (%)</th><th>T° agua</th><th>T° amb.</th>
        <th>T° mezcla</th><th>pH ini.</th><th>Maduración</th>
        <th>Observaciones</th><th>Resp.</th>
    </tr>
    @foreach($registro->dias as $dia)
    <tr>
        <td>{{ $dia->dia }}</td>
        <td>{{ $dia->pct_harina_trigo }}</td>
        <td>{{ $dia->otras_harinas ?? 'NA' }}</td>
        <td>{{ $dia->pct_agua }}</td>
        <td>{{ $dia->temp_agua }}</td>
        <td>{{ $dia->temp_ambiente }}</td>
        <td>{{ $dia->temp_mezcla }}</td>
        <td class="{{ $dia->ph_inicial <= 4.5 ? 'badge-ok' : ($dia->ph_inicial <= 5.5 ? 'badge-alerta' : 'badge-error') }}">
            {{ $dia->ph_inicial }}
        </td>
        <td>{{ $dia->tiempo_maduracion_horas }}h</td>
        <td>{{ $dia->observaciones ?? 'NA' }}</td>
        <td>{{ $dia->responsable }}</td>
    </tr>
    @endforeach
</table>
@endif

@foreach($registro->panes as $pan)
<table>
    <tr>
        <th>Fecha</th><th>Tipo pan</th><th>Harina</th>
        <th>T° agua</th><th>T° amb.</th><th>T° M.M.</th>
        <th>pH M.M.</th><th>pH cocción</th><th>pH pan</th>
        <th>T° pan</th><th>Observaciones</th><th>Resp.</th>
    </tr>
    <tr>
        <td>{{ $pan->fecha_elaboracion?->format('d/m/Y') }}</td>
        <td>{{ $pan->tipo_pan }}</td>
        <td>{{ $pan->tipo_harina }}</td>
        <td>{{ $pan->temp_agua }}</td>
        <td>{{ $pan->temp_ambiente }}</td>
        <td>{{ $pan->temp_masa_madre }}</td>
        <td class="{{ $pan->ph_masa_madre < 4.2 ? 'badge-ok' : 'badge-error' }}">{{ $pan->ph_masa_madre }}</td>
        <td class="{{ $pan->ph_masa_antes_coccion < 4.8 ? 'badge-ok' : 'badge-error' }}">{{ $pan->ph_masa_antes_coccion }}</td>
        <td class="{{ $pan->ph_pan < 5.8 ? 'badge-ok' : 'badge-error' }}">{{ $pan->ph_pan }}</td>
        <td>{{ $pan->temp_pan }}</td>
        <td>{{ $pan->observaciones ?? 'NA' }}</td>
        <td>{{ $pan->responsable }}</td>
    </tr>
</table>
@endforeach

@endforeach

<div class="footer">Sistema de Control de Masa Madre — SENA</div>
</body>
</html>