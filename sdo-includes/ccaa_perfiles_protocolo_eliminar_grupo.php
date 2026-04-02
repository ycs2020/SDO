<?php
include '../sdo-funciones/conexion.php';

$idPerfilGrupo = $_POST['idPerfilGrupo'] ?? null;

if (!$idPerfilGrupo || !is_numeric($idPerfilGrupo)) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'ID inválido']);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM protoc_ccaa_perfil_grupo WHERE idPerfilGrupo = ?");
$stmt->bind_param("i", $idPerfilGrupo);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['mensaje' => 'Grupo Eliminado Exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['mensaje' => 'Error al eliminar el grupo']);
}

$stmt->close();
$conexion->close();
