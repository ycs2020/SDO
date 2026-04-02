<?php
header('Content-Type: application/json');
include "../sdo-funciones/conexion.php";

$nombre = trim($_POST['nombre'] ?? '');

if ($nombre == "") {
    echo json_encode([
        'status' => 'error',
        'message' => 'El nombre no puede estar vacío.'
    ]);
    exit;
}

// Normalizar espaciado y capitalización
$nombreNormalizado = mb_convert_case($nombre, MB_CASE_UPPER, "UTF-8");

// Verificar duplicado (case insensitive)
$sqlCheck = $conexion->prepare("SELECT 1 FROM credenciales_rrhh_unidades WHERE LOWER(nomUnidad) = LOWER(?)");
$sqlCheck->bind_param("s", $nombreNormalizado);
$sqlCheck->execute();
$sqlCheck->store_result();

if ($sqlCheck->num_rows > 0) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'Ya existe una unidad con ese nombre.'
    ]);
    exit;
}

// Insertar nuevo registro
$sqlInsert = $conexion->prepare("INSERT INTO credenciales_rrhh_unidades (nomUnidad) VALUES (?)");
$sqlInsert->bind_param("s", $nombreNormalizado);

if ($sqlInsert->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Unidad creada correctamente.',
        'id' => $sqlInsert->insert_id,
        'nombre' => $nombreNormalizado
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al guardar la unidad.'
    ]);
}

$sqlCheck->close();
$sqlInsert->close();
$conexion->close();
