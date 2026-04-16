<?php

namespace App\Exports;

use App\Models\Caracterizacion;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CaracterizacionesExport
{
    // Paleta de colores por sección
    private const COLOR_TITULO    = 'FF2C5F2E'; // verde oscuro SENA
    private const COLOR_SECCION   = 'FF4A7C59'; // verde medio
    private const COLOR_SUBHEADER = 'FFD6E4D0'; // verde claro
    private const COLOR_IMPARES   = 'FFF7FAF7';
    private const COLOR_TEXT_BLANCO = 'FFFFFFFF';
    private const COLOR_BORDE     = 'FF9DBB9F';

    public function __construct(private array $filtros = []) {}

    public function download(string $filename): StreamedResponse
    {
        $query = Caracterizacion::with('panaderia')->where('paso_completado', 8);
        if (!empty($this->filtros['panaderia_id'])) {
            $query->where('panaderia_id', $this->filtros['panaderia_id']);
        }
        $rows = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Caracterizaciones');

        // ── Ancho de columnas ──────────────────────────────────────────────
        $colWidths = [
            'A' => 6,  'B' => 30, 'C' => 16, 'D' => 16, 'E' => 18,
            'F' => 18, 'G' => 14, 'H' => 14, 'I' => 16, 'J' => 18,
            'K' => 12, 'L' => 18, 'M' => 18, 'N' => 14, 'O' => 8,
            'P' => 16, 'Q' => 16, 'R' => 10, 'S' => 10, 'T' => 10,
            'U' => 10, 'V' => 12, 'W' => 12, 'X' => 12, 'Y' => 12,
            'Z' => 14, 'AA'=> 14,
        ];
        foreach ($colWidths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $lastCol = 'AA'; // columna final

        $row = 1;

        // ══ TÍTULO PRINCIPAL ═══════════════════════════════════════════════
        $sheet->setCellValue("A{$row}", 'CARACTERIZACIÓN DE PANADERÍAS — PROGRAMA MASA MADRE SENA');
        $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_TITULO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(28);
        $row++;

        // Subtítulo: fecha de generación y total
        $sheet->setCellValue("A{$row}", 'Generado: ' . now()->format('d/m/Y H:i') . '   |   Total registros: ' . $rows->count());
        $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF555555']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0F5F0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;
        $row++; // espacio

        if ($rows->isEmpty()) {
            $sheet->setCellValue("A{$row}", 'Sin registros con caracterización completa.');
            $spreadsheet = $this->save($spreadsheet, $filename);
        }

        // ══ CABECERA DE COLUMNAS ═══════════════════════════════════════════
        // Definimos secciones con sus columnas y colores
        $sections = [
            ['label' => 'IDENTIFICACIÓN',       'cols' => ['A','B','C','D','E','F','G','H','I']],
            ['label' => 'UBICACIÓN',             'cols' => ['J','K','L','M','N','O']],
            ['label' => 'EMPLEADOS / EDADES',    'cols' => ['P','Q','R','S','T','U','V','W','X','Y','Z','AA']],
        ];

        // Fila de sección (merge + color diferente por sección)
        $sectionColors = ['FFCCE0CC', 'FFCCD6E0', 'FFE0CCCC'];
        foreach ($sections as $i => $sec) {
            $first = $sec['cols'][0];
            $last  = end($sec['cols']);
            $sheet->setCellValue("{$first}{$row}", $sec['label']);
            $sheet->mergeCells("{$first}{$row}:{$last}{$row}");
            $sheet->getStyle("{$first}{$row}:{$last}{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FF1A3A1A']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $sectionColors[$i]]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
            ]);
        }
        $row++;

        // Fila de encabezados de campo
        $headers = [
            'A'  => '#',
            'B'  => 'Panadería',
            'C'  => 'Responsable',
            'D'  => 'Cédula',
            'E'  => 'Rol',
            'F'  => 'Extensionista',
            'G'  => 'Formalización',
            'H'  => 'Tipo doc.',
            'I'  => 'N° documento',
            'J'  => 'Municipio',
            'K'  => 'Zona',
            'L'  => 'Barrio/Vereda',
            'M'  => 'Dirección',
            'N'  => 'Celular',
            'O'  => 'Estrato',
            'P'  => 'Años func.',
            'Q'  => 'N° empleados',
            'R'  => 'Empl. 18-28',
            'S'  => 'Empl. 29-40',
            'T'  => 'Empl. 41-55',
            'U'  => 'Empl. 55+',
            'V'  => 'Gén. fem.',
            'W'  => 'Gén. masc.',
            'X'  => 'Otro gén.',
            'Y'  => 'No responde',
            'Z'  => 'Muj. cab. hogar',
            'AA' => 'Homb. cab. hogar',
        ];
        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$row}", $label);
        }
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_SECCION]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(30);
        $headerRow = $row;
        $row++;

        // ══ FILAS DE DATOS ════════════════════════════════════════════════
        foreach ($rows as $idx => $c) {
            $bgColor = ($idx % 2 === 0) ? 'FFFFFFFF' : self::COLOR_IMPARES;
            $sheet->setCellValue("A{$row}", $idx + 1);
            $sheet->setCellValue("B{$row}", $c->panaderia->nombre ?? '—');
            $sheet->setCellValue("C{$row}", $c->nombres_apellidos);
            $sheet->setCellValue("D{$row}", $c->cedula);
            $sheet->setCellValue("E{$row}", $c->rol);
            $sheet->setCellValue("F{$row}", $c->extensionista);
            $sheet->setCellValue("G{$row}", $c->formalizacion);
            $sheet->setCellValue("H{$row}", $c->tipo_documento_panaderia);
            $sheet->setCellValue("I{$row}", $c->numero_documento_panaderia);
            $sheet->setCellValue("J{$row}", $c->ciudad_municipio);
            $sheet->setCellValue("K{$row}", $c->zona);
            $sheet->setCellValue("L{$row}", $c->barrio_vereda);
            $sheet->setCellValue("M{$row}", $c->direccion);
            $sheet->setCellValue("N{$row}", $c->celular_contacto);
            $sheet->setCellValue("O{$row}", $c->estrato);
            $sheet->setCellValue("P{$row}", $c->anos_funcionamiento);
            $sheet->setCellValue("Q{$row}", $c->num_empleados);
            $sheet->setCellValue("R{$row}", $c->empleados_18_28);
            $sheet->setCellValue("S{$row}", $c->empleados_29_40);
            $sheet->setCellValue("T{$row}", $c->empleados_41_55);
            $sheet->setCellValue("U{$row}", $c->empleados_55_mas);
            $sheet->setCellValue("V{$row}", $c->empleados_femenino);
            $sheet->setCellValue("W{$row}", $c->empleados_masculino);
            $sheet->setCellValue("X{$row}", $c->empleados_otro_genero);
            $sheet->setCellValue("Y{$row}", $c->empleados_no_responde);
            $sheet->setCellValue("Z{$row}", $c->mujeres_cabeza_hogar);
            $sheet->setCellValue("AA{$row}", $c->hombres_cabeza_hogar);

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
                'font'    => ['size' => 9],
            ]);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            foreach (['R','S','T','U','V','W','X','Y','Z','AA'] as $numCol) {
                $sheet->getStyle("{$numCol}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $row++;
        }

        // ══ SEGUNDA HOJA: Grupos Especiales + Educación + Masa Madre + Expectativas + Economía ═
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Detalle');

        // Ancho columnas hoja 2
        $col2Widths = [
            'A'=>6,'B'=>30,'C'=>12,'D'=>12,'E'=>12,'F'=>12,'G'=>12,
            'H'=>12,'I'=>12,'J'=>12,'K'=>12,'L'=>12,'M'=>12,'N'=>12,
            'O'=>12,'P'=>12,'Q'=>12,'R'=>12,'S'=>12,'T'=>12,'U'=>12,
            'V'=>12,'W'=>12,'X'=>12,'Y'=>14,'Z'=>30,'AA'=>30,'AB'=>30,
            'AC'=>18,'AD'=>12,'AE'=>30,'AF'=>30,
        ];
        foreach ($col2Widths as $col => $w) {
            $sheet2->getColumnDimension($col)->setWidth($w);
        }

        $lastCol2 = 'AF';
        $r2 = 1;

        // Título hoja 2
        $sheet2->setCellValue("A{$r2}", 'DETALLE CARACTERIZACIÓN — Grupos Especiales · Educación · Masa Madre · Expectativas · Economía');
        $sheet2->mergeCells("A{$r2}:{$lastCol2}{$r2}");
        $sheet2->getStyle("A{$r2}:{$lastCol2}{$r2}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_TITULO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet2->getRowDimension($r2)->setRowHeight(24);
        $r2++;

        // Secciones hoja 2
        $sec2Colors = ['FFCCE0CC','FFCCD6E0','FFE0D6CC','FFE0CCCC','FFD6CCE0'];
        $sections2 = [
            ['label' => 'IDENTIFICACIÓN',       'start' => 'B',  'end' => 'B'],
            ['label' => 'GRUPOS ESPECIALES (P.29)', 'start' => 'C', 'end' => 'U'],
            ['label' => 'NIVEL EDUCATIVO',       'start' => 'V',  'end' => 'AC'],  // 8 cols
            // grupos especiales: 19 cols (C..U)
            // educación: 8 cols (V..AC)
            // masa madre: 6 cols (AD..AJ) — ajustaremos
        ];
        // Secciones simplificadas (corrección de columnas reales)
        $sec2 = [
            ['label' => 'PANADERÍA',                  'start' => 'A', 'end' => 'B',  'color' => $sec2Colors[0]],
            ['label' => 'GRUPOS ESPECIALES',          'start' => 'C', 'end' => 'U',  'color' => $sec2Colors[1]],
            ['label' => 'NIVEL EDUCATIVO',            'start' => 'V', 'end' => 'AC', 'color' => $sec2Colors[2]],
            ['label' => 'MASA MADRE',                 'start' => 'AD','end' => 'AF', 'color' => $sec2Colors[3]],
        ];
        foreach ($sec2 as $s) {
            $sheet2->setCellValue("{$s['start']}{$r2}", $s['label']);
            $sheet2->mergeCells("{$s['start']}{$r2}:{$s['end']}{$r2}");
            $sheet2->getStyle("{$s['start']}{$r2}:{$s['end']}{$r2}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FF1A3A1A']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $s['color']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
            ]);
        }
        $r2++;

        // Encabezados hoja 2
        $headers2 = [
            'A'  => '#',
            'B'  => 'Panadería',
            'C'  => 'Víctima violencia',
            'D'  => 'Discapacidad',
            'E'  => 'Indígena',
            'F'  => 'Afrocolombiana',
            'G'  => 'Comunidades negras',
            'H'  => 'Raizal',
            'I'  => 'Palenquera',
            'J'  => 'Privada libertad',
            'K'  => 'Víctima trata',
            'L'  => 'Tercera edad',
            'M'  => 'Adolescentes jóvenes',
            'N'  => 'Adolescentes ley penal',
            'O'  => 'Mujer cab. hogar',
            'P'  => 'Reincorporación',
            'Q'  => 'Reintegración',
            'R'  => 'Víctima ag. químico',
            'S'  => 'Pueblo Rom',
            'T'  => 'Mujeres empresarias',
            'U'  => 'Ninguna',
            'V'  => 'Sin estudios',
            'W'  => 'Primaria',
            'X'  => 'Secundaria',
            'Y'  => 'Ed. media',
            'Z'  => 'Técnico',
            'AA' => 'Tecnólogo',
            'AB' => 'Pregrado',
            'AC' => 'Posgrado',
            'AD' => 'kg harina/día',
            'AE' => 'Tipos de pan',
            'AF' => 'Prefermentos',
        ];
        foreach ($headers2 as $col => $label) {
            $sheet2->setCellValue("{$col}{$r2}", $label);
        }
        $sheet2->getStyle("A{$r2}:{$lastCol2}{$r2}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_SECCION]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
        ]);
        $sheet2->getRowDimension($r2)->setRowHeight(36);
        $r2++;

        foreach ($rows as $idx => $c) {
            $g = $c->grupos_especiales ?? [];
            $bgColor = ($idx % 2 === 0) ? 'FFFFFFFF' : self::COLOR_IMPARES;
            $sheet2->setCellValue("A{$r2}", $idx + 1);
            $sheet2->setCellValue("B{$r2}", $c->panaderia->nombre ?? '—');
            $sheet2->setCellValue("C{$r2}", $g['victima_violencia'] ?? '0');
            $sheet2->setCellValue("D{$r2}", $g['discapacidad'] ?? '0');
            $sheet2->setCellValue("E{$r2}", $g['indigena'] ?? '0');
            $sheet2->setCellValue("F{$r2}", $g['afrocolombiana'] ?? '0');
            $sheet2->setCellValue("G{$r2}", $g['comunidades_negras'] ?? '0');
            $sheet2->setCellValue("H{$r2}", $g['raizal'] ?? '0');
            $sheet2->setCellValue("I{$r2}", $g['palenquera'] ?? '0');
            $sheet2->setCellValue("J{$r2}", $g['privada_libertad'] ?? '0');
            $sheet2->setCellValue("K{$r2}", $g['victima_trata'] ?? '0');
            $sheet2->setCellValue("L{$r2}", $g['tercera_edad'] ?? '0');
            $sheet2->setCellValue("M{$r2}", $g['adolescentes_jovenes'] ?? '0');
            $sheet2->setCellValue("N{$r2}", $g['adolescentes_ley_penal'] ?? '0');
            $sheet2->setCellValue("O{$r2}", $g['mujer_cabeza_hogar'] ?? '0');
            $sheet2->setCellValue("P{$r2}", $g['reincorporacion'] ?? '0');
            $sheet2->setCellValue("Q{$r2}", $g['reintegracion'] ?? '0');
            $sheet2->setCellValue("R{$r2}", $g['victima_agente_quimico'] ?? '0');
            $sheet2->setCellValue("S{$r2}", $g['pueblo_rom'] ?? '0');
            $sheet2->setCellValue("T{$r2}", $g['mujeres_empresarias'] ?? '0');
            $sheet2->setCellValue("U{$r2}", $g['ninguna'] ?? '0');
            $sheet2->setCellValue("V{$r2}", $c->edu_sin_estudios);
            $sheet2->setCellValue("W{$r2}", $c->edu_primaria);
            $sheet2->setCellValue("X{$r2}", $c->edu_secundaria);
            $sheet2->setCellValue("Y{$r2}", $c->edu_media);
            $sheet2->setCellValue("Z{$r2}", $c->edu_tecnico);
            $sheet2->setCellValue("AA{$r2}", $c->edu_tecnologo);
            $sheet2->setCellValue("AB{$r2}", $c->edu_pregrado);
            $sheet2->setCellValue("AC{$r2}", $c->edu_posgrado);
            $sheet2->setCellValue("AD{$r2}", $c->kilos_harina_dia);
            $sheet2->setCellValue("AE{$r2}", $c->tipos_pan);
            $sheet2->setCellValue("AF{$r2}", is_array($c->prefermentos) ? implode(', ', $c->prefermentos) : ($c->prefermentos ?? ''));
            $sheet2->getStyle("A{$r2}:{$lastCol2}{$r2}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
                'font'    => ['size' => 9],
            ]);
            $r2++;
        }

        // ══ TERCERA HOJA: Masa Madre (completo) + Expectativas + Economía ══
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Expectativas y Economía');
        $lastCol3 = 'M';
        foreach (['A'=>6,'B'=>30,'C'=>10,'D'=>10,'E'=>10,'F'=>40,'G'=>40,'H'=>40,'I'=>40,'J'=>20,'K'=>12,'L'=>20,'M'=>20] as $col => $w) {
            $sheet3->getColumnDimension($col)->setWidth($w);
        }
        $r3 = 1;
        $sheet3->setCellValue("A{$r3}", 'EXPECTATIVAS · SITUACIÓN ECONÓMICA');
        $sheet3->mergeCells("A{$r3}:{$lastCol3}{$r3}");
        $sheet3->getStyle("A{$r3}:{$lastCol3}{$r3}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_TITULO]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet3->getRowDimension($r3)->setRowHeight(24);
        $r3++;

        $headers3 = [
            'A' => '#',
            'B' => 'Panadería',
            'C' => 'Sabe masa madre',
            'D' => 'Usa masa madre',
            'E' => 'Recibió transferencia',
            'F' => 'Pan masa madre deseado',
            'G' => 'Expectativa aprendizaje',
            'H' => 'Preocupación masa madre',
            'I' => 'Expectativa proyecto',
            'J' => 'Situación económica',
            'K' => 'Cierre/reducción',
            'L' => 'Dificultad para sostener',
            'M' => 'Nuevas técnicas → ↑ingresos',
        ];
        foreach ($headers3 as $col => $label) {
            $sheet3->setCellValue("{$col}{$r3}", $label);
        }
        $sheet3->getStyle("A{$r3}:{$lastCol3}{$r3}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => self::COLOR_TEXT_BLANCO]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COLOR_SECCION]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
        ]);
        $sheet3->getRowDimension($r3)->setRowHeight(36);
        $r3++;

        foreach ($rows as $idx => $c) {
            $bgColor = ($idx % 2 === 0) ? 'FFFFFFFF' : self::COLOR_IMPARES;
            $sheet3->setCellValue("A{$r3}", $idx + 1);
            $sheet3->setCellValue("B{$r3}", $c->panaderia->nombre ?? '—');
            $sheet3->setCellValue("C{$r3}", $c->sabe_masa_madre);
            $sheet3->setCellValue("D{$r3}", $c->usa_masa_madre);
            $sheet3->setCellValue("E{$r3}", $c->recibio_transferencia);
            $sheet3->setCellValue("F{$r3}", $c->pan_masa_madre_deseado);
            $sheet3->setCellValue("G{$r3}", $c->expectativa_aprendizaje);
            $sheet3->setCellValue("H{$r3}", $c->preocupacion_masa_madre);
            $sheet3->setCellValue("I{$r3}", $c->expectativa_proyecto);
            $sheet3->setCellValue("J{$r3}", $c->situacion_economica);
            $sheet3->setCellValue("K{$r3}", $c->cierre_reduccion);
            $sheet3->setCellValue("L{$r3}", $c->dificultad_sostener);
            $sheet3->setCellValue("M{$r3}", $c->nuevas_tecnicas_ingresos);
            $sheet3->getStyle("A{$r3}:{$lastCol3}{$r3}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => self::COLOR_BORDE]]],
                'font'      => ['size' => 9],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);
            $r3++;
        }

        // Activar primera hoja al abrir
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}



