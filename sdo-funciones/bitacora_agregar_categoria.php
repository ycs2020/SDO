<?php
include '../sdo-funciones/conexion.php';
include '../sdo-funciones/funciones.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Santiago');

$idBitacora = obtenerIDBitacora();

// Validación
if (!isset($_POST['nomSubcategoria'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el nombre de la categoría']);
    exit;
}

$nombre = trim($_POST['nomSubcategoria']);

if (empty($nombre)) {
    http_response_code(422);
    echo json_encode(['error' => 'El nombre no puede estar vacío']);
    exit;
}

// Insertar en la tabla
$sql = "INSERT INTO subcategorias (nomSubcategoria, idCategoria) VALUES (?,?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en prepare: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("si", $nombre, $idBitacora);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al insertar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
