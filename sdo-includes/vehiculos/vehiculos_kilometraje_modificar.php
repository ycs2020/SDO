<?php
require_once("../../sdo-funciones/conexion.php");
header('Content-Type: application/json');

try {
    // Los nombres aquí deben coincidir con los "name" de tu formulario HTML
    $idKilometraje   = (int)$_POST['idKilometraje'];
    $fechaRegistro   = $_POST['fechaRegistro'];
    $cantKilometraje = (int)$_POST['cantKilometraje'];

    if ($idKilometraje <= 0) {
        throw new Exception("ID de registro no válido.");
    }

    $sql = "UPDATE historial_kilometraje_moviles 
            SET fechaRegistro = ?, cantKilometraje = ? 
            WHERE idKilometraje = ?";

    $stmt = $conexion->prepare($sql);
    // "sii" -> string (fecha), integer (kms), integer (id)
    $stmt->bind_param("sii", $fechaRegistro, $cantKilometraje, $idKilometraje);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0 || $stmt->errno === 0) {
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception("No se realizaron cambios en el registro.");
        }
    } else {
        throw new Exception("Error en la base de datos: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}