<?php
require '../sdo-funciones/conexion.php';
require '../sdo-funciones/funciones.php';

header('Content-Type: application/json');

$idUnidad = intval($_POST['idUnidad'] ?? 0);
$nombreJefatura = trim($_POST['nombreJefatura'] ?? '');

if ($idUnidad <= 0 || $nombreJefatura === '') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos inválidos']);
    exit;
}

$nombreJefatura = mb_strtoupper($nombreJefatura, 'UTF-8');

// Validar que no exista la jefatura con esa unidad
$stmt = $conexion->prepare("SELECT idJefatura FROM credenciales_rrhh_jefaturas WHERE idUnidad = ? AND UPPER(nomJefatura) = ?");
$stmt->bind_param("is", $idUnidad, $nombreJefatura);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'existe', 'mensaje' => 'La jefatura ya existe en esta unidad.']);
    exit;
}
$stmt->close();

// Insertar
$stmtInsert = $conexion->prepare("INSERT INTO credenciales_rrhh_jefaturas (idUnidad, nomJefatura) VALUES (?, ?)");
$stmtInsert->bind_param("is", $idUnidad, $nombreJefatura);

if ($stmtInsert->execute()) {
    echo json_encode(['status' => 'ok', 'id' => $stmtInsert->insert_id, 'nombre' => $nombreJefatura]);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al insertar jefatura']);
}

$stmtInsert->close();
$conexion->close();
