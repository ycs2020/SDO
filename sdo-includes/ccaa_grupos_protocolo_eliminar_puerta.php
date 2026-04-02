<?php
include '../sdo-funciones/conexion.php';

$idGrupoPuerta = $_POST['idGrupoPuerta'] ?? null;

if (!$idGrupoPuerta || !is_numeric($idGrupoPuerta)) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'ID inválido']);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM protoc_ccaa_grupo_puerta WHERE idGrupoPuerta = ?");
$stmt->bind_param("i", $idGrupoPuerta);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['mensaje' => 'Puerta eliminada exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['mensaje' => 'Error al eliminar la puerta']);
}

$stmt->close();
$conexion->close();
