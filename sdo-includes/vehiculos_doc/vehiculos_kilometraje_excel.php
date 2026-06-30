<?php
// vehiculos_kilometraje_excel.php

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
$hoja->setTitle("Reporte Kilometraje");
$hoja->setShowGridlines(true);

// --- CONFIGURACIÓN DE IMPRESIÓN ---
$hoja->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$hoja->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$hoja->getPageSetup()->setFitToPage(true);
$hoja->getPageSetup()->setFitToWidth(1);
$hoja->getPageSetup()->setFitToHeight(0);

// Altura de filas iniciales
$hoja->getRowDimension('1')->setRowHeight(40);
$hoja->getRowDimension('2')->setRowHeight(25);

// --- LOGO ---
$rutaLogo = '../../dist/img/movilizacion.jpg';
if (file_exists($rutaLogo)) {
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setPath($rutaLogo);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(54); // Reducido un poco para que calce estéticamente en la fila 1 sin romper la malla
    $objDrawing->setWorksheet($hoja);
}

// --- DEFINICIÓN DE ESTILOS ---
$estiloBordes = [
    'borders' => [
        'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => ['rgb' => 'D3D3D3']
        ]
    ],
    'alignment' => ['vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER]
];

$estiloTitulo = [
    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']], // Tamaño 12 para que no se desborde en 3 columnas
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

$estiloTotal = [
    'font' => ['bold' => true],
    'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'E9ECEF']],
    'borders' => [
        'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        'bottom' => ['style' => PHPExcel_Style_Border::BORDER_DOUBLE]
    ]
];

// --- TEXTOS DE CABECERA ---
$textoRango = ($descargarTodo == 'si') ? "HISTORIAL COMPLETO" : "Rango: " . date('d/m/Y', strtotime($fechaInicio)) . " al " . date('d/m/Y', strtotime($fechaFin));

// CORRECCIÓN: Combinar de A a C para cubrir toda la tabla corta y evitar desbordes en el XML de PHPExcel
$hoja->mergeCells('A1:D1');
$hoja->setCellValue('A1', "HISTORIAL DE KILOMETRAJE - " . mb_convert_case($idUnico, MB_CASE_UPPER, 'UTF-8') . " ($patente)");
$hoja->getStyle('A1:D1')->applyFromArray($estiloTitulo);

$hoja->mergeCells('A2:D2');
$hoja->setCellValue('A2', $textoRango);
$hoja->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$hoja->getStyle('A2:D2')->getFont()->setItalic(true);

// --- TABLA ---
$fila = 5;
$cabeceras = ['FECHA MEDICIÓN', 'FECHA INGRESO A SISTEMA', 'KILOMETRAJE', 'ESTADO'];
$hoja->fromArray($cabeceras, NULL, 'A' . $fila);
$hoja->getStyle('A'.$fila.':D'.$fila)->applyFromArray($estiloCabecera);

// --- CONSULTA ---
if ($descargarTodo == 'si') {
    $query = "SELECT * FROM historial_kilometraje_moviles WHERE idMovil = ? ORDER BY fechaRegistro ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idMovil);
} else {
    $query = "SELECT * FROM historial_kilometraje_moviles WHERE idMovil = ? AND DATE(fechaRegistro) BETWEEN ? AND ? ORDER BY fechaRegistro ASC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iss", $idMovil, $fechaInicio, $fechaFin);
}
$stmt->execute();
$res = $stmt->get_result();

// Variables para acumular totales en PHP
$acumKM = 0;

$inicioDatos = $fila + 1;
while ($row = $res->fetch_assoc()) {
    $fila++;

    // 1. VALIDACIÓN Y ASIGNACIÓN DE FECHA DE CARGA (Columna A)
    $fRegistro = $row['fechaRegistro'];
    if (!empty($fRegistro) && $fRegistro !== '0000-00-00 00:00:00' && $fRegistro !== '0000-00-00') {
        $fechaEx = PHPExcel_Shared_Date::PHPToExcel(strtotime($fRegistro));
        $hoja->setCellValue('A' . $fila, $fechaEx);
        $hoja->getStyle('A' . $fila)->getNumberFormat()->setFormatCode('dd-mm-yyyy hh:mm');
    } else {
        $hoja->setCellValue('A' . $fila, '');
    }

    // 2. VALIDACIÓN Y ASIGNACIÓN DE FECHA COMPROBANTE (Columna B)
    $fSubida = $row['fechaSubida'];
    if (!empty($fSubida) && $fSubida !== '0000-00-00 00:00:00' && $fSubida !== '0000-00-00') {
        $fechaComprob = PHPExcel_Shared_Date::PHPToExcel(strtotime($fSubida));
        $hoja->setCellValue('B' . $fila, $fechaComprob);
        $hoja->getStyle('B' . $fila)->getNumberFormat()->setFormatCode('dd-mm-yyyy hh:mm');
    } else {
        $hoja->setCellValue('B' . $fila, '');
    }

    $estadoVehiculo = $row['estadoVehiculo'] ?? 'DESCONOCIDO';
    $hoja->setCellValue('D' . $fila, $estadoVehiculo);

// 🛠️ CENTRALIZAR HORIZONTALMENTE LA CELDA "D" DE ESTA FILA
    $hoja->getStyle('D' . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    // 3. LIMPIEZA DE VALORES NUMÉRICOS
    $kmLimpios = preg_replace('/[^0-9.]/', '', $row['cantKilometraje']);
    $kmLimpiosInt = (int)$kmLimpios;

    // Acumular los totales en PHP
//    $acumKM += $kmLimpiosInt;

    // Inyectamos los valores ya calculados para ganarle a la Vista Protegida
    $hoja->setCellValueExplicit('C' . $fila, $kmLimpiosInt, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->getStyle('C' . $fila)->getNumberFormat()->setFormatCode('#,##0');

    // 4. COLORES DE FILA (Fieles al Dashboard)
    if ($row['registroMantenimiento'] == 1) {
        $hoja->getStyle('A'.$fila.':D'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
    } elseif ($fila % 2 == 0) {
        $hoja->getStyle('A'.$fila.':D'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
    }
}
$finDatos = $fila;


// --- FILA DE TOTALES ACUMULADOS ---
if ($finDatos >= $inicioDatos) {
    $fila++;

    $hoja->getStyle('A'.$fila.':D'.$fila)->applyFromArray($estiloTotal);
}

// --- AJUSTES FINALES ---

// 1. Aplicar bordes generales al bloque de datos completo
$hoja->getStyle('A5:D'.$fila)->applyFromArray($estiloBordes);

// 2. Activar Autofiltro (Excluyendo los totales)
if ($finDatos >= $inicioDatos) {
    $hoja->setAutoFilter('A5:D'.$finDatos);
}

// 3. Anchos manuales holgados (Previene errores de visualización de celdas '###')
$hoja->getColumnDimension('A')->setWidth(25);
$hoja->getColumnDimension('B')->setWidth(35);
$hoja->getColumnDimension('C')->setWidth(25);
$hoja->getColumnDimension('D')->setWidth(25);

// --- PROCESO DE DESCARGA ---
$idUnicoLimpio = str_replace([' ', '-', '/'], '_', $idUnico);
$nomDescarga = "Reporte_Kilometraje_" . $idUnicoLimpio . "_" . date('His') . ".xlsx";

if (ob_get_length()) ob_end_clean();

$objWriter = PHPExcel_IOFactory::createWriter($archivo, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nomDescarga.'"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');
exit;