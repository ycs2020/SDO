<?php
include_once '../sdo-funciones/conexion.php';
date_default_timezone_set('America/Santiago');

// Recibir datos desde POST
$idOrdenVisador = $_POST['idot'] ?? null;
$nombreVisador = $_POST['usuario'] ?? null;

// Validaciones iniciales
if (!$nombreVisador) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el nombre del usuario."]);
    exit;
}

if (!$idOrdenVisador || $idOrdenVisador < 1) {
    echo json_encode(["status" => "error", "msg" => "No se recibió el ID de la OT."]);
    exit;
}


$conexion->begin_transaction();

try {
    // Preparar el UPDATE
    $stmt = $conexion->prepare("
        UPDATE trabajos_general 
        SET isVisado = 1, nombreVisado = ?, fechaVisado = NOW() 
        WHERE idTG = ? AND isVisado = 0
    ");

    $stmt->bind_param("ss", $nombreVisador, $idOrdenVisador);
    $stmt->execute();

    $filasAfectadas = $stmt->affected_rows;
    $stmt->close();

    if ($filasAfectadas > 0) {
        $conexion->commit();
        echo json_encode([
            "status" => "success",
            "msg" => "OT visada correctamente.",
        ]);
    } else {
        $conexion->rollback();
        echo json_encode([
            "status" => "warning",
            "msg" => "No se actualizó la OT: ya estaba visada o no existe.",
        ]);
    }

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode([
        "status" => "error",
        "msg" => "Error al visar OT: " . $e->getMessage()
    ]);
}

$conexion->close();
