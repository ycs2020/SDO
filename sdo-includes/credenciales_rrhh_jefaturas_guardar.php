<?php
header('Content-Type: application/json');
include "../sdo-funciones/conexion.php";

// Recuperar valores
$nombre = trim($_POST['nombre'] ?? '');
$idUnidad = (int)($_POST['unidad'] ?? 0);

// Validaciones
if ($idUnidad <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Debe seleccionar una unidad válida.'
    ]);
    exit;
}

if ($nombre == "") {
    echo json_encode([
        'status' => 'error',
        'message' => 'El nombre no puede estar vacío.'
    ]);
    exit;
}

// Formato estandarizado
//$nombreNormalizado = ucwords(strtolower($nombre));
$nombreNormalizado = mb_convert_case($nombre, MB_CASE_UPPER, "UTF-8");


// Validar duplicado dentro de esa unidad (case insensitive)
$sqlCheck = $conexion->prepare("SELECT 1 FROM credenciales_rrhh_jefaturas 
                                WHERE idUnidad = ? AND LOWER(nomJefatura) = LOWER(?)");
$sqlCheck->bind_param("is", $idUnidad, $nombreNormalizado);
$sqlCheck->execute();
$sqlCheck->store_result();

if ($sqlCheck->num_rows > 0) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'Ya existe una jefatura con ese nombre en esta unidad.'
    ]);
    exit;
}

// Insertar registro
$sqlInsert = $conexion->prepare("INSERT INTO credenciales_rrhh_jefaturas (idUnidad, nomJefatura) VALUES (?, ?)");
$sqlInsert->bind_param("is", $idUnidad, $nombreNormalizado);

if ($sqlInsert->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Jefatura creada correctamente.',
        'id' => $sqlInsert->insert_id,
        'nombre' => $nombreNormalizado,
        'unidad' => $idUnidad
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al guardar la jefatura.'
    ]);
}

$sqlCheck->close();
$sqlInsert->close();
$conexion->close();
