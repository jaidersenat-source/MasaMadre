<?php

namespace App\Exports;

use App\Models\RegistroProceso;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class RegistrosExport implements WithMultipleSheets
{
    public function __construct(
        private array $filtros = []
    ) {}

    public function sheets(): array
    {
        return [
            new RegistrosResumenSheet($this->filtros),
            new DiasMasaMadreSheet($this->filtros),
            new ElaboracionPanSheet($this->filtros),
        ];
    }
}

// ─── Hoja 1: Resumen de procesos ────────────────────────────────────────────
class RegistrosResumenSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filtros) {}

    public function title(): string { return 'Resumen Procesos'; }

    public function collection()
    {
        $query = RegistroProceso::with(['panaderia'])
            ->withCount(['dias', 'panes']);

        $this->aplicarFiltros($query);

        return $query->latest('fecha_inicio')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Panadería', 'Ciudad', 'Regional', 'Extensionista',
            'Fecha inicio', 'pH agua', 'Cloro agua (ppm)',
            'Fecha calibración tester', 'Días registrados', 'Panes registrados', 'Estado',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->panaderia->nombre,
            $row->panaderia->ciudad,
            $row->panaderia->regional,
            $row->panaderia->extensionista,
            $row->fecha_inicio?->format('d/m/Y'),
            $row->ph_agua,
            $row->cloro_agua,
            $row->fecha_calibracion_tester?->format('d/m/Y'),
            $row->dias_count,
            $row->panes_count,
            $row->estado,
        ];
    }

    private function aplicarFiltros($query): void
    {
        if (!empty($this->filtros['regional'])) {
            $query->whereHas('panaderia', fn($q) => $q->where('regional', $this->filtros['regional']));
        }
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }
        if (!empty($this->filtros['fecha_desde'])) {
            $query->where('fecha_inicio', '>=', $this->filtros['fecha_desde']);
        }
        if (!empty($this->filtros['fecha_hasta'])) {
            $query->where('fecha_inicio', '<=', $this->filtros['fecha_hasta']);
        }
    }
}

// ─── Hoja 2: Días masa madre ────────────────────────────────────────────────
class DiasMasaMadreSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filtros) {}

    public function title(): string { return 'Días Masa Madre'; }

    public function collection()
    {
        return \App\Models\DiaMasaMadre::with(['registro.panaderia'])
            ->orderBy('registro_id')
            ->orderBy('dia')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Proceso', 'Panadería', 'Regional', 'Día',
            'Harina trigo (%)', 'Otras harinas', 'Agua (%)',
            'Temp. agua (°C)', 'Temp. ambiente (°C)', 'Temp. mezcla (°C)',
            'pH inicial', 'Maduración (h)', 'Observaciones', 'Responsable',
        ];
    }

    public function map($row): array
    {
        return [
            $row->registro_id,
            $row->registro->panaderia->nombre,
            $row->registro->panaderia->regional,
            $row->dia,
            $row->pct_harina_trigo,
            $row->otras_harinas ?? 'NA',
            $row->pct_agua,
            $row->temp_agua,
            $row->temp_ambiente,
            $row->temp_mezcla,
            $row->ph_inicial,
            $row->tiempo_maduracion_horas,
            $row->observaciones ?? 'NA',
            $row->responsable,
        ];
    }
}

// ─── Hoja 3: Elaboración de pan ─────────────────────────────────────────────
class ElaboracionPanSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filtros) {}

    public function title(): string { return 'Elaboración Pan'; }

    public function collection()
    {
        return \App\Models\ElaboracionPan::with(['registro.panaderia'])
            ->orderBy('registro_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Proceso', 'Panadería', 'Regional', 'Fecha', 'Hora',
            'Tipo pan', 'Tipo harina',
            'Temp. agua (°C)', 'Temp. ambiente (°C)', 'Temp. masa madre (°C)',
            'pH masa madre', 'pH antes cocción', 'pH pan', 'Temp. pan (°C)',
            'Observaciones', 'Responsable',
        ];
    }

    public function map($row): array
    {
        return [
            $row->registro_id,
            $row->registro->panaderia->nombre,
            $row->registro->panaderia->regional,
            $row->fecha_elaboracion?->format('d/m/Y'),
            $row->hora_elaboracion,
            $row->tipo_pan,
            $row->tipo_harina,
            $row->temp_agua,
            $row->temp_ambiente,
            $row->temp_masa_madre,
            $row->ph_masa_madre,
            $row->ph_masa_antes_coccion,
            $row->ph_pan,
            $row->temp_pan,
            $row->observaciones ?? 'NA',
            $row->responsable,
        ];
    }
}