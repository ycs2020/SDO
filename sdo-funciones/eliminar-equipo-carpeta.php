<?php
include_once 'conexion.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'ID inválido.'
    ]);
    exit;
}

$idEquipo = (int) $_POST['id'];

/**
 * Verifica recursivamente si un equipo tiene descendientes
 */
function tieneDescendientes(mysqli $conexion, int $idPadre): bool
{
    $sql = "SELECT id FROM equipamiento_hbqp WHERE idEquipoPadre = ? LIMIT 1";
    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        return true; // por seguridad, si falla la consulta, impedir eliminación
    }

    mysqli_stmt_bind_param($stmt, "i", $idPadre);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultado)) {
        mysqli_stmt_close($stmt);
        return true; // tiene al menos un hijo directo
    }

    mysqli_stmt_close($stmt);
    return false;
}

// Verificar si tiene hijos
if (tieneDescendientes($conexion, $idEquipo)) {
    echo json_encode([
        'status' => false,
        'message' => 'No se puede eliminar el equipo porque tiene equipos hijos asociados.'
    ]);
    exit;
}

// Si no tiene hijos, recién eliminar
$sqlDelete = "DELETE FROM equipamiento_hbqp WHERE id = ?";
$stmtDelete = mysqli_prepare($conexion, $sqlDelete);

if (!$stmtDelete) {
    echo json_encode([
        'status' => false,
        'message' => 'Error al preparar la eliminación.'
    ]);
    exit;
}

mysqli_stmt_bind_param($stmtDelete, "i", $idEquipo);
$resDelete = mysqli_stmt_execute($stmtDelete);

if ($resDelete) {
    echo json_encode([
        'status' => true,
        'message' => 'Equipo eliminado correctamente.'
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'No se pudo eliminar el equipo.'
    ]);
}

mysqli_stmt_close($stmtDelete);