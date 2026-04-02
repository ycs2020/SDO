<?php
require '../vendor/autoload.php';
include_once 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$headers = [
    'A1' => 'N° Orden de Compra',
    'B1' => 'Fecha del Documento',
    'C1' => 'Tipo de Documento',
    'D1' => 'N° Documento',
    'E1' => 'Proveedor',
    'F1' => 'Fecha de Lectura',
    'G1' => 'N° Lectura',
    'H1' => 'Consumo de Agua (Mt3)',
    'I1' => 'Valor Neto',
    'J1' => 'IVA',
    'K1' => 'Monto Total del Mes y Documento',
    'L1' => 'Estado de Pago'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

$sheet->getStyle('A1:K1')->getAlignment()->setWrapText(true);

$queryAC = "SELECT * FROM agua_administracion WHERE tipoHospital = 'HBQP' ORDER BY fechaDocumento DESC";
$resAC = mysqli_query($conexion, $queryAC);

$fila = 2;

while ($row = mysqli_fetch_assoc($resAC)) {

    $numeroOC = ($row['numeroOC'] == '' || $row['numeroOC'] == null) ? 'Sin Información' : $row['numeroOC'];
    $estado = ($row['estado'] == '' || $row['estado'] == null) ? 'Sin Información' : $row['estado'];

    $fechaDoc = (!empty($row['fechaDocumento']) && $row['fechaDocumento'] != '0000-00-00')
        ? Date::PHPToExcel(strtotime($row['fechaDocumento'])) : null;

    $fechaLect = (!empty($row['fechaLectura']) && $row['fechaLectura'] != '0000-00-00')
        ? Date::PHPToExcel(strtotime($row['fechaLectura'])) : null;

    $nLectura_val = (int)$row['nLectura'];
    $consumo_val = (int)$row['consumoAgua'];
    $neto_val = (int)$row['neto'];
    $iva_val = (int)$row['iva'];
    $total_val = (int)$row['montoTotalMes'];

    $sheet->setCellValue("A$fila", $numeroOC);
    $sheet->setCellValue("B$fila", $fechaDoc);
    $sheet->setCellValue("C$fila", $row['tipoDocumento']);
    $sheet->setCellValue("D$fila", $row['nDocumento']);
    $sheet->setCellValue("E$fila", $row['proveedor']);
    $sheet->setCellValue("F$fila", $fechaLect);
    $sheet->setCellValue("G$fila", $nLectura_val);
    $sheet->setCellValue("H$fila", $consumo_val . ' (Mt³)');
    $sheet->setCellValue("I$fila", $neto_val);
    $sheet->setCellValue("J$fila", $iva_val);
    $sheet->setCellValue("K$fila", $total_val);
    $sheet->setCellValue("L$fila", $estado);

    $sheet->getStyle("A$fila:K$fila")->getAlignment()->setWrapText(true);
    $fila++;
}

// FORMATO FECHAS
$sheet->getStyle("B2:B$fila")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
$sheet->getStyle("F2:F$fila")->getNumberFormat()->setFormatCode('dd/mm/yyyy');

// FORMATOS MONETARIOS Y NUMÉRICOS
$sheet->getStyle("G2:G$fila")->getNumberFormat()->setFormatCode('#,##0');
$sheet->getStyle("I2:K$fila")->getNumberFormat()->setFormatCode('"$" #,##0');

// ✅ Encabezado profesional
$sheet->getStyle('A1:L1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4D4D4D']],
//    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
]);

// ✅ Bordes finos
$sheet->getStyle("A1:L" . ($fila - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'A6A6A6']]]
]);

// ✅ Congelar encabezado
$sheet->freezePane('A2');

// ✅ AutoSize + recalcular
foreach(range('A','L') as $col){
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->calculateColumnWidths();

// ✅ Autofiltro
$sheet->setAutoFilter("A1:L" . ($fila - 1));

// Descargar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_Agua_HBQP.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
