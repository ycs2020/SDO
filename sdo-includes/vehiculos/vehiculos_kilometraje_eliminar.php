<?php
require_once("../../sdo-funciones/conexion.php");
header('Content-Type: application/json');

try {
    // Validamos que llegue el ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("ID de registro no proporcionado.");
    }

    $idKilometraje = intval($_POST['id']);

    // Preparamos la eliminación
    $sql = "DELETE FROM historial_kilometraje_moviles WHERE idKilometraje = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idKilometraje);

    if ($stmt->execute()) {
        // Verificamos si realmente se eliminó algo (por si el ID no existe)
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception("El registro ya no existe o no pudo ser eliminado.");
        }
    } else {
        throw new Exception("Error al ejecutar la eliminación: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>