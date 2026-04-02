<?php
require '../sdo-funciones/conexion.php';
header('Content-Type: application/json');

$nombre = trim(mb_strtoupper($_POST['nombre'] ?? ''));

if ($nombre === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Nombre vacío']);
    exit;
}

$stmt = $conexion->prepare("SELECT idCalidad FROM credenciales_rrhh_calidades WHERE nomCalidad = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'existe']);
    exit;
}

$stmt->close();

$stmt = $conexion->prepare("INSERT INTO credenciales_rrhh_calidades (nomCalidad) VALUES (?)");
$stmt->bind_param("s", $nombre);
if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'id' => $stmt->insert_id]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo guardar']);
}
