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
$hoja->setShowGridlines(true);

// --- CONFIGURACIÓN DE IMPRESIÓN ---
$hoja->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$hoja->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$hoja->getPageSetup()->setFitToPage(true);
$hoja->getPageSetup()->setFitToWidth(1);
$hoja->getPageSetup()->setFitToHeight(0);

// Altura de filas iniciales
$hoja->getRowDimension('1')->setRowHeight(43);
$hoja->getRowDimension('2')->setRowHeight(25);

// --- LOGO ---
$rutaLogo = '../../dist/img/movilizacion.jpg';
if (file_exists($rutaLogo)) {
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setPath($rutaLogo);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(55);
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

$hoja->mergeCells('B1:I1');
$hoja->setCellValue('B1', "HISTORIAL DE CARGA DE PETRÓLEO - " . mb_convert_case($idUnico, MB_CASE_UPPER, 'UTF-8') . " ($patente)");
$hoja->getStyle('B1:I1')->applyFromArray($estiloTitulo);

$hoja->mergeCells('B2:I2');
$hoja->setCellValue('B2', $textoRango);
$hoja->getStyle('B2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$hoja->getStyle('B2:I2')->getFont()->setItalic(true);

// --- TABLA ---
$fila = 5;
$cabeceras = ['FECHA Y HORA DE CARGA', 'FECHA INGRESO COMPROBANTE', 'CONDUCTOR', 'N° GUÍA', 'LITROS', 'V. UNITARIO', 'NETO', 'IVA (19%)', 'TOTAL'];
$hoja->fromArray($cabeceras, NULL, 'A' . $fila);
$hoja->getStyle('A'.$fila.':I'.$fila)->applyFromArray($estiloCabecera);

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

// Variables para acumular totales en PHP
$acumLitros = 0;
$acumNeto   = 0;
$acumIva    = 0;
$acumTotal  = 0;

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

    // 3. TEXTOS
    $hoja->setCellValue('C' . $fila, mb_convert_case($row['nomConductor'], MB_CASE_UPPER, 'UTF-8'));
    $hoja->setCellValue('D' . $fila, $row['numGuia']);

    // 4. LIMPIEZA AGRESIVA DE VALORES NUMÉRICOS
    $litrosLimpios       = preg_replace('/[^0-9.]/', '', $row['cantidadLitros']);
    $valorUnitarioLimpio = preg_replace('/[^0-9]/', '', $row['valorUnitario']);

    $litrosFloat  = (float)$litrosLimpios;
    $vUnitarioInt = (int)$valorUnitarioLimpio;

    // 5. CÁLCULO MATEMÁTICO PREVIO EN PHP
    $netoCalculado  = $litrosFloat * $vUnitarioInt;
    $ivaCalculado   = round($netoCalculado * 0.19);
    $totalCalculado = $netoCalculado + $ivaCalculado;

    // Acumular los totales en PHP
    $acumLitros += $litrosFloat;
    $acumNeto   += $netoCalculado;
    $acumIva    += $ivaCalculado;
    $acumTotal  += $totalCalculado;

    // Inyectamos los valores ya calculados para ganarle a la Vista Protegida
    $hoja->setCellValueExplicit('E' . $fila, $litrosFloat, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('F' . $fila, $vUnitarioInt, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('G' . $fila, $netoCalculado, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('H' . $fila, $ivaCalculado, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('I' . $fila, $totalCalculado, PHPExcel_Cell_DataType::TYPE_NUMERIC);

    // 6. FORMATOS VISUALES DE EXCEL
    $hoja->getStyle('E' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $hoja->getStyle('F'.$fila.':I'.$fila)->getNumberFormat()->setFormatCode('$#,##0');

    // Cebra intercalado
    if($fila % 2 == 0){
        $hoja->getStyle('A'.$fila.':I'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
    }
}
$finDatos = $fila;


// --- FILA DE TOTALES ACUMULADOS ---
if ($finDatos >= $inicioDatos) {
    $fila++;
    $hoja->setCellValue('A' . $fila, 'TOTALES GENERALES');
    $hoja->mergeCells("A{$fila}:D{$fila}");
    $hoja->getStyle("A{$fila}:D{$fila}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // CAMBIO CLAVE: Escribimos el valor estático calculado en PHP para burlar la vista protegida,
    // pero dejamos la fórmula por detrás para cuando habiliten la escritura.
    $hoja->setCellValueExplicit('E' . $fila, $acumLitros, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('G' . $fila, $acumNeto, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('H' . $fila, $acumIva, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $hoja->setCellValueExplicit('I' . $fila, $acumTotal, PHPExcel_Cell_DataType::TYPE_NUMERIC);

    // Si quieres que Excel mantenga las fórmulas nativas al habilitar la edición,
    // PHPExcel permite sobreescribir el valor de despliegue manteniendo la ecuación:
    $hoja->setCellValue('E' . $fila, "=SUM(E{$inicioDatos}:E{$finDatos})");
    $hoja->setCellValue('G' . $fila, "=SUM(G{$inicioDatos}:G{$finDatos})");
    $hoja->setCellValue('H' . $fila, "=SUM(H{$inicioDatos}:H{$finDatos})");
    $hoja->setCellValue('I' . $fila, "=SUM(I{$inicioDatos}:I{$finDatos})");

    // Forzar el recalculo previo en el Writer para que guarde los números en el XML del archivo
    // Esto asegura que la celda muestre el total real incluso con la barra amarilla activa.
    PHPExcel_Calculation::getInstance($archivo)->calculateFormula($hoja->getCell('E' . $fila));
    PHPExcel_Calculation::getInstance($archivo)->calculateFormula($hoja->getCell('G' . $fila));
    PHPExcel_Calculation::getInstance($archivo)->calculateFormula($hoja->getCell('H' . $fila));
    PHPExcel_Calculation::getInstance($archivo)->calculateFormula($hoja->getCell('I' . $fila));

    // Aplicar formatos a los totales
    $hoja->getStyle('E' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $hoja->getStyle('G'.$fila.':I'.$fila)->getNumberFormat()->setFormatCode('$#,##0');
    $hoja->getStyle('A'.$fila.':I'.$fila)->applyFromArray($estiloTotal);
}

// --- AJUSTES FINALES ---

// 1. Aplicar bordes generales al bloque de datos completo
$hoja->getStyle('A5:I'.$fila)->applyFromArray($estiloBordes);

// 2. Activar Autofiltro (Se limita solo al rango de datos, excluyendo la fila de totales)
if ($finDatos >= $inicioDatos) {
    $hoja->setAutoFilter('A5:I'.$finDatos);
}

// 3. Anchos manuales holgados
$hoja->getColumnDimension('A')->setWidth(36); // Un poco más de espacio para Fecha Carga
$hoja->getColumnDimension('B')->setWidth(36); // Espacio extra para asegurar que no salgan los ###
$hoja->getColumnDimension('C')->setWidth(38); // Conductor
$hoja->getColumnDimension('D')->setWidth(20); // Guía
$hoja->getColumnDimension('E')->setWidth(18); // Litros
$hoja->getColumnDimension('F')->setWidth(20); // V. Unitario
$hoja->getColumnDimension('G')->setWidth(20); // Neto
$hoja->getColumnDimension('H')->setWidth(20); // IVA
$hoja->getColumnDimension('I')->setWidth(20); // Total

// --- PROCESO DE DESCARGA ---
$idUnicoLimpio = str_replace([' ', '-', '/'], '_', $idUnico);
$nomDescarga = "Reporte_Petroleo_" . $idUnicoLimpio . "_" . date('His') . ".xlsx";

if (ob_get_length()) ob_end_clean();

$objWriter = PHPExcel_IOFactory::createWriter($archivo, 'Excel2007');

// Esto le dice al generador que pre-calcule todas las fórmulas e incruste el resultado estático
// en el archivo para que se lea inmediatamente al abrir, saltándose el bloqueo de Excel.
$objWriter->setPreCalculateFormulas(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nomDescarga.'"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');
exit;