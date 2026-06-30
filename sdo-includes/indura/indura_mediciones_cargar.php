<?php
// Archivo: sdo-includes/indura/indura_mediciones_cargar.php

error_reporting(0);
ini_set('display_errors', 0);

include '../../sdo-funciones/conexion.php';
include '../../sdo-funciones/funciones.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($conexion) && $conexion) {
    $conexion->set_charset("utf8");
} else {
    echo json_encode(["error" => "No se pudo establecer la conexión a la base de datos."]);
    exit;
}

$idTanque = isset($_POST['idTanque']) ? trim($_POST['idTanque']) : '';
$anio = isset($_POST['anio']) ? trim($_POST['anio']) : '';

if (empty($idTanque)) {
    echo json_encode(["datos" => [], "totalRecargas" => 0]);
    exit;
}

if (!empty($anio) && is_numeric($anio)) {
    $sql = "SELECT * FROM medicion_tanque_indura WHERE tanqueMedicion = ? AND YEAR(fechaMedicion) = ? ORDER BY fechaMedicion DESC;";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $idTanque, $anio);
} else {
    $sql = "SELECT * FROM medicion_tanque_indura WHERE tanqueMedicion = ? ORDER BY fechaMedicion DESC;";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $idTanque);
}

if (!$stmt) {
    echo json_encode(["error" => "Error preparando la consulta: " . $conexion->error]);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$datos = array();
$totalRecargas = 0; // <-- Inicializamos el contador de recargas

$mapeoFuentes = [
    'Telemetry' => 'Telemetría',
    'Telemetría' => 'Telemetría',
    'Driver before delivery' => 'Conductor Antes de la Entrega',
    'Driver after delivery' => 'Conductor Después de la Entrega',
    'Conductor antes de la entrega' => 'Conductor Antes de la Entrega',
    'Conductor después de la entrega' => 'Conductor Después de la Entrega'
];

while ($row = $result->fetch_assoc()) {
    $fechaMedicion = $row["fechaMedicion"];
    $fechaSubidaMedicion = $row["fechaSubidaMedicion"];
    $fuenteOriginal = trim($row["fuenteMedicion"]);

    if (empty($fechaMedicion)) {
        $row['fechaMedicion2'] = 'Sin Información';
    } else {
        $timeMedicion = strtotime(str_replace('/', '-', $fechaMedicion));
        $row['fechaMedicion2'] = $timeMedicion ? date('d/m/Y \a \l\a\s H:i \h\r\s\.', $timeMedicion) : 'Sin Información';
    }

    if (empty($fechaSubidaMedicion)) {
        $row['fechaSubidaMedicion2'] = 'Sin Información';
    } else {
        $timeSubida = strtotime(str_replace('/', '-', $fechaSubidaMedicion));
        $row['fechaSubidaMedicion2'] = $timeSubida ? date('d/m/Y \a \l\a\s H:i \h\r\s\.', $timeSubida) : 'Sin Información';
    }

    $row['fuenteMedicion2'] = isset($mapeoFuentes[$fuenteOriginal]) ? $mapeoFuentes[$fuenteOriginal] : 'Sin Información';

    // <-- Si la fuente corresponde a una recarga, aumentamos el contador
    if ($row['fuenteMedicion2'] === 'Conductor Después de la Entrega') {
        $totalRecargas++;
    }

    $datos[] = $row;
}

$stmt->close();
$conexion->close();

// Modificamos la estructura de retorno para inyectar la meta-data del contador
$respuesta = [
    "datos" => $datos,
    "totalRecargas" => $totalRecargas
];

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;