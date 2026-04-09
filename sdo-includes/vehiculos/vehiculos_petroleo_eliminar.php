<?php
include_once '../../sdo-funciones/conexion.php';
header('Content-Type: application/json');

// Validar que el ID sea enviado y sea numérico
$idPetroleo = $_POST['idPetroleo'] ?? null;

if (!$idPetroleo || !is_numeric($idPetroleo)) {
    echo json_encode(['success' => false, 'message' => 'ID de registro no válido.']);
    exit;
}

// Preparar la sentencia para evitar SQL Injection
$sql = "DELETE FROM historial_petroleo_moviles WHERE idPetroleo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $idPetroleo);

if ($stmt->execute()) {
    // Verificar si realmente se eliminó una fila
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'El registro de petróleo ha sido eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el registro o ya fue eliminado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();