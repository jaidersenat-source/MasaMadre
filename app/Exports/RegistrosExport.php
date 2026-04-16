<?php

namespace App\Exports;

use App\Models\Caracterizacion;
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
            new CaracterizacionSheet($this->filtros),
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

// ─── Hoja 4: Caracterización ────────────────────────────────────────────────
class CaracterizacionSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filtros = []) {}

    public function title(): string { return 'Caracterización'; }

    public function collection()
    {
        $q = \App\Models\Caracterizacion::with('panaderia')
            ->where('paso_completado', 8);

        if (!empty($this->filtros['panaderia_id'])) {
            $q->where('panaderia_id', $this->filtros['panaderia_id']);
        }

        return $q->get();
    }

    public function headings(): array
    {
        return [
            // Identificación
            'Panadería','Regional','Responsable','Cédula','Rol','Extensionista',
            'Formalización','Tipo doc. panadería','N° doc. panadería',
            // Ubicación
            'Municipio','Zona','Barrio/Vereda','Dirección','Celular','Estrato',
            // Empleados
            'Años funcionamiento','N° empleados',
            'Empl. 18-28','Empl. 29-40','Empl. 41-55','Empl. 55+',
            'Gén. femenino','Gén. masculino','Otro género','No responde',
            'Mujeres cab. hogar','Hombres cab. hogar',
            // Grupos especiales (P29) — columnas por cada grupo
            'Victima violencia','Discapacidad','Indígena','Afrocolombiana','Comunidades negras',
            'Raizal','Palenquera','Privada libertad','Víctima trata','Tercera edad',
            'Adolescentes y jóvenes','Adolescentes ley penal','Mujer cabeza hogar','Reincorporación',
            'Reintegración','Víctima agente químico','Pueblo Rom','Mujeres empresarias','Ninguna',
            // Educación
            'Edu: sin estudios','Edu: primaria','Edu: secundaria','Edu: media',
            'Edu: técnico','Edu: tecnólogo','Edu: pregrado','Edu: posgrado',
            // Masa madre
            'kg harina/día','Tipos de pan','Sabe masa madre','Usa masa madre','Prefermentos','Recibió transferencia',
            'Pan masa madre deseado',
            // Expectativas
            'Expectativa aprendizaje','Preocupación masa madre','Expectativa proyecto',
            // Situación económica
            'Situación económica','Cierre o reducción','Dificultad sostener','Nuevas técnicas mejoran ingreso',
            // Meta
            'Paso completado','Registrado por','Fecha registro',
        ];
    }

    public function map($row): array
    {
        $g = $row->grupos_especiales ?? [];
        return [
            // Identificación
            $row->panaderia->nombre ?? '—',
            $row->panaderia->regional ?? '—',
            $row->nombres_apellidos,
            $row->cedula,
            $row->rol,
            $row->extensionista,
            $row->formalizacion,
            $row->tipo_documento_panaderia,
            $row->numero_documento_panaderia,
            // Ubicación
            $row->ciudad_municipio,
            $row->zona,
            $row->barrio_vereda,
            $row->direccion,
            $row->celular_contacto,
            $row->estrato,
            // Empleados
            $row->anos_funcionamiento,
            $row->num_empleados,
            $row->empleados_18_28,
            $row->empleados_29_40,
            $row->empleados_41_55,
            $row->empleados_55_mas,
            $row->empleados_femenino,
            $row->empleados_masculino,
            $row->empleados_otro_genero,
            $row->empleados_no_responde,
            $row->mujeres_cabeza_hogar,
            $row->hombres_cabeza_hogar,
            // Grupos especiales (cada clave por columna)
            $g['victima_violencia'] ?? '0',
            $g['discapacidad'] ?? '0',
            $g['indigena'] ?? '0',
            $g['afrocolombiana'] ?? '0',
            $g['comunidades_negras'] ?? '0',
            $g['raizal'] ?? '0',
            $g['palenquera'] ?? '0',
            $g['privada_libertad'] ?? '0',
            $g['victima_trata'] ?? '0',
            $g['tercera_edad'] ?? '0',
            $g['adolescentes_jovenes'] ?? '0',
            $g['adolescentes_ley_penal'] ?? '0',
            $g['mujer_cabeza_hogar'] ?? '0',
            $g['reincorporacion'] ?? '0',
            $g['reintegracion'] ?? '0',
            $g['victima_agente_quimico'] ?? '0',
            $g['pueblo_rom'] ?? '0',
            $g['mujeres_empresarias'] ?? '0',
            $g['ninguna'] ?? '0',
            // Educación
            $row->edu_sin_estudios,
            $row->edu_primaria,
            $row->edu_secundaria,
            $row->edu_media,
            $row->edu_tecnico,
            $row->edu_tecnologo,
            $row->edu_pregrado,
            $row->edu_posgrado,
            // Masa madre
            $row->kilos_harina_dia,
            $row->tipos_pan,
            $row->sabe_masa_madre,
            $row->usa_masa_madre,
            is_array($row->prefermentos) ? implode(', ', $row->prefermentos) : ($row->prefermentos ?? ''),
            $row->recibio_transferencia,
            $row->pan_masa_madre_deseado,
            // Expectativas
            $row->expectativa_aprendizaje,
            $row->preocupacion_masa_madre,
            $row->expectativa_proyecto,
            // Situación económica
            $row->situacion_economica,
            $row->cierre_reduccion,
            $row->dificultad_sostener,
            $row->nuevas_tecnicas_ingresos,
            // Meta
            $row->paso_completado,
            $row->user?->email ?? ($row->user_id ?? '—'),
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
