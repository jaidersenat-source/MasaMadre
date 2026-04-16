<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProcesoExcelExport
{
    public function __construct(private $registro) {}

    public function download(string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Informe Proceso');

        $p = $this->registro->panaderia;
        $r = $this->registro;

        // ── Estilos de utilidad ────────────────────────────────────────────
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $labelStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
        ];
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FF000000']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFBFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
        $centerBold = [
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $center = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // ── Ancho de columnas (A–N) ────────────────────────────────────────
        $widths = [
            'A' => 6,  'B' => 18, 'C' => 18, 'D' => 10,
            'E' => 10, 'F' => 10, 'G' => 10, 'H' => 8,
            'I' => 18, 'J' => 18, 'K' => 14, 'L' => 10,
            'M' => 12, 'N' => 12,
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $row = 1;

        // ══ BLOQUE 1: cabecera de panadería ════════════════════════════════
        // Fila 1: FECHA | HORA | NOMBRE DE LA PANADERÍA
        $sheet->mergeCells("A{$row}:A{$row}");
        $sheet->setCellValue("A{$row}", 'FECHA:');
        $sheet->setCellValue("B{$row}", $r->fecha_inicio?->format('d/m/Y') ?? '');
        $sheet->setCellValue("C{$row}", 'HORA:');
        $sheet->setCellValue("D{$row}", $r->hora_inicio ?? '');
        $sheet->setCellValue("E{$row}", 'NOMBRE DE LA PANADERÍA:');
        $sheet->mergeCells("E{$row}:G{$row}");
        $sheet->setCellValue("H{$row}", strtoupper($p->nombre ?? ''));
        $sheet->mergeCells("H{$row}:N{$row}");

        foreach (['A', 'B', 'C', 'D', 'E', 'H'] as $c) {
            $sheet->getStyle("{$c}{$row}")->applyFromArray($labelStyle);
        }
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila 2: CIUDAD | DIRECCIÓN
        $sheet->setCellValue("A{$row}", 'CIUDAD O MUNICIPIO:');
        $sheet->mergeCells("A{$row}:A{$row}");
        $sheet->setCellValue("B{$row}", $p->ciudad ?? '');
        $sheet->mergeCells("B{$row}:C{$row}");
        $sheet->setCellValue("D{$row}", 'DIRECCIÓN:');
        $sheet->setCellValue("E{$row}", $p->direccion ?? '');
        $sheet->mergeCells("E{$row}:N{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("D{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila 3: REGIONAL
        $sheet->setCellValue("A{$row}", 'REGIONAL:');
        $sheet->mergeCells("A{$row}:A{$row}");
        $sheet->setCellValue("B{$row}", $p->regional ?? '');
        $sheet->mergeCells("B{$row}:N{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila 4: CENTRO DE FORMACIÓN
        $sheet->setCellValue("A{$row}", 'CENTRO DE FORMACIÓN:');
        $sheet->mergeCells("A{$row}:A{$row}");
        $sheet->setCellValue("B{$row}", strtoupper($p->centro_formacion ?? ''));
        $sheet->mergeCells("B{$row}:N{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila 5: NOMBRE DE EXTENSIONISTA
        $sheet->setCellValue("A{$row}", 'NOMBRE DE EXTENSIONISTA:');
        $sheet->mergeCells("A{$row}:A{$row}");
        $sheet->setCellValue("B{$row}", strtoupper($p->extensionista ?? ''));
        $sheet->mergeCells("B{$row}:N{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila 6: espacio
        $row++;

        // ══ BLOQUE 2: AGUA DE PROCESO INICIAL ══════════════════════════════
        $sheet->setCellValue("A{$row}", 'AGUA DE PROCESO INICIAL');
        $sheet->mergeCells("A{$row}:N{$row}");
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font'      => ['bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFBFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        $sheet->setCellValue("A{$row}", 'Rango pH agua (Tiras 6,5 a 9)');
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("F{$row}", $r->ph_agua ?? '');
        $sheet->setCellValue("G{$row}", 'Nivel de cloro en Cinta (0,3 a 2 ppm)');
        $sheet->mergeCells("G{$row}:M{$row}");
        $sheet->setCellValue("N{$row}", $r->cloro_agua ?? '');
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("G{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $row++;

        // Fila vacía
        $row++;

        // ══ BLOQUE 3: ELABORACIÓN DE LA MASA MADRE ═════════════════════════
        $sheet->setCellValue("A{$row}", 'ELABORACIÓN DE LA MASA MADRE');
        $sheet->mergeCells("A{$row}:N{$row}");
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font'      => ['bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFBFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        // Encabezados días
        $diasHeaders = [
            'A' => 'Día',
            'B' => "Porcentaje de\nharina de Trigo\n(%)",
            'C' => "Indicar proporcion y\ntipos de otras\nharinas, Si Aplica",
            'D' => "Porcentaje de Agua\n(%)",
            'E' => "Temp. agua\n(°C)",
            'F' => "Temp.\nambiente\n(°C)",
            'G' => "Temp.\nmezcla\n(°C)",
            'H' => "pH inicial",
            'I' => "Tiempo de maduración\n(Horas)",
            'J' => "Observaciones",
            'K' => "Responsable",
        ];
        foreach ($diasHeaders as $col => $label) {
            $sheet->setCellValue("{$col}{$row}", $label);
            $sheet->getStyle("{$col}{$row}")->applyFromArray($headerStyle);
        }
        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray($border);
        $sheet->getRowDimension($row)->setRowHeight(50);
        $row++;

        foreach ($r->dias->sortBy('dia') as $dia) {
            $sheet->setCellValue("A{$row}", $dia->dia);
            $sheet->setCellValue("B{$row}", $dia->pct_harina_trigo);
            $sheet->setCellValue("C{$row}", $dia->otras_harinas ?? 'NA');
            $sheet->setCellValue("D{$row}", $dia->pct_agua);
            $sheet->setCellValue("E{$row}", $dia->temp_agua);
            $sheet->setCellValue("F{$row}", $dia->temp_ambiente);
            $sheet->setCellValue("G{$row}", $dia->temp_mezcla);
            $sheet->setCellValue("H{$row}", $dia->ph_inicial);
            $sheet->setCellValue("I{$row}", $dia->tiempo_maduracion_horas);
            $sheet->setCellValue("J{$row}", $dia->observaciones ?? 'NA');
            $sheet->setCellValue("K{$row}", $dia->responsable);
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray(array_merge($border, $center));
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $row++;
        }

        // Fila vacía
        $row++;

        // ══ BLOQUE 4: ELABORACIÓN DE PAN CON MASA MADRE ════════════════════
        $sheet->setCellValue("A{$row}", 'ELABORACIÓN DE PAN CON MASA MADRE');
        $sheet->mergeCells("A{$row}:N{$row}");
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font'      => ['bold' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFBFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        foreach ($r->panes as $pan) {
            // Sub-cabecera: FECHA | HORA | TIPO DE PAN
            $sheet->setCellValue("A{$row}", 'FECHA:');
            $sheet->setCellValue("B{$row}", $pan->fecha_elaboracion?->format('d/m/Y') ?? '');
            $sheet->mergeCells("B{$row}:C{$row}");
            $sheet->setCellValue("D{$row}", 'HORA:');
            $sheet->setCellValue("E{$row}", $pan->hora_elaboracion ?? '');
            $sheet->mergeCells("E{$row}:F{$row}");
            $sheet->setCellValue("G{$row}", 'TIPO DE PAN A ELABORAR (SALUDABLE)');
            $sheet->mergeCells("G{$row}:J{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
            $sheet->getStyle("D{$row}")->applyFromArray($labelStyle);
            $sheet->getStyle("G{$row}")->applyFromArray($labelStyle);
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($border);
            $row++;

            // Encabezados pan
            $panHeaders = [
                'A' => "Tipo de harina",
                'B' => "Temp. agua (°C)",
                'C' => "Temp.\nambiente\n(°C)",
                'D' => "Temp.\nmasa\nmadre\n(°C)",
                'E' => "pH masa\nmadre (<4,2)",
                'F' => "pH masa antes de\ncocción (<4,8)",
                'G' => "pH pan\n(<5,8)",
                'H' => "Temperatura pan (°C)",
                'I' => "Observaciones",
                'J' => "Responsable",
            ];
            foreach ($panHeaders as $col => $label) {
                $sheet->setCellValue("{$col}{$row}", $label);
                $sheet->getStyle("{$col}{$row}")->applyFromArray($headerStyle);
            }
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray($border);
            $sheet->getRowDimension($row)->setRowHeight(55);
            $row++;

            // Fila de datos del pan
            $sheet->setCellValue("A{$row}", $pan->tipo_harina);
            $sheet->setCellValue("B{$row}", $pan->temp_agua);
            $sheet->setCellValue("C{$row}", $pan->temp_ambiente);
            $sheet->setCellValue("D{$row}", $pan->temp_masa_madre);
            $sheet->setCellValue("E{$row}", $pan->ph_masa_madre);
            $sheet->setCellValue("F{$row}", $pan->ph_masa_antes_coccion);
            $sheet->setCellValue("G{$row}", $pan->ph_pan);
            $sheet->setCellValue("H{$row}", $pan->temp_pan);
            $sheet->setCellValue("I{$row}", $pan->observaciones ?? 'NA');
            $sheet->setCellValue("J{$row}", $pan->responsable);
            $sheet->getStyle("A{$row}:J{$row}")->applyFromArray(array_merge($border, $center));
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $row++;
            $row++; // Espacio entre panes
        }

        // ══ BLOQUE 5: FECHA DE CALIBRACIÓN ═════════════════════════════════
        $sheet->setCellValue("A{$row}", 'FECHA DE VERIFICACIÓN/CALIBRACION DEL TESTER:');
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("G{$row}", $r->fecha_calibracion_tester?->format('d/m/Y') ?? '');
        $sheet->mergeCells("G{$row}:N{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray($labelStyle);
        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray($border);

        // ── Fijar autoajuste de texto en todas las celdas ──────────────────
        $sheet->getStyle('A1:N' . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
