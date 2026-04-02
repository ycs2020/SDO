<?php
include '../sdo-funciones/conexion.php';

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$dominioBase = 'http://sdo.hospitalquillota.cl/';

$folio = null;
$esBusquedaPorFolio = false;

// ✅ Verificar si viene folioSDO o numSoliCompra
if (isset($_GET['folioSDO'])) {
    $folio = strtoupper(trim(filter_var($_GET['folioSDO'], FILTER_SANITIZE_STRING)));
    if (!preg_match('/^SC\-SDO\-\d{4}\-[A-Z0-9]+\-\d{4}\-[A-Z0-9]+$/', $folio)) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de folio inválido: ' . htmlspecialchars($folio)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    $esBusquedaPorFolio = true;

} elseif (isset($_GET['numSDO'])) {
    $folio = trim(filter_var($_GET['numSDO'], FILTER_SANITIZE_STRING));
    if (!is_numeric($folio)) {
        http_response_code(400);
        echo json_encode(['error' => 'Número de solicitud inválido: ' . htmlspecialchars($folio)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetro "folioSDO" o "numSDO" no especificado'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ✅ Consulta principal según parámetro recibido
if ($esBusquedaPorFolio) {
    $sqlSolicitud = "SELECT * FROM solicitudes_compra_sdo WHERE folioSoliCompra LIKE ?";
} else {
    $sqlSolicitud = "SELECT * FROM solicitudes_compra_sdo WHERE numSoliCompra LIKE ?";
}

$stmtSolicitud = $conexion->prepare($sqlSolicitud);

if (!$stmtSolicitud) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar consulta de solicitud'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$stmtSolicitud->bind_param("s", $folio);
$stmtSolicitud->execute();
$resultSolicitud = $stmtSolicitud->get_result();

if ($resultSolicitud->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Solicitud no encontrada'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$solicitud = $resultSolicitud->fetch_assoc();

// ✅ Obtener materiales
$sqlMateriales = "
    SELECT nomArticuloSC, cantidadArticuloSC, medidaSC, valorSC
    FROM material_solicompra_sdo
    WHERE idSoliCompra = ?
    ORDER BY nomArticuloSC ASC
";
$stmtMat = $conexion->prepare($sqlMateriales);

if (!$stmtMat) {
    http_response_code(500);
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

// ✅ Obtener cotizaciones
$sqlCoti = "
    SELECT nomCotizacionSC, rutaCotizacionSC
    FROM cotizacion_solicompra_sdo
    WHERE idSoliCompra = ?
    ORDER BY nomCotizacionSC ASC
";
$stmtCoti = $conexion->prepare($sqlCoti);

if (!$stmtCoti) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar consulta de cotizaciones'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$stmtCoti->bind_param("i", $solicitud['idSoliCompra']);
$stmtCoti->execute();
$resultCoti = $stmtCoti->get_result();

$cotizaciones = [];
while ($row = $resultCoti->fetch_assoc()) {
    $rutaRelativa = ltrim($row['rutaCotizacionSC'], './');
    $partes = explode('/', $rutaRelativa);
    $nombreArchivo = rawurlencode(array_pop($partes));
    $rutaCompleta = implode('/', $partes) . '/' . $nombreArchivo;

    $row['rutaCotizacionSC'] = $dominioBase . $rutaCompleta;
    $cotizaciones[] = $row;
}

// ✅ Armar JSON de respuesta
$respuesta = [
    'folioSDO' => $solicitud['folioSoliCompra'],
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

header('Content-Type: application/json; charset=utf-8');
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

$conexion->close();
?>

