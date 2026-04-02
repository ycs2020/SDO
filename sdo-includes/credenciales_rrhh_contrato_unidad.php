<?php
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$nombreUnidad = trim($_POST['nombreUnidad'] ?? '');

if ($nombreUnidad === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Nombre de unidad vacío']);
    exit;
}

$nombreUnidad = mb_strtoupper($nombreUnidad, 'UTF-8');

// Verificar si ya existe (exactamente igual)
$stmt = $conexion->prepare("SELECT idUnidad FROM credenciales_rrhh_unidades WHERE UPPER(nomUnidad) = ?");
$stmt->bind_param("s", $nombreUnidad);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'existe', 'mensaje' => 'La unidad ya existe.']);
    exit;
}
$stmt->close();

// Insertar si no existe
$stmtInsert = $conexion->prepare("INSERT INTO credenciales_rrhh_unidades (nomUnidad) VALUES (?)");
$stmtInsert->bind_param("s", $nombreUnidad);

if ($stmtInsert->execute()) {
    echo json_encode(['status' => 'ok', 'id' => $stmtInsert->insert_id, 'nombre' => $nombreUnidad]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al insertar unidad']);
}

$stmtInsert->close();
$conexion->close();
