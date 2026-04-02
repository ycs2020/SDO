<?php
include '../sdo-funciones/conexion.php';

// 🚫 No mostrar errores en producción
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// ✅ Validar existencia del parámetro
if (!isset($_GET['folio'])) {
    echo json_encode(['error' => 'Parámetro "folio" no especificado'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ✅ Sanitizar y validar formato del folio (ajustado a SC-SDO-2025-CC-0074-YCS)
$folio = filter_var($_GET['folio'], FILTER_SANITIZE_STRING);

// Acepta letras, números, guiones (más permisivo)
if (!preg_match('/^SC\-SDO\-\d{4}\-CC\-\d{4}\-[A-Z]+$/', $folio)) {
    echo json_encode(['error' => 'Formato de folio inválido'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$dominioBase = 'http://sdo.hospitalquillota.cl/';

// ✅ Consulta de la solicitud
$sqlSolicitud = "SELECT * FROM solicitudes_compra_sdo_cc WHERE folioSoliCompra = ?";
$stmtSolicitud = $conexion->prepare($sqlSolicitud);

if (!$stmtSolicitud) {
    echo json_encode(['error' => 'Error al preparar consulta de solicitud'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$stmtSolicitud->bind_param("s", $folio);
$stmtSolicitud->execute();
$resultSolicitud = $stmtSolicitud->get_result();

if ($resultSolicitud->num_rows === 0) {
    echo json_encode(['error' => 'Solicitud no encontrada'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$solicitud = $resultSolicitud->fetch_assoc();

// ✅ Obtener materiales ordenados
$sqlMateriales = "
    SELECT nomArticuloSC, cantidadArticuloSC, medidaSC, valorSC
    FROM material_solicompra_sdo
    WHERE idSoliCompra = ?
    ORDER BY nomArticuloSC ASC
";
$stmtMat = $conexion->prepare($sqlMateriales);

if (!$stmtMat) {
    echo json_encode(['error' => 'Error al preparar consulta de materiales'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$stmtMat->bind_param("i", $solicitud['idSoliCompra']);
$stmtMat->execute();
$resultMat = $stmtMat->get_result();

$materiales = [];
while ($row = $resultMat->fetch_assoc()) {
    $materiales[] = $row;
}

// ✅ Obtener cotizaciones ordenadas y convertir ruta
$sqlCoti = "
    SELECT nomCotizacionSC, rutaCotizacionSC
    FROM cotizacion_solicompra_sdo
    WHERE idSoliCompra = ?
    ORDER BY nomCotizacionSC ASC
";
$stmtCoti = $conexion->prepare($sqlCoti);

if (!$stmtCoti) {
    echo json_encode(['error' => 'Error al preparar consulta de cotizaciones'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$stmtCoti->bind_param("i", $solicitud['idSoliCompra']);
$stmtCoti->execute();
$resultCoti = $stmtCoti->get_result();

$cotizaciones = [];
while ($row = $resultCoti->fetch_assoc()) {
    $rutaRelativa = ltrim($row['rutaCotizacionSC'], './');

    // Separar ruta y archivo para aplicar rawurlencode solo al nombre
    $partes = explode('/', $rutaRelativa);
    $nombreArchivo = rawurlencode(array_pop($partes));
    $rutaCompleta = implode('/', $partes) . '/' . $nombreArchivo;

    $row['rutaCotizacionSC'] = $dominioBase . $rutaCompleta;
    $cotizaciones[] = $row;
}

// ✅ Armar respuesta JSON final
$respuesta = [
    'folio' => $solicitud['folioSoliCompra'],
    'numSoliCompra' => $solicitud['numSoliCompra'],
    'descripcion' => $solicitud['descripcionSoliCompra'],
    'fecha' => $solicitud['fechaSoliCompra'],
    'personal' => $solicitud['personalSoliCompra'],
    'unidad' => $solicitud['unidadSoliCompra'],
    'email' => $solicitud['emailSoliCompra'],
    'argumentos' => $solicitud['argumentosSoliCompra'],
    'estado' => $solicitud['estadoSoliCompra'],
    'adjuntaCotizacion' => (bool) $solicitud['adjuntaCotizacion'],
    'materiales' => $materiales,
    'cotizaciones' => $cotizaciones
];

header('Content-Type: application/json');
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
