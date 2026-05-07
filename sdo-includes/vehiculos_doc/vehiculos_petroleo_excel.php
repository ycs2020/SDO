<?php
// vehiculos_petroleo_excel.php

// 1. CONTROL DE ERRORES Y LIMPIEZA
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 0);

ob_start();

include_once '../../sdo-funciones/conexion.php';
include_once '../../sdo-funciones/funciones.php';

if (session_status() == PHP_SESSION_NONE) { session_start(); }

date_default_timezone_set('America/Santiago');
$now = date('d-m-Y_H-i-s');

if (!isset($_SESSION['idUsuario'])) { die("Acceso denegado."); }

// 2. RECEPCIÓN DE PARÁMETROS
$idMovil       = $_POST['idMovil'] ?? null;
$fechaInicio   = $_POST['inicio'] ?? null;
$fechaFin      = $_POST['fin'] ?? null;
$descargarTodo = $_POST['todo'] ?? 'no';

if (!$idMovil) { die("Error: ID de móvil no proporcionado."); }

// --- CONSULTA PARA OBTENER DATOS DEL VEHÍCULO ---
$stmtMovil = $conexion->prepare("SELECT nomVehiculo, patenteVehiculo, idUnico FROM moviles_movilizacion WHERE idMovil = ?");
$stmtMovil->bind_param("i", $idMovil);
$stmtMovil->execute();
$resMovil = $stmtMovil->get_result()->fetch_assoc();
$nombreVehiculo = $resMovil['nomVehiculo'] ?? 'DESCONOCIDO';
$patente = $resMovil['patenteVehiculo'] ?? 'S_P';
$idUnico = $resMovil['idUnico'] ?? 'DESCONOCIDO';


// --- CARGAR LIBRERÍA ---
require_once '../../sdo-excelphp/Classes/PHPExcel.php';

$archivo = new PHPExcel();
$hoja = $archivo->getActiveSheet();
$hoja->setTitle("Reporte Petroleo");
$hoja->setShowGridlines(false);

// --- CONFIGURACIÓN DE IMPRESIÓN (Para que salga bien al imprimir) ---
$hoja->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); // Horizontal
$hoja->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$hoja->getPageSetup()->setFitToPage(true);
$hoja->getPageSetup()->setFitToWidth(1);
$hoja->getPageSetup()->setFitToHeight(0);

// Altura de filas iniciales
$hoja->getRowDimension('1')->setRowHeight(35);
$hoja->getRowDimension('2')->setRowHeight(25);

// --- LOGO ---
$rutaLogo = '../../dist/img/movilizacion.jpg';
if (file_exists($rutaLogo)) {
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setPath($rutaLogo);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(75);
    $objDrawing->setWorksheet($hoja);
}

// --- DEFINICIÓN DE ESTILOS DE BORDES ---
$estiloBordes = [
    'borders' => [
        'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ],
    'alignment' => ['vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER]
];

$estiloTitulo = [
    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
    'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '28A745']]
];

$estiloCabecera = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
    'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '343A40']],
    'borders' => [
        'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

// --- TEXTOS DE CABECERA ---
$textoRango = ($descargarTodo == 'si') ? "HISTORIAL COMPLETO" : "Rango: " . date('d/m/Y', strtotime($fechaInicio)) . " al " . date('d/m/Y', strtotime($fechaFin));

$hoja->mergeCells('B1:H1');
$hoja->setCellValue('B1', "HISTORIAL DE CARGA DE PETRÓLEO - " . mb_convert_case($idUnico, MB_CASE_UPPER, 'UTF-8') . " ($patente)");
$hoja->getStyle('B1:H1')->applyFromArray($estiloTitulo);

$hoja->mergeCells('B2:H2');
$hoja->setCellValue('B2', $textoRango);
$hoja->getStyle('B2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$hoja->getStyle('B2:H2')->getFont()->setItalic(true);

// --- TABLA ---
$fila = 5;
$cabeceras = ['FECHA', 'CONDUCTOR', 'N° GUÍA', 'LITROS', 'V. UNITARIO', 'NETO', 'IVA (19%)', 'TOTAL'];
$hoja->fromArray($cabeceras, NULL, 'A' . $fila);
$hoja->getStyle('A'.$fila.':H'.$fila)->applyFromArray($estiloCabecera);

// --- CONSULTA ---
if ($descargarTodo == 'si') {
    $query = "SELECT * FROM historial_petroleo_moviles WHERE idMovil = ? ORDER BY fechaRegistro ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idMovil);
} else {
    $query = "SELECT * FROM historial_petroleo_moviles WHERE idMovil = ? AND DATE(fechaRegistro) BETWEEN ? AND ? ORDER BY fechaRegistro ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iss", $idMovil, $fechaInicio, $fechaFin);
}
$stmt->execute();
$res = $stmt->get_result();

$inicioDatos = $fila + 1;
while ($row = $res->fetch_assoc()) {
    $fila++;

    $fechaEx = PHPExcel_Shared_Date::PHPToExcel(strtotime($row['fechaRegistro']));
    $hoja->setCellValue('A' . $fila, $fechaEx);
    $hoja->getStyle('A' . $fila)->getNumberFormat()->setFormatCode('DD-MM-YYYY HH:MM');

    $hoja->setCellValue('B' . $fila, mb_convert_case($row['nomConductor'], MB_CASE_UPPER, 'UTF-8'));
    $hoja->setCellValue('C' . $fila, $row['numGuia']);
    $hoja->setCellValue('D' . $fila, (float)$row['cantidadLitros']);
    $hoja->setCellValue('E' . $fila, (int)$row['valorUnitario']);
    $hoja->setCellValue('F' . $fila, (int)$row['totalSinIva']);
    $hoja->setCellValue('G' . $fila, (int)$row['IvaCalculado']);
    $hoja->setCellValue('H' . $fila, (int)$row['totalConIva']);

    $hoja->getStyle('E'.$fila.':H'.$fila)->getNumberFormat()->setFormatCode('$#,##0');

    if($fila % 2 == 0){
        $hoja->getStyle('A'.$fila.':H'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
    }
}

// --- AJUSTES FINALES ---

// 1. APLICAR BORDES A RANGO DE DATOS (Desde la cabecera hasta la última fila)
$hoja->getStyle('A5:H'.$fila)->applyFromArray($estiloBordes);

// 2. ACTIVAR AUTOFILTRO
$hoja->setAutoFilter('A5:H'.$fila);

// 3. ANCHOS MANUALES HOLGADOS (Para que no salgan los ###)
$hoja->getColumnDimension('A')->setWidth(22); // Fecha
$hoja->getColumnDimension('B')->setWidth(30); // Conductor
$hoja->getColumnDimension('C')->setWidth(18); // Guía
$hoja->getColumnDimension('D')->setWidth(12); // Litros
$hoja->getColumnDimension('E')->setWidth(16); // V. Unitario
$hoja->getColumnDimension('F')->setWidth(16); // Neto
$hoja->getColumnDimension('G')->setWidth(16); // IVA
$hoja->getColumnDimension('H')->setWidth(18); // Total

// --- PROCESO DE DESCARGA ---
$idUnicoLimpio = str_replace([' ', '-', '/'], '_', $idUnico);
$nomDescarga = "Reporte_Petroleo_" . $idUnicoLimpio . "_" . date('His') . ".xlsx";

if (ob_get_length()) ob_end_clean();

$objWriter = PHPExcel_IOFactory::createWriter($archivo, 'Excel2007');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nomDescarga.'"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');
exit;