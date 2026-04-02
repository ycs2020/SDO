<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

// Validar
if (!isset($_POST['idSubcategoria'], $_POST['observacionBitacora'], $_POST['tecnicoBitacora'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos']);
    exit;
}

$idSubcategoria      = intval($_POST['idSubcategoria']);
$observacionBitacora = trim($_POST['observacionBitacora']);
$tecnicoBitacora     = trim($_POST['tecnicoBitacora']);
$fechaBitacora       = date('Y-m-d H:i:s'); // Fecha actual del servidor

if ($idSubcategoria <= 0 || empty($observacionBitacora) || empty($tecnicoBitacora)) {
    http_response_code(422);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

try {
    $sql = "INSERT INTO bitacora (observacionBitacora, fechaBitacora, tecnicoBitacora, idSubcategoria)
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $observacionBitacora, $fechaBitacora, $tecnicoBitacora, $idSubcategoria);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al insertar: ' . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
}
